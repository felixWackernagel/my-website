<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * name, email, password, password_confirmation
     */
    public function register( Request $request ) {
        $validated = $this->validate( $request, [
            "name" => "required|string",
            "email" => "required|email|unique:users,email",
            "password" => "required|string|confirmed"
        ] );

        $user = User::create([
            "name" => $validated[ "name" ],
            "email" => $validated[ "email" ],
            "password" => bcrypt( $validated[ "password" ] )
        ]);

        $token = $user->createToken( 'MyApp' )->plainTextToken;

        $response = [
            'success' => true,
            'data' => [
                'user' => $user,
                'token' => $token
            ],
            'message' => 'User registration successfully'
        ];
        return response()->json( $response, 201 );
    }

    public function login( Request $request ) {
        $validated = $request->validate( [
            "email" => "required|email",
            "password" => "required"
        ] );
        
        if( Auth::attempt( $request->only( 'email', 'password' ) ) ) {
            $user = auth()->user();
            $token = $user->createToken( 'MyApp' )->plainTextToken;

            $response = [
                "success" => true,
                "data" => [
                    "user" => $user,
                    "token" => $token
                ],
                "message" => "User login successfully"
            ];
            return response()->json( $response, 200 );
        }

        throw ValidationException::withMessages([
            'message' => 'E-Mail/Password combination is wrong!'
        ] );
    }

    public function logout( Request $request ) {
        $request->user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'message' => 'User logout successfully'
        ], 200 );
    }
}
