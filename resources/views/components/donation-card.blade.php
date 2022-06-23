@props(['donation'])

<x-card>
    <div class="flex">
        <img class="hidden w-48 mr-6 md:block"
            src="{{ $donation->image ? asset('storage/' . $donation->image) : asset('/images/no-image.png') }}"
            alt="" />
        <div>
            <h3 class="text-2xl">
                <a href="/donations/{{ $donation->id }}">{{ $donation->title }}</a>
            </h3>
            <div class="text-xl font-bold mb-4"> By {{ $donation->user->name }}</div>
            <x-donation-tags :tagsCsv="$donation->tags" />
            <div class="block bg-green-500 text-white mt-6 py-2 rounded-xl"><i class=""></i>
                <span>₦{{ number_format($donation->amount) }} / ₦{{ number_format($donation->target) }}

                    @unless($donation->amount == 0)
                        ({{ number_format(($donation->amount / $donation->target) * 100) }}%)
                    @else
                        (0%)
                    @endunless
                </span>
            </div>
        </div>
    </div>
</x-card>
