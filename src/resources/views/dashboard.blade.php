@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Live Dashboard</h1>

    <!-- Latest Sensor & Power Cards -->
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">Latest Sensor Log</div>
                <div class="card-body">
                    @if($latestSensor)
                        <p><strong>Time:</strong> {{ $latestSensor->timestamp }}</p>
                        <p><strong>Avg LDR:</strong> {{ round($latestSensor->average_ldr) }} / 1023</p>
                        <p><strong>Shadow:</strong> {{ $latestSensor->shadow_detected ? 'Yes' : 'No' }}</p>
                        <p><strong>Moving:</strong> {{ $latestSensor->is_moving ? 'Yes' : 'No' }}</p>
                        <p><strong>Mode:</strong> {{ $latestSensor->mode }}</p>
                        <p><strong>Ultrasonic distance:</strong> {{ $latestSensor->ultrasonic_distance ?? 'N/A' }} cm</p>
                    @else
                        <p>No data yet</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">Latest Power Reading</div>
                <div class="card-body">
                    @if($latestPower)
                        <p><strong>Time:</strong> {{ $latestPower->timestamp }}</p>
                        <p><strong>Battery Voltage:</strong> {{ $latestPower->battery_voltage }} V</p>
                        <p><strong>Panel Voltage:</strong> {{ $latestPower->panel_voltage ?? 'N/A' }} V</p>
                    @else
                        <p>No power data</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Events -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-secondary text-white">Recent System Events</div>
                <div class="card-body">
                    <ul class="list-group">
                        @forelse($recentEvents as $event)
                            <li class="list-group-item">{{ $event->timestamp }} - {{ $event->event_type }} @if($event->details) ({{ $event->details }}) @endif</li>
                        @empty
                            <li class="list-group-item">No events recorded.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Historical Chart -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">Light Intensity History (Last 24 Hours)</div>
                <div class="card-body">
                    <canvas id="lightChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const chartData = @json($chartData);
    const labels = chartData.map(item => item.time_bucket);
    const lightValues = chartData.map(item => item.avg_light);

    const ctx = document.getElementById('lightChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Average Light (0-1023)',
                data: lightValues,
                borderColor: 'rgb(255, 159, 64)',
                backgroundColor: 'rgba(255, 159, 64, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                tooltip: { mode: 'index' },
                legend: { position: 'top' }
            }
        }
    });
</script>
@endsection