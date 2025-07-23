<?php

namespace App\Http\Controllers;

use App\Http\Requests\SubscriptionRequest;
use App\Models\Package;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $subscriptions = Subscription::select(['id','start_at','end_at','payment_status','user_id','package_id','assigned_by'])->with(['user:id,name','package:id,name','assignedBy:id,name'])->paginate(20);
        $pagination = [
            'total' => $subscriptions->total(),
            'per_page' => $subscriptions->perPage(),
            'current_page' => $subscriptions->currentPage(),
            'last_page' => $subscriptions->lastPage(),
            'from' => $subscriptions->firstItem(),
            'to' => $subscriptions->lastItem(),
        ];
        return Inertia::render('Subscription/index',['subscriptions'=>$subscriptions,'pagination'=>$pagination]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        // return Inertia::render('Subscription/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubscriptionRequest $request)
    {
        //
        // $validated = $request->validated();
        // $package = Package::findOrFail($validated['package_id']);
        // $duration = Package::DURATION_MAP[$package->duration_type];
        // $start_at = now();
        // $end_at = now()->addDays($duration);
        // Subscription::create([
        //     'user_id'=> $validated['user_id'],
        //     'package_id' => $validated['package_id'],
        //     'start_at' => $start_at,
        //     'end_at' => $end_at,
        //     'is_active' => $request['is_active'] ?? false,
        //     'auto_renew' => $request['auto_renew'] ?? false,
        //     'payment_status' => $validated['payment_status'],
        //     'assigned_by' => auth()->id(),
        // ]);
        
        // return to_route('subscription.index')->with(['success'=> "Subsciption created!"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $subscription = Subscription::select(['user_id','package_id','auto_renew','is_active','start_at','end_at','payment_status'])->with(['user:id,name','package:id,name'])->findOrFail($id);
        return Inertia::render('Subscription/show',['subscription'=>$subscription]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $subscription = Subscription::select(['id','user_id','package_id','auto_renew','is_active','start_at','end_at','payment_status'])->with(['user:id,name','package:id,name'])->findOrFail($id);
         return Inertia::render('Subscription/edit',['subscription'=>$subscription]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubscriptionRequest $request, string $id)
    {
        //
         $validated = $request->validated();
         $subscription = Subscription::findOrFail($id);
         $subscription->update([
            'user_id'=> $validated['user_id'],
            'package_id' => $validated['package_id'],
            'is_active' => $request['is_active'] ?? false,
            'auto_renew' => $request['auto_renew'] ?? false,
            'payment_status' => $validated['payment_status'],
            'assigned_by' => auth()->id(),
        ]);
        return to_route('subscription.index')->with(['success'=> "Subsciption updated!"]);

    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Subscription::where('id',$id)->delete();
        return to_route('subscription.index')->with(['success'=>'Deleted Successfully!']);
    }
}
