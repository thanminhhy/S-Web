<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Requests\Admin\User\UpdateUserRequest;
use App\Models\User;
use App\Models\Country;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $user = Auth::user();
        // // dd(session('_old_input'));
        // dd(old('name'));

        return view('admin.user.profile', compact('user'));
    }

    public function showListUser()
    {
        $users = User::paginate(6);
        $countries = Country::get();

        return view('admin.user.listUser', compact('users', 'countries'));
    }

    public function updateUser(UpdateUserRequest $request, string $userId)
    {
        $user = User::findOrFail($userId);
        $data = $request->validated();

        $user->update($data);

        return response()->json([
            'message' => 'Update user profile successfully!',
            'user' => $user->load('country')
        ]);
    }

    public function deleteUser(string $userId)
    {
        User::destroy($userId);

        return response()->json([
            'message' => "Delete user successfully!"
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, string $userId)
    {
        $user = User::findOrFail($userId);
        $data = $request->except('email');
        $file = $request->file('avatar');
        $path = public_path('upload/user/avatar/' . $user->avatar);


        if ($data['password']) {
            $data['password'] = bcrypt($data['password']);
        } else {
            $data['password'] = $user->password;
        }

        if (!empty($file)) {
            $data['avatar'] = $file->getClientOriginalName();
        }
        // dd($data);
        if ($user->update($data)) {
            if (!empty($file)) {
                if (file_exists($path)) {
                    unlink($path);
                }
                $file->move('upload/user/avatar', $file->getClientOriginalName());
            }
            return redirect()->back()->with('success', __('Update profile success.'));
        } else {
            return redirect()->back()->withErrors('Update profile error.');
        }
    }
}
