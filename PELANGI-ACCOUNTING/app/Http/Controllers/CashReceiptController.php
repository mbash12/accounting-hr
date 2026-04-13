<?php

namespace App\Http\Controllers;

use App\Models\CashReceipt;
use Illuminate\Http\Request;

class CashReceiptController extends Controller
{
    public function printVoucher($id)
    {
        $cashReceipt = CashReceipt::with([
            'items.account',
            'toAccount',
            'company',
            'createdByUser'
        ])->findOrFail($id);

        return view('cash-receipt.print-voucher', [
            'cashReceipt' => $cashReceipt
        ]);
    }
}
