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

        .legend-box {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            padding: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .legend-item {
            display: flex;
            align-items: center;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .legend-symbol {
            width: 20px;
            height: 20px;
            margin-right: 10px;
            border-radius: 3px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .stats-panel {
            position: fixed;
            top: 80px;
            right: 20px;
            width: 320px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(15px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            z-index: 1000;
            max-height: 70vh;
            overflow-y: auto;
        }

        .chart-container {
            position: relative;
            height: 250px;
            margin: 15px 0;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 10px;
            text-align: center;
        }

        .stat-number {
            font-size: 28px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            opacity: 0.9;
        }

        .toggle-panel {
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            width: 35px;
            height: 35px;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .analysis-tabs {
            margin-bottom: 15px;
        }

        .tab-button {
            background: none;
            border: 1px solid #ddd;
            padding: 8px 12px;
            margin-right: 5px;
            border-radius: 20px;
            cursor: pointer;
            font-size: 12px;
            transition: all 0.3s;
        }

        .tab-button.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
        }

        .leaflet-control-legend {
            background: rgba(255, 255, 255, 0.95);
            padding: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);

            .legend-box {
                background: rgba(255, 255, 255, 0.95);
                border-radius: 8px;
                padding: 15px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(255, 255, 255, 0.2);
            }

            .legend-item {
                display: flex;
                align-items: center;
                margin-bottom: 8px;
                font-size: 14px;
            }

            .legend-symbol {
                width: 20px;
                height: 20px;
                margin-right: 10px;
                border-radius: 3px;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .stats-panel {
                position: fixed;
                top: 80px;
                right: 20px;
                width: 320px;
                background: rgba(255, 255, 255, 0.95);
                border-radius: 12px;
                padding: 20px;
                box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
                backdrop-filter: blur(15px);
                border: 1px solid rgba(255, 255, 255, 0.2);
                z-index: 1000;
                max-height: 70vh;
                overflow-y: auto;
            }

            .chart-container {
                position: relative;
                height: 250px;
                margin: 15px 0;
            }

            .stat-card {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 15px;
                border-radius: 8px;
                margin-bottom: 10px;
                text-align: center;
            }

            .stat-number {
                font-size: 28px;
                font-weight: bold;
                margin-bottom: 5px;
            }

            .stat-label {
                font-size: 12px;
                opacity: 0.9;
            }

            .toggle-panel {
                position: absolute;
                top: 10px;
                right: 10px;
                background: rgba(255, 255, 255, 0.9);
                border: none;
                border-radius: 50%;
                width: 35px;
                height: 35px;
                cursor: pointer;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
            }

            .analysis-tabs {
                margin-bottom: 15px;
            }

            .tab-button {
                background: none;
                border: 1px solid #ddd;
                padding: 8px 12px;
                margin-right: 5px;
                border-radius: 20px;
                cursor: pointer;
                font-size: 12px;
                transition: all 0.3s;
            }

            .tab-button.active {
                background: #667eea;
                color: white;
                border-color: #667eea;
            }

            .leaflet-control-legend {
                background: rgba(255, 255, 255, 0.95);
                padding: 10px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
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
    <div class="stats-panel" id="statsPanel">
        <button class="toggle-panel" onclick="togglePanel()">
            <i class="fas fa-chart-bar"></i>
        </button>

        <h5 class="mb-3"><i class="fas fa-analytics"></i> Analisis Data</h5>

        <!-- Analysis Tabs -->
        <div class="analysis-tabs">
            <button class="tab-button active" onclick="showTab('overview')">Overview</button>
            <button class="tab-button" onclick="showTab('distribution')">Distribusi</button>
            <button class="tab-button" onclick="showTab('density')">Kepadatan</button>
        </div>

        <!-- Overview Tab -->
        <div id="overview-tab" class="tab-content">
            <div class="stat-card">
                <div class="stat-number" id="totalStations">0</div>
                <div class="stat-label">Total Stasiun</div>
            </div>

            <div class="stat-card" style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                <div class="stat-number" id="totalProvinces">6</div>
                <div class="stat-label">Provinsi Jabodetabek</div>
            </div>

            <div class="stat-card" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <div class="stat-number" id="totalLength">0</div>
                <div class="stat-label">KM Jalur Kereta</div>
            </div>
        </div>

        <!-- Distribution Tab -->
        <div id="distribution-tab" class="tab-content" style="display: none;">
            <h6>Distribusi Stasiun per Wilayah</h6>
            <div class="chart-container">
                <canvas id="distributionChart"></canvas>
            </div>
        </div>

        <!-- Density Tab -->
        <div id="density-tab" class="tab-content" style="display: none;">
            <h6>Analisis Cluster</h6>
            <div class="chart-container">
                <canvas id="densityChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Modal Create Polyline-->
    <div class="modal fade" id="createpolylineModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
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
                            <input type="text" class="form-control" id="polyline_name" name="name">
                            <textarea class="form-control" id="polyline_description" name="description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="polylines_description" name="description" rows="3"></textarea>
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
                            <input type="text" class="form-control" id="polygon_name" name="name">
                            <textarea class="form-control" id="polygon_description" name="description"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description_polygon" </div>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
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

        // Custom Legend Control
        L.Control.Legend = L.Control.extend({
            onAdd: function(map) {
                var div = L.DomUtil.create('div', 'leaflet-control-legend');
                div.innerHTML = `
        <div class="legend-box">
            <h6 style="margin-bottom: 15px; color: #333;">
                <i class="fas fa-map-marked-alt"></i> Legenda Peta
            </h6>

            <div class="legend-item">
                <div class="legend-symbol" style="background: #b388eb;">
                    <i class="fas fa-circle" style="color: white; font-size: 8px;"></i>
                </div>
                <span>Cluster Stasiun (Kecil)</span>
            </div>

            <div class="legend-item">
                <div class="legend-symbol" style="background: #f7a1c4;">
                    <i class="fas fa-circle" style="color: white; font-size: 10px;"></i>
                </div>
                <span>Cluster Stasiun (Sedang)</span>
            </div>

            <div class="legend-item">
                <div class="legend-symbol" style="background: #ec6fbc;">
                    <i class="fas fa-circle" style="color: white; font-size: 12px;"></i>
                </div>
                <span>Cluster Stasiun (Besar)</span>
            </div>

            <div class="legend-item">
                <div class="legend-symbol" style="background: #2196F3;">
                    <i class="fas fa-train" style="color: white; font-size: 10px;"></i>
                </div>
                <span>Jalur Kereta Api</span>
            </div>

            <div class="legend-item">
                <div class="legend-symbol" style="background: linear-gradient(45deg, #70d6ff, #caffbf);">
                </div>
                <span>Batas Wilayah Jabodetabek</span>
            </div>

            <hr style="margin: 15px 0;">

            <div style="font-size: 12px; color: #666;">
                <i class="fas fa-info-circle"></i>
                Klik pada marker untuk detail informasi
            </div>
        </div>
    `;
                return div;
            },
            onRemove: function(map) {}
        });

        // Add legend to map
        new L.Control.Legend({
            position: 'bottomleft'
        }).addTo(map);

        // Variables untuk chart dan analytics
        let distributionChart, densityChart;
        let stasiunData = [];
        let provinsiCount = {};
        let analysisInitialized = false;

        // Fungsi untuk update statistik berdasarkan data real
        function updateRealStats() {
            console.log('Updating stats with data:', stasiunData.length, 'items');

            if (stasiunData.length === 0) {
                console.log('No data available yet');
                return;
            }

            // Update total stasiun
            const totalStations = stasiunData.length;
            document.getElementById('totalStations').textContent = totalStations;

            // Hitung distribusi per provinsi/kabkot
            provinsiCount = {};
            stasiunData.forEach(feature => {
                // Cek berbagai kemungkinan field untuk wilayah
                const provinsi = feature.properties.provinsi ||
                    feature.properties.kabkot ||
                    feature.properties.PROVINSI ||
                    feature.properties.KABKOT ||
                    'Tidak Diketahui';
                provinsiCount[provinsi] = (provinsiCount[provinsi] || 0) + 1;
            });

            console.log('Distribusi Provinsi/Kabkot:', provinsiCount);

            // Update total provinces/kabkot yang memiliki stasiun
            const totalProvinces = Object.keys(provinsiCount).length;
            document.getElementById('totalProvinces').textContent = totalProvinces;

            // Estimasi total panjang jalur (simulasi)
            const estimatedLength = Math.round(totalStations * 2.5); // Rata-rata 2.5km per stasiun
            document.getElementById('totalLength').textContent = estimatedLength;

            // Initialize charts setelah data siap dengan delay yang cukup
            setTimeout(() => {
                console.log('Initializing charts after data ready...');
                initAnalysisCharts();
            }, 1000); // Increase delay to 1 second
        }

        // Inisialisasi charts dengan data real
        function initAnalysisCharts() {
            console.log('initAnalysisCharts called');
            console.log('provinsiCount:', provinsiCount);
            console.log('analysisInitialized:', analysisInitialized);

            if (Object.keys(provinsiCount).length === 0) {
                console.log('No province data available');
                return;
            }

            // Destroy existing charts
            if (distributionChart) {
                distributionChart.destroy();
                distributionChart = null;
            }
            if (densityChart) {
                densityChart.destroy();
                densityChart = null;
            }

            // Data untuk distribusi chart berdasarkan data real
            const labels = Object.keys(provinsiCount);
            const data = Object.values(provinsiCount);
            const colors = [
                'rgba(102, 126, 234, 0.8)',
                'rgba(118, 75, 162, 0.8)',
                'rgba(240, 147, 251, 0.8)',
                'rgba(245, 87, 108, 0.8)',
                'rgba(79, 172, 254, 0.8)',
                'rgba(0, 242, 254, 0.8)',
                'rgba(255, 193, 7, 0.8)',
                'rgba(220, 53, 69, 0.8)',
                'rgba(40, 167, 69, 0.8)'
            ];

            // Distribution Chart
            const distCtx = document.getElementById('distributionChart');
            if (distCtx && labels.length > 0) {
                console.log('Creating distribution chart with labels:', labels, 'and data:', data);

                try {
                    distributionChart = new Chart(distCtx.getContext('2d'), {
                        type: 'doughnut',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Jumlah Stasiun',
                                data: data,
                                backgroundColor: colors.slice(0, labels.length),
                                borderColor: colors.slice(0, labels.length).map(color => color.replace(
                                    '0.8', '1')),
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom',
                                    labels: {
                                        boxWidth: 12,
                                        font: {
                                            size: 10
                                        }
                                    }
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.label + ': ' + context.parsed + ' stasiun';
                                        }
                                    }
                                }
                            }
                        }
                    });
                    console.log('Distribution chart created successfully');
                } catch (error) {
                    console.error('Error creating distribution chart:', error);
                }
            } else {
                console.log('Distribution chart context not found or no data');
            }

            // Density Chart - analisis cluster berdasarkan data real
            const densCtx = document.getElementById('densityChart');
            if (densCtx) {
                console.log('Creating density chart...');

                try {
                    // Hitung distribusi cluster size berdasarkan provinsi
                    const sortedProvinces = Object.entries(provinsiCount).sort((a, b) => b[1] - a[1]);
                    const clusterData = {
                        'Wilayah Padat (>10 stasiun)': sortedProvinces.filter(([, count]) => count > 10).length,
                        'Wilayah Sedang (5-10 stasiun)': sortedProvinces.filter(([, count]) => count >= 5 && count <=
                            10).length,
                        'Wilayah Jarang (<5 stasiun)': sortedProvinces.filter(([, count]) => count < 5).length
                    };

                    console.log('Cluster data:', clusterData);

                    densityChart = new Chart(densCtx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: Object.keys(clusterData),
                            datasets: [{
                                label: 'Jumlah Wilayah',
                                data: Object.values(clusterData),
                                backgroundColor: ['#ec6fbc', '#f7a1c4', '#b388eb'],
                                borderColor: ['#ec6fbc', '#f7a1c4', '#b388eb'],
                                borderWidth: 2
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        label: function(context) {
                                            return context.parsed.y + ' wilayah';
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
                                    }
                                }
                            }
                        }
                    });
                    console.log('Density chart created successfully');
                } catch (error) {
                    console.error('Error creating density chart:', error);
                }
            } else {
                console.log('Density chart context not found');
            }

            analysisInitialized = true;
        }

        // Tab functions - improved
        function showTab(tabName) {
            console.log('Switching to tab:', tabName);

            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.style.display = 'none';
            });

            // Remove active class from all buttons
            document.querySelectorAll('.tab-button').forEach(btn => {
                btn.classList.remove('active');
            });

            // Show selected tab
            const targetTab = document.getElementById(tabName + '-tab');
            if (targetTab) {
                targetTab.style.display = 'block';
                console.log('Tab shown:', tabName + '-tab');
            } else {
                console.error('Tab not found:', tabName + '-tab');
            }

            // Add active class to clicked button
            event.target.classList.add('active');

            // Initialize or refresh charts when switching to chart tabs
            if ((tabName === 'distribution' || tabName === 'density')) {
                console.log('Chart tab selected, data length:', stasiunData.length);

                if (stasiunData.length > 0 && Object.keys(provinsiCount).length > 0) {
                    // Force reinitialize charts
                    analysisInitialized = false;
                    setTimeout(() => {
                        initAnalysisCharts();
                    }, 300);
                } else {
                    console.log('Data not ready for charts');
                }
            }
        }

        // Toggle panel function
        function togglePanel() {
            const panel = document.getElementById('statsPanel');
            if (panel) {
                if (panel.style.display === 'none') {
                    panel.style.display = 'block';
                } else {
                    panel.style.display = 'none';
                }
            }
        }

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
            "#70d6ff", "#b9fbc0", "#fcd5ce", "#a0c4ff", "#caffbf",
            "#fdffb6", "#ffc6ff", "#ffd6a5", "#9bf6ff"
        ];

        function getPastelColor() {
            return pastelColors[Math.floor(Math.random() * pastelColors.length)];
        }

        function style(feature) {
            return {
                fillColor: getPastelColor(),
                weight: 2,
                opacity: 1,
                color: '#bdbdbd',
                dashArray: '3',
                fillOpacity: 0.5
            };
        }

        // Declare all layer variables
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

        var jalurKeretaLayer;
        var wmsJakarta = L.tileLayer.wms("http://localhost:8080/geoserver/pgwebl/wms", {
            layers: 'pgwebl:Provinsi_Jakarta1',
            format: 'image/png',
            transparent: true,
            version: '1.1.0',
            attribution: "GeoServer"
        });

        // Add WMS Jakarta to map
        wmsJakarta.addTo(map);

        // Load data GeoJSON stasiun - IMPROVED VERSION
        $.getJSON("{{ route('api.stasiun') }}", function(data) {
            console.log("Raw API response:", data);
            console.log("Response type:", typeof data);

            let processedData = [];

            if (Array.isArray(data)) {
                console.log("Data is array with length:", data.length);
                processedData = data;
            } else if (data && data.features && Array.isArray(data.features)) {
                console.log("Data has features array with length:", data.features.length);
                processedData = data.features;
            } else {
                console.error("Format data tidak dikenali:", data);
                return;
            }

            console.log("Processed data length:", processedData.length);
            console.log("Sample data:", processedData[0]);

            if (processedData.length > 0) {
                // Simpan data untuk analisis
                stasiunData = processedData;

                var geoJsonData = {
                    "type": "FeatureCollection",
                    "features": processedData
                };

                stasiun.addData(geoJsonData);
                markers.addLayer(stasiun);
                map.addLayer(markers);

                // Update statistik dan charts dengan data real
                console.log("Calling updateRealStats...");
                updateRealStats();

                console.log("Stasiun data loaded successfully!");
            } else {
                console.error("No data found in response");
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Failed to load stasiun data:", textStatus, errorThrown);
            console.error("Response text:", jqXHR.responseText);
        });

        // Load data GeoJSON jalur kereta
        $.getJSON("/api/jalur-kereta", function(data) {
            console.log("Jalur kereta data:", data);

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

            setupOverlayMaps();

        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error("Failed to load jalur kereta data:", textStatus, errorThrown);
            setupOverlayMaps();
        });

        // Load data GeoJSON polygon Jabodetabek
        $.getJSON("{{ asset('Jabodetabek_New.json') }}", function(data) {
            console.log("Jabodetabek polygon data:", data);

            if (data) {
                polygon = L.geoJSON(data, {
                    style: style,
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

        // Function to setup overlay maps - MOVED TO BOTTOM RIGHT
        function setupOverlayMaps() {
            var overlayMaps = {
                "Titik Stasiun Kereta Api": markers,
                "Batas Administrasi Metropolitan Jabodetabek": polygon,
                "Batas Provinsi DKI Jakarta (GeoServer)": wmsJakarta
            };

            if (jalurKeretaLayer) {
                overlayMaps["Jalur Kereta"] = jalurKeretaLayer;
            }

            // Add layer control to bottom right to avoid collision
            L.control.layers(null, overlayMaps, {
                position: 'bottomright' // Changed from default 'topright' to 'bottomright'
            }).addTo(map);

            console.log("Layer control added to bottom-right with overlays:", Object.keys(overlayMaps));
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
            $('#created_at').val(data.created_at || '');
            $('#updated_at').val(data.updated_at || '');

            if (data.gambar) {
                $('#preview-image-point').attr('src', "/storage/images/" + data.gambar);
                $('#preview-image-point').attr('alt', data.gambar);
            } else {
                $('#preview-image-point').attr('src', '');
                $('#preview-image-point').attr('alt', '');
            }

            $('#editPointForm').attr('action', '/api/stasiun/' + id);
            $('#EditPointModal').modal('show');
        }

        // Add search control to the map
        var searchControl = new L.Control.Search({
            layer: markers,
            propertyName: 'namobj',
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
            $('#editPointForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData();
                formData.append('name', $('#editPointForm #name').val());
                formData.append('description', $('#editPointForm #description').val());
                formData.append('geom_point', $('#editPointForm #geom_point').val());

                if ($('#image_point')[0].files[0]) {
                    formData.append('photo', $('#image_point')[0].files[0]);
                }

                formData.append('_method', 'PUT');
                formData.append('_token', '{{ csrf_token() }}');

                var actionUrl = $(this).attr('action');

                $.ajax({
                    url: actionUrl,
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        alert('Data berhasil diupdate!');
                        $('#EditPointModal').modal('hide');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error("Update error:", xhr.responseJSON);

                        if (xhr.responseJSON && xhr.responseJSON.errors) {
                            var errorMessages = [];
                            for (var field in xhr.responseJSON.errors) {
                                errorMessages.push(field + ': ' + xhr.responseJSON.errors[field]
                                    .join(', '));
                            }
                            alert('Validation errors:\n' + errorMessages.join('\n'));
                        } else {
                            alert('Gagal mengupdate data: ' + (xhr.responseJSON?.message ||
                                error));
                        }
                    }
                });
            });
        });
    </script>
@endsection
