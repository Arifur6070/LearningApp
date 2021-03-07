<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Exception;
use Validator;


class RegistrationController extends Controller{

    public function createUser(Request $request)
    {try{
        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'role' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ]);

        $role = $request -> role;
        $isPending="";
        if($role == '3'){
            $isPending='1';
        }else{
            $isPending='0';
        }

        $user = User::create([
            'username' => trim($request->input('username')),
            'email' => strtolower($request->input('email')),
            'password' => bcrypt($request->input('password')),
            'role' => strtolower($request->input('role')),
            'first_name' => strtolower($request->input('first_name')),
            'last_name' => strtolower($request->input('last_name')),
            'phone' => strtolower($request->input('phone')),
            'address' => strtolower($request->input('address')),
            'isPending' => strtolower($request->input('isPending')),
        ]);



        if (!$user) {
            throw new Exception('Create Course failed!');
        }

        return response()->json(array(
            'status' => true,
            'status_message' => "User Create Successful!",
            'user' => $user,
        ));

    }
    catch (Exception $e) {
        return response()->json(array(
            'status' => false,
            'status_message' => $e->getMessage(),
        ));
    }


    }
}
