<?php

namespace App\Http\Controllers\Api;

use App\Contracts\PaymentContract;
use App\Data\Payment\PayPayload;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePaymentRequest;

class PaymentController extends Controller
{
    public function store(StorePaymentRequest $request, PaymentContract $payment): \Illuminate\Http\Response
    {
        $payment->pay(PayPayload::fromPaymentForm($request->payload()));

        return response()->noContent();
    }
}
