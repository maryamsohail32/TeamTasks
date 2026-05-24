<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <p>Welcome back, {{ auth()->user()->name }}!</p>
                    <p class="mt-2">Here are your teams:</p>

                    <ul class="mt-4 space-y-2">
                        @foreach($teams as $team)
                            <li class="p-3 border rounded">
                                <a href="{{ route('teams.show', $team) }}" class="font-semibold text-indigo-600">
                                    {{ $team->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
