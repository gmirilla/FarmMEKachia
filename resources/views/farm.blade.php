<x-layouts.app>
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<link rel="stylesheet" href=
"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<div class="d-flex flex-row-reverse">
<!--
<form method='get' action='dashboard'>
  <input type="text" placeholder="Search for Farm" name="farmsearch" style="border: solid 1px grey ; padding:3px">
  <input type="submit" class="btn btn-success" value="Search **TO DO">
<form>
-->
@php
  $user=auth()->user();
@endphp
@if ($user->roles=='ADMINISTRATOR')
<a href='{{route('season.index')}}' class="btn btn-warning" style="margin:5px"><i class="fa fa-calendar"></i> Season Management</a>
<a href='{{route('disabled.farms')}}' class="btn btn-secondary" style="margin:5px"><i class="fa fa-ban"></i> Disabled Farms</a>
<a href='{{route('import_list')}}' class="btn btn-success" style="margin:5px"> Import Farmers</a>
<a href='/newfarm' class="btn btn-success" style="margin:5px"> Register New Farmer</a>
@endif

</div>
<div class="col-md col-xl col-sm py-md-3 pl-md-5t  fs-6, fs-md-5, fs-lg-5, fs-xl-1" style="font-size: 0.9rem">
<div class="card">
  <div class="card-header"><h4>LIST OF FARMS</h4></div>

  <div class="card-body table-responsive">

    <table class="table table-striped table-sm display" id="farms" style="width:100%">
        <thead>
          <tr>
            <th scope="col">Community</th>
            <th scope="col">Farm Code</th>
            <th scope="col">Farmer Name</th>
            <th scope="col">Last Inspection Date</th>
            <th scope="col">Next Inspection Date</th>
            <th scope="col">Status</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($farmlist as $farm )
          @php 
          $farmcode=$farm->farmcode;
          @endphp
          <tr>
            <td>{{$farm->community}}</td>
            <td>{{$farm->farmcode}}</td>
            <td>{{$farm->farmname}}           
               @if ($farm->getlastreport()=='CONDITIONAL')
               <div><br/>
                    <span class="position-absolute  translate-middle badge rounded-pill bg-warning ml-5">
                    Conditional
                    <span class="visually-hidden">Conditional</span>
            </span>
            <br/>

               </div>

           @endif</td>
            <td>{{$farm->lastinspection}}</td>
            <td>{{$farm->nextinspection}}</td>
            <td>{{$farm->farmstate}}</td>
            <td>
          
              @if ($user->roles=='ADMINISTRATOR' && $farm->inspectorid!=null) 
              <button type="button" class="btn btn-success" data-bs-toggle="modal"
              data-bs-target="#exampleModal" data-bs-whatever="{{$farm->farmcode}}" data-toggle="tooltip" data-placement="right" title="Schedule Inspection"><i class="fa fa-calendar" aria-hidden="true"></i></button>  
              @endif
              <a href="/farm/view?id={{$farm->farmcode}}" type="button" class="btn btn-success" style="margin:3px" data-toggle="tooltip" data-placement="right" title="View Farm"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
          </tr>
            
          @empty
          <tr>
            <td></td><td><h1>No Farms Registered on System!!</h1></td><td></td><td></td><td></td><td></td><td></td><td></td>
          </tr>
            
            {{$farmcode='None'}}
          @endforelse 
        </tbody>
      </table>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">New message</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="farmschedule" method="post" action='/farm/schedule'>
        @csrf
      <div class="modal-body">
        
          <div class="mb-3">
            <label for="farmcode" class="col-form-label">Farm Code</label>
            <input type="text"  readonly class="form-control fcode" id="farmcode" name="farmcode">
          </div>
          <div class="mb-3">
            <label for="message-dropdown" class="col-form-label">Inspection Type</label>
            <select  class="form-select" id="scheduletype" name="inspectiontype" style="background-color:cornflowerblue">
              @forelse ($reports as $report )
              <option value="{{$report->id}}"> {{$report->reportname}}</option>
              @empty
                <option value="0" > No Reports Available</option>
              @endforelse
            </select>
          </div>  
          <div class="mb-3">
            <label for="message-text" class="col-form-label">Date</label>
            <input type="date" class="form-control" id="scheduledate" name="newinspectiondate" style="background-color:cornflowerblue">
          </div>       
      </div>     
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <input  type="submit" class="btn btn-success" value="Save">
      </div>
    </form>
    </div>
  </div>
</div>
</div>
</div>
</div>
<script>
var exampleModal = document.getElementById('exampleModal')
exampleModal.addEventListener('show.bs.modal', function (event) {
  // Button that triggered the modal
  var button = event.relatedTarget
  // Extract info from data-bs-* attributes
  var recipient = button.getAttribute('data-bs-whatever')
  // If necessary, you could initiate an AJAX request here
  // and then do the updating in a callback.
  //
  // Update the modal's content.
  var modalTitle = exampleModal.querySelector('.modal-title')
  var modalBodyInput = exampleModal.querySelector('.modal-body input')

  modalTitle.textContent = 'Schedule Inspection for  ' + recipient
  modalBodyInput.value = recipient
})
</script>
    <script>
            $(document).ready(function() {
    $('#farms').DataTable({
        dom: 'Bfrtip',
        pageLength: 200,
        order: [[1, 'asc']],
        stateSave: true,
        buttons: [
            {
                extend: 'excelHtml5',
                title: 'FARMERS_Report',
                exportOptions: {
                    columns: ':visible'
                }
            }
        ]
    });
});
    </script>
</x-layouts.app>

            
