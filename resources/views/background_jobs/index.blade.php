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
            x-init="setTimeout(() => show = false, 2000)"
            class="text-sm text-gray-600"
        >{{ session('status') }}</p>
    @endif

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <table class="table">
            <thead>
                <tr>
                    <th>Job ID</th>
                    <th>Class</th>
                    <th>Method</th>
                    <th>Status</th>
                    <th>Attempts</th>
                    <th>Priority</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jobs as $job)
                    <tr>
                        <td>{{ $job['id'] }}</td>
                        <td>{{ $job['class'] }}</td>
                        <td>{{ $job['method'] }}</td>
                        <td>{{ $job['status'] }}</td>
                        <td>{{ $job['attempts'] }}</td>
                        <td>{{ $job['priority'] }}</td>
                        <td>
                            <a href="{{ route('background-jobs.logs', $job['id']) }}" class="btn btn-info">
                                <x-primary-button class="ms-4">View Logs</x-primary-button>
                            </a>
                            @if($job['status'] !== 'canceled' && $job['status'] !== 'completed')
                                <form action="{{ route('background-jobs.cancel', $job['id']) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <x-danger-button class="ms-4">Cancel</x-danger-button>
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
