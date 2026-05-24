@extends('layouts.app')
@section('content')
<div class="max-w-lg">
    <p class="text-sm text-gray-400 mb-1">
        <a href="{{ route('teams.show', $team) }}" class="hover:underline">{{ $team->name }}</a> /
    </p>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Edit Task</h1>

    <form method="POST" action="{{ route('tasks.update', [$team, $task]) }}"
          class="bg-white rounded-xl border border-gray-200 p-6 space-y-4">
        @csrf @method('PUT')
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Title</label>
            <input name="title" type="text" required value="{{ old('title', $task->title) }}"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">
            @error('title')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
            <textarea name="description" rows="3"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-none">{{ old('description', $task->description) }}</textarea>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none">
                    @foreach(['todo'=>'To Do','in_progress'=>'In Progress','done'=>'Done'] as $val=>$lbl)
                        <option value="{{ $val }}" @selected(old('status',$task->status)===$val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                <select name="priority" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none">
                    @foreach(['low'=>'Low','medium'=>'Medium','high'=>'High'] as $val=>$lbl)
                        <option value="{{ $val }}" @selected(old('priority',$task->priority)===$val)>{{ $lbl }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Assign to</label>
                <select name="assignee_id" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none">
                    <option value="">Unassigned</option>
                    @foreach($members as $member)
                        <option value="{{ $member->id }}" @selected($task->assignee_id===$member->id)>{{ $member->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Due date</label>
                <input name="due_date" type="date"
                    value="{{ old('due_date', $task->due_date?->format('Y-m-d')) }}"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none">
            </div>
        </div>
        <button type="submit"
            class="w-full bg-indigo-600 text-white py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
            Save Changes
        </button>
    </form>
</div>
@endsection