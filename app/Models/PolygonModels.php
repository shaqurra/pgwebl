<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolygonModels extends Model
{
    protected $table = 'polygon';

    protected $guarded = ['id'];

    public function geojson_polygon(){

        $polygon = $this
        ->select(DB::raw('ST_AsGeoJson(geom) AS geom, ST_Area(geom, true) AS area_m2, ST_Area(geom, true)/1000000 AS area_km2, ST_Area(geom, true)/10000 AS area_hectare,       name, description, created_at, updated_at '))
        ->get();

        $geojson = [
            'type'      => 'FeatureCollection',
            'features'  => []
        ];

        foreach ($polygon as $polygon) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($polygon->geom),
                'properties' => [
                    'name' => $polygon->name,
                    'description' => $polygon->description,
                    'area_m2' => $polygon->area_m2,
                    'area_km2' => $polygon->area_km2,
                    'area_hectare' => $polygon->area_hectare,
                    'created_at' => $polygon->created_at,
                    'updated_at' => $polygon->updated_at
                ]
            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;

    }
}
