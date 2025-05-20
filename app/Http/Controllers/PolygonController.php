<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolygonModels;

class PolygonController extends Controller
{

    public function __construct()
    {
        $this->polygon = new PolygonModels();
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = [
            'title' => 'Polygon List',
            'polygons' => $this->polygon->all(),
        ];

        return view('polygon.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('polygon.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate Request
        $request->validate(
            [
                'name' => 'required|unique:polygon,name',
                'description' => 'required',
                'geom_polygon' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_polygon.required' => 'Geometry Polygon is required',
            ]
        );

        // Create image directory if not exists
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777, true);
        }

        // Get Image File
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'geom' => $request->geom_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // Create Data
        if (!$this->polygon->create($data)) {
            return redirect()->route('map')->with('error', 'Polygon failed to add');
        }

        // Redirect to Map
        return redirect()->route('map')->with('success', 'Polygon has been added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $polygon = $this->polygon->find($id);

        if (!$polygon) {
            return redirect()->route('map')->with('error', 'Polygon not found');
        }

        $data = [
            'title' => 'Polygon Details',
            'polygon' => $polygon,
        ];

        return view('polygon.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
{
    $polygon = $this->polygon->find($id);

    if (!$polygon) {
        return redirect()->route('map')->with('error', 'Polygon not found');
    }

    $data = [
        'title' => 'Edit Polygon',
        'polygon' => $polygon,
        'id' => $id, // Kirim $id ke view
    ];

    return view('edit-polygon', $data);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validation
        $request->validate(
            [
                'name' => 'required|unique:polygon,name,' . $id,
                'description' => 'required',
                'geom_polygon' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description polygon is required',
                'geom_polygon.required' => 'Geom polygon is required',
            ]
        );

        $polygon = $this->polygon->find($id);

        if (!$polygon) {
            return redirect()->route('map')->with('error', 'Polygon not found');
        }

        // Create image directory if not exists
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777, true);
        }

        // Get old image file name
        $old_image = $polygon->image;

        // Get Image File
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);

            // Delete old image file
            if ($old_image && file_exists('./storage/images/' . $old_image)) {
                unlink('./storage/images/' . $old_image);
            }
        } else {
            $name_image = $old_image;
        }

        $data = [
            'geom' => $request->geom_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // Update Data
        if (!$polygon->update($data)) {
            return redirect()->route('map')->with('error', 'Polygon failed to update');
        }

        // Redirect to map
        return redirect()->route('map')->with('success', 'Polygon has been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $polygon = $this->polygon->find($id);

        if (!$polygon) {
            return redirect()->route('map')->with('error', 'Polygon not found');
        }

        $imagefile = $polygon->image;

        if (!$polygon->delete()) {
            return redirect()->route('map')->with('error', 'Polygon failed to delete');
        }

        // Delete image file
        if ($imagefile && file_exists('./storage/images/' . $imagefile)) {
            unlink('./storage/images/' . $imagefile);
        }

        return redirect()->route('map')->with('success', 'Polygon has been deleted successfully');
    }
}
