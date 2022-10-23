<?php

namespace App\Http\Controllers;

use App\Enum\UserRoleEnum;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->ajax()) {
            $query = User::overallSearch($request->query('search'))->roleUser();
            $total = $query->count();
            $users = $query->with('city')
                            ->customOrderBy($request->query('sort', 'id'), $request->query('order', 'asc'))
                            ->offset($request->query('offset', 0))->limit($request->query('limit', 10))
                            ->get();

            return response()->json([
                'rows'              => $users,
                'total'             => $total,
                'totalNotFiltered'  => User::roleUser()->count(),
            ]);
        }

        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreUserRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreUserRequest $request)
    {
        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'password'          => Hash::make($request->password),
            'phone'             => $request->phone,
            'identification'    => $request->identification,
            'birthday'          => $request->birthday,
            'role'              => UserRoleEnum::USER,
            'city_id'           => $request->city_id,
        ]);

        return redirect()->route('users.index')->with([
            'msg' => 'user created',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateUserRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $user->name     = $request->name;
        $user->phone    = $request->phone;
        $user->birthday = $request->birthday;
        $user->city_id  = $request->city_id;

        $user->update();

        return redirect()->route('users.index')->with([
            'msg' => 'user updated',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response()->json([
            'msg' => 'user deleted',
        ]);
    }
}
