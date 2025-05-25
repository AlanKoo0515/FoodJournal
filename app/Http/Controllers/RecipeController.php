<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Recipe::query();
        
        if ($search = $request->input('search')) {
            $query->where('title', 'like', "%{$search}%");
        }

        if ($servings = $request->input('servings')) {
            if ($servings === 'more') {
                $query->where('servings', '>', 3);
            } else {
                $query->where('servings', $servings);
            }
        }

        if ($cookTime = $request->input('cook_time')) {
            if ($cookTime === 'quick') {
                $query->where('cook_time', '<=', 30);
            } elseif ($cookTime === 'mid') {
                $query->whereBetween('cook_time', [31, 60]);
            } elseif ($cookTime === 'long') {
                $query->where('cook_time', '>', 60);
            }
        }

        if ($year = $request->input('year')) {
            $query->whereYear('created_at', $year);
        }

        $recipes = $query->latest()->paginate(10);
        return view('recipes.index', compact('recipes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('recipes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'calories_per_serving' => 'required|integer|min:0',
            'servings' => 'required|integer|min:1',
            'cook_time' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        // Handle file upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('recipes', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        // Attach the currently authenticated user
        $validated['user_id'] = auth()->id();

        Recipe::create($validated);
        return redirect()->route('recipes.index')->with('success', 'Recipe created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $recipe = Recipe::with(['user', 'reviews'])->findOrFail($id);
        return view('recipes.show', compact('recipe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $recipe = Recipe::findOrFail($id);
        return view('recipes.edit', compact('recipe'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $recipe = Recipe::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'ingredients' => 'required|string',
            'instructions' => 'required|string',
            'calories_per_serving' => 'required|integer|min:0',
            'servings' => 'required|integer|min:1',
            'cook_time' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('recipes', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        $recipe->update($validated);
        return redirect()->route('recipes.index')->with('success', 'Recipe updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $recipe = Recipe::findOrFail($id);
        $recipe->delete();
        return redirect()->route('recipes.index')->with('success', 'Recipe deleted successfully!');
    }
}
