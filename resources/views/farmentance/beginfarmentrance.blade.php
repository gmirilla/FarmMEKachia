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
                     <input type="number" value="{{$farmerdetail->yob}}" class="form-control" disabled/>  
                </div>
                <div class="col-3 p-3">
                    <label for="phoneno" class="form-label">Phone Number</label>
                    <input type="text" name="phonenumber" id="phonenumber" value="{{$farmerdetail->phonenumber}}" disabled class="form-control" />
                </div>
                <div class="col-3 p-3">
                    <label for="householdsize" class="form-label">Household Size</label>
                    <input type="text" disabled name="householdsize" id="householdsize" value="{{$farmerdetail->householdsize}}"  class="form-control" />
                </div>
                <div class="col-3 p-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address"  disabled id="address" class="form-control" >{{$farmerdetail->address}}</textarea>
                </div>
               
                <div class="col-3 p-3">
                    <label for="dateoflastinspection" class="form-label">Date of Last Inspection</label>
                        <input type="text" disabled name="dateoflastinspection" id="dateoflastinspection" class="form-control" value="{{$farmentrance->lastinspection}}"/>
                        </div>
                <div class="col-3 p-3">
                    <label for="dateoflastinspection" class="form-label">Outcome of Last Inspection</label>
                    <input type="text" disabled name="outcomeoflastinspection" id="dateoflastinspection" class="form-control" value="{{$farmentrance->inspectionresult}})"/>
                </div>
                    
                <div class="col-3 p-3">
                    <label for="nameofcrop" class="form-label">Name of Crop</label>
                    <input type="text"  disabled name="nameofcrop" id="crop" class="form-control" value="{{$farmerdetail->crop}}"/>
                </div>
                <div class="col-3 p-3">
                    <label for="varietyofcrop" class="form-label">Variety of Crop</label>
                    <input type="text"  disabled name="varietyofcrop" id="varietycrop" class="form-control" value="{{$farmerdetail->cropvariety}}"/>
                </div> 
                                <div class="col-3 p-3">
                    @if (!empty($farmerdetail->signaturepath))
                    <img src="{{url('/storage/'.$farmerdetail->signaturepath)}}" alt="" width="70px"><br> 
                    @endif
                    <label for="signature" class="form-label">Farmers Signature</label>
                </div>          
            </div>
        </div>
    </div>
    <div class="card my-3">
    <div class="card-body">
            <h5>A. PRDOUCTION HISTORY</h5>
            <table class="table">
                <thead>
                    <th>Ginger Plot Name</th>
                    <th>Farm Size (Ha)</th>
                    <th>Estimated Yield (Kg)</th>
                    <th>Lat. N</th>
                    <th>Long. E</th>
                    <th></th>
                </thead>
                <tbody>
                    @forelse ($farmplots as $farmplot)
                    <tr>
                        <td>{{$farmplot->plotname}}</td>
                        <td>{{$farmplot->fuarea}}</td>
                        <td>{{$farmplot->estimatedyield}}</td>
                        <td>{{$farmplot->fulatitude}}</td>
                        <td>{{$farmplot->fulongitude}}</td>
                        <td><a href="{{ route('disablefarm', ['farmcode' => $farmerdetail->farmcode, 'fuid' => $farmplot->id]) }}" class="btn btn-danger">Disable</a></td>

                    </tr>
                    @empty
                    <tr>
                        <td>NO FARM PLOTS ON RECORD</td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>   
                    @endforelse
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td><form action="{{route('newfunit')}}" method="get">

                                    <input type="text" name="farmid" id="farmid2" hidden value="{{$farmerdetail->id}}">
                                    <input type="text" name="farmentranceid" id="farmentranceid" hidden value="{{$farmentrance->id}}">
                                    <button class="btn btn-primary" name="newfunit"> Add Farm Unit</button>

                                </form></td>   

                    </tr>
                </tbody>
            </table>
        </div>
        </div>
            <div class="card my-3">
    <div class="card-body">
            <h5>B. VOLUME OF CERTIFIED CROPS SOLD/DELIVERED TO THE GROUP IN PREVIOUS YEARS (KGS)</h5>
            <table class="table">
                <thead>
                    <th>SEASON</th>
                    <th>VOLUME SOLD/DELIVERED (KGS)</th>
                    <th></th>
                </thead>
                <tbody>
                    @forelse ($farmentrance->getvolumesold() as $volsold)
                     <tr>
                        <td>{{$volsold->season}}</td>
                        <td>{{$volsold->value}}</td>
                        <td><a href="{{ route('disablevolsold', ['farmcode' => $farmerdetail->farmcode, 'vsid' => $volsold->id]) }}"  required class="btn btn-danger">Disable</a></td>
                     </tr>   
                    @empty
                        
                    @endforelse
                    <tr>
                        <form action="{{route('addvolsold')}}" method="post">
                            @csrf
                        <td><select name="volseason" id="seasonrange" class="form-select">
                            @foreach ($seasonrange as $srange)
                                <option value="{{$srange}}">{{$srange}}</option>
                            @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="number" step=any name="volsold" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="farmcode" hidden class="form-control" value="{{$farmerdetail->farmcode}}">
                            <input type="text" name="farmid" hidden class="form-control" value="{{$farmerdetail->id}}">
                            
                        <button type="submit" class="btn btn-primary" >ADD</button></td>
                        </form>
                    </tr> 

                </tbody>
            </table>
    </div>
    </div>
<div class="card my-3">
    <div class="card-body">
            <h5>C. </h5>
            <table class="table">
                <thead>
                    <th>SEASON</th>
                    <th>DESCRIPTION</th>
                    <th>QUANTITY IN KGS</th>
                    <th></th>
                </thead>
                <tbody>

                    <tr><form action="{{route('cropdelivered')}}" method="get">
                        @csrf
                        <td>{{$prevseason}}</td>
                            <td>Previous year’s <b> ({{$prevseason}})</b> harvest of certified crop delivered to the group</td>
                            <td><input type="number" step=any name="cropdelivered" id="cropdelivered" class="form-control" value="{{$prevcropdelivered}}"></td>
                            <td>
                                <input type="text" hidden name='prevseason' value='{{$seasonrange[0]}}'>
                                <input type="text" hidden name='farmid' value='{{$farmerdetail->id}}'>
                                <input type="text" hidden name='farmcode' value='{{$farmerdetail->farmcode}}'>
                                <button type="submit" class="btn btn-primary">UPDATE</button>
                            </td>
                            </form>

                    </tr>
                    <tr>
                        <form action="{{route('cropproduced')}}" method="get">
                            @csrf
                        <td>{{$prevseason}}</td>
                            <td>Previous year’s <b> ({{$prevseason}})</b> estimated total production </td>
                            <td><input type="number" step=any name="cropproduced" class="form-control" value="{{$prevcropproduced}}"></td>
                                  <td>
                                <input type="text" hidden name='prevseason' value='{{$prevseason}}'>
                                <input type="text" hidden name='farmid' value='{{$farmerdetail->id}}'>
                                <input type="text" hidden name='farmcode' value='{{$farmerdetail->farmcode}}'>
                                <button type="submit" class="btn btn-primary">UPDATE</button>
                            </td>
                            </form>



                    </tr>

                </tbody>
            </table>
    </div>
    </div>
    <div class="card my-3">
    <div class="card-body">
            <h5>D. AGROCHEMICALS USED ON THE FARM LAND  </h5>
            <table class="table">
                <thead>
                    <th>NAME  OF HERBICIDE & FERTILIZERS USED</th>
                    <th>QUANTITY APPLIED (LITER'S/BAGS)</th>
                    <th>NAME OF PERSON WHO APPLIED</th>
                    <th>Ha OF GINGER APPLIED ON</th>
                    <th></th>
                </thead>
                <tbody>'
                    @forelse ($agrochems as $agrochem )
                                           <tr>
                        <td><input type="text" disabled name="herbicide" class="form-control" value="{{$agrochem->herbicidename}}"></td>
                        <td><input type="text"  disabled name="herbicideqty" class="form-control" value="{{$agrochem->quantity}}"></td>
                        <td><input type="text" disabled name="herbicideapplier" class="form-control" value="{{$agrochem->nameofperson}}"></td>
                        <td><input type="number" disabled step=any name="hectareapplied" class="form-control" value="{{number_format($agrochem->hectaresapplied,2)}}"></td>
                        <td><a href="{{ route('disablechems', ['farmcode' => $farmerdetail->farmcode, 'aid' => $agrochem->id]) }}" class="btn btn-danger">Disable</a></td>
                    </tr> 
                    @empty
                        
                    @endforelse
                    <tr><form action="{{route('addchems')}}" method="post">
                        @csrf
                        <td><input type="text" required name="herbicide" class="form-control" placeholder="Enter name of Chemical.."></td>
                        <td><input type="text" required name="herbicideqty" class="form-control"></td>
                        <td><input type="text" required name="herbicideapplier" class="form-control"></td>
                        <td><input type="text" required name="hectareapplied" class="form-control"></td>
                        <td>
                            <input type="text" name="farmcode" hidden class="form-control" value="{{$farmerdetail->farmcode}}">
                            <input type="text" name="farmid" hidden class="form-control" value="{{$farmerdetail->id}}">
                            <input type="text" name="farmentrance" hidden class="form-control" value="{{$farmentrance->id}}">
                            <input type="text" name="season" hidden class="form-control" value="{{$currentseason}}">
                            <button type="submit" name="addherbicide" class="btn btn-primary">ADD</a></td>
                            </form>
                    </tr>
                </tbody>
            </table>
    </div>
    </div>
    <div class="card my-3">
    <div class="card-body">
            <h5>E. OTHER CULTIVATED CROPS  </h5>
            <table class="table">
                <thead>
                    <th>PLOT NAME</th>
                    <th>CROP CULTIVATED</th>
                    <th>ESTIMATED HECTARES</th>
                    <th>LOCATION</th>
                    <th></th>
                </thead>
                <tbody>
                    @forelse ($otherplots as $othercrop )
                    <tr>
                        <td><input type="text" name="otherplotname"    class="form-control" value="{{$othercrop->plotname}}"></td>
                        <td><input type="text" name="otherplotcrop"  class="form-control" value="{{$othercrop->crop}}"></td>
                        <td><input type="text" name="otherplotarea" class="form-control" value="{{$othercrop->area}}"></td>
                        <td><input type="text" name="otherplotlocation" class="form-control" value="{{$othercrop->location}}"></td>
                        <td><a href="{{ route('disableoplots', ['farmcode' => $farmerdetail->farmcode, 'oid' => $othercrop->id]) }}" class="btn btn-danger">Disable</a></td>
                    </tr> 
                    @empty
                        
                    @endforelse
                    <tr>
                        <form action="{{route('addoplots')}}" method="post">
                            @csrf

                        <td><input type="text" required name="otherplotname" class="form-control"></td>
                        <td><input type="text" required name="otherplotcrop" class="form-control"></td>
                        <td><input type="text" required name="otherplotarea" class="form-control"></td>
                        <td><input type="text" required name="otherplotlocation" class="form-control"></td>
                        <td><input type="text" name="farmcode" hidden class="form-control" value="{{$farmerdetail->farmcode}}">
                            <input type="text" name="farmid" hidden class="form-control" value="{{$farmerdetail->id}}">
                            <input type="text"name="farmentranceid" hidden class="form-control" value="{{$farmentrance->id}}">
                            <input type="text" name="season" hidden class="form-control" value="{{$currentseason}}">
                            <button type="submit" name="addotherplot" class="btn btn-primary">ADD</a></td>
                                </form>
                    </tr>
                </tbody>
            </table>
    </div>
    </div>

    @if (empty($farmentrance->internalinspectionid))
            <div class="d-flex">
        <form action="{{route('start')}}" method="post">
            @csrf
        <input type="text" name="farmentrance" hidden class="form-control" value="{{$farmentrance->id}}">
        <input type="text" name="internalinspectionid" hidden class="form-control" value="{{$farmentrance->internalinspectionid}}">
        <input type="text" name="farmid" hidden class="form-control" value="{{$farmerdetail->id}}">
        <input type="text" name="reportid" hidden class="form-control" value="{{ $report ? $report->id : 'NULL' }}">
        <button type="submit" class="btn btn-success">PROCEED</button>
        </form>
        <div class="ml-3"><a  href="{{route('feprofile', ['fcode' => $farmerdetail->farmcode])}}" class="btn btn-danger">Go Back</a></div>
    </div>

    @else
                <div class="d-flex">
        <form action="{{route('continue')}}" method="post">
            @csrf
        <input type="text" name="farmentrance" hidden class="form-control" value="{{$farmentrance->id}}">
        <input type="text" name="internalinspectionid" hidden class="form-control" value="{{$farmentrance->internalinspectionid}}">
        <input type="text" name="inspectionid" hidden class="form-control" value="{{$farmentrance->internalinspectionid}}">
        <input type="text" name="farmid" hidden class="form-control" value="{{$farmerdetail->id}}">
        <input type="text" name="id" hidden class="form-control" value="{{$report->id}}">
        <button type="submit" class="btn btn-success">PROCEED</button>
        </form>
        <div class="ml-3"><a  href="{{route('feprofile', ['fcode' => $farmerdetail->farmcode])}}" class="btn btn-danger">Go Back</a></div>
    </div>
    
        
    @endif
    

</x-layouts.app>