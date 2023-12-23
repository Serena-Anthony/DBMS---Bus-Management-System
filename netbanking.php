<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="upi.css">
    <title>Net Banking Payment</title>
</head>
<body>
    <div class="container">
        <h1 class="wow">Net Banking Payment</h1>
        <p class="wow">Calculate net amount based on the number of tickets selected.</p>
        <button class="success-btn wow" onclick="redirectToPaymentSuccessful()">Payment Successful</button>
    </div>


    <script>
        function redirectToPaymentSuccessful() {
            // Redirect to payment_successful5.php
            window.location.href = 'payment_successful5.php';
        }
    </script>

</body>
</html>
