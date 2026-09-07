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
    .score-bar  { height:8px; border-radius:4px; background:#e9ecef; }
    .score-fill { height:8px; border-radius:4px; transition:width .6s ease; }
</style>

@php
    $statusBadge = fn($state) => match($state) {
        'APPROVED'    => 'bg-success',
        'CONDITIONAL' => 'bg-warning text-dark',
        'REJECTED'    => 'bg-danger',
        'SUBMITTED'   => 'bg-info text-dark',
        'ACTIVE'      => 'bg-primary',
        'PENDING'     => 'bg-secondary',
        default       => 'bg-secondary',
    };

    $scoreColor = fn($pct) => match(true) {
        $pct <= 49.99 => '#dc3545',
        $pct <= 69.99 => '#fd7e14',
        default       => '#198754',
    };

    $total      = $inspections->count();
    $inProgress = $inspections->whereIn('inspectionstate', ['ACTIVE','PENDING'])->count();
    $submitted  = $inspections->where('inspectionstate', 'SUBMITTED')->count();
    $reviewed   = $inspections->whereIn('inspectionstate', ['APPROVED','CONDITIONAL','REJECTED'])->count();
@endphp

{{-- Page toolbar --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Assigned Inspections</h4>
    <a class="btn btn-success" href="{{ url('inspection/new') }}">
        <i class="fa fa-plus"></i> New Inspection
    </a>
</div>

{{-- Metric Cards --}}
<div class="row g-2 mb-3">
    <div class="col-6 col-md-3">
        <div class="card text-center border-secondary">
            <div class="card-body py-2">
                <div class="fs-4 fw-bold">{{ $total }}</div>
                <div class="text-muted small">Total</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center border-primary">
            <div class="card-body py-2">
                <div class="fs-4 fw-bold text-primary">{{ $inProgress }}</div>
                <div class="text-muted small">In Progress</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center border-info">
            <div class="card-body py-2">
                <div class="fs-4 fw-bold text-info">{{ $submitted }}</div>
                <div class="text-muted small">Submitted</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card text-center border-success">
            <div class="card-body py-2">
                <div class="fs-4 fw-bold text-success">{{ $reviewed }}</div>
                <div class="text-muted small">Reviewed</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped display table-sm" id="inspectiondt">
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Farm</th>
                    <th>Last Updated</th>
                    <th>Status</th>
                    <th>Score</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
            @forelse ($inspections as $inspection)
                @php
                    $pct = ($inspection->max_score > 0)
                        ? round(($inspection->score / $inspection->max_score) * 100, 2)
                        : 0;
                @endphp
                <tr>
                    <td>{{ $inspection->reportname }}</td>
                    <td>{{ $inspection->farmcode }} | {{ $inspection->farmname }}</td>
                    <td>{{ $inspection->updated_at }}</td>
                    <td>
                        <span class="badge {{ $statusBadge($inspection->inspectionstate) }}">
                            {{ $inspection->inspectionstate }}
                        </span>
                    </td>
                    <td style="min-width:90px">
                        @if ($inspection->max_score == 0)
                            <span class="text-muted">0.00%</span>
                        @else
                            <span class="fw-bold" style="color:{{ $scoreColor($pct) }}">{{ $pct }}%</span>
                            <div class="score-bar mt-1">
                                <div class="score-fill" style="width:{{ $pct }}%; background:{{ $scoreColor($pct) }}"></div>
                            </div>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-wrap gap-1">
                            {{-- Continue button for PENDING/ACTIVE --}}
                            @if (in_array($inspection->inspectionstate, ['PENDING','ACTIVE','SUBMITTED']))
                                <form action="{{ url('inspection/continue') }}" method="POST">
                                    @csrf
                                    <input type="hidden" value="{{ $inspection->id }}" name="farmid">
                                    <input type="hidden" value="{{ $inspection->iid }}" name="inspectionid">
                                    <input type="hidden" value="{{ $inspection->reportid }}" name="reportid">
                                    <button type="submit" class="btn btn-warning btn-sm" title="Continue Inspection">
                                        <i class="fa fa-pencil-square-o"></i>
                                    </button>
                                </form>
                            @endif

                            {{-- View + PDF in a single form --}}
                            <form action="{{ url('inspection/continue') }}" method="POST">
                                @csrf
                                <input type="hidden" value="{{ $inspection->id }}" name="farmid">
                                <input type="hidden" value="{{ $inspection->iid }}" name="inspectionid">
                                <input type="hidden" value="{{ $inspection->reportid }}" name="reportid">
                                <button class="btn btn-success btn-sm" name="viewsheet" title="View Inspection Sheet">
                                    <i class="fa fa-eye"></i>
                                </button>
                                <button class="btn btn-danger btn-sm" name="printsheet" title="Download PDF">
                                    <i class="fa fa-file-pdf-o"></i>
                                </button>
                            </form>

                            {{-- Cancel (ACTIVE only) --}}
                            @if ($inspection->inspectionstate === 'ACTIVE')
                                <form action="{{ route('icancel') }}" method="POST"
                                      onsubmit="return confirm('Cancel this inspection?');">
                                    @csrf
                                    <input type="hidden" value="{{ $inspection->iid }}" name="inspectionid">
                                    <button class="btn btn-outline-danger btn-sm" title="Cancel Inspection">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </form>

                                <button type="button" class="btn btn-outline-secondary btn-sm"
                                        data-bs-toggle="modal" data-bs-target="#changeDateModal"
                                        data-bs-whatever="{{ $inspection->farmcode }}"
                                        data-bs-orgdate="{{ $inspection->inspectiondate }}"
                                        data-bs-reportname="{{ $inspection->reportname }}"
                                        data-bs-inspectionid="{{ $inspection->iid }}"
                                        title="Change Inspection Date">
                                    <i class="fa fa-calendar"></i>
                                </button>
                            @endif
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-4">
                        <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                        No inspections conducted yet.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Change Date Modal --}}
<div class="modal fade" id="changeDateModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Inspection Date</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="{{ route('changedate') }}">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Inspection Type</label>
                        <input type="text" disabled class="form-control itype">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Date</label>
                        <input type="text" disabled class="form-control orgdate">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Date</label>
                        <input type="date" class="form-control" required name="newinspectiondate">
                        <input type="hidden" class="inspectionid" name="inspectionid">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var changeDateModal = document.getElementById('changeDateModal');
    changeDateModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget;
        changeDateModal.querySelector('.modal-title').textContent =
            'Update Inspection Date for ' + button.getAttribute('data-bs-whatever');
        changeDateModal.querySelector('.itype').value   = button.getAttribute('data-bs-reportname');
        changeDateModal.querySelector('.orgdate').value = button.getAttribute('data-bs-orgdate');
        changeDateModal.querySelector('.inspectionid').value = button.getAttribute('data-bs-inspectionid');
    });
</script>
<script>
    $(document).ready(function () {
        $('#inspectiondt').DataTable({
            dom: 'Bfrtip',
            order: [[2, 'desc']],
            buttons: [{
                extend: 'excelHtml5',
                title: 'Inspections',
                exportOptions: { columns: [0,1,2,3,4] }
            }]
        });
    });
</script>
</x-layouts.app>
