<?php

namespace App\Http\Controllers;

use App\Models\Software;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class SoftwareController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $softwares = Software::latest()->paginate(20);
        $pagination = [
            'total' => $softwares->total(),
            'per_page' => $softwares->perPage(),
            'current_page' => $softwares->currentPage(),
            'last_page' => $softwares->lastPage(),
            'from' => $softwares->firstItem(),
            'to' => $softwares->lastItem(),
        ];
        return Inertia::render('Software/index', [
            'softwares' => $softwares,
            'pagination' => $pagination,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        return Inertia::render('Software/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        $software = new Software();
        $software->name = $request->name;
        $software->slug = Str::slug(strtolower($request->name));
        $software->url = $request->url;
        $software->is_active = $request->is_active;
        $software->save();

        return to_route('software.index')->with('success','Software created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Software $software)
    {
        //
        $data = Software::findOrFail($software->id);
        return Inertia::render('Software/show', [
        'software' => $data,
        'flash' => [
            'success' => session('success'),
            'error' => session('error'),
        ],
    ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        $data = Software::findOrFail($id);
        return Inertia::render('Software/edit', ['software' => $data]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url',
        ]);

        $software = Software::findOrFail($id);
        $software->update([
            'name' => $request->name,
            'slug' => Str::slug(strtolower($request->name)),
            'url' => $request->url,
            'is_active' => $request->is_active
        ]);

        return to_route('software.index')
       ->with('success', 'Software updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
       Software::where('id',$id)->delete();
       return to_route('software.index')->with('success','Software deleted successfully');
    }
}
