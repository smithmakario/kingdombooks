<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Paystack Logs') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between gap-3 mb-4">
                        <p class="text-sm text-gray-600">
                            Showing recent Paystack-related lines from <code>storage/logs/laravel.log</code>.
                        </p>
                        <a
                            href="{{ route('admin.paystack.logs') }}"
                            class="inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-2 text-xs font-semibold uppercase tracking-wider text-gray-700 shadow-sm hover:bg-gray-50"
                        >
                            Refresh
                        </a>
                    </div>

                    @if (empty($entries))
                        <div class="rounded-md border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-600">
                            No Paystack log entries found yet.
                        </div>
                    @else
                        <div class="overflow-auto rounded-md border border-gray-200 bg-gray-950 text-gray-100">
                            <pre class="p-4 text-xs leading-6 whitespace-pre-wrap break-words">@foreach ($entries as $entry){{ $entry }}
@endforeach</pre>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
