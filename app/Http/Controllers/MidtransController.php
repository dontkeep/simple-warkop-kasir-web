<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MidtransController extends Controller
{
    public function getSnapToken(Request $request)
    {
        $cart = $request->input('cart', []);
        if (empty($cart)) {
            return response()->json(['error' => 'Cart is empty'], 400);
        }

        $order_id = uniqid('ORDER-');
        $gross_amount = 0;
        $items = [];
        foreach ($cart as $item) {
            $items[] = [
                'id' => isset($item['id']) ? $item['id'] : substr(md5($item['name']), 0, 10),
                'price' => (int) $item['price'],
                'quantity' => (int) $item['qty'],
                'name' => $item['name'],
            ];
            $gross_amount += ((int) $item['price']) * ((int) $item['qty']);
        }

        $payload = [
            'transaction_details' => [
                'order_id' => $order_id,
                'gross_amount' => $gross_amount,
            ],
            'item_details' => $items,
        ];

        $serverKey = env('MIDTRANS_SERVER_KEY');
        $isProduction = env('MIDTRANS_IS_PRODUCTION', false);
        $midtransUrl = $isProduction
            ? 'https://app.midtrans.com/snap/v1/transactions'
            : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

        $response = Http::withBasicAuth($serverKey, '')
            ->withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])
            ->post($midtransUrl, $payload);

        if ($response->successful()) {
            return response()->json(['snap_token' => $response['token']]);
        } else {
            return response()->json(['error' => 'Failed to get Snap token'], 500);
        }
    }
}
