<style>
#overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background-color: rgba(0,0,0,0.5);
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}
.loader-circle {
  border: 6px solid rgba(255,255,255,0.3);
  border-top: 6px solid #3498db;
  border-radius: 50%;
  width: 50px;
  height: 50px;
  animation: spin 1s linear infinite;
  margin-top: 12px;
}
.loader-text {
  color: white;
  font-size: 1.1rem;
  font-weight: 600;
  text-align: center;
}
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
.custom-info {
  font-size: 16px;
  font-weight: bold;
  color: #ffffff;
  padding: 8px;
  background-color: transparent;
}
.gm-style .gm-style-iw-c {
  background-color: transparent !important;
  overflow: hidden !important;
  box-shadow: none !important;
}
.gm-style-iw-d {
  background-color: transparent !important;
  overflow: hidden !important;
}
.gm-style-iw-chr { display: none !important; }
.gm-style .gm-style-iw-tc { display: none !important; }

.stat-card {
  background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
  border: 1px solid #e9ecef;
  border-radius: 10px;
  padding: 12px 16px;
  text-align: center;
}
.stat-card label {
  font-size: 0.72rem;
  font-weight: 600;
  color: #6c757d;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  display: block;
  margin-bottom: 2px;
}
.stat-card .form-control {
  border: none;
  background: transparent;
  text-align: center;
  font-weight: 600;
  padding: 0;
  height: auto;
  font-size: 0.95rem;
  color: #212529;
  box-shadow: none;
}
.map-controls-card {
  border-radius: 12px;
  border: 1px solid #dee2e6;
}
.map-controls-card .card-header {
  background: linear-gradient(90deg, #198754, #20c997);
  color: white;
  border-radius: 11px 11px 0 0;
  font-weight: 600;
  font-size: 0.85rem;
  letter-spacing: 0.5px;
  text-transform: uppercase;
}
.coord-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: #f1f3f5;
  border: 1px solid #dee2e6;
  border-radius: 6px;
  padding: 6px 12px;
  font-size: 0.85rem;
  font-weight: 600;
  color: #495057;
}
.coord-badge span {
  color: #0d6efd;
  min-width: 80px;
}
.btn-tool {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 3px;
  font-size: 0.7rem;
  font-weight: 600;
  padding: 8px 10px;
  border-radius: 8px;
  min-width: 64px;
}
.btn-tool i { font-size: 1rem; }
#map-wrapper {
  border-radius: 12px;
  overflow: hidden;
  border: 2px solid #dee2e6;
}
.section-title {
  font-size: 0.7rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #6c757d;
  margin-bottom: 8px;
}
.map-preview-thumb {
  width: 72px;
  height: 72px;
  object-fit: cover;
  border-radius: 8px;
  border: 2px solid #dee2e6;
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<x-layouts.app>

<div id="overlay" style="display: none;">
  <div class="loader-text">Saving map...</div>
  <div class="loader-circle"></div>
</div>

{{-- Page Header --}}
<div class="d-flex align-items-center justify-content-between mb-3 flex-wrap gap-2">
  <div>
    <h4 class="mb-0 fw-bold">
      <i class="fa fa-map-o me-2 text-success"></i>
      {{$farm->farmcode}} &mdash; Farm Unit
    </h4>
    <p class="text-muted mb-0" style="font-size:0.82rem">Configure plot details and mark boundaries on the map</p>
  </div>
</div>

{{-- Farm Details Form --}}
<form action="{{route('fusave')}}" method="post" id="fuForm">
  @csrf
  <input type="text" value="{{$farm->id}}" id="fid" name="fid" hidden>
  @if (!empty($farmunit))
  <input type="text" value="{{$farmunit->id}}" id="farmunitid" name="farmunitid" hidden>
  @endif
  @if (!empty($farmentrance))
  <input type="text" name="farmentranceid" hidden value="{{$farmentrance->id}}">
  @endif
  <input type="text" hidden id="imagefilePath" name="imagefilePath" class="form-control"
    @if(!empty($farmunit->imagefilepath)) value="{{$farmunit->imagefilepath}}" @endif>

  <div class="card border-0 shadow-sm mb-3" style="border-radius:12px;">
    <div class="card-header bg-white border-bottom py-2 px-3" style="border-radius:12px 12px 0 0;">
      <span class="fw-semibold" style="font-size:0.82rem; text-transform:uppercase; letter-spacing:.5px; color:#6c757d;">
        <i class="fa fa-info-circle me-1"></i> Plot Information
      </span>
    </div>
    <div class="card-body px-3 py-3">

      {{-- Row 1: identifiers --}}
      <div class="row g-3 mb-3">
        <div class="col-6 col-sm-4 col-md-2">
          <label class="form-label fw-semibold" style="font-size:.8rem;">Farm Code</label>
          <input type="text" value="{{$farm->farmcode}}" id="farmcode" name="farmcode" disabled class="form-control form-control-sm bg-light">
        </div>
        <div class="col-6 col-sm-4 col-md-2">
          <label class="form-label fw-semibold" style="font-size:.8rem;">Farm ID</label>
          <input type="text" value="{{$farm->id}}" id="fuid" name="fuid" disabled class="form-control form-control-sm bg-light">
        </div>
        <div class="col-12 col-sm-4 col-md-3">
          <label class="form-label fw-semibold" style="font-size:.8rem;">Plot Name <span class="text-danger">*</span></label>
          <input type="text" value="{{$farmunit->plotname ?? ''}}" id="fuplotname" name="fuplotname" required
            placeholder="e.g. North Block A" class="form-control form-control-sm">
        </div>
        <div class="col-6 col-sm-4 col-md-2">
          <label class="form-label fw-semibold" style="font-size:.8rem;">Area (ha) <span class="text-danger">*</span></label>
          <input type="text" value="{{$farmunit->fuarea ?? ''}}" id="fuarea" name="fuarea" required
            placeholder="0.00" class="form-control form-control-sm">
        </div>
        <div class="col-6 col-sm-4 col-md-2">
          <label class="form-label fw-semibold" style="font-size:.8rem;">Date Planted <span class="text-danger">*</span></label>
          <input type="date" value="{{$farmunit->dateplanted ?? ''}}" id="dateplanted" name="dateplanted" required
            class="form-control form-control-sm">
        </div>
      </div>

      {{-- Row 2: coordinates + map + submit --}}
      <div class="row g-3 align-items-end">
        <div class="col-6 col-sm-4 col-md-2">
          <label class="form-label fw-semibold" style="font-size:.8rem;">Latitude</label>
          <input type="text" value="{{$farmunit->fulatitude ?? ''}}" id="fulatitude" name="fulatitude"
            placeholder="auto" class="form-control form-control-sm">
        </div>
        <div class="col-6 col-sm-4 col-md-2">
          <label class="form-label fw-semibold" style="font-size:.8rem;">Longitude</label>
          <input type="text" value="{{$farmunit->fulongitude ?? ''}}" id="fulongitude" name="fulongitude"
            placeholder="auto" class="form-control form-control-sm">
        </div>
        <div class="col-12 col-sm-6 col-md-4">
          <label class="form-label fw-semibold" style="font-size:.8rem;">Saved Map</label>
          <div class="d-flex align-items-center gap-2">
            @if (empty($farmunit->imagefilepath))
              <img id="uploadedImage" src="{{Request::root().('/storage/farmmap.png')}}" alt="No Map" class="map-preview-thumb">
              <input type="text" disabled id="mapfilePath" name="mapfilePath" value="No map saved" class="form-control form-control-sm bg-light text-muted flex-grow-1">
            @else
              <img src="{{Request::root().($farmunit->imagefilepath)}}" alt="Map" class="map-preview-thumb">
              <input type="text" disabled id="mapfilePath" name="mapfilePath" value="{{$farmunit->imagefilepath}}" class="form-control form-control-sm bg-light flex-grow-1">
            @endif
          </div>
        </div>
        <div class="col-12 col-sm-auto ms-sm-auto">
          @if (empty($farmunit->imagefilepath))
            <button type="submit" disabled class="btn btn-primary w-100" id="addfarmunit">
              <i class="fa fa-save me-1"></i> Save Farm Unit
            </button>
          @else
            <button type="submit" class="btn btn-primary w-100" id="addfarmunit">
              <i class="fa fa-save me-1"></i> Save Farm Unit
            </button>
          @endif
        </div>
      </div>

    </div>
  </div>
</form>
{{-- Import Map --}}
<div class="card border-0 shadow-sm mb-4" style="border-radius:12px;">
  <div class="card-header bg-white border-bottom py-2 px-3" style="border-radius:12px 12px 0 0;">
    <span class="fw-semibold" style="font-size:.82rem; text-transform:uppercase; letter-spacing:.5px; color:#6c757d;">
      <i class="fa fa-upload me-1"></i> Import Map Image
    </span>
  </div>
  <div class="card-body px-3 py-3">
    <p class="text-muted mb-2" style="font-size:.82rem;">
      <i class="fa fa-info-circle me-1"></i>
      Manually upload a map image. Max <strong>2MB</strong> &mdash; JPG, JPEG, PNG accepted.
    </p>
    <form id="uploadForm" enctype="multipart/form-data" class="d-flex flex-wrap gap-2 align-items-end">
      @csrf
      <div style="flex:1; min-width:200px;">
        <input type="file" name="importimage" id="importimage" class="form-control form-control-sm" accept=".jpg,.jpeg,.png">
      </div>
      <button type="submit" class="btn btn-primary btn-sm">
        <i class="fa fa-upload me-1"></i> Upload
      </button>
    </form>
    <div id="uploadStatus" class="alert alert-danger mt-2 py-2" style="display:none; font-size:.82rem;"></div>
  </div>
</div>

{{-- Map Tools + Map --}}
<div class="card border-0 shadow-sm mb-3 map-controls-card">
  <div class="card-header d-flex align-items-center gap-2 py-2 px-3">
    <i class="fa fa-map-marker"></i>
    <span>Boundary Mapping &amp; Area Calculator</span>
  </div>
  <div class="card-body p-3">

    {{-- Coordinate readout --}}
    <div class="d-flex flex-wrap gap-3 mb-3 align-items-center">
      <div class="coord-badge">
        <i class="fa fa-crosshairs text-muted" style="font-size:.85rem;"></i>
        Lat: <span id="latitude">—</span>
      </div>
      <div class="coord-badge">
        <i class="fa fa-crosshairs text-muted" style="font-size:.85rem;"></i>
        Lng: <span id="longitude">—</span>
      </div>
    </div>

    {{-- Toolbar --}}
    <div class="d-flex flex-wrap gap-2 mb-3">
      <button class="btn btn-outline-primary btn-tool" onclick="addWaypoint()" title="Get Current Coordinates">
        <i class="fa fa-map-marker"></i>Waypoint
      </button>
      <button class="btn btn-outline-success btn-tool" onclick="setFarmLocation()" title="Set as Farm Coordinates">
        <i class="fa fa-pencil"></i>Set Location
      </button>
      <button class="btn btn-outline-warning btn-tool" onclick="showPolygonArea()" title="Calculate Area">
        <i class="fa fa-calculator"></i>Calc Area
      </button>
      <button class="btn btn-outline-danger btn-tool" onclick="resetPoly()" title="Clear Coordinates">
        <i class="fa fa-eraser"></i>Reset
      </button>
      <div class="vr d-none d-sm-block mx-1" style="opacity:.3;"></div>
      <button class="btn btn-primary btn-tool" id="startTrackingbtn" onclick="startTracking()">
        <i class="fa fa-play"></i>Track
      </button>
      <button class="btn btn-danger btn-tool" id="stopTrackingbtn" onclick="stopTracking()">
        <i class="fa fa-stop"></i>Stop
      </button>
      <div class="vr d-none d-sm-block mx-1" style="opacity:.3;"></div>
      <button class="btn btn-success btn-tool" onclick="saveMap()">
        <i class="fa fa-camera"></i>Save Map
      </button>
    </div>

    {{-- Area result --}}
    <div id="showArea" class="alert alert-success d-flex align-items-center gap-3 py-2 mb-3" style="display:none!important; border-radius:8px;" hidden>
      <i class="fa fa-area-chart fa-lg"></i>
      <div>
        <div class="fw-bold">Calculated Farm Area</div>
        <span id="farmareaha"></span> ha &nbsp;|&nbsp; <span id="farmaream2"></span> m&sup2;
      </div>
      <button class="btn btn-success btn-sm ms-auto" onclick="setFarmarea()">
        <i class="fa fa-check me-1"></i>Use This Area
      </button>
    </div>

    {{-- Plotted coordinates (hidden by default) --}}
    <div class="mb-3" style="display:none;">
      <div class="section-title">Plotted Coordinates</div>
      <div class="table-responsive" style="max-height:180px;">
        <table class="table table-sm table-striped table-bordered mb-1" style="font-size:.8rem;">
          <thead class="table-dark"><tr><th>Lat</th><th>Long</th></tr></thead>
          <tbody id="formartcoordinates"></tbody>
        </table>
      </div>
      @if (!empty($farmunit))
      <textarea id="polycoord" name="polycoords" class="form-control form-control-sm mt-1" rows="2"
        style="font-size:.75rem; font-family:monospace;">{{$farmunit->plot_coords}}</textarea>
      @endif
    </div>

    {{-- Map --}}
    <div id="map-wrapper" style="height:520px;">
      <div id="map" style="height:100%; width:100%;"></div>
    </div>

  </div>
</div>

<body
  @if (!empty($farmunit) && !empty($farmunit->fulatitude) && !empty($farmunit->fulongitude))
  onload="initMap(parseFloat({{$farmunit->fulatitude}}), parseFloat({{$farmunit->fulongitude}}))">
  @else
  onload="initMap(0, 0)">
  @endif
</body>

<script src="https://cdn.jsdelivr.net/npm/html-to-image@1.11.13/dist/html-to-image.min.js"></script>
<script async src="https://maps.googleapis.com/maps/api/js?key={{env('MAP')}}&libraries=geometry,drawing"></script>
<script>
  var map;
  var pathCoordinates = [];
  var polyline;
  var trackingActive = false;
  var watchID = null;
  var polygon;
  var polygonCoordinates = [];
  var waypointCoordinates = [];
  var infoWindow = null;
  var maparea;

  function initMap() {
    map = new google.maps.Map(document.getElementById("map"), {
      zoom: 20,
      center: { lat: 12.0022, lng: 8.5919 }
    });

    polyline = new google.maps.Polyline({
      path: pathCoordinates,
      geodesic: true,
      strokeColor: "#FF0000",
      strokeOpacity: 1.0,
      strokeWeight: 2
    });
    polyline.setMap(map);

    polygon = new google.maps.Polygon({
      paths: polygonCoordinates,
      strokeColor: "#90EE90",
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: "#0b6623",
      fillOpacity: 0.8
    });
    polygon.setMap(map);

    map.addListener("click", function(event) {
      addPoint(event.latLng);
    });
  }

  function addPoint(location) {
    polygonCoordinates.push(location);
    polygon.setPaths(polygonCoordinates);
  }

  function showPolygonArea() {
    if (!polygon) return console.warn("Polygon not ready.");
    const area = google.maps.geometry.spherical.computeArea(polygon.getPath().getArray());
    const areaha = area / 10000;
    const center = getPolygonCenter(polygon.getPath());

    if (infoWindow) infoWindow.close();
    infoWindow = new google.maps.InfoWindow({
      content: `<div class="custom-info">Area: ${areaha.toFixed(3)} Ha</div>`,
      position: center
    });
    maparea = areaha.toFixed(3);
    infoWindow.open(map);

    const areaCard = document.getElementById('showArea');
    document.getElementById('farmareaha').textContent = areaha.toFixed(3);
    document.getElementById('farmaream2').textContent = area.toFixed(0);
    areaCard.removeAttribute('hidden');
    areaCard.style.display = '';
  }

  function getPolygonCenter(path) {
    const bounds = new google.maps.LatLngBounds();
    path.forEach(function(latlng) { bounds.extend(latlng); });
    return bounds.getCenter();
  }

  function setFarmarea() {
    document.getElementById('fuarea').value = maparea;
  }

  function startTracking() {
    if (!trackingActive) {
      trackingActive = true;
      document.getElementById('startTrackingbtn').disabled = true;
      document.getElementById('stopTrackingbtn').disabled = false;

      if (navigator.geolocation) {
        watchID = navigator.geolocation.watchPosition(
          function(position) {
            var newPoint = { lat: position.coords.latitude, lng: position.coords.longitude };
            pathCoordinates.push(newPoint);
            polyline.setPath(pathCoordinates);
            const el = document.getElementById('polycoords');
            if (el) el.textContent = JSON.stringify(pathCoordinates);
            map.setCenter(newPoint);
          },
          function(error) { console.log("Error getting location: ", error); },
          { enableHighAccuracy: true, timeout: 5000, maximumAge: 5000 }
        );
      } else {
        alert("Geolocation is not supported by your browser.");
      }
    }
  }

  function stopTracking() {
    if (trackingActive) {
      trackingActive = false;
      document.getElementById('startTrackingbtn').disabled = false;
      document.getElementById('stopTrackingbtn').disabled = true;
      if (watchID !== null) {
        navigator.geolocation.clearWatch(watchID);
        watchID = null;
      }
      if (pathCoordinates.length > 2) {
        generatePolygon(pathCoordinates);
      } else {
        alert("Not enough data points to create a polygon.");
      }
    }
  }

  function addWaypoint() {
    if (pathCoordinates.length > 0) {
      let lastPoint = pathCoordinates[pathCoordinates.length - 1];
      waypointCoordinates.push(lastPoint);
    } else {
      navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
      let lastPoint = pathCoordinates[0];
      waypointCoordinates.push(lastPoint);
    }
  }

  function successCallback(position) {
    const latitude = position.coords.latitude;
    const longitude = position.coords.longitude;
    document.getElementById('longitude').textContent = longitude;
    document.getElementById('latitude').textContent = latitude;
  }

  function errorCallback(error) {}

  function generatePolygon(coordinates) {
    if (polygon) polygon.setMap(null);
    if (coordinates.length < 3) { alert("Not enough points to form a polygon."); return; }
    polygon = new google.maps.Polygon({
      paths: coordinates,
      strokeColor: "#90EE90",
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: "#0b6623",
      fillOpacity: 0.8
    });
    polygon.setMap(map);
    showPolygonArea();
  }

  function resetPoly() {
    if (polygon) polygon.setMap(null);
    if (infoWindow) infoWindow.close();
    polygonCoordinates = [];
    pathCoordinates = [];
    const areaCard = document.getElementById('showArea');
    areaCard.setAttribute('hidden', '');
    areaCard.style.display = 'none';
    polygon = new google.maps.Polygon({
      paths: polygonCoordinates,
      strokeColor: "#90EE90",
      strokeOpacity: 0.8,
      strokeWeight: 2,
      fillColor: "#0b6623",
      fillOpacity: 0.8
    });
    polygon.setMap(map);
  }

  function setFarmLocation() {
    document.getElementById('fulatitude').value = document.getElementById('latitude').textContent;
    document.getElementById('fulongitude').value = document.getElementById('longitude').textContent;
    document.getElementById('fuarea').value = maparea;
  }
</script>

<script>
function saveMap() {
  const mapElement = document.getElementById('map');
  const overlay = document.getElementById('overlay');
  const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
  let imagePath = document.getElementById('imagefilePath');
  let mapFilePath = document.getElementById('mapfilePath');
  let addfarmunit = document.getElementById('addfarmunit');

  if (!mapElement) { console.error('Map element not found!'); return; }
  overlay.style.display = 'flex';

  htmlToImage.toPng(mapElement)
    .then((dataUrl) => fetch('/save-map-image', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
      body: JSON.stringify({ image: dataUrl })
    }))
    .then((response) => {
      if (!response.ok) throw new Error(`Server responded with status ${response.status}`);
      return response.json();
    })
    .then((data) => {
      imagePath.value = data.path;
      mapFilePath.value = data.filename;
      addfarmunit.disabled = false;
      alert('Map saved successfully!');
    })
    .catch((err) => {
      console.error('Failed to save map image:', err);
      alert('Error saving map. Please try again.');
    })
    .finally(() => { overlay.style.display = 'none'; });
}
</script>

<script>
document.getElementById('uploadForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const formData = new FormData(this);
  let addfarmunit = document.getElementById('addfarmunit');
  const statusEl = document.getElementById('uploadStatus');
  addfarmunit.disabled = true;

  fetch('/upload-map', {
    method: 'POST',
    headers: {
      'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
      'Accept': 'application/json'
    },
    body: formData
  })
  .then(async response => {
    const data = await response.json();
    if (!response.ok) throw data;
    return data;
  })
  .then(data => {
    statusEl.className = 'alert alert-success mt-2 py-2';
    statusEl.style.display = '';
    statusEl.innerText = data.message || 'Upload successful!';
    if (data.filename) {
      document.querySelector('input[name="mapfilePath"]').value = data.path;
      document.querySelector('input[name="imagefilePath"]').value = data.path;
      addfarmunit.disabled = false;
      changeImage();
    }
  })
  .catch(error => {
    let message = 'Upload failed.';
    if (error.errors) {
      message = Object.values(error.errors).flat().join('\n');
    } else if (error.message) {
      message = error.message;
    }
    statusEl.className = 'alert alert-danger mt-2 py-2';
    statusEl.style.display = '';
    statusEl.innerText = message;
    addfarmunit.disabled = false;
    console.error(error);
  });
});

document.getElementById("importimage").addEventListener("change", function() {
  const file = this.files[0];
  if (file && file.size > 2 * 1024 * 1024) {
    alert("File is too large! Please select a file smaller than 2MB.");
    this.value = "";
  }
});

function changeImage() {
  const newImageUrl = document.getElementById("imagefilePath").value;
  const img = document.getElementById("uploadedImage");
  if (img) img.src = newImageUrl;
}
</script>

</x-layouts.app>
