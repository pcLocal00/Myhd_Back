<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'loginUser' => 'required|string',
            'passwordUser' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Validation Error', 'messages' => $validator->messages()], 422);
        }

        // Find the user by loginUser
        $user = User::where('loginUser', $request->loginUser)->first();

        if (!$user) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Check if the password is SHA-1 hashed
        if (sha1($request->passwordUser) !== $user->passwordUser) {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }

        // Generate an API token for the user
        $token = $user->createToken('api_token')->plainTextToken;

        return response()->json(['token' => $token, 'user' => $user], 200);
    }
}

