<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SystemConfig;

class SystemConfigController extends Controller
{
    public function latest()
    {
        $config = SystemConfig::orderBy('config_id', 'desc')->first();
        if (!$config) {
            return response()->json(['error' => 'No system configuration found'], 404);
        }
        return response()->json($config);
    }

    public function store(Request $request)
    {
        $config = SystemConfig::create($request->all());
        return response()->json($config, 201);
    }
}
