<?php

use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Models\Package;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;



Route::middleware('auth')->group(function(){
    //dashboard
    Route::get('/', [DashboardController::class,'index'])->name('dashboard');

    //logout
    Route::post('/logout',[UserAuthController::class,'destroy'])->name('logout');

     //user
    Route::resource('/users',UserController::class);
    Route::get('/user',function(){
        return Auth::user();
    })->name('user');

    //fetch response data
    Route::get('/users/no_role',function(){
        $users = User::doesntHave('roles')->get();
        return response()->json($users);
    })->name('users.no_role');

    Route::get('/packages',function(){
        $packages = Package::all();
        return response()->json($packages);
    })->name('ourPackages.index');

    Route::get('/fetchPackageData/{package}',function ($package){
        $package = Package::where('id',$package)->select('id','name','price','duration_type','duration','is_free')->firstOrFail();
        return response()->json($package);
    })->name('fetch.package');

    Route::get('/get_packages',function(){
        $packages = Package::all();
        return response()->json($packages);
    });


    //Menus
    Route::resource('/software',SoftwareController::class);
    Route::resource('/package',PackageController::class);
    Route::resource('/subscription',SubscriptionController::class);
    Route::resource('/payment',PaymentController::class);
    Route::get('/subscription/payment/{subscription}',[PaymentController::class,'paySubscription'])->name('pay.subscription');


    //Settings
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting', [SettingController::class, 'update'])->name('setting.update');
});


Route::middleware('guest')->group(function(){
    Route::inertia('/login','Auth/Login');
    Route::post('/login',[UserAuthController::class,'login'])->name('login');
    Route::inertia('/register','Auth/Register');
    Route::post('/register',[UserAuthController::class,'register'])->name('register');
   
});

