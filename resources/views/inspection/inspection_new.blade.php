<x-layouts.app>
    <div>
        <div class="row">
            <form action="/inspection/start" method="post">
                @csrf
                <select name="farmid" id="farmselect" class="form-select">
                    @forelse ( $farms as $farm )
                        <option value="{{$farm->id ?? ' '}}">{{$farm->farmcode ?? 'N/A'}}| {{$farm->farmname ?? 'N/A'}}</option>  
                    @empty
                    <option value="0">No Farms have been assigned to you</option>
                    @endforelse
                </select>
                <select name="reportid" id="reportselect" class="form-select">
                    @forelse ( $reports as $report )
                        <option value="{{$report->id ?? ' '}}">{{$report->reportname ?? 'N/A'}}</option>  
                    @empty
                    <option value="0">No Reports configured on the system</option>
                    @endforelse
                </select>

                @if (count($farms)<1)
                    <button type="submit" disabled class="btn btn-primary">No Farm Assigned to User</button>
                @else
                <button type="submit"  class="btn btn-primary">Next</button>
                @endif 
                
                </form>
        </div>
    
    </div>
</x-layouts.app>
