<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Inertia\Inertia;
use App\Models\Branch;
use App\Models\Package;
use App\Models\Category;
use Illuminate\Http\Request;


class FoodMenuController extends Controller
{
    public function index(Request $request, Branch $branch)
    {
            $foods = Food::with(['category', 'extraOptions'])
            ->where('branch_id', $branch->id)
            ->where('is_available', true)
            ->orderBy('sort_order')
            ->get()
            ->groupBy('category_id');

            $packages = Package::with(['foods'])->where('branche_id', $branch->id)->get();



        $categories = Category::whereHas('packages', function ($query) use ($branch) {
            $query->where('branche_id', $branch->id);
        })->get();


        return Inertia::render('Customer/FoodMenu', [
            'branch' => $branch,
            'categories' => $categories,
            'foods' => $foods,
            'packages'=> $packages,
            'orderType' => $request->input('type', 'collection') // collection or delivery
        ]);
    }
}
