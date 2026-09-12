@props(['icon' => 'search', 'title', 'description'])

<div class="flex flex-col items-center justify-center py-16 px-4 text-center">
    <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-5">
        @if ($icon === 'search')
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        @elseif ($icon === 'ticket')
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
        @endif
    </div>

    <h3 class="text-base font-semibold text-gray-900 mb-1.5">
        {{ $title }}
    </h3>

    <p class="text-sm text-gray-500 max-w-sm">
        {{ $description }}
    </p>

    @isset($action)
        <div class="mt-6">
            {{ $action }}
        </div>
    @endisset
</div>