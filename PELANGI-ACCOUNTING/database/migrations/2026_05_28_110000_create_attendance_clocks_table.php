<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_clocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_id')->constrained('attendances')->cascadeOnDelete();
            $table->string('type', 10);
            $table->dateTime('clocked_at');
            $table->string('source', 20)->default('app');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('photo_path')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['attendance_id', 'clocked_at']);
            $table->index(['attendance_id', 'source']);
        });

        // Migrate existing attendance data into clock records.
        if (Schema::hasTable('attendances')) {
            $rows = DB::table('attendances')
                ->whereNull('deleted_at')
                ->orderBy('id')
                ->get(['id', 'date', 'check_in', 'check_out', 'lat_in', 'lng_in', 'lat_out', 'lng_out', 'photo_in_path', 'photo_out_path', 'notes_in', 'notes_out']);

            $now = now();
            $clocks = [];

            foreach ($rows as $row) {
                $date = (string) $row->date;

                if ($row->check_in) {
                    $clocks[] = [
                        'attendance_id' => $row->id,
                        'type' => 'in',
                        'clocked_at' => $this->normalizeClockedAt($date, $row->check_in),
                        'source' => 'app',
                        'latitude' => $row->lat_in,
                        'longitude' => $row->lng_in,
                        'photo_path' => $row->photo_in_path,
                        'notes' => $row->notes_in,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }

                if ($row->check_out) {
                    $clocks[] = [
                        'attendance_id' => $row->id,
                        'type' => 'out',
                        'clocked_at' => $this->normalizeClockedAt($date, $row->check_out),
                        'source' => 'app',
                        'latitude' => $row->lat_out,
                        'longitude' => $row->lng_out,
                        'photo_path' => $row->photo_out_path,
                        'notes' => $row->notes_out,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
            }

            foreach (array_chunk($clocks, 500) as $chunk) {
                DB::table('attendance_clocks')->insert($chunk);
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_clocks');
    }

    private function normalizeClockedAt(string $date, mixed $value): string
    {
        $value = trim((string) $value);

        if (preg_match('/^\d{1,2}:\d{2}/', $value) && ! str_contains($value, '-')) {
            return $date . ' ' . $value;
        }

        return $value;
    }
};
