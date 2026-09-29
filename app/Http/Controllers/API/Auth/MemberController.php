<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\API\BaseController;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Models\User;


class MemberController extends BaseController
{
    //
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        $user->update($data);

        return $this->sendResponse(new UserResource($user), 'User updated successfully!');
    }
}
