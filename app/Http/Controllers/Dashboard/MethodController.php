<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Method;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\MethodResource;
use App\Http\Requests\Dashboard\Method\ShowRequest;
use App\Http\Requests\Dashboard\Method\IndexRequest;
use App\Http\Requests\Dashboard\Method\StoreRequest;
use App\Http\Requests\Dashboard\Method\UpdateRequest;

class MethodController extends Controller
{
    public function index(IndexRequest $request)
    {
        $methods = Method::paginate();
        return MethodResource::collection($methods);
    }

    public function store(StoreRequest $request)
    {
        $methods = Method::create($request->validated());
        return response(new MethodResource($methods), 201);
    }

    public function show(ShowRequest $request)
    {
        $method = Method::uuid($request->uuid);
        return new MethodResource($method);
    }

    public function update(UpdateRequest $request)
    {
        $method = Method::uuid($request->uuid);
        $method->update($request->validated());

        return new MethodResource($method);
    }
}
