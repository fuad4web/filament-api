<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Traits\HttpResponse;

class UserController extends Controller
{
    use HttpResponse;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->success([
            UserResource::collection(
                User::orderBy('created_at', 'desc')->get()
            ),
        ]);
        // return UserResource::collection(
        //     User::orderBy('created_at', 'desc')->get()
        //     // User::query()->orderBy('created_at', 'desc')->get()
        // );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        $data['password'] = bcrypt($data['password']);
        $user = User::create($data);
        return $this->success([
            new UserResource($user),
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->success([
            new UserResource($user),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();
        if(isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }
        $user->update($data);
        
        return $this->success([
            new UserResource($user),
        ], 204);
        
        // return new UserResource($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        return $this->success([
            $user->delete()
        ], 204);
    }
}
