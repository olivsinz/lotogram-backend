<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\PlannedCompetition;
use App\Http\Controllers\Controller;
use App\Exceptions\CompetitionException;
use App\Http\Resources\Dashboard\PlannedCompetitionResource;
use App\Http\Requests\Dashboard\PlannedCompetition\ShowRequest;
use App\Http\Requests\Dashboard\PlannedCompetition\IndexRequest;
use App\Http\Requests\Dashboard\PlannedCompetition\StoreRequest;
use App\Http\Requests\Dashboard\PlannedCompetition\UpdateRequest;
use App\Http\Requests\Dashboard\PlannedCompetition\GetRewardRequest;
use App\Http\Requests\Dashboard\PlannedCompetition\EditRewardRequest;
use App\Http\Requests\Dashboard\PlannedCompetition\AssignRewardRequest;
use App\Http\Requests\Dashboard\PlannedCompetition\RevokeRewardRequest;
use App\Http\Resources\CompetitionAPI\PlannedCompetitionRewardResource;

class PlannedCompetitionController extends Controller
{
    public function index(IndexRequest $request)
    {
        $plannedComoetitions = PlannedCompetition::paginate();
        return PlannedCompetitionResource::collection($plannedComoetitions);
    }

    public function store(StoreRequest $request)
    {
        $plannedComoetition = PlannedCompetition::create($request->validated());
        return response()->json(new PlannedCompetitionResource($plannedComoetition), 201);
    }

    public function show(ShowRequest $request)
    {
        $plannedComoetition = PlannedCompetition::where('uuid', $request->uuid)->first();
        return new PlannedCompetitionResource($plannedComoetition);
    }

    public function update(UpdateRequest $request)
    {
        $plannedComoetition = PlannedCompetition::where('uuid', $request->uuid)->first();
        $plannedComoetition->update($request->validated());

        return new PlannedCompetitionResource($plannedComoetition);
    }

    public function getReward(GetRewardRequest $request)
    {
        $plannedComoetition = PlannedCompetition::uuid($request->uuid);
        return PlannedCompetitionRewardResource::collection($plannedComoetition->rewards()->paginate());
    }

    public function assignReward(AssignRewardRequest $request)
    {
        $plannedComoetition = PlannedCompetition::uuid($request->uuid);

        if ($plannedComoetition->competitions->count() > 0) {
            throw CompetitionException::alreadyCreated();
        }

        if ($plannedComoetition->rewards->sum('percentage') + $request->percentage > 100) {
            throw CompetitionException::totalPercentageExceeded();
        }

        $reward = $plannedComoetition->rewards()->create([
            'type' => $request->type,
            'percentage' => $request->percentage,
        ]);

        return response()->json(new PlannedCompetitionRewardResource($reward), 201);
    }

    public function editReward(EditRewardRequest $request)
    {
        $plannedComoetition = PlannedCompetition::uuid($request->uuid);

        if ($plannedComoetition->competitions->count() > 0) {
            throw CompetitionException::alreadyCreated();
        }

        $plannedComoetitionReward = $plannedComoetition->rewards()->uuid($request->reward_uuid);

        if ($plannedComoetition->rewards->sum('percentage') + $request->percentage - $plannedComoetitionReward->percentage > 100) {
            throw CompetitionException::totalPercentageExceeded();
        }

        $plannedComoetitionReward->update([
            'type' => $request->type,
            'percentage' => $request->percentage,
        ]);

        return new PlannedCompetitionRewardResource($plannedComoetitionReward);
    }

    public function revokeReward(RevokeRewardRequest $request)
    {
        $plannedComoetition = PlannedCompetition::uuid($request->uuid);

        if ($plannedComoetition->competitions->count() > 0) {
            throw CompetitionException::alreadyCreated();
        }

        $plannedComoetition->rewards()->uuid($request->reward_uuid)->delete();
        return response()->noContent();
    }
}
