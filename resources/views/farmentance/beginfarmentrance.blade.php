<x-layouts.app>
@php
    $year = date('Y');
    if (empty($farmentrance->getcropdeliver())) {
        $prevseason = $seasonrange[0];
        $prevcropdelivered = null;
    } else {
        $prevseason = $farmentrance->getcropdeliver()->season;
        $prevcropdelivered = $farmentrance->getcropdeliver()->value;
    }
    if (empty($farmentrance->getcropproduced())) {
        $prevcropproduced = null;
    } else {
        $prevcropproduced = $farmentrance->getcropproduced()->value;
    }
@endphp

<style>
.fe-section-card {
  border: 1px solid #e9ecef;
  border-radius: 12px;
  box-shadow: 0 1px 4px rgba(0,0,0,.06);
  margin-bottom: 1.25rem;
  overflow: hidden;
}
.fe-section-header {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 10px 18px;
  background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);
  border-bottom: 1px solid #e9ecef;
}
.fe-section-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 28px;
  height: 28px;
  border-radius: 50%;
  background: #198754;
  color: #fff;
  font-size: 0.75rem;
  font-weight: 800;
  flex-shrink: 0;
}
.fe-section-title {
  font-size: 0.78rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.8px;
  color: #495057;
  margin: 0;
}
.bio-field label {
  font-size: 0.72rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  color: #6c757d;
  margin-bottom: 3px;
  display: block;
}
.bio-field .form-control {
  font-size: 0.88rem;
  background-color: #f8f9fa;
  border-color: #e9ecef;
  border-radius: 7px;
}
.fe-table thead th {
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  background: #f1f3f5;
  color: #495057;
  border-bottom: 2px solid #dee2e6;
  white-space: nowrap;
  padding: 10px 12px;
}
.fe-table tbody td {
  font-size: 0.83rem;
  vertical-align: middle;
  padding: 8px 12px;
}
.fe-table tbody tr:hover { background: #f8f9fa; }
.fe-add-row { background: #f0fdf4 !important; }
.fe-add-row td { padding: 8px 12px; }
.fe-add-row .form-control { font-size: 0.82rem; border-radius: 6px; }
.btn-action-sm {
  font-size: 0.72rem;
  padding: 4px 10px;
  border-radius: 6px;
  font-weight: 600;
}
.sig-box {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  gap: 4px;
}
.sig-box img {
  border: 2px solid #dee2e6;
  border-radius: 8px;
  background: #fff;
}
</style>

{{-- Validation errors --}}
@if ($errors->any())
<div class="alert alert-danger alert-dismissible fade show mb-3" style="border-radius:10px; font-size:.85rem;" role="alert">
  <ul class="mb-0 ps-3">
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Page Header --}}
<div class="mb-4">
  <h4 class="fw-bold mb-0">
    <i class="fa fa-file-text-o me-2 text-success"></i>
    Annex 5: Field Entrance Form
  </h4>
  <p class="text-muted mb-0" style="font-size:.83rem;">
    UEBT / RA / Mabagrown &mdash;
    <span class="fw-semibold text-dark">{{ $currentseason }} Season</span>
  </p>
</div>

{{-- ─────────────────────────────────────────────────────── --}}
{{-- SECTION A: BIO-DATA --}}
{{-- ─────────────────────────────────────────────────────── --}}
<div class="fe-section-card">
  <div class="fe-section-header">
    <span class="fe-section-badge">A</span>
    <h6 class="fe-section-title">Field Operator Bio-Data</h6>
  </div>
  <div class="card-body p-3">
    <div class="row g-3">

      <div class="col-6 col-sm-4 col-md-3 bio-field">
        <label>Surname</label>
        <input type="text" disabled class="form-control form-control-sm" value="{{ $farmerdetail->surname }}">
      </div>
      <div class="col-6 col-sm-4 col-md-3 bio-field">
        <label>Other Name</label>
        <input type="text" disabled class="form-control form-control-sm" value="{{ $farmerdetail->fname }}">
      </div>
      <div class="col-6 col-sm-4 col-md-2 bio-field">
        <label>Gender</label>
        <input type="text" disabled class="form-control form-control-sm" value="{{ $farmerdetail->gender }}">
      </div>
      <div class="col-6 col-sm-4 col-md-2 bio-field">
        <label>Farmer Code</label>
        <input type="text" disabled class="form-control form-control-sm" value="{{ $farmerdetail->farmcode }}">
      </div>
      <div class="col-6 col-sm-4 col-md-2 bio-field">
        <label>ID Number</label>
        <input type="text" disabled class="form-control form-control-sm" value="{{ $farmerdetail->nationalidnumber }}">
      </div>

      <div class="col-6 col-sm-4 col-md-2 bio-field">
        <label>Year of Birth</label>
        <input type="number" disabled class="form-control form-control-sm" value="{{ $farmerdetail->yob }}">
      </div>
      <div class="col-6 col-sm-4 col-md-2 bio-field">
        <label>Phone Number</label>
        <input type="text" disabled name="phonenumber" id="phonenumber" class="form-control form-control-sm" value="{{ $farmerdetail->phonenumber }}">
      </div>
      <div class="col-6 col-sm-4 col-md-2 bio-field">
        <label>Household Size</label>
        <input type="text" disabled name="householdsize" id="householdsize" class="form-control form-control-sm" value="{{ $farmerdetail->householdsize }}">
      </div>
      <div class="col-12 col-sm-8 col-md-4 bio-field">
        <label>Address</label>
        <textarea disabled name="address" id="address" class="form-control form-control-sm" rows="2">{{ $farmerdetail->address }}</textarea>
      </div>

      <div class="col-6 col-sm-4 col-md-3 bio-field">
        <label>Date of Last Inspection</label>
        <input type="text" disabled name="dateoflastinspection" id="dateoflastinspection" class="form-control form-control-sm" value="{{ $farmentrance->lastinspection }}">
      </div>
      <div class="col-6 col-sm-4 col-md-3 bio-field">
        <label>Outcome of Last Inspection</label>
        <input type="text" disabled name="outcomeoflastinspection" class="form-control form-control-sm" value="{{ $farmentrance->inspectionresult }})">
      </div>
      <div class="col-6 col-sm-4 col-md-2 bio-field">
        <label>Crop</label>
        <input type="text" disabled name="nameofcrop" id="crop" class="form-control form-control-sm" value="{{ $farmerdetail->crop }}">
      </div>
      <div class="col-6 col-sm-4 col-md-2 bio-field">
        <label>Crop Variety</label>
        <input type="text" disabled name="varietyofcrop" id="varietycrop" class="form-control form-control-sm" value="{{ $farmerdetail->cropvariety }}">
      </div>
      <div class="col-12 col-sm-4 col-md-2 bio-field sig-box">
        @if (!empty($farmerdetail->signaturepath))
        <img src="{{ url('/storage/'.$farmerdetail->signaturepath) }}" alt="Signature" width="80" height="50" style="object-fit:contain;">
        @endif
        <label>Farmer's Signature</label>
      </div>

    </div>
  </div>
</div>

{{-- ─────────────────────────────────────────────────────── --}}
{{-- SECTION B: PRODUCTION HISTORY --}}
{{-- ─────────────────────────────────────────────────────── --}}
<div class="fe-section-card">
  <div class="fe-section-header">
    <span class="fe-section-badge">B</span>
    <h6 class="fe-section-title">Production History</h6>
  </div>
  <div class="table-responsive">
    <table class="table fe-table mb-0">
      <thead>
        <tr>
          <th>Ginger Plot Name</th>
          <th>Farm Size (Ha)</th>
          <th>Estimated Yield (Kg)</th>
          <th>Lat. N</th>
          <th>Long. E</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($farmplots as $farmplot)
        <tr>
          <td class="fw-semibold">{{ $farmplot->plotname }}</td>
          <td>{{ $farmplot->fuarea }}</td>
          <td>{{ $farmplot->estimatedyield }}</td>
          <td style="font-family:monospace; font-size:.78rem;">{{ $farmplot->fulatitude }}</td>
          <td style="font-family:monospace; font-size:.78rem;">{{ $farmplot->fulongitude }}</td>
          <td class="text-center">
            <a href="{{ route('disablefarm', ['farmcode' => $farmerdetail->farmcode, 'fuid' => $farmplot->id]) }}"
               class="btn btn-outline-danger btn-action-sm">
              <i class="fa fa-times me-1"></i>Disable
            </a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center py-3 text-muted" style="font-size:.83rem;">
            <i class="fa fa-seedling me-1"></i> No farm plots on record.
          </td>
        </tr>
        @endforelse
        <tr class="fe-add-row">
          <td colspan="5"></td>
          <td class="text-center">
            <form action="{{ route('newfunit') }}" method="get">
              <input type="text" name="farmid" hidden value="{{ $farmerdetail->id }}">
              <input type="text" name="farmentranceid" hidden value="{{ $farmentrance->id }}">
              <button class="btn btn-success btn-action-sm" name="newfunit">
                <i class="fa fa-plus me-1"></i> Add Farm Unit
              </button>
            </form>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</div>

{{-- ─────────────────────────────────────────────────────── --}}
{{-- SECTION C: VOLUME SOLD / DELIVERED --}}
{{-- ─────────────────────────────────────────────────────── --}}
<div class="fe-section-card">
  <div class="fe-section-header">
    <span class="fe-section-badge">C</span>
    <h6 class="fe-section-title">Volume of Certified Crops Sold / Delivered to the Group in Previous Years (Kgs)</h6>
  </div>
  <div class="table-responsive">
    <table class="table fe-table mb-0">
      <thead>
        <tr>
          <th style="width:180px;">Season</th>
          <th>Volume Sold / Delivered (Kgs)</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($farmentrance->getvolumesold() as $volsold)
        <tr>
          <td>{{ $volsold->season }}</td>
          <td>{{ $volsold->value }}</td>
          <td class="text-center">
            <a href="{{ route('disablevolsold', ['farmcode' => $farmerdetail->farmcode, 'vsid' => $volsold->id]) }}"
               class="btn btn-outline-danger btn-action-sm">
              <i class="fa fa-times me-1"></i>Disable
            </a>
          </td>
        </tr>
        @empty
        @endforelse
        <tr class="fe-add-row">
          <form action="{{ route('addvolsold') }}" method="post">
            @csrf
            <td>
              <select name="volseason" id="seasonrange" class="form-select form-select-sm" style="border-radius:6px;">
                @foreach ($seasonrange as $srange)
                <option value="{{ $srange }}">{{ $srange }}</option>
                @endforeach
              </select>
            </td>
            <td>
              <input type="number" step="any" name="volsold" class="form-control form-control-sm" placeholder="Volume in Kgs">
            </td>
            <td class="text-center">
              <input type="text" name="farmcode" hidden value="{{ $farmerdetail->farmcode }}">
              <input type="text" name="farmid" hidden value="{{ $farmerdetail->id }}">
              <button type="submit" class="btn btn-primary btn-action-sm">
                <i class="fa fa-plus me-1"></i> Add
              </button>
            </td>
          </form>
        </tr>
      </tbody>
    </table>
  </div>
</div>

{{-- ─────────────────────────────────────────────────────── --}}
{{-- SECTION D: CROP DELIVERED / PRODUCED --}}
{{-- ─────────────────────────────────────────────────────── --}}
<div class="fe-section-card">
  <div class="fe-section-header">
    <span class="fe-section-badge">D</span>
    <h6 class="fe-section-title">Previous Season Crop Data</h6>
  </div>
  <div class="table-responsive">
    <table class="table fe-table mb-0">
      <thead>
        <tr>
          <th style="width:120px;">Season</th>
          <th>Description</th>
          <th style="width:180px;">Quantity (Kgs)</th>
          <th class="text-center" style="width:120px;">Action</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <form action="{{ route('cropdelivered') }}" method="get">
            @csrf
            <td>{{ $prevseason }}</td>
            <td>Previous year's <strong>({{ $prevseason }})</strong> harvest of certified crop delivered to the group</td>
            <td>
              <input type="number" step="any" name="cropdelivered" id="cropdelivered" class="form-control form-control-sm" value="{{ $prevcropdelivered }}">
            </td>
            <td class="text-center">
              <input type="text" hidden name="prevseason" value="{{ $seasonrange[0] }}">
              <input type="text" hidden name="farmid" value="{{ $farmerdetail->id }}">
              <input type="text" hidden name="farmcode" value="{{ $farmerdetail->farmcode }}">
              <button type="submit" class="btn btn-primary btn-action-sm">
                <i class="fa fa-refresh me-1"></i> Update
              </button>
            </td>
          </form>
        </tr>
        <tr>
          <form action="{{ route('cropproduced') }}" method="get">
            @csrf
            <td>{{ $prevseason }}</td>
            <td>Previous year's <strong>({{ $prevseason }})</strong> estimated total production</td>
            <td>
              <input type="number" step="any" name="cropproduced" class="form-control form-control-sm" value="{{ $prevcropproduced }}">
            </td>
            <td class="text-center">
              <input type="text" hidden name="prevseason" value="{{ $prevseason }}">
              <input type="text" hidden name="farmid" value="{{ $farmerdetail->id }}">
              <input type="text" hidden name="farmcode" value="{{ $farmerdetail->farmcode }}">
              <button type="submit" class="btn btn-primary btn-action-sm">
                <i class="fa fa-refresh me-1"></i> Update
              </button>
            </td>
          </form>
        </tr>
      </tbody>
    </table>
  </div>
</div>

{{-- ─────────────────────────────────────────────────────── --}}
{{-- SECTION E: AGROCHEMICALS --}}
{{-- ─────────────────────────────────────────────────────── --}}
<div class="fe-section-card">
  <div class="fe-section-header">
    <span class="fe-section-badge">E</span>
    <h6 class="fe-section-title">Agrochemicals Used on the Farm Land</h6>
  </div>
  <div class="table-responsive">
    <table class="table fe-table mb-0">
      <thead>
        <tr>
          <th>Name of Herbicide &amp; Fertilizer</th>
          <th>Quantity Applied (Litres / Bags)</th>
          <th>Name of Person Who Applied</th>
          <th>Ha of Ginger Applied On</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($agrochems as $agrochem)
        <tr>
          <td><input type="text" disabled name="herbicide" class="form-control form-control-sm" value="{{ $agrochem->herbicidename }}"></td>
          <td><input type="text" disabled name="herbicideqty" class="form-control form-control-sm" value="{{ $agrochem->quantity }}"></td>
          <td><input type="text" disabled name="herbicideapplier" class="form-control form-control-sm" value="{{ $agrochem->nameofperson }}"></td>
          <td><input type="number" disabled step="any" name="hectareapplied" class="form-control form-control-sm" value="{{ number_format($agrochem->hectaresapplied, 2) }}"></td>
          <td class="text-center">
            <a href="{{ route('disablechems', ['farmcode' => $farmerdetail->farmcode, 'aid' => $agrochem->id]) }}"
               class="btn btn-outline-danger btn-action-sm">
              <i class="fa fa-times me-1"></i>Disable
            </a>
          </td>
        </tr>
        @empty
        @endforelse
        <tr class="fe-add-row">
          <form action="{{ route('addchems') }}" method="post">
            @csrf
            <td><input type="text" required name="herbicide" class="form-control form-control-sm" placeholder="Chemical name..."></td>
            <td><input type="text" required name="herbicideqty" class="form-control form-control-sm" placeholder="Qty..."></td>
            <td><input type="text" required name="herbicideapplier" class="form-control form-control-sm" placeholder="Applicator name..."></td>
            <td><input type="text" required name="hectareapplied" class="form-control form-control-sm" placeholder="0.00"></td>
            <td class="text-center">
              <input type="text" name="farmcode" hidden value="{{ $farmerdetail->farmcode }}">
              <input type="text" name="farmid" hidden value="{{ $farmerdetail->id }}">
              <input type="text" name="farmentrance" hidden value="{{ $farmentrance->id }}">
              <input type="text" name="season" hidden value="{{ $currentseason }}">
              <button type="submit" name="addherbicide" class="btn btn-primary btn-action-sm">
                <i class="fa fa-plus me-1"></i> Add
              </button>
            </td>
          </form>
        </tr>
      </tbody>
    </table>
  </div>
</div>

{{-- ─────────────────────────────────────────────────────── --}}
{{-- SECTION F: OTHER CULTIVATED CROPS --}}
{{-- ─────────────────────────────────────────────────────── --}}
<div class="fe-section-card">
  <div class="fe-section-header">
    <span class="fe-section-badge">F</span>
    <h6 class="fe-section-title">Other Cultivated Crops</h6>
  </div>
  <div class="table-responsive">
    <table class="table fe-table mb-0">
      <thead>
        <tr>
          <th>Plot Name</th>
          <th>Crop Cultivated</th>
          <th>Estimated Hectares</th>
          <th>Location</th>
          <th class="text-center">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($otherplots as $othercrop)
        <tr>
          <td><input type="text" name="otherplotname" class="form-control form-control-sm" value="{{ $othercrop->plotname }}"></td>
          <td><input type="text" name="otherplotcrop" class="form-control form-control-sm" value="{{ $othercrop->crop }}"></td>
          <td><input type="text" name="otherplotarea" class="form-control form-control-sm" value="{{ $othercrop->area }}"></td>
          <td><input type="text" name="otherplotlocation" class="form-control form-control-sm" value="{{ $othercrop->location }}"></td>
          <td class="text-center">
            <a href="{{ route('disableoplots', ['farmcode' => $farmerdetail->farmcode, 'oid' => $othercrop->id]) }}"
               class="btn btn-outline-danger btn-action-sm">
              <i class="fa fa-times me-1"></i>Disable
            </a>
          </td>
        </tr>
        @empty
        @endforelse
        <tr class="fe-add-row">
          <form action="{{ route('addoplots') }}" method="post">
            @csrf
            <td><input type="text" required name="otherplotname" class="form-control form-control-sm" placeholder="Plot name..."></td>
            <td><input type="text" required name="otherplotcrop" class="form-control form-control-sm" placeholder="Crop..."></td>
            <td><input type="text" required name="otherplotarea" class="form-control form-control-sm" placeholder="0.00 ha"></td>
            <td><input type="text" required name="otherplotlocation" class="form-control form-control-sm" placeholder="Location..."></td>
            <td class="text-center">
              <input type="text" name="farmcode" hidden value="{{ $farmerdetail->farmcode }}">
              <input type="text" name="farmid" hidden value="{{ $farmerdetail->id }}">
              <input type="text" name="farmentranceid" hidden value="{{ $farmentrance->id }}">
              <input type="text" name="season" hidden value="{{ $currentseason }}">
              <button type="submit" name="addotherplot" class="btn btn-primary btn-action-sm">
                <i class="fa fa-plus me-1"></i> Add
              </button>
            </td>
          </form>
        </tr>
      </tbody>
    </table>
  </div>
</div>

{{-- ─────────────────────────────────────────────────────── --}}
{{-- FOOTER ACTIONS --}}
{{-- ─────────────────────────────────────────────────────── --}}
<div class="d-flex align-items-center gap-3 mt-2 mb-4 flex-wrap">

  @if (empty($farmentrance->internalinspectionid))
  <form action="{{ route('start') }}" method="post" class="mb-0">
    @csrf
    <input type="text" name="farmentrance" hidden value="{{ $farmentrance->id }}">
    <input type="text" name="internalinspectionid" hidden value="{{ $farmentrance->internalinspectionid }}">
    <input type="text" name="farmid" hidden value="{{ $farmerdetail->id }}">
    <input type="text" name="reportid" hidden value="{{ $report ? $report->id : 'NULL' }}">
    <button type="submit" class="btn btn-success px-4 fw-semibold">
      <i class="fa fa-arrow-right me-2"></i>Proceed
    </button>
  </form>
  @else
  <form action="{{ route('continue') }}" method="post" class="mb-0">
    @csrf
    <input type="text" name="farmentrance" hidden value="{{ $farmentrance->id }}">
    <input type="text" name="internalinspectionid" hidden value="{{ $farmentrance->internalinspectionid }}">
    <input type="text" name="inspectionid" hidden value="{{ $farmentrance->internalinspectionid }}">
    <input type="text" name="farmid" hidden value="{{ $farmerdetail->id }}">
    <input type="text" name="id" hidden value="{{ $report->id }}">
    <button type="submit" class="btn btn-success px-4 fw-semibold">
      <i class="fa fa-arrow-right me-2"></i>Proceed
    </button>
  </form>
  @endif

  <a href="{{ route('feprofile', ['fcode' => $farmerdetail->farmcode]) }}"
     class="btn btn-outline-secondary px-4 fw-semibold">
    <i class="fa fa-arrow-left me-2"></i>Go Back
  </a>

</div>

</x-layouts.app>
