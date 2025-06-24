# JAKTRACK – GIS Stasiun Kereta Jabodetabek

**JAKTRACK** adalah aplikasi Web GIS berbasis **Laravel** untuk manajemen dan visualisasi data **stasiun kereta, jalur kereta, dan wilayah Jabodetabek** secara interaktif.

## 🚀 Fitur Utama

- **CRUD Data Stasiun** – Tambah, edit, hapus, dan unggah foto stasiun kereta.
- **Peta Interaktif** – Visualisasi stasiun, jalur kereta, dan batas wilayah menggunakan Leaflet.
- **Cluster Marker** – Pengelompokan marker agar tampilan peta tetap rapi.
- **Pencarian & Filter** – Cari stasiun berdasarkan nama secara real-time.
- **Digitasi Langsung** – Tambah titik, garis, dan area langsung di peta.
- **Layer GeoServer** – Menampilkan batas wilayah menggunakan WMS dari GeoServer.
- **Popup Detail** – Informasi langsung pada klik marker/polyline/polygon.
- **Statistik Sederhana (Opsional)** – Statistik jumlah stasiun, jalur, dll.

## 🛠️ Instalasi

```bash
git clone https://github.com/shaqurra/pgwebl.git
cd pgwebl
composer install
npm install
cp .env.example .env
```

Lalu edit file `.env` dan atur konfigurasi database:

```
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=namadatabase
DB_USERNAME=root
DB_PASSWORD=
```

Lanjutkan dengan:

```bash
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Akses aplikasi di browser melalui [http://127.0.0.1:8000](http://127.0.0.1:8000)

## 📁 Struktur Folder Penting

- `resources/views/` – Template blade (peta, form, dll)
- `public/` – Asset publik (gambar, CSS, JS)
- `routes/api.php` – Endpoint API untuk data spasial
- `app/Http/Controllers/` – Logic controller Laravel

## 🧰 Teknologi yang Digunakan

- Laravel 10+
- Leaflet.js & Leaflet.markercluster
- Bootstrap 5 & jQuery
- PostgreSQL + PostGIS (disarankan)
- GeoServer (untuk layer WMS)

## 🗺️ Cara Penggunaan

- Klik marker → Edit atau Hapus data stasiun
- Gunakan toolbar di pojok kiri atas untuk menambah titik/garis/area
- Cari stasiun dengan fitur search
- Buka tabel data dari navbar

## 🤝 Kontributor

- [@shaqurra](https://github.com/shaqurra)

## ⚠️ Catatan

- Pastikan GeoServer aktif di `localhost:8080`
- Layer batas wilayah sudah dipublish dan tersedia sebagai WMS

## 📄 Lisensi

MIT License

## 🖼️ Tampilan Aplikasi

Berikut beberapa tampilan antarmuka dari aplikasi JAKTRACK:

### 📍 Halaman Peta Interaktif
![Halaman Peta](Screenshot%202025-06-25%20001427.png)

### 📝 CRUD Stasiun Kereta
![CRUD Stasiun](Screenshot%202025-06-25%20001556.png)

### ➕ Digitasi Jalur dan Wilayah
![Digitasi Peta](Screenshot%202025-06-25%20001700.png)

### 📊 Statistik dan Layer GeoServer
![Statistik GeoServer](Screenshot%202025-06-25%20001737.png)
