<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Tag;
use App\Models\HistoryLogger;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\TagResource;
use App\Http\Requests\Dashboard\Tag\ShowRequest;
use App\Http\Requests\Dashboard\Tag\IndexRequest;
use App\Http\Requests\Dashboard\Tag\StoreRequest;
use App\Http\Resources\Dashboard\HistoryResource;
use App\Http\Requests\Dashboard\Tag\UpdateRequest;
use App\Http\Requests\Dashboard\Tag\DestroyRequest;
use App\Http\Requests\Dashboard\Tag\HistoryRequest;

class TagController extends Controller
{
    public function index(IndexRequest $request)
    {
        $tags = Tag::filterByName($request->name)
            ->filterByColor($request->color)
            ->filterByStatus($request->is_active)
            ->paginate($request->per_page);

        return TagResource::collection($tags);
    }

    public function store(StoreRequest $request)
    {
        $tag = Tag::create($request->validated());

        return (new TagResource($tag))
            ->response()
            ->setStatusCode(Response::HTTP_CREATED);
    }

    public function show(ShowRequest $request)
    {
        $tag = Tag::uuid($request->uuid);
        return TagResource::make($tag);
    }

    public function update(UpdateRequest $request)
    {
        $tag = Tag::uuid($request->uuid);
        $tag->update($request->validated());

        return TagResource::make($tag);
    }

    public function destroy(DestroyRequest $request)
    {
        $tag = Tag::uuid($request->uuid);
        $tag->delete();

        return response()->noContent();
    }

    public function histories(HistoryRequest $request)
    {
        $tag = Tag::uuid($request->uuid);
        $histories = HistoryLogger::filterByOwnerable($tag->id, Tag::class)
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page);

        return HistoryResource::collection($histories);
    }
}
