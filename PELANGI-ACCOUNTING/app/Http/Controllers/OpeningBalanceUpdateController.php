<?php

namespace App\Http\Controllers;

use App\Models\OpeningBalance;
use App\Models\JournalEntry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OpeningBalanceUpdateController extends Controller
{
    /**
     * Update all existing opening balances and their associated journal entries
     * to the beginning of the year based on their current date.
     */
    public function updateDates(Request $request)
    {
        try {
            DB::beginTransaction();

            // 1. Update OpeningBalance records
            $openingBalances = OpeningBalance::all();
            $countOB = 0;
            foreach ($openingBalances as $ob) {
                $newDate = \Carbon\Carbon::parse($ob->date)->startOfYear()->format('Y-m-d');
                if ($ob->date !== $newDate) {
                    $ob->update(['date' => $newDate]);
                    $countOB++;
                }
            }

            // 2. Update JournalEntry records with sub_module 'opening_balance'
            $journalEntries = JournalEntry::where('sub_module', 'opening_balance')->get();
            $countJE = 0;
            foreach ($journalEntries as $je) {
                $newDate = \Carbon\Carbon::parse($je->date)->startOfYear()->format('Y-m-d');
                if ($je->date !== $newDate) {
                    $je->update(['date' => $newDate]);
                    $countJE++;
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "Successfully updated $countOB opening balance records and $countJE journal entries to the beginning of the year.",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update records: ' . $e->getMessage(),
            ], 500);
        }
    }
}
