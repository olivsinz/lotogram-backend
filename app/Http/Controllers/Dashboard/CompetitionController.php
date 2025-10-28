<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Competition;
use Illuminate\Http\Request;
use App\Enum\CompetitionStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\CompetitionResource;

class CompetitionController extends Controller
{
    public function index (Request $request)
    {
        $competition = Competition::paginate();
        $competition->load('plannedCompetition:id,uuid,title');

        return CompetitionResource::collection($competition);
    }

    public function show (Request $request)
    {
        $competition = Competition::uuid($request->uuid);
        $competition->load('plannedCompetition:id,uuid,title');

        return new CompetitionResource($competition);
    }
}
