<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\Teams\CreateRequest;
use App\Http\Requests\Teams\UpdateRequest;
use App\Models\Team;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class TeamsController extends Controller
{

  public function index(): View
  {
    $this->authorize('isValidRole', Auth::user());

    $teams = Team::orderBy('id', 'desc')->get();

    return view('modules.teams.table', [
      'teams' => $teams,
    ]);
  }

  /**
   * Display the specified resource.
   */
  public function show(Request $request)
  {
    $request->validate([
      'search' => ['required', 'string']
    ]);

    $search = $request->input('search');

    $teams = Team::where('name', 'like', '%' . $search . '%')
      ->get();

    return view('modules.teams.table', [
      'teams' => $teams,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    $this->authorize('isValidRole', Auth::user());

    return view('modules.teams.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(CreateRequest $request): RedirectResponse
  {
    $this->authorize('isValidRole', Auth::user());

    $data = $request->validated();

    DB::beginTransaction();

    try {
      Team::create([
        'name' => $data['name'],
        'active' => true,
      ]);

      DB::commit();

    } catch (\Exception $err) {
      DB::rollBack();

      Log::error('ERROR CREATING TEAM', [
        'STATUS' => 'ERROR',
        'ACTION' => 'create team',
        'USER' => $request->user(),
        'MESSAGE' => $err->getMessage(),
        'LINE_CODE' => $err->getLine(),
        'FILE' => $err->getFile(),
      ]);

      return redirect()
        ->back()
        ->withErrors(['error' => 'There was an error creating the user.']);
    }

    return redirect()->route('teams.index')
      ->with('status', 'team created');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $this->authorize('isValidRole', Auth::user());

    $team = Team::where('id', $id)->first();

    if (!$team || !$team->active) {
      return redirect()
      ->back()
      ->withErrors(['error', 'Cannot edit team, it is deactivated']);
    }

    return view('modules.teams.edit', [
      'team' => $team,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateRequest $request, $id): RedirectResponse
  {
    $this->authorize('isValidRole', Auth::user());

    $team = Team::find($id);

    if (!$team || !$team->active) {
      return redirect()
        ->back()
        ->withErrors(['error', 'Team not found']);
    }

    $data = $request->validated();

    DB::beginTransaction();

    try {
      $team->update([
        'name' => $data['name'],
      ]);

      DB::commit();
    } catch (\Exception $err) {
      DB::rollBack();

      Log::error('ERROR UPDATING TEAM', [
        'STATUS' => 'ERROR',
        'ACTION' => 'update Team',
        'USER' => $request->user(),
        'MESSAGE' => $err->getMessage(),
        'LINE_CODE' => $err->getLine(),
        'FILE' => $err->getFile(),
      ]);

      return redirect()
        ->back()
        ->withErrors(['error', 'There was an error updating the team.']);
    }

    return redirect()->route('teams.index')
      ->with('status', 'Team updated');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id): RedirectResponse
  {
    $this->authorize('isValidRole', Auth::user());

    $team = Team::find($id);

    $team->update([
      'active' => !$team->active
    ]);

    return redirect()->route('teams.index')
    ->with('status', $team->active ? 'Team activated' : 'Team deactivated');
  }

}


