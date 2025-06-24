<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class StasiunController extends Controller
{
    // API GeoJSON untuk map
    public function index()
    {
        $data = DB::table('stasiun_jabodetabek')->get();
        $features = [];

        foreach ($data as $stasiun) {
            if (isset($stasiun->lon) && isset($stasiun->lat)) {
                $geometry = [
                    "type" => "Point",
                    "coordinates" => [floatval($stasiun->lon), floatval($stasiun->lat)]
                ];
            } else {
                $geometry = null;
            }

            if ($geometry) {
                $features[] = [
                    "type" => "Feature",
                    "geometry" => $geometry,
                    "properties" => [
                        "id" => $stasiun->id,
                        "namobj" => $stasiun->namobj ?? '',
                        "kabkot" => $stasiun->kabkot ?? '',
                        "provinsi" => $stasiun->provinsi ?? '',
                        "user_id" => $stasiun->user_id ?? '',
                        "created_at" => $stasiun->created_at ?? '',
                        "updated_at" => $stasiun->updated_at ?? '',
                        "gambar" => $stasiun->gambar ?? '',
                        "lon" => $stasiun->lon,
                        "lat" => $stasiun->lat
                    ]
                ];
            }
        }

        return response()->json([
            "type" => "FeatureCollection",
            "features" => $features
        ]);
    }

    // Show detail satu stasiun
    public function show($id)
    {
        $stasiun = DB::table('stasiun_jabodetabek')->where('id', $id)->first();

        if (!$stasiun) {
            return response()->json(['error' => 'Stasiun not found'], 404);
        }

        return response()->json($stasiun);
    }

    // Tambah data stasiun (CREATE)
    public function store(Request $request)
    {
        // Log incoming request for debugging
        Log::info('Store request data:', $request->all());

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string', // Changed to nullable
            'geom_point' => 'nullable|string', // Changed to nullable
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'namobj' => $request->name,
            'kabkot' => $request->description ?? '', // Handle null description
            'user_id' => auth()->id() ?? 1,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        // Ambil lon dan lat dari geom_point (format: POINT(lon lat))
        if ($request->geom_point) {
            if (preg_match('/POINT\(([-\d\.]+) ([-\d\.]+)\)/', $request->geom_point, $m)) {
                $data['lon'] = floatval($m[1]);
                $data['lat'] = floatval($m[2]);
            }
        }

        // Handle file upload with better error handling
        if ($request->hasFile('photo')) {
            try {
                $file = $request->file('photo');
                $filename = time() . '_' . $file->getClientOriginalName();

                // Make sure directory exists
                $uploadPath = public_path('storage/images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                $file->move($uploadPath, $filename);
                $data['gambar'] = $filename;
            } catch (\Exception $e) {
                Log::error('File upload error: ' . $e->getMessage());
                return response()->json(['error' => 'File upload failed'], 400);
            }
        }

        try {
            $id = DB::table('stasiun_jabodetabek')->insertGetId($data);
            return response()->json(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            Log::error('Database insert error: ' . $e->getMessage());
            return response()->json(['error' => 'Database error'], 500);
        }
    }

    // Update data stasiun
    public function update(Request $request, $id)
    {
        // Log incoming request for debugging
        Log::info('Update request data for ID ' . $id . ':', $request->all());

        // Check if stasiun exists
        $stasiun = DB::table('stasiun_jabodetabek')->where('id', $id)->first();
        if (!$stasiun) {
            return response()->json(['error' => 'Stasiun not found'], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string', // Changed to nullable
            'geom_point' => 'nullable|string', // Changed to nullable
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $data = [
            'namobj' => $request->name,
            'kabkot' => $request->description ?? '', // Handle null description
            'updated_at' => now(),
        ];

        // Ambil lon dan lat dari geom_point (format: POINT(lon lat))
        if ($request->geom_point) {
            if (preg_match('/POINT\(([-\d\.]+) ([-\d\.]+)\)/', $request->geom_point, $m)) {
                $data['lon'] = floatval($m[1]);
                $data['lat'] = floatval($m[2]);
            }
        }

        // Handle file upload with better error handling
        if ($request->hasFile('photo')) {
            try {
                $file = $request->file('photo');
                $filename = time() . '_' . $file->getClientOriginalName();

                // Make sure directory exists
                $uploadPath = public_path('storage/images');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }

                // Delete old image if exists
                if (!empty($stasiun->gambar)) {
                    $oldImagePath = $uploadPath . '/' . $stasiun->gambar;
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                $file->move($uploadPath, $filename);
                $data['gambar'] = $filename;
            } catch (\Exception $e) {
                Log::error('File upload error: ' . $e->getMessage());
                return response()->json(['error' => 'File upload failed'], 400);
            }
        }

        try {
            DB::table('stasiun_jabodetabek')->where('id', $id)->update($data);
            return response()->json(['success' => true, 'message' => 'Data updated successfully']);
        } catch (\Exception $e) {
            Log::error('Database update error: ' . $e->getMessage());
            return response()->json(['error' => 'Database error'], 500);
        }
    }

    // Hapus data stasiun
    public function destroy($id)
    {
        try {
            // Get stasiun data to delete associated image
            $stasiun = DB::table('stasiun_jabodetabek')->where('id', $id)->first();

            if (!$stasiun) {
                return response()->json(['error' => 'Stasiun not found'], 404);
            }

            // Delete associated image file if exists
            if (!empty($stasiun->gambar)) {
                $imagePath = public_path('storage/images/' . $stasiun->gambar);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Delete from database
            DB::table('stasiun_jabodetabek')->where('id', $id)->delete();

            return response()->json(['success' => true, 'message' => 'Data deleted successfully']);
        } catch (\Exception $e) {
            Log::error('Database delete error: ' . $e->getMessage());
            return response()->json(['error' => 'Database error'], 500);
        }
    }
}
