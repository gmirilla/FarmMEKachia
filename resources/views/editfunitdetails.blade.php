<x-layouts.app>

<style>
.nav-pill-btn {
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  padding: 6px 16px;
  letter-spacing: 0.3px;
}
.summary-stat {
  background: #f8f9fa;
  border: 1px solid #e9ecef;
  border-radius: 10px;
  padding: 14px 18px;
  min-width: 130px;
}
.summary-stat .label {
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  color: #6c757d;
}
.summary-stat .value {
  font-size: 1.25rem;
  font-weight: 700;
  color: #212529;
  line-height: 1.2;
}
.fu-table th {
  font-size: 0.72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  white-space: nowrap;
  background: #f1f3f5;
  color: #495057;
  border-bottom: 2px solid #dee2e6;
}
.fu-table td {
  font-size: 0.85rem;
  vertical-align: middle;
}
.fu-table tbody tr:hover {
  background-color: #f8f9fa;
}
.badge-active {
  background: #d1fae5;
  color: #065f46;
  font-size: 0.7rem;
  font-weight: 700;
  padding: 3px 8px;
  border-radius: 20px;
  text-transform: uppercase;
}
.badge-inactive {
  background: #fee2e2;
  color: #991b1b;
  font-size: 0.7rem;
  font-weight: 700;
  padding: 3px 8px;
  border-radius: 20px;
  text-transform: uppercase;
}
</style>

{{-- Navigation --}}
<div class="d-flex align-items-center flex-wrap gap-2 mb-4">
  <form method="get" action="{{route('edit_farmunit')}}">
    @csrf
    <input type="text" name="farmid" hidden value="{{$farm->id}}">
    <button class="btn btn-outline-secondary nav-pill-btn">
      <i class="fa fa-leaf me-1"></i> Farm Details
    </button>
  </form>

  <button disabled class="btn btn-primary nav-pill-btn">
    <i class="fa fa-th-large me-1"></i> Farm Plots
  </button>

  <form method="get" action="{{route('list_yield')}}">
    @csrf
    <input type="text" name="farmid" hidden value="{{$farm->id}}">
    <button class="btn btn-outline-secondary nav-pill-btn" name="getyield">
      <i class="fa fa-bar-chart me-1"></i> Yield Details
    </button>
  </form>
</div>

{{-- Validation errors --}}
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show mb-3" role="alert" style="font-size:.85rem; border-radius:10px;">
  <ul class="mb-0 ps-3">
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Page header + summary stats --}}
<div class="d-flex align-items-start justify-content-between flex-wrap gap-3 mb-3">
  <div>
    <h4 class="fw-bold mb-0">
      <i class="fa fa-map me-2 text-success"></i>
      {{ $farm->farmcode }} &mdash; Farm Plots
    </h4>
    <p class="text-muted mb-0" style="font-size:.82rem;">Manage all registered farm units for this farm</p>
  </div>
  <div class="d-flex gap-3 flex-wrap">
    <div class="summary-stat text-center">
      <div class="label">Farm Code</div>
      <div class="value" style="font-size:1rem;">{{ $farm->farmcode }}</div>
    </div>
    <div class="summary-stat text-center">
      <div class="label">No. of Units</div>
      <div class="value">{{ $farm->nooffarmunits ?? 0 }}</div>
    </div>
    <div class="summary-stat text-center">
      <div class="label">Total Area</div>
      <div class="value" style="font-size:1rem;">{{ $farm->farmarea ?? '—' }} <span style="font-size:.7rem; color:#6c757d; font-weight:600;">ha</span></div>
    </div>
  </div>
</div>

{{-- Farm units table --}}
<div class="card border-0 shadow-sm" style="border-radius:12px;">
  <div class="card-header bg-white border-bottom px-3 py-2 d-flex align-items-center justify-content-between" style="border-radius:12px 12px 0 0;">
    <span class="fw-semibold" style="font-size:.82rem; text-transform:uppercase; letter-spacing:.5px; color:#6c757d;">
      <i class="fa fa-list me-1"></i> Registered Plot Units
    </span>
    <form action="{{route('newfunit')}}" method="get" class="mb-0">
      <input type="text" name="farmid" hidden value="{{$farm->id}}">
      <button class="btn btn-success btn-sm" name="newfunit" style="border-radius:20px; font-size:.78rem; padding:5px 14px;">
        <i class="fa fa-plus me-1"></i> Add Farm Unit
      </button>
    </form>
  </div>

  <div class="card-body p-0">
    <div class="table-responsive">
      <table class="table fu-table mb-0">
        <thead>
          <tr>
            <th class="ps-3">Unit ID</th>
            <th>Plot Name</th>
            <th>Area (ha)</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Date Planted</th>
            <th>Status</th>
            <th class="pe-3 text-center">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($farmunits as $funit)
          <form method="post" action="{{route('editfu')}}">
            {{ csrf_field() }}
            <input type="text" value="{{$funit->id}}" name="fid" hidden>
            <input type="text" value="{{$farm->id}}" name="farmid" hidden>
            <tr>
              <td class="ps-3 fw-semibold text-muted" style="font-size:.8rem;">#{{ $funit->id }}</td>
              <td class="fw-semibold">{{ $funit->plotname ?? '—' }}</td>
              <td>{{ $funit->fuarea }}</td>
              <td style="font-size:.8rem; font-family:monospace;">{{ $funit->fulatitude ?? '—' }}</td>
              <td style="font-size:.8rem; font-family:monospace;">{{ $funit->fulongitude ?? '—' }}</td>
              <td style="font-size:.82rem;">{{ $funit->dateplanted ?? '—' }}</td>
              <td>
                @if ($funit->active)
                  <span class="badge-active">Active</span>
                @else
                  <span class="badge-inactive">Inactive</span>
                @endif
              </td>
              <td class="pe-3 text-center">
                @if ($funit->active)
                <button type="submit" class="btn btn-outline-primary btn-sm" name="updatefu" style="font-size:.75rem; border-radius:6px;">
                  <i class="fa fa-pencil me-1"></i>Edit
                </button>
                <button type="submit" class="btn btn-outline-danger btn-sm ms-1" name="deletefu" style="font-size:.75rem; border-radius:6px;">
                  <i class="fa fa-times me-1"></i>Deactivate
                </button>
                @else
                <button type="submit" class="btn btn-outline-secondary btn-sm" disabled name="updatefu" style="font-size:.75rem; border-radius:6px;">
                  <i class="fa fa-pencil me-1"></i>Edit
                </button>
                <button type="submit" class="btn btn-outline-secondary btn-sm ms-1" disabled name="deletefu" style="font-size:.75rem; border-radius:6px;">
                  <i class="fa fa-times me-1"></i>Inactive
                </button>
                @endif
              </td>
            </tr>
          </form>
          @empty
          <tr>
            <td colspan="8" class="text-center py-5 text-muted">
              <i class="fa fa-map-o fa-2x mb-2 d-block" style="opacity:.35;"></i>
              <strong>No farm units recorded yet.</strong><br>
              <span style="font-size:.82rem;">Click <em>Add Farm Unit</em> to get started.</span>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

</x-layouts.app>
