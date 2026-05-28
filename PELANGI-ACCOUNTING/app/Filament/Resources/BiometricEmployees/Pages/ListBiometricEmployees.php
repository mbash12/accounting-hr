<?php

namespace App\Filament\Resources\BiometricEmployees\Pages;

use App\Filament\Resources\BiometricEmployees\BiometricEmployeeResource;
use Filament\Resources\Pages\ListRecords;

class ListBiometricEmployees extends ListRecords
{
    protected static string $resource = BiometricEmployeeResource::class;
}
