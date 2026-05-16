<?php

namespace App\Http\Controllers;

use App\Repositories\Contracts\SensorLogRepositoryInterface;
use App\Models\UploadStatus;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(
        protected SensorLogRepositoryInterface $sensorLogRepo
    ) {}

    /**
     * Main dashboard view.
     */
    public function index(Request $request)
    {
        $stats   = $this->sensorLogRepo->getSummaryStats();
        $latest  = $stats['latest'];
        $modes   = $this->sensorLogRepo->getDistinctModes();
        $uploads = UploadStatus::latest('timestamp')->take(5)->get();

        return view('dashboard', compact('stats', 'latest', 'modes', 'uploads'));
    }

    /**
     * AJAX endpoint: returns chart data for a given time range.
     * GET /dashboard/chart-data?range=1hour
     */
    public function chartData(Request $request)
    {
        $range  = $request->input('range', '24hours');
        $points = (int) $request->input('points', 60);

        $allowedRanges = ['15min', '30min', '1hour', '6hours', '24hours', '7days'];
        if (!in_array($range, $allowedRanges)) {
            $range = '24hours';
        }

        $data = $this->sensorLogRepo->getChartData($range, $points);

        return response()->json([
            'labels'       => $data->pluck('time_bucket'),
            'avg_light'    => $data->pluck('avg_light'),
            'shadow_ratio' => $data->pluck('shadow_ratio'),
            'servo_h'      => $data->pluck('servo_h'),
            'servo_v'      => $data->pluck('servo_v'),
            'avg_batt'     => $data->pluck('avg_batt'),
            'avg_panel'    => $data->pluck('avg_panel'),
        ]);
    }

    /**
     * Sensor logs history page (infinite scroll).
     * GET  /dashboard/logs          → Blade view (initial page load)
     * GET  /dashboard/logs?page=2   → JSON paginated data (AJAX / infinite scroll)
     */
    public function logs(Request $request)
    {
        $filters = $request->only(['date_from', 'date_to', 'shadow', 'mode', 'per_page']);
        $modes   = $this->sensorLogRepo->getDistinctModes();

        // Return JSON for AJAX / infinite-scroll requests
        if ($request->ajax() || $request->wantsJson()) {
            $logs = $this->sensorLogRepo->getPaginated($filters);
            return response()->json($logs);
        }

        return view('logs', compact('filters', 'modes'));
    }

    /**
     * Latest sensor reading for real-time polling.
     * GET /api/sensor-logs/latest
     */
    public function latestApi()
    {
        $latest = $this->sensorLogRepo->getLatest();

        if (!$latest) {
            return response()->json(['error' => 'No data yet'], 404);
        }

        return response()->json($latest);
    }
}