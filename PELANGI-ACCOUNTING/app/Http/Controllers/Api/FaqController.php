<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FaqCategory;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $employee = $request->attributes->get('employee');
        
        $categories = FaqCategory::where('company_id', $employee->company_id)
            ->with(['faqs' => function($query) {
                $query->orderBy('sort_order');
            }])
            ->orderBy('sort_order')
            ->get();
            
        return response()->json([
            'records' => $categories
        ]);
    }
}
