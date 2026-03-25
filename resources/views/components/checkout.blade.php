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
                    @if($addresses->count())
<div class="mb-6">
    <h3 class="text-lg font-semibold mb-3">Your Saved Addresses</h3>

    <div class="space-y-3">
        @foreach($addresses as $address)
            <div class="border rounded-lg p-4 hover:border-green-500 flex items-start gap-4">
                <!-- Radio Input -->
                <input type="radio"
                       name="selected_address"
                       value="{{ $address->id }}"
                       class="address-radio shrink-0 mt-1">

                <!-- Address Info -->
                <label class="grow cursor-pointer">
                    <span class="font-medium block">
                        {{ $address->street_address }},
                        {{ $address->city }},
                        {{ $address->district }}
                    </span>
                    <div class="text-sm text-gray-500">
                        Building: {{ $address->building }},
                        Apt: {{ $address->apartment }}
                    </div>
                </label>

                <!-- Delete Button -->
                
                    <button type="button"
                            class="text-red-500 hover:text-red-700 hover:bg-red-50 p-2 rounded-lg transition-colors"
                            title="Delete address"
                            form="delete-address-{{ $address->id }}"
                            onclick="document.getElementById('delete-address-{{ $address->id }}').submit();">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                
            </div>

        @endforeach
    </div>
</div>
<hr>
                    @if($addresses->count() >= 3)
                    <p class="text-yellow-600 text-sm mb-3">
                        You already have 3 saved addresses. Delete one to add a new address.
                    </p>
                    @else
                    <p class="text-m font-semibold text-black-800 mb-4">
                        Or enter a new address below
                    </p>
                    @endif
                    @endif
                    @php
                        $limitReached = $addresses->count() >= 3;
                    @endphp

                    <div class="space-y-4">

                        <!-- Street Address -->
                        <div>
                            <input type="text" name="street_address"
                            {{ $limitReached ? 'disabled' : '' }}
                                   placeholder="Street Address"
                                   value="{{ old('street_address') }}"
                                   class="border rounded-lg px-4 py-2 w-full @error('street_address') border-red-500 @enderror">

                            <p id="street_address-error" class="text-red-500 text-sm error-message"></p>
                        </div>

                        <!-- City / District -->
                        <div class="grid md:grid-cols-2 gap-4">
                            <div>
                                <input type="text"
                                {{ $limitReached ? 'disabled' : '' }}
                                 name="city"
                                       placeholder="City"
                                       value="{{ old('city') }}"
                                       class="border rounded-lg px-4 py-2 w-full @error('city') border-red-500 @enderror">

                            <p id="city-error" class="text-red-500 text-sm error-message"></p>
                                
                            </div>

                            <div>
                                <input type="text" 
                                {{ $limitReached ? 'disabled' : '' }}
                                name="district"
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
                                {{ $limitReached ? 'disabled' : '' }}
                                       placeholder="Building"
                                       value="{{ old('building') }}"
                                       class="border rounded-lg px-4 py-2 w-full @error('building') border-red-500 @enderror">

                            <p id="building-error" class="text-red-500 text-sm error-message"></p>
                                
                            </div>

                            <div>
                                <input type="text" name="apartment"
                                {{ $limitReached ? 'disabled' : '' }}
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
        @foreach($addresses as $address)

<form method="POST" id="delete-address-{{ $address->id }}" action="{{ route('address.delete', $address->id) }}" class="shrink-0">
                    @csrf
                    @method('DELETE')

</form>

        @endforeach

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
//
document.querySelectorAll('.address-radio').forEach(radio => {
    radio.addEventListener('change', function () {

        const inputs = document.querySelectorAll(
            'input[name="street_address"], input[name="city"], input[name="district"], input[name="building"], input[name="apartment"]'
        );

        inputs.forEach(input => {
            input.value = '';
        });
    });
});
const radios = document.querySelectorAll('.address-radio');

const inputs = document.querySelectorAll(
    'input[name="street_address"], input[name="city"], input[name="district"], input[name="building"], input[name="apartment"]'
);

inputs.forEach(input => {
    input.addEventListener('input', () => {

        // Uncheck all radios
        radios.forEach(radio => {
            radio.checked = false;
        });

    });
});
</script>



</x-layout>
