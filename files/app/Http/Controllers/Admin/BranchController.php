<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\BranchRequest;

class BranchController extends Controller
{
    public function index()
    {
        $query = Branch::withCount('foods')->orderBy('name');

        if (auth()->user()->role == 'branch_admin') {
            $query->where('id', auth()->user()->rest_id);
        }

        $branches = $query->paginate(10);
        
        return Inertia::render('Admin/Branches/Index', [
            'branches' => [
                'data' => $branches->items(),
                'meta' => [
                    'current_page' => $branches->currentPage(),
                    'from' => $branches->firstItem(),
                    'last_page' => $branches->lastPage(),
                    'links' => $branches->linkCollection()->toArray(),
                    'path' => $branches->path(),
                    'per_page' => $branches->perPage(),
                    'to' => $branches->lastItem(),
                    'total' => $branches->total(),
                ],
            ],
            'flash' => [
                'success' => session('success'),
                'error' => session('error')
            ],
        ]);
    }

    public function create()
    {
        $cities = DB::table('cities')->select('id', 'name')->orderBy('id', 'desc')->get();
        $locals = DB::table('locals')->select('id', 'name', 'area_id')->get();
        return Inertia::render('Admin/Branches/Form', compact('cities', 'locals'));
    }

    public function get_area($city_id)
    {
        $area = DB::table('areas')->where('district_id', $city_id)->select('id', 'name')->get();
        return response()->json($area);
    }

public function store(BranchRequest $request)
{
    try {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('uploads', 'public');
        }

        Branch::create($data);

        return redirect()->route('admin.branches.index')
            ->with('success', 'Branch created successfully.');
    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}


    public function edit(Branch $branch)
    {
          $cities = DB::table('cities')->select('id', 'name')->orderBy('id', 'desc')->get();
        $locals = DB::table('locals')->select('id', 'name', 'area_id')->get();
        return Inertia::render('Admin/Branches/Form', [
            'branch' => $branch,
            'cities' => $cities,
            'locals'=> $locals
        ]);
    }

    public function update(BranchRequest $request, Branch $branch)
    {
        try {
            $branch->update($request->validated());
            return redirect()->route('admin.branches.index')
                ->with('success', 'Branch updated successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating branch. Please try again.');
        }
    }

    public function destroy(Branch $branch)
    {
        try {
            if ($branch->foods()->exists()) {
                return back()->with('error', 'Cannot delete branch with associated foods.');
            }

            $branch->delete();
            return back()->with('success', 'Branch deleted successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Error deleting branch. Please try again.');
        }
    }
}
