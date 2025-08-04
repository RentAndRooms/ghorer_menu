<?php

namespace App\Http\Controllers;

use App\Models\Food;
use Inertia\Inertia;
use App\Models\Branch;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $categories = Category::whereHas('foods', function ($query) use ($branch) {
            $query->where('branch_id', $branch->id)
                ->where('is_available', true);
        })->get();



        return Inertia::render('Customer/FoodMenu', [
            'branch' => $branch,
            'categories' => $categories,
            'foods' => $foods,
            'orderType' => $request->input('type', 'collection') // collection or delivery
        ]);
    }
}
