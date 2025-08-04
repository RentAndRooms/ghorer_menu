<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BranchSelectionController extends Controller
{
    public function index(Request $request)
    {
        $userLat = $request->input('latitude');
        $userLng = $request->input('longitude');

        $query = Branch::query()->active();

        if ($userLat && $userLng) {
            $query->withDistance($userLat, $userLng)
                ->orderBy('distance');
        } else {
            $query->orderBy('name');
        }
        $locals = DB::table('r_search')->select('id', 'name', 'division_id', 'district_id', 'own_id')->get();

        $branches = $query->get();

        return Inertia::render('Customer/BranchSelection', [
            'branches' => $branches,
            'locals' => $locals,
            'userLocation' => [
                'latitude' => $userLat ? (float)$userLat : null,
                'longitude' => $userLng ? (float)$userLng : null
            ]
        ]);
    }
}
