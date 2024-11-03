<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Services\PaymentGateway\PaymentService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function handleWebhook(Request $request)
    {
        $data = $request->all();

        if(isset($data['type']) && $data['type'] === 'payment') {
            $paymentId = $data['data']['id'];

            $paymentStatus = $this->paymentService->paymentStatus($paymentId);
            $payment = Payment::where('payment_id', $paymentId)->first();

            if ($payment) {
                $payment->update([
                    'status' => $paymentStatus['status'] ?? 'unknown',
                    'status_detail' => $paymentStatus['status_detail'] ?? 'null',
                ]);
            }

            return response()->json(['status' => 'success'], 200);
        }

        return response()->json(['status' => 'ignored'], 200);
    }
}
