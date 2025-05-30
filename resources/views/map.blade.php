@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">

    <style>
        #map {
            width: 100%;
            height: calc(100vh - 56px);
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>

    <!-- Modal Create Point-->
    <div class="modal fade" id="createpointModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Point</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('points.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="fill point name">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_point" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_point" name="geom_point" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_point" name="image"
                                onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-point" class="img-thumbnail"
                                width="400">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Create Polyline-->
    <div class="modal fade" id="createpolylineModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Polyline</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('polyline.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="fill point name">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_polyline" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_polyline" name="geom_polyline" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_polyline" name="image"
                                onchange="document.getElementById('preview-image-polyline').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-polyline" class="img-thumbnail"
                                width="400">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Create Polygon-->
    <div class="modal fade" id="createpolygonModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Polygon</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('polygon.store') }}" enctype="multipart/form-data">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="fill point name">
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_polygon" class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_polygon" name="geom_polygon" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_polygon" name="image"
                                onchange="document.getElementById('preview-image-polygon').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-polygon" class="img-thumbnail"
                                width="400">
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://unpkg.com/@terraformer/wkt"></script>

    <script>
        var map = L.map('map').setView([-7.6587330, 110.3772891], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        /* Digitize Function */
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: {
                position: 'topleft',
                polyline: true,
                polygon: true,
                rectangle: true,
                circle: true,
                marker: true,
                circlemarker: false
            },
            edit: false
        });

        map.addControl(drawControl);

        map.on('draw:created', function(e) {
            var type = e.layerType,
                layer = e.layer;

            console.log(type);

            var drawnJSONObject = layer.toGeoJSON();
            var objectGeometry = Terraformer.geojsonToWKT(drawnJSONObject.geometry);

            console.log(drawnJSONObject);

            if (type === 'polyline') {
                console.log("Create " + type);

                $('#geom_polyline').val(objectGeometry)

                $('#createpolylineModal').modal('show');

            } else if (type === 'polygon' || type === 'rectangle') {
                console.log("Create " + type);

                $('#geom_polygon').val(objectGeometry)

                $('#createpolygonModal').modal('show');

            } else if (type === 'marker') {
                console.log("Create " + type);

                $('#geom_point').val(objectGeometry)

                $('#createpointModal').modal('show');

            } else {
                console.log('undefined');
            }

            drawnItems.addLayer(layer);
        });

        // Point Layer
        var point = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var routedelete = "{{ route('points.destroy', ':id') }}";
                routedelete = routedelete.replace(':id', feature.properties.id);

                var routeedit = "{{ route('points.edit', ':id') }}";
                routeedit = routeedit.replace(':id', feature.properties.id);

                var popupContent = `
                    <table>
                        <tr><th>Nama</th><td>${feature.properties.name}</td></tr>
                        <tr><th>Deskripsi</th><td>${feature.properties.description}</td></tr>
                        <tr><th>Dibuat</th><td>${feature.properties.created_at}</td></tr>
                        <tr>
                            <th>Foto</th>
                            <td><img src="{{ asset('storage/images') }}/${feature.properties.image}" alt="" width="200px" height="200px"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row mt-4">
        <div class="col-6 text-end">
            <a href="${routeedit}" class="btn btn-warning btn-sm">
                <i class="fa-solid fa-pen-to-square"></i> Edit
            </a>
        </div>
        <div class="col-6">
            <form method="POST" action="${routedelete}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <input type="hidden" name="_method" value="DELETE">
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin akan dihapus?')">
                    <i class="fa-solid fa-trash-can"></i> Hapus
                </button>
            </form>
        </div>
        <div class="col-12 mt-2">
            <p>Dibuat Oleh: ${feature.properties.user_created}</p>
        </div>
    </div>
</td>

                        </tr>
                    </table>
                `;

                layer.on({
                    click: function(e) {
                        layer.bindPopup(popupContent).openPopup();
                    },
                    mouseover: function(e) {
                        layer.bindTooltip(feature.properties.name);
                    },
                });
            },
        });

        $.getJSON("{{ route('api.points') }}", function(data) {
            point.addData(data);
            map.addLayer(point);
        });

        // Polyline Layer
        var polyline = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var routedelete = "{{ route('polyline.destroy', ':id') }}";
                routedelete = routedelete.replace(':id', feature.properties.id);

                var routeedit = "{{ route('polyline.edit', ':id') }}";
                routeedit = routeedit.replace(':id', feature.properties.id);

                var popupContent = `
                    <table>
                        <tr><th>Nama</th><td>${feature.properties.name}</td></tr>
                        <tr><th>Deskripsi</th><td>${feature.properties.description}</td></tr>
                        <tr><th>Panjang</th><td>${feature.properties.length_km} km</td></tr>
                        <tr><th>Dibuat</th><td>${feature.properties.created_at}</td></tr>
                        <tr>
                            <th>Foto</th>
                            <td><img src="{{ asset('storage/images') }}/${feature.properties.image}" alt="" width="200px" height="200px"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row mt-4">
                                    <div class="col-6 text-end">
                                        <a href="${routeedit}" class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <form method="POST" action="${routedelete}">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin akan dihapus?')">
                                                <i class="fa-solid fa-trash-can"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <p>Dibuat Oleh: ${feature.properties.user_created}</p>  
                                </div>
                            </td>
                        </tr>
                    </table>
                `;

                layer.on({
                    click: function(e) {
                        layer.bindPopup(popupContent).openPopup();
                    },
                    mouseover: function(e) {
                        layer.bindTooltip(feature.properties.name);
                    },
                });
            },
        });

        $.getJSON("{{ route('api.polyline') }}", function(data) {
            polyline.addData(data);
            map.addLayer(polyline);
        });

        // Polygon Layer
        var polygon = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                var routedelete = "{{ route('polygon.destroy', ':id') }}";
                routedelete = routedelete.replace(':id', feature.properties.id);

                var routeedit = "{{ route('polygon.edit', ':id') }}";
                routeedit = routeedit.replace(':id', feature.properties.id);

                var popupContent = `
                    <table>
                        <tr><th>Nama</th><td>${feature.properties.name}</td></tr>
                        <tr><th>Deskripsi</th><td>${feature.properties.description}</td></tr>
                        <tr><th>Luas</th><td>${feature.properties.area_hectare} ha</td></tr>
                        <tr><th>Dibuat</th><td>${feature.properties.created_at}</td></tr>
                        <tr>
                            <th>Foto</th>
                            <td><img src="{{ asset('storage/images') }}/${feature.properties.image}" alt="" width="200px" height="200px"></td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <div class="row mt-4">
                                    <div class="col-6 text-end">
                                        <a href="${routeedit}" class="btn btn-warning btn-sm">
                                            <i class="fa-solid fa-pen-to-square"></i> Edit
                                        </a>
                                    </div>
                                    <div class="col-6">
                                        <form method="POST" action="${routedelete}">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin akan dihapus?')">
                                                <i class="fa-solid fa-trash-can"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                    <div class="col-12 mt-2">
                                        <p>Dibuat Oleh: ${feature.properties.user_created}</p>

                                </div>
                            </td>
                        </tr>
                    </table>
                `;

                layer.on({
                    click: function(e) {
                        layer.bindPopup(popupContent).openPopup();
                    },
                    mouseover: function(e) {
                        layer.bindTooltip(feature.properties.name);
                    },
                });
            },
        });

        $.getJSON("{{ route('api.polygon') }}", function(data) {
            polygon.addData(data);
            map.addLayer(polygon);
        });

        // Layer Groups
        var baseLayers = {
            "OpenStreetMap": L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            })
        };

        var overlays = {
            "Points": point,
            "Polylines": polyline,
            "Polygons": polygon
        };

        // Tambahkan Layer Control ke Peta di pojok kanan bawah
        L.control.layers(baseLayers, overlays, {
            collapsed: false,
            position: 'bottomright' // Pindahkan ke pojok kanan bawah
        }).addTo(map);
    </script>
    </script>
@endsection
