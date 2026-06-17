<x-layouts.app>
            @if ($errors->any())
  <div class="alert alert-danger">
      <ul>
          @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
          @endforeach
      </ul>
  </div>
@endif
@php
    $year=date('Y');
    if (empty($farmentrance->getcropdeliver())) {
        # code...
        $prevseason=$seasonrange[0];
        $prevcropdelivered=null;
    } else {
        # code...
        $prevseason=$farmentrance->getcropdeliver()->season;
        $prevcropdelivered=$farmentrance->getcropdeliver()->value;
    }
        if (empty($farmentrance->getcropproduced())) {
        # code...
        $prevcropproduced=null;
    } else {
        # code...
        $prevcropproduced=$farmentrance->getcropproduced()->value;
    }
    
@endphp


    <div class="card">
        <div class="card-header"><h4>Annex 5:  Field Entrance Form (UEBT/RA/Mabagrown) {{$currentseason}} Season</h4></div>
        <div class="card-body">
            <h5>A. FIELD OPERATOR BIO-DATA</h5>
            <form action="{{route('feprofile_update')}}" method="post" enctype="multipart/form-data">
                @csrf
            <div class="row my-3 gx-5">
                <div class="col-3 p-3">
                    <label for="surname" class="form-label">Surname</label>
                    <input type="text" disabled class="form-control" value="{{$farmerdetail->surname}}">
                </div>
                <div class="col-3 p-3">
                    <label for="fname" class="form-label">Other name</label>
                    <input type="text" disabled class="form-control" value="{{$farmerdetail->fname}}">
                </div>
                <div class="col-3 p-3">
                    <label for="gender" class="form-label">Gender</label>
                    <input type="text" disabled class="form-control" value="{{$farmerdetail->gender}}">
                </div>
                <div class="col-3 p-3">
                    <label for="farmercode" class="form-label">Farmer Code</label>
                    <input type="text" disabled class="form-control" value="{{$farmerdetail->farmcode}}">
                </div>
                <div class="col-3 p-3">
                    <label for="idnumber" class="form-label">ID Number</label>
                    <input type="text" disabled class="form-control" value="{{$farmerdetail->nationalidnumber}}">
                </div>

                <div class="col-3 p-3">
                    <label for="yearofbirth" class="form-label">Year of Birth</label>
                   
                    @if (empty($farmerdetail->yob))
                    <input type="number" min="1900" max="2100" step="1" placeholder="Enter year" name="yearofbirth" id="yearofbirth" required class="form-control"/>
                    @else
                     <input type="number" value="{{$farmerdetail->yob}}" class="form-control" name="yearofbirth" id="yearofbirth"/>  
                    @endif
                </div>
                <div class="col-3 p-3">
                    <label for="phoneno" class="form-label">Phone Number</label>
                    <input type="text" name="phonenumber" id="phonenumber" value="{{$farmerdetail->phonenumber}}" disabled class="form-control" />
                </div>
                <div class="col-3 p-3">
                    <label for="householdsize" class="form-label">Household Size</label>
                    <input type="text" name="householdsize" id="householdsize" value="{{$farmerdetail->householdsize}}"  class="form-control" />
                </div>
                <div class="col-3 p-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address" id="address" class="form-control" >{{$farmerdetail->address}}</textarea>
                </div>
               
                <div class="col-3 p-3">
                    <label for="dateoflastinspection" class="form-label">Date of Last Inspection</label>
                    @if (empty($lastreport))
                        <input type="date" name="dateoflastinspection" id="dateoflastinspection" class="form-control" value="" placeholder="No report on System. Please enter report date"/>
                    </div>
                <div class="col-3 p-3">
                    <label for="dateoflastinspection" class="form-label">Outcome of Last Inspection</label>
                    <input type="text" name="outcomeoflastinspection" id="outcomeoflastinspection" class="form-control" value="" placeholder="No report on Record Please enter value"/>
                </div>

                    @else
                        <input type="text"  name="dateoflastinspection" id="dateoflastinspection" class="form-control" value="{{date_format($lastreport->updated_at, 'd/m/Y')}}"/>
                        </div>
                <div class="col-3 p-3">
                    <label for="dateoflastinspection" class="form-label">Outcome of Last Inspection</label>
                    <input type="text" name="outcomeflastinspection" id="outcomeoflastinspection" class="form-control" value="{{$lastreport->score}}% ({{$lastreport->inspectionstate}})"/>
                </div>
                    @endif
                    
                <div class="col-3 p-3">
                    <label for="nameofcrop" class="form-label">Name of Crop</label>
                    <input type="text"  readonly name="nameofcrop" id="crop" class="form-control" value="{{$farmerdetail->crop}}"/>
                </div>
                <div class="col-3 p-3">
                    <label for="varietyofcrop" class="form-label">Variety of Crop</label>
                    <input type="text"  readonly name="varietyofcrop" id="varietycrop" class="form-control" value="{{$farmerdetail->cropvariety}}"/>
                </div> 
                                <div class="col-3 p-3">
                    <label for="regdate" class="form-label">Reg Date</label>
                    <input type="date" name="regdate" id="regdate" class="form-control" value="{{$farmentrance->regdate}}"/>
                </div>  
                <div class="col-3 p-3">
                    @if (!empty($farmerdetail->signaturepath))
                    <img src="{{url('/storage/'.$farmerdetail->signaturepath)}}" alt="" width="70px"><br> 
                    @endif
                    <label for="signature" class="form-label">Signature</label>
                    <input type="file" name="signature" accept="image/*" class="form-control">
                </div> 
                <div class="col-3 p-3">
                    @if (!empty($farmentrance->farmerpicture))
                    <img src="{{url('/storage/'.$farmentrance->farmerpicture)}}" alt="" width="70px"><br> 
                    @endif
                    <label for="farmerpicture" class="form-label">Farmer Picture</label>
                    <input type="file" name="farmerpicture" accept="image/*" class="form-control">
                </div> 
                <div class="d-flex"> 
                    <input type="text" hidden name="fcode" value="{{$farmerdetail->farmcode}}">
                     <input type="text" hidden name="farmentranceid" value="{{$farmentrance->id}}">
                    <button type="submit" class="btn btn-success">PROCEED</button>
                    <button type="button" class="btn btn-danger mx-2" onclick="goback()">GO BACK</button>
                </div>  
            </form>       
            </div>
        </div>

</x-layouts.app>