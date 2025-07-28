<?php

namespace App\Http\Middleware;

use App\Models\Package;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        // return [
        //     ...parent::share($request),
        //     //
        // ];
        return array_merge(parent::share($request),[
        'flash' => [
            'success' => fn () => $request->session()->get('success'),
            'error' => fn () => $request->session()->get('error'),
            'store'=> fn()=> $request->session()->get('store'),
            'update'=> fn()=> $request->session()->get('update'),
            'delete'=> fn()=> $request->session()->get('delete'),
             'software_empty' => fn () => $request->session()->get('software_empty'),
        ],
        'users' => fn()=>User::all(),
        'packages'=>fn()=>Package::all(),
        'avatar'=>fn()=> Auth::check() && Auth::user()?->profile?->avatar !='' ? asset('storage/'.Auth::user()->profile->avatar) : asset('assets/img/admin.png'),
        ]);
    }
}
