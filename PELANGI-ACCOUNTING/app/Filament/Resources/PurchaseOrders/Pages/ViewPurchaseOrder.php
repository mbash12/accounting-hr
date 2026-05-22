<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
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
                    $record->update(['status' => 'approved']);

                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Purchase Order Approved')
                        ->body("Purchase Order {$record->purchase_order_no} has been approved.")
                        ->send();
                }),
            Action::make('post')
                ->label('Post')
                ->icon('heroicon-o-check-badge')
                ->color('success')
                ->visible(fn (PurchaseOrder $record): bool => $record->status === 'approved')
                ->requiresConfirmation()
                ->modalHeading('Post Purchase Order')
                ->modalDescription('Are you sure you want to post this purchase order? This will create journal entries.')
                ->modalSubmitActionLabel('Yes, Post')
                ->action(function (PurchaseOrder $record) {
                    $record->update(['status' => 'posted']);

                    \Filament\Notifications\Notification::make()
                        ->success()
                        ->title('Purchase Order Posted')
                        ->body("Purchase Order {$record->purchase_order_no} has been posted.")
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