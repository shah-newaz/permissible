<?php

namespace Shahnewaz\Permissible\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Shahnewaz\Permissible\Http\Requests\UserRequest;
use Shahnewaz\Permissible\Role;

class UsersController extends Controller
{

    public function __construct()
    {
        $this->ifConfigured();
    }

    public function ifConfigured()
    {
        $configured = config('permissible.first_last_name_migration', false) === true && config('permissible.enable_routes', false) === true;
        if (!$configured) {
            app()->abort(403);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */

    public function index(Request $request)
    {
        $users = User::withTrashed();

        if ($request->has('name')) {
            $users = $users->whereRaw("MATCH (first_name, last_name) AGAINST (? IN NATURAL LANGUAGE MODE)", [$request->get('name')]);
        }

        if ($request->has('email')) {
            $users = $users->where('email', 'LIKE', '%' . $request->get('email'));
        }


        $users = $users->paginate(20);
        return view('permissible::users.index')->withUsers($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param UserRequest $request
     * @return Response
     */
    public function post(UserRequest $request)
    {
        $user = User::find($request->get('id')) ?: new User;
        $user->first_name = $request->get('first_name');
        $user->last_name = $request->get('last_name');
        $user->email = $request->get('email');
        if ($request->get('password')) {
            $user->password = $request->get('password');
        }
        $user->save();
        if ($request->get('role')) {
            $user->roles()->sync($request->get('role'));
        }
        return redirect()->route('permissible.user.index')->withSuccess(trans('permissible::permissible.user_saved'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return
     */
    public function form(User $user = null)
    {
        $user = $user ?: new User;
        $roles = Role::all();
        return view('permissible::users.form')->withUser($user)->withRoles($roles);
    }

    public function restore($userId)
    {
        $user = User::withTrashed()->find($userId);
        $user->restore();
        return redirect()->back();
    }

    public function forceDelete($userId)
    {
        DB::transaction(function () use ($userId) {
            $user = User::withTrashed()->find($userId);
            // Delete Roles
            DB::table('role_user')->where('user_id', $userId)->delete();
            $user->forceDelete();
        });
        return redirect()->back()->withSuccess(
            trans('permissible::permissible.user_permanently_deleted')
        );
    }

    public function delete(User $user)
    {
        $user->delete();
        return redirect()->back()->withSuccess(trans('permissible::permissible.user_deleted'));
    }

}
