<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PointsModel extends Model
{
    protected $table = 'points';
    protected $guarded = ['id'];

    public function geojson_points()
    {
        // Ambil data dari database
        $points = $this -> select(DB::raw('points.id,
            ST_AsGeoJSON(points.geom) as geom,
            points.name,
            points.description,
            points.image,
            points.created_at,
            points.updated_at,
            points.user_id,
            users.name as user_created'))
            ->leftjoin('users', 'points.user_id', '=', 'users.id')
            ->get();

        // Bangun struktur GeoJSON
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($points as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'image' => $p->image,
                    'user_created' => $p->user_created,
                    'users_id' => $p->user_id,
                ],
            ];

            array_push($geojson['features'], $feature);
        }

        // Kembalikan GeoJSON
        return $geojson;
    }

    public function geojson_point($id)
    {
        // Ambil data dari database
        $points = $this
            ->select(DB::raw('id, st_asgeojson(geom) as geom, name, description, image, created_at, updated_at'))
            ->where('id', $id)
            ->get();

        // Bangun struktur GeoJSON
        $geojson = [
            'type' => 'FeatureCollection',
            'features' => [],
        ];

        foreach ($points as $p) {
            $feature = [
                'type' => 'Feature',
                'geometry' => json_decode($p->geom),
                'properties' => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'description' => $p->description,
                    'created_at' => $p->created_at,
                    'updated_at' => $p->updated_at,
                    'image' => $p->image,
                ],
            ];

            ($geojson['features'][] = $feature);
        }

        // Kembalikan GeoJSON
        return $geojson;
    }
}
