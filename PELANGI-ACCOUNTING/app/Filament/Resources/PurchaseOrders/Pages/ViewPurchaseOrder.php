<?php

namespace App\Filament\Resources\PurchaseOrders\Pages;

use App\Filament\Resources\PurchaseOrders\PurchaseOrderResource;
use App\Models\PurchaseOrder;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
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
            ActionGroup::make([
                Action::make('printA4')
                    ->label('A4 - Portrait')
                    ->icon('heroicon-o-document')
                    ->url(url()->current() . '?print=1&paper=a4')
                    ->openUrlInNewTab(),
                Action::make('printA5')
                    ->label('A5 - Landscape')
                    ->icon('heroicon-o-document')
                    ->url(url()->current() . '?print=1&paper=a5')
                    ->openUrlInNewTab(),
            ])
                ->label('Print')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->button(),
            EditAction::make(),
        ];
    }
}
