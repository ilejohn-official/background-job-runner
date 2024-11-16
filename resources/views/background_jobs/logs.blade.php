<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
          Logs for Job ID:  {{ $id  }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg p-6">
                <!-- Back to Dashboard link -->
                <a href="{{ route('background-jobs.index') }}" class="mb-4 text-blue-500 hover:text-blue-700">
                    &larr; Back to Dashboard
                </a>

                <!-- Logs list -->
                <ul class="space-y-2">
                    @foreach($logs as $log)
                        <li class="text-sm text-gray-700 bg-gray-100 p-2 rounded-lg shadow-sm">
                            {{ $log }}
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

</x-app-layout>
