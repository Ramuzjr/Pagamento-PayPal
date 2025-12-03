<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PayPal Checkout</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://assets.edlin.app/bootstrap/v5.3/bootstrap.css">

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- PayPal SDK -->
    <script src="https://www.sandbox.paypal.com/sdk/js?client-id={{ config('paypal.client_id') }}&currency={{ config('paypal.currency') }}&intent=capture&components=buttons,funding-eligibility"></script>

</head>
<body class="py-5">

<div class="container text-center">
    <h1 class="mb-4">Pagamento PayPal</h1>

    <div class="row mb-3">
        <div class="col-12 col-lg-6 offset-lg-3">
            <div class="input-group">
                <span class="input-group-text">€</span>
                <input type="number" id="paypal-amount" class="form-control" value="10" min="0.01" step="0.01">
                <span class="input-group-text">,00</span>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12 col-lg-6 offset-lg-3" id="payment_options"></div>
    </div>
</div>

<script>
paypal.Buttons({

    createOrder: function () {
        const amount = document.getElementById('paypal-amount').value;

        return fetch("/create", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ amount: amount })
        })
        .then(res => res.text());
    },

    onApprove: function (data, actions) {
        return fetch("/complete", {
            method: "POST",
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(res => res.json())
        .then(data => {
            // Mostra SweetAlert de sucesso
            Swal.fire({
                icon: 'success',
                title: 'Pagamento concluído!',
                text: 'Seu pagamento foi registrado com sucesso.',
                confirmButtonText: 'Ver histórico'
            }).then(() => {
                // Redireciona para a página de histórico
                window.location.href = "/payments";
            });
        })
        .catch(err => {
            console.error("PayPal Error:", err);
            Swal.fire({
                icon: 'error',
                title: 'Erro no pagamento',
                text: 'Ocorreu um problema. Veja o console para mais detalhes.'
            });
        });
    },

    onError: function(err) {
        console.error("PayPal Button Error:", err);
        Swal.fire({
            icon: 'error',
            title: 'Erro no PayPal',
            text: 'Ocorreu um problema. Veja o console para mais detalhes.'
        });
    }

}).render('#payment_options');
</script>

</body>
</html>
