<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ env('APP_NAME') }} - Unsubscribe</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;1,100;1,200;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet">
    <link rel="shortcut icon"
        href="https://i0.wp.com/withauto.com/wp-content/uploads/2023/04/cropped-withauto-logo.png?fit=32%2C32&#038;ssl=1"
        sizes="32x32" type="image/x-icon">
    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScripts
    @stack('scripts')
</head>

<body class="bg-[#E1E6EF] font-['IBM_Plex_Sans'] antialiased">
    <main class="flex flex-col items-center justify-center min-h-screen gap-3 py-4">
        <img src="{{ Vite::asset('resources/images/unsubscribe.svg') }}" alt="Unsubscribe" class="h-[304px]">
        <h1 class="text-2xl font-bold md:text-4xl">You’re missing out</h1>
        <p class="text-base md:text-xl font-semibold w-full text-white bg-[#DC9814] py-1 text-center">We’re giving 10%
            off to our subscribers</p>
        <p class="text-sm text-center md:text-base">As a subscriber you can use our coupon code <b>CARCANDY</b> to get a
            10% off<br /> discount on
            all purchase on our <a href="https://withauto.com" target="_blank" class="text-green-600">website</a>. </p>
        <div class="flex gap-2 md:gap-4">
            <form action="{{ route('unsubscribe') }}" method="post">
                @csrf
                <input type="text" name="user_id" value="{{ request()->query('user_id') }}" hidden>
                <button type="submit"
                    class="px-4 py-2 text-sm font-semibold text-white transition-all bg-red-600 rounded md:px-6 md:text-xl hover:scale-110">Unsubscribe</button>
            </form>
            <a href="https://withauto.com" target="_blank"
                class="px-4 py-2 text-sm font-semibold text-white transition-all bg-green-600 rounded md:px-6 md:text-xl hover:scale-110">Don’t
                miss out →</a>
        </div>
    </main>
    @livewire('notifications')
</body>

</html>
