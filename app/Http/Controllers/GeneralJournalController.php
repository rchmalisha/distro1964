<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GeneralJournal;

class GeneralJournalController extends Controller
{
    public function index(Request $request)
    {
        $query = GeneralJournal::with('details');

        // Filter berdasarkan tanggal
        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('tanggal_jurnal', [$request->from, $request->to]);
        }

        // Pagination & jumlah baris
        $entries = $request->get('entries', 10);
        $journals = $query->orderBy('tanggal_jurnal', 'desc')->paginate($entries);

        return view('accounting.journal.index', compact('journals'));
    }
}

