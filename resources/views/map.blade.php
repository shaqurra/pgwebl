@extends('layout.template')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet-search/2.9.8/leaflet-search.min.css" />
    <style>
        #map {
            width: 100%;
            height: calc(100vh - 56px);
        }

        /* Warna dasar semua cluster jadi ungu (default) */
        .marker-cluster-small {
            background-color: #b388eb !important;
            /* ungu pastel */
        }

        .marker-cluster-small div {
            background-color: #b388eb !important;
        }

        /* Cluster sedang jadi pink */
        .marker-cluster-medium {
            background-color: #f7a1c4 !important;
            /* pink lembut */
        }

        .marker-cluster-medium div {
            background-color: #f7a1c4 !important;
        }

        /* Cluster besar juga pink lebih tua */
        .marker-cluster-large {
            background-color: #ec6fbc !important;
            /* pink cerah */
        }

        .marker-cluster-large div {
            background-color: #ec6fbc !important;
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>

    <!-- Modal Create Point-->
    <div class="modal fade" id="EditPointModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Point</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editPointForm" method="POST">
                    <div class="modal-body">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama Stasiun</label>
                            <input type="text" class="form-control" id="name" name="name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Geometry</label>
                            <textarea class="form-control" id="geom_point" name="geom_point" readonly></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Created At</label>
                            <input type="text" class="form-control" id="created_at" name="created_at" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Updated At</label>
                            <input type="text" class="form-control" id="updated_at" name="updated_at" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Photo</label>
                            <input type="file" class="form-control" id="image_point" name="photo"
                                onchange="document.getElementById('preview-image-point').src = window.URL.createObjectURL(this.files[0])">
                            <img src="" alt="" id="preview-image-point" class="img-thumbnail"
                                width="400">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update</button>
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
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/terraformer@1.0.7/terraformer.js"></script>
    <script src="https://unpkg.com/terraformer-wkt-parser@1.1.2/terraformer-wkt-parser.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet-search/2.9.8/leaflet-search.src.js"></script>

    <script>
        // Inisialisasi peta
        var map = L.map('map').setView([-6.2, 106.8], 9);

        // Basemap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // FeatureGroup untuk hasil digitasi
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
                $('#geom_polyline').val(objectGeometry);
                $('#createpolylineModal').modal('show');
            } else if (type === 'polygon' || type === 'rectangle') {
                console.log("Create " + type);
                $('#geom_polygon').val(objectGeometry);
                $('#createpolygonModal').modal('show');
            } else if (type === 'marker') {
                console.log("Create " + type);
                $('#geom_point').val(objectGeometry);
                $('#EditPointModal').modal('show');
            } else {
                console.log('undefined');
            }
            drawnItems.addLayer(layer);
        });

        // Custom icon untuk marker
        var customIcon = L.icon({
            iconUrl: "{{ asset('icon-removebg-preview.png') }}",
            iconSize: [32, 32],
            iconAnchor: [16, 32],
            popupAnchor: [0, -32]
        });

        // Marker Cluster Group
        var markers = L.markerClusterGroup();

        // GeoJSON Point Layer
        var stasiun = L.geoJson(null, {
            pointToLayer: function(feature, latlng) {
                return L.marker(latlng, {
                    icon: customIcon
                });
            },
            onEachFeature: function(feature, layer) {
                var props = feature.properties;
                var popupContent = `
                    <div style="font-size: 14px;">
                        <strong>Nama Stasiun:</strong> ${props.namobj || '-'}<br>
                        <strong>Kode:</strong> ${props.kodkod || '-'}<br>
                        <strong>Kab/Kota:</strong> ${props.kabkot || '-'}<br>
                        <strong>Provinsi:</strong> ${props.provinsi || '-'}<br>
                        <strong>User ID:</strong> ${props.user_id || '-'}<br>
                        <strong>Created:</strong> ${props.created_at || '-'}<br>
                        <strong>Updated:</strong> ${props.updated_at || '-'}<br>
                        ${props.gambar ? `<img src="/storage/images/${props.gambar}" alt="Foto" style="max-width: 100%; margin-top: 8px;">` : ''}
                        <div class="mt-2">
                            <button class="btn btn-primary btn-sm" onclick="editStasiun('${props.id}')">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="hapusStasiun('${props.id}')">Hapus</button>
                        </div>
                    </div>
                `;
                layer.bindPopup(popupContent);
            }
        });

        // Polygon style functions
        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }

        const pastelColors = [
            "#70d6ff", // biru muda terang
            "#b9fbc0", // hijau segar
            "#fcd5ce", // peach soft
            "#a0c4ff", // biru langit pastel
            "#caffbf", // hijau lemon
            "#fdffb6", // kuning pucat terang
            "#ffc6ff", // pink ungu muda
            "#ffd6a5", // orange muda pastel
            "#9bf6ff" // cyan muda
        ];

        // Fungsi ambil warna pastel random
        function getPastelColor() {
            return pastelColors[Math.floor(Math.random() * pastelColors.length)];
        }

        function style(feature) {
            return {
                fillColor: getPastelColor(), // warna isi pastel random
                weight: 2,
                opacity: 1,
                color: '#bdbdbd', // garis tepi abu pastel
                dashArray: '3',
                fillOpacity: 0.5 // transparansi isi
            };
        }

        // Polygon Layer Jabodetabek - DECLARE BEFORE USING
        var polygon = L.geoJson(null, {
            style: style,
            onEachFeature: function(feature, layer) {
                var popupContent = feature.properties.NAMOBJ || 'Batas Jabodetabek';
                layer.on({
                    click: function(e) {
                        layer.bindPopup(popupContent).openPopup(e.latlng);
                    }
                });
            }
        });

        // Layer WMS Provinsi Jakarta dari GeoServer
        var wmsJakarta = L.tileLayer.wms("http://localhost:8080/geoserver/pgwebl/wms", {
            layers: 'pgwebl:Provinsi_Jakarta1',
            format: 'image/png',
            transparent: true,
            version: '1.1.0',
            attribution: "GeoServer"
        });

        // Add WMS Jakarta to map
        wmsJakarta.addTo(map);

        // Deklarasi variable untuk jalur kereta
        var jalurKeretaLayer;

        // Load data GeoJSON stasiun - FIXED VERSION
        $.getJSON("{{ route('api.stasiun') }}", function(data) {
            console.log("Raw API response:", data);

            // Check if data is an array (your current API format)
            if (Array.isArray(data)) {
                console.log("JUMLAH FITUR:", data.length);

                // Convert array to proper GeoJSON FeatureCollection
                var geoJsonData = {
                    "type": "FeatureCollection",
                    "features": data
                };

                stasiun.addData(geoJsonData);
                markers.addLayer(stasiun);
                map.addLayer(markers);

                console.log("Stasiun data loaded successfully!");
            }
            // Check if data is already a proper GeoJSON FeatureCollection
            else if (data && data.features && Array.isArray(data.features)) {
                console.log("JUMLAH FITUR:", data.features.length);
                stasiun.addData(data);
                markers.addLayer(stasiun);
                map.addLayer(markers);

                console.log("Stasiun data loaded successfully!");
            }
            // Handle other possible formats
            else {
                console.error("Format data tidak dikenali:", data);
                console.log("Expected: Array of features OR GeoJSON FeatureCollection");
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Failed to load stasiun data:", textStatus, errorThrown);
        });

        // Load data GeoJSON jalur kereta
        $.getJSON("/api/jalur-kereta", function(data) {
            console.log("Jalur kereta data:", data);

            // Handle jalur kereta data - same fix as stasiun
            var jalurData;
            if (Array.isArray(data)) {
                jalurData = {
                    "type": "FeatureCollection",
                    "features": data
                };
            } else if (data && data.features) {
                jalurData = data;
            } else {
                console.error("Invalid jalur kereta data format:", data);
                return;
            }

            jalurKeretaLayer = L.geoJSON(jalurData, {
                style: function() {
                    return {
                        color: 'blue',
                        weight: 2
                    };
                },
                onEachFeature: function(feature, layer) {
                    var props = feature.properties;
                    var popupContent = `
                        <div>
                            <strong>ID:</strong> ${props.id || '-'}<br>
                            <strong>Nama:</strong> ${props.name || '-'}<br>
                            <strong>Panjang:</strong> ${props.shape_leng || '-'}
                        </div>
                    `;
                    layer.bindPopup(popupContent);
                }
            }).addTo(map);

            // Setup overlay maps setelah semua layer siap
            setupOverlayMaps();

        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Failed to load jalur kereta data:", textStatus, errorThrown);
            // Setup overlay maps even if jalur kereta fails
            setupOverlayMaps();
        });

        // Load data GeoJSON polygon Jabodetabek
        $.getJSON("{{ asset('Jabodetabek_New.json') }}", function(data) {
            console.log("Jabodetabek polygon data:", data);

            if (data) {
                polygon = L.geoJSON(data, {
                    style: style, // kamu sudah punya fungsi style
                    onEachFeature: function(feature, layer) {
                        const props = feature.properties;
                        const popupContent = `
                    <strong>Nama Wilayah:</strong> ${props.KABKOT || 'Tidak diketahui'}<br>
                    <strong>Luas (Shape_Area):</strong> ${Number(props.Shape_Area).toFixed(6)} km²<br>
                    <strong>Keliling (Shape_Leng):</strong> ${Number(props.Shape_Leng).toFixed(6)} km
                `;
                        layer.bindPopup(popupContent);
                    }
                });

                map.addLayer(polygon);
                console.log("Jabodetabek polygon loaded with popup!");
            }
        });

        // Function to setup overlay maps
        function setupOverlayMaps() {
            var overlayMaps = {
                "Titik Stasiun Kereta Api": markers,
                "Batas Administrasi Metropolitan Jabodetabek": polygon,
                "Batas Provinsi DKI Jakarta (GeoServer)": wmsJakarta
            };

            // Add jalur kereta only if it exists
            if (jalurKeretaLayer) {
                overlayMaps["Jalur Kereta"] = jalurKeretaLayer;
            }

            // Add layer control
            L.control.layers(null, overlayMaps).addTo(map);
            console.log("Layer control added with overlays:", Object.keys(overlayMaps));
        }

        // Function to find and edit stasiun
        function findStasiunById(id) {
            var found = null;
            stasiun.eachLayer(function(layer) {
                if (layer.feature && layer.feature.properties.id == id) {
                    found = layer.feature.properties;
                }
            });
            return found;
        }

        function editStasiun(id) {
            var data = findStasiunById(id);
            if (!data) {
                console.error("Stasiun with ID " + id + " not found");
                return;
            }

            $('#name').val(data.namobj || '');
            $('#description').val(data.kabkot || '');
            $('#geom_point').val(
                (data.lon && data.lat) ? `POINT(${data.lon} ${data.lat})` : (data.geom || ''));
            $('#user_id').val(data.user_id || '');
            $('#created_at').val(data.created_at || '');
            $('#updated_at').val(data.updated_at || '');

            if (data.gambar) {
                $('#preview-image-point').attr('src', "/storage/images/" + data.gambar);
                $('#preview-image-point').attr('alt', data.gambar);
            } else {
                $('#preview-image-point').attr('src', '');
                $('#preview-image-point').attr('alt', '');
            }

            // Set form action for update
            $('#editPointForm').attr('action', '/api/stasiun/' + id);
            $('#EditPointModal').modal('show');
        }

        // Function to delete stasiun
        function hapusStasiun(id) {
            if (confirm('Yakin ingin menghapus data stasiun ini?')) {
                $.ajax({
                    url: '/api/stasiun/' + id,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        alert('Data berhasil dihapus!');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error("Delete error:", error);
                        alert('Gagal menghapus data!');
                    }
                });
            }
        }
        // Add search control to the map
        var searchControl = new L.Control.Search({
            layer: markers,
            propertyName: 'namobj', // Changed from 'name' to 'namobj' based on your data structure
            marker: false,
            moveToLocation: function(latlng, title, map) {
                map.setView(latlng, 15);
                L.popup()
                    .setLatLng(latlng)
                    .setContent(title)
                    .openOn(map);
            }
        });
        map.addControl(searchControl);

        // Handle form submissions
        $(document).ready(function() {
            // Handle edit point form submission
            $('#editPointForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData();
                formData.append('name', $('#editPointForm #name').val());
                formData.append('description', $('#editPointForm #description').val());
                formData.append('geom_point', $('#editPointForm #geom_point').val());
                if ($('#image_point')[0].files[0]) {
                    formData.append('photo', $('#image_point')[0].files[0]);
                }
                // Remove this line: formData.append('_method', 'PATCH');
                // The @method('PUT') directive already handles this

                var actionUrl = $(this).attr('action');
                for (var pair of formData.entries()) {
                    console.log(pair[0] + ': ' + pair[1]);
                }
                $.ajax({
                    url: actionUrl,
                    type: 'PUT', // Laravel will convert this to PUT due to @method('PUT')
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        alert('Data berhasil diupdate!');
                        $('#EditPointModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error("Update error:", xhr.responseJSON);
                        alert('Gagal mengupdate data: ' + (xhr.responseJSON?.message ||
                            error));
                    }
                });
            });
        });
    </script>
@endsection
