<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\RegulationFile;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LandingController extends Controller
{
     /**
     * Halaman Dashboard
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Halaman Landing
     */
    public function index()
    {
        $events = Event::where('is_active', true)
            ->with('photos')
            ->latest()
            ->get()
            ->map(function ($event) {

                $event->type_formatted = match ($event->type) {
                    'race'     => 'Roadrace',
                    'drag'     => 'Dragrace',
                    'dragbike' => 'Dragbike',
                    default    => ucfirst($event->type),
                };

                $event->photo_url = $event->photo
                    ? asset('storage/' . $event->photo->path)
                    : null;

                return $event;
            });

        $documentationEvents = Event::where('link_documentation_active', true)
            ->whereNotNull('link_documentation')
            ->latest()
            ->get();

        $regulations = RegulationFile::where('is_active', true)->get();

        return view('landing.landing', compact('events', 'regulations', 'documentationEvents'));
    }

    public function showEvent($id)
    {
        try {

            $event = Event::with([
                'photos',
                'classes',
            ])->findOrFail($id);

            if (!$event->is_active) {
                throw new \Exception('Event tidak tersedia atau telah dinonaktifkan.');
            }

            $event->type_formatted = match ($event->type) {
                'race'     => 'Roadrace',
                'drag'     => 'Dragrace',
                'dragbike' => 'Dragbike',
                default    => ucfirst($event->type),
            };


            $event->location =
                $event->venue . ', ' .
                $event->kota . ', ' .
                $event->provinsi;

            $event->registration_date_formatted =
                ($event->registration_start_date && $event->registration_end_date)
                    ? Carbon::parse($event->registration_start_date)->translatedFormat('d F Y H:i')
                        . ' - ' .
                    Carbon::parse($event->registration_end_date)->translatedFormat('d F Y H:i')
                    : null;

            $event->registration_end_date_formatted =
                $event->registration_end_date
                    ? Carbon::parse($event->registration_end_date)->translatedFormat('d F Y H:i')
                    : null;

            $event->event_date_formatted =
                ($event->start_date && $event->end_date)
                    ? (
                        Carbon::parse($event->start_date)->isSameDay(Carbon::parse($event->end_date))
                            ? Carbon::parse($event->start_date)->translatedFormat('d F Y')
                            : Carbon::parse($event->start_date)->translatedFormat('d F Y')
                                . ' - ' .
                            Carbon::parse($event->end_date)->translatedFormat('d F Y')
                    )
                    : null;

            $event->registration_start_date_formatted_input =
                $event->registration_start_date
                    ? Carbon::parse($event->registration_start_date)->format('Y-m-d\TH:i')
                    : null;

            $event->registration_end_date_formatted_input =
                $event->registration_end_date
                    ? Carbon::parse($event->registration_end_date)->format('Y-m-d\TH:i')
                    : null;

            $event->start_date_formatted_input =
                $event->start_date
                    ? Carbon::parse($event->start_date)->format('Y-m-d')
                    : null;

            $event->end_date_formatted_input =
                $event->end_date
                    ? Carbon::parse($event->end_date)->format('Y-m-d')
                    : null;

            $event->is_late_registration =
                now()->gt(Carbon::parse($event->registration_end_date));

            $isLateRegistration = now()->gt($event->registration_end_date);

            $event->classes->transform(function ($class) use ($isLateRegistration) {

                $class->final_price = $isLateRegistration
                    ? ($class->price + $class->price_fine)
                    : $class->price;

                return $class;
            });

            $event->photo_url = optional($event->photo)->path
                ? asset('storage/' . $event->photo->path)
                : asset('assets/landing/img/poster_1.png');

            return view('landing.event', compact('event'));

        } catch (ModelNotFoundException $e) {

            return redirect('/')
                ->with('error', 'Event tidak ditemukan.');

        } catch (\Exception $e) {

            return redirect('/')
                ->with('error', $e->getMessage());
        }
    }

}
