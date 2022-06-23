<x-layout>
    <x-card class="p-10 max-w-lg mx-auto mt-24">
        <header class="text-center">
            <h2 class="text-2xl font-bold uppercase mb-1">Withdraw</h2>
            <p class="text-gray-600">
                Withdraw to bank account
            </p>

        </header>


        <form method="POST" action="/withdraw" enctype="multipart/form-data">
            @csrf
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
                <label for="email" class="inline-block text-lg mb-2">
                    Bank Name
                </label>
                <input type="text" class="border border-gray-200 rounded p-2 w-full" name="bank"
                    placeholder="Bank Name" value="{{ old('bank') }}" id="bank" />

                @error('bank')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label for="amount" class="inline-block text-lg mb-2">
                    Account Number
                </label>
                <input type="number" class="border border-gray-200 rounded p-2 w-full" name="account"
                    placeholder="123456789" value="{{ old('account') }}" id="account" />

                @error('account')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <button class="bg-laravel text-white rounded py-2 px-4 hover:bg-black">
                    Withdraw
                </button>
                <div class="block bg-laravel text-white mt-6 py-2 rounded-xl "><i class=""></i>
                    Withdrawals: <span>{{ $withdrawals->count() }}</span></div>


                @foreach ($withdrawals as $withdrawal)
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="text-sm">₦{{ number_format($withdrawal->amount) }}
                                {{ $withdrawal->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                @endforeach


                <a href="/" class="text-black ml-4"> Back </a>
            </div>
        </form>
    </x-card>

</x-layout>
