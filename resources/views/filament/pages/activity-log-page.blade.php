<x-filament-panels::page>
    <div class="space-y-4">
        @forelse ($this->getActivities() as $activity)
            <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 bg-white dark:bg-gray-800">
                <div class="flex items-center justify-between">
                    <div>
                        <span class="font-semibold text-gray-900 dark:text-gray-100">
                            {{ $activity->causer?->name ?? 'System' }}
                        </span>
                        <span class="text-gray-500 dark:text-gray-400">
                            {{ $activity->description }}
                        </span>
                        <span class="font-medium text-primary-600">
                            {{ class_basename($activity->subject_type) }} #{{ $activity->subject_id }}
                        </span>
                    </div>
                    <span class="text-sm text-gray-400">
                        {{ $activity->created_at->diffForHumans() }}
                    </span>
                </div>

                @if ($activity->properties->has('attributes'))
                    <div class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                        @foreach ($activity->properties['attributes'] as $key => $value)
                            @php
                                $old = $activity->properties['old'][$key] ?? null;
                            @endphp
                            @if ($old !== $value)
                                <div>
                                    <span class="font-medium">{{ $key }}:</span>
                                    <span class="text-red-500 line-through">{{ $old }}</span>
                                    →
                                    <span class="text-green-600">{{ $value }}</span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <p class="text-gray-500">No activity recorded yet.</p>
        @endforelse
    </div>
</x-filament-panels::page>