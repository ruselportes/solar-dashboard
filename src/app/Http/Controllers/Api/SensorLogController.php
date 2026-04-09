<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Contracts\SensorLogRepositoryInterface;

class SensorLogController extends Controller
{
    protected $sensorLogRepo;

    public function __construct(SensorLogRepositoryInterface $sensorLogRepo)
    {
        $this->sensorLogRepo = $sensorLogRepo;
    }

    public function latest()
    {
        return response()->json($this->sensorLogRepo->getLatest());
    }

    public function index()
    {
        return response()->json($this->sensorLogRepo->getPaginated(20));
    }
}