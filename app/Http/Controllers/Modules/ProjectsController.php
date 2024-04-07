<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\Projects\CreateRequest;
use App\Http\Requests\Projects\UpdateRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller
{

  public function index(): View
  {
    $this->authorize('isValidRole', Auth::user());

    $projects = Project::orderBy('id', 'desc')->get();

    return view('modules.projects.table', [
      'projects' => $projects,
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

    $projects = Project::where('name', 'like', '%' . $search . '%')
      ->orWhere('description', 'like', '%' . $search . '%')
      ->get();

    return view('modules.projects.table', [
      'projects' => $projects,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    $this->authorize('isValidRole', Auth::user());

    return view('modules.projects.create', []);
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
      Project::create([
        'name' => $data['name'],
        'description' => $data['description'],
        'active' => true,
      ]);

      DB::commit();

    } catch (\Exception $err) {
      DB::rollBack();

      Log::error('ERROR CREATING PROJECT', [
        'STATUS' => 'ERROR',
        'ACTION' => 'create project',
        'USER' => $request->user(),
        'MESSAGE' => $err->getMessage(),
        'LINE_CODE' => $err->getLine(),
        'FILE' => $err->getFile(),
      ]);

      return redirect()
        ->back()
        ->withErrors(['error' => 'There was an error creating the Project.']);
    }

    return redirect()->route('projects.index')
      ->with('status', 'Projects created');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $this->authorize('isValidRole', Auth::user());

    $project = Project::where('id', $id)->first();

    if (!$project || !$project->active) {
      return redirect()
        ->back()
        ->withErrors(['error', 'Cannot edit project, it is deactivated']);
    }

    return view('modules.projects.edit', [
      'project' => $project,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateRequest $request, $id): RedirectResponse
  {
    $this->authorize('isValidRole', Auth::user());

    $project = Project::find($id);

    if (!$project || !$project->active) {
      return redirect()
        ->back()
        ->withErrors(['description' => 'Project not found']);
    }

    $data = $request->validated();

    DB::beginTransaction();

    try {
      $project->update([
        'name' => $data['name'],
        'description' => $data['description'],
      ]);

      DB::commit();
    } catch (\Exception $err) {
      DB::rollBack();

      Log::error('ERROR UPDATING PROJECT', [
        'STATUS' => 'ERROR',
        'ACTION' => 'update project',
        'USER' => $request->user(),
        'MESSAGE' => $err->getMessage(),
        'LINE_CODE' => $err->getLine(),
        'FILE' => $err->getFile(),
      ]);

      return redirect()
        ->back()
        ->withErrors(['error' => 'There was an error updating the Report Status']);
    }

    return redirect()->route('projects.index')
      ->with('status', 'Projects updated');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id): RedirectResponse
  {
    $this->authorize('isValidRole', Auth::user());

    $project = Project::find($id);

    $project->update([
      'active' => !$project->active
    ]);

    return redirect()->route('projects.index')
      ->with('status', $project->active ? 'Project activated' : 'Project deactivated');
  }
}
