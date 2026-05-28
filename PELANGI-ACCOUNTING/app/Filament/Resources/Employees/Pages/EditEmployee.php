<?php

namespace App\Filament\Resources\Employees\Pages;

use App\Filament\Resources\Employees\EmployeeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use App\Models\Employee;
use App\Services\GeminiService;
use Pgvector\Laravel\Vector;
use Illuminate\Support\Facades\DB;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function afterSave(): void
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
