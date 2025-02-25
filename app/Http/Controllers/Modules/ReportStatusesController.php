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
use Illuminate\Support\Facades\Gate;

class ReportStatusesController extends Controller
{
  public function index(): View
  {
    Gate::authorize('is-admin-coordinator');

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
    Gate::authorize('is-admin-coordinator');

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
    Gate::authorize('is-admin-coordinator');

    return view('modules.report_statuses.create');
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
        ->withErrors(['error' => 'There was an error creating the report status.']);
    }

    return redirect()->route('report_statuses.index')
      ->with('status', 'Report Statuses created');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    Gate::authorize('is-admin-coordinator');

    $report_status = ReportStatus::where('id', $id)->first();

    if (!$report_status || !$report_status->active) {
      return redirect()
        ->back()
        ->withErrors(['error' => 'Cannot edit report status, it is deactivated']);
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
    Gate::authorize('is-admin-coordinator');

    $report_status = ReportStatus::find($id);

    if (!$report_status || !$report_status->active) {
      return redirect()
        ->back()
        ->withErrors(['error' => 'Report Status not found']);
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
        ->withErrors(['error' => 'There was an error updating the report status.']);
    }

    return redirect()->route('report_statuses.index')
      ->with('status', 'Report Status updated');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id): RedirectResponse
  {
    Gate::authorize('is-admin');

    $report_status = ReportStatus::find($id);

    $report_status->update([
      'active' => !$report_status->active
    ]);

    return redirect()->route('report_statuses.index')
      ->with('status', $report_status->active ? 'Report Status activated' : 'Report Status deactivated');
  }
}
