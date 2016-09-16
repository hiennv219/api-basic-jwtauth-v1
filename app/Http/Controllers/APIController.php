<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\User;
use Hash;
use JWTAuth;

class APIController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    //JWTAUTH IM HERE

    public function register(Request $request){

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        User::create($input);
        return response()->json(['result' => true]);
    }

    public function login(Request $request){

        $input = $request->all();

        if(!$token = JWTAuth::attempt($input)){

            return response()->json(['result' => 'worng email or password']);
        
        }

        return response()->json(['result' => $token]);
    }


    public function get_user_details(Request $request){

        $input = $request->all();

        $user = JWTAuth::toUser($input['token']);

        return response()->json(['result' => $user]);

    }


}
