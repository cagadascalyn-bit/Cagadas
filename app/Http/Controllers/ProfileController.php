<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name'            => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $user->id,
            'gender'          => 'nullable|in:Male,Female,Other',
            'address'         => 'nullable|string|max:500',
            'password'        => 'nullable|min:6|confirmed',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('name', 'email', 'gender', 'address');

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $data['profile_picture'] = $path;
        }

        $user->update($data);
        return redirect()->route('profile.show')->with('toast_success', 'Profile updated successfully!');
    }
}
