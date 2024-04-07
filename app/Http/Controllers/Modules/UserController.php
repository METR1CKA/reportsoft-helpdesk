<?php

namespace App\Http\Controllers\Modules;

use App\Http\Requests\Users\CreateRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Models\Role;
use App\Notifications\SendCredsNotification;
use App\Services\GenerateCodes;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index(): View
  {
    $this->authorize('isValidRole', Auth::user());

    $user_id = Auth::id();

    $users = User::with('role')
      ->where('id', '!=', $user_id)
      ->orderBy('id', 'desc')
      ->get();

    return view('modules.users.table', [
      'users' => $users,
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

    $_users = User::where('username', 'like', '%' . $search . '%')
      ->orWhere('email', 'like', '%' . $search . '%')
      ->orWhere('phone', 'like', '%' . $search . '%')
      ->orWhereHas('role', function ($query) use ($search) {
        $query->where('name', 'like', '%' . $search . '%');
      })
      ->get();

    $users = $_users->filter(function ($user) {
      return $user->id !== Auth::id();
    });

    return view('modules.users.table', [
      'users' => $users,
    ]);
  }


  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    $this->authorize('isValidRole', Auth::user());

    $roles = Role::getRoles();

    return view('modules.users.create', [
      'roles' => $roles,
    ]);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(CreateRequest $request): RedirectResponse
  {
    $this->authorize('isValidRole', Auth::user());

    $data = $request->validated();

    $role = Role::find($data['role_id']);

    if (!$role) {
      return redirect()
        ->back()
        ->withErrors(['role_id' => 'Role not found']);
    }

    $roles = Role::getRoles();

    $default = GenerateCodes::generateStringCode(12);

    DB::beginTransaction();

    try {
      $user = User::create([
        'username' => $data['username'],
        'email' => $data['email'],
        'password' => Hash::make($default),
        'active' => true,
        'phone' => $data['phone'],
      ]);

      DB::commit();

      $user->role()->attach(id: $role->id);

      DB::commit();

      $user->authFA()->create([
        'type' => '2FA'
      ]);

      DB::commit();

      $is_admin = $user->role()
        ->where('roles.id', $roles['ADMIN'])
        ->exists();

      if ($is_admin) {
        $user->authFA()->create([
          'type' => '3FA'
        ]);
      }

      DB::commit();

      $user->notify(
        instance: new SendCredsNotification(
          userCreated: $user,
          userRequest: $request->user(),
          passwd: $default
        )
      );
    } catch (\Exception $err) {
      DB::rollBack();

      Log::error('ERROR CREATING USER', [
        'STATUS' => 'ERROR',
        'ACTION' => 'create user',
        'USER' => $request->user(),
        'MESSAGE' => $err->getMessage(),
        'LINE_CODE' => $err->getLine(),
        'FILE' => $err->getFile(),
      ]);

      return redirect()
        ->back()
        ->withErrors(['role_id' => 'There was an error creating the user.']);
    }

    return redirect()->route('users.index')
      ->with('status', 'User created');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $this->authorize('isValidRole', Auth::user());

    $user = User::where('id', $id)->with('role')->first();


    if (!$user || !$user->active) {
      return redirect()
        ->back()
        ->with('status', 'Cannot edit user, it is deactivated');
    }

    $roles = Role::getRoles();

    return view('modules.users.edit', [
      'user' => $user,
      'roles' => $roles,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateRequest $request, $id): RedirectResponse
  {
    $this->authorize('isValidRole', Auth::user());

    $user = User::find($id);

    if (!$user || !$user->active) {
      return redirect()
        ->back()
        ->withErrors(['role_id' => 'User not found']);
    }

    $data = $request->validated();

    $role = Role::find($data['role_id']);

    if (!$role) {
      return redirect()
        ->back()
        ->withErrors(['role_id' => 'Role not found']);
    }

    DB::beginTransaction();

    try {
      $user->update([
        'username' => $data['username'],
        'email' => $data['email'],
        'phone' => $data['phone'],
      ]);

      DB::commit();

      $user->role()->sync([$role->id]);

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
        ->withErrors(['role_id' => 'There was an error updating the user.']);
    }

    return redirect()->route('users.index')
      ->with('status', 'User updated');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id): RedirectResponse
  {
    $this->authorize('isValidRole', Auth::user());

    $user = User::find($id);

    if (!$user) {
      return redirect()
        ->back()
        ->withErrors(['error' => 'User not found']);
    }

    $user->update([
      'active' => !$user->active
    ]);

    return redirect()->route('users.index')
      ->with('status', $user->active ? 'User activated' : 'User deactivated');
  }
}
