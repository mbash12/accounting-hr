<?php

namespace App\Imports;

use App\Models\Bank;
use App\Models\BankAccount;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BankAccountsImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all'
            ? session('selected_company_id')
            : null;

        $banks = Bank::all()->keyBy(fn(Bank $b) => strtolower($b->code));

        foreach ($rows as $row) {
            $bankCode = strtolower((string) ($row['bank_code'] ?? ''));
            $bank = $banks->get($bankCode);

            if (! $bank) {
                continue;
            }

            $account = BankAccount::where('account_number', (string) $row['account_number'])
                ->where('bank_id', $bank->id)
                ->where('company_id', $companyId)
                ->first();

            $data = [
                'bank_id' => $bank->id,
                'account_name' => (string) ($row['account_name'] ?? ''),
                'account_type' => (string) ($row['account_type'] ?? 'checking'),
                'balance' => (float) ($row['balance'] ?? 0),
                'is_active' => $this->parseBoolean($row['active_status'] ?? 'yes'),
                'created_by_user_id' => Auth::check() ? Auth::id() : session('current_user_id'),
            ];

            if ($account) {
                $account->update($data);
            } else {
                $data['account_number'] = (string) $row['account_number'];
                $data['company_id'] = $companyId;
                BankAccount::create($data);
            }
        }
    }

    private function parseBoolean(string $value): bool
    {
        return in_array(strtolower($value), ['yes', 'true', '1', 'active'], true);
    }

    public function rules(): array
    {
        return [
            'bank_code' => 'required|string|max:20',
            'account_number' => 'required|string|max:50',
            'account_name' => 'required|string|max:200',
            'account_type' => 'nullable|string',
            'balance' => 'nullable|numeric',
            'active_status' => 'nullable|string',
        ];
    }

    public function customValidationMessages(): array
    {
        return [
            'bank_code.required' => 'Bank Code is required.',
            'account_number.required' => 'Account Number is required.',
            'account_name.required' => 'Account Name is required.',
        ];
    }
}
