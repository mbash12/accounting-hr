<?php

namespace App\Traits;

use Filament\Notifications\Notification;

trait HasDependencyValidation
{
    public static function bootHasDependencyValidation(): void
    {
        static::deleting(function ($model) {
            if (method_exists($model, 'getDependencyChecks')) {
                foreach ($model->getDependencyChecks() as $check) {
                    $relation = $check['relation'] ?? null;
                    $label = $check['label'];
                    $table = $check['table'] ?? null;
                    $foreignKey = $check['foreignKey'] ?? null;

                    $exists = false;

                    if ($table && $foreignKey) {
                        $exists = \Illuminate\Support\Facades\DB::table($table)
                            ->where($foreignKey, $model->id)
                            ->whereNull('deleted_at')
                            ->exists();
                    } elseif ($relation && method_exists($model, $relation)) {
                        $exists = $model->{$relation}()->exists();
                    }

                    if ($exists) {
                        Notification::make()
                            ->danger()
                            ->title('Cannot delete')
                            ->body("This record is being used by {$label} and cannot be deleted.")
                            ->send();

                        return false;
                    }
                }
            }
        });
    }
}
