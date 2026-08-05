<?php

namespace App\Http\Controllers\Frontend\Account;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Country;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\User;


class ProfileController extends Controller
{

    public function showProfile()
    {
        $user = Auth::user();
        $countries = Country::all();
        // $user->load('country');

        return view('frontend.account.myProfile', compact('user', 'countries'));
    }

    public function updateProfile(UpdateProfileRequest $request, User $user)
    {
        $file = null;
        $fileName = null;
        $hasNewFile = $request->hasFile('avatar');
        $data = $request->validated();
        // dd($data);
        $oldImage = public_path($user->avatar);

        // if ($data['password']) {
        //     $data['password'] = bcrypt($data['password']);
        // } else {
        //     $data['password'] = $user->password;
        // }
        if ($hasNewFile) {
            $file = $request->file('avatar');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $data['avatar'] = 'upload/user/avatar/' . $fileName;
        }

        if ($user->update($data)) {
            if ($hasNewFile) {
                $file->move(public_path('upload/user/avatar'), $fileName);

                if (file_exists($oldImage)) {
                    unlink($oldImage);
                }
            }
            return redirect()->route('frontend.myAccount')->with('success', 'Update profile successfully!');
        } else {
            return redirect()->back()->with('error', 'Update profile unsuccessfully!');
        }
    }

    public function showMyProduct()
    {
        return view('frontend.account.myProduct');
    }
}
