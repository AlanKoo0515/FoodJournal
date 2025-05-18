<?php

namespace App\Services;

use App\Models\CulinaryExperience;

class CulinaryExperienceService
{
    public function getAll($search = null, $category = null, $ownership = null)
    {
        $query = CulinaryExperience::query();
        if ($search) {
            $query->where('title', 'like', "%$search%")
                ->orWhere('description', 'like', "%$search%")
                ->orWhere('category', 'like', "%$search%")
                ->orWhere('location', 'like', "%$search%")
                ->orWhere('date', 'like', "%$search%");
        }
        if ($category) {
            $query->where('category', $category);
        }

        // Filter by ownership
        if ($ownership === 'my') {
            $query->where('user_id', auth()->id());
        } elseif ($ownership === 'others') {
            $query->where('user_id', '!=', auth()->id());
        }

        // Sort by creation date
        $query->orderBy('created_at', 'asc');

        return $query->get();
    }

    public function create(array $data)
    {
        return CulinaryExperience::create($data);
    }

    public function update($id, array $data)
    {
        $experience = CulinaryExperience::findOrFail($id);
        $experience->update($data);
        return $experience;
    }

    public function delete($id)
    {
        $experience = CulinaryExperience::findOrFail($id);
        return $experience->delete();
    }

    public function find($id)
    {
        return CulinaryExperience::findOrFail($id);
    }
}
