<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\ProfitLossService;


class ProfitAndLossController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->month;
        $year  = $request->year;

        $plService = new ProfitLossService($month, $year);
        $data = $plService->calculate();

        return view('reports.profit_and_loss.index', array_merge(
            compact('month', 'year'),
            $data
        ));
    }

    public function print(Request $request)
    {
        $month = $request->month;
        $year  = $request->year;

        $plService = new ProfitLossService($month, $year);
        $data = $plService->calculate();

        // Sertakan month & year
        $data['month'] = $month;
        $data['year'] = $year;

        return view('reports.profit_and_loss.print', $data);
    }
}
