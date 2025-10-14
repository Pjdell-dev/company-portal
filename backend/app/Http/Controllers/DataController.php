<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;
use App\Models\System;

class DataController extends Controller
{
    public function companies()
    {
        return Company::select('id', 'name', 'code')->get();
    }

    public function modules(Request $request)
    {
        $user = $request->user();
        $submoduleIds = $user->submodules->pluck('id');

        $systems = System::with([
            'modules.submodules' => function ($query) use ($submoduleIds) {
                $query->whereIn('id', $submoduleIds);
            }
        ])->get();

        $result = [];
        foreach ($systems as $system) {
            $modules = [];
            foreach ($system->modules as $module) {
                if ($module->submodules->isNotEmpty()) {
                    $modules[] = [
                        'module_id' => $module->id,
                        'module_name' => $module->name,
                        'submodules' => $module->submodules->map(fn($s) => [
                            'id' => $s->id,
                            'name' => $s->name
                        ])->values()
                    ];
                }
            }
            if (!empty($modules)) {
                $result[] = [
                    'system_id' => $system->id,
                    'system_name' => $system->name,
                    'modules' => $modules
                ];
            }
        }

        return response()->json($result);
    }
}