<?php

namespace App\Services\PaymentGateway;

use App\Contracts\PaymentInterface;
use MercadoPago\Client\Payment\PaymentClient;
use MercadoPago\Exceptions\MPApiException;
use MercadoPago\MercadoPagoConfig;

class MercadoPagoService implements PaymentInterface
{

    private $client;

    public function __construct()
    {
        MercadoPagoConfig::setAccessToken(env('MERCADO_PAGO_ACCESS_TOKEN'));
        $this->client = new PaymentClient();
    }

    public function payment(array $data)
    {
        $request = [
            'transaction_amount' => $data['amount'],
            'description' => $data['description'],
            'payer' => [
                'email' => $data['email'],
            ],
        ];

        switch ($data['payment_type']) {
            case 'credit_card':
                $request['token'] = $data['token'];
                $request['installments'] = $data['installments'];
                $request['payment_method_id'] = $data['payment_method_id'];
                break;
            
            case 'pix':
                $request['payment_method_id'] = 'pix';
                break;
            
            case 'boleto':
                $request['payment_method_id'] = 'boleto';
                break;
            
            default:
                return ['error' => 'Tipo de pagamento não suportado'];
        }

        try {
            return $this->client->create($request);
        } catch (MPApiException $e) {
            return ['error' => $e->getApiResponse()->getContent()];
        }
    }

    public function paymentStatus(string $paymentId)
    {
        try {
            $payment = $this->client->get($paymentId);

            return [
                'status' => $payment->status,
                'status_detail' => $payment->status_detail,
                'transaction_details' => $payment->transaction_details,
                'payer' => $payment->payer,
                'id' => $payment->id,
                'description' => $payment->description,
                'amount' => $payment->transaction_amount,
            ];
        } catch (MPApiException $e) {
            return ['error' => $e->getApiResponse()->getContent()];
        }
    }
}