<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolygonModels extends Model
{
    protected $table = 'polygon';

    protected $guarded = ['id'];

    public function geojson_polygon()
    {

        $polygon = $this
            ->select(DB::raw('ST_AsGeoJson(geom) AS geom,
                ST_Area(geom, true) AS area_m2,
                    ST_Area(geom, true)/1000000 AS area_km2,
                    ST_Area(geom, true)/10000 AS area_hectare,
                    name, description, image, created_at, updated_at')) // Tambahkan kolom `image`
            ->get();

        $geojson = [
            'type'      => 'FeatureCollection',
            'features'  => []
        ];

        foreach ($polygon as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image,
                    'area_m2' => $p->area_m2,
                    'area_km2' => $p->area_km2,
                    'area_hectare' => $p->area_hectare,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at
                ]
            ];

            array_push($geojson['features'], $feature);
        }

        return $geojson;
    }
}
