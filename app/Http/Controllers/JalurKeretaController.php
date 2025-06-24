<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\JalurKereta;

class JalurKeretaController extends Controller
{
    public function index()
    {
        $jalur = DB::table('jalur_kereta_jabodetabek')
            ->select('id', 'shape_leng', DB::raw('ST_AsGeoJSON(geom) as geom'))
            ->get();

        $features = $jalur->map(function($item) {
            return [
                "type" => "Feature",
                "geometry" => json_decode($item->geom),
                "properties" => [
                    "id" => $item->id,
                    "shape_leng" => $item->shape_leng,
                ]
            ];
        });

        return response()->json([
            "type" => "FeatureCollection",
            "features" => $features,
        ]);
    }
}
