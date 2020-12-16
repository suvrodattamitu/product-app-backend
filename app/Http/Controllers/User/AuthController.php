<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\User;
use JWTAuth;
use Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTExceptions;

class AuthController extends Controller
{
    public function register(Request $request) {

        $validator = Validator::make($request->all(),[

            'name'      => 'required',
            'email'     => 'required|email',
            'password'  => 'required|min:5',

        ]);

        if($validator->fails()) {
            return response()->json([

                'success'   => false,
                'errors'    => $validator->messages()->toArray(),
                'message'   => 'Register is failed!'

            ],400);
        }

        $checkEmail = User::where('email',$request->email)->count();

        if($checkEmail) {

            return response()->json([

                'success'   => false,
                'errors'    => [],
                'message'   => 'This email already exists! Please try another email.'

            ],400);

        }

        $registerUser = User::create([

            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make( $request->password ),

        ]);

        if($registerUser){
           return $this->login($request);
        }

    }

    public function login(Request $request) {
        
        $validator = Validator::make($request->only('email','password'),[

            'email'     => 'required|email',
            'password'  => 'required|min:5',

        ]);

        if($validator->fails()) {
            return response()->json([

                'success'   => false,
                'errors'    => $validator->messages()->toArray(),
                'message'   => ''

            ],400);
        }

        $jwt_token = null;
        $input = $request->only('email','password');

        if( !$jwt_token = auth('users')->attempt($input) ) {
            return response()->json([

                'success'   => false,
                'errors'    => [],
                'message'   => 'Invalid email or password!'

            ],400);
        }

        return response()->json([

            'success'   => true,
            'token'     => $jwt_token,
            'message'   => ''

        ],200);


    }
}
