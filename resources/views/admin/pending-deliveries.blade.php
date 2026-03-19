<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pending Deliveries') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-4 flex items-center justify-between gap-3">
                        <p class="text-sm text-gray-600">
                            All successful Paystack order payments pending delivery.
                        </p>
                        <span class="inline-flex items-center rounded-md border border-indigo-200 bg-indigo-50 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-indigo-700">
                            {{ $deliveries->count() }} pending
                        </span>
                    </div>

                    @if ($deliveries->isEmpty())
                        <div class="rounded-md border border-gray-200 bg-gray-50 px-4 py-3 text-sm text-gray-600">
                            No pending delivery records found.
                        </div>
                    @else
                        <div class="overflow-x-auto rounded-md border border-gray-200">
                            <table class="min-w-full divide-y divide-gray-200 text-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Name</th>
                                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Email</th>
                                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Phone</th>
                                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Delivery Preference</th>
                                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Address</th>
                                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Books</th>
                                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Amount</th>
                                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Reference</th>
                                        <th class="px-3 py-3 text-left font-semibold text-gray-700">Paid At</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 bg-white">
                                    @foreach ($deliveries as $delivery)
                                        <tr>
                                            <td class="px-3 py-3 align-top">{{ $delivery->customer_name }}</td>
                                            <td class="px-3 py-3 align-top break-all">{{ $delivery->customer_email }}</td>
                                            <td class="px-3 py-3 align-top">{{ $delivery->customer_phone ?: 'N/A' }}</td>
                                            <td class="px-3 py-3 align-top">{{ $delivery->delivery_preference ?: 'N/A' }}</td>
                                            <td class="px-3 py-3 align-top">{{ $delivery->customer_address ?: 'N/A' }}</td>
                                            <td class="px-3 py-3 align-top">{{ $delivery->items_description ?: 'N/A' }}</td>
                                            <td class="px-3 py-3 align-top">
                                                {{ $delivery->currency }} {{ number_format($delivery->amount_kobo / 100, 2) }}
                                            </td>
                                            <td class="px-3 py-3 align-top break-all">{{ $delivery->reference }}</td>
                                            <td class="px-3 py-3 align-top">
                                                {{ $delivery->paid_at?->format('Y-m-d H:i') ?: 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
