<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventClass;
use App\Models\Racer;
use App\Models\Registration;
use App\Models\RegistrationClass;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class RegistrationController extends Controller
{
    // public function index()
    // {
    //     return view('admin.registration.index');
    // }

    public function formRegistration($eventId)
    {
        $event = Event::findOrFail($eventId);

        $rekening = $event->paymentAccount;

        $isLate = $event->registration_end_date
            ? Carbon::now()->gt(Carbon::parse($event->registration_end_date))
            : false;

        $class = EventClass::where('event_id', $eventId)
            ->get()
            ->map(function ($item) use ($isLate) {

                $item->total_price = $isLate
                    ? ($item->price + $item->price_fine)
                    : $item->price;

                return $item;
            });

        return view('admin.registration.form', compact(
            'eventId',
            'event',
            'rekening',
            'class'
        ));
    }

    public function generatePdf(Request $request, $id)
    {
        try {
            $request->validate([
                'type' => 'required|string'
            ]);

            switch ($request->type) {

                case 'kwitansi':
                    $registrationClass = RegistrationClass::with(['registration.racer.user', 'eventClass'])->findOrFail($id);
                    $registration = $registrationClass->registration;

                    $price = $registrationClass->eventClass->price ?? 0;
                    if ($registration && $registration->is_fined) {
                        $price += $registrationClass->eventClass->price_fine ?? 0;
                    }

                    $priceString = number_format($price, 0, ',', '.');

                    $terbilang = $this->konversiTerbilang($price) . ' RUPIAH';

                    $view = 'pdf.kwitansi';
                    $filename = 'kwitansi_' . ($registrationClass->registration->racer->short_name ?? 'peserta') . '.pdf';

                    Carbon::setLocale('id');
                    $today = Carbon::now()->isoFormat('MMMM Y');
                    $today = strtoupper($today);

                    $pdf = Pdf::loadView($view, [
                            'registClass' => $registrationClass,
                            'price'  => $priceString,
                            'terbilang'   => $terbilang,
                            'today'=> $today
                        ]);

                    // Mengatur kertas ukuran A4 atau Letter dengan orientasi Landscape / Mendatar agar pas dengan contoh gambar
                    $pdf->setPaper('a4', 'portrait');

                    return $pdf->stream($filename);
                    break;

                default:
                    throw new \Exception('Tipe PDF tidak valid');
            }

        } catch (\Illuminate\Validation\ValidationException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {

            return response()->json([
                'success' => false,
                'message' => 'Data tidak ditemukan'
            ], 404);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    private function konversiTerbilang($nilai) {
        $nilai = abs(intval($nilai));

        // if ($nilai === 0) {
        //     return "NOL";
        // }

        $huruf = ["", "SATU", "DUA", "TIGA", "EMPAT", "LIMA", "ENAM", "TUJUH", "DELAPAN", "SEMBILAN", "SEPULUH", "SEBELAS"];
        $temp = "";

        // Logika Belasan & Puluhan Utama
        if ($nilai < 12) {
            $temp = " " . $huruf[$nilai];
        } else if ($nilai < 20) {
            $temp = $this->konversiTerbilang($nilai - 10) . " BELAS";
        } else if ($nilai < 100) {
            $temp = $this->konversiTerbilang(intval($nilai / 10)) . " PULUH " . $this->konversiTerbilang($nilai % 10);
        } else if ($nilai < 200) {
            $temp = " SERATUS " . $this->konversiTerbilang($nilai - 100);
        } else if ($nilai < 1000) {
            $temp = $this->konversiTerbilang(intval($nilai / 100)) . " RATUS " . $this->konversiTerbilang($nilai % 100);
        } else if ($nilai < 2000) {
            $temp = " SERIBU " . $this->konversiTerbilang($nilai - 1000);
        } else if ($nilai < 1000000) {
            // PERBAIKAN UTAMA: Berikan space tegas sebelum dan sesudah kata RIBU
            $temp = $this->konversiTerbilang(intval($nilai / 1000)) . " RIBU " . $this->konversiTerbilang($nilai % 1000);
        } else if ($nilai < 1000000000) {
            // PERBAIKAN UTAMA: Berikan space tegas sebelum dan sesudah kata JUTA
            $temp = $this->konversiTerbilang(intval($nilai / 1000000)) . " JUTA " . $this->konversiTerbilang($nilai % 1000000);
        }

        // Bersihkan semua double space gila akibat tumpukan string rekursif
        $hasilBersih = preg_replace('/\s+/', ' ', $temp);

        return trim($hasilBersih);
    }
}
