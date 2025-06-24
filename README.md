# JAKTRACK – GIS Stasiun Kereta Jabodetabek

**JAKTRACK** adalah aplikasi Web GIS berbasis **Laravel** untuk manajemen dan visualisasi data **stasiun kereta, jalur kereta, dan wilayah Jabodetabek** secara interaktif.

---

## 🚀 Fitur Utama

- **CRUD Data Stasiun**  
  Tambah, edit, hapus, dan unggah foto stasiun kereta.

- **Peta Interaktif**  
  Menampilkan stasiun, jalur kereta, dan batas wilayah menggunakan **Leaflet**.

- **Cluster Marker**  
  Mengelompokkan marker stasiun agar peta tetap rapi dan responsif.

- **Pencarian & Filter**  
  Cari stasiun berdasarkan nama secara real-time.

- **Digitasi Langsung**  
  Tambah titik (marker), garis (polyline), dan area (polygon) langsung melalui tools di peta.

- **Layer GeoServer**  
  Menampilkan layer batas wilayah (WMS) yang di-*publish* dari GeoServer.

- **Popup Detail**  
  Menampilkan informasi detail untuk setiap stasiun, jalur, dan wilayah.

- **Statistik Sederhana (Opsional)**  
  Menampilkan statistik jumlah stasiun, panjang jalur, dan lainnya.

---

## 🛠️ Instalasi

### 1. Clone Repository

```bash
git clone https://github.com/shaqurra/pgwebl.git
cd pgwebl
2. Install Dependency
bash
Copy
Edit
composer install
npm install
3. Salin File Environment
bash
Copy
Edit
cp .env.example .env
4. Konfigurasi Database
Edit file .env dan sesuaikan bagian berikut dengan database lokal kamu:

makefile
Copy
Edit
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=namadatabase
DB_USERNAME=root
DB_PASSWORD=
5. Generate Application Key
bash
Copy
Edit
php artisan key:generate
6. Migrasi dan Seed Database
bash
Copy
Edit
php artisan migrate --seed
7. Jalankan Server Laravel
bash
Copy
Edit
php artisan serve
8. Akses Aplikasi
Buka browser dan akses:
http://127.0.0.1:8000

📁 Struktur Folder Penting
Folder/File	Deskripsi
resources/views/	Blade template (form, peta, dsb)
public/	Aset publik (gambar, CSS, JS)
routes/api.php	Endpoint API untuk data stasiun dan jalur
app/Http/Controllers/	Controller Laravel

🧰 Teknologi yang Digunakan
Laravel 10+

Leaflet.js & Leaflet.markercluster

Bootstrap 5 & jQuery

GeoServer (untuk layer WMS)

PostgreSQL + PostGIS (disarankan)

🗺️ Cara Penggunaan
CRUD Stasiun: Klik marker di peta → tombol Edit/Hapus.

Digitasi Polyline/Polygon: Gunakan tools di pojok kiri atas peta.

Pencarian: Gunakan search bar di peta.

Tabel Data: Klik menu Data/Table di navbar.

🤝 Kontributor
@shaqurra

⚠️ Catatan
Untuk menampilkan layer dari GeoServer, pastikan:

GeoServer aktif di http://localhost:8080

Layer batas wilayah sudah dipublish dan tersedia sebagai WMS.

