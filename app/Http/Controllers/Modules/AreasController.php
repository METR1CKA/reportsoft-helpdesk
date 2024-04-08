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
use Illuminate\Support\Facades\Gate;

class AreasController extends Controller
{
  public function index(): View
  {
    Gate::authorize('is-admin-coordinator');

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
    Gate::authorize('is-admin-coordinator');

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
    Gate::authorize('is-admin-coordinator');

    return view('modules.areas.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(CreateRequest $request): RedirectResponse
  {
    Gate::authorize('is-admin-coordinator');

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
        ->withErrors(['description' => 'There was an error creating the area.']);
    }

    return redirect()->route('areas.index')
      ->with('status', 'Area created');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    Gate::authorize('is-admin-coordinator');

    $area = Area::where('id', $id)->first();

    if (!$area || !$area->active) {
      return redirect()
        ->back()
        ->withErrors(['error' => 'Cannot edit area, it is deactivated']);
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
    Gate::authorize('is-admin-coordinator');

    $area = Area::find($id);

    if (!$area->active) {
      return redirect()
        ->back()
        ->withErrors(['description' => 'Area not found']);
    }

    $data = $request->validated();

    DB::beginTransaction();

    try {
      $area->update([
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
    Gate::authorize('is-admin');

    $area = Area::find($id);

    $area->update([
      'active' => !$area->active
    ]);

    return redirect()->route('areas.index')
      ->with('status', $area->active ? 'Area activated' : 'Area deactivated');
  }
}
