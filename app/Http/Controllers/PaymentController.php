<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        $checked = match ($request->type) {
            'lunas' => ['paid'],
            'pending' => [
                'unpaid',
                'menunggu-pembayaran',
                'menunggu-approval',
                'rejected'
            ],
            default => [
                'unpaid',
                'menunggu-pembayaran',
                'menunggu-approval',
                'rejected'
            ]
        };

        return view('admin.payment.index', compact('userId', 'checked'));
    }
}
