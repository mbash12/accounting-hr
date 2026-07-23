<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use App\Filament\Forms\Components\NumberInput;
use App\Services\AdditionalChargesHelper;
use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use App\Models\Tax;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Validation\ValidationException;

class EditPurchaseOrder extends EditRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    public function save(...$args): void
    {
        try {
            parent::save(...$args);
        } catch (ValidationException $e) {
            $message = collect($e->errors())->flatten()->first() ?? __('Validation failed.');

            Notification::make()
                ->title(__('Save Failed'))
                ->body($message)
                ->danger()
                ->send();

            throw $e;
        } catch (\Exception $e) {
            Notification::make()
                ->title(__('Save Failed'))
                ->body($e->getMessage())
                ->danger()
                ->send();

            throw $e;
        }
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $discountPercentage = (float) ($data['discount_percentage'] ?? 0);
        $otherCharges = AdditionalChargesHelper::resolveAmount(
            $data['otherCharges'] ?? null,
            $data['other_charges'] ?? 0,
        );
        $items = $data['items'] ?? [];

        $subtotal = 0.0;
        $taxTotal = 0.0;

        foreach ($items as $index => $item) {
            $quantity = NumberInput::parseToFloat($item['quantity'] ?? 0);
            $unitPrice = NumberInput::parseToFloat($item['unit_price'] ?? 0);
            $lineTotal = $quantity * $unitPrice;

            $data['items'][$index]['quantity'] = $quantity;
            $data['items'][$index]['unit_price'] = $unitPrice;
            $data['items'][$index]['total'] = $lineTotal;

            $subtotal += $lineTotal;

            $taxId = $item['tax_id'] ?? null;
            if ($taxId) {
                $tax = Tax::find($taxId);
                if ($tax && isset($tax->tax_percentage)) {
                    $lineDiscount = $lineTotal * ($discountPercentage / 100);
                    $taxBase = $lineTotal - $lineDiscount;
                    $taxTotal += $taxBase * ((float) $tax->tax_percentage) / 100;
                }
            }
        }

        $discountAmount = $subtotal * ($discountPercentage / 100);
        $total = $subtotal - $discountAmount + $otherCharges + $taxTotal;

        // Set calculated totals
        $data['subtotal'] = $subtotal;
        $data['discount'] = $discountAmount;
        $data['tax_amount'] = $taxTotal;
        $data['total_amount'] = $total;
        $data['other_charges'] = $otherCharges;

        return $data;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('warning')
                ->authorize('approve')
                ->visible(fn (PurchaseOrder $record): bool => $record->status === 'draft' && auth()->user()?->can('approve', $record))
                ->form([
                    Textarea::make('comment')
                        ->label('Comment')
                        ->rows(3)
                        ->placeholder('Optional approval comment'),
                ])
                ->requiresConfirmation()
                ->modalHeading('Approve Purchase Order')
                ->modalDescription('Are you sure you want to approve this purchase order?')
                ->modalSubmitActionLabel('Yes, Approve')
                ->action(function (PurchaseOrder $record, array $data) {
                    $result = $record->approveWithWisma($data['comment'] ?? null);

                    if (empty($result['skipped']) && !($result['success'] ?? false)) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Purchase Order Failed to Approve')
                            ->body("Purchase Order {$record->purchase_order_no} failed to sync to Wisma. Approval blocked.")
                            ->send();

                        return;
                    }

                    $body = "Purchase Order {$record->purchase_order_no} has been approved.";
                    if (!empty($result['skipped'])) {
                        $body .= " (Local approval only — no Wisma reference)";
                    }

                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Purchase Order Approved')
                        ->body($body)
                        ->send();
                }),
            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->authorize('approve')
                ->visible(fn (PurchaseOrder $record): bool => $record->status === 'draft' && auth()->user()?->can('approve', $record))
                ->form([
                    Textarea::make('comment')
                        ->label('Comment')
                        ->rows(3)
                        ->placeholder('Optional rejection comment'),
                ])
                ->requiresConfirmation()
                ->modalHeading('Reject Purchase Order')
                ->modalDescription('Are you sure you want to reject this purchase order?')
                ->modalSubmitActionLabel('Yes, Reject')
                ->action(function (PurchaseOrder $record, array $data) {
                    $result = $record->rejectWithWisma($data['comment'] ?? null);

                    if (empty($result['skipped']) && !($result['success'] ?? false)) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Purchase Order Failed to Reject')
                            ->body("Purchase Order {$record->purchase_order_no} failed to sync to Wisma. Rejection blocked.")
                            ->send();

                        return;
                    }

                    $body = "Purchase Order {$record->purchase_order_no} has been rejected.";
                    if (!empty($result['skipped'])) {
                        $body .= " (Local rejection only — no Wisma reference)";
                    }

                    \Filament\Notifications\Notification::make()
                        ->danger()
                        ->title('Purchase Order Rejected')
                        ->body($body)
                        ->send();
                }),
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        if ($this->record instanceof PurchaseOrder) {
            $this->record->refresh();

            $this->record->syncOtherChargesTotal();
            if ($this->record->items()->exists()) {
                $this->record->recalculateTotalsFromItems();
            }
            $this->record->saveQuietly();
        }
    }
}
