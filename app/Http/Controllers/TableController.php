<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\PointsModel;
use App\Models\PolygonModels;
use App\Models\PolylineModels;
use App\Models\JalurKereta;
use App\Models\ProvinsiJakarta;
use Illuminate\Http\Request;

class TableController extends Controller
{
    public function __construct()
    {
        $this->points = new PointsModel();
        $this->polyline = new JalurKereta();
        $this->polygon = new ProvinsiJakarta();
    }
    public function index()
    {
        $data = [
            'title' => 'Table',
            'stasiun' => DB::table('stasiun_jabodetabek')->get(),
            'polyline' => JalurKereta::all(),
            'polygon' => ProvinsiJakarta::all(),
        ];

        // Perbaiki baris ini
        return view('table', $data);
    }
}
