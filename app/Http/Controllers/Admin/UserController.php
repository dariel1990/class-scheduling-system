<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Services\UserService;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\QueryException;

class UserController extends Controller
{
    protected $userService;

    function __construct(UserService $userService)
    {
        $this->userService = $userService;

        $this->middleware('permission:user-read', ['only' => ['index']]);
        $this->middleware('permission:user-create', ['only' => ['create', 'store']]);
        $this->middleware('permission:user-update', ['only' => ['edit', 'update', 'editProfile']]);
        $this->middleware('permission:user-delete', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $pageTitle = 'User Lists';
        $roles = Role::get();
        return view('admin.users.index', compact('pageTitle', 'roles'));
    }

    public function list()
    {
        $userId = $this->userService->getLoggedInUser()->id;
        if (request()->ajax()) {
            $data = $this->userService->getAllUserExcludingLoggedInUser($userId);
            return (new DataTables)->of($data)
                ->addColumn('fullname', function ($row) {
                    $fullname = '';
                    $roles = $row->getRoleNames()->first();
                    if ($roles == 'Faculty') {
                        $fullname = $row->faculty->fullname;
                    } else if ($roles == 'Student') {
                        $fullname = $row->student->fullname;
                    }
                    return  $fullname;
                })
                ->addColumn('roles', function ($row) {
                    return $row->getRoleNames()->first();
                })
                ->make(true);
        }
    }

    public function show()
    {
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'username'      => 'required|unique:users,username',
            'password'      => 'required|min:8',
            'email'         => 'required|unique:users,email',
            'roles'         => 'required',
        ]);

        $data = [
            'username'      => $request->username,
            'password'      => Hash::make($request->password),
            'email'         => $request->email,
        ];

        $user = $this->userService->createUser($data);

        $user->assignRole($request->input('roles'));

        return response()->json(['success' => true]);
    }

    public function edit($id)
    {
        $user = $this->userService->getUserById($id);
        $userRoles = $user->getRoleNames();
        $user->roles = $userRoles;

        return $user;
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'username'      => 'required|unique:users,username,' . $id,
            'email'         => 'required|unique:users,email,' . $id,
            'roles'         => 'required'
        ]);

        $data = [
            'username'      => $request->username,
            'email'         => $request->email,
        ];

        $user = $this->userService->updateUser($id, $data);

        DB::table('model_has_roles')->where('model_id', $id)->delete();

        $user->assignRole($request->input('roles'));
        return response()->json(['success' => true]);
    }

    public function destroy($id)
    {
        try {
            DB::table("users")->where('id', $id)->delete();
            return response()->json(['success' => true]);
        } catch (QueryException $e) {
            return response()->json(['success' => false]);
        }
    }

    //Update User Profile
    public function editProfile()
    {
        $pageTitle = 'Edit Profile';
        $userProfile = $this->userService->getUserById(Auth::user()->id);
        $userRole = $userProfile->roles->first();
        $layout = '';

        if (Auth::user()->hasRole('Student')) {
            $layout = 'app-student';
        } elseif (Auth::user()->hasRole('Faculty')) {
            $layout = 'app-faculty';
        } elseif (Auth::user()->hasRole('Admin')) {
            $layout = 'app';
        }

        return view('admin.users.edit-profile', compact('userProfile', 'userRole', 'pageTitle', 'layout'));
    }

    public function updateProfile(Request $request, $id)
    {
        $this->validate($request, [
            'username'      => 'required|unique:users,username,' . $id,
            'email'         => 'required|unique:users,email,' . $id,
        ]);

        $input = $request->all();
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }

        $user = User::find($id);
        $user->update($input);

        return redirect()->route('edit.profile')
            ->with('success', 'Profile successfully updated.');
    }
}
