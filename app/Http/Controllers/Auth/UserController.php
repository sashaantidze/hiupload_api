<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }


    public function __invoke(Request $request)
    {
        return new UserResource($request->user());
    }
}
