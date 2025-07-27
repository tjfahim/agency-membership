<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentRequest;
use App\Models\Package;
use App\Models\Payment;
use App\Models\Subscription;
use App\Models\User;
use App\Service\UserStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $payments = Payment::select(['id', 'user_id', 'amount', 'payment_method', 'payment_status'])->with(['user:id,name'])->paginate(20);
        $pagination = [
            'total' => $payments->total(),
            'per_page' => $payments->perPage(),
            'current_page' => $payments->currentPage(),
            'last_page' => $payments->lastPage(),
            'from' => $payments->firstItem(),
            'to' => $payments->lastItem(),
        ];
        return Inertia::render('Payment/index', ['payments' => $payments, 'pagination' => $pagination]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $packages = Package::all();
        $users = User::doesntHave('roles')->select(['id', 'name'])->get();
        return Inertia::render('Payment/create', ['users' => $users, 'packages' => $packages]);
    }

    public function paySubscription(Subscription $subscription)
    {
        //    $subscription = $subscription->load(['user','package']);
        //    return Inertia::render('Payment/create',['subscription'=>$subscription]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(PaymentRequest $request)
    {
        $validated = $request->validated();

        DB::beginTransaction();

        try {
            $duration_map = Package::DURATION_MAP[$request->duration_type];
            $total_duration = intval($request->duration) * intval($duration_map);
            Log::info('try block is running');
            //subscription
            $subscription = Subscription::create([
                'user_id' => $validated['user_id'],
                'package_id' => $validated['package_id'],
                'start_at' => now(),
                'end_at' => now()->addDays($total_duration),
                'is_active' => $request['is_active'] ?? false,
                'auto_renew' => $request['auto_renew'] ?? false,
                'payment_status' => $validated['payment_status'],
                'assigned_by' => auth()->id(),
            ]);
            $softwares = $subscription->load(['package.softwares']);
            if (!$subscription->package || $subscription->package->softwares->isEmpty()) {
                // throw ValidationException::withMessages([
                //     'software.empty'=>'No software is included in this package. Please select softwares!'
                // ]);
                return Inertia::render('Payment/index', [
    'flash' => [
        'software_empty' => 'No software is included in this package. Please select softwares!',
    ],
    ]);
           }
            Log::info('subscription: ' . $subscription);
            //payment
            $subscription->payments()->create([
                'user_id' => $validated['user_id'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_status'],
                'subscription_id' => $subscription->id,
            ]);
            Log::info('payment created');
            DB::commit();
            Log::info('commited done');

            $userStatus = new UserStatus($subscription->user);
            $userStatus->activeStatus();

            return to_route('payment.index')->with(['success' => "Payment and Subscription created successfully!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('throws error' . $e->getMessage());
            return to_route('payment.index')->with(['error' => 'Something went wrong!' . $e->getMessage()]);
        }

        return to_route('payment.index')->with(['success' => 'Payment has done for ' . $request->user['name']]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Payment $payment)
    {
        //
        $payment = $payment->load(['user', 'subscription.package.softwares']);
        return Inertia::render('Payment/show', ['payment' => $payment]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Payment $payment)
    {
        //
        $payment = $payment->load(['user', 'subscription.package.softwares']);
        $packages = Package::all();
        $users = User::doesntHave('roles')->select(['id', 'name'])->get();
        return Inertia::render('Payment/edit', ['payment' => $payment, 'packages' => $packages, 'users' => $users]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PaymentRequest $request, Payment $payment)
    {
        //
        $validated = $request->validated();
        DB::beginTransaction();

        try {
            $duration_map = Package::DURATION_MAP[$request->duration_type];
            $total_duration = intval($request->duration) * intval($duration_map);
            Log::info('try block is running in update method');
            //subscription

            $subscription = Subscription::findOrFail($payment->subscription->id);

            $subscription->update([
                'user_id' => $validated['user_id'],
                'package_id' => $validated['package_id'],
                'start_at' => now(),
                'end_at' => now()->addDays($total_duration),
                'is_active' => $request['is_active'] ?? false,
                'auto_renew' => $request['auto_renew'] ?? false,
                'payment_status' => $validated['payment_status'],
            ]);
            Log::info('subscription: ' . $subscription);
            //payment
            $payment->update([
                'user_id' => $validated['user_id'],
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'payment_status' => $validated['payment_status'],
                'subscription_id' => $subscription->id,
            ]);
            Log::info('payment updated');
            DB::commit();
            Log::info('commited done');
            return to_route('payment.index')->with(['success' => "Payment and Subscription updated successfully!"]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error :' . $e->getMessage());
            return to_route('payment.index')->with(['error ' => 'Something went wrong!']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Payment $payment)
    {
        //
        Subscription::where('id', $payment->subscription_id)->delete();
        $payment->delete();
        return to_route('payment.index')->with(['success' => 'Deleted Successfully']);
    }
}
