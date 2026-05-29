<x-layouts.app>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<style>
    .section-header {
        background: #f8f9fa;
        border-left: 4px solid #198754;
        padding: 8px 14px;
        font-weight: 700;
        font-size: .85rem;
        text-transform: uppercase;
        letter-spacing: .06em;
        color: #495057;
        margin-bottom: 1rem;
        border-radius: 0 4px 4px 0;
    }
</style>

@php $user = auth()->user(); @endphp

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="fa fa-calendar me-2"></i>Season Management</h4>
    <a href="{{ route('index') }}" class="btn btn-outline-secondary btn-sm">
        <i class="fa fa-arrow-left"></i> Back to Farms
    </a>
</div>

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Season Status Banner --}}
<div class="alert {{ $currentSeason && $currentSeason->status === 'OPEN' ? 'alert-success' : 'alert-secondary' }} mb-4">
    <strong>Current Season: {{ $currentSeasonString }}</strong> —
    Status: <span class="badge {{ $currentSeason && $currentSeason->status === 'OPEN' ? 'bg-success' : 'bg-secondary' }}">
        {{ $currentSeason ? $currentSeason->status : 'NOT STARTED' }}
    </span>
    @if ($currentSeason && $currentSeason->nextinspection_date)
        &nbsp;| Next Inspection: <strong>{{ $currentSeason->nextinspection_date }}</strong>
    @endif
</div>

<div class="row g-3 mb-4">
    {{-- Open Season Card --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <i class="fa fa-unlock"></i> Open Season
            </div>
            <div class="card-body">
                <p class="text-muted small">Opens the current season: sets all farms to ACTIVE, clears inspector assignments, and sets the next inspection date.</p>
                <form action="{{ route('season.open') }}" method="POST"
                      onsubmit="return confirm('Open season {{ $currentSeasonString }}? This will set ALL farms to ACTIVE.');">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Next Inspection Date</label>
                        <input type="date" name="nextinspection_date" class="form-control" required
                               value="{{ $currentSeason?->nextinspection_date }}">
                    </div>
                    <button type="submit" class="btn btn-success w-100"
                            {{ $currentSeason && $currentSeason->status === 'OPEN' ? 'disabled' : '' }}>
                        <i class="fa fa-unlock"></i> Open Season
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Close Season Card --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-secondary text-white">
                <i class="fa fa-lock"></i> Close Season
            </div>
            <div class="card-body">
                <p class="text-muted small">Closes the current season: sets all ACTIVE farms to CLOSED.</p>
                <form action="{{ route('season.close') }}" method="POST"
                      onsubmit="return confirm('Close season {{ $currentSeasonString }}? This will set all ACTIVE farms to CLOSED.');">
                    @csrf
                    <button type="submit" class="btn btn-secondary w-100"
                            {{ !$currentSeason || $currentSeason->status === 'CLOSED' ? 'disabled' : '' }}>
                        <i class="fa fa-lock"></i> Close Season
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Season History Card --}}
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header bg-info text-white">
                <i class="fa fa-history"></i> Season History
            </div>
            <div class="card-body p-0">
                <table class="table table-sm table-striped mb-0">
                    <thead>
                        <tr>
                            <th>Season</th>
                            <th>Status</th>
                            <th>Next Insp.</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($seasons as $season)
                            <tr>
                                <td>{{ $season->season }}</td>
                                <td>
                                    <span class="badge {{ $season->status === 'OPEN' ? 'bg-success' : 'bg-secondary' }}">
                                        {{ $season->status }}
                                    </span>
                                </td>
                                <td>{{ $season->nextinspection_date ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">No seasons on record.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Mass Assign Card --}}
<div class="card mb-4">
    <div class="card-header">
        <i class="fa fa-users"></i> Mass Assign Farms to Inspector
    </div>
    <div class="card-body">
        <form action="{{ route('season.massassign') }}" method="POST"
              onsubmit="return confirm('Assign selected farms to the chosen inspector?');">
            @csrf
            <div class="row g-3 mb-3 align-items-end">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Inspector</label>
                    <select name="inspector_id" class="form-select" required>
                        <option value="">-- Select Inspector --</option>
                        @foreach ($inspectors as $inspector)
                            <option value="{{ $inspector->id }}">{{ $inspector->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-warning w-100">
                        <i class="fa fa-check"></i> Assign Selected
                    </button>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAllFarms">
                        <label class="form-check-label fw-bold" for="selectAllFarms">Select All Farms</label>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-sm table-striped display" id="farmsAssignTable">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Farm Code</th>
                            <th>Farm Name</th>
                            <th>Community</th>
                            <th>Status</th>
                            <th>Current Inspector</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($farms as $farm)
                            <tr>
                                <td>
                                    <input class="form-check-input farm-checkbox" type="checkbox"
                                           name="farm_ids[]" value="{{ $farm->id }}">
                                </td>
                                <td>{{ $farm->farmcode }}</td>
                                <td>{{ $farm->farmname }}</td>
                                <td>{{ $farm->community }}</td>
                                <td>
                                    <span class="badge {{ match($farm->farmstate) {
                                        'ACTIVE'  => 'bg-success',
                                        'PENDING' => 'bg-warning text-dark',
                                        'CLOSED'  => 'bg-secondary',
                                        default   => 'bg-secondary',
                                    } }}">{{ $farm->farmstate }}</span>
                                </td>
                                <td>{{ $farm->getinspectorName() }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                                    No farms registered.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#farmsAssignTable').DataTable({
            dom: 'Bfrtip',
            pageLength: 50,
            order: [[1, 'asc']],
            buttons: [{
                extend: 'excelHtml5',
                title: 'Farm_Assignment',
                exportOptions: { columns: [1, 2, 3, 4, 5] }
            }],
            columnDefs: [{ orderable: false, targets: 0 }]
        });
    });

    document.getElementById('selectAllFarms').addEventListener('change', function () {
        document.querySelectorAll('.farm-checkbox').forEach(cb => cb.checked = this.checked);
    });
</script>
</x-layouts.app>
