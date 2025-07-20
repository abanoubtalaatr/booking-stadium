<?php

namespace App\Http\Controllers\Admin;

use App\Models\Stadium;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StadiumRequest;

class StadiumController extends Controller
{
    /**
     * Display a listing of stadiums.
     */
    public function index()
    {
        $stadiums = Stadium::with(['pitches'])->paginate(config('app.per_page'));
        return view('admin.stadiums.index', compact('stadiums'));
    }

    /**
     * Show the form for creating a new stadium.
     */
    public function create()
    {
        return view('admin.stadiums.create');
    }

    /**
     * Store a newly created stadium.
     */
    public function store(StadiumRequest $request)
    {
        Stadium::create($request->validated());

        return redirect()->route('admin.stadiums.index')
            ->with('success', 'Stadium created successfully!');
    }

    /**
     * Display the specified stadium.
     */
    public function show(Stadium $stadium)
    {
        $stadium->load(['pitches']);
        return view('admin.stadiums.show', compact('stadium'));
    }

    /**
     * Show the form for editing the stadium.
     */
    public function edit(Stadium $stadium)
    {
        return view('admin.stadiums.edit', compact('stadium'));
    }

    /**
     * Update the specified stadium.
     */
    public function update(StadiumRequest $request, Stadium $stadium)
    {
        $stadium->update($request->validated());

        return redirect()->route('admin.stadiums.index')
            ->with('success', 'Stadium updated successfully!');
    }

    /**
     * Remove the specified stadium.
     */
    public function destroy(Stadium $stadium)
    {
        $stadium->delete();

        return redirect()->route('admin.stadiums.index')
            ->with('success', 'Stadium deleted successfully!');
    }
}
