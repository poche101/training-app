<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceApiController extends Controller
{
    public function index(Request $request)
    {
        $query = Resource::where('is_public', true)->with('category');

        if ($request->search)   $query->where('title', 'like', '%'.$request->search.'%');
        if ($request->category) $query->where('category_id', $request->category);

        return response()->json($query->latest()->paginate(12));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:resource_categories,id',
            'file_url'    => 'required|url',
            'file_type'   => 'required|string',
            'file_size'   => 'nullable|string',
            'is_public'   => 'boolean',
        ]);
        $data['uploaded_by'] = auth()->id();
        return response()->json(Resource::create($data), 201);
    }

    public function destroy($id)
    {
        Resource::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
