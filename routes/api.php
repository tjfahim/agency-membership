<?php

use App\Http\Resources\SubscriptionResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/send-member-details', function (Request $request) {
    if ($request->header('Authorization') !== 'Bearer ' . env('DFASHION_FOOTWEAR_TOKEN')) {
        return response()->json([
            'status' => 'Error',
            'error' => 'Token Failed!',
        ]);
    }

    //Data
    $user = User::with([
            'subscription' => function ($q) {
                $q->select('id', 'start_at', 'end_at', 'payment_status', 'user_id', 'package_id');
            },
            'subscription.package' => function ($q) {
                $q->select('id', 'name');
            }
        ])->select('id', 'name', 'email')->where('email', $request->email)->first();

     if($user && $user->subscription){
        $user->subscription = new SubscriptionResource($user->subscription);
     }

    return response()->json([
        'status' => 'Success',

        'user' => $user,
    ]);
});

Route::post('/send-member-details-cnf', function (Request $request) {
    if ( $request->header('Authorization') !== 'Bearer ' . env('CNF_TOKEN')) {
        return response()->json([
            'status' => 'Error',
            'error' => 'Token Failed!',
        ]);
    }

    //Data
    $user = User::with([
            'subscription' => function ($q) {
                $q->select('id', 'start_at', 'end_at', 'payment_status', 'user_id', 'package_id');
            },
            'subscription.package' => function ($q) {
                $q->select('id', 'name');
            }
        ])->select('id', 'name', 'email')->where('email', $request->email)->first();

     if($user && $user->subscription){
        $user->subscription = new SubscriptionResource($user->subscription);
     }

    return response()->json([
        'status' => 'Success',

        'user' => $user,
    ]);
});

Route::post('/send-member-details-factory-tracking', function (Request $request) {
    if ( $request->header('Authorization') !== 'Bearer ' . env('TRACKING_FACTORY_TOKEN')) {
        return response()->json([
            'status' => 'Error',
            'error' => 'Token Failed!',
        ]);
    }

    //Data
    $user = User::with([
            'subscription' => function ($q) {
                $q->select('id', 'start_at', 'end_at', 'payment_status', 'user_id', 'package_id');
            },
            'subscription.package' => function ($q) {
                $q->select('id', 'name');
            }
        ])->select('id', 'name', 'email')->where('email', $request->email)->first();

     if($user && $user->subscription){
        $user->subscription = new SubscriptionResource($user->subscription);
     }

    return response()->json([
        'status' => 'Success',

        'user' => $user,
    ]);
});
