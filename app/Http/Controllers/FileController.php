<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileSignedRequest;
use App\Http\Resources\FileResource;
use App\Models\File;
use Aws\S3\PostObjectV4;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
    }

    public function index(Request $request)
    {
        return FileResource::collection($request->user()->files);
    }


    public function destroy(Request $request, File $file)
    {
        $this->authorize('destroy', $file);
        $file->delete();
    }


    public function store(Request $request)
    {
        $file = $request->user()->files()->firstOrCreate($request->only('path'), $request->only('name', 'size'));

        return new FileResource($file);
    }


    public function signed(FileSignedRequest $request)
    {

        $filename = md5($request->name . microtime()) . '.' . $request->extension;

        $object = new PostObjectV4(
            Storage::disk('s3')->getAdapter()->getClient(),
            config('filesystems.disks.s3.bucket'), // Dots (.) fucking up whole thing! NEVER use dots in s3 bucket names!!!
            ['key' => 'files/' . $filename],
            [
                ['bucket' => config('filesystems.disks.s3.bucket')],
                ['starts-with', '$key', 'files/']
            ]
        );


        return response()->json([
            'attributes' => $object->getFormAttributes(),
            'additionalData' => $object->getFormInputs(),
        ]);

    }
}
