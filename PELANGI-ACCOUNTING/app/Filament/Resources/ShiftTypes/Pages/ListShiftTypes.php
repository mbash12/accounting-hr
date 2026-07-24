<?php

namespace App\Filament\Resources\ShiftTypes\Pages;

use App\Filament\Resources\ShiftTypes\ShiftTypeResource;
use App\Models\ShiftType;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListShiftTypes extends ListRecords
{
    protected static string $resource = ShiftTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateDefault')
                ->label('Generate')
                ->icon('heroicon-o-sparkles')
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Generate pre-coded shift types')
                ->modalDescription('This will create the standard shift codes (R, 0, 1, 2, 3, RS1, RS2, EQ) for the current company. Existing codes will be skipped.')
                ->modalSubmitActionLabel('Generate')
                ->action(function () {
                    $companyId = session('selected_company_id') && session('selected_company_id') !== 'all'
                        ? (int) session('selected_company_id') : null;

                    $types = [
                        ['code' => 'R',   'name' => 'REGULER',          'start_time' => '08:00', 'end_time' => '17:00', 'color' => '#cfe2ff', 'is_off' => false],
                        ['code' => '1',   'name' => 'PAGI (1)',         'start_time' => '07:00', 'end_time' => '16:00', 'color' => '#cfe2ff', 'is_off' => false],
                        ['code' => '2',   'name' => 'SIANG (2)',        'start_time' => '14:00', 'end_time' => '23:00', 'color' => '#fff3cd', 'is_off' => false],
                        ['code' => '3',   'name' => 'MALAM (3)',        'start_time' => '22:00', 'end_time' => '07:00', 'color' => '#20c997', 'is_off' => false],
                        ['code' => 'RS1', 'name' => 'REGULER SHIFT 1',  'start_time' => '09:00', 'end_time' => '17:00', 'color' => '#d1e7dd', 'is_off' => false],
                        ['code' => 'RS2', 'name' => 'REGULER SHIFT 2',  'start_time' => '09:00', 'end_time' => '15:00', 'color' => '#a3cfbb', 'is_off' => false],
                        ['code' => '0',   'name' => 'OFF',              'start_time' => null,    'end_time' => null,    'color' => '#dc3545', 'text_color' => '#ffffff', 'is_off' => true],
                        ['code' => 'EQ',  'name' => 'EXTRA OFF / EQ',   'start_time' => null,    'end_time' => null,    'color' => '#f8d7da', 'is_off' => true],
                    ];

                    foreach ($types as $t) {
                        ShiftType::updateOrCreate(
                            ['company_id' => $companyId, 'code' => $t['code']],
                            array_merge($t, [
                                'text_color'  => $t['text_color'] ?? '#000000',
                                'is_active'   => true,
                                'description' => $t['name'],
                            ])
                        );
                    }

                    Notification::make()
                        ->title('Shift types generated')
                        ->body('Pre-coded shift types are ready for the current company.')
                        ->success()
                        ->send();

                }),
            CreateAction::make(),
        ];
    }
}
