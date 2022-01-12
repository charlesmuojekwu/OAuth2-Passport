<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

use Illuminate\Http\Request;

class AuthController extends Controller
{

    use ApiResponser;
    
    public function home() 
    {
        return 'The passport api works';
    }
    
    public function register(Request $request)
    {
        //Validate user entry
        $validator = Validator::make($request->all(),[
            'name'=>'required',
            'email'=>'required|email|unique:users',
            'phone'=>'required|digits:11|unique:users',
            'password'=>'required|min:6',
        ]);

        if($validator->fails())
        {
            return $this->error('Invalid Inputs',Response::HTTP_UNPROCESSABLE_ENTITY,$validator->errors()->all());
        }

        //Create User
        $user= User::create([
            'name' =>$request->name,
            'email'=>$request->email,
            'phone'=>$request->phone,
            'password'=>bcrypt($request->password)
        ]);

        // Create Access Token
        $access_token = $user->createToken('token')->accessToken;

        //Response
        return $this->success(
            ['User' => $user,'Token' => $access_token],
            'Registion Completed Successful',
            Response::HTTP_CREATED
        );


    }


    public function getUser() 
    {
        return Auth::user();
        //return Auth::user()->makeVisible(['email','phone']);
    }


    
    public function login(Request $request) 
    {
        // Check login type Phone/Email 
        $field = 'email';
        if (is_numeric($request->input('login'))) {
            $field = 'phone';
        } else if (filter_var($request->input('login'), FILTER_VALIDATE_EMAIL)) {
            $field = 'email';
        }

        //  set the login type
        $request->merge([$field => $request->input('login')]);

        // Attempt Authentication
        if(!Auth::attempt($request->only($field, 'password')))
        {
            return response([
                'message' => 'Invalid Login Credentials'
            ],Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        // Get Authenticated User
        $user=Auth::user();
        $access_token = $user->createToken('token')->accessToken;

        // set https cookie
        $cookie = cookie('Token',$access_token,60*24);

        /// Return success
        return $this->success(
            ['User' => $user,'Token' => $access_token],
            'Loggin Successfully',
            Response::HTTP_OK
        )->withCookie($cookie);
    }


    public function logout(Request $request)
    {
        $user_token = $request->user()->token();

        $user_token->revoke();

        return $this->success('','Logout Successful',Response::HTTP_OK);

    }
}
