<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PolygonModels;
use App\Models\ProvinsiJakarta; // Tambahkan ini

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
        // Ganti ini untuk ambil data dari provinsi_jakarta
        $data = [
            'title' => 'Polygon List',
            'polygons' => ProvinsiJakarta::all(),
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
        // Validasi input
        $request->validate([
            'name' => 'required|unique:polygon,name',
            'description' => 'required',
            'geom_polygon' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Pastikan direktori gambar ada
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777, true);
        }

        // Simpan gambar jika ada
        $name_image = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        }

        $data = [
            'geom' => $request->geom_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
            'user_id' => auth()->user()->id,
        ];

        if (!$this->polygon->create($data)) {
            return redirect()->route('map')->with('error', 'Polygon failed to add');
        }

        return redirect()->route('map')->with('success', 'Polygon has been added successfully');
    }

    public function show(string $id)
    {
        $polygon = $this->polygon->find($id);

        if (!$polygon) {
            return redirect()->route('map')->with('error', 'Polygon not found');
        }

        return view('polygon.show', [
            'title' => 'Polygon Details',
            'polygon' => $polygon,
        ]);
    }

    public function edit($id)
    {
        $polygon = $this->polygon->find($id);

        if (!$polygon) {
            return redirect()->route('map')->with('error', 'Polygon not found');
        }

        return view('edit-polygon', [
            'title' => 'Edit Polygon',
            'polygon' => $polygon,
            'id' => $id,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|unique:polygon,name,' . $id,
            'description' => 'required',
            'geom_polygon' => 'required',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $polygon = $this->polygon->find($id);

        if (!$polygon) {
            return redirect()->route('map')->with('error', 'Polygon not found');
        }

        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777, true);
        }

        $old_image = $polygon->image;

        $name_image = $old_image;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polygon." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);

            if ($old_image && file_exists('./storage/images/' . $old_image)) {
                unlink('./storage/images/' . $old_image);
            }
        }

        $data = [
            'geom' => $request->geom_polygon,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
            'user_id' => auth()->user()->id,
        ];

        if (!$polygon->update($data)) {
            return redirect()->route('map')->with('error', 'Polygon failed to update');
        }

        return redirect()->route('map')->with('success', 'Polygon has been updated successfully');
    }

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

        if ($imagefile && file_exists('./storage/images/' . $imagefile)) {
            unlink('./storage/images/' . $imagefile);
        }

        return redirect()->route('map')->with('success', 'Polygon has been deleted successfully');
    }
}