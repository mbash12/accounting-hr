<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\EmployeeLeaveQuota;
use App\Models\EmployeeSalaryComponent;
use App\Models\OvertimeLog;
use App\Models\OvertimeRule;
use App\Models\PayrollPeriod;
use App\Models\Permit;
use App\Models\SalaryComponent;
use App\Services\PayrollService;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayrollSeeder extends Seeder
{
    public function run(): void
    {
        $companyId = 15;
        $userId = 6; // Valid User ID from DB
        
        Auth::loginUsingId($userId);

        // Cleanup existing data for this company in CORRECT ORDER to avoid FK issues
        DB::table('payslip_items')->where('company_id', $companyId)->delete();
        DB::table('payslips')->where('company_id', $companyId)->delete();
        Attendance::where('company_id', $companyId)->forceDelete();
        Permit::where('company_id', $companyId)->forceDelete();
        OvertimeLog::where('company_id', $companyId)->forceDelete();
        EmployeeSalaryComponent::where('company_id', $companyId)->forceDelete();
        EmployeeLeaveQuota::where('company_id', $companyId)->forceDelete();
        Employee::where('company_id', $companyId)->forceDelete();
        SalaryComponent::where('company_id', $companyId)->forceDelete();
        PayrollPeriod::where('company_id', $companyId)->forceDelete();

        // 1. Create Salary Components
        $allowance = SalaryComponent::updateOrCreate(
            ['company_id' => $companyId, 'code' => 'ALW-001'],
            [
                'name' => 'Tunjangan Makan',
                'type' => 'allowance',
                'is_fixed' => true,
                'is_taxable' => true,
                'is_bpjs_base' => true,
                'is_active' => true,
                'created_by_user_id' => $userId,
            ]
        );

        $deduction = SalaryComponent::updateOrCreate(
            ['company_id' => $companyId, 'code' => 'DED-001'],
            [
                'name' => 'Potongan Kedisiplinan',
                'type' => 'deduction',
                'is_fixed' => false,
                'is_taxable' => false,
                'is_bpjs_base' => false,
                'is_active' => true,
                'created_by_user_id' => $userId,
            ]
        );

        $month = 2; // February
        $year = 2026;
        $startDate = Carbon::create($year, $month, 1);
        $endDate = $startDate->copy()->endOfMonth();

        // 2. Create Department
        $dept = Department::updateOrCreate(
            ['company_id' => $companyId, 'name' => 'IT'],
            [
                'code' => 'IT-001',
                'work_start_time' => '08:00:00',
                'work_end_time' => '17:00:00',
                'working_days' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                'is_active' => true,
                'created_by_user_id' => $userId,
            ]
        );

        // 3. Create Overtime Rule
        OvertimeRule::updateOrCreate(
            ['company_id' => $companyId, 'department_id' => $dept->id],
            [
                'name' => 'IT Overtime Standard',
                'is_default' => true,
                'is_active' => true,
                'base_hourly_rate_divisor' => 173,
                'workday_first_hour_multiplier' => 1.5,
                'workday_subsequent_hour_multiplier' => 2.0,
                'holiday_multiplier' => 2.0,
                'created_by_user_id' => $userId,
            ]
        );

        // 4. Create 10 Employees
        $ptkpStatuses = ['TK/0', 'TK/1', 'K/0', 'K/1'];
        for ($i = 1; $i <= 10; $i++) {
            $employee = Employee::updateOrCreate(
                ['company_id' => $companyId, 'employee_id' => "EMP-{$i}"],
                [
                    'name' => "Karyawan Simulation {$i}",
                    'department_id' => $dept->id,
                    'status' => 'permanent',
                    'ptkp_status' => $ptkpStatuses[array_rand($ptkpStatuses)],
                    'basic_salary' => rand(5000000, 15000000),
                    'is_active' => true,
                    'hire_date' => Carbon::now()->subYears(rand(1, 5)),
                    'created_by_user_id' => $userId,
                ]
            );

            // Assign Components
            EmployeeSalaryComponent::create([
                'employee_id' => $employee->id,
                'salary_component_id' => $allowance->id,
                'amount' => rand(500000, 1000000),
                'company_id' => $companyId,
                'created_by_user_id' => $userId,
            ]);

            EmployeeSalaryComponent::create([
                'employee_id' => $employee->id,
                'salary_component_id' => $deduction->id,
                'amount' => rand(50000, 200000),
                'company_id' => $companyId,
                'created_by_user_id' => $userId,
            ]);

            // Create Leave Quota
            EmployeeLeaveQuota::create([
                'employee_id' => $employee->id,
                'year' => $year,
                'total_quota' => 12,
                'used_quota' => 1,
                'remaining_quota' => 11,
                'company_id' => $companyId,
                'created_by_user_id' => $userId,
            ]);

            // 5. Generate Attendance for February
            $leaveDay = rand(1, 20);
            $workdayCount = 0;
            $currentDate = $startDate->copy();
            while ($currentDate <= $endDate) {
                if ($currentDate->isWeekday()) {
                    $workdayCount++;
                    
                    if ($workdayCount === $leaveDay) {
                        Permit::create([
                            'employee_id' => $employee->id,
                            'type' => 'annual_leave',
                            'start_date' => $currentDate->toDateString(),
                            'end_date' => $currentDate->toDateString(),
                            'status' => 'approved',
                            'company_id' => $companyId,
                            'created_by_user_id' => $userId,
                        ]);
                        
                        Attendance::create([
                            'employee_id' => $employee->id,
                            'date' => $currentDate->toDateString(),
                            'status' => 'leave',
                            'company_id' => $companyId,
                            'created_by_user_id' => $userId,
                        ]);
                        
                        $currentDate->addDay();
                        continue;
                    }

                    $rand = rand(1, 20);
                    if ($rand === 1) { // Izin Sakit
                        Permit::create([
                            'employee_id' => $employee->id,
                            'type' => 'sick',
                            'start_date' => $currentDate->toDateString(),
                            'end_date' => $currentDate->toDateString(),
                            'status' => 'approved',
                            'company_id' => $companyId,
                            'created_by_user_id' => $userId,
                        ]);
                        
                        Attendance::create([
                            'employee_id' => $employee->id,
                            'date' => $currentDate->toDateString(),
                            'status' => 'permit',
                            'company_id' => $companyId,
                            'created_by_user_id' => $userId,
                        ]);
                    } elseif ($rand === 2) { // Alpa
                        Attendance::create([
                            'employee_id' => $employee->id,
                            'date' => $currentDate->toDateString(),
                            'status' => 'absent',
                            'company_id' => $companyId,
                            'created_by_user_id' => $userId,
                        ]);
                    } else { // Normal / Late
                        $isLate = rand(1, 5) === 1;
                        $lateMinutes = $isLate ? rand(5, 60) : 0;
                        $checkIn = Carbon::createFromTimeString($dept->work_start_time)->addMinutes($lateMinutes);
                        
                        Attendance::create([
                            'employee_id' => $employee->id,
                            'date' => $currentDate->toDateString(),
                            'check_in' => $checkIn,
                            'check_out' => Carbon::createFromTimeString($dept->work_end_time),
                            'late_minutes' => $lateMinutes,
                            'status' => $isLate ? 'late' : 'present',
                            'company_id' => $companyId,
                            'created_by_user_id' => $userId,
                        ]);

                        if (rand(1, 10) === 1) {
                            OvertimeLog::create([
                                'employee_id' => $employee->id,
                                'date' => $currentDate->toDateString(),
                                'hours' => rand(1, 4),
                                'status' => 'approved',
                                'company_id' => $companyId,
                                'created_by_user_id' => $userId,
                            ]);
                        }
                    }
                }
                $currentDate->addDay();
            }
        }

        // 6. Create Payroll Period
        $period = PayrollPeriod::updateOrCreate(
            ['company_id' => $companyId, 'month' => $month, 'year' => $year],
            [
                'name' => "Payroll Februari {$year}",
                'start_date' => $startDate->toDateString(),
                'end_date' => $endDate->toDateString(),
                'apply_attendance_deduction' => true,
                'status' => 'draft',
                'created_by_user_id' => $userId,
            ]
        );

        // 7. Generate Payslips
        $service = new PayrollService();
        $service->generatePayslips($period);
    }
}
