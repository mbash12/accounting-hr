<?php

namespace App\Services\DataCleanup;

use App\Models\Account;
use App\Models\AccountMapping;
use App\Models\AdvanceDisbursement;
use App\Models\AdvancePayment;
use App\Models\AdvanceReceipt;
use App\Models\Attendance;
use App\Models\AttendanceSpot;
use App\Models\Bank;
use App\Models\BankAccount;
use App\Models\BankReconciliation;
use App\Models\BonusCalculation;
use App\Models\CashBankTransaction;
use App\Models\CashDisbursement;
use App\Models\CashReceipt;
use App\Models\CashTransfer;
use App\Models\CheckDisbursement;
use App\Models\Contact;
use App\Models\DeferredRevenue;
use App\Models\Department;
use App\Models\DeliveryDocument;
use App\Models\Employee;
use App\Models\EmployeeLeaveQuota;
use App\Models\Expedition;
use App\Models\FixedAsset;
use App\Models\FixedAssetCategory;
use App\Models\GoodsReceipt;
use App\Models\Holiday;
use App\Models\JournalEntry;
use App\Models\OpeningBalance;
use App\Models\OvertimeLog;
use App\Models\OvertimeRule;
use App\Models\PayablePayment;
use App\Models\PaymentTerm;
use App\Models\PayrollPeriod;
use App\Models\Permit;
use App\Models\PeriodClosing;
use App\Models\Product;
use App\Models\ProductGroup;
use App\Models\PurchaseInvoice;
use App\Models\PurchaseOrder;
use App\Models\PurchaseReturn;
use App\Models\ReceivablePayment;
use App\Models\SalaryComponent;
use App\Models\SalesInvoice;
use App\Models\SalesOrder;
use App\Models\SalesReturn;
use App\Models\Tax;
use App\Models\THRCalculation;
use App\Models\TransactionClassification;
use App\Models\Unit;
use App\Models\UnitCategory;
use App\Models\Warehouse;
use App\Services\DataCleanupService;

class DataCleanupRegistry
{
    /**
     * @return array<string, DatasetDefinition>
     */
    public function all(): array
    {
        $defs = [
            // —— General Ledger (transactions first) ——
            $this->def(
                key: 'period_closings',
                label: 'Period Closings',
                group: 'General Ledger',
                description: 'Deletes period closing records for the company.',
                danger: true,
                order: 10,
                model: PeriodClosing::class,
            ),
            $this->def(
                key: 'journal_entries',
                label: 'Journal Entries',
                group: 'General Ledger',
                description: 'Deletes journal entries and their line items.',
                danger: true,
                order: 20,
                model: JournalEntry::class,
                children: [
                    ['table' => 'journal_entry_items', 'fk' => 'journal_entry_id'],
                    ['table' => 'account_journal_entry', 'fk' => 'journal_entry_id'],
                ],
            ),
            $this->def(
                key: 'bank_reconciliations',
                label: 'Bank Reconciliations',
                group: 'General Ledger',
                description: 'Deletes bank reconciliations and items.',
                danger: true,
                order: 30,
                model: BankReconciliation::class,
                children: [
                    ['table' => 'bank_reconciliation_items', 'fk' => 'bank_reconciliation_id'],
                ],
            ),
            $this->def(
                key: 'deferred_revenues',
                label: 'Deferred Revenues',
                group: 'General Ledger',
                description: 'Deletes deferred revenues and schedules.',
                danger: true,
                order: 40,
                model: DeferredRevenue::class,
                children: [
                    ['table' => 'deferred_revenue_schedules', 'fk' => 'deferred_revenue_id'],
                ],
            ),
            $this->def(
                key: DataCleanupService::DATASET_OPENING_BALANCES,
                label: 'Opening Balances',
                group: 'General Ledger',
                description: 'Deletes opening balance records.',
                danger: false,
                order: 50,
                model: OpeningBalance::class,
            ),
            $this->def(
                key: DataCleanupService::DATASET_ACCOUNT_MAPPINGS,
                label: 'Account Mappings',
                group: 'General Ledger',
                description: 'Deletes document account mappings.',
                danger: false,
                order: 60,
                model: AccountMapping::class,
            ),
            $this->def(
                key: DataCleanupService::DATASET_CHART_OF_ACCOUNTS,
                label: 'Chart of Accounts',
                group: 'General Ledger',
                description: 'Deletes all accounts. Cascade also clears mappings, opening balances, taxes, and FA categories. Nullify clears account FKs where nullable.',
                danger: true,
                order: 900,
                model: Account::class,
                handler: 'chart_of_accounts',
                cascadeRelated: [
                    DataCleanupService::DATASET_ACCOUNT_MAPPINGS,
                    DataCleanupService::DATASET_OPENING_BALANCES,
                    DataCleanupService::DATASET_TAXES,
                    DataCleanupService::DATASET_FIXED_ASSET_CATEGORIES,
                ],
                nullify: [
                    ['table' => 'bank_accounts', 'column' => 'coa_account_id'],
                    ['table' => 'salary_components', 'column' => 'account_id', 'company_scoped' => true],
                    ['table' => 'sales_orders', 'column' => 'other_charges_account_id', 'company_scoped' => true],
                    ['table' => 'sales_orders', 'column' => 'discount_account_id', 'company_scoped' => true],
                    ['table' => 'sales_invoices', 'column' => 'other_charges_account_id', 'company_scoped' => true],
                    ['table' => 'sales_invoices', 'column' => 'discount_account_id', 'company_scoped' => true],
                    ['table' => 'purchase_orders', 'column' => 'other_charges_account_id', 'company_scoped' => true],
                    ['table' => 'purchase_orders', 'column' => 'discount_account_id', 'company_scoped' => true],
                    ['table' => 'purchase_invoices', 'column' => 'other_charges_account_id', 'company_scoped' => true],
                    ['table' => 'purchase_invoices', 'column' => 'discount_account_id', 'company_scoped' => true],
                    ['table' => 'purchase_invoices', 'column' => 'advance_payment_account_id', 'company_scoped' => true],
                    ['table' => 'receivable_payments', 'column' => 'other_costs_account_id', 'company_scoped' => true],
                    ['table' => 'payable_payments', 'column' => 'other_costs_account_id', 'company_scoped' => true],
                    ['table' => 'sales_order_other_charges', 'column' => 'account_id'],
                    ['table' => 'sales_invoice_other_charges', 'column' => 'account_id'],
                    ['table' => 'purchase_order_other_charges', 'column' => 'account_id'],
                    ['table' => 'purchase_invoice_other_charges', 'column' => 'account_id'],
                    ['table' => 'taxes', 'column' => 'purchase_account_id', 'company_scoped' => true],
                    ['table' => 'taxes', 'column' => 'sales_account_id', 'company_scoped' => true],
                    ['table' => 'fixed_asset_categories', 'column' => 'asset_account_id', 'company_scoped' => true],
                    ['table' => 'fixed_asset_categories', 'column' => 'depreciation_account_id', 'company_scoped' => true],
                    ['table' => 'fixed_asset_categories', 'column' => 'accumulated_depreciation_account_id', 'company_scoped' => true],
                    ['table' => 'fixed_asset_categories', 'column' => 'sales_account_id', 'company_scoped' => true],
                ],
                nullifyBlockers: [
                    ['table' => 'journal_entry_items', 'column' => 'account_id', 'label' => 'journal entry items'],
                ],
            ),

            // —— Sales ——
            $this->def(
                key: 'receivable_payments',
                label: 'Receivable Payments',
                group: 'Sales',
                description: 'Deletes receivable payments and allocation items.',
                danger: true,
                order: 100,
                model: ReceivablePayment::class,
                children: [
                    ['table' => 'receivable_payment_items', 'fk' => 'receivable_payment_id'],
                ],
            ),
            $this->def(
                key: 'advance_receipts',
                label: 'Advance Receipts',
                group: 'Sales',
                description: 'Deletes advance receipts and items.',
                danger: true,
                order: 110,
                model: AdvanceReceipt::class,
                children: [
                    ['table' => 'advance_receipt_items', 'fk' => 'advance_receipt_id'],
                ],
            ),
            $this->def(
                key: 'sales_returns',
                label: 'Sales Returns',
                group: 'Sales',
                description: 'Deletes sales returns and items.',
                danger: true,
                order: 120,
                model: SalesReturn::class,
                children: [
                    ['table' => 'sales_return_items', 'fk' => 'sales_return_id'],
                ],
            ),
            $this->def(
                key: 'sales_invoices',
                label: 'Sales Invoices',
                group: 'Sales',
                description: 'Deletes sales invoices, items, and other charges.',
                danger: true,
                order: 130,
                model: SalesInvoice::class,
                children: [
                    ['table' => 'sales_invoice_items', 'fk' => 'sales_invoice_id'],
                    ['table' => 'sales_invoice_other_charges', 'fk' => 'sales_invoice_id'],
                ],
            ),
            $this->def(
                key: 'sales_deliveries',
                label: 'Sales Deliveries',
                group: 'Sales',
                description: 'Deletes delivery documents and items.',
                danger: true,
                order: 140,
                model: DeliveryDocument::class,
                children: [
                    ['table' => 'delivery_document_items', 'fk' => 'delivery_document_id'],
                ],
            ),
            $this->def(
                key: 'sales_orders',
                label: 'Sales Orders',
                group: 'Sales',
                description: 'Deletes sales orders, items, and other charges.',
                danger: true,
                order: 150,
                model: SalesOrder::class,
                children: [
                    ['table' => 'sales_order_items', 'fk' => 'sales_order_id'],
                    ['table' => 'sales_order_other_charges', 'fk' => 'sales_order_id'],
                ],
            ),

            // —— Purchasing ——
            $this->def(
                key: 'payable_payments',
                label: 'Payable Payments',
                group: 'Purchasing',
                description: 'Deletes payable payments and allocation items.',
                danger: true,
                order: 200,
                model: PayablePayment::class,
                children: [
                    ['table' => 'payable_payment_items', 'fk' => 'payable_payment_id'],
                ],
            ),
            $this->def(
                key: 'advance_disbursements',
                label: 'Advance Disbursements',
                group: 'Purchasing',
                description: 'Deletes advance disbursements and items.',
                danger: true,
                order: 210,
                model: AdvanceDisbursement::class,
                children: [
                    ['table' => 'advance_disbursement_items', 'fk' => 'advance_disbursement_id'],
                ],
            ),
            $this->def(
                key: 'advance_payments',
                label: 'Advance Payments',
                group: 'Purchasing',
                description: 'Deletes advance payments and allocations.',
                danger: true,
                order: 220,
                model: AdvancePayment::class,
                children: [
                    ['table' => 'advance_payment_allocations', 'fk' => 'advance_payment_id'],
                ],
            ),
            $this->def(
                key: 'purchase_returns',
                label: 'Purchase Returns',
                group: 'Purchasing',
                description: 'Deletes purchase returns and items.',
                danger: true,
                order: 230,
                model: PurchaseReturn::class,
                children: [
                    ['table' => 'purchase_return_items', 'fk' => 'purchase_return_id'],
                ],
            ),
            $this->def(
                key: 'purchase_invoices',
                label: 'Purchase Invoices',
                group: 'Purchasing',
                description: 'Deletes purchase invoices, items, and other charges.',
                danger: true,
                order: 240,
                model: PurchaseInvoice::class,
                children: [
                    ['table' => 'purchase_invoice_items', 'fk' => 'purchase_invoice_id'],
                    ['table' => 'purchase_invoice_other_charges', 'fk' => 'purchase_invoice_id'],
                ],
            ),
            $this->def(
                key: 'goods_receipts',
                label: 'Goods Receipts',
                group: 'Purchasing',
                description: 'Deletes goods receipts and items.',
                danger: true,
                order: 250,
                model: GoodsReceipt::class,
                children: [
                    ['table' => 'goods_receipt_items', 'fk' => 'goods_receipt_id'],
                ],
            ),
            $this->def(
                key: 'purchase_orders',
                label: 'Purchase Orders',
                group: 'Purchasing',
                description: 'Deletes purchase orders, items, and other charges.',
                danger: true,
                order: 260,
                model: PurchaseOrder::class,
                children: [
                    ['table' => 'purchase_order_items', 'fk' => 'purchase_order_id'],
                    ['table' => 'purchase_order_other_charges', 'fk' => 'purchase_order_id'],
                ],
            ),

            // —— Cash & Bank ——
            $this->def(
                key: 'cash_bank_transactions',
                label: 'Cash/Bank Transactions',
                group: 'Cash & Bank',
                description: 'Deletes cash and bank transaction ledger rows.',
                danger: true,
                order: 300,
                model: CashBankTransaction::class,
            ),
            $this->def(
                key: 'check_disbursements',
                label: 'Check Disbursements',
                group: 'Cash & Bank',
                description: 'Deletes check disbursements.',
                danger: true,
                order: 310,
                model: CheckDisbursement::class,
            ),
            $this->def(
                key: 'cash_transfers',
                label: 'Cash Transfers',
                group: 'Cash & Bank',
                description: 'Deletes cash transfers.',
                danger: true,
                order: 320,
                model: CashTransfer::class,
            ),
            $this->def(
                key: 'cash_disbursements',
                label: 'Cash Disbursements',
                group: 'Cash & Bank',
                description: 'Deletes cash disbursements and items.',
                danger: true,
                order: 330,
                model: CashDisbursement::class,
                children: [
                    ['table' => 'cash_disbursement_items', 'fk' => 'cash_disbursement_id'],
                ],
            ),
            $this->def(
                key: 'cash_receipts',
                label: 'Cash Receipts',
                group: 'Cash & Bank',
                description: 'Deletes cash receipts and items.',
                danger: true,
                order: 340,
                model: CashReceipt::class,
                children: [
                    ['table' => 'cash_receipt_items', 'fk' => 'cash_receipt_id'],
                ],
            ),

            // —— HR & Payroll ——
            $this->def(
                key: 'payroll_periods',
                label: 'Payroll Periods',
                group: 'HR & Payroll',
                description: 'Deletes payroll periods, payslips, and payslip items.',
                danger: true,
                order: 400,
                model: PayrollPeriod::class,
                handler: 'payroll_periods',
            ),
            $this->def(
                key: 'bonus_calculations',
                label: 'Bonus Calculations',
                group: 'HR & Payroll',
                description: 'Deletes bonus calculations and items.',
                danger: true,
                order: 410,
                model: BonusCalculation::class,
                children: [
                    ['table' => 'bonus_calculation_items', 'fk' => 'bonus_calculation_id'],
                ],
            ),
            $this->def(
                key: 'thr_calculations',
                label: 'THR Calculations',
                group: 'HR & Payroll',
                description: 'Deletes THR calculations and items.',
                danger: true,
                order: 420,
                model: THRCalculation::class,
                children: [
                    ['table' => 'thr_calculation_items', 'fk' => 'thr_calculation_id'],
                ],
            ),
            $this->def(
                key: 'overtime_logs',
                label: 'Overtime Logs',
                group: 'HR & Payroll',
                description: 'Deletes overtime logs.',
                danger: false,
                order: 430,
                model: OvertimeLog::class,
            ),
            $this->def(
                key: 'attendances',
                label: 'Attendances',
                group: 'HR & Payroll',
                description: 'Deletes attendance records.',
                danger: false,
                order: 440,
                model: Attendance::class,
            ),
            $this->def(
                key: 'employee_leave_quotas',
                label: 'Employee Leave Quotas',
                group: 'HR & Payroll',
                description: 'Deletes employee leave quotas.',
                danger: false,
                order: 450,
                model: EmployeeLeaveQuota::class,
            ),
            $this->def(
                key: 'permits',
                label: 'Permits',
                group: 'HR & Payroll',
                description: 'Deletes employee permits.',
                danger: false,
                order: 460,
                model: Permit::class,
            ),
            $this->def(
                key: 'employees',
                label: 'Employees',
                group: 'HR & Payroll',
                description: 'Deletes employees and salary component links.',
                danger: true,
                order: 470,
                model: Employee::class,
                children: [
                    ['table' => 'employee_salary_components', 'fk' => 'employee_id'],
                ],
            ),
            $this->def(
                key: 'salary_components',
                label: 'Salary Components',
                group: 'HR & Payroll',
                description: 'Deletes salary component masters.',
                danger: false,
                order: 480,
                model: SalaryComponent::class,
            ),
            $this->def(
                key: 'overtime_rules',
                label: 'Overtime Rules',
                group: 'HR & Payroll',
                description: 'Deletes overtime rules.',
                danger: false,
                order: 490,
                model: OvertimeRule::class,
            ),
            $this->def(
                key: 'departments',
                label: 'Departments',
                group: 'HR & Payroll',
                description: 'Deletes departments.',
                danger: false,
                order: 500,
                model: Department::class,
            ),
            $this->def(
                key: 'attendance_spots',
                label: 'Attendance Spots',
                group: 'HR & Payroll',
                description: 'Deletes attendance spots.',
                danger: false,
                order: 510,
                model: AttendanceSpot::class,
            ),
            $this->def(
                key: 'holidays',
                label: 'Holidays',
                group: 'HR & Payroll',
                description: 'Deletes company holidays.',
                danger: false,
                order: 520,
                model: Holiday::class,
            ),

            // —— Master Data ——
            $this->def(
                key: 'fixed_assets',
                label: 'Fixed Assets',
                group: 'Master Data',
                description: 'Deletes fixed assets, depreciations, disposals, and transactions.',
                danger: true,
                order: 600,
                model: FixedAsset::class,
                children: [
                    ['table' => 'fixed_asset_depreciations', 'fk' => 'fixed_asset_id'],
                    ['table' => 'fixed_asset_disposals', 'fk' => 'fixed_asset_id'],
                    ['table' => 'fixed_asset_transactions', 'fk' => 'fixed_asset_id'],
                ],
            ),
            $this->def(
                key: DataCleanupService::DATASET_FIXED_ASSET_CATEGORIES,
                label: 'Fixed Asset Categories',
                group: 'Master Data',
                description: 'Deletes fixed asset categories.',
                danger: false,
                order: 610,
                model: FixedAssetCategory::class,
            ),
            $this->def(
                key: DataCleanupService::DATASET_TAXES,
                label: 'Taxes',
                group: 'Master Data',
                description: 'Deletes tax masters.',
                danger: false,
                order: 620,
                model: Tax::class,
            ),
            $this->def(
                key: 'products',
                label: 'Products',
                group: 'Master Data',
                description: 'Deletes products and product units.',
                danger: true,
                order: 630,
                model: Product::class,
                children: [
                    ['table' => 'product_units', 'fk' => 'product_id'],
                ],
            ),
            $this->def(
                key: 'product_groups',
                label: 'Product Groups',
                group: 'Master Data',
                description: 'Deletes product groups.',
                danger: false,
                order: 640,
                model: ProductGroup::class,
            ),
            $this->def(
                key: 'units',
                label: 'Units',
                group: 'Master Data',
                description: 'Deletes units of measure.',
                danger: false,
                order: 650,
                model: Unit::class,
            ),
            $this->def(
                key: 'unit_categories',
                label: 'Unit Categories',
                group: 'Master Data',
                description: 'Deletes unit categories.',
                danger: false,
                order: 660,
                model: UnitCategory::class,
            ),
            $this->def(
                key: 'warehouses',
                label: 'Warehouses',
                group: 'Master Data',
                description: 'Deletes warehouses.',
                danger: false,
                order: 670,
                model: Warehouse::class,
            ),
            $this->def(
                key: 'expeditions',
                label: 'Expeditions',
                group: 'Master Data',
                description: 'Deletes expeditions.',
                danger: false,
                order: 680,
                model: Expedition::class,
            ),
            $this->def(
                key: 'bank_accounts',
                label: 'Bank Accounts',
                group: 'Master Data',
                description: 'Deletes company bank accounts.',
                danger: true,
                order: 690,
                model: BankAccount::class,
            ),
            $this->def(
                key: 'banks',
                label: 'Banks',
                group: 'Master Data',
                description: 'Deletes bank masters.',
                danger: false,
                order: 700,
                model: Bank::class,
            ),
            $this->def(
                key: 'contacts',
                label: 'Contacts',
                group: 'Master Data',
                description: 'Deletes contacts (customers/vendors).',
                danger: true,
                order: 710,
                model: Contact::class,
            ),
            $this->def(
                key: 'payment_terms',
                label: 'Payment Terms',
                group: 'Master Data',
                description: 'Deletes payment terms.',
                danger: false,
                order: 720,
                model: PaymentTerm::class,
            ),
            $this->def(
                key: 'transaction_classifications',
                label: 'Transaction Classifications',
                group: 'Master Data',
                description: 'Deletes transaction classifications.',
                danger: false,
                order: 730,
                model: TransactionClassification::class,
            ),
        ];

        $keyed = [];
        foreach ($defs as $def) {
            $keyed[$def->key] = $def;
        }

        return $keyed;
    }

    public function get(string $key): ?DatasetDefinition
    {
        return $this->all()[$key] ?? null;
    }

    /**
     * @param  list<array{table: string, fk: string}>  $children
     * @param  list<string>  $cascadeRelated
     * @param  list<array{table: string, column: string, company_scoped?: bool}>  $nullify
     * @param  list<array{table: string, column: string, label: string}>  $nullifyBlockers
     */
    private function def(
        string $key,
        string $label,
        string $group,
        string $description,
        bool $danger,
        int $order,
        ?string $model = null,
        ?string $handler = null,
        array $children = [],
        array $cascadeRelated = [],
        array $nullify = [],
        array $nullifyBlockers = [],
    ): DatasetDefinition {
        return new DatasetDefinition(
            key: $key,
            label: $label,
            group: $group,
            description: $description,
            danger: $danger,
            order: $order,
            model: $model,
            handler: $handler,
            children: $children,
            cascadeRelated: $cascadeRelated,
            nullify: $nullify,
            nullifyBlockers: $nullifyBlockers,
        );
    }
}
