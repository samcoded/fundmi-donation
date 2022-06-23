<x-layout>

    @include('partials._search')

    <div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">

        @unless(count($donations) == 0)

            @foreach ($donations as $donation)
                <x-donation-card :donation="$donation" />
            @endforeach
        @else
            <p>No donations found</p>
        @endunless

    </div>

    <div class="mt-6 p-4">
        {{ $donations->links() }}
    </div>
</x-layout>
