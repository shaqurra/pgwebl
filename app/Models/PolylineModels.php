<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class PolylineModels extends Model
{
    protected $table = 'polyline'; // Nama tabel
    protected $guarded = ['id']; // Kolom yang tidak boleh diisi secara massal

    public static function geojson_polyline()
    {
        $polyline = DB::table('polyline')
            ->select(DB::raw('ST_AsGeoJSON(geom) as geom, name, description, image,
            ST_Length(geom, true) as length_m, ST_Length(geom, true)/1000 as length_km, created_at, updated_at')) // Tambahkan kolom 'image'
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
