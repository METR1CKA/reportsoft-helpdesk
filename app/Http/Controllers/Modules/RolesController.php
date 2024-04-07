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

class RolesController extends Controller
{
  public function index(): View
  {
    $this->authorize('isValidRole', Auth::user());

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
    $this->authorize('isValidRole', Auth::user());

    return view('modules.roles.create');
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
        ->withErrors(['description' => 'There was an error creating the user.']);
    }

    return redirect()->route('roles.index')
      ->with('status', 'Role created');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $this->authorize('isValidRole', Auth::user());

    $role = Role::where('id', $id)->first();

    if (!$role || !$role->active) {
      return redirect()
      ->back()
      ->with('status', 'Cannot edit role, it is deactivated');
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
    $this->authorize('isValidRole', Auth::user());

    $role = Role::find($id);

    if (!$role || !$role->active) {
      return redirect()
        ->back()
        ->withErrors(['description' => 'Role not found']);
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
        ->withErrors(['description' => 'There was an error updating the role.']);
    }

    return redirect()->route('roles.index')
      ->with('status', 'Role updated');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id): RedirectResponse
  {
    Log::info('REQUEST TO DELETE ROLE', [
      'ACTION' => 'Delete role',
      'CONTROLLER' => RolesController::class,
      'USER-AUTH' => Auth::user(),
      'ID' => $id,
      'METHOD' => 'destroy',
    ]);

    $this->authorize('isValidRole', Auth::user());

    $role = Role::find($id);

    if (!$role) {
      Log::alert('ROLE NOT FOUND', [
        'STATUS' => 'ERROR',
        'ACTION' => 'Delete role',
        'USER-AUTH' => Auth::user(),
        'ROLE' => $role ?? 'NOT FOUND',
      ]);

      return redirect()
        ->back()
        ->with('status', 'Role not found');
    }

    $role->update([
      'active' => !$role->active
    ]);

    Log::info('ROLE DELETED', [
      'STATUS' => 'SUCCESS',
      'ACTION' => 'Delete role',
      'USER-AUTH' => Auth::user(),
      'ROLE' => $role,
    ]);

    return redirect()->route('roles.index')
      ->with('status', $role->active ? 'Role activated' : 'Role deactivated');
  }

}

