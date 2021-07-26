<?php

namespace App\Http\Controllers;

use App\Models\File;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class FileLinkController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function store(Request $request, File $file)
    {
        $this->authorize('create-link', $file);

        $link = $file->links()->firstOrCreate([], [
            'token' => hash_hmac('sha256', Str::random(40), $file->uuid)
        ]);

        return [
            'data' => [
                'url' => config('app.client_url').'/download/' . $file->uuid . '?token=' . $link->token
            ]
        ];
    }
}