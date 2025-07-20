<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PitchRequest;
use App\Models\Pitch;
use App\Models\Stadium;

class PitchController extends Controller
{
    /**
     * Display a listing of pitches.
     */
    public function index()
    {
        $pitches = Pitch::with(['stadium'])->paginate(config('app.per_page'));

        return view('admin.pitches.index', compact('pitches'));
    }

    /**
     * Show the form for creating a new pitch.
     */
    public function create()
    {
        $stadiums = Stadium::active()->get();

        return view('admin.pitches.create', compact('stadiums'));
    }

    /**
     * Store a newly created pitch.
     */
    public function store(PitchRequest $request)
    {
        $data = $request->validated();

        $data['amenities'] = $request->input('amenities', []);

        Pitch::create($data);

        return redirect()->route('admin.pitches.index')
            ->with('success', 'Pitch created successfully!');
    }

    /**
     * Display the specified pitch.
     */
    public function show(Pitch $pitch)
    {
        $pitch->load(['stadium']);
        return view('admin.pitches.show', compact('pitch'));
    }

    /**
     * Show the form for editing the specified pitch.
     */
    public function edit(Pitch $pitch)
    {
        $stadiums = Stadium::all();
        return view('admin.pitches.edit', compact('pitch', 'stadiums'));
    }

    /**
     * Update the specified pitch.
     */
    public function update(PitchRequest $request, Pitch $pitch)
    {
        $data = $request->validated();

        $data['amenities'] = $request->input('amenities', []);

        $pitch->update($data);

        return redirect()->route('admin.pitches.index')
            ->with('success', 'Pitch updated successfully!');
    }

    /**
     * Remove the specified pitch.
     */
    public function destroy(Pitch $pitch)
    {
        $pitch->delete();

        return redirect()->route('admin.pitches.index')
            ->with('success', 'Pitch deleted successfully!');
    }
}
