<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pagamentos PayPal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://assets.edlin.app/bootstrap/v5.3/bootstrap.css">

    <!-- PayPal SDK -->
    <script src="https://www.sandbox.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}&currency=GBP&intent=capture&components=buttons,funding-eligibility"></script>
</head>
<body class="py-5">

    <div class="container text-center">
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Order ID</th>
                    <th>Amount</th>
                    <th>Currency</th>
                    <th>Status</th>
                    <th>Data</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td>{{ $payment->id }}</td>
                        <td>{{ $payment->order_id }}</td>
                        <td>{{ $payment->amount }}</td>
                        <td>{{ $payment->currency }}</td>
                        <td>{{ $payment->status }}</td>
                        <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</html>
