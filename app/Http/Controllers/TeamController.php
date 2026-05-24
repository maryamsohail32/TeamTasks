<?php
namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\TeamInviteMail;

class TeamController extends Controller {
    public function index() {
        $teams = auth()->user()->teams()->with('members', 'tasks')->get();
        return view('teams.index', compact('teams'));
    }

    public function create() {
        return view('teams.create');
    }

    public function store(Request $request) {
        $data = $request->validate(['name' => 'required|string|max:100']);
        $team = Team::create([...$data, 'owner_id' => auth()->id()]);
        $team->members()->attach(auth()->id(), ['role' => 'owner']);
        return redirect()->route('teams.show', $team)->with('success', 'Team created!');
    }

    public function show(Team $team) {
        $this->authorize('view', $team);
        $tasks = $team->tasks()->with('assignee', 'creator')->latest()->get();
        $members = $team->members;
        return view('teams.show', compact('team', 'tasks', 'members'));
    }

    public function invite(Request $request, Team $team) {
        $this->authorize('update', $team);
        $data = $request->validate(['email' => 'required|email']);
        $user = User::FirstOrCreate(['email' => $data['email']], ['name' => 'Pending Invite', 'password' => bcrypt(Str::random(12))]);
        if (!$team->members->contains($user)) {
            $team->members()->attach($user->id, ['role' => 'member']);
        }
        Mail::to($user->email)->send(new TeamInviteMail($team, $user));
        return back()->with('success', 'Member invited!');
    }
}