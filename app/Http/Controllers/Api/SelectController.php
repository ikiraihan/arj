<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\EventClass;
use Illuminate\Http\Request;
use App\Models\Racer;
use App\Models\Registration;

class SelectController extends Controller
{
    public function getRegistrationNumbers(Request $request, $eventId = null)
    {
        $keyword = $request->get('keyword');

        $racers = Registration::query()
            ->when($eventId, function ($query) use ($eventId) {
                $query->where('event_id', $eventId);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('registration_number', 'like', "%{$keyword}%");
            })
            ->orderBy('id')
            // ->limit(20)
            ->get([
                'id',
                'registration_number'
            ]);

        return response()->json([
            'data' => $racers->map(function ($racer) {
                return [
                    'id' => $racer->registration_number,
                    'text' => $racer->registration_number,
                ];
            }),
        ]);
    }

    public function getRacers(Request $request, $eventId = null)
    {
        $keyword = $request->keyword;

        $racerIds = Registration::where('event_id', $eventId)
            ->pluck('racer_id');

        $racers = Racer::query()
            ->whereIn('id', $racerIds)
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('full_name', 'like', "%{$keyword}%")
                    ->orWhere('short_name', 'like', "%{$keyword}%");
                });
            })
            ->orderBy('full_name')
            // ->limit(20)
            ->get([
                'id',
                'full_name',
                'short_name',
            ]);

        return response()->json([
            'data' => $racers->map(function ($racer) {
                return [
                    'id' => $racer->id,
                    'text' => "{$racer->full_name} ({$racer->short_name})",
                ];
            }),
        ]);
    }

    public function getTeamNames(Request $request, $eventId = null)
    {
        $keyword = $request->get('keyword');

        $teams = Registration::query()
            ->when($eventId, function ($query) use ($eventId) {
                $query->where('event_id', $eventId);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('team_name', 'like', "%{$keyword}%");
            })
            ->whereNotNull('team_name')
            ->where('team_name', '<>', '')
            ->select('team_name')
            ->distinct()
            ->orderBy('team_name')
            // ->limit(20)
            ->get();

        return response()->json([
            'data' => $teams->map(function ($team) {
                return [
                    'id' => $team->team_name,
                    'text' => $team->team_name,
                ];
            }),
        ]);
    }

    public function getEventClasses(Request $request, $eventId = null)
    {
        $keyword = $request->get('keyword');

        $classes = EventClass::query()
            ->when($eventId, function ($query) use ($eventId) {
                $query->where('event_id', $eventId);
            })
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('name', 'like', "%{$keyword}%");
            })
            ->orderBy('id')
            // ->limit(20)
            ->get([
                'id',
                'name'
            ]);

        return response()->json([
            'data' => $classes->map(function ($class) {
                return [
                    'id' => $class->id,
                    'text' => $class->name,
                ];
            }),
        ]);
    }
}
