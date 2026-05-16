{{-- resources/views/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Solar Station — Dashboard')

@push('styles')
<style>
  /* ── Dashboard-specific styles ──────────────────────── */

  /* Fade-in animation for cards */
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(20px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .animate-in {
    animation: fadeInUp 0.5s ease-out both;
  }
  .animate-in:nth-child(1) { animation-delay: 0.05s; }
  .animate-in:nth-child(2) { animation-delay: 0.10s; }
  .animate-in:nth-child(3) { animation-delay: 0.15s; }
  .animate-in:nth-child(4) { animation-delay: 0.20s; }

  /* ── Stat cards ───────────────────────────────────────── */
  .stat-card {
    background: var(--bg-card);
    backdrop-filter: blur(var(--glass-blur));
    -webkit-backdrop-filter: blur(var(--glass-blur));
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius);
    padding: 1.25rem 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    transition: var(--transition);
    position: relative;
    overflow: hidden;
  }
  .stat-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    border-radius: var(--radius) var(--radius) 0 0;
    background: linear-gradient(90deg, var(--accent-from), var(--accent-to));
    opacity: 0.8;
  }
  .stat-card:hover {
    background: var(--bg-card-hover);
    border-color: rgba(255,255,255,0.1);
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(0,0,0,0.3);
  }
  .stat-card.amber  { --accent-from: #f59e0b; --accent-to: #f97316; }
  .stat-card.teal   { --accent-from: #06b6d4; --accent-to: #0ea5e9; }
  .stat-card.slate  { --accent-from: #64748b; --accent-to: #475569; }
  .stat-card.violet { --accent-from: #8b5cf6; --accent-to: #a855f7; }
  .stat-card.emerald { --accent-from: #10b981; --accent-to: #059669; }
  .stat-card.blue    { --accent-from: #3b82f6; --accent-to: #2563eb; }

  .stat-icon {
    width: 48px; height: 48px;
    border-radius: var(--radius-sm);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem; flex-shrink: 0;
  }
  .stat-card.amber  .stat-icon { background: var(--amber-glow); }
  .stat-card.teal   .stat-icon { background: var(--teal-glow); }
  .stat-card.slate  .stat-icon { background: rgba(100,116,139,0.15); }
  .stat-card.violet .stat-icon { background: var(--violet-glow); }
  .stat-card.emerald .stat-icon { background: var(--emerald-glow); }
  .stat-card.blue    .stat-icon { background: rgba(59,130,246,0.15); }

  .stat-label {
    font-size: 0.7rem; font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 0.15rem;
  }
  .stat-value {
    font-size: 1.5rem; font-weight: 800;
    line-height: 1.1;
    color: var(--text-primary);
    letter-spacing: -0.02em;
  }

  /* ── Glass card (charts, panels) ──────────────────────── */
  .glass-card {
    background: var(--bg-card);
    backdrop-filter: blur(var(--glass-blur));
    -webkit-backdrop-filter: blur(var(--glass-blur));
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius);
    padding: 1.5rem;
    transition: var(--transition);
  }
  .glass-card:hover {
    border-color: rgba(255,255,255,0.08);
  }
  .glass-card h5 {
    font-size: 0.82rem; font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    color: var(--text-muted);
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
  }

  /* ── Time-range toggle ─────────────────────────────────── */
  .range-pills { display: flex; flex-wrap: wrap; gap: 0.35rem; margin-bottom: 1rem; }
  .range-pill {
    padding: 0.3rem 0.75rem;
    border-radius: 50px;
    font-size: 0.72rem; font-weight: 600;
    background: rgba(255,255,255,0.04);
    color: var(--text-muted);
    border: 1px solid transparent;
    cursor: pointer;
    transition: var(--transition);
    user-select: none;
  }
  .range-pill:hover {
    background: rgba(255,255,255,0.08);
    color: var(--text-secondary);
  }
  .range-pill.active {
    background: var(--amber-glow);
    color: var(--amber);
    border-color: rgba(245,158,11,0.3);
  }

  /* ── Status badges ──────────────────────────────────────── */
  .badge-mode {
    display: inline-flex; align-items: center; gap: 0.35rem;
    padding: 0.35rem 0.85rem; border-radius: 50px;
    font-size: 0.75rem; font-weight: 600;
    letter-spacing: 0.02em;
  }
  .badge-auto   { background: rgba(16,185,129,0.15); color: #34d399; border: 1px solid rgba(16,185,129,0.2); }
  .badge-manual { background: rgba(251,191,36,0.15); color: #fbbf24; border: 1px solid rgba(251,191,36,0.2); }
  .badge-shadow { background: var(--rose-glow); color: #fb7185; border: 1px solid rgba(244,63,94,0.2); }
  .badge-moving { background: rgba(59,130,246,0.15); color: #60a5fa; border: 1px solid rgba(59,130,246,0.2); }

  .pulse-dot {
    width: 7px; height: 7px; border-radius: 50%;
    background: currentColor; display: inline-block;
    animation: pulseDot 1.6s ease-in-out infinite;
  }
  @keyframes pulseDot {
    0%,100% { opacity: 1; transform: scale(1); }
    50%     { opacity: 0.4; transform: scale(1.5); }
  }

  /* ── LDR grid ──────────────────────────────────────────── */
  .ldr-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 0.5rem;
  }
  .ldr-cell {
    background: rgba(255,255,255,0.03);
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius-sm);
    padding: 0.6rem 0.75rem;
    font-size: 0.72rem;
    font-weight: 600;
    color: var(--text-muted);
    transition: var(--transition);
  }
  .ldr-cell:hover {
    background: rgba(255,255,255,0.06);
    border-color: rgba(255,255,255,0.1);
  }
  .ldr-cell span {
    display: block;
    font-size: 1.05rem;
    font-weight: 800;
    color: var(--amber);
    margin-top: 0.15rem;
    letter-spacing: -0.02em;
  }

  /* ── Loading overlay ────────────────────────────────────── */
  .chart-loading {
    position: absolute; inset: 0;
    background: rgba(11,15,26,0.8);
    backdrop-filter: blur(4px);
    display: flex; align-items: center; justify-content: center;
    border-radius: var(--radius);
    font-size: 0.82rem; color: var(--text-muted);
    z-index: 5;
  }
  .chart-wrap { position: relative; }

  /* ── Section header ─────────────────────────────────────── */
  .section-head {
    display: flex; align-items: center;
    justify-content: space-between;
    margin-bottom: 1rem;
    flex-wrap: wrap;
    gap: 0.5rem;
  }

  /* ── System status table ────────────────────────────────── */
  .status-table { width: 100%; border-collapse: collapse; }
  .status-table tr { border-bottom: 1px solid var(--border-subtle); }
  .status-table tr:last-child { border-bottom: none; }
  .status-table td { padding: 0.65rem 0; font-size: 0.85rem; }
  .status-table td:first-child { color: var(--text-muted); width: 55%; }
  .status-table td:last-child { text-align: right; font-weight: 600; }

  .status-badge {
    display: inline-flex; align-items: center;
    padding: 0.2rem 0.65rem; border-radius: 50px;
    font-size: 0.72rem; font-weight: 700;
    letter-spacing: 0.02em;
  }
  .status-badge.danger  { background: var(--rose-glow); color: #fb7185; }
  .status-badge.success { background: var(--emerald-glow); color: #34d399; }
  .status-badge.info    { background: rgba(59,130,246,0.15); color: #60a5fa; }
  .status-badge.muted   { background: rgba(100,116,139,0.15); color: #94a3b8; }

  /* ── Page header ────────────────────────────────────────── */
  .page-header h2 {
    font-size: 1.35rem;
    font-weight: 800;
    letter-spacing: -0.02em;
    margin: 0;
  }
  .page-header small {
    color: var(--text-muted);
    font-size: 0.8rem;
  }

  /* ── Updated time stamp ─────────────────────────────────── */
  .updated-tag {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.72rem;
    color: var(--text-muted);
    background: rgba(255,255,255,0.04);
    padding: 0.3rem 0.75rem;
    border-radius: 50px;
    border: 1px solid var(--border-subtle);
  }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">

  {{-- ── Page header ──────────────────────────────────────────────── --}}
  <div class="d-flex align-items-center justify-content-between mb-4 page-header">
    <div>
      <h2>☀️ Solar Station Dashboard</h2>
      <small>Real-time monitoring · Auto-refreshes every 10s</small>
    </div>
    <div class="updated-tag" id="last-refresh">
      <span class="status-dot" style="width:6px;height:6px;border-radius:50%;background:var(--emerald);display:inline-block;"></span>
      Waiting for data…
    </div>
  </div>

  {{-- ── Stat cards ──────────────────────────────────────────────── --}}
  <div class="row g-3 mb-4">
    <div class="col-6 col-md-3 animate-in">
      <div class="stat-card amber">
        <div class="stat-icon">💡</div>
        <div>
          <div class="stat-label">Avg Light Today</div>
          <div class="stat-value" id="stat-avg-light">{{ $stats['avg_light'] }}</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3 animate-in">
      <div class="stat-card teal">
        <div class="stat-icon">🌑</div>
        <div>
          <div class="stat-label">Shadow Events</div>
          <div class="stat-value" id="stat-shadow">{{ $stats['shadow_count'] }}</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3 animate-in">
      <div class="stat-card slate">
        <div class="stat-icon">📋</div>
        <div>
          <div class="stat-label">Logs Today</div>
          <div class="stat-value" id="stat-logs">{{ $stats['total_logs'] }}</div>
        </div>
      </div>
    </div>
    <div class="col-6 col-md-3 animate-in">
      <div class="stat-card violet">
        <div class="stat-icon">🔄</div>
        <div>
          <div class="stat-label">System Mode</div>
          <div class="stat-value" id="stat-mode" style="font-size:1rem; padding-top:.2rem;">
            @if($latest)
              <span class="badge-mode badge-{{ strtolower($latest->mode ?? 'auto') }}">
                <span class="pulse-dot"></span>{{ $latest->mode ?? 'AUTO' }}
              </span>
            @else
              <span style="color:var(--text-muted)">—</span>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-6 animate-in">
      <div class="stat-card emerald">
        <div class="stat-icon">🔋</div>
        <div>
          <div class="stat-label">Battery Voltage</div>
          <div class="stat-value"><span id="stat-batt">{{ $latest->powerReading->battery_voltage ?? '—' }}</span><span style="font-size: 0.8rem; font-weight: 500; margin-left: 0.2rem; color: var(--text-muted);">V</span></div>
        </div>
      </div>
    </div>
    <div class="col-6 animate-in">
      <div class="stat-card blue">
        <div class="stat-icon">☀️</div>
        <div>
          <div class="stat-label">Panel Voltage</div>
          <div class="stat-value"><span id="stat-panel">{{ $latest->powerReading->panel_voltage ?? '—' }}</span><span style="font-size: 0.8rem; font-weight: 500; margin-left: 0.2rem; color: var(--text-muted);">V</span></div>
        </div>
      </div>
    </div>
  </div>

  {{-- ── Charts row ───────────────────────────────────────────────── --}}
  <div class="row g-3 mb-4">

    {{-- Light Intensity Chart --}}
    <div class="col-12 col-lg-8 animate-in">
      <div class="glass-card">
        <div class="section-head">
          <h5 class="m-0">💡 Light Intensity & Shadow Ratio</h5>
          <div class="range-pills" id="range-pills-light">
            @foreach(['15min'=>'15 min','30min'=>'30 min','1hour'=>'1 hr','6hours'=>'6 hrs','24hours'=>'24 hrs','7days'=>'7 days'] as $val=>$label)
              <span class="range-pill {{ $val==='24hours' ? 'active':'' }}" data-range="{{ $val }}">{{ $label }}</span>
            @endforeach
          </div>
        </div>
        <div class="chart-wrap" style="height:280px;">
          <div class="chart-loading" id="loading-light" style="display:none;">Loading…</div>
          <canvas id="chartLight"></canvas>
        </div>
      </div>
    </div>

    {{-- Servo Angles Chart --}}
    <div class="col-12 col-lg-4 animate-in">
      <div class="glass-card">
        <div class="section-head">
          <h5 class="m-0">🎯 Servo Angles</h5>
          <div class="range-pills" id="range-pills-servo">
            @foreach(['15min'=>'15 min','30min'=>'30 min','1hour'=>'1 hr','6hours'=>'6 hrs','24hours'=>'24 hrs','7days'=>'7 days'] as $val=>$label)
              <span class="range-pill {{ $val==='1hour' ? 'active':'' }}" data-range="{{ $val }}">{{ $label }}</span>
            @endforeach
          </div>
        </div>
        <div class="chart-wrap" style="height:280px;">
          <div class="chart-loading" id="loading-servo" style="display:none;">Loading…</div>
          <canvas id="chartServo"></canvas>
        </div>
      </div>
    </div>

  </div>

  {{-- ── Power Chart row ─────────────────────────────────────────── --}}
  <div class="row g-3 mb-4">
    <div class="col-12 animate-in">
      <div class="glass-card">
        <div class="section-head">
          <h5 class="m-0">⚡ Power Status (Battery & Panel)</h5>
          <div class="range-pills" id="range-pills-power">
            @foreach(['15min'=>'15 min','30min'=>'30 min','1hour'=>'1 hr','6hours'=>'6 hrs','24hours'=>'24 hrs','7days'=>'7 days'] as $val=>$label)
              <span class="range-pill {{ $val==='24hours' ? 'active':'' }}" data-range="{{ $val }}">{{ $label }}</span>
            @endforeach
          </div>
        </div>
        <div class="chart-wrap" style="height:280px;">
          <div class="chart-loading" id="loading-power" style="display:none;">Loading…</div>
          <canvas id="chartPower"></canvas>
        </div>
      </div>
    </div>
  </div>
  <div class="row g-3 mb-4">

    {{-- LDR Sensor Readings --}}
    <div class="col-12 col-md-6 animate-in">
      <div class="glass-card">
        <div class="section-head">
          <h5 class="m-0">📡 Live LDR Readings</h5>
          <span class="updated-tag" id="live-updated">—</span>
        </div>
        @if($latest)
        <div class="ldr-grid" id="ldr-grid">
          @foreach(range(1,6) as $i)
          <div class="ldr-cell">
            LDR {{ $i }}
            <span id="ldr-{{ $i }}">{{ $latest->{'ldr'.$i} ?? '—' }}</span>
          </div>
          @endforeach
        </div>
        @else
        <p style="color:var(--text-muted);">No data yet.</p>
        @endif
      </div>
    </div>

    {{-- System Status --}}
    <div class="col-12 col-md-6 animate-in">
      <div class="glass-card">
        <h5>⚙️ System Status</h5>
        @if($latest)
        <table class="status-table" id="status-table">
          <tbody>
            <tr>
              <td>Shadow Detected</td>
              <td id="s-shadow">{!! $latest->shadow_detected ? '<span class="status-badge danger">YES</span>' : '<span class="status-badge success">NO</span>' !!}</td>
            </tr>
            <tr>
              <td>Moving</td>
              <td id="s-moving">{!! $latest->is_moving ? '<span class="status-badge info">YES</span>' : '<span class="status-badge muted">NO</span>' !!}</td>
            </tr>
            <tr>
              <td>Servo H Angle</td>
              <td id="s-servo-h" style="color:var(--teal); font-size:1rem; font-weight:700;">{{ $latest->servo_horizontal_angle ?? '—' }}°</td>
            </tr>
            <tr>
              <td>Servo V Angle</td>
              <td id="s-servo-v" style="color:var(--violet); font-size:1rem; font-weight:700;">{{ $latest->servo_vertical_angle ?? '—' }}°</td>
            </tr>
            <tr>
              <td>Battery Voltage</td>
              <td id="s-batt" style="color:var(--emerald); font-size:1rem; font-weight:700;">{{ $latest->powerReading->battery_voltage ?? '—' }} V</td>
            </tr>
            <tr>
              <td>Panel Voltage</td>
              <td id="s-panel" style="color:var(--blue); font-size:1rem; font-weight:700;">{{ $latest->powerReading->panel_voltage ?? '—' }} V</td>
            </tr>
            <tr>
              <td>Obstacle Distance</td>
              <td id="s-distance" style="color:var(--amber); font-size:1rem; font-weight:700;">{{ $latest->ultrasonic_distance ?? '—' }} cm</td>
            </tr>
            <tr>
              <td>Last Update</td>
              <td id="s-ts" style="font-size:.78rem; color:var(--text-muted);">{{ $latest->timestamp ?? '—' }}</td>
            </tr>
          </tbody>
        </table>
        @else
        <p style="color:var(--text-muted);">No data yet.</p>
        @endif
      </div>
    </div>

  </div>

  <div class="row g-3">
    {{-- Upload Activity --}}
    <div class="col-12 animate-in">
      <div class="glass-card">
        <div class="section-head">
          <h5 class="m-0">📤 Recent Upload Activity</h5>
          <span class="updated-tag">ESP32 Sync History</span>
        </div>
        <div class="table-responsive">
          <table class="status-table">
            <thead>
              <tr style="border-bottom: 2px solid var(--border-subtle)">
                <td style="color: var(--text-primary); font-weight: 700;">Timestamp</td>
                <td style="color: var(--text-primary); font-weight: 700;">Files Uploaded</td>
                <td style="color: var(--text-primary); font-weight: 700;">Pending</td>
                <td style="color: var(--text-primary); font-weight: 700;">Storage Used</td>
                <td style="color: var(--text-primary); font-weight: 700; text-align: right;">Status</td>
              </tr>
            </thead>
            <tbody>
              @forelse($uploads as $upload)
              <tr>
                <td>{{ \Carbon\Carbon::parse($upload->timestamp)->format('M d, H:i:s') }}</td>
                <td>{{ $upload->files_uploaded }}</td>
                <td>{{ $upload->files_pending }}</td>
                <td>{{ number_format($upload->storage_used_kb / 1024, 2) }} MB</td>
                <td style="text-align: right;">
                  @if($upload->upload_success)
                    <span class="status-badge success">SUCCESS</span>
                  @else
                    <span class="status-badge danger">FAILED</span>
                  @endif
                </td>
              </tr>
              @empty
              <tr>
                <td colspan="5" class="text-center py-4" style="color: var(--text-muted);">No upload history found.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
// ── Chart.js dark theme defaults ────────────────────────────────────────────
Chart.defaults.color = '#94a3b8';
Chart.defaults.borderColor = 'rgba(255,255,255,0.04)';

const chartDefaults = {
  responsive: true,
  maintainAspectRatio: false,
  interaction: { mode: 'index', intersect: false },
  plugins: {
    legend: {
      position: 'bottom',
      labels: { boxWidth: 10, padding: 16, font: { size: 11, family: 'Inter' }, usePointStyle: true }
    },
    tooltip: {
      backgroundColor: 'rgba(17, 24, 39, 0.95)',
      borderColor: 'rgba(255,255,255,0.1)',
      borderWidth: 1,
      titleFont: { family: 'Inter', weight: '600' },
      bodyFont: { family: 'Inter' },
      padding: 12,
      cornerRadius: 10,
      callbacks: { label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y}` }
    }
  },
  scales: {
    x: {
      ticks: { maxTicksLimit: 8, font: { size: 10, family: 'Inter' }, maxRotation: 30, color: '#64748b' },
      grid:  { color: 'rgba(255,255,255,0.03)' }
    },
    y: {
      grid: { color: 'rgba(255,255,255,0.03)' },
      ticks: { font: { size: 10, family: 'Inter' }, color: '#64748b' }
    }
  }
};

// ── Light Intensity Chart ───────────────────────────────────────────────────
const ctxLight = document.getElementById('chartLight').getContext('2d');

const lightGradient = ctxLight.createLinearGradient(0, 0, 0, 280);
lightGradient.addColorStop(0, 'rgba(245, 158, 11, 0.2)');
lightGradient.addColorStop(1, 'rgba(245, 158, 11, 0.0)');

const lightChart = new Chart(ctxLight, {
  type: 'line',
  data: { labels: [], datasets: [
    {
      label: 'Avg Light',
      data: [], fill: true,
      borderColor: '#f59e0b',
      backgroundColor: lightGradient,
      borderWidth: 2, pointRadius: 0, pointHoverRadius: 4, tension: 0.4
    },
    {
      label: 'Shadow Ratio',
      data: [], fill: false,
      borderColor: '#f43f5e',
      backgroundColor: 'rgba(244,63,94,0.1)',
      borderWidth: 2, pointRadius: 0, pointHoverRadius: 4, tension: 0.4,
      borderDash: [4, 4],
      yAxisID: 'y2'
    }
  ]},
  options: {
    ...chartDefaults,
    scales: {
      ...chartDefaults.scales,
      y:  { ...chartDefaults.scales.y, title: { display: true, text: 'Light (0–1023)', font:{size:10, family:'Inter'}, color:'#64748b' } },
      y2: { position: 'right', min: 0, max: 1, grid: { drawOnChartArea: false },
            ticks: { font:{size:10, family:'Inter'}, color:'#64748b', callback: v => (v*100).toFixed(0)+'%' },
            title: { display: true, text: 'Shadow %', font:{size:10, family:'Inter'}, color:'#64748b' } }
    }
  }
});

// ── Servo Chart ─────────────────────────────────────────────────────────────
const ctxServo = document.getElementById('chartServo').getContext('2d');
const servoChart = new Chart(ctxServo, {
  type: 'line',
  data: { labels: [], datasets: [
    {
      label: 'Horizontal',
      data: [], fill: false,
      borderColor: '#06b6d4',
      backgroundColor: 'rgba(6,182,212,0.1)',
      borderWidth: 2, pointRadius: 0, pointHoverRadius: 4, tension: 0.4
    },
    {
      label: 'Vertical',
      data: [], fill: false,
      borderColor: '#8b5cf6',
      backgroundColor: 'rgba(139,92,246,0.1)',
      borderWidth: 2, pointRadius: 0, pointHoverRadius: 4, tension: 0.4
    }
  ]},
  options: {
    ...chartDefaults,
    scales: {
      ...chartDefaults.scales,
      y: { ...chartDefaults.scales.y, min: 0, max: 180,
           title: { display: true, text: 'Angle (°)', font:{size:10, family:'Inter'}, color:'#64748b' } }
    }
  }
});

const ctxPower = document.getElementById('chartPower').getContext('2d');
const powerChart = new Chart(ctxPower, {
  type: 'line',
  data: { labels: [], datasets: [
    {
      label: 'Battery (V)',
      data: [], fill: true,
      borderColor: '#10b981',
      backgroundColor: 'rgba(16,185,129,0.05)',
      borderWidth: 2, pointRadius: 0, pointHoverRadius: 4, tension: 0.4
    },
    {
      label: 'Panel (V)',
      data: [], fill: false,
      borderColor: '#3b82f6',
      backgroundColor: 'rgba(59,130,246,0.05)',
      borderWidth: 2, pointRadius: 0, pointHoverRadius: 4, tension: 0.4
    }
  ]},
  options: {
    ...chartDefaults,
    scales: {
      ...chartDefaults.scales,
      y: { ...chartDefaults.scales.y, min: 0, max: 25,
           title: { display: true, text: 'Voltage (V)', font:{size:10, family:'Inter'}, color:'#64748b' } }
    }
  }
});

// ── Fetch & update chart ────────────────────────────────────────────────────
async function fetchChartData(range, chart, loadingId, keys) {
  document.getElementById(loadingId).style.display = 'flex';
  try {
    const res  = await fetch(`{{ route('dashboard.chartData') }}?range=${range}&points=80`);
    const data = await res.json();
    chart.data.labels = data.labels;
    keys.forEach((k, i) => { chart.data.datasets[i].data = data[k]; });
    chart.update('active');
  } catch(e) { console.error(e); }
  finally { document.getElementById(loadingId).style.display = 'none'; }
}

// ── Range pill wiring ───────────────────────────────────────────────────────
function initRangePills(containerId, chartObj, loadingId, keys, defaultRange) {
  const container = document.getElementById(containerId);
  container.querySelectorAll('.range-pill').forEach(pill => {
    pill.addEventListener('click', () => {
      container.querySelectorAll('.range-pill').forEach(p => p.classList.remove('active'));
      pill.classList.add('active');
      fetchChartData(pill.dataset.range, chartObj, loadingId, keys);
    });
  });
  fetchChartData(defaultRange, chartObj, loadingId, keys);
}

initRangePills('range-pills-light', lightChart, 'loading-light', ['avg_light', 'shadow_ratio'], '24hours');
initRangePills('range-pills-servo', servoChart, 'loading-servo', ['servo_h', 'servo_v'], '1hour');
initRangePills('range-pills-power', powerChart, 'loading-power', ['avg_batt', 'avg_panel'], '24hours');

// ── Real-time polling (every 10 s) ─────────────────────────────────────────
async function pollLatest() {
  try {
    const res  = await fetch('/api/sensor-logs/latest');
    if (!res.ok) return;
    const d    = await res.json();
    for (let i = 1; i <= 6; i++) {
      const el = document.getElementById('ldr-'+i);
      if (el) el.textContent = d['ldr'+i] ?? '—';
    }
    const fields = {
      's-shadow':   d.shadow_detected ? '<span class="status-badge danger">YES</span>' : '<span class="status-badge success">NO</span>',
      's-moving':   d.is_moving       ? '<span class="status-badge info">YES</span>' : '<span class="status-badge muted">NO</span>',
      's-servo-h':  `<span style="color:var(--teal);font-size:1rem;font-weight:700;">${d.servo_horizontal_angle ?? '—'}°</span>`,
      's-servo-v':  `<span style="color:var(--violet);font-size:1rem;font-weight:700;">${d.servo_vertical_angle ?? '—'}°</span>`,
      's-batt':     `<span style="color:var(--emerald);font-size:1rem;font-weight:700;">${d.power_reading?.battery_voltage ?? '—'} V</span>`,
      's-panel':    `<span style="color:var(--blue);font-size:1rem;font-weight:700;">${d.power_reading?.panel_voltage ?? '—'} V</span>`,
      's-distance': `<span style="color:var(--amber);font-size:1rem;font-weight:700;">${d.ultrasonic_distance ?? '—'} cm</span>`,
      's-ts':       d.timestamp ?? '—',
    };
    Object.entries(fields).forEach(([id, val]) => {
      const el = document.getElementById(id);
      if (el) el.innerHTML = val;
    });

    // Update top stat cards
    if (d.power_reading) {
      const bEl = document.getElementById('stat-batt');
      if (bEl) bEl.textContent = d.power_reading.battery_voltage ?? '—';
      const pEl = document.getElementById('stat-panel');
      if (pEl) pEl.textContent = d.power_reading.panel_voltage ?? '—';
    }

    const now = new Date().toLocaleTimeString('en-PH', { hour12: false });
    document.getElementById('live-updated').textContent = '⏱ Updated ' + now;
    document.getElementById('last-refresh').innerHTML = '<span style="width:6px;height:6px;border-radius:50%;background:var(--emerald);display:inline-block;"></span> Live · ' + now;
  } catch(e) {
    document.getElementById('nav-connection-status').textContent = 'Reconnecting…';
  }
}

pollLatest();
setInterval(pollLatest, 10000);
</script>
@endpush