<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 10px; color: #212529; }
        h2, h3 { margin: 0; padding: 0; }
        .header { text-align: center; margin-bottom: 10px; }
        .header h2 { font-size: 16px; }
        .header h3 { font-size: 12px; font-weight: normal; margin-top: 2px; }
        .meta { width: 100%; margin-bottom: 10px; border-collapse: collapse; }
        .meta td { padding: 3px 6px; font-size: 10px; }
        table.data { width: 100%; border-collapse: collapse; }
        table.data th, table.data td { border: 1px solid #333; padding: 4px; font-size: 9px; text-align: left; }
        table.data th { background-color: #e9ecef; font-weight: bold; }
        .text-muted { color: #6c757d; }
        .footer { margin-top: 10px; font-size: 8px; color: #6c757d; text-align: right; }
    </style>
</head>
<body>

@php
    $reporttype = 'NIL';
    if (strpos($reportname->reportname, 'Entrance') !== false) {
        $reporttype = 'Entrance';
    }
@endphp

<div class="header">
    <h2>B &amp; R SPICES NIGERIA LTD</h2>
    <h3>INSPECTION SUMMARY REPORT</h3>
</div>

<table class="meta">
    <tr>
        <td><b>Season:</b> {{ $season }}</td>
        <td><b>Report Name:</b> {{ $reportname->reportname }}</td>
        <td><b>Report State:</b> {{ $state }}</td>
        <td><b>Total Records:</b> {{ $internalinspection->count() }}</td>
    </tr>
</table>

@if ($reporttype == 'Entrance')
    <table class="data">
        <thead>
            <tr>
                <th>Farmer Name</th>
                <th>Farm Code</th>
                <th>Gender</th>
                <th>Year of Birth</th>
                <th>ID NO</th>
                <th>Plot name</th>
                <th>Plot Size (ha)</th>
                <th>Plot Lat.</th>
                <th>Plot Long.</th>
                <th>No of Plots</th>
                <th>Total Farm Size (ha)</th>
                <th>Estimated yield (kg)</th>
                <th>Non Ginger Hectare</th>
                <th>Previous Year Del.</th>
                <th>Previous 2 Years Del.</th>
                <th>Previous 3 Years Del.</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($internalinspection as $inspection)
                <tr>
                    <td>{{ $inspection->getfarm()->farmname }}</td>
                    <td>{{ $inspection->getfarm()->farmcode }}</td>
                    <td>{{ $inspection->getfarm()->gender }}</td>
                    <td>{{ $inspection->getfarm()->yob }}</td>
                    <td>{{ $inspection->getfarm()->nationalidnumber }}</td>
                    <td>{{ $inspection->getplotdetails()->plotname ?? 'N/A' }}</td>
                    <td>{{ $inspection->getplotdetails()->fuarea ?? 'N/A' }}</td>
                    <td>{{ $inspection->getplotdetails()->fulatitude ?? 'N/A' }}</td>
                    <td>{{ $inspection->getplotdetails()->fulongitude ?? 'N/A' }}</td>
                    <td>{{ $inspection->getfarm()->getreportfarmcount($season) }}</td>
                    <td>{{ number_format($inspection->getfarm()->getreportfarmarea($season), 2) }}</td>
                    @if (!empty($inspection->farmentrance))
                        <td>{{ number_format($inspection->farmentrance->getestimatedyield(), 2) }}</td>
                        <td>{{ number_format($inspection->getothercropsize(), 4) }}</td>
                        <td>{{ !empty($inspection->farmentrance->reportvolcropdel()[0]) ? number_format($inspection->farmentrance->reportvolcropdel()[0]->value, 2) : '' }}</td>
                        <td>{{ !empty($inspection->farmentrance->reportvolcropdel()[1]) ? number_format($inspection->farmentrance->reportvolcropdel()[1]->value, 2) : '' }}</td>
                        <td>{{ !empty($inspection->farmentrance->reportvolcropdel()[2]) ? number_format($inspection->farmentrance->reportvolcropdel()[2]->value, 2) : '' }}</td>
                    @else
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    @endif
                </tr>
            @empty
                <tr><td colspan="16" class="text-muted">No records found.</td></tr>
            @endforelse
        </tbody>
    </table>
@else
    <table class="data">
        <thead>
            <tr>
                <th>Farmer Name</th>
                <th>Farm Code</th>
                <th>Phone Number</th>
                <th>House Lat.</th>
                <th>House Long.</th>
                <th>No of Plots</th>
                <th>Total Farm Size (ha)</th>
                <th>Approval Committee Conditions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($internalinspection as $inspection)
                <tr>
                    <td>{{ $inspection->getfarm()->farmname }}</td>
                    <td>{{ $inspection->getfarm()->farmcode }}</td>
                    <td>{{ $inspection->getfarm()->phonenumber }}</td>
                    <td>{{ $inspection->getfarm()->latitude }}</td>
                    <td>{{ $inspection->getfarm()->longitude }}</td>
                    <td>{{ $inspection->getfarm()->getreportfarmcount($season) }}</td>
                    <td>{{ number_format($inspection->getfarm()->getreportfarmarea($season), 2) }}</td>
                    <td>
                        <b>IMS Comments:</b> {{ $inspection->comments }}
                        @if (!empty($inspection->conditions))
                            <br><b>Committee:</b> {{ $inspection->conditions }}
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-muted">No records found.</td></tr>
            @endforelse
        </tbody>
    </table>
@endif

<div class="footer">Generated {{ now()->format('Y-m-d H:i') }}</div>

</body>
</html>
