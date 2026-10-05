<?php

use App\Filament\Pages\Reports\AttendanceReport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

beforeEach(function () {
    Schema::create('companies', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->softDeletes();
    });
    Schema::create('departments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id')->default(1);
        $table->string('name');
        $table->softDeletes();
    });
    Schema::create('employees', function (Blueprint $table) {
        $table->id();
        $table->foreignId('company_id');
        $table->foreignId('department_id')->nullable();
        $table->string('name');
        $table->boolean('is_active')->default(true);
        $table->softDeletes();
    });
    Schema::create('attendances', function (Blueprint $table) {
        $table->id();
        $table->foreignId('employee_id');
        $table->date('date');
        $table->string('status');
        $table->softDeletes();
    });

    DB::table('companies')->insert(['id' => 1, 'name' => 'Test Company']);
    DB::table('departments')->insert(['id' => 1, 'name' => 'Operations']);
    DB::table('employees')->insert([
        ['id' => 1, 'company_id' => 1, 'department_id' => 1, 'name' => 'Employee One', 'is_active' => true],
        ['id' => 2, 'company_id' => 1, 'department_id' => null, 'name' => 'Other Department', 'is_active' => true],
        ['id' => 3, 'company_id' => 2, 'department_id' => 1, 'name' => 'Other Company', 'is_active' => true],
        ['id' => 4, 'company_id' => 1, 'department_id' => 1, 'name' => 'Inactive Employee', 'is_active' => false],
    ]);
    session(['selected_company_id' => 1]);
});

afterEach(function () {
    Schema::dropIfExists('attendances');
    Schema::dropIfExists('employees');
    Schema::dropIfExists('departments');
    Schema::dropIfExists('companies');
});

function attendanceReportData(array $state): array
{
    $page = new AttendanceReport;
    $page->data = $state;

    return (new ReflectionMethod($page, 'getRawData'))->invoke($page);
}

test('attendance summary includes both date boundaries across months and years', function () {
    DB::table('attendances')->insert([
        ['employee_id' => 1, 'date' => '2025-12-24', 'status' => 'present'],
        ['employee_id' => 1, 'date' => '2025-12-25', 'status' => 'present'],
        ['employee_id' => 1, 'date' => '2025-12-26', 'status' => 'late'],
        ['employee_id' => 1, 'date' => '2026-01-01', 'status' => 'absent'],
        ['employee_id' => 1, 'date' => '2026-01-02', 'status' => 'permit'],
        ['employee_id' => 1, 'date' => '2026-01-05', 'status' => 'leave'],
        ['employee_id' => 1, 'date' => '2026-01-06', 'status' => 'present'],
    ]);

    $data = attendanceReportData(['start_date' => '2025-12-25', 'end_date' => '2026-01-05', 'department_id' => 1]);
    $record = $data['records']->sole();

    expect($data['start_date'])->toBe('2025-12-25')
        ->and($data['end_date'])->toBe('2026-01-05')
        ->and($record['employee']->id)->toBe(1)
        ->and($record['present'])->toBe(1)
        ->and($record['late'])->toBe(1)
        ->and($record['absent'])->toBe(1)
        ->and($record['permit'])->toBe(1)
        ->and($record['leave'])->toBe(1)
        ->and($record['total_working_days'])->toBe(5);
});

test('attendance summary supports a single day and retains employees without attendance', function () {
    DB::table('attendances')->insert([
        ['employee_id' => 1, 'date' => '2026-07-14', 'status' => 'present'],
        ['employee_id' => 1, 'date' => '2026-07-15', 'status' => 'late'],
        ['employee_id' => 1, 'date' => '2026-07-16', 'status' => 'absent'],
    ]);

    $data = attendanceReportData(['start_date' => '2026-07-15', 'end_date' => '2026-07-15']);

    expect($data['records']->pluck('employee.id')->all())->toBe([1, 2])
        ->and($data['records'][0]['late'])->toBe(1)
        ->and($data['records'][0]['total_working_days'])->toBe(1)
        ->and($data['records'][1]['total_working_days'])->toBe(0);
});

test('attendance summary rejects invalid or incomplete date ranges', function ($startDate, $endDate) {
    $data = attendanceReportData(['start_date' => $startDate, 'end_date' => $endDate]);

    expect($data)->toHaveKey('error')
        ->and($data['records'])->toBeEmpty();
})->with([
    'reversed dates' => ['2026-07-20', '2026-07-01'],
    'missing start' => [null, '2026-07-20'],
    'missing end' => ['2026-07-01', ''],
    'invalid date' => ['2026-02-30', '2026-03-01'],
]);

test('attendance summary requires a specific company', function () {
    session(['selected_company_id' => 'all']);

    $data = attendanceReportData(['start_date' => '2026-07-01', 'end_date' => '2026-07-31']);

    expect($data['error'])->toBe(__('Please select a specific company.'))
        ->and($data['records'])->toBeEmpty();
});

test('attendance summary defaults to the current full month', function () {
    $this->travelTo(now()->setDate(2026, 7, 15));
    $page = new AttendanceReport;
    $page->mount();

    expect($page->data['start_date'])->toBe('2026-07-01')
        ->and($page->data['end_date'])->toBe('2026-07-31');
});

test('attendance PDF download uses the selected period in its filename and data', function () {
    $pdf = Mockery::mock(\Barryvdh\DomPDF\PDF::class);
    $pdf->shouldReceive('output')->once()->andReturn('PDF output');
    Pdf::shouldReceive('loadView')->once()->withArgs(function ($view, $data) {
        return $view === 'filament.pages.reports.attendance-report-pdf'
            && $data['start_date'] === '2025-12-25'
            && $data['end_date'] === '2026-01-05';
    })->andReturn($pdf);

    $page = new AttendanceReport;
    $page->data = ['start_date' => '2025-12-25', 'end_date' => '2026-01-05'];
    $response = $page->downloadPdf();

    expect($response->headers->get('Content-Disposition'))
        ->toContain('Attendance_Report_2025-12-25_2026-01-05.pdf');
    ob_start();
    $response->sendContent();
    expect(ob_get_clean())->toBe('PDF output');
});

test('attendance PDF cannot be downloaded for an invalid period', function () {
    Pdf::shouldReceive('loadView')->never();
    $page = new AttendanceReport;
    $page->data = ['start_date' => '2026-07-20', 'end_date' => '2026-07-01'];

    expect($page->downloadPdf())->toBeNull();
});

test('attendance PDF displays the selected period', function () {
    $data = attendanceReportData(['start_date' => '2025-12-25', 'end_date' => '2026-01-05', 'department_id' => 1]);
    $html = view('filament.pages.reports.attendance-report-pdf', $data)->render();

    expect($html)->toContain('25/12/2025', '05/01/2026')
        ->not->toContain('Month:');
});
