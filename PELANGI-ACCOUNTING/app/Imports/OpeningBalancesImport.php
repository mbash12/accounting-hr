<?php

namespace App\Imports;

use App\Models\Account;
use App\Models\OpeningBalance;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class OpeningBalancesImport implements ToCollection, WithHeadingRow, WithValidation
{
    public function collection(Collection $rows)
    {
        $companyId = session('selected_company_id') && session('selected_company_id') !== 'all' ? session('selected_company_id') : null;

        if (!$companyId) {
            throw new \Exception('Company ID tidak ditemukan. Silakan pilih perusahaan terlebih dahulu.');
        }

        DB::beginTransaction();

        try {
            foreach ($rows as $row) {
                $code = (string) $row['account_code'];
                $debitAmount = (float) ($row['debit_amount'] ?? 0);
                $creditAmount = (float) ($row['credit_amount'] ?? 0);

                $account = Account::where('code', $code)
                    ->where('company_id', $companyId)
                    ->first();

                if (!$account) {
                    throw new \Exception("Account dengan kode {$code} tidak ditemukan.");
                }

                if ($debitAmount > 0 && $creditAmount > 0) {
                    throw new \Exception("Account {$code} tidak boleh memiliki debit dan credit bersamaan.");
                }

                if ($debitAmount > 0) {
                    OpeningBalance::updateOrCreate(
                        [
                            'account_id' => $account->id,
                            'company_id' => $companyId,
                        ],
                        [
                            'balance_type' => 'debit',
                            'amount' => $debitAmount,
                            'date' => now()->startOfYear()->format('Y-m-d'),
                            'description' => __('Opening balance for :account', ['account' => $account->name]),
                            'created_by_user_id' => Auth::check() ? Auth::id() : session('current_user_id'),
                        ]
                    );
                } elseif ($creditAmount > 0) {
                    OpeningBalance::updateOrCreate(
                        [
                            'account_id' => $account->id,
                            'company_id' => $companyId,
                        ],
                        [
                            'balance_type' => 'credit',
                            'amount' => $creditAmount,
                            'date' => now()->startOfYear()->format('Y-m-d'),
                            'description' => __('Opening balance for :account', ['account' => $account->name]),
                            'created_by_user_id' => Auth::check() ? Auth::id() : session('current_user_id'),
                        ]
                    );
                } else {
                    OpeningBalance::where('account_id', $account->id)
                        ->where('company_id', $companyId)
                        ->delete();
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function prepareForValidation($data, $index)
    {
        return [
            'account_code' => isset($data['account_code']) ? (string) $data['account_code'] : null,
            'debit_amount' => isset($data['debit_amount']) ? (string) $data['debit_amount'] : null,
            'credit_amount' => isset($data['credit_amount']) ? (string) $data['credit_amount'] : null,
        ];
    }

    public function rules(): array
    {
        return [
            'account_code' => 'required|string|max:50',
            'debit_amount' => 'nullable|numeric|min:0',
            'credit_amount' => 'nullable|numeric|min:0',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'account_code.required' => 'Kode Akun wajib diisi.',
            'debit_amount.numeric' => 'Jumlah Debit harus berupa angka.',
            'credit_amount.numeric' => 'Jumlah Credit harus berupa angka.',
        ];
    }
}
