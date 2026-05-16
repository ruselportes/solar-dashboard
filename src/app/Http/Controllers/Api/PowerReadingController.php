<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\PowerReading;

class PowerReadingController extends Controller
{
    public function latest()
    {
        $reading = PowerReading::orderBy('timestamp', 'desc')->first();
        return response()->json($reading);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (!isset($data['timestamp'])) {
            $data['timestamp'] = now();
        }
        $reading = PowerReading::create($data);
        return response()->json($reading, 201);
    }
}
