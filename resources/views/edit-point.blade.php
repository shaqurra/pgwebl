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

        .popup-image {
            max-width: 200px;
            max-height: 150px;
            object-fit: cover;
            border-radius: 4px;
        }
    </style>
@endsection

@section('content')
    <div id="map"></div>

    <!-- Modal Edit Point - FIXED FORM -->
    <div class="modal fade" id="EditPointModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Edit Point</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- FIXED FORM - Action URL will be set dynamically -->
                <form method="POST" id="editPointForm" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="modal-body">
                        <input type="hidden" name="id" id="point_id">

                        <div class="mb-3">
                            <label for="name" class="form-label fw-semibold">Name *</label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Fill point name here..." required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label fw-semibold">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="geom_point" class="form-label fw-semibold">Geometry</label>
                            <input type="text" class="form-control" id="geom_point" name="geom_point" readonly>
                            <small class="text-muted">Format: POINT(lon lat)</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Created At</label>
                                    <input type="text" class="form-control" id="created_at" name="created_at" readonly>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">Updated At</label>
                                    <input type="text" class="form-control" id="updated_at" name="updated_at" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- PHOTO SECTION -->
                        <div class="mb-3">
                            <label for="photo" class="form-label fw-semibold">Photo</label>
                            <input type="file" class="form-control" id="photo" name="photo" accept="image/*"
                                onchange="previewImage(this)">
                            <div class="mt-2">
                                <small class="text-muted">Current photo:</small>
                                <div id="current-image-container" style="display: none;">
                                    <img id="current-image" class="img-thumbnail"
                                        style="max-width: 200px; max-height: 150px;">
                                    <p class="text-muted small" id="current-image-name"></p>
                                </div>
                            </div>
                            <div class="mt-2">
                                <small class="text-muted">New photo preview:</small>
                                <div id="preview-container" style="display: none;">
                                    <img id="preview-image-point" class="img-thumbnail"
                                        style="max-width: 200px; max-height: 150px;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Update Data</button>
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
        // FIXED JAVASCRIPT
        var map = L.map('map').setView([-7.766582, 110.374977], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var drawnItems = new L.FeatureGroup();
        map.addLayer(drawnItems);

        var drawControl = new L.Control.Draw({
            draw: false,
            edit: {
                featureGroup: drawnItems,
                edit: true,
                remove: false
            }
        });
        map.addControl(drawControl);

        // Function to preview new image
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#preview-image-point').attr('src', e.target.result);
                    $('#preview-container').show();
                };
                reader.readAsDataURL(input.files[0]);
            } else {
                $('#preview-container').hide();
            }
        }

        // FIXED: Function to populate modal with feature data
        function populateModal(feature) {
            var props = feature.properties || {};
            
            // Set form action URL dynamically
            var pointId = props.id || '';
            $('#editPointForm').attr('action', "{{ url('api/stasiun') }}/" + pointId);
            
            // Populate form fields
            $('#point_id').val(pointId);
            $('#name').val(props.namobj || props.name || '');
            $('#description').val(props.kabkot || props.description || '');
            
            // Handle geometry
            if (feature.geometry && feature.geometry.coordinates && feature.geometry.coordinates.length === 2) {
                $('#geom_point').val(`POINT(${feature.geometry.coordinates[0]} ${feature.geometry.coordinates[1]})`);
            } else {
                $('#geom_point').val('');
            }
            
            $('#created_at').val(props.created_at || '');
            $('#updated_at').val(props.updated_at || '');

            // Reset image previews
            $('#preview-container').hide();
            $('#current-image-container').hide();

            // Handle current image
            var imageName = props.gambar || props.photo || props.image || props.foto;
            if (imageName && imageName.trim() !== '') {
                var imageUrl = "{{ asset('storage/images/') }}/" + imageName;
                $('#current-image').attr('src', imageUrl);
                $('#current-image-name').text(imageName);
                $('#current-image-container').show();
            }
        }

        // Function to handle edit button click in popup
        function editPoint(pointData) {
            populateModal(pointData);
            $('#EditPointModal').modal('show');
        }

        // FIXED: Create point layer
        var pointLayer = L.geoJson(null, {
            pointToLayer: function(feature, latlng) {
                let iconUrl = '/storage/images/polisi.png';
                if (feature.properties && feature.properties.type) {
                    switch (feature.properties.type) {
                        case 'polsek':
                            iconUrl = '/storage/images/polisi.png';
                            break;
                        case 'posronda':
                            iconUrl = '/storage/images/posronda.png';
                            break;
                        case 'koramil':
                            iconUrl = '/storage/images/koramil.png';
                            break;
                        case 'satpolpp':
                            iconUrl = '/storage/images/satpolpp.png';
                            break;
                        case 'damkar':
                            iconUrl = '/storage/images/damkar.png';
                            break;
                    }
                }

                var customIcon = L.icon({
                    iconUrl: iconUrl,
                    iconSize: [40, 40],
                    iconAnchor: [20, 40],
                    popupAnchor: [0, -40]
                });

                return L.marker(latlng, {
                    icon: customIcon
                });
            },

            onEachFeature: function(feature, layer) {
                var props = feature.properties;

                // Build image HTML
                var imageName = props.gambar || props.photo || props.image || props.foto;
                var imageHtml = '';

                if (imageName && imageName.trim() !== '') {
                    var imageUrl = "{{ asset('storage/images/') }}/" + imageName;
                    imageHtml = `<img src="${imageUrl}" alt="Gambar Stasiun" class="popup-image" style="width:100%;max-width:200px;margin-top:8px;" onerror="this.style.display='none'; this.nextSibling.style.display='block';">
                                <p style="display:none; color:red; font-size:12px;">Image not found</p><br>`;
                }

                var popupContent = `
                    <div style="min-width: 250px;">
                        <strong>Nama Stasiun:</strong> ${props.namobj || props.name || '-'}<br>
                        <strong>Kode:</strong> ${props.kodkod || props.kode || '-'}<br>
                        <strong>Kab/Kota:</strong> ${props.kabkot || '-'}<br>
                        <strong>Provinsi:</strong> ${props.provinsi || '-'}<br>
                        <strong>User ID:</strong> ${props.user_id || '-'}<br>
                        <strong>Created:</strong> ${props.created_at || '-'}<br>
                        <strong>Updated:</strong> ${props.updated_at || '-'}<br>
                        ${imageHtml}
                        <div class="mt-2">
                            <button class="btn btn-primary btn-sm" onclick="editPoint(${JSON.stringify(feature).replace(/"/g, '&quot;')})">Edit</button>
                            <button class="btn btn-danger btn-sm" onclick="deletePoint(${props.id})">Hapus</button>
                        </div>
                    </div>
                `;

                layer.bindPopup(popupContent, {
                    maxWidth: 300,
                    className: 'custom-popup'
                });

                drawnItems.addLayer(layer);
            }
        });

        // Load GeoJSON point from API
        $.getJSON("{{ route('api.stasiun') }}", function(data) {
            console.log('API Data:', data); // Debug log
            pointLayer.addData(data);
            map.addLayer(pointLayer);

            if (pointLayer.getBounds && pointLayer.getBounds().isValid()) {
                map.fitBounds(pointLayer.getBounds(), {
                    padding: [50, 50]
                });
            }
        }).fail(function(jqXHR, textStatus, errorThrown) {
            console.error('Error loading GeoJSON data:', textStatus, errorThrown);
            alert('Error loading map data');
        });

        // When feature is edited via draw controls
        map.on('draw:edited', function(e) {
            var layers = e.layers;
            layers.eachLayer(function(layer) {
                var feature = layer.feature || layer.toGeoJSON();
                populateModal(feature);
                $('#EditPointModal').modal('show');
            });
        });

        // FIXED: Form submission handler
        $('#editPointForm').on('submit', function(e) {
            e.preventDefault();

            var formData = new FormData(this);
            var actionUrl = $(this).attr('action');
            
            // Validate action URL
            if (!actionUrl) {
                alert('Error: Form action URL not set');
                return;
            }

            // Show loading
            var submitBtn = $(this).find('button[type="submit"]');
            var originalText = submitBtn.text();
            submitBtn.text('Updating...').prop('disabled', true);

            $.ajax({
                url: actionUrl,
                type: 'POST', // Use POST with _method override
                data: formData,
                processData: false,
                contentType: false,
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert('Data berhasil diupdate!');
                    $('#EditPointModal').modal('hide');
                    location.reload();
                },
                error: function(xhr, status, error) {
                    console.error('Update error:', xhr.responseJSON);
                    var errorMessage = 'Error updating data: ';
                    
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        var errors = xhr.responseJSON.errors;
                        var errorList = [];
                        for (var field in errors) {
                            errorList.push(field + ': ' + errors[field].join(', '));
                        }
                        errorMessage += errorList.join('; ');
                    } else if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMessage += xhr.responseJSON.message;
                    } else {
                        errorMessage += error;
                    }
                    
                    alert(errorMessage);
                },
                complete: function() {
                    submitBtn.text(originalText).prop('disabled', false);
                }
            });
        });

        // Function to delete point
        function deletePoint(pointId) {
            if (confirm('Apakah Anda yakin ingin menghapus point ini?')) {
                $.ajax({
                    url: "{{ url('api/stasiun') }}/" + pointId,
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        alert('Point berhasil dihapus!');
                        location.reload();
                    },
                    error: function(xhr, status, error) {
                        console.error('Delete error:', xhr.responseJSON);
                        alert('Error deleting point: ' + (xhr.responseJSON?.message || error));
                    }
                });
            }
        }
    </script>
@endsection