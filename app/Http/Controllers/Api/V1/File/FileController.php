<?php

namespace App\Http\Controllers\Api\V1\File;

use App\Core\File\File;
use App\Http\Requests\File\CreateFileRequest;
use App\Http\Requests\File\DeleteFileRequest;
use App\Http\Requests\File\GetFileRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(GetFileRequest $request)
    {
        $files = File::query()
            ->when($request->search, function ($query, $search) {
                $query->search($search);
            })
            ->when($request->with_trashed, function ($query, $with_trashed) {
                $query->withTrashed();
            })
            ->latest()
            ->get();

        return compact('files');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateFileRequest $request)
    {
        $file = new File();
        $file->fillFileData($request->base64, $request->name, $this);
        $file->fileable()->associate($this);
        $file->save();

        return compact('file');
    }

    /**
     * Display the specified resource.
     */
    public function show(GetFileRequest $request, File  $file)
    {
        return compact('file');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DeleteFileRequest  $file)
    {
        $file->delete();
    }
}
