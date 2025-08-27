<?php

namespace App\Http\Controllers\Admin;

use Exception;
use Inertia\Inertia;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class PackageController extends Controller
{
    public function index(){
        $packages = Package::with(['foods', 'branch', 'category'])->paginate(10);
        return Inertia::render('Admin/Packages/Index', compact(['packages']));
    }

    public function create(){
        $branches = DB::table('branches')->select('id', 'name')->get();
        $categories = DB::table('categories')->select('id', 'name')->whereNull('deleted_at')->get();
        return Inertia::render('Admin/Packages/Create', compact(['categories', 'branches']));
    }

    public function edit($pack){
        $packageData = Package::with('foods', 'branch', 'category')->find($pack);
        $branches = DB::table('branches')->select('id', 'name')->get();
        $categories = DB::table('categories')->select('id', 'name')->whereNull('deleted_at')->get();
        return Inertia::render('Admin/Packages/Edit', compact(['categories', 'branches', 'packageData']));
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

    public function update(Request $request, Package $package)
    {
        try {
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

            // If a new image is uploaded, delete the old one and store new
            if ($request->hasFile('image')) {
                if ($package->image && Storage::disk('public')->exists($package->image)) {
                    Storage::disk('public')->delete($package->image);
                }
                $validatedData['image'] = $request->file('image')->store('package', 'public');
            }

            // Update package
            $package->update($validatedData);

            // Sync foods (replace old relations with new ones)
            $package->foods()->sync($selectedFood);

            DB::commit();

            return redirect()->route('admin.package.index')
                            ->with('success', 'Package updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            logger($e->getMessage());
            return back()->with('error', 'Error in updating package, please try again later');
        }
    }


    public function destroy(Package $package){
        try{
            $package->delete();
            return back()->with('success', 'Package deleted successfully');
        }catch(Exception $e){
            return back()->with(['error', 'Package was not deleted'], 500);
        }
    }
}
