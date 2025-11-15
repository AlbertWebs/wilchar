<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller
{
    public function index(Request $request): View|JsonResponse
    {
        $teams = Team::with([
            'members' => fn ($query) => $query->select('users.id', 'users.name', 'users.email')->with('roles'),
        ])->withCount([
            'loanOfficers as loan_officers_count',
            'collectionOfficers as collection_officers_count',
            'financeMembers as finance_officers_count',
        ])->orderBy('name')->get();

        if ($request->wantsJson()) {
            return response()->json([
                'teams' => $teams,
            ]);
        }

        $users = User::orderBy('name')->get();

        return view('admin.teams.index', [
            'teams' => $teams,
            'users' => $users,
        ]);
    }

    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams,name',
            'description' => 'nullable|string|max:500',
        ]);

        $team = Team::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'created_by' => auth()->id(),
        ]);

        AuditLog::log(Team::class, $team->id, 'created', "Team {$team->name} created", null, $team->toArray());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Team created successfully.',
                'team' => $team->load('members'),
            ]);
        }

        return redirect()->route('teams.index')->with('success', 'Team created successfully.');
    }

    public function update(Request $request, Team $team): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id,
            'description' => 'nullable|string|max:500',
        ]);

        $old = $team->toArray();
        $team->update($validated);

        AuditLog::log(Team::class, $team->id, 'updated', "Team {$team->name} updated", $old, $team->fresh()->toArray());

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Team updated successfully.',
                'team' => $team->load('members'),
            ]);
        }

        return redirect()->route('teams.index')->with('success', 'Team updated successfully.');
    }

    public function destroy(Request $request, Team $team): JsonResponse|RedirectResponse
    {
        $teamName = $team->name;
        $team->delete();

        AuditLog::log(Team::class, $team->id, 'deleted', "Team {$teamName} deleted");

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Team deleted successfully.',
            ]);
        }

        return redirect()->route('teams.index')->with('success', 'Team deleted successfully.');
    }

    public function assignMember(Request $request, Team $team): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|in:loan_officer,collection_officer,finance,marketer,recovery_officer',
        ]);

        $team->members()->syncWithoutDetaching([
            $validated['user_id'] => ['role' => $validated['role']],
        ]);

        AuditLog::log(
            Team::class,
            $team->id,
            'member_assigned',
            "User {$validated['user_id']} assigned to team {$team->name} as {$validated['role']}"
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Member assigned successfully.',
                'team' => $team->load('members'),
            ]);
        }

        return back()->with('success', 'Member assigned successfully.');
    }

    public function removeMember(Request $request, Team $team, User $user): JsonResponse|RedirectResponse
    {
        $team->members()->detach($user->id);

        AuditLog::log(
            Team::class,
            $team->id,
            'member_removed',
            "User {$user->id} removed from team {$team->name}"
        );

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Member removed successfully.',
                'team' => $team->load('members'),
            ]);
        }

        return back()->with('success', 'Member removed successfully.');
    }
}

