<?php

namespace App\Http\Controllers;

use App\Services\PaymentGateway\PaymentService;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function processPayment(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|numeric',
            'description' => 'required|string',
            'email' => 'required|email',
            'payment_type' => 'required|string|in:credit_card,pix,boleto',
            'token' => 'sometimes|string',
            'installments' => 'sometimes|integer',
            'payment_method_id' => 'sometimes|string',
        ]);

        $payment = Payment::create([
            'amount' => $data['amount'],
            'description' => $data['description'],
            'payment_type' => $data['payment_type'],
            'payer_email' => $data['email'],
        ]);

        $response = $this->paymentService->processPayment($data);

        if (isset($response['id'])) {
            $payment->update([
                'status' => 'approved',
                'payment_id' => $response['id'],
            ]);
        } else {
            $payment->update(['status' => 'failed']);
        }

        return response()->json($response);
    }

    public function paymentStatus($paymentId)
    {
        $status = $this->paymentService->paymentStatus($paymentId);
        return response()->json($status);
    }
}
