<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Pages\ViewRecord;

class ViewPurchaseOrder extends ViewRecord
{
    protected static string $resource = PurchaseOrderResource::class;

    protected string $view = 'filament.pages.purchase-order-view';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('warning')
                ->visible(fn (PurchaseOrder $record): bool => $record->status === 'draft')
                ->requiresConfirmation()
                ->modalHeading('Approve Purchase Order')
                ->modalDescription('Are you sure you want to approve this purchase order?')
                ->modalSubmitActionLabel('Yes, Approve')
                ->action(function (PurchaseOrder $record) {
                    $result = $record->approveWithWisma();

                    if (!($result['success'] ?? false)) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Purchase Order Failed to Approve')
                            ->body("Purchase Order {$record->purchase_order_no} gagal diapprove ke Wisma, status lokal tidak diubah.")
                            ->send();

                        return;
                    }

                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Purchase Order Approved')
                        ->body("Purchase Order {$record->purchase_order_no} has been approved.")
                        ->send();
                }),
            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->visible(fn (PurchaseOrder $record): bool => $record->status === 'draft')
                ->form([
                    Textarea::make('comment')
                        ->label('Comment')
                        ->rows(3)
                        ->placeholder('Optional rejection comment for Wisma'),
                ])
                ->requiresConfirmation()
                ->modalHeading('Reject Purchase Order')
                ->modalDescription('Are you sure you want to reject this purchase order?')
                ->modalSubmitActionLabel('Yes, Reject')
                ->action(function (PurchaseOrder $record, array $data) {
                    $result = $record->rejectWithWisma($data['comment'] ?? null);

                    if (!($result['success'] ?? false)) {
                        \Filament\Notifications\Notification::make()
                            ->danger()
                            ->title('Purchase Order Failed to Reject')
                            ->body("Purchase Order {$record->purchase_order_no} gagal direject ke Wisma, status lokal tidak diubah.")
                            ->send();

                        return;
                    }

                    \Filament\Notifications\Notification::make()
                        ->danger()
                        ->title('Purchase Order Rejected')
                        ->body("Purchase Order {$record->purchase_order_no} has been rejected.")
                        ->send();
                }),
            Action::make('print')
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(url()->current() . '?print=1')
                ->openUrlInNewTab(),
            EditAction::make(),
        ];
    }
}
