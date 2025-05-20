<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use App\Models\PolygonModels;
use App\Models\PolylinesModel;
use App\Models\PolygonsModel;
use App\Models\PolylineModels;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function __construct()
    {
        $this->points = new PointsModel;
        $this->polyline = new PolylineModels();
        $this->polygon = new PolygonModels();
    }

    public function points()
    {
        $points = $this->points->geojson_points();

        return response()->json($points);
    }

    public function point($id)
    {
        $points = $this->points->geojson_point($id);

        return response()->json($points);
    }

    public function polyline()
    {
        $polyline = $this->polyline->geojson_polyline();

        return response()->json($polyline, 200, [], JSON_NUMERIC_CHECK);
    }

    public function polylines($id)
    {
        $polyline = $this->polyline->geojson_polylines($id);

        return response()->json($polyline);
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
