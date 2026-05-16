<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ManualCommand;

class ManualCommandController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        if (!isset($data['timestamp'])) {
            $data['timestamp'] = now();
        }
        $command = ManualCommand::create($data);
        return response()->json($command, 201);
    }
}
