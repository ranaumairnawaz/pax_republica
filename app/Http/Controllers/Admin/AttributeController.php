<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = Attribute::orderBy('name')->paginate(15);
        return view('admin.attributes.index', compact('attributes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.attributes.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
            'code' => 'required|string|max:255|unique:attributes,code|regex:/^[A-Z_]+$/',
            'description' => 'nullable|string',
            'is_primary' => 'sometimes|boolean',
            'default_value' => 'required|integer',
            'min_value' => 'required|integer',
            'max_value' => 'required|integer|gt:min_value', // max_value must be greater than min_value
        ]);

        Attribute::create($validated);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Attribute $attribute)
    {
        // Usually not needed for admin CRUD, often redirect to edit
        return redirect()->route('admin.attributes.edit', $attribute);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Attribute $attribute)
    {
        return view('admin.attributes.edit', compact('attribute'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name,' . $attribute->id,
            'code' => 'required|string|max:255|unique:attributes,code,' . $attribute->id . '|regex:/^[A-Z_]+$/',
            'description' => 'nullable|string',
            'is_primary' => 'sometimes|boolean',
            'default_value' => 'required|integer',
            'min_value' => 'required|integer',
            'max_value' => 'required|integer|gt:min_value', // max_value must be greater than min_value
        ]);

        $attribute->update($validated);

        return redirect()->route('admin.attributes.index')->with('success', 'Attribute updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        // Add check for dependencies before deleting if necessary
        try {
            $attribute->delete();
            return redirect()->route('admin.attributes.index')->with('success', 'Attribute deleted successfully.');
        } catch (\Illuminate\Database\QueryException $e) {
            // Handle potential foreign key constraint violation
            return redirect()->route('admin.attributes.index')->with('error', 'Could not delete attribute. It might be in use.');
        }
    }
}
