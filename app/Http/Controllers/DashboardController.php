<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Payment;
use App\Models\Software;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
{
    $summary = [
        'total_users' => User::count(),
        'total_payments' => Payment::sum('amount'),
        'due_payments' => Payment::whereHas('subscription', function($q) {
            $q->where('payment_status', 'Due');
        })->sum('amount'),
        'pending_payments' => Payment::whereHas('subscription', function($q) {
            $q->where('payment_status', 'Pending');
        })->sum('amount'),
        'total_softwares' => Software::count(),
        'total_packages' => Package::count(),
        'active_subscriptions' => Subscription::where('is_active', 1)->count(),
        'expired_subscriptions' => Subscription::where('end_at', '<', now())->count(),
    ];

    $recentPayments = Payment::with(['subscription.user', 'subscription.package'])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get()
        ->map(function($payment) {
            return [
                'id' => $payment->id,
                'amount' => $payment->amount,
                'created_at_format' => $payment->created_at->format('Y-m-d'),
                'subscription' => [
                    'user' => $payment->subscription->user,
                    'package' => $payment->subscription->package,
                    'payment_status' => $payment->subscription->payment_status
                ]
            ];
        });

    // Get users data if needed for other parts of the dashboard
    $users = []; // Add your users query here if needed

    return Inertia::render('Dashboard', [
        'summary' => $summary,
        'recentPayments' => $recentPayments,
        'users' => $users,
        'pagination' => [] // Add pagination data if needed
    ]);
}
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
