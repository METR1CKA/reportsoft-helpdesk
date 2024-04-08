<?php

namespace App\Http\Controllers\Modules;

use App\Http\Requests\Enterprises\CreateRequest;
use App\Http\Requests\Enterprises\UpdateRequest;
use App\Models\Enterprise;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class EnterprisesController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    Gate::authorize('is-admin-coordinator');

    $enterprises = Enterprise::orderBy('id', 'desc')->get();

    return view('modules.enterprises.table', [
      'enterprises' => $enterprises,
    ])
      ->with('status', 'Success');
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

    $enterprises = Enterprise::where('contact_name', 'like', '%' . $search . '%')
      ->orWhere('contact_phone', 'like', '%' . $search . '%')
      ->orWhere('contact_email', 'like', '%' . $search . '%')
      ->orWhere('legal_id', 'like', '%' . $search . '%')
      ->orWhere('legal_name', 'like', '%' . $search . '%')
      ->get();

    return view('modules.enterprises.table', [
      'enterprises' => $enterprises,
    ])
      ->with('status', 'Success');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    Gate::authorize('is-admin-coordinator');

    return view('modules.enterprises.create');
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(CreateRequest $request)
  {
    Gate::authorize('is-admin-coordinator');

    $data = $request->validated();

    DB::beginTransaction();

    try {
      Enterprise::create([
        'contact_name' => $data['contact_name'],
        'contact_email' => $data['contact_email'],
        'contact_phone' => $data['contact_phone'],
        'legal_id' => $data['legal_id'],
        'legal_name' => $data['legal_name'],
        'active' => true,
      ]);

      DB::commit();
    } catch (\Exception $err) {
      DB::rollBack();

      Log::error('ERROR CREATING ENTERPRISE', [
        'STATUS' => 'ERROR',
        'ACTION' => 'create enterprise',
        'USER' => $request->user(),
        'MESSAGE' => $err->getMessage(),
        'LINE_CODE' => $err->getLine(),
        'FILE' => $err->getFile(),
      ]);

      return redirect()
        ->back()
        ->withErrors(['error' => 'There was an error creating the enterprise.']);
    }

    return redirect()->route('enterprises.index')
      ->with('status', 'Enterprise created');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    Gate::authorize('is-admin-coordinator');

    $enterprise = Enterprise::where('id', $id)->first();

    if (!$enterprise->active) {
      return redirect()
        ->back()
        ->withErrors(['error' => 'Cannot edit enterprise, it is deactivated']);
    }

    return view('modules.enterprises.edit', [
      'enterprise' => $enterprise,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateRequest $request, string $id)
  {
    Gate::authorize('is-admin-coordinator');

    $enterprise = Enterprise::find($id);

    $data = $request->validated();

    DB::beginTransaction();

    try {
      $enterprise->update([
        'contact_name' => $data['contact_name'],
        'contact_email' => $data['contact_email'],
        'contact_phone' => $data['contact_phone'],
        'legal_id' => $data['legal_id'],
        'legal_name' => $data['legal_name'],
      ]);

      DB::commit();
    } catch (\Exception $err) {
      DB::rollBack();

      Log::error('ERROR UPDATING ENTERPRISE', [
        'STATUS' => 'ERROR',
        'ACTION' => 'update enterprise',
        'USER' => $request->user(),
        'MESSAGE' => $err->getMessage(),
        'LINE_CODE' => $err->getLine(),
        'FILE' => $err->getFile(),
      ]);

      return redirect()
        ->back()
        ->withErrors(['error' => 'There was an error updating the enterprise.']);
    }

    return redirect()->route('enterprises.index')
      ->with('status', 'Enterprise updated');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    Gate::authorize('is-admin');

    $enterprise = Enterprise::find($id);

    $enterprise->update([
      'active' => !$enterprise->active
    ]);

    return redirect()->route('enterprises.index')
      ->with('status', 'Enterprise ' . ($enterprise->active ? 'activated' : 'deactivated'));
  }
}
