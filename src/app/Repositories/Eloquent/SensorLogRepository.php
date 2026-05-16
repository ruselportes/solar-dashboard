<?php

namespace App\Repositories\Eloquent;

use App\Models\SensorLog;
use App\Repositories\Contracts\SensorLogRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class SensorLogRepository implements SensorLogRepositoryInterface
{
    public function __construct(protected SensorLog $model) {}

    public function getLatest(): ?SensorLog
    {
        return $this->model->with('powerReading')->orderBy('timestamp', 'desc')->first();
    }

    public function getPaginated(array $filters = []): LengthAwarePaginator
    {
        $query = $this->model->with('powerReading')->orderBy('timestamp', 'desc');

        if (!empty($filters['date_from'])) {
            $query->where('timestamp', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->where('timestamp', '<=', $filters['date_to'] . ' 23:59:59');
        }

        if (isset($filters['shadow']) && $filters['shadow'] !== '') {
            $query->where('shadow_detected', (bool) $filters['shadow']);
        }

        if (!empty($filters['mode'])) {
            $query->where('mode', $filters['mode']);
        }

        $perPage = $filters['per_page'] ?? 50;

        return $query->paginate($perPage)->withQueryString();
    }

    public function getChartData(string $range = '24hours', int $points = 60): \Illuminate\Support\Collection
    {
        [$bucketSql, $fromTimestamp] = $this->resolveBucketAndFrom($range);

        $countInRange = $this->model
            ->where('timestamp', '>=', $fromTimestamp)
            ->count();

        $query = $this->model->leftJoin('power_readings', 'sensor_logs.log_id', '=', 'power_readings.log_id')
            ->select(
                DB::raw("TO_CHAR(date_trunc('{$bucketSql}', sensor_logs.timestamp), 'YYYY-MM-DD HH24:MI:SS') as time_bucket"),
                DB::raw('ROUND(AVG((ldr1+ldr2+ldr3+ldr4+ldr5+ldr6)/6.0)::numeric, 2) as avg_light'),
                DB::raw('ROUND(AVG(CAST(shadow_detected AS integer))::numeric, 4) as shadow_ratio'),
                DB::raw('MAX(servo_horizontal_angle) as servo_h'),
                DB::raw('MAX(servo_vertical_angle) as servo_v'),
                DB::raw('ROUND(AVG(battery_voltage)::numeric, 2) as avg_batt'),
                DB::raw('ROUND(AVG(panel_voltage)::numeric, 2) as avg_panel')
            );

        if ($countInRange > 0) {
            $query->where('sensor_logs.timestamp', '>=', $fromTimestamp);
        }

        return $query
            ->groupBy(DB::raw("date_trunc('{$bucketSql}', sensor_logs.timestamp)"))
            ->orderBy(DB::raw("date_trunc('{$bucketSql}', sensor_logs.timestamp)"), 'asc')
            ->limit($points)
            ->get();
    }

    public function getSummaryStats(): array
    {
        $latest = $this->getLatest();

        // Use the date of the latest record (not today()) so demo data works
        $refDate = $latest ? \Carbon\Carbon::parse($latest->timestamp)->toDateString() : today()->toDateString();

        $day = $this->model
            ->whereDate('timestamp', $refDate)
            ->selectRaw('
                AVG((ldr1+ldr2+ldr3+ldr4+ldr5+ldr6)/6.0) as avg_light,
                SUM(CAST(shadow_detected AS integer)) as shadow_count,
                COUNT(*) as total_logs
            ')
            ->first();

        return [
            'latest'       => $latest,
            'avg_light'    => round($day->avg_light ?? 0, 1),
            'shadow_count' => $day->shadow_count ?? 0,
            'total_logs'   => $day->total_logs ?? 0,
        ];
    }

    public function getDistinctModes(): array
    {
        return $this->model
            ->select('mode')
            ->distinct()
            ->whereNotNull('mode')
            ->pluck('mode')
            ->toArray();
    }

    private function resolveBucketAndFrom(string $range): array
    {
        $map = [
            '15min'   => ['minute', now()->subMinutes(15)],
            '30min'   => ['minute', now()->subMinutes(30)],
            '1hour'   => ['minute', now()->subHour()],
            '6hours'  => ['minute', now()->subHours(6)],
            '24hours' => ['hour',   now()->subHours(24)],
            '7days'   => ['hour',   now()->subDays(7)],
        ];

        return $map[$range] ?? $map['24hours'];
    }
}