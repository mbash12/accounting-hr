<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Resources\Pages\CreateRecord;

use App\Models\Employee;
use App\Services\GeminiService;
use Pgvector\Laravel\Vector;
use Illuminate\Support\Facades\DB;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function afterCreate(): void
    {
        $employee = $this->record;

        if (! empty($employee->foto)) {
            $photoPath = storage_path('app/public/' . $employee->foto);

            if (file_exists($photoPath)) {
                try {
                    DB::beginTransaction();
                    $vector = GeminiService::generateFaceVectorWithVertexAI($photoPath);
                    $employee->foto_vector = new Vector($vector);
                    $employee->save();
                    DB::commit();
                } catch (\Exception $e) {
                    DB::rollback();

                    \Illuminate\Support\Facades\Log::error('Face vector generation failed: ' . $e->getMessage(), [
                        'employee_id' => $employee->id,
                        'photo_path' => $photoPath,
                        'exception' => $e,
                    ]);
                }
            }
        }
    }
}