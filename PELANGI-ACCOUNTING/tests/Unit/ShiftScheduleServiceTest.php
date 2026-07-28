<?php

use App\Services\ShiftScheduleService;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

uses(Tests\TestCase::class);

beforeEach(function () {
    Schema::create('companies', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->timestamps();
    });
    Schema::create('departments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')->nullable();
        $table->string('name');
        $table->string('code');
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')->nullable();
        $table->foreignId('department_id')->nullable();
        $table->string('name');
        $table->string('employee_id');
        $table->string('status')->default('permanent');
        $table->string('ptkp_status')->default('TK/0');
        $table->timestamps();
        $table->softDeletes();
    });
    Schema::create('shift_types', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')->nullable();
        $table->string('code');
        $table->string('name');
        $table->time('start_time')->nullable();
        $table->time('end_time')->nullable();
        $table->boolean('is_off')->default(false);
        $table->boolean('is_active')->default(true);
        $table->timestamps();
    });
    Schema::create('shift_schedules', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')->nullable();
        $table->foreignId('employee_id');
        $table->foreignId('shift_type_id')->nullable();
        $table->date('date');
        $table->string('shift_code');
        $table->boolean('is_off')->default(false);
        $table->boolean('is_holiday')->default(false);
        $table->string('holiday_name')->nullable();
        $table->timestamps();
        $table->softDeletes();
    });
    Schema::create('holidays', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')->nullable();
        $table->date('date');
        $table->string('name');
        $table->boolean('is_cuti_bersama')->default(false);
        $table->softDeletes();
    });
});

afterEach(function () {
    Schema::dropIfExists('holidays');
    Schema::dropIfExists('shift_schedules');
    Schema::dropIfExists('shift_types');
    Schema::dropIfExists('employees');
    Schema::dropIfExists('departments');
    Schema::dropIfExists('companies');
});

test('filters the monthly grid by the selected company department and shift type', function () {
    DB::table('companies')->insert([
        'id' => 1,
        'name' => 'Company One',
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    DB::table('companies')->insert([
        'id' => 2,
        'name' => 'Company Two',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('departments')->insert([
        ['id' => 11, 'company_id' => 1, 'name' => 'Operations', 'code' => 'OPS', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['id' => 12, 'company_id' => 1, 'name' => 'Finance', 'code' => 'FIN', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['id' => 21, 'company_id' => 2, 'name' => 'Other', 'code' => 'OTH', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
    ]);

    DB::table('employees')->insert([
        ['id' => 101, 'company_id' => 1, 'department_id' => 11, 'name' => 'Matching Employee', 'employee_id' => 'E101', 'status' => 'permanent', 'ptkp_status' => 'TK/0', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 102, 'company_id' => 1, 'department_id' => 12, 'name' => 'Wrong Department', 'employee_id' => 'E102', 'status' => 'permanent', 'ptkp_status' => 'TK/0', 'created_at' => now(), 'updated_at' => now()],
        ['id' => 201, 'company_id' => 2, 'department_id' => 21, 'name' => 'Wrong Company', 'employee_id' => 'E201', 'status' => 'permanent', 'ptkp_status' => 'TK/0', 'created_at' => now(), 'updated_at' => now()],
    ]);

    DB::table('shift_types')->insert([
        ['id' => 301, 'company_id' => 1, 'code' => 'A', 'name' => 'Morning', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['id' => 302, 'company_id' => 1, 'code' => 'B', 'name' => 'Night', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
        ['id' => 401, 'company_id' => 2, 'code' => 'A', 'name' => 'Other Morning', 'is_active' => true, 'created_at' => now(), 'updated_at' => now()],
    ]);

    DB::table('shift_schedules')->insert([
        ['company_id' => 1, 'employee_id' => 101, 'shift_type_id' => 301, 'date' => '2026-07-01', 'shift_code' => 'A', 'created_at' => now(), 'updated_at' => now()],
        ['company_id' => 1, 'employee_id' => 102, 'shift_type_id' => 301, 'date' => '2026-07-01', 'shift_code' => 'A', 'created_at' => now(), 'updated_at' => now()],
        ['company_id' => 1, 'employee_id' => 101, 'shift_type_id' => 302, 'date' => '2026-07-02', 'shift_code' => 'B', 'created_at' => now(), 'updated_at' => now()],
        ['company_id' => 2, 'employee_id' => 201, 'shift_type_id' => 401, 'date' => '2026-07-01', 'shift_code' => 'A', 'created_at' => now(), 'updated_at' => now()],
    ]);

    $result = app(ShiftScheduleService::class)->buildMonthGrid(2026, 7, 1, 11, 301);

    expect(collect($result['employees'])->pluck('id')->all())->toBe([101]);
    expect($result['grid'][101][1]['code'])->toBe('A');
    expect($result['grid'][101][2] ?? null)->toBeNull();
});
