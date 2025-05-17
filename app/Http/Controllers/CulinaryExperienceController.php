<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CulinaryExperienceService;
use App\Models\CulinaryExperience;

class CulinaryExperienceController extends Controller
{
    protected $service;

    public function __construct(CulinaryExperienceService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $category = $request->input('category');
        $experiences = $this->service->getAll($search, $category);
        return view('experiences.index', compact('experiences'));
    }   

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('experiences.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'image_url' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);
    
        // Handle file upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('experiences', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }
    
        // Attach the currently authenticated user
        $validated['user_id'] = auth()->id();
    
        $experience = $this->service->create($validated);
        return redirect()->route('experiences.index')->with('success', 'Experience created successfully!');
    }
    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $experience = $this->service->find($id);
        return view('experiences.show', compact('experience'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $experience = $this->service->find($id);
        return view('experiences.edit', compact('experience'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'date' => 'required|date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('experiences', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        $this->service->update($id, $validated);
        return redirect()->route('experiences.index')->with('success', 'Experience updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->service->delete($id);
        return redirect()->route('experiences.index')->with('success', 'Experience deleted successfully!');
    }
}
