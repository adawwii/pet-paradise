<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Processing Order</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

<div class="bg-white p-8 rounded-2xl shadow-lg text-center w-96">

    <!-- Spinner -->
    <div class="flex justify-center mb-6">
        <div class="w-12 h-12 border-4 border-blue-500 border-t-transparent rounded-full animate-spin"></div>
    </div>

    <h2 class="text-xl font-semibold text-gray-800">
        Creating your order...
    </h2>

    <p class="text-gray-500 mt-2 text-sm">
        Please wait while we confirm your payment.
    </p>

    <p id="status" class="text-gray-400 mt-4 text-xs">
        Checking order status...
    </p>

</div>

<script>

async function checkOrder() {
    try {
        const response = await fetch('/check-order');
        const data = await response.json();

        if (data.exists) {
            document.getElementById('status').innerText = "Order created successfully!";
            setTimeout(() => {
                window.location.href = "{{ route('orders.customer') }}";
            }, 1000);
        } else {
            setTimeout(checkOrder, 2000);
        }

    } catch (error) {
        setTimeout(checkOrder, 3000);
    }
}

checkOrder();

</script>

</body>
</html>
