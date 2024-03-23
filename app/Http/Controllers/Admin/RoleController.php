<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Session;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Exceptions\RoleAlreadyExists;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware('permission:role-read', ['only' => ['index']]);
        $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:role-update', ['only' => ['edit', 'update']]);
        $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $pageTitle = 'Roles';
        $roles = Role::get();
        // $permission = Permission::get();

        $modules = [
            'Dashboard' => ['dashboard-read'],
            'Role Management' => ['role-read', 'role-create', 'role-update', 'role-delete'],
            'User Management' => ['user-read', 'user-create', 'user-update', 'user-delete'],
            'Period Management' => ['period-read', 'period-create', 'period-update', 'period-delete'],
            'Class Management' => ['class-read', 'class-create', 'class-update', 'class-delete'],
            'Department Management' => ['department-read', 'department-create', 'department-update', 'department-delete'],
            'Faculty Management' => ['faculty-read', 'faculty-create', 'faculty-update', 'faculty-delete'],
            'Subject Management' => ['subject-read', 'subject-create', 'subject-update', 'subject-delete'],
            'Student Management' => ['student-read', 'student-create', 'student-update', 'student-delete', 'student-import'],
            'Settings' => ['settings-read', 'settings-update'],
        ];
        $groupedPermissions = [];
        foreach ($modules as $module => $permissions) {
            $groupedPermissions[$module] = Permission::whereIn('name', $permissions)->get();
        }

        return view('admin.roles.index', compact('pageTitle', 'roles', 'groupedPermissions'));
    }

    public function list()
    {
        if (request()->ajax()) {
            $data = Role::get();
            return (new DataTables)->of($data)
                ->addColumn('roles', function ($row) {
                    return $row->name;
                })
                ->make(true);
        }
    }

    public function create()
    {
        $permission = Permission::get();
        return view('admin.roles.create', compact(['permission']));
    }


    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);

        try {
            $role = Role::create([
                'name' => $request->input('name'),
            ]);
        } catch (RoleAlreadyExists $exception) {
            $errorMessage = $exception->getMessage();
            Session::flash('error', $errorMessage);

            return redirect()->back();
        }

        $role->syncPermissions($request->input('permission'));

        return response()->json(['success' => true]);
    }

    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('admin.roles.show', compact('role', 'rolePermissions'));
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $rolePermissions = DB::table("role_has_permissions")
            ->where("role_has_permissions.role_id", $id)
            ->pluck('role_has_permissions.permission_id')
            ->toArray();

        return response()->json([
            'role' => $role,
            'permissions' => $permission,
            'rolePermissions' => $rolePermissions,
        ]);
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id);
        $role->name = $request->input('name');
        $role->save();

        $role->syncPermissions($request->input('permission'));

        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        try {
            DB::table("roles")->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            return response()->json(['success' => false]);
        }
    }
}
