<?php

use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\SoftwareController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\Role;
use App\Models\Package;
use App\Models\Setting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


Route::get('/role', function () {
    $user = Auth::user()->hasRole('Admin');
    return response()->json(Auth::user()->name);

    return response()->json($user);
});

Route::middleware(['auth', Role::class])->group(function () {
    //dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    //report
    Route::get('/report', [ReportController::class, 'index'])->name('report.dashboard');

    //logout
    Route::post('/logout', [UserAuthController::class, 'destroy'])->name('logout');

    //user
    Route::resource('/users', UserController::class);
    Route::get('/user', function () {
        return Auth::user();
    })->name('user');

    //fetch response data
    Route::get('/users-without-roles', function () {
        $users = User::doesntHave('roles')->get()->map(function ($user) {
            return [
                'id'=>$user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at->setTimezone('Asia/Dhaka')->format('Y-m-d H:i A')
            ];
        });;

        return response()->json($users);
    })->name('users.no_role');

    Route::get('/packages', function () {
        $packages = Package::all();
        return response()->json($packages);
    })->name('ourPackages.index');

    Route::get('/fetchPackageData/{package}', function ($package) {
        $package = Package::where('id', $package)->select('id', 'name', 'price', 'duration_type', 'duration', 'is_free')->firstOrFail();
        return response()->json($package);
    })->name('fetch.package');

    Route::get('/get_packages', function () {
        $packages = Package::all();
        return response()->json($packages);
    });
    Route::get('/get_packages_payment', function () {
        $packages = Package::has('softwares')->get();
        return response()->json($packages);
    });

    //Menus
    Route::resource('/software', SoftwareController::class);
    Route::resource('/package', PackageController::class);
    Route::resource('/subscription', SubscriptionController::class);
    Route::resource('/payment', PaymentController::class);
    Route::get('/subscription/payment/{subscription}', [PaymentController::class, 'paySubscription'])->name('pay.subscription');

    //user deafault password
    Route::get('/default-password/{user}', [UserController::class, 'setPassword'])->name('default.password');
    Route::put('/user/{email}', [UserController::class, 'updateUser'])->name('update.user');
    //Settings
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting', [SettingController::class, 'update'])->name('setting.update');
    Route::get('/profile/setting', [ProfileController::class, 'profile_setting'])->name('profile.setting');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/change-password', [ProfileController::class, 'passwordUpdate'])->name('password.update');

    //authenticated user
    Route::get('/get_user' ,function(){
        $user = Auth::user();
        return response()->json($user);
    });
});





Route::get('/maintenance/clear', function () {
    // Clear caches
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');

    // Delete all files in storage/app/public except .gitignore
    $files = File::files(storage_path('app/public'));
    foreach ($files as $file) {
        File::delete($file->getPathname());
    }

    // Recreate the symbolic link to public/storage
    Artisan::call('storage:link');

    return '✅ Cache cleared, storage cleaned, and storage link created!';
})->name('maintenance.clear');

Route::inertia('/login', 'Auth/Login')->name('view_login');
Route::post('/login', [UserAuthController::class, 'login'])->name('login');
Route::inertia('/register', 'Auth/Register');
Route::post('/register', [UserAuthController::class, 'register'])->name('register');
Route::get('/time',function(){
    return Carbon::parse(now())->setTimezone('Asia/Dhaka')->toDateTimeString();
});
