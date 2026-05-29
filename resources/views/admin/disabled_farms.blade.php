<x-layouts.app>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

{{-- Page toolbar --}}
<div class="d-flex justify-content-between align-items-center mb-3">
    <div>
        <h4 class="mb-0"><i class="fa fa-ban text-secondary me-2"></i>Disabled Farms</h4>
        <small class="text-muted">These farms are hidden from all listings and inspectors. Re-enable to restore visibility.</small>
    </div>
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

{{-- Summary banner --}}
<div class="alert {{ $farmlist->isEmpty() ? 'alert-success' : 'alert-warning' }} mb-3 py-2">
    <i class="fa fa-info-circle"></i>
    @if ($farmlist->isEmpty())
        No disabled farms on record.
    @else
        <strong>{{ $farmlist->count() }}</strong> farm{{ $farmlist->count() === 1 ? '' : 's' }} currently disabled.
        These farms are invisible to inspectors and excluded from all counts and reports.
    @endif
</div>

<div class="card">
    <div class="card-body table-responsive">
        <table class="table table-striped table-hover table-sm display" id="disabledFarmsTable">
            <thead>
                <tr>
                    <th>Farm Code</th>
                    <th>Farm Name</th>
                    <th>Community</th>
                    <th>Crop</th>
                    <th>Last Inspector</th>
                    <th>Last Inspection</th>
                    <th>Re-enable</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($farmlist as $farm)
                    <tr>
                        <td>{{ $farm->farmcode }}</td>
                        <td>
                            <a href="/farm/view?id={{ $farm->farmcode }}" class="text-decoration-none">
                                {{ $farm->farmname }}
                            </a>
                        </td>
                        <td>{{ $farm->community }}</td>
                        <td>{{ $farm->crop ?: '—' }}</td>
                        <td>{{ $farm->getinspectorName() }}</td>
                        <td>{{ $farm->lastinspection ?: '—' }}</td>
                        <td>
                            <button type="button"
                                    class="btn btn-success btn-sm"
                                    data-bs-toggle="modal"
                                    data-bs-target="#reEnableModal"
                                    data-farm-id="{{ $farm->id }}"
                                    data-farm-name="{{ $farm->farmname }}"
                                    data-farm-code="{{ $farm->farmcode }}">
                                <i class="fa fa-check-circle"></i> Re-enable
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">
                            <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                            No disabled farms found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- Re-enable Modal --}}
<div class="modal fade" id="reEnableModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Re-enable Farm</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('farm.reenable') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">
                        Confirm you want to re-enable this farm and choose its restored state.
                    </p>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Farm</label>
                        <input type="text" class="form-control" id="modalFarmName" readonly>
                        <input type="hidden" name="farmid" id="modalFarmId">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Restore to State</label>
                        <select name="newstate" class="form-select" required>
                            <option value="ACTIVE">ACTIVE — Farm is fully active and visible</option>
                            <option value="PENDING">PENDING — Farm requires onboarding before becoming active</option>
                        </select>
                        <div class="form-text">
                            Choose <strong>PENDING</strong> if the farm needs a new farm entrance before inspections can resume.
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-check-circle"></i> Re-enable Farm
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.getElementById('reEnableModal').addEventListener('show.bs.modal', function (event) {
        var btn = event.relatedTarget;
        document.getElementById('modalFarmName').value = btn.getAttribute('data-farm-name')
            + ' (' + btn.getAttribute('data-farm-code') + ')';
        document.getElementById('modalFarmId').value = btn.getAttribute('data-farm-id');
    });
</script>
<script>
    $(document).ready(function () {
        $('#disabledFarmsTable').DataTable({
            dom: 'Bfrtip',
            order: [[2, 'asc']],
            buttons: [{
                extend: 'excelHtml5',
                title: 'Disabled_Farms',
                exportOptions: { columns: [0, 1, 2, 3, 4, 5] }
            }]
        });
    });
</script>
</x-layouts.app>
