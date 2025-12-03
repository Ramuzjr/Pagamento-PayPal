<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use App\Models\Payment; // modelo de pagamentos

class PayPalController extends Controller
{
    public function index()
    {
        return view('welcome'); // ou checkout, conforme preferir
    }

    private function getAccessToken()
    {
        $response = Http::asForm()
            ->withBasicAuth(config('paypal.client_id'), config('paypal.secret'))
            ->post(config('paypal.base_url') . '/v1/oauth2/token', [
                'grant_type' => 'client_credentials'
            ]);

        return $response->json()['access_token'];
    }

    public function create(Request $request)
    {
        $amount = $request->amount;

        $requestId = uuid_create();

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $this->getAccessToken(),
            'PayPal-Request-Id' => $requestId,
        ])->post(config('paypal.base_url') . '/v2/checkout/orders', [
            "intent" => "CAPTURE",
            "purchase_units" => [[
                "reference_id" => $requestId,
                "amount" => [
                    "currency_code" => config('paypal.currency'),
                    "value" => number_format($amount, 2, '.', '')
                ]
            ]]
        ]);

        $orderId = $response->json()['id'];

        Session::put('order_id', $orderId);

        return $orderId; // apenas o ID
    }

    public function complete()
    {
        $orderId = Session::get('order_id');

        $response = Http::withToken($this->getAccessToken())
            ->post(config('paypal.base_url') . "/v2/checkout/orders/$orderId/capture");

        $data = $response->json();

        // Salvar pagamento no banco
        $capture = $data['purchase_units'][0]['payments']['captures'][0] ?? null;

        if ($capture) {
            Payment::updateOrCreate(
                ['order_id' => $orderId],
                [
                    'amount' => $capture['amount']['value'] ?? 0,
                    'currency' => $capture['amount']['currency_code'] ?? config('paypal.currency'),
                    'status' => $capture['status'] ?? 'UNKNOWN',
                    'details' => json_encode($data),
                ]
            );
        }

        return response()->json($data);
    }

    // Opcional: listar pagamentos
    public function listPayments()
    {
        $payments = Payment::orderBy('created_at', 'desc')->get();
        return view('payments', compact('payments'));
    }
}
