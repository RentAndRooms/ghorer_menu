<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function index()
    {
        $users = DB::table('users')->select('id', 'name', 'phone', 'role')->get();
        return Inertia::render('Admin/Users/Index', compact('users'));
    }
    public function create()
    {
        $restuarent = DB::table('branches')->get();
        return Inertia::render('Admin/Users/Create', compact('restuarent'));
    }

    public function store(UserRequest $request)
    {
        User::create($request->validated());
        return redirect()->route('admin.user.index')->with('success', 'User Created Successfully');
    }
}
