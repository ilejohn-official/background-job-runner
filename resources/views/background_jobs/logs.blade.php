<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Logs for Job ID:  {{ $id  }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="container">
            <a href="{{ route('background-jobs.index') }}" class="mb-3">Back to Dashboard</a>
            <ul>
                @foreach($logs as $log)
                    <li>{{ $log }}</li>
                @endforeach
            </ul>
        </div>
        </div>
    </div>
</x-app-layout>
