<x-layouts.app>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

@php
    $year = date('Y');

    $statusBadge = fn($state) => match($state) {
        'APPROVED'    => 'bg-success',
        'CONDITIONAL' => 'bg-warning text-dark',
        'REJECTED'    => 'bg-danger',
        'SUBMITTED'   => 'bg-primary',
        'PENDING'     => 'bg-info text-dark',
        'ACTIVE'      => 'bg-success',
        default       => 'bg-secondary',
    };

    $total       = $reportquestions->count();
    $submitted   = $reportquestions->where('inspectionstate', 'SUBMITTED')->count();
    $approved    = $reportquestions->where('inspectionstate', 'APPROVED')->count();
    $conditional = $reportquestions->where('inspectionstate', 'CONDITIONAL')->count();
    $rejected    = $reportquestions->where('inspectionstate', 'REJECTED')->count();
@endphp

<style>
    .metric-card   { border-left: 4px solid #198754; }
    .metric-num    { font-size: 1.8rem; font-weight: 700; line-height: 1.1; }
    .metric-sub    { font-size: .75rem; color: #6c757d; }
    .page-toolbar  { display: flex; align-items: center; justify-content: space-between;
                     flex-wrap: wrap; gap: .5rem; margin-bottom: 1.25rem; }
    .page-title    { font-size: 1.25rem; font-weight: 700; color: #212529; margin: 0; }
    .filter-label  { font-size: .72rem; font-weight: 700; text-transform: uppercase;
                     letter-spacing: .05em; color: #495057; margin-bottom: 3px; }
    .score-bar     { height: 5px; background: #e9ecef; border-radius: 3px;
                     min-width: 60px; margin-top: 3px; }
    .score-fill    { height: 100%; border-radius: 3px; }
    .active-pill   { display: inline-flex; align-items: center; gap: 4px; cursor: pointer;
                     font-size: .72rem; }
    .active-pill:hover { opacity: .8; }
    @media (max-width: 576px) { th, td { font-size: 0.75rem; padding: 0.25rem; } }
    .table td { word-wrap: break-word; }
</style>

{{-- ── VALIDATION ERRORS ─────────────────────────────────────────────────── --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

{{-- ── PAGE TOOLBAR ─────────────────────────────────────────────────────── --}}
<div class="page-toolbar">
    <h5 class="page-title">
        <i class="fa fa-clipboard me-2 text-primary"></i>Inspection Review
    </h5>
</div>

{{-- ── METRIC CARDS ─────────────────────────────────────────────────────── --}}
<div class="row g-3 mb-4">
    <div class="col-6 col-md">
        <div class="card metric-card shadow-sm h-100">
            <div class="card-body">
                <div class="metric-sub mb-1">Total</div>
                <div class="metric-num text-success">{{ $total }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#0d6efd">
            <div class="card-body">
                <div class="metric-sub mb-1">Submitted</div>
                <div class="metric-num" style="color:#0d6efd">{{ $submitted }}</div>
                <div style="font-size:.7rem;color:#adb5bd">Awaiting review</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card metric-card shadow-sm h-100">
            <div class="card-body">
                <div class="metric-sub mb-1">Approved</div>
                <div class="metric-num text-success">{{ $approved }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#fd7e14">
            <div class="card-body">
                <div class="metric-sub mb-1">Conditional</div>
                <div class="metric-num" style="color:#fd7e14">{{ $conditional }}</div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md">
        <div class="card metric-card shadow-sm h-100" style="border-left-color:#dc3545">
            <div class="card-body">
                <div class="metric-sub mb-1">Rejected</div>
                <div class="metric-num text-danger">{{ $rejected }}</div>
            </div>
        </div>
    </div>
</div>

{{-- ── FILTER PANEL ──────────────────────────────────────────────────────── --}}
<div class="card shadow-sm mb-3">
    <div class="card-header"
         style="font-size:.85rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em">
        <i class="fa fa-filter me-2 text-primary"></i>Filter Inspections
    </div>
    <div class="card-body pb-2">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="filter-label">Season</div>
                    <select id="f_season" class="form-select form-select-sm">
                        <option value="">All Seasons</option>
                        @forelse ($seasons as $season)
                            <option value="{{ $season->season }}">{{ $season->season }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="filter-label">Report Type</div>
                    <select id="f_report" class="form-select form-select-sm">
                        <option value="">All Reports</option>
                        @forelse ($reports as $report)
                            <option value="{{ $report->reportname }}">{{ $report->reportname }}</option>
                        @empty
                        @endforelse
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="filter-label">Status</div>
                    <select id="f_status" class="form-select form-select-sm">
                        <option value="">All Statuses</option>
                        <option value="SUBMITTED">SUBMITTED</option>
                        <option value="APPROVED">APPROVED</option>
                        <option value="CONDITIONAL">CONDITIONAL</option>
                        <option value="REJECTED">REJECTED</option>
                        <option value="ACTIVE">ACTIVE</option>
                        <option value="PENDING">PENDING</option>
                    </select>
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="filter-label">Farm Name</div>
                    <input type="text" id="f_farm" class="form-control form-control-sm"
                           placeholder="Search farm…">
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <div class="filter-label">Filed By</div>
                    <input type="text" id="f_filedby" class="form-control form-control-sm"
                           placeholder="Search inspector…">
                </div>
                <div class="col-12 col-sm-6 col-md-2">
                    <button id="applyFilters" class="btn btn-primary btn-sm w-100">
                        <i class="fa fa-filter me-1"></i> Apply
                    </button>
                    <button id="clearFilters" class="btn btn-outline-secondary btn-sm w-100 mt-1">
                        <i class="fa fa-times me-1"></i> Reset
                    </button>
                </div>
            </div>
            {{-- Active filter pills --}}
            <div id="activeFilters" class="mt-2 d-flex flex-wrap gap-1"></div>
            <div id="resultCount" class="mt-1 text-muted" style="font-size:.72rem"></div>
        </div>
</div>

{{-- ── GENERATE SUMMARY REPORT ───────────────────────────────────────────── --}}
<div class="card shadow-sm mb-3">
    <div class="card-header"
         style="font-size:.85rem;font-weight:700;text-transform:uppercase;letter-spacing:.05em">
        <i class="fa fa-bar-chart me-2 text-success"></i>Generate Summary Report
    </div>
    <div class="card-body">
            <form action="{{ route('summarypage') }}" method="get">
                <div class="row g-3 align-items-end">
                    <div class="col-12 col-md-3">
                        <div class="filter-label form-label">Season</div>
                        <select class="form-select" name="season">
                            @forelse ($seasons as $season)
                                <option value="{{ $season->season }}">{{ $season->season }}</option>
                            @empty
                                <option value="" disabled>No Season Available</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="filter-label form-label">Report</div>
                        <select class="form-select" name="report">
                            @forelse ($reports as $report)
                                <option value="{{ $report->id }}">{{ $report->reportname }}</option>
                            @empty
                                <option value="" disabled>No Report Available</option>
                            @endforelse
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <div class="filter-label form-label">Report State</div>
                        <select class="form-select" name="reportstate">
                            <option value="ALL">ALL</option>
                            <option value="APPROVED">APPROVED</option>
                            <option value="CONDITIONAL">APPROVED WITH CONDITIONS</option>
                            <option value="PENDING">PENDING</option>
                            <option value="SUBMITTED">SUBMITTED</option>
                            <option value="REJECTED">REJECTED</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-3">
                        <button class="btn btn-success w-100" type="submit">
                            <i class="fa fa-bar-chart me-1"></i> Generate Summary
                        </button>
                    </div>
                </div>
            </form>
        </div>
</div>

{{-- ── INSPECTION TABLE ──────────────────────────────────────────────────── --}}
<div class="card shadow-sm">
    <div class="card-body table-responsive">
        <table class="table display table-sm table-striped table-hover align-middle"
               id="reports" style="width:100%">
            <thead class="table-dark">
                <tr>
                    <th style="width:8%">Season</th>
                    <th style="width:12%">Report Type</th>
                    <th style="width:8%">Filed By</th>
                    <th style="width:15%">Farm Name</th>
                    <th style="width:8%">Score</th>
                    <th style="width:7%">Status</th>
                    <th style="width:8%">Error Check</th>
                    <th style="width:12%">IMS Comment(s)</th>
                    <th style="width:6%">Verification</th>
                    <th style="width:8%">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($reportquestions as $inspection)
                    <tr>
                        <td class="small">{{ $inspection->season }}</td>
                        <td class="small">{{ $inspection->getreport()->reportname ?? 'N/A'}}</td>
                        <td class="small">{{ $inspection->reportinspectorname()->iname }}</td>
                        <td class="small fw-semibold">{{ $inspection->getfarm()->farmname }}</td>
                        <td>
                            @if ($inspection->getreport()->max_score ?? 0 == 0)
                                <span class="badge bg-secondary">Check Report</span>
                            @else
                                @php
                                    $pct      = ($inspection->score / $inspection->getreport()->max_score) * 100;
                                    $barColor = $pct >= 70 ? '#198754' : ($pct >= 50 ? '#fd7e14' : '#dc3545');
                                @endphp
                                <div style="font-size:.78rem;font-weight:600;color:{{ $barColor }}">
                                    {{ number_format($pct, 1) }}%
                                </div>
                                <div class="score-bar">
                                    <div class="score-fill"
                                         style="width:{{ $pct }}%;background:{{ $barColor }}"></div>
                                </div>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $statusBadge($inspection->inspectionstate) }}"
                                  style="font-size:.7rem">
                                {{ $inspection->inspectionstate }}
                            </span>
                        </td>
                        <td class="d-none d-sm-table-cell small">
                            @if (!empty($inspection->getplotdetails()) || $inspection->inspectionstate == 'ACTIVE')
                                @if (strpos($inspection->getreport()->reportname, 'Entrance') != false)
                                    <span class="text-success">
                                        <i class="fa fa-check-circle me-1"></i>No Issues
                                    </span>
                                @endif
                            @else
                                @if (strpos($inspection->getreport()->reportname, 'Entrance') != false)
                                    <span class="text-danger">
                                        <i class="fa fa-exclamation-circle me-1"></i>Check Plots
                                    </span>
                                @endif
                            @endif
                        </td>
                        <td>
                            <form action="iapprove" method="POST">
                                @csrf
                                <input type="hidden" name="iid" value="{{ $inspection->id }}">
                                <textarea class="form-control form-control-sm" name="comments"
                                          rows="2">{{ $inspection->comments }}</textarea>
                        </td>
                        <td class="small">
                            <b>{{ $inspection->getverificationstatus() }}</b>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                {{-- View sheet --}}
                                <button name="viewsheet" type="submit" class="btn btn-success btn-sm"
                                        title="View Inspection Sheet">
                                    <i class="fa fa-eye"></i>
                                </button>

                                {{-- Verify (conditional, unverified) --}}
                                @if (in_array($inspection->inspectionstate, ['CONDITIONAL']) && $inspection->verifiedby == null)
                                    <button type="button" class="btn btn-info btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#verifyModal"
                                            data-bs-whatever="{{ $inspection->farmname }} : {{ $inspection->reportname }}"
                                            data-bs-whatever2="{{ $inspection->id }}"
                                            data-bs-conditions="{{ $inspection->conditions }}"
                                            title="Verify Inspection">
                                        <i class="fa fa-search-plus"></i>
                                    </button>
                                @endif

                                {{-- Approve / Reject / Delete (submitted/pending/active) --}}
                                @if (in_array($inspection->inspectionstate, ['SUBMITTED', 'PENDING', 'ACTIVE']))
                                    <button type="submit" name="approvebtn" class="btn btn-success btn-sm"
                                            title="Approve (IMS Manager)">
                                        <i class="fa fa-check-square-o"></i>
                                    </button>
                                    <button type="submit" name="rejectbtn" class="btn btn-danger btn-sm"
                                            title="Reject (IMS Manager)">
                                        <i class="fa fa-times-circle"></i>
                                    </button>
                                    <button type="submit" name="deletetbtn" class="btn btn-danger btn-sm"
                                            title="Delete Inspection">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endif

                                {{-- Evaluation Committee Decision --}}
                                @if (strpos($inspection->getreport()->reportname, 'Entrance') == false && $inspection->ecomm_checked == 1)
                                    <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#committeeModal"
                                            data-bs-whatever="{{ $inspection->farmname }} : {{ $inspection->reportname }}"
                                            data-bs-whatever2="{{ $inspection->id }}"
                                            title="Evaluation Committee Decision">
                                        <i class="fa fa-check-square-o"></i>
                                    </button>
                                @endif
                            </div>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center text-muted py-5">
                            <i class="fa fa-inbox fa-2x mb-2 d-block"></i>
                            No inspection records found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

{{-- ── MODAL: Evaluation Committee Decision ─────────────────────────────── --}}
<div class="modal fade" id="committeeModal" tabindex="-1"
     aria-labelledby="committeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="committeeModalLabel">
                    <i class="fa fa-check-square-o me-2"></i>Evaluation Committee Decision
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="iapprove">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Inspection Report</label>
                        <input type="text" readonly class="form-control fcode" name="report_name">
                        <input type="hidden" class="fiid" name="iid">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Committee Decision</label>
                        <select name="ecdecision" required class="form-select">
                            <option value="APPROVED">APPROVED</option>
                            <option value="CONDITIONAL">APPROVED WITH CONDITIONS</option>
                            <option value="REJECTED">REJECTED</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Conditions / Comments</label>
                        <textarea class="form-control" name="apprconditions" rows="6"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Committee Members</label>
                        <div class="border rounded p-2">
                            @forelse ($approvalcommittees as $acmember)
                                <div class="form-check">
                                    <input type="checkbox" name="acmembers[]"
                                           value="{{ $acmember->id }}"
                                           class="form-check-input"
                                           id="ac_{{ $acmember->id }}">
                                    <label class="form-check-label" for="ac_{{ $acmember->id }}">
                                        <b>{{ $acmember->name }}</b>
                                        <span class="text-muted small">({{ $acmember->position }})</span>
                                    </label>
                                </div>
                            @empty
                                <p class="text-muted mb-0 small">
                                    No committee members for {{ $year }} on record.
                                </p>
                            @endforelse
                        </div>
                        @if ($approvalcommittees->isNotEmpty())
                            <div class="form-check mt-2">
                                <input type="checkbox" name="addcommittee"
                                       class="form-check-input" id="addAllCommittee">
                                <label class="form-check-label" for="addAllCommittee">
                                    Add <u>ALL</u> active committee members to note
                                </label>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" name="approvewithcondition">
                        <i class="fa fa-check me-1"></i> Save Decision
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ── MODAL: Verify Conditions ─────────────────────────────────────────── --}}
<div class="modal fade" id="verifyModal" tabindex="-1"
     aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyModalLabel">
                    <i class="fa fa-search-plus me-2"></i>Verify Conditions
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="post" action="iapprove">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Inspection Report</label>
                        <input type="text" readonly class="form-control vcode" name="report_name_verify">
                        <input type="hidden" class="fiid" name="iid">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Approval Conditions</label>
                        <textarea class="form-control" readonly name="apprconditions"
                                  id="approveconditions_verify" rows="5"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">
                            Verification Notes
                            <span class="text-muted fw-normal">(Optional)</span>
                        </label>
                        <textarea class="form-control" name="verify_note" rows="4"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Verification Date</label>
                        <input type="date" name="verify_date" class="form-control"
                               value="{{ date('Y-m-d') }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" name="verifyinspection">
                        <i class="fa fa-check me-1"></i> Verify
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // ── Modal: Evaluation Committee Decision ──────────────────────────────
    var committeeModal = document.getElementById('committeeModal');
    committeeModal.addEventListener('show.bs.modal', function (event) {
        var button   = event.relatedTarget;
        var label    = button.getAttribute('data-bs-whatever');
        var reportid = button.getAttribute('data-bs-whatever2');
        committeeModal.querySelector('.modal-title').innerHTML =
            '<i class="fa fa-check-square-o me-2"></i>Evaluation Committee Decision';
        committeeModal.querySelector('.fcode').value = label;
        committeeModal.querySelector('.fiid').value  = reportid;
    });

    // ── Modal: Verify Conditions ──────────────────────────────────────────
    var verifyModal = document.getElementById('verifyModal');
    verifyModal.addEventListener('show.bs.modal', function (event) {
        var button     = event.relatedTarget;
        var label      = button.getAttribute('data-bs-whatever');
        var reportid   = button.getAttribute('data-bs-whatever2');
        var conditions = button.getAttribute('data-bs-conditions');
        verifyModal.querySelector('.modal-title').innerHTML =
            '<i class="fa fa-search-plus me-2"></i>Verify Conditions &mdash; ' + label;
        verifyModal.querySelector('.vcode').value                    = label;
        verifyModal.querySelector('.fiid').value                     = reportid;
        verifyModal.querySelector('#approveconditions_verify').value = conditions;
    });
</script>

<script>
$(document).ready(function () {

    // ── DataTable init ────────────────────────────────────────────────────
    var table = $('#reports').DataTable({
        dom: 'Bfrtip',
        pageLength: 200,
        order: [[0, 'desc']],
        stateSave: false,
        buttons: [{
            extend: 'excelHtml5',
            title: 'Inspection_Report_Review',
            exportOptions: { columns: ':not(:last-child)' }
        }]
    });

    // Column indices (0-based)
    var COL = { season: 0, reportType: 1, filedBy: 2, farmName: 3, status: 5 };

    // ── Apply all active filters to DataTable ─────────────────────────────
    function applyFilters() {
        var season   = $('#f_season').val().trim();
        var report   = $('#f_report').val().trim();
        var status   = $('#f_status').val().trim();
        var farmName = $('#f_farm').val().trim();
        var filedBy  = $('#f_filedby').val().trim();

        // Exact-match for dropdowns (regex anchors); partial-match for text inputs
        var escape = $.fn.dataTable.util.escapeRegex;

        table.column(COL.season)
             .search(season   ? '^' + escape(season)  + '$' : '', true, false);
        table.column(COL.reportType)
             .search(report   ? '^' + escape(report)  + '$' : '', true, false);
        table.column(COL.status)
             .search(status   ? '^' + escape(status)  + '$' : '', true, false);
        table.column(COL.farmName)
             .search(farmName, false, false);
        table.column(COL.filedBy)
             .search(filedBy,  false, false);
        table.draw();
    }

    // ── Render active filter pills below the controls ─────────────────────
    function renderPills() {
        var defs = [
            { id: 'f_season',  label: 'Season',   val: $('#f_season').val()  },
            { id: 'f_report',  label: 'Report',   val: $('#f_report').val()  },
            { id: 'f_status',  label: 'Status',   val: $('#f_status').val()  },
            { id: 'f_farm',    label: 'Farm',     val: $('#f_farm').val()    },
            { id: 'f_filedby', label: 'Filed By', val: $('#f_filedby').val() },
        ];

        var html = defs.filter(d => d.val).map(d =>
            '<span class="badge bg-primary active-pill" data-target="' + d.id + '">' +
            d.label + ': ' + $('<span>').text(d.val).html() +
            ' <i class="fa fa-times-circle"></i></span>'
        ).join('');

        $('#activeFilters').html(html);

        $('#activeFilters .badge').on('click', function () {
            var id = $(this).data('target');
            $('#' + id).is('select') ? $('#' + id).val('') : $('#' + id).val('');
            applyFilters();
            renderPills();
            updateCount();
        });
    }

    // ── Update record count display ───────────────────────────────────────
    function updateCount() {
        var info = table.page.info();
        $('#resultCount').text(
            'Showing ' + info.recordsDisplay.toLocaleString() +
            ' of '     + info.recordsTotal.toLocaleString()   + ' records'
        );
    }

    // ── Bind controls ─────────────────────────────────────────────────────
    $('#applyFilters').on('click', function () {
        applyFilters();
        renderPills();
        updateCount();
    });

    // Selects apply immediately on change
    $('#f_season, #f_report, #f_status').on('change', function () {
        applyFilters();
        renderPills();
        updateCount();
    });

    // Text inputs apply on keyup (debounced)
    var debounceTimer;
    $('#f_farm, #f_filedby').on('keyup', function () {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(function () {
            applyFilters();
            renderPills();
            updateCount();
        }, 250);
    });

    $('#clearFilters').on('click', function () {
        $('#f_season, #f_report, #f_status').val('');
        $('#f_farm, #f_filedby').val('');
        table.columns().search('').draw();
        renderPills();
        updateCount();
    });

    // Keep count in sync whenever DataTable redraws for any reason
    table.on('draw', updateCount);
    updateCount();
});
</script>

</x-layouts.app>
