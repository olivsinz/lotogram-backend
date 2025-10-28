<?php

namespace App\Http\Controllers\Dashboard;

use Illuminate\Support\Str;
use App\Http\Controllers\Controller;

class SupportController extends Controller
{
    public function enumList()
    {
        $enumsPath = __DIR__ . '/../../../Enum';
        $enumFiles = scandir($enumsPath);

        $enumsArray = [];

        foreach ($enumFiles as $file)
        {
            if ($file !== '.' && $file !== '..') {

                require_once $enumsPath . '/' . $file;

                $enumName = pathinfo($file, PATHINFO_FILENAME);
                $enumClass = "app\Enum\\$enumName";


                if (enum_exists($enumClass)) {
                    $cases = $enumClass::cases();
                    $i = 0;
                    foreach ($cases as $case) {

                        if (method_exists($case, 'isVisible'))
                        {
                            if ($case->isVisible($case->value) == true)
                            {
                                $enumsArray[Str::snake($enumName)][$i]['value'] = $case->value;
                                $enumsArray[Str::snake($enumName)][$i]['key'] = strtolower($case->name);
                                $enumsArray[Str::snake($enumName)][$i]['string'] = $case->toString($case->value);
                                $i++;
                            }

                            continue;
                        }

                        $enumsArray[Str::snake($enumName)][$i]['value'] = $case->value;
                        $enumsArray[Str::snake($enumName)][$i]['key'] = strtolower($case->name);
                        $enumsArray[Str::snake($enumName)][$i]['string'] = $case->toString($case->value);
                        $i++;
                    }
                }
            }
        }

        return response()->json([
            'data' => $enumsArray
        ]);
    }

    public function apiVersion()
    {
        return response()->json([
            'data' => [
                'version' => config('app.app_version')
            ]
        ]);
    }
}
