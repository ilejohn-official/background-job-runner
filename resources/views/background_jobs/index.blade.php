<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Background Jobs Dashboard') }}
        </h2>
    </x-slot>

    @if (session('status'))
        <p
            x-data="{ show: true }"
            x-show="show"
            x-transition
            x-init="setTimeout(() => show = false, 3000)"
            class="fixed top-4 left-1/2 transform -translate-x-1/2 w-96 px-6 py-3 bg-green-500 text-white text-center text-sm rounded-lg shadow-lg z-50"
        >{{ session('status') }}</p>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg shadow-md">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Job ID</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Class</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Method</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Status</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Attempts</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Priority</th>
                        <th class="px-6 py-3 text-left text-sm font-medium text-gray-600">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-sm font-normal text-gray-800">
                    @foreach($jobs as $job)
                        <tr class="border-t border-gray-300">
                            <td class="px-6 py-4">{{ $job['id'] }}</td>
                            <td class="px-6 py-4">{{ $job['class'] }}</td>
                            <td class="px-6 py-4">{{ $job['method'] }}</td>
                            <td class="px-6 py-4">{{ $job['status'] }}</td>
                            <td class="px-6 py-4">{{ $job['attempts'] }}</td>
                            <td class="px-6 py-4">{{ $job['priority'] }}</td>
                            <td class="px-6 py-4 flex items-center space-x-2">
                                <a href="{{ route('background-jobs.logs', $job['id']) }}" class="btn btn-info text-blue-500 hover:text-blue-700">
                                    <x-primary-button class="bg-blue-500 hover:bg-blue-700 text-white px-4 py-2 rounded">View Logs</x-primary-button>
                                </a>
                                @if($job['status'] !== 'canceled' && $job['status'] !== 'completed')
                                    <form action="{{ route('background-jobs.cancel', $job['id']) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <x-danger-button class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded">Cancel</x-danger-button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</x-app-layout>
