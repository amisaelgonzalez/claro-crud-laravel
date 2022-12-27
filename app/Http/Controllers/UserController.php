<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, UserService $userService): View|JsonResponse
    {
        if($request->ajax()) {
            $usersPaginated = $userService->getPaginatedWithFilters([
                'search'    => $request->query('search', null),
                'sort'      => $request->query('sort', 'id'),
                'order'     => $request->query('order', 'asc'),
                'offset'    => $request->query('offset', 0),
                'limit'     => $request->query('limit', 0),
            ]);

            return response()->json($usersPaginated);
        }

        return view('users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request, UserService $userService): RedirectResponse
    {
        $userService->store($request->validated());

        return redirect()->route('users.index')->with([
            'msg' => 'user created',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserService $userService, int $userId): View
    {
        $user = $userService->getById($userId);

        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, UserService $userService, int $userId): RedirectResponse
    {
        $userService->update($userId, $request->validated());

        return redirect()->route('users.index')->with([
            'msg' => 'user updated',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserService $userService, int $userId): JsonResponse
    {
        $userService->deleteById($userId);

        return response()->json([
            'msg' => 'user deleted',
        ]);
    }
}
