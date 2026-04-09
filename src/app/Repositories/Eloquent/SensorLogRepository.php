<?php
namespace App\Repositories\Eloquent;

use App\Models\SensorLog;
use App\Repositories\Contracts\SensorLogRepositoryInterface;
use Illuminate\Support\Facades\DB;

class SensorLogRepository implements SensorLogRepositoryInterface
{
    protected $model;

    public function __construct(SensorLog $model)
    {
        $this->model = $model;
    }

    public function getLatest()
    {
        return $this->model->with('powerReading')->latest('timestamp')->first();
    }

    public function getPaginated(int $perPage = 20)
    {
        return $this->model->with('powerReading')->orderByDesc('timestamp')->paginate($perPage);
    }

    public function getChartData(string $groupBy = 'hour', int $limit = 24)
{
    return $this->model
        ->select(
            DB::raw("DATE_FORMAT(sensor_logs.timestamp, '%Y-%m-%d %H:00:00') as time_bucket"),
            DB::raw('AVG((ldr1+ldr2+ldr3+ldr4+ldr5+ldr6)/6) as avg_light'),
            DB::raw('AVG(shadow_detected) as shadow_ratio')
        )
        ->join('power_readings', 'sensor_logs.log_id', '=', 'power_readings.log_id')
        ->groupBy('time_bucket')
        ->orderBy('time_bucket', 'desc')
        ->limit($limit)
        ->get();
}
   /* public function getChartData(string $groupBy = 'hour', int $limit = 24)
    {
        // Example: group by hour and get average light and battery voltage
        return $this->model
            ->select(
                DB::raw("DATE_FORMAT(timestamp, '%Y-%m-%d %H:00:00') as time_bucket"),
                DB::raw('AVG((ldr1+ldr2+ldr3+ldr4+ldr5+ldr6)/6) as avg_light'),
                DB::raw('AVG(shadow_detected) as shadow_ratio')
            )
            ->join('power_readings', 'sensor_logs.log_id', '=', 'power_readings.log_id')
            ->groupBy('time_bucket')
            ->orderBy('time_bucket', 'desc')
            ->limit($limit)
            ->get();
    }*/
}