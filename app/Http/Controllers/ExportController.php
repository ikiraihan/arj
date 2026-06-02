<?php

namespace App\Http\Controllers;

use App\Exports\RegistrationClassExport;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function exportRace(Request $request, $eventId)
    {
        return Excel::download(
            new RegistrationClassExport(array_merge($request->all(), ['event_id' => $eventId])),
            'registrasi-balap-'. $eventId . '-' . now()->format('YmdHis') . '.xlsx'
        );
    }
}

