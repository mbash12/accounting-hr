<?php

namespace App\Services;

use App\Models\Account;
use App\Models\AccountMapping;
use App\Models\Attendance;
use App\Models\Employee;
use App\Models\JournalEntry;
use App\Models\JournalEntryItem;
use App\Models\OvertimeLog;
use App\Models\OvertimeRule;
use App\Models\PayrollPeriod;
use App\Models\Payslip;
use App\Models\PayslipItem;
use App\Models\SalaryComponent;
use App\Models\BonusCalculation;
use App\Models\BonusCalculationItem;
use App\Models\THRCalculation;
use App\Models\THRCalculationItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    /**
     * Calculate THR for all active employees
     */
    public function calculateTHRForPeriod(THRCalculation $thr)
    {
        return DB::transaction(function () use ($thr) {
            $companyId = $thr->company_id ?: session('selected_company_id');
            
            if (!$companyId || $companyId === 'all') {
                return $thr;
            }

            $employees = Employee::where('is_active', true)
                ->where('company_id', $companyId)
                ->get();

            $totalAmount = 0;
            $totalPPh21 = 0;

            // Clear existing items if any
            $thr->items()->delete();

            foreach ($employees as $employee) {
                if (!$employee->hire_date) continue;

                $monthsService = (int) $employee->hire_date->diffInMonths($thr->payout_date);
                $amount = 0;

                if ($monthsService >= 12) {
                    $amount = $employee->basic_salary;
                } elseif ($monthsService >= 1) {
                    $amount = ($monthsService / 12) * $employee->basic_salary;
                }

                if ($amount > 0) {
                    // Simple PPh21 for THR (using TER for simplicity in this version)
                    $pph21 = $thr->is_taxable ? $this->calculatePPh21($employee, $amount) : 0;

                    THRCalculationItem::create([
                        'thr_calculation_id' => $thr->id,
                        'employee_id' => $employee->id,
                        'basic_salary' => $employee->basic_salary,
                        'months_service' => $monthsService,
                        'amount' => $amount,
                        'pph21' => $pph21,
                        'company_id' => $companyId,
                    ]);

                    $totalAmount += $amount;
                    $totalPPh21 += $pph21;
                }
            }

            $thr->update([
                'total_amount' => $totalAmount,
                'total_pph21' => $totalPPh21,
                'status' => 'processed',
                'company_id' => $thr->company_id ?: $companyId,
            ]);

            return $thr;
        });
    }

    /**
     * Calculate Bonus PPh21 (Placeholder - can be refined)
     */
    public function calculateBonusForPeriod(BonusCalculation $bonus)
    {
        // Bonus items are usually added manually in UI or imported
        // This method just updates totals
        $totalAmount = $bonus->items()->sum('amount');
        $totalPPh21 = 0;

        foreach ($bonus->items as $item) {
            $pph21 = $bonus->is_taxable ? $this->calculatePPh21($item->employee, $item->amount) : 0;
            $item->update(['pph21' => $pph21]);
            $totalPPh21 += $pph21;
        }

        $bonus->update([
            'total_amount' => $totalAmount,
            'total_pph21' => $totalPPh21,
            'status' => 'processed',
        ]);

        return $bonus;
    }

    /**
     * Post THR to General Ledger
     */
    public function postTHRToLedger(THRCalculation $thr)
    {
        return DB::transaction(function () use ($thr) {
            $mappings = AccountMapping::getMappingsForDocument('payroll', $thr->company_id);
            
            $thrExpenseAccount = $mappings['thr_expense'] ?? ($mappings['salary_expense'] ?? null);
            $salaryPayableAccount = $mappings['salary_payable'] ?? null;
            $pph21PayableAccount = $mappings['pph21_payable'] ?? null;

            if (!$thrExpenseAccount || !$salaryPayableAccount || !$pph21PayableAccount) {
                throw new \Exception("THR Account Mappings (Expense, Payable, PPh21) not fully configured.");
            }

            $description = "THR Posting - " . $thr->name;
            $entryNumber = $this->generateEntryNumber();
            
            $journalEntry = JournalEntry::create([
                'entry_number' => $entryNumber,
                'date' => $thr->payout_date,
                'reference_no' => $thr->name,
                'description' => $description,
                'amount' => $thr->total_amount, 
                'total_amount' => $thr->total_amount,
                'status' => 'posted',
                'is_posted' => true,
                'sub_module' => 'payroll',
                'reference_type' => get_class($thr),
                'reference_id' => $thr->id,
                'posted_by_user_id' => Auth::id(),
                'posted_at' => now(),
                'company_id' => $thr->company_id,
            ]);

            // Debit: THR Expense
            $this->createJournalItem($journalEntry, $thrExpenseAccount, 'debit', $thr->total_amount);

            // Credit: Salary Payable (Net)
            $this->createJournalItem($journalEntry, $salaryPayableAccount, 'credit', $thr->total_amount - $thr->total_pph21);

            // Credit: PPh21 Payable
            $this->createJournalItem($journalEntry, $pph21PayableAccount, 'credit', $thr->total_pph21);

            $thr->update([
                'journal_entry_id' => $journalEntry->id,
                'status' => 'posted',
            ]);

            return $journalEntry;
        });
    }

    /**
     * Post Bonus to General Ledger
     */
    public function postBonusToLedger(BonusCalculation $bonus)
    {
        return DB::transaction(function () use ($bonus) {
            $mappings = AccountMapping::getMappingsForDocument('payroll', $bonus->company_id);
            
            $bonusExpenseAccount = $mappings['bonus_expense'] ?? ($mappings['salary_expense'] ?? null);
            $salaryPayableAccount = $mappings['salary_payable'] ?? null;
            $pph21PayableAccount = $mappings['pph21_payable'] ?? null;

            if (!$bonusExpenseAccount || !$salaryPayableAccount || !$pph21PayableAccount) {
                throw new \Exception("Bonus Account Mappings (Expense, Payable, PPh21) not fully configured.");
            }

            $description = "Bonus Posting - " . $bonus->name;
            $entryNumber = $this->generateEntryNumber();
            
            $journalEntry = JournalEntry::create([
                'entry_number' => $entryNumber,
                'date' => $bonus->payout_date,
                'reference_no' => $bonus->name,
                'description' => $description,
                'amount' => $bonus->total_amount, 
                'total_amount' => $bonus->total_amount,
                'status' => 'posted',
                'is_posted' => true,
                'sub_module' => 'payroll',
                'reference_type' => get_class($bonus),
                'reference_id' => $bonus->id,
                'posted_by_user_id' => Auth::id(),
                'posted_at' => now(),
                'company_id' => $bonus->company_id,
            ]);

            // Debit: Bonus Expense
            $this->createJournalItem($journalEntry, $bonusExpenseAccount, 'debit', $bonus->total_amount);

            // Credit: Salary Payable (Net)
            $this->createJournalItem($journalEntry, $salaryPayableAccount, 'credit', $bonus->total_amount - $bonus->total_pph21);

            // Credit: PPh21 Payable
            $this->createJournalItem($journalEntry, $pph21PayableAccount, 'credit', $bonus->total_pph21);

            $bonus->update([
                'journal_entry_id' => $journalEntry->id,
                'status' => 'posted',
            ]);

            return $journalEntry;
        });
    }

    /**
     * Calculate BPJS for an employee
     */
    public function calculateBPJS(Employee $employee, float $baseSalary)
    {
        // BPJS Ketenagakerjaan
        $jkkRate = 0.0024; // 0.24% (default)
        $jkmRate = 0.003;  // 0.3%
        
        $jkkEmployer = $baseSalary * $jkkRate;
        $jkmEmployer = $baseSalary * $jkmRate;
        
        // JHT: 3.7% Employer, 2% Employee
        $jhtEmployer = $baseSalary * 0.037;
        $jhtEmployee = $baseSalary * 0.02;
        
        // JP (Pension): 2% Employer, 1% Employee (with cap)
        $jpCap = 10042300; // 2024 Cap
        $jpBase = min($baseSalary, $jpCap);
        $jpEmployer = $jpBase * 0.02;
        $jpEmployee = $jpBase * 0.01;
        
        // BPJS Kesehatan: 4% Employer, 1% Employee (with cap)
        $kesCap = 12000000; // Cap
        $kesBase = min($baseSalary, $kesCap);
        $kesEmployer = $kesBase * 0.04;
        $kesEmployee = $kesBase * 0.01;
        
        return [
            'ketenagakerjaan' => [
                'jkk_employer' => $jkkEmployer,
                'jkm_employer' => $jkmEmployer,
                'jht_employer' => $jhtEmployer,
                'jht_employee' => $jhtEmployee,
                'jp_employer' => $jpEmployer,
                'jp_employee' => $jpEmployee,
                'total_employer' => $jkkEmployer + $jkmEmployer + $jhtEmployer + $jpEmployer,
                'total_employee' => $jhtEmployee + $jpEmployee,
            ],
            'kesehatan' => [
                'employer' => $kesEmployer,
                'employee' => $kesEmployee,
            ],
            'total_employer' => $jkkEmployer + $jkmEmployer + $jhtEmployer + $jpEmployer + $kesEmployer,
            'total_employee' => $jhtEmployee + $jpEmployee + $kesEmployee,
        ];
    }

    /**
     * Calculate Overtime for an employee in a period
     */
    public function calculateOvertime(Employee $employee, PayrollPeriod $period)
    {
        // Find rule for department or default
        $rule = OvertimeRule::where('department_id', $employee->department_id)
            ->where('is_active', true)
            ->first() ?? OvertimeRule::whereNull('department_id')
            ->where('is_default', true)
            ->first();

        if (!$rule) return 0;

        $logs = OvertimeLog::where('employee_id', $employee->id)
            ->whereBetween('date', [$period->start_date, $period->end_date])
            ->where('status', 'approved')
            ->get();

        $hourlyRate = $employee->basic_salary / $rule->base_hourly_rate_divisor;
        $totalOvertimePay = 0;

        foreach ($logs as $log) {
            $amount = 0;
            if ($log->is_holiday) {
                $amount = $log->hours * $rule->holiday_multiplier * $hourlyRate;
            } else {
                // First hour 1.5x, subsequent 2x
                if ($log->hours <= 1) {
                    $amount = $log->hours * $rule->workday_first_hour_multiplier * $hourlyRate;
                } else {
                    $amount = (1 * $rule->workday_first_hour_multiplier * $hourlyRate) + 
                             (($log->hours - 1) * $rule->workday_subsequent_hour_multiplier * $hourlyRate);
                }
            }
            $totalOvertimePay += $amount;
            
            // Save calculated amount to log for reference
            $log->update(['calculated_amount' => $amount]);
        }

        return $totalOvertimePay;
    }

    /**
     * Calculate THR (Tunjangan Hari Raya)
     * Standard: 1 month salary for 12 months service, prorated for less.
     */
    public function calculateTHR(Employee $employee)
    {
        if (!$employee->hire_date) return 0;

        $monthsService = $employee->hire_date->diffInMonths(now());
        
        if ($monthsService >= 12) {
            return $employee->basic_salary;
        } elseif ($monthsService >= 1) {
            return ($monthsService / 12) * $employee->basic_salary;
        }

        return 0;
    }

    /**
     * Calculate PPh21 using TER 2024 for monthly
     */
    public function calculatePPh21(Employee $employee, float $grossIncome)
    {
        $category = $this->getTERCategory($employee->ptkp_status);
        $rate = $this->getTERRate($category, $grossIncome);
        
        return $grossIncome * $rate;
    }

    private function getTERCategory(string $ptkpStatus): string
    {
        if (in_array($ptkpStatus, ['TK/0', 'TK/1', 'K/0'])) return 'A';
        if (in_array($ptkpStatus, ['TK/2', 'TK/3', 'K/1', 'K/2'])) return 'B';
        if ($ptkpStatus === 'K/3') return 'C';
        return 'A';
    }

    private function getTERRate(string $category, float $grossIncome): float
    {
        if ($category === 'A') {
            if ($grossIncome <= 5400000) return 0;
            if ($grossIncome <= 5650000) return 0.0025;
            if ($grossIncome <= 5950000) return 0.005;
            if ($grossIncome <= 6300000) return 0.0075;
            if ($grossIncome <= 6750000) return 0.01;
            if ($grossIncome <= 7500000) return 0.0125;
            if ($grossIncome <= 8550000) return 0.015;
            if ($grossIncome <= 9650000) return 0.0175;
            if ($grossIncome <= 10000000) return 0.02;
            return 0.05; 
        }
        
        if ($category === 'B') {
            if ($grossIncome <= 6200000) return 0;
            if ($grossIncome <= 6500000) return 0.0025;
            if ($grossIncome <= 6850000) return 0.005;
            return 0.01;
        }
        
        if ($category === 'C') {
            if ($grossIncome <= 6600000) return 0;
            if ($grossIncome <= 6950000) return 0.0025;
            return 0.01;
        }

        return 0;
    }

    /**
     * Generate Payslips for a Period
     */
    public function generatePayslips(PayrollPeriod $period)
    {
        return DB::transaction(function () use ($period) {
            // Remove existing payslips (and their items via cascade/manual) before regenerating
            $existingPayslipIds = $period->payslips()->pluck('id');
            if ($existingPayslipIds->isNotEmpty()) {
                PayslipItem::whereIn('payslip_id', $existingPayslipIds)->delete();
                Payslip::whereIn('id', $existingPayslipIds)->delete();
            }

            $employees = Employee::where('is_active', true)
                ->where('company_id', $period->company_id)
                ->get();
            
            foreach ($employees as $employee) {
                $this->generateSinglePayslip($employee, $period);
            }
            
            $this->updatePeriodTotals($period);
            
            $period->status = 'processed';
            $period->save();
        });
    }

    private function generateSinglePayslip(Employee $employee, PayrollPeriod $period)
    {
        $basicSalary = $employee->basic_salary;
        
        // 1. Regular Allowances & Deductions
        $empComponents = $employee->salaryComponents()->with('salaryComponent')->get()
            ->filter(fn ($comp) => $comp->salaryComponent !== null);
        $allowances = $empComponents->where('salaryComponent.type', 'allowance');
        $deductions = $empComponents->where('salaryComponent.type', 'deduction');
        
        $totalAllowance = $allowances->sum('amount');
        $totalDeduction = $deductions->sum('amount');
        
        // 2. Attendance Deductions (Late & Early Departure)
        $attendanceDeduction = 0;
        if ($period->apply_attendance_deduction) {
            $attendanceStats = Attendance::where('employee_id', $employee->id)
                ->whereBetween('date', [$period->start_date, $period->end_date])
                ->select(DB::raw('SUM(late_minutes) as total_late, SUM(early_departure_minutes) as total_early'))
                ->first();
            
            $totalOffMinutes = ($attendanceStats->total_late ?? 0) + ($attendanceStats->total_early ?? 0);
            $attendanceDeduction = $totalOffMinutes * 500; // Example: Rp 500 per minute
            $totalDeduction += $attendanceDeduction;
        }

        // 3. Overtime
        $overtimePay = $this->calculateOvertime($employee, $period);
        $totalAllowance += $overtimePay;

        // 3. THR (Logic: check if period name or month suggests THR, or add a flag to period)
        // For now, let's assume if 'THR' is in the period name
        $thrPay = 0;
        if (str_contains(strtoupper($period->name), 'THR')) {
            $thrPay = $this->calculateTHR($employee);
            $totalAllowance += $thrPay;
        }
        
        $grossSalary = $basicSalary + $totalAllowance;
        
        // BPJS
        $bpjsBase = $basicSalary; 
        $bpjs = $this->calculateBPJS($employee, $bpjsBase);
        
        // PPh21 (Monthly)
        $pph21 = $this->calculatePPh21($employee, $grossSalary);
        
        $netSalary = $grossSalary - $totalDeduction - $pph21 - $bpjs['total_employee'];
        
        $payslip = Payslip::create([
            'employee_id' => $employee->id,
            'payroll_period_id' => $period->id,
            'basic_salary' => $basicSalary,
            'total_allowance' => $totalAllowance,
            'total_deduction' => $totalDeduction,
            'gross_salary' => $grossSalary,
            'taxable_income' => $grossSalary,
            'pph21' => $pph21,
            'bpjs_kesehatan_employee' => $bpjs['kesehatan']['employee'],
            'bpjs_kesehatan_employer' => $bpjs['kesehatan']['employer'],
            'bpjs_ketenagakerjaan_employee' => $bpjs['ketenagakerjaan']['total_employee'],
            'bpjs_ketenagakerjaan_employer' => $bpjs['ketenagakerjaan']['total_employer'],
            'net_salary' => $netSalary,
            'company_id' => $period->company_id,
        ]);
        
        // Create Items for individual components
        foreach ($empComponents as $comp) {
            if (! $comp->salaryComponent) {
                continue;
            }
            PayslipItem::create([
                'payslip_id' => $payslip->id,
                'salary_component_id' => $comp->salary_component_id,
                'name' => $comp->salaryComponent->name,
                'type' => $comp->salaryComponent->type,
                'amount' => $comp->amount,
                'company_id' => $period->company_id,
            ]);
        }

        // Add special items for Overtime and THR if they exist
        if ($overtimePay > 0) {
            PayslipItem::create([
                'payslip_id' => $payslip->id,
                'name' => 'Overtime',
                'type' => 'allowance',
                'amount' => $overtimePay,
                'company_id' => $period->company_id,
            ]);
        }

        if ($attendanceDeduction > 0) {
            PayslipItem::create([
                'payslip_id' => $payslip->id,
                'name' => __('Potongan Kehadiran (Keterlambatan/Pulang Cepat)'),
                'type' => 'deduction',
                'amount' => $attendanceDeduction,
                'company_id' => $period->company_id,
            ]);
        }

        if ($thrPay > 0) {
            PayslipItem::create([
                'payslip_id' => $payslip->id,
                'name' => 'THR',
                'type' => 'allowance',
                'amount' => $thrPay,
                'company_id' => $period->company_id,
            ]);
        }
        
        return $payslip;
    }

    /**
     * Post Payroll to General Ledger
     */
    public function postToLedger(PayrollPeriod $period)
    {
        return DB::transaction(function () use ($period) {
            $mappings = AccountMapping::getMappingsForDocument('payroll', $period->company_id);
            
            if ($mappings->isEmpty()) {
                throw new \Exception("Payroll Account Mappings not configured for this company.");
            }

            $description = "Payroll Posting - " . $period->name;
            
            $journalEntry = JournalEntry::create([
                'entry_number' => $this->generateEntryNumber(),
                'date' => $period->end_date,
                'reference_no' => $period->name,
                'description' => $description,
                'amount' => 0, 
                'total_amount' => $period->total_gross_salary + $period->total_bpjs_employer,
                'status' => 'posted',
                'is_posted' => true,
                'sub_module' => 'payroll',
                'reference_type' => get_class($period),
                'reference_id' => $period->id,
                'posted_by_user_id' => Auth::id(),
                'posted_at' => now(),
                'company_id' => $period->company_id,
            ]);

            // 1. Debit: Salary Expenses (Detailed by Component if mapped)
            $this->postSalaryExpenses($period, $journalEntry, $mappings);

            // 2. Debit: BPJS Employer Expenses
            if (isset($mappings['bpjs_expense'])) {
                $this->createJournalItem($journalEntry, $mappings['bpjs_expense'], 'debit', $period->total_bpjs_employer);
            }

            // 3. Credit: Salary Payable (Net)
            if (isset($mappings['salary_payable'])) {
                $this->createJournalItem($journalEntry, $mappings['salary_payable'], 'credit', $period->total_net_salary);
            }

            // 4. Credit: PPh21 Payable
            if (isset($mappings['pph21_payable'])) {
                $this->createJournalItem($journalEntry, $mappings['pph21_payable'], 'credit', $period->total_pph21);
            }

            // 5. Credit: BPJS Payable (Total)
            if (isset($mappings['bpjs_payable'])) {
                $this->createJournalItem($journalEntry, $mappings['bpjs_payable'], 'credit', $period->total_bpjs_employer + $period->total_bpjs_employee);
            }

            $journalEntry->update(['amount' => $journalEntry->items()->sum('debit')]);
            
            $period->journal_entry_id = $journalEntry->id;
            $period->status = 'posted';
            $period->save();

            return $journalEntry;
        });
    }

    private function postSalaryExpenses(PayrollPeriod $period, JournalEntry $journalEntry, $mappings)
    {
        // Basic Salary Expense
        $defaultExpenseAccount = $mappings['salary_expense'] ?? null;
        $totalBasicSalary = $period->payslips()->sum('basic_salary');
        if ($totalBasicSalary > 0 && $defaultExpenseAccount) {
            $this->createJournalItem($journalEntry, $defaultExpenseAccount, 'debit', $totalBasicSalary);
        }

        // Components Expenses (Grouped by their individual account mappings or fallback to default)
        $items = PayslipItem::whereIn('payslip_id', $period->payslips()->pluck('id'))
            ->where('type', 'allowance')
            ->with('salaryComponent')
            ->get();
            
        $groupedItems = $items->groupBy(function($item) use ($defaultExpenseAccount) {
            return $item->salaryComponent->account_id ?? ($defaultExpenseAccount->id ?? 0);
        });

        foreach ($groupedItems as $accountId => $compItems) {
            if ($accountId == 0) continue;
            $account = Account::find($accountId);
            $this->createJournalItem($journalEntry, $account, 'debit', $compItems->sum('amount'));
        }
    }

    private function updatePeriodTotals(PayrollPeriod $period)
    {
        $period->total_gross_salary = $period->payslips()->sum('gross_salary');
        $period->total_deductions = $period->payslips()->sum('total_deduction');
        $period->total_net_salary = $period->payslips()->sum('net_salary');
        $period->total_pph21 = $period->payslips()->sum('pph21');
        $period->total_bpjs_employer = $period->payslips()->sum(DB::raw('bpjs_kesehatan_employer + bpjs_ketenagakerjaan_employer'));
        $period->total_bpjs_employee = $period->payslips()->sum(DB::raw('bpjs_kesehatan_employee + bpjs_ketenagakerjaan_employee'));
        $period->save();
    }

    private function generateEntryNumber(): string
    {
        $prefix = 'PY';
        $date = now()->format('Ymd');
        $lastEntry = JournalEntry::where('entry_number', 'like', $prefix . $date . '%')->orderBy('entry_number', 'desc')->first();
        $newNumber = $lastEntry ? str_pad((int) substr($lastEntry->entry_number, -4) + 1, 4, '0', STR_PAD_LEFT) : '0001';
        return $prefix . $date . $newNumber;
    }

    private function createJournalItem($journalEntry, $account, $type, $amount)
    {
        if (!$account || $amount <= 0) return;
        JournalEntryItem::create([
            'journal_entry_id' => $journalEntry->id,
            'account_id' => $account->id,
            'debit' => $type === 'debit' ? $amount : 0,
            'credit' => $type === 'credit' ? $amount : 0,
        ]);
    }
}
