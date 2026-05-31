<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Get all events
     */
    public function index(Request $request, $userId): JsonResponse
    {
        $length = $request->length ?? 10;
        $start  = $request->start ?? 0;

        $query = Registration::where('user_id', $userId)->select([
            'id',
            'event_id',
            'racer_id',
            'registration_number',
            'user_id',
            'team_name',
            'status',
            'payment_proof',
            'payment_method',
            'race_status',
            'created_at',
            'is_fined'
        ])->with([
            'registrationClasses.eventClass',
            'event',
            'racer',
            'event.paymentAccount',
            'event.contactPerson'
        ]);
        // ->withCount('registrations')

        // SEARCH
        if ($request->search_payments) {

            $search = $request->search_payments;

            $query->where(function ($q) use ($search) {

                $q->where('name', 'like', "%{$search}%")
                ->orWhere('venue', 'like', "%{$search}%")
                ->orWhere('kota', 'like', "%{$search}%");

            });
        }

        // FILTER STATUS
        if ($request->status && is_array($request->status)) {

            $query->whereIn('status', $request->status);

        }
        // SORT
        $orderColumnIndex = $request->input('order.0.column');
        $orderDirection   = $request->input('order.0.dir', 'desc');

        $columns = $request->input('columns');

        $orderColumn = $columns[$orderColumnIndex]['data'] ?? 'created_at';

        $allowedColumns = [
            'name',
            'venue',
            'created_at',
        ];

        if (!in_array($orderColumn, $allowedColumns)) {
            $orderColumn = 'created_at';
        }

        $query->orderBy($orderColumn, $orderDirection);

        $recordsTotal = Registration::where('user_id', $userId)->count();
        $recordsFiltered = (clone $query)->count();

        $registers = $query
            ->skip($start)
            ->take($length)
            ->get()
            ->map(function ($register) {
                $countClass = $register->registrationClasses->count();
                $totalPrice = $register->registrationClasses->sum(function ($registrationClass) use ($register) {

                    $price = $registrationClass->eventClass->price ?? 0;

                    if ($register->is_fined) {
                        $price += $registrationClass->eventClass->price_fine ?? 0;
                    }

                    return $price;
                });

                return [
                    'id' => $register->id,
                    'racer_id' => $register->racer_id,
                    'registration_number' => $register->registration_number,
                    'user_id' => $register->user_id,
                    'team_name' => $register->team_name,
                    // 'phone_number' => $register->phone_number,
                    'status' => $register->status,
                    'race_status' => $register->race_status,
                    'payment_method' => $register->payment_method,
                    'payment_proof' => $register->payment_proof,
                    'payment_proof_url' => asset('storage/' . $register->payment_proof),
                    'count_class' => $countClass,
                    'total_price' => $totalPrice,
                    'created_at' => $register->created_at,
                    'event' => $register->event,
                    'racer' => $register->racer,
                    'payment_account' => $register->event?->paymentAccount,
                    'classes' => $register->registrationClasses->map(function ($registrationClass) {
                        return [
                            'id' => $registrationClass->id,
                            'class_id' => $registrationClass->class_id,
                            'class_name' => $registrationClass->eventClass->name ?? null,
                            // 'price' => $registrationClass->eventClass->price ?? 0,
                            // 'price_fine' => $registrationClass->eventClass->price_fine ?? 0,
                            // 'racer_number' => $registrationClass->racer_number,
                            // 'vehicle' => $registrationClass->vehicle,
                            // 'vehicle_number' => $registrationClass->vehicle_number,
                            // 'rangka_number' => $registrationClass->rangka_number,
                        ];
                    }),
                    // 'classes' => $register->registrationClasses,
                ];
            });
        // dd($registers); // debug
        return response()->json([
            'draw' => intval($request->draw),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $registers
        ]);
    }

    public function uploadPaymentProof(Request $request, $registrationId): JsonResponse
    {
        $request->validate([
            'payment_proof' => 'required|image|mimes:jpg,jpeg,png|max:10240'
        ], [
            'payment_proof.required' => 'Bukti pembayaran wajib diupload',
            'payment_proof.image' => 'File harus berupa gambar',
            'payment_proof.mimes' => 'Format harus JPG, JPEG, atau PNG',
            'payment_proof.max' => 'Ukuran maksimal 2MB',
        ]);

        $registration = Registration::findOrFail($registrationId);

        // upload file
        $path = $request->file('payment_proof')
            ->store('events/payment', 'public');

        // update registration
        $registration->update([
            'payment_proof' => $path,
            'status' => 'menunggu-approval'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Bukti pembayaran berhasil diupload',
            'data' => [
                'payment_proof_url' => asset('storage/' . $path)
            ]
        ]);
    }
}
