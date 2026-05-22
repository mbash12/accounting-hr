<?php

namespace App\Filament\Resources\PayableLists\Pages;

use App\Filament\Resources\PayableLists\PayableListResource;
use App\Filament\Forms\Components\RoundedIntegerMoneyInput;
use App\Filament\Forms\Components\NumberInput;
use App\Services\CodeGeneratorService;
use Filament\Actions\ActionGroup;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ViewPayableDetail extends ViewRecord implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = PayableListResource::class;

    protected string $view = 'filament.resources.payable-lists.pages.view-payable-detail';

    public function getTitle(): string
    {
        return __('Payable Detail');
    }

    public function table(Table $table): Table
    {
        $today = now();
        $selectedCompanyId = session('selected_company_id');
        $supplierId = $this->record->id;

        return $table
            ->query(
                \App\Models\PurchaseInvoice::query()
                    ->where('supplier_id', $supplierId)
                    ->whereNotIn('status', ['draft','paid', 'cancelled'])
                    ->whereRaw('CAST(outstanding_amount AS DECIMAL) > 0')
                    ->when($selectedCompanyId && $selectedCompanyId !== 'all', function ($q) use ($selectedCompanyId) {
                        $q->where(function ($query) use ($selectedCompanyId) {
                            $query->where('company_id', $selectedCompanyId)
                                ->orWhereNull('company_id');
                        });
                    })
                    ->orderBy('due_date', 'asc')
            )
            ->columns([
                TextColumn::make('date')
                    ->label(__('Date'))
                    ->date('M d, Y')
                    ->sortable(),
                TextColumn::make('due_date')
                    ->label(__('Due Date'))
                    ->date('M d, Y')
                    ->sortable()
                    ->color(fn ($record) => $record->due_date < now() ? 'danger' : null)
                    ->hidden(),
                TextColumn::make('invoice_number')
                    ->label(__('Invoice No.'))
                    ->searchable()
                    ->sortable(),
                TextColumn::make('aging_less_30')
                    ->label(__('< 30 Days'))
                    ->formatStateUsing(function ($state) {
                        return 'IDR ' . number_format($state, 0, ',', '.');
                    })
                    ->getStateUsing(function ($record) use ($today) {
                        if ($record->due_date >= $today) {
                            // Not yet due - goes to < 30 days
                            return $record->outstanding_amount;
                        }
                        $daysPastDue = $today->diffInDays($record->due_date);
                        if ($daysPastDue < 30) {
                            return $record->outstanding_amount;
                        }
                        return 0;
                    }),
                TextColumn::make('aging_30_60')
                    ->label(__('30 - 60 Days'))
                    ->formatStateUsing(function ($state) {
                        return 'IDR ' . number_format($state, 0, ',', '.');
                    })
                    ->getStateUsing(function ($record) use ($today) {
                        $daysPastDue = $today->diffInDays($record->due_date);
                        if ($daysPastDue >= 30 && $daysPastDue < 60) {
                            return $record->outstanding_amount;
                        }
                        return 0;
                    }),
                TextColumn::make('aging_60_90')
                    ->label(__('60 - 90 Days'))
                    ->formatStateUsing(function ($state) {
                        return 'IDR ' . number_format($state, 0, ',', '.');
                    })
                    ->getStateUsing(function ($record) use ($today) {
                        $daysPastDue = $today->diffInDays($record->due_date);
                        if ($daysPastDue >= 60 && $daysPastDue < 90) {
                            return $record->outstanding_amount;
                        }
                        return 0;
                    }),
                TextColumn::make('aging_over_90')
                    ->label(__('> 90 Days'))
                    ->formatStateUsing(function ($state) {
                        return 'IDR ' . number_format($state, 0, ',', '.');
                    })
                    ->getStateUsing(function ($record) use ($today) {
                        $daysPastDue = $today->diffInDays($record->due_date);
                        if ($daysPastDue >= 90) {
                            return $record->outstanding_amount;
                        }
                        return 0;
                    }),
            ])
            ->recordActions([
                ActionGroup::make([
                    \App\Filament\Actions\ViewJournalVoucherAction::make(),
                    \Filament\Actions\Action::make('write_off')
                        ->label('Write Off')
                        ->icon('heroicon-o-x-mark')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->modalHeading(__('Delete Payable'))
                        ->modalDescription(__('Are you sure you want to delete the remaining payable? This action will create an automatic journal entry and settle the remaining balance.'))
                        ->action(function ($record) {
                            try {
                                $amount = $record->outstanding_amount;

                                if ($amount <= 0) {
                                    throw new \InvalidArgumentException(__('Amount must be greater than zero'));
                                }

                                $payableAccountId = \App\Models\Account::where('is_header', false)
                                    ->where('is_active', true)
                                    ->where(function ($q) {
                                        $q->where('code', 'like', '21%')
                                            ->orWhere('name', 'like', '%Accounts Payable%');
                                    })->orderBy('code')->value('id');

                                $journalEntry = $record->journalEntry;
                                $writeOffAccountId = null;
                                
                                if ($journalEntry) {
                                    $contraItem = $journalEntry->items()
                                        ->where('account_id', '!=', $payableAccountId)
                                        ->orderByDesc('debit')
                                        ->first();
                                    $writeOffAccountId = $contraItem?->account_id;
                                }

                                if (!$writeOffAccountId) {
                                    $writeOffAccountId = \App\Models\Account::where('is_header', false)
                                        ->where('is_active', true)
                                        ->where(function ($q) {
                                            $q->where('code', 'like', '5%')
                                                ->orWhere('name', 'like', '%Bad Debt%')
                                                ->orWhere('name', 'like', '%Write Off%');
                                        })->orderBy('code')->value('id');
                                }

                                if (!$payableAccountId || !$writeOffAccountId) {
                                    throw new \Exception(__('Failed to automatically detect Payable or Write Off account.'));
                                }

                                // Create journal entry for write off
                                $companyId = $record->company_id ?? session('selected_company_id');
                                $codeService = app(CodeGeneratorService::class);
                                
                                $entryNumber = $codeService->generateCode('journal_entry', $companyId);
                                
                                $journalEntry = \App\Models\JournalEntry::create([
                                    'entry_number' => $entryNumber,
                                    'date' => now(),
                                    'reference_no' => $entryNumber, 
                                    'description' => __('Account Payable Write Off, for invoice :invoice', ['invoice' => $record->invoice_number]),
                                    'amount' => $amount,
                                    'total_amount' => $amount,
                                    'status' => 'posted',
                                    'is_posted' => true,
                                    'sub_module' => 'penghapusan_utang',
                                    'reference_type' => \App\Models\PurchaseInvoice::class,
                                    'reference_id' => $record->id,
                                    'cash_bank_transaction_id' => null,
                                    'department_id' => 1, // Default department
                                    'posted_by_user_id' => Auth::id(),
                                    'posted_at' => now(),
                                    'company_id' => $companyId,
                                    'created_by_user_id' => Auth::id(),
                                    'updated_by_user_id' => Auth::id(),
                                ]);

                                \App\Models\JournalEntryItem::create([
                                    'journal_entry_id' => $journalEntry->id,
                                    'account_id' => $payableAccountId,
                                    'debit' => $amount,
                                    'credit' => 0,
                                    'notes' => 'Account Payable Write Off',
                                    'cost_center_id' => 1,
                                    'department_id' => 1,
                                ]);

                                \App\Models\JournalEntryItem::create([
                                    'journal_entry_id' => $journalEntry->id,
                                    'account_id' => $writeOffAccountId, 
                                    'debit' => 0,
                                    'credit' => $amount,
                                    'notes' => 'Account Payable Write Off',
                                    'cost_center_id' => 1,
                                    'department_id' => 1,
                                ]);

                                $record->outstanding_amount = 0;
                                $record->paid_amount += $amount;
                                $record->is_paid = true;
                                $record->status = 'paid';
                                $record->save();

                                \Filament\Notifications\Notification::make()
                                    ->title(__('Write Off Successful'))
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                \Filament\Notifications\Notification::make()
                                    ->title(__('Write Off Failed'))
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                    \Filament\Actions\Action::make('detail')
                        ->label(__('Details'))
                        ->icon('heroicon-o-magnifying-glass')
                        ->url(fn ($record) => \App\Filament\Resources\PurchaseInvoices\PurchaseInvoiceResource::getUrl('view', ['record' => $record])),
                ]),
            ])
            ->defaultSort('due_date', 'asc');
    }
}



