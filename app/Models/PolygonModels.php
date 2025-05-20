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
        // Ambil data dari database
        $polygon = DB::table($this->table)
            ->select(DB::raw('id, ST_AsGeoJson(geom) AS geom,
                ST_Area(geom, true) AS area_m2,
                ST_Area(geom, true)/1000000 AS area_km2,
                ST_Area(geom, true)/10000 AS area_hectare,
                name, description, image, created_at, updated_at'))
            ->get();

        // Bangun struktur GeoJSON
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polygon as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image,
                    'area_m2' => $p->area_m2,
                    'area_km2' => $p->area_km2,
                    'area_hectare' => $p->area_hectare,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                ],
            ];

            $geojson['features'][] = $feature;
        }

        return $geojson;
    }

    /**
     * Mengambil data polygon berdasarkan ID dalam format GeoJSON.
     *
     * @param int $id
     * @return array
     */
    public function geojson_polygons($id)
    {
        // Ambil data berdasarkan ID
        $polygon = DB::table($this->table)
            ->select(DB::raw('id, ST_AsGeoJson(geom) AS geom,
                ST_Area(geom, true) AS area_m2,
                ST_Area(geom, true)/1000000 AS area_km2,
                ST_Area(geom, true)/10000 AS area_hectare,
                name, description, image, created_at, updated_at'))
            ->where('id', $id)
            ->first();

        if (!$polygon) {
            return [
                'type' => 'FeatureCollection',
                'features' => [],
            ];
        }

        // Bangun struktur GeoJSON untuk satu fitur
        $feature = [
            'type' => 'Feature',
            'geometry' => json_decode($polygon->geom),
            'properties' => [
                'id' => $polygon->id,
                'name' => $polygon->name,
                'description' => $polygon->description,
                'image' => $polygon->image,
                'area_m2' => $polygon->area_m2,
                'area_km2' => $polygon->area_km2,
                'area_hectare' => $polygon->area_hectare,
                'created_at' => $polygon->created_at,
                'updated_at' => $polygon->updated_at,
            ],
        ];

        return [
            'type' => 'FeatureCollection',
            'features' => [$feature],
        ];
    }
}
