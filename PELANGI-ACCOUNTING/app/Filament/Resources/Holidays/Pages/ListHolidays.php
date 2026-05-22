<?php

namespace App\Filament\Resources\Holidays\Pages;

use App\Filament\Resources\Holidays\HolidayResource;
use App\Models\Holiday;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Support\Facades\Http;

class ListHolidays extends ListRecords
{
    protected static string $resource = HolidayResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('syncHolidays')
                ->label(__('Sync Holidays'))
                ->icon('heroicon-o-arrow-path')
                ->color('info')
                ->requiresConfirmation()
                ->action(fn () => $this->syncHolidays()),
            CreateAction::make(),
        ];
    }

    private function syncHolidays()
    {
        $companyId = session('selected_company_id');
        if (!$companyId || $companyId === 'all') {
            Notification::make()->title(__('Please select a company first'))->danger()->send();
            return;
        }

        $years = [now()->year, now()->year + 1];
        $count = 0;

        foreach ($years as $year) {
            try {
                $response = Http::get("https://libur.deno.dev/api?year={$year}");
                
                if ($response->successful()) {
                    $holidays = $response->json();
                    
                    foreach ($holidays as $data) {
                        $isCutiBersama = str_contains(strtoupper($data['name']), 'CUTI BERSAMA');
                        
                        Holiday::updateOrCreate(
                            [
                                'company_id' => $companyId,
                                'date' => $data['date'],
                            ],
                            [
                                'name' => $data['name'],
                                'is_cuti_bersama' => $isCutiBersama,
                            ]
                        );
                        $count++;
                    }
                }
            } catch (\Exception $e) {
                // Log or handle error
            }
        }

        Notification::make()
            ->title(__('Sync Successful'))
            ->body(__('Successfully imported :count holidays', ['count' => $count]))
            ->success()
            ->send();
    }
}
