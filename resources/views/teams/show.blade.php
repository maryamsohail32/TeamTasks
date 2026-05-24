@extends('layouts.app')
@section('content')

<div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:28px;">
    <div>
        <p style="font-size:12px; color:#9CA3AF; margin-bottom:5px;">
            <a href="{{ route('teams.index') }}" style="color:#6366F1; text-decoration:none;">Teams</a>
            <span style="margin:0 6px;">›</span>
        </p>
        <h1 style="font-size:26px; font-weight:600; color:#1E1B4B; letter-spacing:-0.5px;">{{ $team->name }}</h1>
        <p style="font-size:13px; color:#9CA3AF; margin-top:3px;">{{ $team->members->count() }} members · {{ $tasks->count() }} tasks</p>
    </div>
    <a href="{{ route('tasks.create', $team) }}" class="btn btn-primary">
        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        Add Task
    </a>
</div>

{{-- Kanban --}}
<div style="display:grid; grid-template-columns:repeat(3,1fr); gap:20px; margin-bottom:32px;">
    @php
    $cols = [
        'todo'        => ['label'=>'To Do',       'color'=>'#6366F1','bg'=>'#EEF2FF','dot'=>'#818CF8'],
        'in_progress' => ['label'=>'In Progress',  'color'=>'#F59E0B','bg'=>'#FFFBEB','dot'=>'#FCD34D'],
        'done'        => ['label'=>'Done',          'color'=>'#10B981','bg'=>'#ECFDF5','dot'=>'#34D399'],
    ];
    @endphp

    @foreach($cols as $status => $col)
    <div style="background:#F8F7FF; border-radius:16px; padding:18px; border:1px solid #EDE9FE;">
        <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:16px;">
            <div style="display:flex; align-items:center; gap:8px;">
                <span style="width:8px;height:8px;border-radius:50%;background:{{ $col['dot'] }};display:inline-block;"></span>
                <span style="font-size:12px; font-weight:600; color:#374151; letter-spacing:0.04em; text-transform:uppercase;">{{ $col['label'] }}</span>
            </div>
            <span style="background:{{ $col['bg'] }}; color:{{ $col['color'] }}; font-size:11px; font-weight:600; padding:2px 9px; border-radius:99px;">
                {{ $tasks->where('status',$status)->count() }}
            </span>
        </div>

        <div style="display:flex; flex-direction:column; gap:10px;">
        @forelse($tasks->where('status',$status) as $task)
        <div style="background:#fff; border-radius:12px; padding:14px; border:1px solid #EDE9FE; transition:box-shadow 0.15s;"
             onmouseover="this.style.boxShadow='0 4px 16px rgba(99,102,241,0.1)'"
             onmouseout="this.style.boxShadow='none'">

            @php
            $pColors = ['high'=>['#FEF2F2','#DC2626'],'medium'=>['#FFFBEB','#D97706'],'low'=>['#F0FDF4','#16A34A']];
            $pc = $pColors[$task->priority] ?? ['#F3F4F6','#6B7280'];
            @endphp

            <div style="display:flex; justify-content:space-between; align-items:flex-start; gap:8px; margin-bottom:10px;">
                <p style="font-size:13px; font-weight:500; color:#111827; line-height:1.45; flex:1;">{{ $task->title }}</p>
                <span style="background:{{ $pc[0] }}; color:{{ $pc[1] }}; font-size:10px; font-weight:600; padding:2px 8px; border-radius:99px; flex-shrink:0; text-transform:uppercase; letter-spacing:0.04em;">
                    {{ $task->priority }}
                </span>
            </div>

            @if($task->assignee)
            <div style="display:flex; align-items:center; gap:6px; margin-bottom:8px;">
                <div style="width:20px;height:20px;border-radius:50%;background:linear-gradient(135deg,#818CF8,#6366F1);display:flex;align-items:center;justify-content:center;font-size:9px;font-weight:600;color:#fff;">
                    {{ strtoupper(substr($task->assignee->name,0,2)) }}
                </div>
                <span style="font-size:11px; color:#6B7280;">{{ $task->assignee->name }}</span>
            </div>
            @endif

            @if($task->due_date)
            <div style="display:inline-flex; align-items:center; gap:4px; background:{{ $task->isOverdue() ? '#FEF2F2' : '#F3F4F6' }}; color:{{ $task->isOverdue() ? '#DC2626' : '#6B7280' }}; padding:3px 8px; border-radius:6px; font-size:11px; margin-bottom:8px;">
                <svg width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                {{ $task->due_date->format('M d, Y') }}{{ $task->isOverdue() ? ' · Overdue' : '' }}
            </div>
            @endif

            <div style="display:flex; gap:8px; padding-top:8px; border-top:1px solid #F3F4F6;">
                <a href="{{ route('tasks.edit',[$team,$task]) }}"
                   style="font-size:11px; font-weight:500; color:#6366F1; text-decoration:none; padding:4px 10px; background:#EEF2FF; border-radius:6px;">
                   Edit
                </a>
                <form method="POST" action="{{ route('tasks.destroy',[$team,$task]) }}" onsubmit="return confirm('Delete this task?')" style="display:inline;">
                    @csrf @method('DELETE')
                    <button style="font-size:11px; font-weight:500; color:#DC2626; background:#FEF2F2; border:none; padding:4px 10px; border-radius:6px; cursor:pointer; font-family:inherit;">
                        Delete
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="text-align:center; padding:36px 16px; color:#D1D5DB;">
            <svg width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" style="margin:0 auto 8px; display:block; color:#E5E7EB;"><rect x="3" y="3" width="18" height="18" rx="3"/><path d="M9 12h6M12 9v6"/></svg>
            <p style="font-size:12px;">No tasks here</p>
        </div>
        @endforelse
        </div>
    </div>
    @endforeach
</div>

{{-- Invite --}}
@can('update', $team)
<div class="card" style="max-width:460px;">
    <h3 style="font-size:15px; font-weight:600; color:#1E1B4B; margin-bottom:4px;">Team Members</h3>
    <p style="font-size:12px; color:#9CA3AF; margin-bottom:16px;">Invite people to collaborate</p>

    <form method="POST" action="{{ route('teams.invite',$team) }}" style="display:flex; gap:8px; margin-bottom:16px;">
        @csrf
        <input name="email" type="email" required placeholder="colleague@email.com"
            style="flex:1; border:1px solid #E5E7EB; border-radius:9px; padding:9px 14px; font-size:13px; outline:none; font-family:inherit; color:#111827;"
            onfocus="this.style.borderColor='#6366F1'" onblur="this.style.borderColor='#E5E7EB'">
        <button type="submit" class="btn btn-primary" style="padding:9px 18px;">Invite</button>
    </form>
    @error('email')<p style="color:#DC2626; font-size:12px; margin-top:-10px; margin-bottom:12px;">{{ $message }}</p>@enderror

    <div style="border-top:1px solid #F3F4F6; padding-top:14px; display:flex; flex-direction:column; gap:10px;">
        @foreach($members as $member)
        <div style="display:flex; align-items:center; gap:10px;">
            <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#818CF8,#6366F1);display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:600;color:#fff;flex-shrink:0;">
                {{ strtoupper(substr($member->name,0,2)) }}
            </div>
            <div style="flex:1;">
                <p style="font-size:13px; font-weight:500; color:#111827;">{{ $member->name }}</p>
                <p style="font-size:11px; color:#9CA3AF;">{{ $member->email }}</p>
            </div>
            <span style="font-size:11px; font-weight:500; padding:3px 10px; border-radius:99px;
                {{ $member->pivot->role === 'owner' ? 'background:#EEF2FF;color:#4F46E5;' : 'background:#F3F4F6;color:#6B7280;' }}">
                {{ $member->pivot->role }}
            </span>
        </div>
        @endforeach
    </div>
</div>
@endcan

@endsection