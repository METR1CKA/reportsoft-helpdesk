<?php

namespace App\Http\Controllers\Modules;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportStatuses\CreateRequest;
use App\Http\Requests\ReportStatuses\UpdateRequest;
use App\Models\ReportStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ReportStatusesController extends Controller
{
  public function index(): View
  {
    $this->authorize('isValidRole', Auth::user());

    $report_statuses = ReportStatus::orderBy('id', 'desc')->get();

    return view('modules.report_statuses.table', [
      'report_statuses' => $report_statuses,
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

    $roles = ReportStatus::where('name', 'like', '%' . $search . '%')
      ->orWhere('description', 'like', '%' . $search . '%')
      ->get();

    return view('modules.report_statuses.table', [
      'report_statuses' => $roles,
    ]);
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create(): View
  {
    $this->authorize('isValidRole', Auth::user());

    return view('modules.report_statuses.create');
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
      $role = ReportStatus::create([
        'name' => $data['name'],
        'description' => $data['description'],
        'active' => true,
      ]);

      DB::commit();

    } catch (\Exception $err) {
      DB::rollBack();

      Log::error('ERROR CREATING REPORT STATUS', [
        'STATUS' => 'ERROR',
        'ACTION' => 'create report status',
        'USER' => $request->user(),
        'MESSAGE' => $err->getMessage(),
        'LINE_CODE' => $err->getLine(),
        'FILE' => $err->getFile(),
      ]);

      return redirect()
        ->back()
        ->withErrors(['description' => 'There was an error creating the report status.']);
    }

    return redirect()->route('report_statuses.index')
      ->with('status', 'Report Statuses created');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    $this->authorize('isValidRole', Auth::user());

    $report_status = ReportStatus::where('id', $id)->first();

    if (!$report_status || !$report_status->active) {
      return redirect()
        ->back()
        ->with('status', 'Cannot edit report status, it is deactivated');
    }

    return view('modules.report_statuses.edit', [
      'report_status' => $report_status,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateRequest $request, $id): RedirectResponse
  {
    $this->authorize('isValidRole', Auth::user());

    $report_status = ReportStatus::find($id);

    if (!$report_status || !$report_status->active) {
      return redirect()
        ->back()
        ->withErrors(['description' => 'Report Status not found']);
    }

    $data = $request->validated();

    DB::beginTransaction();

    try {
      $report_status->update([
        'name' => $data['name'],
        'description' => $data['description'],
      ]);

      DB::commit();
    } catch (\Exception $err) {
      DB::rollBack();

      Log::error('ERROR UPDATING REPORT STATUS', [
        'STATUS' => 'ERROR',
        'ACTION' => 'update report status',
        'USER' => $request->user(),
        'MESSAGE' => $err->getMessage(),
        'LINE_CODE' => $err->getLine(),
        'FILE' => $err->getFile(),
      ]);

      return redirect()
        ->back()
        ->withErrors(['description' => 'There was an error updating the report status.']);
    }

    return redirect()->route('report_statuses.index')
      ->with('status', 'Report Status updated');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id): RedirectResponse
  {
    Log::info('REQUEST TO DELETE REPORT STATUS', [
      'ACTION' => 'Delete report status',
      'CONTROLLER' => ReportStatusesController::class,
      'USER-AUTH' => Auth::user(),
      'ID' => $id,
      'METHOD' => 'destroy',
    ]);

    $this->authorize('isValidRole', Auth::user());

    $report_status = ReportStatus::find($id);

    if (!$report_status) {
      Log::alert('REPORT STATUS NOT FOUND', [
        'STATUS' => 'ERROR',
        'ACTION' => 'Delete report status',
        'USER-AUTH' => Auth::user(),
        'REPORT STATUS' => $report_status ?? 'NOT FOUND',
      ]);

      return redirect()
        ->back()
        ->withErrors(['error' => 'User not found']);
    }

    $report_status->update([
      'active' => !$report_status->active
    ]);

    Log::info('REPORT STATUS DELETED', [
      'STATUS' => 'SUCCESS',
      'ACTION' => 'Delete report status',
      'USER-AUTH' => Auth::user(),
      'REPORT STATUS' => $report_status,
    ]);

    return redirect()->route('report_statuses.index')
      ->with('status', $report_status->active ? 'Report Status activated' : 'Report Status deactivated');
  }
}
