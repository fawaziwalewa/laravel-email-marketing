<x-mail::layout>
    {{-- Header --}}
    <x-slot:header>
        <x-mail::header :url="config('app.url')">
            {{ config('app.name') }}
        </x-mail::header>
    </x-slot:header>

    {{-- Body --}}
    {{ $slot }}
    {{-- {{ $subscriber- }} --}}
    {{-- Subcopy --}}
    @isset($subcopy)
        <x-slot:subcopy>
            <x-mail::subcopy>
                {{ $subcopy }}
            </x-mail::subcopy>
        </x-slot:subcopy>
    @endisset

    {{-- Footer --}}
    <x-slot:footer>
        <x-mail::footer>
            @php
                $user_id = \Hashids::encode($subscriber->id);
            @endphp
            <img src="{{ route('emailOpen') }}" alt="" width="1" height="1">
            Don't want {{ env('APP_NAME') }} products delivered to your inbox? <a
                href="{{ env('APP_URL') . 'unsubscribe?user_id=' . $user_id }}">Unsubscribe</a><br>
            © {{ date('Y') }} {{ config('app.name') }}. @lang('All rights reserved.')
        </x-mail::footer>
    </x-slot:footer>
</x-mail::layout>
