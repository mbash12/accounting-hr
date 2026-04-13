<?php

namespace App\Filament\Resources\ReceivableLists\Pages;

use App\Filament\Resources\ReceivableLists\ReceivableListResource;
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

class ViewReceivableDetail extends ViewRecord implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = ReceivableListResource::class;

    protected string $view = 'filament.resources.receivable-lists.pages.view-receivable-detail';

    public function getTitle(): string
    {
        return __('Detail Account Receivable');
    }

    // public function getReceivableSummary(): array
    // {
    //     $selectedCompanyId = session('selected_company_id');
    //     $customerId = $this->record->id;

    //     $query = \App\Models\SalesInvoice::query()
    //         ->where('customer_id', $customerId)
    //         ->whereIn('status', ['draft', 'sent', 'overdue', 'partially_paid'])
    //         ->where(function ($q) {
    //             $q->where('outstanding_amount', '>', 0)
    //                 ->orWhere('is_paid', false);
    //         });

    //     if ($selectedCompanyId && $selectedCompanyId !== 'all') {
    //         $query->where(function ($q) use ($selectedCompanyId) {
    //             $q->where('company_id', $selectedCompanyId)
    //                 ->orWhereNull('company_id');
    //         });
    //     }

    //     $summary = $query->selectRaw('
    //             COALESCE(SUM(total_amount), 0) as total_receivable,
    //             COALESCE(SUM(paid_amount), 0) as total_paid,
    //             COALESCE(SUM(outstanding_amount), 0) as total_outstanding
    //         ')
    //         ->first();

    //     return [
    //         'total_receivable' => $summary->total_receivable ?? 0,
    //         'total_paid' => $summary->total_paid ?? 0,
    //         'total_outstanding' => $summary->total_outstanding ?? 0,
    //     ];
    // }

    public function table(Table $table): Table
    {
        $today = now();
        $selectedCompanyId = session('selected_company_id');
        $customerId = $this->record->id;

        return $table
            ->query(
                \App\Models\SalesInvoice::query()
                    ->where('customer_id', $customerId)
                    ->whereIn('status', ['posted', 'sent', 'overdue', 'partially_paid'])
                    ->where(function ($q) {
                        $q->where('outstanding_amount', '>', 0)
                            ->orWhere('is_paid', false);
                    })
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
                    ->label(__('< 30 HARI'))
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
                    ->label(__('30 - 60 HARI'))
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
                    ->label(__('60 - 90 HARI'))
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
                    ->label(__('> 90 HARI'))
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
                        ->modalHeading(__('Hapus Piutang Usaha'))
                        ->modalDescription(__('Apakah Anda yakin ingin menghapus sisa piutang usaha ini? Tindakan ini akan membuat jurnal otomatis dan melunasi sisa tagihan.'))
                        ->action(function ($record) {
                            try {
                                $amount = $record->outstanding_amount;

                                if ($amount <= 0) {
                                    throw new \InvalidArgumentException(__('Amount must be greater than zero'));
                                }

                                $receivableAccountId = \App\Models\Account::where('is_header', false)
                                    ->where('is_active', true)
                                    ->where(function ($q) {
                                        $q->where('code', 'like', '11%')
                                            ->orWhere('name', 'like', '%Piutang Usaha%');
                                    })->orderBy('code')->value('id');

                                $journalEntry = $record->journalEntry;
                                $writeOffAccountId = null;
                                
                                if ($journalEntry) {
                                    $contraItem = $journalEntry->items()
                                        ->where('account_id', '!=', $receivableAccountId)
                                        ->orderByDesc('credit')
                                        ->first();
                                    $writeOffAccountId = $contraItem?->account_id;
                                }

                                if (!$writeOffAccountId) {
                                    $writeOffAccountId = \App\Models\Account::where('is_header', false)
                                        ->where('is_active', true)
                                        ->where(function ($q) {
                                            $q->where('code', 'like', '5%')
                                                ->orWhere('name', 'like', '%Penghapusan%')
                                                ->orWhere('name', 'like', '%Bad Debt%');
                                        })->orderBy('code')->value('id');
                                }

                                if (!$receivableAccountId || !$writeOffAccountId) {
                                    throw new \Exception(__('Gagal mendeteksi akun Piutang atau akun Write Off secara otomatis.'));
                                }

                                $companyId = $record->company_id ?? session('selected_company_id');
                                $codeService = app(CodeGeneratorService::class);
                                
                                $entryNumber = $codeService->generateCode('journal_entry', $companyId);
                                
                                $journalEntry = \App\Models\JournalEntry::create([
                                    'entry_number' => $entryNumber,
                                    'date' => now(),
                                    'reference_no' => $entryNumber, 
                                    'description' => __('Account Receivable Write Off, for invoice :invoice', ['invoice' => $record->invoice_number]),
                                    'amount' => $amount,
                                    'total_amount' => $amount,
                                    'status' => 'posted',
                                    'is_posted' => true,
                                    'sub_module' => 'penghapusan_piutang',
                                    'reference_type' => \App\Models\SalesInvoice::class,
                                    'reference_id' => $record->id,
                                    'cash_bank_transaction_id' => null,
                                    'department_id' => 1, // Default department
                                    'posted_by_user_id' => Auth::id(),
                                    'posted_at' => now(),
                                    'company_id' => $companyId,
                                    'created_by_user_id' => Auth::id(),
                                    'updated_by_user_id' => Auth::id(),
                                ]);

                                // Create journal entry items
                                \App\Models\JournalEntryItem::create([
                                    'journal_entry_id' => $journalEntry->id,
                                    'account_id' => $writeOffAccountId, 
                                    'debit' => $amount,
                                    'credit' => 0,
                                    'notes' => 'Account Receivable Write Off',
                                    'cost_center_id' => 1,
                                    'department_id' => 1,
                                ]);

                                \App\Models\JournalEntryItem::create([
                                    'journal_entry_id' => $journalEntry->id,
                                    'account_id' => $receivableAccountId, 
                                    'debit' => 0,
                                    'credit' => $amount,
                                    'notes' => 'Account Receivable Write Off',
                                    'cost_center_id' => 1,
                                    'department_id' => 1,
                                ]);

                                // Update invoice
                                $record->outstanding_amount = 0;
                                $record->paid_amount += $amount;
                                $record->is_paid = true;
                                $record->status = 'paid';
                                $record->save();

                                \Filament\Notifications\Notification::make()
                                    ->title(__('Write Off Berhasil'))
                                    ->success()
                                    ->send();

                            } catch (\Exception $e) {
                                \Filament\Notifications\Notification::make()
                                    ->title(__('Write Off Gagal'))
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                    \Filament\Actions\Action::make('detail')
                        ->label(__('Detail'))
                        ->icon('heroicon-o-magnifying-glass')
                        ->url(fn ($record) => \App\Filament\Resources\SalesInvoices\SalesInvoiceResource::getUrl('view', ['record' => $record])),
                ]),
            ])
            ->defaultSort('due_date', 'asc');
    }
}

