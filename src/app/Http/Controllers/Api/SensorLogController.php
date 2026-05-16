<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\SensorLogRepositoryInterface;
use App\Models\SensorLog;

class SensorLogController extends Controller
{
    protected $sensorLogRepo;

    public function __construct(SensorLogRepositoryInterface $sensorLogRepo)
    {
        $this->sensorLogRepo = $sensorLogRepo;
    }


    public function latest()
    {
        $latest = $this->sensorLogRepo->getLatest();
        if ($latest) {
            $latest->load('powerReading');
        }
        return response()->json($latest);
    }

    public function index()
    {
        return response()->json($this->sensorLogRepo->getPaginated(['per_page' => 20]));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (!isset($data['timestamp'])) {
            $data['timestamp'] = now();
        }
        
        $log = \App\Models\SensorLog::create($data);
        return response()->json(['log_id' => $log->log_id], 201);
    }
}
