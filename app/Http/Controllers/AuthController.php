<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // //if user not at auth as admin
        // if(Auth::user()->role != 'admin'){
        //     return response()->json([
        //         'status' => 'error',
        //         'message' => 'Unauthorized Access! For admin only.'
        //     ], 401);
        // }
        
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string', // Validation rule for role
            'gender' => 'required|string', // Validation rule for gender
            'telephone' => 'required|string', // Validation rule for telephone
        ]);

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'gender' => $validatedData['gender'],
            'telephone' => $validatedData['telephone'],
        ]);

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = $request->user();

        //check admin
        if($user->role != 'admin'){
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized Access! For admin only.'
            ], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'token' => $token,
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id); // Find the user by ID

        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
            'role' => 'sometimes|required|string', // Validation rule for role
            'gender' => 'sometimes|required|string', // Validation rule for gender
            'telephone' => 'sometimes|required|string', // Validation rule for telephone
        ]);

        if (isset($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        }

        $user->update($validatedData);

        return response()->json([
            'status' => 'success',
            'user' => $user,
        ]);
    }

    public function getAllUsers()
    {
        $users = User::all();

        return response()->json([
            'status' => 'success',
            'users' => $users,
        ]);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id); // Find the user by ID

        $user->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'User deleted successfully',
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully',
        ]);
    }
}
