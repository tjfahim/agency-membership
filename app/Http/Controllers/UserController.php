<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::select(['id', 'name', 'email', 'created_at'])->doesntHave('roles')->paginate(20);
        $pagination = [
            'total' => $users->total(),
            'per_page' => $users->perPage(),
            'current_page' => $users->currentPage(),
            'last_page' => $users->lastPage(),
            'from' => $users->firstItem(),
            'to' => $users->lastItem(),
        ];
        return Inertia::render('Users/index', ['users' => $users, 'pagination' => $pagination]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Users/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:50'],
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(6)],
            'web_url'=>['required','url','min:5'],
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'web_url'=>rtrim($request->web_url,'/'),
            'status'=>false,
        ]);

        $response = Http::withHeaders([
            'TECHLAB_API_TOKEN'=>env('TECHLAB_API_TOKEN'),
        ])->post($request->web_url.'/api/membership/register',[
            'name'=>$request->name,
            'email'=>$request->email,
            'password'=>$request->password,
            'status'=>false,
        ]);


        return to_route('users.index')->with(['success' => 'User Created Successfully']);
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
    public function edit(User $user)
    {
        //
        return Inertia::render('Users/edit', ['User' => $user]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => ['required', 'string', 'min:4', 'max:50'],
            'email' => ['required', 'email'],
            'password' => ['required', Password::min(6)],
        ]);

        User::where('id', $id)->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        return to_route('users.index')->with(['success' => 'User Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        User::where('id', $id)->delete();
        return to_route('users.index')->with(['success' => "User Deleted Successfully"]);
    }
}
