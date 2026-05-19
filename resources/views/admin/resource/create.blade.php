@extends('layouts.admin')
@section('title', 'Upload Resource')
@section('page-title', 'Upload New Resource')

@section('content')
<div style="max-width:640px;">
    <a href="{{ route('admin.resources.index') }}" style="font-size:13px; color:#c9a227; margin-bottom:20px; display:inline-block;">← Back to Resources</a>

    <div class="admin-card" style="padding:28px;">
        <form method="POST" action="{{ route('admin.resources.store') }}" enctype="multipart/form-data">
            @csrf
            <div style="display:grid; gap:20px;">

                <div>
                    <label>Resource Title *</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="e.g. Evangelism Training Manual Vol.1">
                    @error('title')<p style="color:#fca5a5;font-size:11px;margin-top:4px;">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label>Description</label>
                    <textarea name="description" rows="3" placeholder="Brief description of this resource...">{{ old('description') }}</textarea>
                </div>

                <div>
                    <label>Category</label>
                    <select name="category_id">
                        <option value="">— No Category —</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id')==$cat->id?'selected':'' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label>File * <span style="color:#4a5568;">(PDF, DOCX, PPT, ZIP — max 50MB)</span></label>
                    <input type="file" name="file" required accept=".pdf,.docx,.doc,.ppt,.pptx,.zip" style="padding:8px 12px;">
                    @error('file')<p style="color:#fca5a5;font-size:11px;margin-top:4px;">{{ $message }}</p>@enderror
                </div>

                <div style="display:flex; align-items:center; gap:10px; padding:12px 16px; background:rgba(201,162,39,0.05); border:1px solid rgba(201,162,39,0.1); border-radius:8px;">
                    <input type="checkbox" name="is_public" id="is_public" value="1" {{ old('is_public', true) ? 'checked' : '' }} style="width:16px; height:16px;">
                    <div>
                        <label for="is_public" style="margin:0; cursor:pointer; color:#e2e8f0;">Make Publicly Visible</label>
                        <p style="font-size:11px; color:#6b7280; margin-top:2px;">Members can browse and download this resource</p>
                    </div>
                </div>

                <div style="display:flex; gap:12px; padding-top:8px; border-top:1px solid rgba(201,162,39,0.1);">
                    <button type="submit" class="btn-gold" style="padding:10px 28px;">Upload Resource</button>
                    <a href="{{ route('admin.resources.index') }}" class="btn-secondary" style="padding:10px 20px; display:inline-block;">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
