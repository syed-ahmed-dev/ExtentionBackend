<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Http\Resources\LoginResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, Response::HTTP_BAD_REQUEST, $validator->errors()->first(), 'Validation Error');
        }
        $user = User::create([
            'name' => $request['name'],
            'phone_number' => $request['phone_number'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);
        $user->assignRole('user');
        return $this->sendResponse(true, Response::HTTP_OK, 'User created successfully', $user);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendResponse(false, Response::HTTP_BAD_REQUEST, $validator->errors()->first(), 'Validation Error');
        }
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return $this->sendResponse(false, Response::HTTP_UNAUTHORIZED, 'Invalid credentials');
        }

        $token = $user->createToken('api-token')->plainTextToken;
        $user->token = $token;

        return $this->sendResponse(true, Response::HTTP_OK, 'Login successfully', new LoginResource($user));

    }

    public function profile(Request $request)
    {
        $userId = auth()->user()->id;
        $user = User::where('id', $userId)->first();

        if (!$user) {
            return $this->sendResponse(false, Response::HTTP_NOT_FOUND, 'Not Found', null);
        }

        return $this->sendResponse(true, Response::HTTP_OK, 'User Profile', new UserResource($user));

    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return $this->sendResponse(true, Response::HTTP_OK, 'Logged out successfully');

    }

    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'phone_number' => 'sometimes|required|string|max:255',
        ]);

        $userId = auth()->user()->id;
        $user = User::where('id', $userId)->first();

        if ($validator->fails()) {
            return $this->sendResponse(false, Response::HTTP_BAD_REQUEST, $validator->errors()->first(), 'Validation Error');
        }

        $user->update($validator);
        return $this->sendResponse(true, Response::HTTP_OK, 'Profile update successfully');

    }
}
