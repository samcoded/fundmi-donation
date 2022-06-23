<x-layout>
    <x-card class="p-10 max-w-lg mx-auto mt-24">
        <header class="text-center">
            <h2 class="text-2xl font-bold uppercase mb-1">Make a Donation</h2>
            <p class="text-gray-600">
                Make a donation to a cause you care about.

            </p>
            <p class="text-gray-600">{{ $donation->title }} by {{ $donation->user->name }}</p>

        </header>
        <script src="https://js.paystack.co/v1/inline.js"></script>

        <form method="POST" action="/donate/{{ $donation->id }}" enctype="multipart/form-data" id="paymentForm">
            @csrf

            <div class="mb-6">
                <label for="name" class="inline-block text-lg mb-2">Name</label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="name"
                    placeholder="John Doe" value="{{ old('name') }}" />

                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="email" class="inline-block text-lg mb-2">
                    Email Address
                </label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="email"
                    placeholder="john@email.com" value="{{ old('email') }}" id="email" />

                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>
            <div class="mb-6">
                <label for="amount" class="inline-block text-lg mb-2">
                    Amount in ₦
                </label>
                <input type="number" class="border border-gray-200 rounded p-2 w-full" name="amount"
                    placeholder="10000" value="{{ old('amount') }}" id="amount" />

                @error('amount')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>


            <div class="mb-6">
                <button class="bg-laravel text-white rounded py-2 px-4 hover:bg-black" onclick="payWithPaystack()">
                    Donate
                </button>

                <a href="/" class="text-black ml-4"> Back </a>
            </div>
        </form>
    </x-card>

    <script>
        const paymentForm = document.getElementById('paymentForm');
        paymentForm.addEventListener('submit', payWithPaystack, false);


        function payWithPaystack() {
            const handler = PaystackPop.setup({
                key: '{{ env('PAYSTACK_PUBLIC_KEY') }}', // Replace with your public key
                email: document.getElementById('email').value,
                label: document.getElementById('name').value,
                amount: document.getElementById('amount').value *
                    100, // the amount value is multiplied by 100 to convert to the lowest currency unit
                currency: 'NGN', // Use GHS for Ghana Cedis or USD for US Dollars
                // ref: 'YOUR_REFERENCE', // Replace with a reference you generated
                callback: function(response) {
                    alert('success. transaction ref is ' + response.reference);
                    window.location = "http://www.yoururl.com/verify_transaction.php?reference=" + response
                        .reference;
                },
                // On the redirected page, you can call Paystack's verify endpoint.
                onClose: function() {
                    alert('Transaction was not completed, window closed.');
                },
            });
            handler.openIframe();
        }
    </script>
</x-layout>
