<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use App\Models\PolylineModels;
use App\Models\PolygonModels; // Tambahkan model PolygonModels
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->points = new PointsModel;
        $this->polyline = new PolylineModels;
        $this->polygon = new PolygonModels; // Tambahkan properti untuk PolygonModels
    }

    public function points()
    {
        $points = $this->points->geojson_points();

        return response()->json($points);
    }

    public function polyline()
    {
        $polyline = $this->polyline->geojson_polyline();

        return response()->json($polyline);
    }


    public function polygon() // Tambahkan metode untuk polygon
    {
        $polygon = $this->polygon->geojson_polygon(); // Panggil metode geojson_polygons di PolygonModels

        return response()->json($polygon);
    }
}
