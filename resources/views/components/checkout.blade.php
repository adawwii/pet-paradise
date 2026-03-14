<x-layout>


<div class="max-w-6xl mx-auto px-4 py-12">

    <h1  class="text-3xl font-bold mb-8">Checkout</h1>

    <form
     id="payment-form"
      method="POST" action="{{ route('payment') }}">
        @csrf

        <div class="grid lg:grid-cols-3 gap-10">

            <!-- LEFT SIDE -->
            <div class="lg:col-span-2 space-y-8">

                <!-- Shipping Address -->
                <div  class="bg-white shadow rounded-xl p-6">
                    <h2  class="text-xl font-semibold mb-6">
                        Shipping Address
                    </h2>

                    <div class="space-y-4">

                        <!-- Street Address -->
                        <div>
                            <input type="text" name="street_address"
                                   placeholder="Street Address"
                                   value="{{ old('street_address') }}"
                                   class="border rounded-lg px-4 py-2 w-full @error('street_address') border-red-500 @enderror">

                            <p id="street_address-error" class="text-red-500 text-sm error-message"></p>
                        </div>

                        <!-- City / District -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <input type="text" name="city"
                                       placeholder="City"
                                       value="{{ old('city') }}"
                                       class="border rounded-lg px-4 py-2 w-full @error('city') border-red-500 @enderror">

                            <p id="city-error" class="text-red-500 text-sm error-message"></p>
                                
                            </div>

                            <div>
                                <input type="text" name="district"
                                       placeholder="District"
                                       value="{{ old('district') }}"
                                       class="border rounded-lg px-4 py-2 w-full @error('district') border-red-500 @enderror">

                           <p id="district-error" class="text-red-500 text-sm error-message"></p>

                            </div>
                        </div>

                        <!-- Building / Apartment -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <input type="text" name="building"
                                       placeholder="Building"
                                       value="{{ old('building') }}"
                                       class="border rounded-lg px-4 py-2 w-full @error('building') border-red-500 @enderror">

                            <p id="building-error" class="text-red-500 text-sm error-message"></p>
                                
                            </div>

                            <div>
                                <input type="text" name="apartment"
                                       placeholder="Apartment"
                                       value="{{ old('apartment') }}"
                                       class="border rounded-lg px-4 py-2 w-full @error('apartment') border-red-500 @enderror">

                            <p id="apartment-error" class="text-red-500 text-sm error-message"></p>
                                
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Payment Section -->
                <div class="bg-white shadow rounded-xl p-6">
                    <h2 class="text-xl font-semibold mb-6">
                        Payment Details
                    </h2>

                    <div id="card-element"
                         class="border rounded-lg p-3"></div>

                    <div id="card-errors"
                         class="text-red-500 text-sm mt-3"></div>
                </div>

            </div>

            <!-- RIGHT SIDE (Order Summary) -->
            <div class="bg-white shadow rounded-xl p-6 h-fit">

                <h2 class="text-xl font-semibold mb-4">
                    Order Summary
                </h2>

                @php $total = 0; @endphp

                @foreach($cart->cartItems as $item)
                    @php
                        $subtotal = $item->product->price * $item->quantity;
                        $total += $subtotal;
                    @endphp

                    <div class="flex justify-between mb-2 text-sm">
                        <span>{{ $item->product->name }} x{{ $item->quantity }}</span>
                        <span>${{ number_format($subtotal, 2) }}</span>
                    </div>
                @endforeach

                <hr class="my-4">

                <div class="flex justify-between font-bold text-lg">
                    <span>Total</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>

                <button type="submit"
                        id="submit"
                        class="w-full mt-6 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 transition font-semibold">
                    Pay Now
                </button>

            </div>

        </div>

    </form>
</div>

<!-- Stripe JS -->
<script src="https://js.stripe.com/v3/"></script>

<script>
    
const stripe = Stripe("{{ config('services.stripe.key') }}");
const elements = stripe.elements();
const card = elements.create("card");
card.mount("#card-element");

const form = document.getElementById("payment-form");
form.addEventListener("submit", async function(e) {
    e.preventDefault();
    

    const {error, paymentMethod} = await stripe.createPaymentMethod({
        type: "card",
        card: card,
    });

    if (error) {
        document.getElementById("card-errors").textContent = error.message;
        return;
    }

    const formData = new FormData(this);
    formData.append("payment_method", paymentMethod.id);

    fetch("{{ route('payment') }}", {
        method: "POST",
        headers: {
            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
        },
        body: formData
    })
    .then(res => res.json())
    .then(async data => {
        document.getElementById("street_address-error").textContent='';
            document.getElementById("city-error").textContent='';
            document.getElementById("district-error").textContent='';
            document.getElementById("building-error").textContent='';
            document.getElementById("apartment-error").textContent='';
            document.getElementById("card-errors").textContent = '';
            if(data.validation_error) {
            
             Object.keys(data.errors).forEach(field => {
            let errorElement = document.getElementById(field + '-error');
            if (errorElement) {
                errorElement.textContent = data.errors[field][0];
            }
        });
        return;
        }
        if(data.client_secret) {
            const { error: confirmError, paymentIntent } = await stripe.confirmCardPayment(data.client_secret, {
                payment_method: paymentMethod.id
            });

            if (confirmError) {
                document.getElementById("card-errors").textContent = confirmError.message;
                return;
            }
            if (paymentIntent.status === "succeeded") {
                window.location.href = "{{ route('order.processing') }}";
            }
            
        } else {
            document.getElementById("card-errors").textContent = data.message;
        }
    });
});
</script>



</x-layout>
