<?php

namespace App\Http\Controllers\Admin;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PackageController extends Controller
{
    public function index(){
        return Inertia::render('Admin/Packages/Index');
    }

    public function create(){
        $branches = DB::table('branches')->select('id', 'name')->get();
        $categories = DB::table('categories')->select('id', 'name')->get();
        return Inertia::render('Admin/Packages/Create', compact(['categories', 'branches']));
    }
}
