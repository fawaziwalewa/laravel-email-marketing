@php
    $user = \Filament\Facades\Filament::auth()->user();

    $accountItem = $items['account'] ?? null;
    $accountItemUrl = $accountItem?->getUrl();

    $logoutItem = $items['logout'] ?? null;
@endphp

<div class="border-t p-2 transition absolute bottom-0 left-0 w-full" x-show="$store.sidebar.isOpen">
    <div class="flex gap-2">
        <div class="flex-shrink">
            <x-filament::user-avatar :user="$user" />
        </div>
        <div class="text-sm">
            <p class="font-semibold">{{ $user->name }}</p>
            <form action="http://withautomail.test/filament/logout" method="post">
                @csrf
                <button type="submit" class="flex gap-2 items-center text-gray-500 hover:text-primary-500 transition">
                    <svg class="h-5 w-5"
                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path fill-rule="evenodd"
                            d="M3 3a1 1 0 00-1 1v12a1 1 0 102 0V4a1 1 0 00-1-1zm10.293 9.293a1 1 0 001.414 1.414l3-3a1 1 0 000-1.414l-3-3a1 1 0 10-1.414 1.414L14.586 9H7a1 1 0 100 2h7.586l-1.293 1.293z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span>Sign Out</span>
                </button>
            </form>
        </div>
    </div>
</div>
