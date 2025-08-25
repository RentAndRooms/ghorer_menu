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
        $Restaurant = DB::table('branches')->get();
        return Inertia::render('Admin/Users/Create', compact('Restaurant'));
    }

    public function store(UserRequest $request)
    {
        User::create($request->validated());
        return redirect()->route('admin.user.index')->with('success', 'User Created Successfully');
    }

       public function show($id)
    {
        $user = User::findOrFail($id);
        return Inertia::render('Admin/Users/Show', compact('user'));
    }

        public function edit($id)
    {
        $user = User::findOrFail($id);
        $Restaurant = DB::table('branches')->get();
        return Inertia::render('Admin/Users/Edit', compact('user', 'Restaurant'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'role' => 'required|string',
            'rest_id' => 'nullable|integer|exists:branches,id',
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = bcrypt($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        return redirect()->route('admin.user.index')->with('success', 'User updated successfully');
    }

    public function delete(User $user){
        $user->delete();
        return redirect()->back()->with('success', 'User delete successfully');
    }
}
