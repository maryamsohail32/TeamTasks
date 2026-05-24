@extends('layouts.app')
@section('content')
<div class="max-w-md">
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Create a Team</h1>
    <form method="POST" action="{{ route('teams.store') }}" class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Team name</label>
            <input name="name" type="text" required
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500"
                placeholder="e.g. Design Team">
            @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
            Create Team
        </button>
    </form>
</div>
@endsection