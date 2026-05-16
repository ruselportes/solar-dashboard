@extends('layouts.app')

@section('title', 'Sensor Log History')

@push('styles')
<style>
  /* ── Fade-in animation ─────────────────────────────────── */
  @keyframes fadeInUp {
    from { opacity: 0; transform: translateY(16px); }
    to   { opacity: 1; transform: translateY(0); }
  }
  .animate-in { animation: fadeInUp 0.4s ease-out both; }

  /* ── Filter card ────────────────────────────────────────── */
  .filter-card {
    background: var(--bg-card);
    backdrop-filter: blur(var(--glass-blur));
    -webkit-backdrop-filter: blur(var(--glass-blur));
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius);
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.25rem;
  }
  .filter-card label {
    font-size: 0.7rem; font-weight: 600;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.06em;
    margin-bottom: 0.35rem;
    display: block;
  }
  .filter-card .form-control,
  .filter-card .form-select {
    font-size: 0.85rem;
    border-radius: var(--radius-sm);
    background: rgba(255,255,255,0.04);
    border: 1px solid var(--border-subtle);
    color: var(--text-primary);
    padding: 0.5rem 0.75rem;
    transition: var(--transition);
  }
  .filter-card .form-control:focus,
  .filter-card .form-select:focus {
    background: rgba(255,255,255,0.06);
    border-color: rgba(245,158,11,0.4);
    box-shadow: 0 0 0 3px rgba(245,158,11,0.1);
    color: var(--text-primary);
    outline: none;
  }
  .filter-card .form-select option {
    background: var(--bg-secondary);
    color: var(--text-primary);
  }

  /* ── Buttons ────────────────────────────────────────────── */
  .btn-apply {
    background: linear-gradient(135deg, var(--amber), #f97316);
    color: #fff;
    border: none;
    padding: 0.5rem 1.5rem;
    border-radius: var(--radius-sm);
    font-size: 0.82rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
  }
  .btn-apply:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 15px var(--amber-glow);
  }
  .btn-clear {
    background: rgba(255,255,255,0.05);
    color: var(--text-secondary);
    border: 1px solid var(--border-subtle);
    padding: 0.5rem 1rem;
    border-radius: var(--radius-sm);
    font-size: 0.82rem;
    font-weight: 500;
    cursor: pointer;
    transition: var(--transition);
  }
  .btn-clear:hover {
    background: rgba(255,255,255,0.08);
    color: var(--text-primary);
  }

  /* ── Table card ─────────────────────────────────────────── */
  .table-card {
    background: var(--bg-card);
    backdrop-filter: blur(var(--glass-blur));
    -webkit-backdrop-filter: blur(var(--glass-blur));
    border: 1px solid var(--border-subtle);
    border-radius: var(--radius);
    overflow: hidden;
  }
  .table-card .table { margin: 0; font-size: 0.82rem; color: var(--text-primary); }
  .table-card .table > :not(caption) > * > * {
    background: transparent;
    border-color: var(--border-subtle);
  }
  .table-card thead tr {
    background: rgba(255,255,255,0.03);
    position: sticky; top: 0; z-index: 2;
  }
  .table-card thead th {
    font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 0.06em; color: var(--text-muted);
    border-bottom: 1px solid var(--border-subtle) !important;
    padding: 0.75rem 1rem; white-space: nowrap;
    background: rgba(17, 24, 39, 0.95);
  }
  .table-card tbody tr {
    transition: background 0.15s ease;
  }
  .table-card tbody tr:hover {
    background: rgba(255,255,255,0.03) !important;
  }
  .table-card tbody td {
    padding: 0.6rem 1rem; vertical-align: middle;
    border-color: var(--border-subtle);
  }

  /* ── Badges ─────────────────────────────────────────────── */
  .badge-yes {
    background: rgba(244,63,94,0.15); color: #fb7185;
    padding: 0.2rem 0.55rem; border-radius: 50px;
    font-size: 0.7rem; font-weight: 700;
  }
  .badge-no {
    background: rgba(16,185,129,0.15); color: #34d399;
    padding: 0.2rem 0.55rem; border-radius: 50px;
    font-size: 0.7rem; font-weight: 700;
  }
  .badge-mode-pill {
    background: rgba(59,130,246,0.15); color: #60a5fa;
    padding: 0.2rem 0.6rem; border-radius: 50px;
    font-size: 0.7rem; font-weight: 700;
  }

  /* ── LDR mini cells ─────────────────────────────────────── */
  .ldr-mini { display: flex; gap: 0.25rem; flex-wrap: wrap; }
  .ldr-mini span {
    background: rgba(255,255,255,0.04);
    border: 1px solid var(--border-subtle);
    border-radius: 4px; padding: 0.1rem 0.35rem;
    font-size: 0.68rem; font-weight: 600;
    color: var(--amber);
    min-width: 28px; text-align: center;
  }

  /* ── Infinite scroll ────────────────────────────────────── */
  #scroll-sentinel { height: 1px; }

  #scroll-spinner {
    display: none;
    text-align: center;
    padding: 1.5rem;
    color: var(--text-muted);
    font-size: 0.82rem;
    gap: 0.5rem;
    align-items: center;
    justify-content: center;
  }
  #scroll-spinner.visible { display: flex; }

  .spinner-ring {
    width: 18px; height: 18px;
    border: 2.5px solid var(--border-subtle);
    border-top-color: var(--amber);
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
    flex-shrink: 0;
  }
  @keyframes spin { to { transform: rotate(360deg); } }

  #end-message {
    display: none;
    text-align: center;
    padding: 1.5rem;
    color: var(--text-muted);
    font-size: 0.8rem;
  }
  #end-message.visible { display: block; }

  .record-counter {
    display: inline-flex; align-items: center; gap: 0.35rem;
    background: rgba(255,255,255,0.04);
    border: 1px solid var(--border-subtle);
    border-radius: 50px;
    padding: 0.25rem 0.75rem; font-size: 0.78rem;
    font-weight: 600; color: var(--text-muted);
  }

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

  .btn-back {
    background: rgba(255,255,255,0.05);
    color: var(--text-secondary);
    border: 1px solid var(--border-subtle);
    padding: 0.45rem 1rem;
    border-radius: var(--radius-sm);
    font-size: 0.82rem;
    font-weight: 500;
    text-decoration: none;
    transition: var(--transition);
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
  }
  .btn-back:hover {
    background: rgba(255,255,255,0.08);
    color: var(--text-primary);
  }
</style>
@endpush

@section('content')
<div class="container-fluid px-4 py-4">

  {{-- ── Header ────────────────────────────────────────────── --}}
  <div class="d-flex align-items-center justify-content-between mb-4 page-header animate-in">
    <div>
      <h2>📋 Sensor Log History</h2>
      <small>Scroll down to load more · Filters reload results instantly</small>
    </div>
    <a href="{{ route('dashboard') }}" class="btn-back">
      ← Dashboard
    </a>
  </div>

  {{-- ── Filters ───────────────────────────────────────────── --}}
  <div class="filter-card animate-in">
    <div class="row g-3 align-items-end">

      <div class="col-6 col-md-2">
        <label>From Date</label>
        <input type="date" id="f-date-from" class="form-control"
               value="{{ request('date_from') }}">
      </div>

      <div class="col-6 col-md-2">
        <label>To Date</label>
        <input type="date" id="f-date-to" class="form-control"
               value="{{ request('date_to') }}">
      </div>

      <div class="col-6 col-md-2">
        <label>Shadow</label>
        <select id="f-shadow" class="form-select">
          <option value="">All</option>
          <option value="1" {{ request('shadow')==='1' ? 'selected':'' }}>Shadow Detected</option>
          <option value="0" {{ request('shadow')==='0' ? 'selected':'' }}>No Shadow</option>
        </select>
      </div>

      <div class="col-6 col-md-2">
        <label>Mode</label>
        <select id="f-mode" class="form-select">
          <option value="">All Modes</option>
          @foreach($modes as $mode)
            <option value="{{ $mode }}" {{ request('mode')===$mode ? 'selected':'' }}>
              {{ $mode }}
            </option>
          @endforeach
        </select>
      </div>

      <div class="col-12 col-md-4 d-flex gap-2 align-items-end">
        <button id="btn-apply" class="btn-apply">
          🔍 Apply
        </button>
        <button id="btn-clear" class="btn-clear">
          ✕ Clear
        </button>
        <span class="record-counter ms-auto">
          <span id="loaded-count">0</span> loaded
        </span>
      </div>

    </div>
  </div>

  {{-- ── Table ─────────────────────────────────────────────── --}}
  <div class="table-card animate-in">
    <div style="overflow-x:auto;">
      <table class="table table-hover">
        <thead>
          <tr>
            <th>#</th>
            <th>Timestamp</th>
            <th>LDR Values</th>
            <th>Shadow</th>
            <th>Moving</th>
            <th>Servo H</th>
            <th>Servo V</th>
            <th>Batt (V)</th>
            <th>Panel (V)</th>
            <th>Distance (cm)</th>
            <th>Mode</th>
          </tr>
        </thead>
        <tbody id="log-tbody">
          {{-- rows injected by JS --}}
        </tbody>
      </table>
    </div>

    <div id="scroll-spinner">
      <div class="spinner-ring"></div>
      Loading more records…
    </div>

    <div id="end-message">
      ✅ All records loaded
    </div>

    <div id="scroll-sentinel"></div>
  </div>

</div>
@endsection

@push('scripts')
<script>
// ── State ───────────────────────────────────────────────────────────────────
let currentPage = 1;
let isFetching  = false;
let hasMore     = true;
let totalLoaded = 0;

// ── DOM refs ────────────────────────────────────────────────────────────────
const tbody    = document.getElementById('log-tbody');
const spinner  = document.getElementById('scroll-spinner');
const endMsg   = document.getElementById('end-message');
const sentinel = document.getElementById('scroll-sentinel');
const counter  = document.getElementById('loaded-count');

// ── Collect filter values ───────────────────────────────────────────────────
function buildQuery(page) {
  const params = new URLSearchParams({
    page,
    per_page: 50,
    date_from: document.getElementById('f-date-from').value,
    date_to:   document.getElementById('f-date-to').value,
    shadow:    document.getElementById('f-shadow').value,
    mode:      document.getElementById('f-mode').value,
  });
  for (const [k, v] of [...params.entries()]) {
    if (v === '') params.delete(k);
  }
  return params.toString();
}

// ── Render one table row ────────────────────────────────────────────────────
function renderRow(log, rowNum) {
  const shadowBadge = log.shadow_detected
    ? '<span class="badge-yes">YES</span>'
    : '<span class="badge-no">NO</span>';

  const movingBadge = log.is_moving
    ? '<span class="badge-yes">YES</span>'
    : '<span class="badge-no">NO</span>';

  const modeBadge = log.mode
    ? `<span class="badge-mode-pill">${log.mode}</span>`
    : '<span style="color:var(--text-muted)">—</span>';

  let ldrHtml = '<div class="ldr-mini">';
  for (let i = 1; i <= 6; i++) {
    ldrHtml += `<span title="LDR${i}">${log['ldr' + i] ?? '—'}</span>`;
  }
  ldrHtml += '</div>';

  const ts = log.timestamp
    ? new Date(log.timestamp).toLocaleString('en-PH', { hour12: false })
    : '—';

  const tr = document.createElement('tr');
  tr.style.animation = 'fadeInUp 0.3s ease-out both';
  tr.innerHTML = `
    <td style="color:var(--text-muted);font-size:.72rem;">${rowNum}</td>
    <td style="white-space:nowrap;font-size:.76rem;color:var(--text-muted);">${ts}</td>
    <td>${ldrHtml}</td>
    <td>${shadowBadge}</td>
    <td>${movingBadge}</td>
    <td style="color:#06b6d4;font-weight:700;">${log.servo_horizontal_angle ?? '—'}°</td>
    <td style="color:#8b5cf6;font-weight:700;">${log.servo_vertical_angle ?? '—'}°</td>
    <td style="color:#10b981;font-weight:700;">${log.power_reading?.battery_voltage ?? '—'}</td>
    <td style="color:#3b82f6;font-weight:700;">${log.power_reading?.panel_voltage ?? '—'}</td>
    <td style="color:#f59e0b;font-weight:700;">${log.ultrasonic_distance ?? '—'}</td>
    <td>${modeBadge}</td>
  `;
  return tr;
}

// ── Fetch one page of results ───────────────────────────────────────────────
async function fetchPage(page) {
  if (isFetching || !hasMore) return;
  isFetching = true;
  spinner.classList.add('visible');

  try {
    const res  = await fetch(`{{ route('logs') }}?${buildQuery(page)}`, {
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      }
    });

    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const json = await res.json();

    const rows = json.data ?? [];
    const from = json.from ?? (totalLoaded + 1);

    rows.forEach((log, idx) => {
      tbody.appendChild(renderRow(log, from + idx));
    });

    totalLoaded += rows.length;
    counter.textContent = totalLoaded.toLocaleString();

    hasMore     = json.next_page_url !== null;
    currentPage = page + 1;

    if (!hasMore) {
      endMsg.classList.add('visible');
      observer.disconnect();
    }

  } catch (err) {
    console.error('Fetch error:', err);
  } finally {
    isFetching = false;
    spinner.classList.remove('visible');
  }
}

// ── IntersectionObserver ────────────────────────────────────────────────────
const observer = new IntersectionObserver(entries => {
  if (entries[0].isIntersecting) fetchPage(currentPage);
}, { rootMargin: '300px' });

observer.observe(sentinel);

// ── Reset and reload ────────────────────────────────────────────────────────
function resetAndFetch() {
  tbody.innerHTML  = '';
  currentPage      = 1;
  isFetching       = false;
  hasMore          = true;
  totalLoaded      = 0;
  counter.textContent = '0';
  endMsg.classList.remove('visible');
  observer.observe(sentinel);
  fetchPage(1);
}

document.getElementById('btn-apply').addEventListener('click', resetAndFetch);

document.getElementById('btn-clear').addEventListener('click', () => {
  ['f-date-from', 'f-date-to', 'f-shadow', 'f-mode'].forEach(id => {
    const el = document.getElementById(id);
    el.value = el.tagName === 'SELECT' ? '' : '';
  });
  resetAndFetch();
});

// ── Initial load ────────────────────────────────────────────────────────────
fetchPage(1);
</script>
@endpush
