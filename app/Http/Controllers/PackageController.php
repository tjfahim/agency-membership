<?php

namespace App\Http\Controllers;

use App\Models\Package;
use App\Models\Software;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $packages = Package::select(['id', 'name', 'price', 'duration_type', 'duration'])->paginate(20);
        $pagination = [
            'total' => $packages->total(),
            'per_page' => $packages->perPage(),
            'current_page' => $packages->currentPage(),
            'last_page' => $packages->lastPage(),
            'from' => $packages->firstItem(),
            'to' => $packages->lastItem(),
        ];
        $softwares = Software::all();
        return Inertia::render('Package/index', ['packages' => $packages, 'pagination' => $pagination,'softwares'=>$softwares]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $softwares = Software::all();
        return Inertia::render('Package/create', ['softwares' => $softwares]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'price' => ['required', 'numeric'],
            'duration_type' => 'required|in:daily,weekly,monthly,yearly',
            'duration' => ['required', 'integer'],
            'description' => ['required', 'string'],
        ]);

        $package = Package::create([
            'name' => $request->name,
            'price' => $request->price,
            'duration_type' => trim(strtolower($request->duration_type)),
            'duration' => $request->duration,
            'description' => $request->description,
            'is_free' => $request->is_free,
        ]);
        if (!empty($request->selectedSoftwares)) {
            $package->softwares()->syncWithoutDetaching($request->selectedSoftwares);
        }
        return to_route('package.index')->with('success', 'Package created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        $ourPackage = Package::findOrFail($id);
        return response()->json(['ourPackage' => $ourPackage, 'softwares' => $ourPackage->softwares]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $softwares = Software::all();
        $ourPackage = Package::findOrFail($id);
        return Inertia::render('Package/edit', ['ourPackage' => $ourPackage, 'softwares' => $softwares, 'selectedSoftwares' => $ourPackage->softwares->pluck('id')]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'price' => ['required', 'numeric'],
            'duration_type' => 'required|in:daily,weekly,monthly,yearly',
            'duration' => ['required', 'integer'],
            'description' => ['required', 'string'],
        ]);

        $package = Package::findOrFail($id);

        $package->update([
            'name' => $request->name,
            'price' => $request->price,
            'duration_type' => trim(strtolower($request->duration_type)),
            'duration' => $request->duration,
            'description' => $request->description,
            'is_free' => $request->is_free,
        ]);
        if (!empty($request->selectedSoftwares)) {
            $package->softwares()->sync($request->selectedSoftwares);
        }
        return to_route('package.index')->with('success', 'Package Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        Package::where('id', $id)->delete();
        return to_route('package.index')->with('success', 'Package deleted successfully!');
    }
}
