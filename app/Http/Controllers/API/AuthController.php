<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Exception;

class AuthController extends Controller
{

    /**
     * Register for new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $errors = [];

        $name       = $request->name;
        $email      = $request->email;
        $password   = $request->password;

        if(!isValidName($name)) {
            $errors[]   = "Invalid name.";
        }
        if(!isValidEmail($email)) {
            $errors[]   = "Invalid email.";
        } else {
            if(User::where('email', $email)->count() > 0) {
                $errors[]   = "Email already exist for another user.";
            }
        }

        if(empty($password)) {
            $errors[]   = "Password is required";
        }

        if(empty($errors)) {
            try {
                $User   = User::create([
                    'name'      => $name,
                    'email'     => $email,
                    'password'      => Hash::make($password),
                ]);

                $token = $User->createToken('Subscription app token');

                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Registration successfull.',
                    'token'     => $token->plainTextToken
                ], 200);
            } catch (Exception $e) {

                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Registration failed. '. $e->getMessage()
                ], 401);
            }
        } else {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Registration failed. '. implode('<br />', $errors)
            ], 401);
        }
    }

    /**
     * Create new token for registered user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create_token(Request $request)
    {
        $errors = [];

        $email      = $request->email;
        $password   = $request->password;

        if(Auth::attempt(['email' => $email, 'password' => $password])) {
            $User   = Auth::user();

            $token = $User->createToken('Subscription app token');

            return response()->json([
                'status'    => 'success',
                'token'     => $token->plainTextToken
            ], 200);

        } else {
            return response()->json([
                'status'    => 'error',
                'message'   => 'Invalid credentials'
            ], 401);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
