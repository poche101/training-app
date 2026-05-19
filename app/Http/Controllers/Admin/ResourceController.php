<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Resource;
use App\Models\ResourceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'admin']);
    // }

    public function index()
    {
        $resources  = Resource::with(['category', 'uploader'])->latest()->paginate(15);
        $categories = ResourceCategory::all();
        return view('admin.resource.index', compact('resources', 'categories'));
    }

    public function create()
    {
        $categories = ResourceCategory::all();
        return view('admin.resource.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'category_id' => ['nullable', 'exists:resource_categories,id'],
            'file'        => ['required', 'file', 'max:51200', 'mimes:pdf,docx,doc,ppt,pptx,zip'],
            'is_public'   => ['boolean'],
        ]);

        $file     = $request->file('file');
        $path     = $file->store('resources', 'public');
        $fileSize = $this->formatFileSize($file->getSize());
        $fileType = $file->getClientOriginalExtension();

        $resource = Resource::create([
            'title'       => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'file_url'    => Storage::url($path),
            'file_type'   => $fileType,
            'file_size'   => $fileSize,
            'is_public'   => $request->boolean('is_public', true),
            'uploaded_by' => auth()->id(),
        ]);

        ActivityLog::record('Uploaded Resource', "Uploaded: {$resource->title}", $resource);

        return redirect()->route('admin.resources.index')
                         ->with('success', 'Resource uploaded successfully.');
    }

    public function destroy(Resource $resource)
    {
        ActivityLog::record('Deleted Resource', "Deleted: {$resource->title}");
        $resource->delete();

        return redirect()->route('admin.resources.index')
                         ->with('success', 'Resource deleted.');
    }

    // Categories
    public function categories()
    {
        $categories = ResourceCategory::withCount('resources')->get();
        return view('admin.resources.categories', compact('categories'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'icon' => ['nullable', 'string', 'max:10'],
        ]);

        ResourceCategory::create([
            'name' => $request->name,
            'slug' => \Illuminate\Support\Str::slug($request->name),
            'icon' => $request->icon,
        ]);

        return back()->with('success', 'Category created.');
    }

    private function formatFileSize(int $bytes): string
    {
        if ($bytes < 1024) return $bytes . ' B';
        if ($bytes < 1048576) return round($bytes / 1024, 2) . ' KB';
        return round($bytes / 1048576, 2) . ' MB';
    }
}
