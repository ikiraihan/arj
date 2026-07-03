<?php

namespace App\Http\Controllers;

use App\Exports\RegistrationClassExport;
use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function index()
    {
        $events = Event::all();
        return view('admin.export.index', compact('events'));
    }
    public function exportRace(Request $request, $eventId)
    {
        return Excel::download(
            new RegistrationClassExport(array_merge($request->all(), ['event_id' => $eventId])),
            'registrasi-balap-'. $eventId . '-' . now()->format('YmdHis') . '.xlsx'
        );
    }

    // public function exportRace(Request $request, $eventId)
    // {
    //     switch ($request->type) {
    //         case 'original':
    //             return Excel::download(
    //                 new RegistrationClassExport(array_merge($request->all(), ['event_id' => $eventId])),
    //                 'registrasi-balap-original-'. $eventId . '-' . now()->format('YmdHis') . '.xlsx'
    //             );
    //         default:
    //             return Excel::download(
    //                 new RegistrationClassExport(array_merge($request->all(), ['event_id' => $eventId])),
    //                 'registrasi-balap-'. $eventId . '-' . now()->format('YmdHis') . '.xlsx'
    //             );
    //     }
    // }
}

