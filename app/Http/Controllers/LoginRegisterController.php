<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Auth;
use Laravel\Passport\RefreshToken;
use Laravel\Passport\Token;

class LoginRegisterController extends Controller
{
    public function Login(Request $request)
    {
        $validation = Validator::make($request->all(),[ 
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        if($validation->fails())
        {
            return response()->json([
                'status' => 201, #this is used for only validations errors
                'errors' => $validation->errors()->all()
            ]);
        }

        // after validation check for user email exist or not 
        $emailExist = User::where('email',$request->email)->first();
        if($emailExist != NULL)
        {
            // matching the credentails
            if (Hash::check($request->password, $emailExist->password)) 
            {
                $token = $emailExist->createToken('TodoList')->accessToken;
                return response()->json([
                    'status' => 304,
                    'message' => trans('message.LoginSuccess'),
                    'token' => $token,
                    'user' => $emailExist
                ]);
            }
        }
        else
        {
            return response()->json([
                'status' => '300', # related to login and signup codes
                'message' => trans('message.LoginFailed')
            ]);
        } 
        
    }

    function Register(Request $request)
    {
        $validation = Validator::make($request->all(),[ 
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!$#%@]).*$/',
        ]);
    
        if($validation->fails())
        {
            return response()->json([
                'status' => 201, #this is used for only validations errors
                'errors' => $validation->errors()->all()
            ]);
        }

        // User creation 
        $user = new User;
        $usercreation = $user->RegisterUser($request);
        if($usercreation)
        {
            // generating the token
            $token = $user->createToken('TodoList')->accessToken;
            return response()->json([
                'status' => 301,
                'message' => trans('message.registerSuccess'),
                'token' => $token,
                'user' => $usercreation
            ]);
        }

        return respnse()->json([
            'status' => 302,
            'message' => 'oops something went wrong'
        ]);
    }

    public function Logout()
    {
        $user = Auth::user()->token();
        $user->revoke();
        return response()->json([
            'status' => 303,
            'message' => trans('message.Logout')
        ]);
    }

    public function LogoutFromAllDevices()
    {
        $user = Auth::user();
        $tokens =  $user->tokens->pluck('id');
        Token::whereIn('id', $tokens)->update(['revoked', true]);
        RefreshToken::whereIn('access_token_id', $tokens)->update(['revoked' => true]);

        return resonse()->json([
            'status' => 303,
            'message' => trans('message.LogoutAll')
        ]);
    }
}
