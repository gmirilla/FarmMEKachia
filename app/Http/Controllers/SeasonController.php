<?php

namespace App\Http\Controllers;

use App\Models\farm;
use App\Models\Season;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SeasonController extends Controller
{
    public function index()
    {
        return view('admin.season_management', [
            'currentSeasonString' => Season::currentString(),
            'currentSeason'       => Season::current(),
            'seasons'             => Season::orderByDesc('created_at')->get(),
            'inspectors'          => User::where('roles', 'INSPECTOR')->orWhere('roles', 'ADMINISTRATOR')->orderBy('name')->get(),
            'farms'               => farm::orderBy('community')->orderBy('farmcode')->get(),
        ]);
    }

    public function open(Request $request)
    {
        $request->validate(['nextinspection_date' => 'required|date']);
        $seasonString = Season::currentString();

        DB::transaction(function () use ($request, $seasonString) {
            $season = Season::firstOrNew(['season' => $seasonString]);
            $season->fill([
                'status'              => 'OPEN',
                'nextinspection_date' => $request->nextinspection_date,
                'opened_by'           => Auth::id(),
            ])->save();
            farm::query()->update([
                'farmstate'      => 'ACTIVE',
                'inspectorid' => null,
                'nextinspection' => $request->nextinspection_date,
            ]);
        });


        return redirect()->route('season.index')->with('success', "Season {$seasonString} opened.");
    }

    public function close()
    {
        $seasonString = Season::currentString();

        DB::transaction(function () use ($seasonString) {
            $season = Season::firstOrNew(['season' => $seasonString]);
            $season->fill([
                'status'    => 'CLOSED',
                'closed_by' => Auth::id(),
            ])->save();
            farm::where('farmstate', 'ACTIVE')->update(['farmstate' => 'CLOSED']);
        });

        return redirect()->route('season.index')->with('success', "Season {$seasonString} closed.");
    }

    public function massassign(Request $request)
    {
        $request->validate([
            'inspector_id' => 'required|exists:users,id',
            'farm_ids'     => 'required|array|min:1',
            'farm_ids.*'   => 'exists:farms,id',
        ]);
        $count = farm::whereIn('id', $request->farm_ids)->update(['inspectorid' => $request->inspector_id]);
        return redirect()->route('season.index')->with('success', "{$count} farm(s) assigned.");
    }
}
