<?php

namespace App\Http\Controllers;

use App\Models\JournalEntry;
use Illuminate\Http\Request;

class JournalVoucherController extends Controller
{
    public function print($id)
    {
        $journalEntry = JournalEntry::with([
            'items.account',
            'items.costCenter',
            'department',
            'company',
            'postedByUser',
            'createdByUser'
        ])->findOrFail($id);

        return view('journal-voucher.print', [
            'journalEntry' => $journalEntry
        ]);
    }

    public function printVoucher($id)
    {
        $journalEntry = JournalEntry::with([
            'items.account',
            'items.costCenter',
            'department',
            'company',
            'postedByUser',
            'createdByUser'
        ])->findOrFail($id);

        return view('journal-voucher.print-voucher', [
            'journalEntry' => $journalEntry
        ]);
    }

    public function pdf($id)
    {
        $journalEntry = JournalEntry::with([
            'items.account',
            'items.costCenter',
            'department',
            'company',
            'postedByUser',
            'createdByUser'
        ])->findOrFail($id);

        // Check if DomPDF is available
        if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('journal-voucher.pdf', [
                'journalEntry' => $journalEntry
            ]);

            $filename = 'Journal_Voucher_' . $journalEntry->entry_number . '.pdf';

            return $pdf->download($filename);
        }

        // Fallback: return HTML view if PDF library is not available
        return view('journal-voucher.pdf', [
            'journalEntry' => $journalEntry
        ]);
    }
}

