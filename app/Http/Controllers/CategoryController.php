<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');
        
        $query = Category::withCount(['assets', 'softwareLicenses']);
        
        if (in_array($sort, ['name', 'type', 'created_at'])) {
            $query->orderBy($sort, $direction);
        } else {
            $query->orderBy('name');
        }

        $categories = $query->get();
        return view('settings.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255', 'type' => 'required']);
        $cat = Category::create($request->all());
        AuditLog::record('create', "Kategori ditambahkan: {$cat->name}");
        return back()->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category)
    {
        $request->validate(['name' => 'required|string|max:255']);
        $category->update($request->all());
        AuditLog::record('update', "Kategori diperbarui: {$category->name}");
        return back()->with('success', 'Kategori berhasil diperbarui.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        AuditLog::record('delete', "Kategori dihapus: {$category->name}");
        return back()->with('success', 'Kategori berhasil dihapus.');
    }
}
