<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\Setting;
use Illuminate\Support\Arr;
use App\Models\HistoryLogger;
use App\Http\Controllers\Controller;
use App\Http\Resources\Dashboard\HistoryResource;
use App\Http\Requests\Dashboard\Setting\IndexRequest;
use App\Http\Requests\Dashboard\Setting\UpdateRequest;
use App\Http\Requests\Dashboard\Setting\HistoryRequest;

class SettingController extends Controller
{
    public function index(IndexRequest $request)
    {
        $settings = collect(Setting::all()->toArray());

        $grouped = $settings->mapToGroups(function (array $item, int $key) {
            return [$item['group'] => [
                'id' => $item['uuid'],
                'key' => $item['key'],
                'value' => $item['type'] == 'boolean' ? (boolean) $item['value'] : $item['value'],
                'type' => $item['type'],
                'available_value' => $item['available_value'],
            ]];
        });

        return response()->json([
            'data' => $grouped,
        ]);

    }

    public function update(UpdateRequest $request)
    {
        $setting = Setting::uuid($request->uuid);
        $setting->value = $request->value;
        $setting->save();

        return response()->noContent();
    }

    public function histories(HistoryRequest $request)
    {
        $setting = Setting::uuid($request->uuid);
        $histories = HistoryLogger::filterByOwnerable($setting->id, Setting::class)
            ->orderBy('created_at', 'desc')
            ->paginate($request->per_page);

        return HistoryResource::collection($histories);
    }
}
