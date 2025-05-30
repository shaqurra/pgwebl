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

    <!-- Modal Edit Point -->
    <div class="modal fade" id="EditPointModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Point</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Form Update -->
                <form method="POST" action="{{ route('points.update', $id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')

                    <div class="modal-body">
                        <input type="hidden" name="id" id="id" value="{{ $id }}">

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Fill point name here..." required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_point" class="form-label fw-semibold">Geometry</label>
                            <textarea class="form-control" id="geom_point" name="geom_point" rows="3" readonly></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label fw-semibold">Photo</label>
                            <input type="file" class="form-control" id="image_point" name="image"
                                onchange="document.getElementById('image-point-preview').src = window.URL.createObjectURL(this.files[0])">
                            <div class="text-center my-3">
                                <img alt="Preview Image" id="image-point-preview"
                                    class="img-thumbnail text-center" width="400">
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Edit Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js"></script>
    <script src="https://unpkg.com/@terraformer/wkt"></script>

    <script>
        var map = L.map('map').setView([-7.766582, 110.374977], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Group for drawn items
        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        // Add draw control for editing
        var drawControl = new L.Control.Draw({
            draw: false,
            edit: {
                featureGroup: drawnItems,
                edit: true,
                remove: false
            }
        });
        map.addControl(drawControl);

        // When feature is edited
        map.on('draw:edited', function(e) {
            var layers = e.layers;

            layers.eachLayer(function(layer) {
                var geojson = layer.toGeoJSON();
                var wkt = Terraformer.geojsonToWKT(geojson.geometry);

                var props = geojson.properties || {};

                $('#name').val(props.name || '');
                $('#description').val(props.description || '');
                $('#geom_point').val(wkt);

                if (props.image) {
                    $('#image-point-preview').attr('src', "{{ asset('storage/images') }}/" + props.image);
                }

                $('#EditPointModal').modal('show');
            });
        });

        // Load GeoJSON point from API
        var pointLayer = L.geoJson(null, {
            onEachFeature: function(feature, layer) {
                drawnItems.addLayer(layer);

                var props = feature.properties;
                var wkt = Terraformer.geojsonToWKT(feature.geometry);

                layer.on('click', function() {
                    $('#name').val(props.name || '');
                    $('#description').val(props.description || '');
                    $('#geom_point').val(wkt);

                    if (props.image) {
                        $('#image-point-preview').attr('src', "{{ asset('storage/images') }}/" + props.image);
                        $('#image-point-preview').attr('alt', props.image);
                        $('#image-point-preview').attr('width', 400);
                    }

                    $('#EditPointModal').modal('show');
                });
            }
        });

        $.getJSON("{{ route('api.point', $id) }}", function(data) {
            pointLayer.addData(data);
            map.addLayer(pointLayer);
            map.fitBounds(pointLayer.getBounds(), { padding: [100, 100] });
        });
    </script>
@endsection
