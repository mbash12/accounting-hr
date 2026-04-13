<?php

namespace App\Http\Controllers;

use App\Models\CashDisbursement;
use Illuminate\Http\Request;

class CashDisbursementController extends Controller
{
    public function printVoucher($id)
    {
        $cashDisbursement = CashDisbursement::with([
            'items.account',
            'fromAccount',
            'company',
            'createdByUser'
        ])->findOrFail($id);

        return view('cash-disbursement.print-voucher', [
            'cashDisbursement' => $cashDisbursement
        ]);
    }
}
