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

        $photo_path = __DIR__ . '/../../../../../storage/app/public/' . $employee->foto;
        
        if (file_exists($photo_path)) {

            try {
            DB::beginTransaction();
                //code here
                $vector = GeminiService::generateFaceVectorWithVertexAI($photo_path);
                $employee->foto_vector = new Vector($vector);
                $employee->save();
                
                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();

                dd($e);
            }
        }
    }   
}
