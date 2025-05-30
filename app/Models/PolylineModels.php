<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolylineModels extends Model
{
    protected $table = 'polyline'; // Nama tabel
    protected $guarded = ['id']; // Kolom yang tidak boleh diisi secara massal

    public function geojson_polyline()
    {
        // Ambil data dari database
        $polyline = $this -> select(DB::raw('polyline.id,
            ST_AsGeoJSON(polyline.geom) as geom,
            polyline.name,
            polyline.description,
            polyline.image,
            polyline.created_at,
            polyline.updated_at,
            st_length(polyline.geom) as length_m,
            st_length(polyline.geom) / 1000 as length_km,
            polyline.user_id,
            users.name as user_created'))
            ->leftJoin('users', 'polyline.user_id', '=', 'users.id')
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polyline as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image, // Properti 'image' sekarang tersedia
                    'length_m' => $p->length_m,
                    'length_km' => $p->length_km,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'user_created' => $p->user_created,
                    'users_id' => $p->user_id,
                ],
            ];

            $geojson['features'][] = $feature;
        }

        return $geojson;
    }

    public function geojson_polylines($id)
    {
        // Ambil data dari database
        $polyline = $this -> select(DB::raw('polyline.id,
            ST_AsGeoJSON(polyline.geom) as geom,
            polyline.name,
            polyline.description,
            polyline.image,
            polyline.created_at,
            polyline.updated_at,
            polyline.user_id,
            users.name as user_created'))
            ->leftjoin('users', 'polyline.user_id', '=', 'users.id')
            ->get();

        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($polyline as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'image' => $p->image, // Properti 'image' sekarang tersedia
                    'length_m' => $p->length_m,
                    'length_km' => $p->length_km,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                ],
            ];

            $geojson['features'][] = $feature;
        }

        return $geojson;
    }
}
