<?php

namespace App\Http\Controllers;

use App\Models\Racer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RacerController extends Controller
{
    /**
     * Halaman Landing
     */
    public function index()
    {
        // $events = Event::all();
        $user = Auth::user();

        return view('admin.racer.index', compact('user'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'short_name' => 'required|string|max:255',
            'nik' => 'required|string|max:16',
            'phone_number' => 'nullable|string|max:20',
            'racer_number' => 'required|max:20',
            'birth_location' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'hometown' => 'required|string|max:255',

            'photo' => 'required|image|max:10240',
            'kis' => 'required|image|max:10240',
            'kta' => 'nullable|image|max:10240',
        ],[

                'full_name.required'      => 'Nama lengkap wajib diisi',
                'short_name.required'     => 'Nama alias wajib diisi',
                'nik.required'            => 'NIK wajib diisi',
                'hometown.required'       => 'Asal kota wajib diisi',
                'racer_number.required'   => 'Nomor start wajib diisi',
                'birth_location.required' => 'Tempat lahir wajib diisi',
                'birth_date.required'     => 'Tanggal lahir wajib diisi',

        ]);

        DB::beginTransaction();

        try {

            $photo = $request->file('photo')
                ? $request->file('photo')->store('racers/photo', 'public')
                : null;

            $kis = $request->file('kis')
                ? $request->file('kis')->store('racers/kis', 'public')
                : null;

            $kta = $request->file('kta')
                ? $request->file('kta')->store('racers/kta', 'public')
                : null;

            Racer::create([
                'user_id' => auth()->id(),
                'nik' => $request->nik,
                'full_name' => $request->full_name,
                'short_name' => $request->short_name,
                'phone_number' => $request->phone_number,
                'birth_location' => $request->birth_location,
                'birth_date' => $request->birth_date,
                'hometown' => $request->hometown,
                'racer_number' => $request->racer_number,
                'photo' => $photo,
                'kis' => $kis,
                'kta' => $kta,
            ]);

            DB::commit();

            return redirect()
                ->route('racers')
                ->with('success', 'Data pembalap berhasil ditambahkan');

        } catch (\Exception $e) {

            DB::rollBack();

            Log::error('Gagal menyimpan racer', [
                'message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menyimpan data pembalap');
        }
    }
}
