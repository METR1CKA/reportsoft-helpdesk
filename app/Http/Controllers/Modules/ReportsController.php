<?php

namespace App\Http\Controllers\Modules;

use App\Models\Area;
use App\Models\Enterprise;
use App\Models\Project;
use App\Models\Report;
use App\Models\ReportStatus;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Reports\CreateRequest;
use App\Http\Requests\Reports\UpdateRequest;

class ReportsController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $roles = Role::getRoles();

    $reports = Report::with('user', 'area', 'enterprise', 'project', 'reportStatus')
      ->orderBy('id', 'desc')
      ->get();

    $is_guest = Auth::user()->role()
      ->where('roles.id', '!=', $roles['ADMIN'])
      ->where('roles.id', '!=', $roles['COORDINATOR'])
      ->exists();

    if ($is_guest) {
      $reports = $reports->filter(function ($report) {
        return $report->active && $report->user_id == Auth::id();
      });
    }

    return view('modules.reports.table', [
      'reports' => $reports,
    ])
      ->with('status', 'Success');
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

    $reports = Report::where('name', 'like', '%' . $search . '%')
      ->orWhereHas('enterprise', function ($query) use ($search) {
        $query->where('legal_name', 'like', '%' . $search . '%');
      })
      ->orWhereHas('project', function ($query) use ($search) {
        $query->where('name', 'like', '%' . $search . '%');
      })
      ->orWhereHas('reportStatus', function ($query) use ($search) {
        $query->where('name', 'like', '%' . $search . '%');
      })
      ->orWhereHas('user', function ($query) use ($search) {
        $query->where('username', 'like', '%' . $search . '%');
      })
      ->orWhereHas('area', function ($query) use ($search) {
        $query->where('name', 'like', '%' . $search . '%');
      })
      ->get();

    return view('modules.reports.table', [
      'reports' => $reports,
    ])
      ->with('status', 'Success');
  }

  /**
   * Show the form for creating a new resource.
   */
  public function create()
  {
    Gate::authorize('is-admin-coordinator');

    $query_users = User::select(['id', 'username'])
      ->orderBy('id', 'desc')
      ->get();

    $users = $query_users->filter(function ($user) {
      $roles = Role::getRoles();

      return $user
        ->role()
        ->where('roles.id', $roles['GUEST'])
        ->exists();
    });

    $areas = Area::select(['id', 'name'])
      ->orderBy('id', 'desc')
      ->get();

    $enterprises = Enterprise::select(['id', 'legal_name'])
      ->orderBy('id', 'desc')
      ->get();

    $projects = Project::select(['id', 'name'])
      ->orderBy('id', 'desc')
      ->get();

    $report_statuses = ReportStatus::select(['id', 'name'])
      ->orderBy('id', 'desc')
      ->get();

    return view('modules.reports.create', [
      'users' => $users,
      'areas' => $areas,
      'enterprises' => $enterprises,
      'projects' => $projects,
      'report_statuses' => $report_statuses,
    ]);
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
      Report::create([
        'user_id' => $data['user_id'],
        'area_id' => $data['area_id'],
        'enterprise_id' => $data['enterprise_id'],
        'project_id' => $data['project_id'],
        'report_status_id' => $data['report_status_id'],
        'name' => $data['name'],
        'description' => $data['description'],
        'comments' => $data['comments'],
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

    return redirect()->route('reports.index')
      ->with('status', 'Report created');
  }

  /**
   * Show the form for editing the specified resource.
   */
  public function edit($id)
  {
    Gate::authorize('is-admin-coordinator');

    $report = Report::where('id', $id)->first();

    if (!$report->active) {
      return redirect()
        ->back()
        ->withErrors(['error' => 'Cannot edit report, it is deactivated']);
    }

    $query_users = User::select(['id', 'username'])
      ->orderBy('id', 'desc')
      ->get();

    $users = $query_users->filter(function ($user) {
      $roles = Role::getRoles();

      return $user
        ->role()
        ->where('roles.id', $roles['GUEST'])
        ->exists();
    });

    $areas = Area::select(['id', 'name'])
      ->orderBy('id', 'desc')
      ->get();

    $enterprises = Enterprise::select(['id', 'legal_name'])
      ->orderBy('id', 'desc')
      ->get();

    $projects = Project::select(['id', 'name'])
      ->orderBy('id', 'desc')
      ->get();

    $report_statuses = ReportStatus::select(['id', 'name'])
      ->orderBy('id', 'desc')
      ->get();

    return view('modules.reports.edit', [
      'report' => $report,
      'users' => $users,
      'areas' => $areas,
      'enterprises' => $enterprises,
      'projects' => $projects,
      'report_statuses' => $report_statuses,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(UpdateRequest $request, string $id)
  {
    Gate::authorize('is-admin-coordinator');

    $report = Report::find($id);

    $data = $request->validated();

    DB::beginTransaction();

    try {
      $report->update([
        'user_id' => $data['user_id'],
        'area_id' => $data['area_id'],
        'enterprise_id' => $data['enterprise_id'],
        'project_id' => $data['project_id'],
        'report_status_id' => $data['report_status_id'],
        'name' => $data['name'],
        'description' => $data['description'],
        'comments' => $data['comments'],
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

    return redirect()->route('reports.index')
      ->with('status', 'Enterprise updated');
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy($id)
  {
    Gate::authorize('is-admin-coordinator');

    $report = Report::find($id);

    $report->update([
      'active' => !$report->active
    ]);

    return redirect()->route('reports.index')
      ->with('status', 'Report ' . ($report->active ? 'activated' : 'deactivated'));
  }
}
