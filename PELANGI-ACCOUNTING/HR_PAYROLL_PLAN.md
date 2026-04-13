# HR and Payroll Module Implementation Plan (Indonesian Context)

This document outlines the plan to add HR and Payroll modules to the Pelangi Accounting system, specifically tailored for Indonesian regulations (BPJS, PPh21, THR) and integrated with the existing accounting engine.

## 1. Objective
Provide a robust system for managing employees, calculating payroll with Indonesian tax and social security rules, and automatically generating journal entries in the general ledger.

## 2. Scope & Key Features
- **HR Module**: Employee profiles, departments, employment status, PTKP status, BPJS numbers.
- **Payroll Module**:
    - Salary Components (Allowances & Deductions).
    - BPJS Calculation (Kesehatan & Ketenagakerjaan: JKK, JKM, JHT, JP).
    - PPh21 Tax Calculation (TER 2024 & Progressive rates).
    - Overtime management.
    - THR (Holiday Bonus) calculation.
    - Payslip generation (PDF).
- **Accounting Integration**: Automated Journal Entries on payroll posting.

## 3. Data Model Design

### HR Components
1. **Employee** (`employees`)
    - `name`, `nik`, `employee_id` (auto-generated).
    - `department_id` (foreign key to `departments`).
    - `position`, `hire_date`, `status` (Permanent, Contract, etc.).
    - `bank_name`, `bank_account_number`, `bank_account_holder`.
    - `ptkp_status` (TK/0, K/0, K/1, etc. - critical for PPh21).
    - `npwp` (tax ID).
    - `bpjs_kesehatan_number`, `bpjs_ketenagakerjaan_number`.
    - `basic_salary`.
    - `is_active`.

### Payroll Components
2. **SalaryComponent** (`salary_components`)
    - `name`, `type` (Allowance, Deduction).
    - `is_fixed` (Basic salary, fixed allowance vs variable).
    - `is_taxable` (included in PPh21).
    - `is_bpjs_base` (included in BPJS calculation base).
    - `account_id` (default GL account mapping).

3. **EmployeeSalaryComponent** (`employee_salary_components`)
    - Link between Employee and SalaryComponent with specific amounts.

4. OvertimeRule (`overtime_rules`)
    - Calculation formulas (standard: 1/173 * monthly salary).
    - Multiplier for workdays (1.5 for first hour, 2 for subsequent).
    - Multiplier for holidays/weekends.

5. OvertimeLog (`overtime_logs`)
    - `employee_id`, `date`, `hours`, `is_holiday`.
    - `status` (Draft, Approved, Rejected).

6. Attendance (`attendances`)
    - `employee_id`, `date`, `check_in`, `check_out`, `lat`, `lng`.
    - `status` (Present, Late, Absent).

7. Permit/Leave (`permits`)
    - `employee_id`, `type` (Sick, Annual, Permission, etc.).
    - `start_date`, `end_date`, `reason`, `status` (Pending, Approved, Rejected).
    - `attachment_path`.

8. Holiday (`holidays`)
    - `name`, `date`, `is_cuti_bersama` (critical for Indonesian context).
    - `company_id`.

9. EmployeeLeaveQuota (`employee_leave_quotas`)
    - `employee_id`, `year`, `total_quota`, `used_quota`, `remaining_quota`.

10. PayrollPeriod (`payroll_periods`)
    - `month`, `year`, `start_date`, `end_date`, `status` (Draft, Processed, Posted).

11. Payslip (`payslips`)
    - Linked to `employee` and `payroll_period`.
    - Summary of total earnings, deductions, net pay, PPh21, BPJS.

12. PayslipItem (`payslip_items`)
    - Individual line items for the payslip.

## 4. Indonesian Specific Logic

### BPJS Calculation
- **BPJS Ketenagakerjaan**:
    - JKK: Employer paid (rate depends on risk, usually 0.24%).
    - JKM: Employer paid (0.3%).
    - JHT: 3.7% Employer, 2% Employee.
    - JP (Pension): 2% Employer, 1% Employee (with salary cap).
- **BPJS Kesehatan**:
    - 4% Employer, 1% Employee (with salary cap).

### PPh21 Tax
- Support for **TER (Tarif Efektif Rata-rata)** 2024 for monthly calculations.
- Support for year-end calculation using progressive rates (5% to 35%) and PTKP deductions.

### THR (Tunjangan Hari Raya)
- Standard calculation: 1 month salary for >= 1 year service, prorated for < 1 year.
- Taxable under PPh21.

## 5. Implementation Steps

### Phase 1: Database & Models
1. Create migrations for all tables mentioned above.
2. Define models and relationships.
3. Add specialized Traits for Indonesian Payroll (e.g., `CalculatesBPJS`, `CalculatesPPh21`).

### Phase 2: HR Filament Resources
1. Create `EmployeeResource`.
2. Create `SalaryComponentResource`.
3. Create `DepartmentResource` (enhance existing if needed).

### Phase 3: Payroll Processing Logic
1. Implement a Service class `PayrollService` to handle complex calculations.
2. Create `PayrollPeriodResource`.
3. Add a "Generate Payslips" action to `PayrollPeriodResource` that creates `Payslip` records for all active employees.

### Phase 4: Accounting Integration
1. Define a mapping system to link salary components to GL Accounts.
2. Create a "Post to Ledger" action that generates a `JournalEntry` from a `PayrollPeriod`.
    - Debit: Salary Expense, BPJS Expense (Employer portion).
    - Credit: Salary Payable, BPJS Payable, PPh21 Payable.

### Phase 5: Reporting & PDF
1. Implement Payslip PDF generation using `dompdf` (already in `config/dompdf.php`).
2. Add "Download Payslip" action to `PayslipResource`.

## 6. Verification & Testing
1. Unit tests for PPh21 calculation logic with various PTKP statuses.
2. Integration tests for Journal Entry generation.
3. UI testing for the Payroll generation workflow.

## 7. Migration & Rollback
- New tables are additive and won't affect existing data.
- Rollback will involve dropping the new tables.
