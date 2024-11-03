<?php

namespace App\Contracts;

interface PaymentInterface
{
    public function payment(array $data);

    public function paymentStatus(string $paymentId);
}