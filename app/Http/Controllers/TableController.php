<?php

namespace App\Http\Controllers;

use App\Models\PointsModel;
use App\Models\PolygonModels;
use App\Models\PolylineModels;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function __construct()
    {
        $this->points = new PointsModel();
        $this->polyline = new PolylineModels();
        $this->polygon = new PolygonModels();

    }
    public function index()
    {
        $data = [
            'title' => 'Table',
            'points' => $this->points->all(),
        ];

        // Perbaiki baris ini
        return view('table', $data);
    }
}
