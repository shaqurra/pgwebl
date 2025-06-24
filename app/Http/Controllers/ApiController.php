<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use App\Models\PolygonModels;
use App\Models\PolylinesModel;
use App\Models\PolygonsModel;
use App\Models\PolylineModels;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->points = new PointsModel;
        $this->polyline = new PolylineModels();
        $this->polygon = new PolygonModels();
    }

    public function stasiun()
    {
        $data = DB::table('stasiun_jabodetabek')->get();

        // Format GeoJSON FeatureCollection
        $features = [];
        foreach ($data as $row) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => [
                    'type' => 'Point',
                    'coordinates' => [(float)$row->lon, (float)$row->lat],
                ],
                'properties' => [
                    'id' => $row->id,
                    'namobj' => $row->namobj,
                    'kodkod' => $row->kodkod,
                    'kabkot' => $row->kabkot,
                    'provinsi' => $row->provinsi,
                    'kelas' => $row->kelas,
                    'status_ope' => $row->status_ope,
                ],
            ];
        }
        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }

    public function stasiunDetail($id)
    {
        $row = DB::table('stasiun_jabodetabek')->where('id', $id)->first();
        if (!$row) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $feature = [
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [(float)$row->lon, (float)$row->lat],
            ],
            'properties' => [
                'id' => $row->id,
                'namobj' => $row->namobj,
                'kodkod' => $row->kodkod,
                'kabkot' => $row->kabkot,
                'provinsi' => $row->provinsi,
                'kelas' => $row->kelas,
                'status_ope' => $row->status_ope,
            ],
        ];
        return response()->json($feature);
    }

    public function jalurKereta()
    {
        $jalur = DB::select("SELECT id, nama, shape_leng, ST_AsGeoJSON(geom) as geom FROM jalur_kereta_jabodetabek");
        $features = [];
        foreach ($jalur as $row) {
            $features[] = [
                'type' => 'Feature',
                'geometry' => json_decode($row->geom),
                'properties' => [
                    'id' => $row->id,
                    'nama' => $row->nama,
                    'shape_leng' => $row->shape_leng,
                ],
            ];
        }
        return response()->json([
            'type' => 'FeatureCollection',
            'features' => $features,
        ]);
    }


    public function polygon()
    {
        $polygon = $this->polygon->geojson_polygon();

        return response()->json($polygon, 200, [], JSON_NUMERIC_CHECK);
    }

    public function polygons($id)
    {
        $polygon = $this->polygon->geojson_polygons($id);

        return response()->json($polygon);
    }
}
