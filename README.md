# JAKTRACK - GIS Stasiun Kereta Jabodetabek

JAKTRACK adalah aplikasi Web GIS berbasis Laravel untuk manajemen dan visualisasi data stasiun kereta, jalur kereta, dan wilayah Jabodetabek secara interaktif.

## Fitur Utama

- **CRUD Data Stasiun**: Tambah, edit, hapus, dan upload foto stasiun kereta.
- **Peta Interaktif**: Visualisasi stasiun, jalur kereta, dan batas wilayah dengan Leaflet.
- **Cluster Marker**: Pengelompokan marker stasiun agar peta tetap rapi.
- **Search & Filter**: Cari stasiun berdasarkan nama.
- **Digitasi Langsung**: Tambah titik, polyline, dan polygon langsung di peta.
- **Layer GeoServer**: Menampilkan batas provinsi dari GeoServer.
- **Popup Detail**: Lihat detail stasiun, jalur, dan wilayah langsung di peta.
- **Statistik Sederhana**: (Opsional) Statistik jumlah stasiun, jalur, dll.

## Instalasi

1. **Clone repository**
   ```sh
   git clone https://github.com/shaqurra/pgwebl.git
   cd pgwebl
2.Install dependency
    composer install
    npm install
3. Copy file environment
    cp .env.example .env
4. Atur konfigurasi database di file .env
5. Generate key
6. <vscode_annotation details='%5B%7B%22title%22%3A%22hardcoded-    credentials%22%2C%22description%22%3A%22Embedding%20credentials%20in%20source%20code%20risks%20unauthorized%20access%22%7D%5D'>.</vscode_annotation> Migrasi dan seed database
php artisan migrate --seed
7. Jalankan server
    php artisan serve
8. Akses Aplikasi
http://127.0.0.1:8000
Struktur Folder Penting
resources/views/ — Blade template (peta, form, dsb)
public/ — Asset publik (gambar, JS, CSS)
routes/api.php — API endpoint untuk data stasiun, jalur, polygon
app/Http/Controllers/ — Controller Laravel
Teknologi
Laravel 10+
Leaflet.js & Leaflet.markercluster
jQuery
Bootstrap 5
GeoServer (untuk layer WMS)
PostgreSQL/PostGIS (disarankan untuk data spasial)
Cara Penggunaan
Tambah/Edit/Hapus Stasiun: Klik marker di peta, gunakan tombol Edit/Hapus.
Tambah Polyline/Polygon: Gunakan tools digitasi di pojok kiri atas peta.
Cari Stasiun: Gunakan fitur search di peta.
Lihat Data Tabel: Klik menu Data/Table di navbar.
Kontributor
shaqurra
Catatan:
Untuk menampilkan layer GeoServer, pastikan GeoServer sudah berjalan di localhost:8080 dan layer sudah dipublish.

Lisensi
MIT License
