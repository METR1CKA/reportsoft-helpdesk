<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\Areas\CreateRequest;
use App\Http\Requests\Areas\UpdateRequest;
use App\Models\Area;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class AreasController extends Controller
{

  public function index(): View
  {
    $this->authorize('isValidRole', Auth::user());

    $areas = Area::orderBy('id', 'desc')->get();

    return view('modules.areas.table', [
      'areas' => $areas,
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

    $areas = Area::where('name', 'like', '%' . $search . '%')
      ->orWhere('description', 'like', '%' . $search . '%')
      ->get();

    return view('modules.areas.table', [
      'areas' => $areas,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    $this->authorize('isValidRole', Auth::user());

    return view('modules.areas.create');
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
      Area::create([
        'name' => $data['name'],
        'description' => $data['description'],
        'active' => true,
      ]);

      DB::commit();

    } catch (\Exception $err) {
      DB::rollBack();

      Log::error('ERROR CREATING AREA', [
        'STATUS' => 'ERROR',
        'ACTION' => 'create area',
        'USER' => $request->user(),
        'MESSAGE' => $err->getMessage(),
        'LINE_CODE' => $err->getLine(),
        'FILE' => $err->getFile(),
      ]);

      return redirect()
        ->back()
        ->withErrors(['description' => 'There was an error creating the user.']);
    }

    return redirect()->route('areas.index')
      ->with('status', 'Area created');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $this->authorize('isValidRole', Auth::user());

    $area = Area::where('id', $id)->first();

    if (!$area || !$area->active) {
      return redirect()
      ->back()
      ->with('status', 'Cannot edit area, it is deactivated');
    }

    return view('modules.areas.edit', [
      'area' => $area,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateRequest $request, $id): RedirectResponse
  {
    $this->authorize('isValidRole', Auth::user());

    $Area = Area::find($id);

    if (!$Area || !$Area->active) {
      return redirect()
        ->back()
        ->withErrors(['description' => 'Area not found']);
    }

    $data = $request->validated();

    DB::beginTransaction();

    try {
      $Area->update([
        'name' => $data['name'],
        'description' => $data['description'],
      ]);

      DB::commit();
    } catch (\Exception $err) {
      DB::rollBack();

      Log::error('ERROR UPDATING AREA', [
        'STATUS' => 'ERROR',
        'ACTION' => 'update area',
        'USER' => $request->user(),
        'MESSAGE' => $err->getMessage(),
        'LINE_CODE' => $err->getLine(),
        'FILE' => $err->getFile(),
      ]);

      return redirect()
        ->back()
        ->withErrors(['description' => 'There was an error updating the area.']);
    }

    return redirect()->route('areas.index')
      ->with('status', 'Area updated');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id): RedirectResponse
  {
    Log::info('REQUEST TO DELETE AREA', [
      'ACTION' => 'Delete area',
      'CONTROLLER' => AreasController::class,
      'USER-AUTH' => Auth::user(),
      'ID' => $id,
      'METHOD' => 'destroy',
    ]);

    $this->authorize('isValidRole', Auth::user());

    $Area = Area::find($id);

    if (!$Area) {
      Log::alert('AREA NOT FOUND', [
        'STATUS' => 'ERROR',
        'ACTION' => 'Delete area',
        'USER-AUTH' => Auth::user(),
        'AREA' => $Area ?? 'NOT FOUND',
      ]);

      return redirect()
        ->back()
        ->withErrors(['description' => 'Area not found']);
    }

    $Area->update([
      'active' => !$Area->active
    ]);

    Log::info('AREA DELETED', [
      'STATUS' => 'SUCCESS',
      'ACTION' => 'Delete area',
      'USER-AUTH' => Auth::user(),
      'AREA' => $Area,
    ]);

    return redirect()->route('areas.index')
    ->with('status', $Area->active ? 'Area activated' : 'Area deactivated');
  }

}

