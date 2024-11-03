<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentInterface as ContractsPaymentInterface;
use App\Services\PaymentGateway\Contracts\PaymentInterface;

class PaymentService
{
    protected $paymentGateway;

    public function __construct(ContractsPaymentInterface $paymentGateway)
    {
        $this->paymentGateway = $paymentGateway;
    }

    public function processPayment(array $data)
    {
        return $this->paymentGateway->payment($data);
    }

    public function paymentStatus(string $paymentId)
    {
        return $this->paymentGateway->paymentStatus($paymentId);
    }
}
