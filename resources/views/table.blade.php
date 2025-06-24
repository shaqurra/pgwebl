@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.1/css/dataTables.dataTables.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #e0e7ff 0%, #f0fdfa 100%);
            min-height: 100vh;
        }

        .container {
            margin-top: 40px;
            margin-bottom: 40px;
        }

        .card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 2px 12px rgba(0, 0, 0, 0.07);
            margin-bottom: 2.5rem;
            padding: 2rem 2rem 1rem 2rem;
        }

        .card-header {
            background: none;
            border-bottom: none;
            padding-bottom: 0;
            margin-bottom: 1rem;
        }

        .card-title {
            font-size: 1.5rem;
            font-weight: bold;
            color: #6366f1;
            margin-bottom: 0.5rem;
        }

        .table {
            background: #f8fafc;
            border-radius: 0.5rem;
            overflow: hidden;
            margin-bottom: 0;
        }

        .table th {
            background: #6366f1;
            color: #fff;
            font-weight: 600;
            border: none;
        }

        .table td {
            vertical-align: middle;
            border: none;
        }

        .table-striped>tbody>tr:nth-of-type(odd) {
            background: #eef2ff;
        }

        .table-striped>tbody>tr:nth-of-type(even) {
            background: #f8fafc;
        }

        img.table-img {
            border-radius: 0.5rem;
            box-shadow: 0 2px 8px rgba(99, 102, 241, 0.08);
            border: 2px solid #e0e7ff;
            background: #fff;
        }

        .image-error {
            background: #fee2e2;
            color: #dc2626;
            padding: 8px 12px;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            border: 1px solid #fecaca;
        }

        @media (max-width: 768px) {
            .card {
                padding: 1rem 0.5rem;
            }

            .card-title {
                font-size: 1.1rem;
            }

            .table th,
            .table td {
                font-size: 0.95rem;
            }

            img.table-img {
                width: 90px !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Stasiun Jabodetabek</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="stasiunTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Stasiun</th>
                            <th>Kode</th>
                            <th>Kab/Kota</th>
                            <th>Provinsi</th>
                            <th>Kelas</th>
                            <th>Status</th>
                            <th>Lon</th>
                            <th>Lat</th>
                            <th>User ID</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                            <th>Foto</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stasiun as $key => $s)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $s->namobj }}</td>
                                <td>{{ $s->kodkod }}</td>
                                <td>{{ $s->kabkot }}</td>
                                <td>{{ $s->provinsi }}</td>
                                <td>{{ $s->kelas }}</td>
                                <td>{{ $s->status_ope }}</td>
                                <td>{{ $s->lon }}</td>
                                <td>{{ $s->lat }}</td>
                                <td>{{ $s->user_id }}</td>
                                <td>{{ $s->created_at }}</td>
                                <td>{{ $s->updated_at }}</td>
                                <td>
                                    @if ($s->gambar)
                                        @if (Storage::disk('public')->exists('images/' . $s->gambar))
                                            <img src="{{ Storage::url('images/' . $s->gambar) }}"
                                                alt="Foto Stasiun {{ $s->namobj }}" width="60" class="table-img"
                                                title="{{ $s->gambar }}"
                                                onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
                                            <div class="image-error" style="display: none;">
                                                Image not found
                                            </div>
                                        @else
                                            <div class="image-error">
                                                File not found
                                            </div>
                                        @endif
                                    @else
                                        <span class="text-muted">No Image</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Jalur Kereta Data</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="polylinesTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Panjang (km)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($polyline as $key => $jalur)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ number_format($jalur->shape_leng * 111, 3) }} km</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Polygons Data</h4>
            </div>
            <div class="card-body">
                <table class="table table-striped" id="PolygonsTable">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Geometry</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($polygon as $key => $poly)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $poly->name }}</td>
                                <td>{{ Str::limit($poly->geom, 60) }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.1/js/dataTables.min.js"></script>
    <script>
        let tablestasiun = new DataTable('#stasiunTable');
        let tablepolylines = new DataTable('#polylinesTable');
        let tablepolygons = new DataTable('#PolygonsTable');
    </script>
@endsection
