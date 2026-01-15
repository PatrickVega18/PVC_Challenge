<?php

namespace App\Http\Controllers;

use App\Services\CreditReportService;
use Illuminate\Http\Request;

class CreditReportController extends Controller
{
    public function export(Request $request, CreditReportService $service)
    {
        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        $service->exportStream($startDate, $endDate);
    }
}
