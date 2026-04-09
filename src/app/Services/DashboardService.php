<?php
namespace App\Services;

use App\Repositories\Contracts\SensorLogRepositoryInterface;
use App\Repositories\Contracts\SystemEventRepositoryInterface; // we'll create this later
use App\Repositories\Contracts\PowerReadingRepositoryInterface;

class DashboardService
{
    protected $sensorLogRepo;
    protected $eventRepo;
    protected $powerRepo;

    public function __construct(
        SensorLogRepositoryInterface $sensorLogRepo,
        // SystemEventRepositoryInterface $eventRepo,
        // PowerReadingRepositoryInterface $powerRepo
    ) {
        $this->sensorLogRepo = $sensorLogRepo;
        // For now we'll query events directly using the model, but you can create the repo similarly.
    }

    public function getDashboardData()
    {
        $latestSensor = $this->sensorLogRepo->getLatest();
        $latestPower = $latestSensor ? $latestSensor->powerReading : null;
        $recentEvents = \App\Models\SystemEvent::latest('timestamp')->limit(10)->get();
        $chartData = $this->sensorLogRepo->getChartData('hour', 24);

        return compact('latestSensor', 'latestPower', 'recentEvents', 'chartData');
    }
}