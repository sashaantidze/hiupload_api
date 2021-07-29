<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class RegisterController extends Controller
{

    public function __construct()
    {
        $this->middleware(['guest']);
    }


    public function __invoke(Request $request)
    {

        // $this->validate($request, [
        //     'name' => ['required', 'max:255'],
        //     'email' => ['required', 'email', 'unique:users', 'max:255'],
        //     'password' => ['required'],
        // ]);

        $validation = Validator::make($request->all() ,[
            'name' => ['required', 'max:255'],
            'email' => ['required', 'email', 'unique:users', 'max:255'],
            'password' => ['required', 'confirmed'],
        ]);

        if($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors()
            ], 422);
        }


        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

    }
}
