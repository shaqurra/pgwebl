<?php

namespace App\Http\Controllers;

use App\Models\PolylineModels;
use Illuminate\Http\Request;

class PolylineController extends Controller
{
    public function __construct()
    {
        $this->polyline = new PolylineModels();
    }


    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validate request
        $request->validate(
            [
                'name' => 'required|unique:polyline,name',
                'description' => 'required',
                'geom_polyline' => 'required',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ],
            [
                'name.required' => 'Name is required',
                'name.unique' => 'Name already exists',
                'description.required' => 'Description is required',
                'geom_polyline.required' => 'Geometry polyline is required',

            ]
        );

        //Create image directory if not exists
        if (!is_dir('storage/images')) {
            mkdir('./storage/images', 0777);
        }

        //Get Image File
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $name_image = time() . "_polyline." . strtolower($image->getClientOriginalExtension());
            $image->move('storage/images', $name_image);
        } else {
            $name_image = null;
        }

        $data = [
            'geom' => $request->geom_polyline,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $name_image,
        ];

        // create data
        if (!$this->polyline->create($data)) {
            return redirect()->route('map')->with('error', 'Polyline failed to added');
        }

        // redirect to map
        return redirect()->route('map')->with('success', 'Polyline has been added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagefile = $this->polyline->find($id)->image;

        if (!$this->polyline->destroy($id)){
            return redirect()->route('map')->with('error', 'Polyline failed to delete');
        }

        //Delete image file
        if ($imagefile != null){
            if(file_exists('./storage/images/'. $imagefile)) {
                unlink('./storage/images/'. $imagefile);
            }
        }
        return redirect()->route('map')->with('success', 'Polyline has been delete');
    }
}
