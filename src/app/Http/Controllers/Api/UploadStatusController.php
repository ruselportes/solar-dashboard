<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UploadStatus;

class UploadStatusController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        if (!isset($data['timestamp'])) {
            $data['timestamp'] = now();
        }
        $status = UploadStatus::create($data);
        return response()->json($status, 201);
    }
}
