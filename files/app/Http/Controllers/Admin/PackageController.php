<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Package;
use Exception;

class PackageController extends Controller
{
    public function index(){
        $packages = Package::with(['foods', 'branch', 'category'])->paginate(10);
        return Inertia::render('Admin/Packages/Index', compact(['packages']));
    }

    public function create(){
        $branches = DB::table('branches')->select('id', 'name')->get();
        $categories = DB::table('categories')->select('id', 'name')->get();
        return Inertia::render('Admin/Packages/Create', compact(['categories', 'branches']));
    }

    public function store(Request $request){
        try{
            DB::beginTransaction();
            $validatedData = $request->validate([
                'name' => 'required|string',
                'branche_id' => 'required|numeric',
                'category_id' => 'required|numeric',
                'preparation_time' => 'nullable',
                'base_price' => 'required|numeric',
                'image' => 'nullable|image:jpeg,jpg,png'
            ]);

            $selectedFood = $request->validate([
                'selectedFoods' => 'required|array'
            ])['selectedFoods'];

        
            if($request->hasFile('image')){
                $validatedData['image'] = $request->file('image')->store('package', 'public');
            }
            $package = Package::create($validatedData);
            $package->foods()->attach($selectedFood);
            DB::commit();
            return redirect()->route('admin.package.index');
        }catch(Exception $e){
            DB::rollBack();
            logger($e->getMessage());
            return back()->with('error', 'Error in creating package, please try again letter');
        }
    }
}
