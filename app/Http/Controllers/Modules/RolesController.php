<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\Roles\CreateRequest;
use App\Http\Requests\Roles\UpdateRequest;
use App\Models\Role;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class RolesController extends Controller
{
  public function index(): View
  {
    Gate::authorize('is-admin');

    $roles = Role::orderBy('id', 'desc')->get();

    return view('modules.roles.table', [
      'roles' => $roles,
    ]);
  }

  /**
   * Display the specified resource.
   */
  public function show(Request $request)
  {
    Gate::authorize('is-admin');

    $request->validate([
      'search' => ['required', 'string']
    ]);

    $search = $request->input('search');

    $roles = Role::where('name', 'like', '%' . $search . '%')
      ->orWhere('description', 'like', '%' . $search . '%')
      ->get();

    return view('modules.roles.table', [
      'roles' => $roles,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    Gate::authorize('is-admin');

    return view('modules.roles.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(CreateRequest $request): RedirectResponse
  {
    Gate::authorize('is-admin');

    $data = $request->validated();

    DB::beginTransaction();

    try {
      Role::create([
        'name' => $data['name'],
        'description' => $data['description'],
        'active' => true,
      ]);

      DB::commit();

    } catch (\Exception $err) {
      DB::rollBack();

      Log::error('ERROR CREATING ROLE', [
        'STATUS' => 'ERROR',
        'ACTION' => 'create role',
        'USER' => $request->user(),
        'MESSAGE' => $err->getMessage(),
        'LINE_CODE' => $err->getLine(),
        'FILE' => $err->getFile(),
      ]);

      return redirect()
        ->back()
        ->withErrors(['error' => 'There was an error creating the user.']);
    }

    return redirect()->route('roles.index')
      ->with('status', 'Role created');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    Gate::authorize('is-admin');

    $role = Role::where('id', $id)->first();

    if (!$role || !$role->active) {
      return redirect()
      ->back()
      ->withErrors(['error' => 'Cannot edit role, it is deactivated']);
    }

    return view('modules.roles.edit', [
      'role' => $role,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateRequest $request, $id): RedirectResponse
  {
    Gate::authorize('is-admin');

    $role = Role::find($id);

    if (!$role || !$role->active) {
      return redirect()
        ->back()
        ->withErrors(['error' => 'Role not found']);
    }

    $data = $request->validated();

    DB::beginTransaction();

    try {
      $role->update([
        'name' => $data['name'],
        'description' => $data['description'],
      ]);

      DB::commit();
    } catch (\Exception $err) {
      DB::rollBack();

      Log::error('ERROR UPDATING USER', [
        'STATUS' => 'ERROR',
        'ACTION' => 'update user',
        'USER' => $request->user(),
        'MESSAGE' => $err->getMessage(),
        'LINE_CODE' => $err->getLine(),
        'FILE' => $err->getFile(),
      ]);

      return redirect()
        ->back()
        ->withErrors(['error' => 'There was an error updating the role.']);
    }

    return redirect()->route('roles.index')
      ->with('status', 'Role updated');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id): RedirectResponse
  {
    Gate::authorize('is-admin');

    $role = Role::find($id);

    $role->update([
      'active' => !$role->active
    ]);

    return redirect()->route('roles.index')
      ->with('status', $role->active ? 'Role activated' : 'Role deactivated');
  }

}

