<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    /**
     * Display the settings (only one record exists)
     */
    public function index()
    {
        // Get the first (and only) settings record or create an empty one if none exists
        $setting = Setting::firstOrNew();
        
        return Inertia::render('Setting/index', [
            'setting' => $setting,
            'flash' => [
                'success' => session('success'),
            ],
        ]);
    }

    /**
     * Update the settings (since we only have one record)
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'renew_time' => 'required|string|max:255',
            'master_password' => 'nullable|string|max:255',
        ]);

        // Get the first record or create a new one if none exists
        $setting = Setting::firstOrNew();
        $setting->renew_time = $request->renew_time;
        $setting->master_password = $request->master_password;
        $setting->save();

        return redirect()->route('setting.index')
            ->with('success', 'Settings saved successfully!');
    }
}