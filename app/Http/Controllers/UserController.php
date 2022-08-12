<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class UserController extends Controller
{
    public function getUsers() {
        return User::all();
    }

    public function getUser($id) {
        return User::find($id);
    }

    public function login(Request $request) {
        Log::info($request->input());
        $credentials = $this->validateUserCredentialsForLogin($request);

        $user = User::where('email', $request->email)->first();
 
        if ( $user && Hash::check($request->password, $user->password) ) {
            $success = true;
            $message = "You have been succesfully logged in.";
            $token = $user->createToken('web-token')->plainTextToken;
        } else {
            $success = false;
            $message = "Login failed - Please check your email and password again.";
            $token = "";
        }

        $response = [
            'success' => $success,
            'message' => $message,
            'token' => $token,
            'userData' => $user
        ];

        return $response;
    }

    function validateUserCredentialsForLogin($request) {
        $rules = [
            'email' => 'email|required',
            'password' => 'required|min:6'
        ];

        $errorMessages = [
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password needs to contain atleast 6 characters.'
        ];

        return $request->validate($rules, $errorMessages);
    }

    public function register(Request $request) {
        $credentials = $this->validateUserCredentialsForRegister($request);
        try {
            $user = new User();
            $user->email = $credentials['email'];
            $user->name = $credentials['username'];
            $user->password = Hash::make($credentials['password']);
            $user->save();

            $success = true;
            $message = "You have been succesfully registered.";
            $token = $user->createToken('web-token')->plainTextToken;
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
            $token = "";
            $user = null;
        }

        $response = [
            'success' => $success,
            'message' => $message,
            'token' => $token,
            'userData' => $user
        ];

        return $response;
    }

    function validateUserCredentialsForRegister($request) {
        $rules = [
            'email' => 'email|required|unique:users',
            'username' => 'required|unique:users,name|min:3',
            'password' => 'required|confirmed|min:6'
        ];

        $errorMessages = [
            'email.required' => 'Email is required.',
            'email.unique' => 'Email is already in use.',
            'email.email' => 'Please enter a valid email.',
            'username.required' => 'username is required.',
            'username.unique' => 'username is already in use.',
            'username.min' => 'username needs to contain atleast 3 characters.',
            'password.required' => 'Password is required.',
            'password.min' => 'Password needs to contain atleast 6 characters.',
            'password.confirmed' => 'The password confirmation does not match.'
        ];

        return $request->validate($rules, $errorMessages);
    }

    public function logout(Request $request) {
        try {
            $request->user()->currentAccessToken()->delete();
            $success = true;
            $message = "You have been succesfully logged out.";
        } catch (\Illuminate\Database\QueryException $ex) {
            $success = false;
            $message = $ex->getMessage();
        }

        $response = [
            'success' => $success,
            'message' => $message
        ];

        return $response;
    }
}
