
<style>
    th, td {
  border: 1px solid;
}
td{
    padding: 2px;
    textalign:justify;}
table {
  width: 100%;
  border-collapse: collapse;
  font-size: 12px;
}
input[type="radio"] {
    
    font-size: 0.75rem;
}
.form-check {
    margin-bottom: 0.5rem;
}
</style>

    @php
        $counter=0;
    @endphp


<div class="card">
    <div class="card-header" style="text-align: center">
    <h3><b>B & R SPICES NIGERIA LTD</b> <br>
        {{strtoupper($farm->farmname)}}</h3>
    <h4>@if (!empty($farmentrance->farm_period))
         {{$farmentrance->farm_period}} Season | 
    @endif
        {{$reportname->reportname}}</h4>
    </div>
    <div class="card-body">
    <table class="table table-bordered" style="border: 1px solid black">
        <tbody>
            <tr>
                <td style="width: 20%"><b>SURNAME:</b><br/>
                {{$farmentrance->surname}}
                </td>
                <td style="width: 20%"><b>OTHER NAME(s):</b><br/>
                {{$farmentrance->fname}}
                </td>
                <td style="width: 20%"><b>GENDER:</b><br/>
                {{$farm->gender}}
                </td>
                <td style="width: 20%"><b>FARMER'S CODE:</b><br/>
                {{$farmentrance->farmcode}}
                </td>
                <td style="width: 20%"><b>NATIONAL ID:</b><br/>
                {{$farmentrance->nationalidno}}
                </td>
            </tr>
            <tr>
            <td><b>YEAR OF BIRTH:</b><br/>
                {{$farm->yob}}
                </td>
                                <td><b>PHONE NUMBER:</b><br/>
                {{$farmentrance->phoneno}}
                </td>
                <td><b>HOUSEHOLD SIZE:</b><br/>
                {{$farmentrance->householdsize}}
                </td>
                <td><b>ADDRESS:</b><br/>
                {{$farmentrance->address}}
                </td>
                <td>
                    @if (!empty($farmentrance->farmerpicture))
                        <img src="{{public_path('/storage/'.$farmentrance->farmerpicture)}}" alt="" style="max-height: 100px; max-width: 100px;">
                    @endif 

                </td>
            </tr>
            <tr>
                <td colspan="2"> <b>DATE OF LAST INSPECTION: </b><br/>
                {{$farmentrance->lastinspection}}</td>
                <td colspan="3"><b>OUTCOME OF LAST INSPECTION:</b><br>{{$farmentrance->inpsectionresult}}
                </td>
            </tr>
            <tr>
                <td colspan="2"><b>NAME OF CROP: </b><br>{{$farmentrance->crop}}</td>
                <td colspan="2"><b>VARIETY:</b><br/>{{$farmentrance->cropvariety}}</td>
                <td><b>REG DATE: </b>{{$farmentrance->regdate}}</td>
            </tr>
        </tbody>
    </table>

    <h4>A. PRODUCTION HISTORY</h4>
    <table>
        <thead><th>Ginger Plot Name</th><th>Farm Size (Ha)</th><th>Estimated Yield Kg</th><th>Lat N.</th><th>Long E.</th>
        </thead>
        <tbody>
            @forelse ($farmentrance->reportprodhistory() as $prodhistory )
                        <tr>
                <td>{{$prodhistory->plotname}}</td>
                 <td>{{$prodhistory->fuarea}}</td>
                  <td>{{number_format($prodhistory->estimatedyield,2)}}</td>
                   <td>{{$prodhistory->fulatitude}}</td>
                    <td>{{$prodhistory->fulongitude}}</td>
            
            </tr>
                
            @empty
                
            @endforelse

        </tbody>
    </table>
    <h4>B. VOLUME OF CERTIFIED CROPS SOLD/DELIVERED TO THE GROUP IN PREVIOUS YEARS (KGS)</h4>
    <table>
        <thead><th>Season</th><th>Volume Sold (Kg)</th>
        </thead>
        <tbody>
            @forelse ( $farmentrance->reportvolcropdel() as $volcropdel )
                            <tr>
                <td>{{$volcropdel->season}}</td>
                 <td>{{$volcropdel->value}}</td>

            
            </tr>
            @empty
                
            @endforelse

        </tbody>
    </table>
     <h4>C.</h4>
    <table>
        <tbody>
            <tr>
                <td style="width: 50%">Previous year’s harvest of certified crop delivered to the group</td>
                 <td>@if (!empty($farmentrance->getcropdeliver()->value))
                     {{$farmentrance->getcropdeliver()->value}}
                 @endif
                    </td>
        </tr>
                    <tr>
                <td>Previous year’s estimated total production </td>
                 <td>
                    @if (!empty($farmentrance->getcropproduced()->value))
                     {{$farmentrance->getcropproduced()->value}}
                 @endif
                    </td>
        </tr>
        </tbody>
    </table>

     <h4>D. AGROCHEMICALS USED ON THE FARM LAND</h4>
    <table>
        <thead><th>Name of  Herbicide and Fertilizers<br> Used on the Farm</th><th>Quantity applied <br>(Liter’s/Bags)</th><th>Name of person who applied</th><th>Ha of Ginger applied on</th>
        </thead>
        <tbody>
            @forelse ($farmentrance->reportagrochems() as $agrochem)
                            <tr>
                <td>{{$agrochem->herbicidename}}</td>
                 <td>{{$agrochem->quantity}}</td>
                  <td>{{$agrochem->nameofperson}}</td>
                   <td>{{number_format($agrochem->hectaresapplied,2)}}</td>
    
        </tr>
            @empty
                
            @endforelse

        </tbody>
    </table>

     <h4>E. OTHER CULTIVATED CROPS</h4>
    <table>
        <thead><th>Plot Name</th><th>Crops Culivated</th><th>Estimated Hectares</th><th>Location</th>
        </thead>
        <tbody>
            @forelse ($farmentrance->reportothercrops() as $othercrop )
                        <tr>
                <td>{{$othercrop->plotname}}</td>
                 <td>{{$othercrop->crop}}<</td>
                  <td>{{$othercrop->area}}<</td>
                   <td>{{$othercrop->location}}<</td>
        </tr>
                
            @empty
                
            @endforelse

        </tbody>
    </table>
 <h4>F. FARM HISTORY</h4>
    <table  style="border: 1px solid black; margin-top:5px;">
        <tbody>
        <thead>
            <th>#</th>
            <th style="width:50%">Question</th>
            <th style="width:20%">Answer</th>
            <th style="width:25%">Remarks</th>
        </thead>
    @forelse ($reportquestions as $reportquestion )
    <tr>
        <td>{{$counter+1}}</td>
        <td>{{$reportquestion->question }}</td>
        <td class="col-2">

            @switch($reportquestion->questiontype)
                @case("TYPEA")
                <!-- Html to handle TypeB questions (Yes/NO)-->
                <div class="form-check">
                    @if ($reportquestion->answer==1)
                    <input class="form-check-input"   disabled checked type="radio" id="yes" name="answers[{{$counter}}]" value="1" >
                    @else
                    <input class="form-check-input" disabled type="radio" id="yes" name="answers[{{$counter}}]" value="1" >
                    @endif
                    <label class="form-check-label" for="yes">YES</label>
                </div>
                <div class="form-check">
                    @if ($reportquestion->answer==2)
                    <input class="form-check-input" disabled  checked type="radio" id="no" name="answers[{{$counter}}]" value="2">
                    @else
                    <input class="form-check-input" disabled type="radio" id="no" name="answers[{{$counter}}]" value="2">
                    @endif
                    <label class="form-check-label" for="no">NO</label>
                </div>
                    
                    @break
                @case("TYPEB")
         <!-- Html to handle TypeB questions (Poor/fair/good/v.good)-->
            <div class="form-check">
                @if ($reportquestion->answer==1)
                <input class="form-check-input"    disabled checked type="radio" id="poor" name="answers[{{$counter}}]" value="1" >
                @else
                <input class="form-check-input" disabled type="radio" id="poor" name="answers[{{$counter}}]" value="1" required>
                @endif
                <label class="form-check-label" for="poor">Poor</label>
            </div>
            <div class="form-check">
                @if ($reportquestion->answer==2)
                <input class="form-check-input"   disabled checked type="radio" id="fair" name="answers[{{$counter}}]" value="2">
                @else
                <input class="form-check-input"   disabled type="radio" id="fair" name="answers[{{$counter}}]" value="2">
                @endif
                <label class="form-check-label" for="fair">Fair</label>
            </div>
            <div class="form-check">
                @if ($reportquestion->answer==3)
                <input class="form-check-input"   disabled checked type="radio" id="good" name="answers[{{$counter}}]" value="3">
                @else
                <input class="form-check-input"  disabled type="radio" id="good" name="answers[{{$counter}}]" value="3">
                @endif
                <label class="form-check-label" for="good">Good</label>
            </div>
            <div class="form-check">
                @if ($reportquestion->answer==4)
                <input class="form-check-input"   disabled checked type="radio" id="vgood" name="answers[{{$counter}}]" value="4">
                @else
                <input class="form-check-input"   disabled type="radio" id="vgood" name="answers[{{$counter}}]" value="4">
                @endif
                <label class="form-check-label" for="vgood">Very Good</label>
            </div> 
                @break

                @case("TYPEC") 
                <!-- Html to handle TypeB questions (comments only)-->
                <div class="form-check">
                    <input class="form-check-input"   disabled checked type="radio" id="commentonly" name="answers[{{$counter}}]" value="1" >
                    <label class="form-check-label" for="commentsonly">Comment Only</label>
                </div>   
                @break
            
                @default
                    
            @endswitch
        </td>
        <td>{{$reportquestion->sectionidcomments}}</td>
    </tr>
    @php
    $counter+=1;
    @endphp
    @empty
        
    @endforelse
        
        </tbody>
    </table>

    <!-- Declaration page-->
    <div style="margin-top: 20px">
    <table class="table table-bordered" style="border: 1px solid black">
        <tbody>
            <tr>
                <td colspan="2"><b>DECLARATION</b></td>
            </tr>
           <tr><td style="width: 50%" class="text-justify">
            <p>I, <b><u>{{$farm->farmname}}</u></b>, the farm owner, declare that, this information is correct, and on this day pledge to adhere
             at all times to the conditions for the UEBT/RA/MABA certification process and sustainable agriculture production </p><br>
            
            <div class="text-center">
                @if (!empty($farm->signaturepath))
                     <img src="{{public_path('/storage/'.$farm->signaturepath)}}" alt="" width="70px"><br>
                @endif
           
            <span style="border-top: 1px dashed rgb(86, 84, 84)">Signature/Thumbprint</span><br><br>
           </div><br><br>

            <span style="border-top: 1px dashed rgb(86, 84, 84)">Date: {{$farmentrance->regdate}}</span>
        </td><td>
        <p>I, the field officer, confirm that the above information is correct.</p> 
        <br>
           <b><u>{{$farmentrance->reportinspectorname()->name}}</u></b><br>
           (Name of Field Officer) 

           <br>
            
           <div class="text-center">
            @if (!empty($farmentrance->reportinspectorname()->signaturepath))
                <img src="{{public_path('/storage/'.$farmentrance->reportinspectorname()->signaturepath)}}" alt="" width="70px"><br>

            @else
            <br><br>    
            @endif
            
            <span style="border-top: 1px dashed rgb(86, 84, 84)">Signature/Thumbprint</span><br><br>
           </div>
 
            

            <span style="border-top: 1px dashed rgb(86, 84, 84)">Date:{{$farmentrance->regdate}}</span>



        </td></tr>
        </tbody>
    </table>
    </div>
@forelse ($farmentrance->reportprodhistory() as $farmplot )

    <div style="page-break-before: always;">
        <h4>FARM SKETCH</h4>
        <table>
            <tr>
                <td style="width: 25%"><b>NAME OF FARMER: </b></td>
                <td style="width: 25%">{{strtoupper($farm->farmname)}}</td>
                <td style="width: 25%"><b>FARM CODE:</b> </td>
                <td style="width: 25%">{{$farm->farmcode}}</td>
            </tr>
            <tr>
                <td style="width: 25%"><b>NAME OF FARM: </b></td>
                <td style="width: 25%">{{$farmplot->plotname}}</td>
                <td style="width: 25%"><b>FARM SIZE:</b> </td>
                <td style="width: 25%">{{$farmplot->fuarea}} Ha</td>
            </tr>
                        <tr>
                <td style="width: 25%"><b>LOCATION OF FARM:</b> </td>
                <td style="width: 25%">{{$farm->community}}</td>
                <td style="width: 25%"><b>NAME OF CROP: </b></td>
                <td style="width: 25%">{{$farm->crop}}</td>
            </tr>
                                    <tr>
                <td style="width: 25%"><b>LATITUDE:</b> </td>
                <td style="width: 25%">{{$farmplot->fulatitude}}</td>
                <td style="width: 25%"><b>LONGITUDE: </b></td>
                <td style="width: 25%">{{$farmplot->fulongitude}}</td>
            </tr>
            <tr>
                <td colspan="4" style="padding: 5px;">
                    @if (!empty($farmplot->imagefilepath))
                        <img src="{{public_path($farmplot->imagefilepath)}}" alt="" style="max-height: 600px; max-width: 100%;">
                    @endif           
                </td>
            </tr>
                        <tr>
                <td colspan="4">
                    @if (!empty($farmplot->imagefilepath))
                        {{url($farmplot->imagefilepath)}}
                    @endif           
                </td>
            </tr>
        </table>
    
@empty
    
@endforelse



    </div>
</div>
</div>

