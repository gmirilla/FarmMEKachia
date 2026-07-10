<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\farm;
use App\Models\farmentrance;
use App\Models\inspectionanswers;
use App\Models\internalinspection;
use App\Models\reportquestions;
use App\Models\reports;
use App\Models\reportsection;
use App\Models\approvalcommitte;
use App\Models\Season;
use App\Services\InspectionApprovalService;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;


class InternalinspectionController extends Controller
{
    public function __construct(private InspectionApprovalService $approvalService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Check if user is authorized to view resource
        Auth::check();
        $user = Auth::user();
        $entranceIds = reports::where('reportname', 'like', '%Entrance%')->pluck('id')->toArray();



        $query = DB::table('internalinspections')
            ->join('farms', 'internalinspections.farmid', '=', 'farms.id')
            ->join('reports', 'internalinspections.reportid', '=', 'reports.id')
            ->select(
                'farmcode',
                'farmname',
                'inspectionstate',
                'internalinspections.id as iid',
                'farms.id',
                'internalinspections.reportid as reportid',
                'score',
                'max_score',
                'internalinspections.inspectorid as inspectorid',
                'internalinspections.inspectiondate as inspectiondate',
                'internalinspections.created_at',
                'internalinspections.updated_at',
                'internalinspections.season as season',
                'reportname'
            )
            ->whereNotIn('internalinspections.reportid', $entranceIds);

        if ($user->roles === 'ADMINISTRATOR') {
            // Administrators see all inspections across all seasons
        } else {
            // Inspectors see only their own inspections for the current season
            $query->where('internalinspections.inspectorid', $user->id)
                  ->where('internalinspections.season', Season::currentString());
        }

        $inspections = $query->get();


        return view('inspection.inspection')->with('inspections', $inspections);
    }



    public function new(Request $request)
    {
        //Check if user is authorized to view resource
        Auth::check();
        $user = Auth::user();


        $inspections = internalinspection::where('inspectorid', $user->id)->get();
        $farms = farm::where('inspectorid', $user->id)
            ->where('farmstate', '!=', 'DISABLED')
            ->get();
        $reports = reports::where('reportstate', 'ACTIVE')->where('reportname', 'not like', '%Entrance%')->get();
        //dd($farms);

        return view('inspection.inspection_new')
            ->with('farms', $farms)
            ->with('reports', $reports);
    }


    public function start(Request $request)
    {
        //Check if user is authorized to view resource
        Auth::check();
        $user = Auth::user();

        $newinspection = internalinspection::where('id', $request->internalinspectionid)->get();
        $farm = farm::where('id', $request->farmid)->first();
        $report = reports::where('reportstate', 'ACTIVE')->where('id', $request->reportid)->first();
        $reportsections = reportsection::where('reportid', $request->reportid)->where('sectionstate', 'ACTIVE')->get();
        $reportquestions = reportquestions::where('reportid', $request->reportid)->where('questionstate', 'ACTIVE')->orderBy('question_seq', 'asc')->get();



        #Create and save a new inspection record if a new inspection process start
        if ($request->internalinspectionid == null) {
            $year0 = date('Y');
            $year1 = $year0 + 1;
            $currentseason = $year0 . "/" . $year1;
            $newinspection = new internalinspection();
            $newinspection->farmid = $farm->id;
            $newinspection->latitude = $farm->latitude;
            $newinspection->longitude = $farm->longitude;
            $newinspection->inspectorid = $user->id;
            $newinspection->reportid = $request->reportid;
            $newinspection->inspectionstate = 'ACTIVE';
            $newinspection->season = $currentseason;
            $newinspection->save();
        }
        $sectioncounter = $request->sectioncounter;

        if ($sectioncounter == null) {
        }

        if (strpos($report->reportname, 'Entrance')) {
            $farmentrance = farmentrance::where('id', $request->farmentrance)->first();
            $farmentrance->internalinspectionid = $newinspection->id;
            $farmentrance->save();
        }






        return view('inspection.inspection_start')
            ->with('farm', $farm)
            ->with('report', $report)
            ->with('reportsections', $reportsections)
            ->with('reportquestions', $reportquestions)
            ->with('sectioncounter', $sectioncounter)
            ->with('inspection', $newinspection);
    }


    public function nextsection(Request $request)
    {
        //TO DO GET ID and Rank of User
        // allow users to begin a new inspection of any farm assigned to them
        Auth::check();
        $user = Auth::user();

        // dd($request);
        //


        $inspection = internalinspection::where('id', $request->inspectionreportid)->first();

        $farm = farm::where('id', $request->farmid)->first();
        $report = reports::where('reportstate', 'ACTIVE')->where('id', $inspection->reportid)->first();
        $reportsections = reportsection::where('reportid', $inspection->reportid)->where('sectionstate', 'ACTIVE')->orderBy('section_seq', 'asc')->get();
        $reportquestions = reportquestions::where('reportid', $inspection->reportid)->where('questionstate', 'ACTIVE')->orderBy('question_seq', 'asc')->get();


        #Get the number of questions in the section
        $question = $request->question;
        $answers = $request->answers;
        $comments = $request->comments;



        #Check if the request has answers


        #loop thru array 
        # a validation check to limit double posting

        $score = $inspection->score;
        $updatescore = 0;
        if ($answers !== null) {
            for ($i = 0; $i < count($answers); $i++) {
                $checkanswer = inspectionanswers::where('internalinspectionid', $request->inspectionreportid)->where('questionid', $question[$i])->first();
                $checkcount = inspectionanswers::where('internalinspectionid', $request->inspectionreportid)->where('questionid', $question[$i])->count();
                if ($checkcount == 0) {
                    # This question has not been answered for this report before, save answer as new record
                    $newanswer = new inspectionanswers();
                    $newanswer->questionid = $question[$i];
                    $newanswer->answer = $answers[$i];
                    $newanswer->sectionidcomments = $comments[$i];
                    $newanswer->reportid = $report->id;
                    $newanswer->internalinspectionid = $request->inspectionreportid;
                    $newanswer->sectionid = $request->sectionid[$i];
                    $newanswer->save();
                } else {
                    # this question has been answered previously double posting update record
                    $updatescore = $updatescore + $checkanswer->answer;
                    $checkanswer->questionid = $question[$i];
                    $checkanswer->answer = $answers[$i];
                    $checkanswer->sectionidcomments = $comments[$i];
                    $checkanswer->reportid = $report->id;
                    $checkanswer->sectionid = $request->sectionid[$i];
                    $checkanswer->internalinspectionid = $request->inspectionreportid;
                    $checkanswer->save();
                }


                $score = $score + $answers[$i];
            }
        }; // Handle empty answers array
        #Update Inspection REport score
        $inspection->score = $score - $updatescore;
        $inspection->save();
        #Get Previous Answers
        $reportquestions = DB::table('reportquestions')
            ->leftJoin('inspectionanswers', 'reportquestions.id', '=', 'inspectionanswers.questionid') // Join the 'reportquestions' and 'answers' tables
            ->select(
                'reportquestions.id as id',
                'reportquestions.reportid  as reportid',
                'reportquestions.indicator as indicator',
                'reportquestions.reportsectionid as reportsectionid',
                'reportquestions.question_seq as question_seq',
                'reportquestions.question as question',
                'reportquestions.questiontype as questiontype',
                'reportquestions.questionstate as questionstate',
                'answer',
                'sectionidcomments'
            )
            ->where('reportquestions.reportid', $inspection->reportid)->where('reportquestions.questionstate', 'ACTIVE')
            ->where('internalinspectionid', $inspection->id)->orderBy('question_seq', 'asc')
            ->get();


        $sectioncounter = $request->sectioncounter;

        #check if at the last section 
        if ($sectioncounter == $reportsections->count()) {

            #UPDATE internal inspection state to submitted
            #update farm records to show last inspection date 
            $inspection->inspectionstate = 'SUBMITTED';
            $inspection->save();



            #Conditional logic to handle Entrance Reports
            switch (true) {
                case strpos($report->reportname, 'Entrance'):
                    # code...
                    $fcode = 'fcode=' . $farm->farmcode;
                    # $farm->farmstate='ACTIVE';
                    # $farm->save();
                    $inspection->inspectionstate = 'SUBMITTED';
                    $inspection->save();

                    return redirect()->route('onboarding');
                    break;

                default:
                    # code...
                    $farm->lastinspection = date('Y-m-d');
                    $farm->save();
                    break;
            }



            #Return  to Dashboard view 



            return redirect()->route('inspection');
        }

        #block of code to populate unanswered questions stack 
        $currentsection = $reportsections[$request->sectioncounter]->id;
        $test = inspectionanswers::where('sectionid', $currentsection)->where('internalinspectionid', $inspection->id)->get();
        if ($test->count() >= 1) {
            # "More questions answered"; Do nothing


        } else {
            # " No More questions answered" repopulate all questions on report

            $reportquestions = reportquestions::where('reportid', $inspection->reportid)->where('questionstate', 'ACTIVE')->orderBy('question_seq', 'asc')->get();
        }


        return view('inspection.inspection_start')
            ->with('farm', $farm)
            ->with('report', $report)
            ->with('reportsections', $reportsections)
            ->with('reportquestions', $reportquestions)
            ->with('sectioncounter', $sectioncounter)
            ->with('inspection', $inspection);
    }



    public function continue(Request $request)
    {
        //Check if user is authorized to view resource
        Auth::check();
        $user = Auth::user();

        #first determine how many sections does the report have
        $sectioncount = reportsection::where('reportid', $request->id)->count();

        #GET previously completed inspection sheet
        $inspection = internalinspection::where('id', $request->inspectionid)->first();

        if ($request->has('viewsheet')) {
            # code...



            return redirect()->route('iapprove', $request);
        }
        if ($request->has('printsheet')) {
            # code...

            return redirect()->route('printsheet', $request);
        }

        #Get Previous Answers
        $reportquestions = DB::table('reportquestions')
            ->leftJoin('inspectionanswers', 'reportquestions.id', '=', 'inspectionanswers.questionid') // Join the 'reportquestions' and 'answers' tables
            ->select(
                'reportquestions.id as id',
                'reportquestions.reportid  as reportid',
                'reportquestions.reportsectionid as reportsectionid',
                'reportquestions.indicator as indicator',
                'reportquestions.question_seq as question_seq',
                'reportquestions.question as question',
                'reportquestions.questiontype as questiontype',
                'reportquestions.questionstate as questionstate',
                'answer',
                'sectionidcomments'
            )
            ->where('reportquestions.reportid', $inspection->reportid)->where('reportquestions.questionstate', 'ACTIVE')
            ->where('internalinspectionid', $inspection->id)->orderBy('question_seq', 'asc')
            ->get();



        $farm = farm::where('id', $request->farmid)->first();
        $report = reports::where('reportstate', 'ACTIVE')->where('id', $inspection->reportid)->first();
        $reportsections = reportsection::where('reportid', $inspection->reportid)->where('sectionstate', 'ACTIVE')->get();
        $sectioncounter = 0;

        ###
        ###
        #block of code to populate unanswered questions stack 
        $reportsections = reportsection::where('reportid', $inspection->reportid)->where('sectionstate', 'ACTIVE')->orderBy('section_seq', 'asc')->get();
        $test = inspectionanswers::where('internalinspectionid', $inspection->id)->get();

        if ($test->count() > 1) {
            # "More questions answered"; Do nothing

        } else {
            # " No More questions answered" repopulate all questions on report

            $reportquestions = reportquestions::where('reportid', $inspection->reportid)->where('questionstate', 'ACTIVE')->orderBy('question_seq', 'asc')->get();
        }


        //return view('inspection.inspection_review')->with('reportquestion',$reportquestions);
        return view('inspection.inspection_start')
            ->with('farm', $farm)
            ->with('report', $report)
            ->with('reportsections', $reportsections)
            ->with('reportquestions', $reportquestions)
            ->with('sectioncounter', $sectioncounter)
            ->with('inspection', $inspection);
    }


    public function iapproval()
    {
        //Check if user is authorized to view resource
        Auth::check();
        $user = Auth::user();
        $year = date('Y');

        if ($user->roles === 'ADMINISTRATOR') {
            # only viewable by administrators
            $inspections = internalinspection::orderBy('created_at', 'desc')->get();


            $seasons = internalinspection::select('season')->distinct()->get();
            $reports = reports::where('reportstate', 'ACTIVE')->get();
            $approvalcommittee = approvalcommitte::where('is_active', true)->where('year', $year)->get();

            return view('inspection.inspection_review')
                ->with('reportquestions', $inspections)
                ->with('seasons', $seasons)->with('reports', $reports)
                ->with('approvalcommittees', $approvalcommittee);
        }



        return redirect()->route('unauthorized');
    }
    public function iapprove(Request $request)
    {
        Auth::check();
        $user = Auth::user();

        $inspection = $request->method() === 'GET'
            ? internalinspection::where('id', $request->inspectionid)->firstOrFail()
            : internalinspection::where('id', $request->iid)->firstOrFail();

        if ($user->roles === 'ADMINISTRATOR') {
            if ($request->has('viewsheet')) {
                return $this->approvalService->renderViewSheet($inspection, $request, $user);
            }

            if ($request->has('deletetbtn')) {
                $this->approvalService->delete($inspection);
                return redirect()->route('iapproval');
            }

            $this->approvalService->handleApprovalAction($inspection, $request, $user);
            $inspection->comments = $request->comments;
            $inspection->save();

            return redirect()->route('iapproval');
        }

        if ($user->roles === 'INSPECTOR') {
            if ($request->has('viewsheet')) {
                return $this->approvalService->renderViewSheet($inspection, $request, $user);
            }
        }

        return redirect()->route('unauthorized');
    }

    public function ireject(Request $request)
    {
        //Check if user is authorized to view resource
        Auth::check();
        $user = Auth::user();

        return redirect()->route('unauthorized');
    }

    public function viewsheet(Request $request)
    {

        return view('inspection.inspection_view_sheet');
    }

    private function getSummaryInspections(Request $request)
    {
        $season = $request->season;
        $state = $request->reportstate;
        $reportname = reports::where('id', $request->report)->first();
        if ($state == 'ALL') {
            $internalinspection = internalinspection::where('reportid', $request->report)->where('season', $season)->get();
        } else {
            $internalinspection = internalinspection::where('reportid', $request->report)->where('inspectionstate', $state)->where('season', $season)->get();
        }

        return [$internalinspection, $season, $state, $reportname];
    }

    public function summarypage(Request $request)
    {
        [$internalinspection, $season, $state, $reportname] = $this->getSummaryInspections($request);

        return view('inspection.inspection_summary', compact('internalinspection', 'season', 'state', 'reportname'));
    }

    public function summarypdf(Request $request)
    {
        [$internalinspection, $season, $state, $reportname] = $this->getSummaryInspections($request);

        $pdf = Pdf::loadView('pdf.inspectionsummarypdf', compact('internalinspection', 'season', 'state', 'reportname'))
            ->setPaper('a4', 'landscape');

        $pdfname = preg_replace('/\//', '_', $reportname->reportname . '_Summary_' . $season . '.pdf');

        return $pdf->download($pdfname);
    }

    public function icancel(Request $request)
    {

        $inspection = internalinspection::where('id', $request->inspectionid)->first();
        inspectionanswers::where('internalinspectionid', $inspection->id)->delete();
        $inspection->delete();

        return redirect()->route('inspection');
    }

    public function changedate(Request $request)
    {

        $inspection = internalinspection::where('id', $request->inspectionid)->first();

        $inspection->inspectiondate = $request->newinspectiondate;
        $inspection->save();


        return redirect()->route('inspection');
    }
}
