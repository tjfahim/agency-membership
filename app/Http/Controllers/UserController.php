<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Illuminate\Support\Facades\Http;

use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $users = User::select(['id', 'name', 'email', 'created_at'])->doesntHave('roles')->latest()->paginate(20)->through(function ($user) {
            $user->created_at_formatted = Carbon::parse($user->created_at)->format('d M Y h:i A');
            return $user;
        });
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
            'web_url' => ['required', 'url', 'min:5'],
        ]);
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'web_url' => rtrim($request->web_url, '/'),
            'status' => false,
        ]);

        try {
            $response = Http::withHeaders([
                'TECHLAB_API_TOKEN' => env('TECHLAB_API_TOKEN'),
            ])->post($request->web_url . '/api/membership/register', [
                'name' => $request->name,
                'email' => $request->email,
                'password' => $request->password,
                'status' => false,
            ]);
        } catch (\Exception $e) {
            return to_route('users.index')->with(
                'error',
                'Website url or api token is mismatched'
            );
        }


        return to_route('users.index')->with(['success' => 'User Created Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
        return response()->json($user);
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
            'password' => Hash::make($request->password),
        ]);

        return to_route('users.index')->with(['success' => 'User Updated Successfully']);
    }

    public function updateUser(Request $request, User $user)
    {
        $rules = [
            'name' => ['required', 'string', 'min:4', 'max:50'],
            'email' => ['required', 'email'],
        ];

        if ($request->filled('password')) {
            $rules['password'] = ['required', Password::min(6)];
        }
        $validated = $request->validate($rules);

        $updatedData = [
            'name'=>$validated['name'],
            'email'=>$validated['email'],
        ];
        if(!empty($validated['password'])){
            $updatedData['password'] = Hash::make($validated['password']);
        }
        $user->update($updatedData);
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

    public function setPassword(User $user)
    {
        $settings = Setting::first();
        if ($settings) {
            $user->update([
                'password' => $settings->default_password
            ]);
            return redirect()->route('users.index')->with('success', 'Default password has been set.');
        }
        return redirect()->route('users.index')->with('error', 'No record has been found to set default password.');
    }
}
