@extends('layouts.app')
@section('content')
<div style="max-width:560px;">
    <p style="font-size:12px; color:#9CA3AF; margin-bottom:5px;">
        <a href="{{ route('teams.show',$team) }}" style="color:#6366F1; text-decoration:none;">{{ $team->name }}</a> › New Task
    </p>
    <h1 style="font-size:22px; font-weight:600; color:#1E1B4B; margin-bottom:24px;">Create a Task</h1>

    <div class="card">
        <form method="POST" action="{{ route('tasks.store',$team) }}" style="display:flex; flex-direction:column; gap:18px;">
            @csrf
            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em;">Title *</label>
                <input name="title" type="text" required value="{{ old('title') }}" placeholder="What needs to be done?"
                    style="width:100%; border:1px solid #E5E7EB; border-radius:9px; padding:10px 14px; font-size:14px; outline:none; font-family:inherit; color:#111827;"
                    onfocus="this.style.borderColor='#6366F1'" onblur="this.style.borderColor='#E5E7EB'">
                @error('title')<p style="color:#DC2626; font-size:12px; margin-top:4px;">{{ $message }}</p>@enderror
            </div>

            <div>
                <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em;">Description</label>
                <textarea name="description" rows="3" placeholder="Add more details..."
                    style="width:100%; border:1px solid #E5E7EB; border-radius:9px; padding:10px 14px; font-size:14px; outline:none; font-family:inherit; color:#111827; resize:vertical;"
                    onfocus="this.style.borderColor='#6366F1'" onblur="this.style.borderColor='#E5E7EB'">{{ old('description') }}</textarea>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                <div>
                    <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em;">Status</label>
                    <select name="status" style="width:100%; border:1px solid #E5E7EB; border-radius:9px; padding:10px 14px; font-size:14px; outline:none; font-family:inherit; color:#111827; background:#fff;">
                        <option value="todo">To Do</option>
                        <option value="in_progress">In Progress</option>
                        <option value="done">Done</option>
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em;">Priority</label>
                    <select name="priority" style="width:100%; border:1px solid #E5E7EB; border-radius:9px; padding:10px 14px; font-size:14px; outline:none; font-family:inherit; color:#111827; background:#fff;">
                        <option value="low">Low</option>
                        <option value="medium" selected>Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:14px;">
                <div>
                    <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em;">Assign to</label>
                    <select name="assignee_id" style="width:100%; border:1px solid #E5E7EB; border-radius:9px; padding:10px 14px; font-size:14px; outline:none; font-family:inherit; color:#111827; background:#fff;">
                        <option value="">Unassigned</option>
                        @foreach($members as $member)
                        <option value="{{ $member->id }}">{{ $member->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display:block; font-size:12px; font-weight:600; color:#374151; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.04em;">Due Date</label>
                    <input name="due_date" type="date" value="{{ old('due_date') }}"
                        style="width:100%; border:1px solid #E5E7EB; border-radius:9px; padding:10px 14px; font-size:14px; outline:none; font-family:inherit; color:#111827;"
                        onfocus="this.style.borderColor='#6366F1'" onblur="this.style.borderColor='#E5E7EB'">
                </div>
            </div>

            <div style="display:flex; gap:10px; padding-top:4px;">
                <button type="submit" class="btn btn-primary" style="flex:1; justify-content:center;">Create Task</button>
                <a href="{{ route('teams.show',$team) }}" class="btn btn-ghost">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection