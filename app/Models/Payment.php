<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'amount',
        'description',
        'payment_type',
        'payer_email',
        'status',
        'payment_id',
    ];
}
