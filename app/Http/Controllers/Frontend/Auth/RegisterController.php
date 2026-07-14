<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegister()
    {
        return view('frontend.auth.register');
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        try {
            User::create($data);
            return redirect()->route('frontend.index')->with('success', 'User has created successfully!');
        } catch (\Exception $e) {
            throw $e;
        }
        return;
    }
}
