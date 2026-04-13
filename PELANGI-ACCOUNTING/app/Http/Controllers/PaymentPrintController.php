<?php

namespace App\Http\Controllers;

use App\Models\ReceivablePayment;
use App\Models\PayablePayment;
use Illuminate\Http\Request;

class PaymentPrintController extends Controller
{
    public function printReceivable($id)
    {
        $payment = ReceivablePayment::with([
            'customer',
            'company',
            'bankAccount',
            'account',
            'items.salesInvoice',
            'createdByUser'
        ])->findOrFail($id);

        return view('receivable-payment.print', [
            'payment' => $payment
        ]);
    }

    public function printPayable($id)
    {
        $payment = PayablePayment::with([
            'supplier',
            'company',
            'bankAccount',
            'account',
            'items.purchaseInvoice',
            'createdByUser'
        ])->findOrFail($id);

        return view('payable-payment.print', [
            'payment' => $payment
        ]);
    }
}
