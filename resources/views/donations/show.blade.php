<x-layout>
    <a href="/" class="inline-block text-black ml-4 mb-4"><i class="fa-solid fa-arrow-left"></i> Back
    </a>
    <div class="mx-4">
        <x-card class="p-10">
            <div class="flex flex-col items-center justify-center text-center">
                <img class="w-48 mr-6 mb-6"
                    src="{{ $donation->image ? asset('storage/' . $donation->image) : asset('/images/no-image.png') }}"
                    alt="" />
                <h3 class="text-2xl mb-2">
                    {{ $donation->title }}
                </h3>
                <div class="text-xl font-bold mb-4"> By {{ $donation->user->name }}</div>

                <x-donation-tags :tagsCsv="$donation->tags" />

                <div class="border border-gray-200 w-full mb-6"></div>
                <div>
                    <h3 class="text-3xl font-bold mb-4">About Donation</h3>
                    <div class="text-lg space-y-6">
                        {{ $donation->description }}

                        <div class="block bg-laravel text-white mt-6 py-2 rounded-xl "><i class=""></i>
                            Target: <span>₦{{ number_format($donation->target) }}</span></div>
                        <div class="block bg-green-500 text-white mt-6 py-2 rounded-xl"><i class=""></i>
                            Donated: <span>₦{{ number_format($donation->amount) }}

                                @unless($donation->amount == 0)
                                    ({{ number_format(($donation->amount / $donation->target) * 100) }}%)
                                @else
                                    (0%)
                                @endunless
                            </span></div>
                        <button class="bg-orange-400 text-white py-2 rounded-xl hover:opacity-80"
                            onclick="copyLink()"><i class="fa-solid fa-share"></i>
                            Copy link</button>
                        <a href="/donate/{{ $donation->id }}"
                            class="block bg-black text-white py-2 rounded-xl hover:opacity-80"><i
                                class="fa-solid fa-dollar"></i>
                            Donate Now</a>


                        <script type="text/javascript">
                            function copyLink() {
                                const urlcopy = "{{ url('/donate/' . $donation->id) }}";
                                navigator.clipboard.writeText(urlcopy);
                                alert('Link copied to clipboard');
                            }
                        </script>
                        <div class="block bg-laravel text-white mt-6 py-2 rounded-xl "><i class=""></i>
                            Donors: <span>{{ $donation->donors->count() }}</span></div>


                        @foreach ($donation->donors as $donor)
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <div class="text-sm font-bold">{{ $donor->name }}</div>
                                    <div class="text-sm">₦{{ number_format($donor->amount) }}
                                        {{ $donor->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </x-card>
    </div>
</x-layout>
