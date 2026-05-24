<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Team;
use Illuminate\Http\Request;

class TaskController extends Controller {

    public function create(Team $team) {
        $this->authorize('view', $team);
        $members = $team->members;
        return view('tasks.create', compact('team', 'members'));
    }

    public function store(Request $request, Team $team) {
        $this->authorize('view', $team);
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'status'      => 'required|in:todo,in_progress,done',
            'priority'    => 'required|in:low,medium,high',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date'    => 'nullable|date',
        ]);
        $team->tasks()->create([...$data, 'creator_id' => auth()->id()]);
        return redirect()->route('teams.show', $team)->with('success', 'Task created!');
    }

    public function edit(Team $team, Task $task) {
        $this->authorize('view', $team);
        $members = $team->members;
        return view('tasks.edit', compact('team', 'task', 'members'));
    }

    public function update(Request $request, Team $team, Task $task) {
        $this->authorize('view', $team);
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'nullable|string',
            'status'      => 'required|in:todo,in_progress,done',
            'priority'    => 'required|in:low,medium,high',
            'assignee_id' => 'nullable|exists:users,id',
            'due_date'    => 'nullable|date',
        ]);
        $task->update($data);
        return redirect()->route('teams.show', $team)->with('success', 'Task updated!');
    }

    public function destroy(Team $team, Task $task) {
        $this->authorize('view', $team);
        $task->delete();
        return back()->with('success', 'Task deleted!');
    }
}