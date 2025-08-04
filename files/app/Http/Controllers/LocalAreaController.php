<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreLocalRequest;
use App\Models\Local;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class LocalAreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $locals = DB::table('locals')->join('areas', 'locals.area_id', '=', 'areas.id')->join('cities', 'locals.city_id', '=', 'cities.id')->select('locals.*', 'areas.name as area_name', 'cities.name as city_name')->get();
        return Inertia::render('Admin/Locals/Index', compact('locals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $cities = DB::table('cities')->select('id', 'name')->orderBy('id', 'desc')->get();
        $areas = DB::table('areas')->select('id', 'name', 'district_id')->get();
        return Inertia::render('Admin/Locals/Create', compact('cities', 'areas'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLocalRequest $request)
    {
        try {
            $localAreaData = $request->validated();
            $id = DB::table('locals')->insertGetId($localAreaData);
            DB::table('r_search')->insert([
                'name' => $localAreaData['name'],
                'division_id' => 0,
                'district_id' => $localAreaData['city_id'],
                'thana_id' => $localAreaData['area_id'],
                'own_id' => $id
            ]);
            return redirect()->route('admin.locals.index');
        } catch (\Exception $e) {
            logger($e->getMessage());
            return back()->with('error', $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
