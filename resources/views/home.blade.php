<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JAKTRACK - Sistem Informasi Geospasial Stasiun</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
            background: #0a0a0a;
            color: #fff;
        }

        /* Animated Background */
        .bg-animation {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(45deg, #0f0f23, #1a1a2e, #16213e);
            animation: gradientShift 8s ease infinite;
        }

        .bg-animation::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at 20% 80%, rgba(120, 119, 198, 0.3) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 119, 198, 0.3) 0%, transparent 50%);
            animation: pulse 4s ease-in-out infinite alternate;
        }

        @keyframes gradientShift {

            0%,
            100% {
                background: linear-gradient(45deg, #0f0f23, #1a1a2e, #16213e);
            }

            50% {
                background: linear-gradient(45deg, #16213e, #0f0f23, #1a1a2e);
            }
        }

        @keyframes pulse {
            0% {
                opacity: 0.5;
            }

            100% {
                opacity: 1;
            }
        }

        /* Floating particles */
        .particle {
            position: absolute;
            width: 3px;
            height: 3px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px) rotate(0deg);
                opacity: 0.3;
            }

            50% {
                transform: translateY(-20px) rotate(180deg);
                opacity: 1;
            }
        }

        /* Header */
        .header {
            position: fixed;
            top: 0;
            width: 100%;
            padding: 20px 50px;
            background: rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .header.scrolled {
            background: rgba(0, 0, 0, 0.9);
            padding: 15px 50px;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 28px;
            font-weight: bold;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .nav-links {
            display: flex;
            list-style: none;
            gap: 30px;
        }

        .nav-links a {
            color: #fff;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            cursor: pointer;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 0 20px;
            position: relative;
        }

        .hero-content {
            max-width: 800px;
            animation: fadeInUp 1s ease-out;
        }

        .hero-badge {
            display: inline-block;
            padding: 8px 20px;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 50px;
            font-size: 14px;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            animation: glow 2s ease-in-out infinite alternate;
        }

        @keyframes glow {
            from {
                box-shadow: 0 0 20px rgba(255, 107, 107, 0.3);
            }

            to {
                box-shadow: 0 0 30px rgba(78, 205, 196, 0.5);
            }
        }

        .hero-title {
            font-size: clamp(3rem, 8vw, 6rem);
            font-weight: 900;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4, #45b7d1, #96ceb4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: slideInLeft 1s ease-out 0.3s both;
        }

        .hero-subtitle {
            font-size: 1.4rem;
            color: #b0b0b0;
            margin-bottom: 40px;
            line-height: 1.6;
            animation: slideInRight 1s ease-out 0.6s both;
        }

        .icon-row {
            display: flex;
            justify-content: center;
            gap: 32px;
            margin: 32px 0;
        }

        .icon-item {
            text-align: center;
            transition: transform 0.3s ease;
        }

        .icon-item:hover {
            transform: translateY(-5px);
        }

        .icon-item img {
            height: 60px;
            filter: drop-shadow(0 0 10px rgba(78, 205, 196, 0.3));
        }

        .icon-item div {
            margin-top: 8px;
            font-size: 0.95rem;
            color: #b0b0b0;
        }

        .cta-buttons {
            display: flex;
            gap: 20px;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease-out 0.9s both;
        }

        .btn {
            padding: 15px 35px;
            border: none;
            border-radius: 50px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
            position: relative;
            overflow: hidden;
        }

        .btn-primary {
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            color: #fff;
        }

        .btn-primary::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, #4ecdc4, #ff6b6b);
            transition: left 0.3s ease;
            z-index: -1;
        }

        .btn-primary:hover::before {
            left: 0;
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 107, 107, 0.4);
        }

        .btn-secondary {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(255, 255, 255, 0.1);
        }

        /* Features Section */
        .features {
            padding: 100px 50px;
            background: rgba(0, 0, 0, 0.5);
            backdrop-filter: blur(10px);
        }

        .section-title {
            text-align: center;
            font-size: 3rem;
            font-weight: bold;
            margin-bottom: 20px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .section-subtitle {
            text-align: center;
            font-size: 1.2rem;
            color: #b0b0b0;
            margin-bottom: 60px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 30px;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px 30px;
            text-align: center;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            position: relative;
            overflow: hidden;
            cursor: pointer;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(45deg, rgba(255, 107, 107, 0.1), rgba(78, 205, 196, 0.1));
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .feature-card:hover::before {
            opacity: 1;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.3);
            border-color: rgba(255, 107, 107, 0.3);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 20px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            position: relative;
            z-index: 1;
        }

        .feature-title {
            font-size: 1.5rem;
            font-weight: bold;
            margin-bottom: 15px;
            color: #fff;
            position: relative;
            z-index: 1;
        }

        .feature-desc {
            color: #b0b0b0;
            line-height: 1.6;
            position: relative;
            z-index: 1;
        }

        /* Student Info */
        .student-info {
            background: rgba(0, 0, 0, 0.7);
            padding: 60px 50px;
            text-align: center;
        }

        .student-card {
            max-width: 500px;
            margin: 0 auto;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px;
            backdrop-filter: blur(10px);
        }

        .student-title {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 30px;
            background: linear-gradient(45deg, #ff6b6b, #4ecdc4);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .student-details {
            list-style: none;
            padding: 0;
        }

        .student-details li {
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .student-details li:last-child {
            border-bottom: none;
        }

        .student-label {
            font-weight: 600;
            color: #4ecdc4;
        }

        .student-value {
            color: #fff;
            font-weight: 500;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            backdrop-filter: blur(10px);
        }

        .modal-content {
            background: linear-gradient(145deg, #1a1a2e, #16213e);
            margin: 5% auto;
            padding: 30px;
            border-radius: 20px;
            width: 90%;
            max-width: 600px;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.1);
            animation: modalSlideIn 0.3s ease-out;
        }

        @keyframes modalSlideIn {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .close {
            position: absolute;
            right: 20px;
            top: 20px;
            font-size: 28px;
            font-weight: bold;
            color: #fff;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .close:hover {
            color: #ff6b6b;
        }

        .modal h2 {
            color: #4ecdc4;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        .modal p {
            color: #b0b0b0;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .modal ul {
            color: #fff;
            margin-left: 20px;
        }

        .modal li {
            margin-bottom: 8px;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(50px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header {
                padding: 15px 20px;
            }

            .nav-links {
                display: none;
            }

            .hero {
                padding: 0 15px;
            }

            .features {
                padding: 60px 20px;
            }

            .student-info {
                padding: 40px 20px;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .icon-row {
                gap: 20px;
            }
        }
    </style>
</head>

<body>
    <div class="bg-animation"></div>

    <!-- Floating Particles -->
    <div class="particle" style="top: 10%; left: 10%; animation-delay: 0s;"></div>
    <div class="particle" style="top: 20%; left: 80%; animation-delay: 1s;"></div>
    <div class="particle" style="top: 60%; left: 20%; animation-delay: 2s;"></div>
    <div class="particle" style="top: 80%; left: 70%; animation-delay: 3s;"></div>
    <div class="particle" style="top: 30%; left: 90%; animation-delay: 4s;"></div>
    <div class="particle" style="top: 70%; left: 5%; animation-delay: 5s;"></div>

    <!-- Header -->
    <header class="header" id="header">
        <nav class="nav">
            <div class="logo">JAKTRACK</div>
            <ul class="nav-links">
                <li><a href="#home">Beranda</a></li>
                <li><a href="#features">Fitur</a></li>
                <li><a href="#about">Tentang</a></li>
                <li><a onclick="showLogin()">Login</a></li>
                <li><a onclick="showTables()">Data</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content">
            <div class="hero-badge">🚉 SIG Stasiun Jabodetabek</div>
            <h1 class="hero-title">JAKTRACK</h1>
            <div class="icon-row">
                <div class="icon-item">
                    <div
                        style="width: 60px; height: 60px; background: linear-gradient(45deg, #ff6b6b, #4ecdc4); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto;">
                        🚉</div>
                    <div>Stasiun</div>
                </div>
                <div class="icon-item">
                    <div
                        style="width: 60px; height: 60px; background: linear-gradient(45deg, #4ecdc4, #45b7d1); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto;">
                        🗺️</div>
                    <div>Peta</div>
                </div>
                <div class="icon-item">
                    <div
                        style="width: 60px; height: 60px; background: linear-gradient(45deg, #45b7d1, #96ceb4); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto;">
                        📊</div>
                    <div>Data</div>
                </div>
            </div>
            <p class="hero-subtitle">
                <b>JAKTRACK</b> (Jakarta Railway Tracker) –
                Peta interaktif & kekinian untuk eksplorasi stasiun kereta Jabodetabek.
                Temukan info, lokasi, dan vibes stasiun favoritmu di Jakarta & sekitarnya! 🚆✨
            </p>
            <div class="cta-buttons">
                <a href="/map" class="btn btn-primary">🚀 Jelajahi Peta</a>
                <a href="/login" class="btn btn-secondary">🔐 Login Admin</a>
                <a href="/table" class="btn btn-secondary">📋 Data Stasiun</a>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <h2 class="section-title">Fitur Unggulan</h2>
        <p class="section-subtitle">Jelajahi berbagai fitur canggih untuk mengelola dan memvisualisasikan data stasiun
            kereta</p>

        <div class="features-grid">
            <div class="feature-card" onclick="showFeatureDetail('mapping')">
                <div class="feature-icon">🗺️</div>
                <h3 class="feature-title">Pemetaan Real-Time</h3>
                <p class="feature-desc">Lihat dan pantau lokasi stasiun secara real-time dengan sistem yang terintegrasi
                    dan responsif.</p>
            </div>

            <div class="feature-card" onclick="showFeatureDetail('analysis')">
                <div class="feature-icon">📊</div>
                <h3 class="feature-title">Analisis Spasial</h3>
                <p class="feature-desc">Gunakan alat analisis canggih untuk memahami pola distribusi dan aksesibilitas
                    stasiun.</p>
            </div>

            <div class="feature-card" onclick="showFeatureDetail('management')">
                <div class="feature-icon">⚙️</div>
                <h3 class="feature-title">Manajemen Data</h3>
                <p class="feature-desc">Tambahkan, ubah, dan hapus data stasiun dengan interface yang intuitif dan mudah
                    digunakan.</p>
            </div>

            <div class="feature-card" onclick="showFeatureDetail('visualization')">
                <div class="feature-icon">📈</div>
                <h3 class="feature-title">Visualisasi Interaktif</h3>
                <p class="feature-desc">Tampilkan data dalam bentuk peta interaktif, grafik, dan dashboard yang
                    informatif.</p>
            </div>
        </div>
    </section>

    <!-- Student Info -->
    <section class="student-info" id="about">
        <div class="student-card">
            <h2 class="student-title">Informasi Pengembang</h2>
            <ul class="student-details">
                <li>
                    <span class="student-label">👤 Nama:</span>
                    <span class="student-value">Shafaa Qurrata A'yun</span>
                </li>
                <li>
                    <span class="student-label">🎓 NIM:</span>
                    <span class="student-value">23/520668/SV/23277</span>
                </li>
                <li>
                    <span class="student-label">📚 Kelas:</span>
                    <span class="student-value">B</span>
                </li>
                <li>
                    <span class="student-label">🏫 Program:</span>
                    <span class="student-value">Sistem Informasi Geografi</span>
                </li>
            </ul>
        </div>
    </section>

    <!-- Modals -->
    <div id="mapModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('mapModal')">&times;</span>
            <h2>🗺️ Peta Interaktif JAKTRACK</h2>
            <p>Selamat datang di fitur peta interaktif JAKTRACK! Di sini Anda dapat:</p>
            <ul>
                <li>Menjelajahi lokasi semua stasiun kereta di Jabodetabek</li>
                <li>Melihat informasi detail setiap stasiun</li>
                <li>Menambahkan titik, garis, dan area baru</li>
                <li>Mengupload foto dan dokumentasi</li>
                <li>Mengekspor data dalam format GeoJSON</li>
                <li>Menggunakan tools analisis spasial</li>
            </ul>
            <p>Klik tombol di bawah untuk mulai menjelajahi peta!</p>
            <div style="text-align: center; margin-top: 20px;">
                <button class="btn btn-primary" onclick="alert('Mengalihkan ke halaman peta...')">Buka Peta
                    Sekarang</button>
            </div>
        </div>
    </div>

    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('loginModal')">&times;</span>
            <h2>🔐 Login Administrator</h2>
            <p>Masuk sebagai administrator untuk mengakses fitur penuh JAKTRACK:</p>
            <ul>
                <li>Mengelola semua data stasiun</li>
                <li>Mengakses dashboard admin</li>
                <li>Menambah/edit/hapus informasi stasiun</li>
                <li>Mengatur hak akses pengguna</li>
                <li>Melihat laporan dan statistik</li>
                <li>Mengekspor data dalam berbagai format</li>
            </ul>
            <p>Silakan login dengan kredensial administrator Anda.</p>
            <div style="text-align: center; margin-top: 20px;">
                <button class="btn btn-primary" onclick="alert('Mengalihkan ke halaman login...')">Login
                    Sekarang</button>
            </div>
        </div>
    </div>

    <div id="dataModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('dataModal')">&times;</span>
            <h2>📊 Data Stasiun Jabodetabek</h2>
            <p>Akses database lengkap stasiun kereta di area Jabodetabek:</p>
            <ul>
                <li>Daftar semua stasiun dengan informasi lengkap</li>
                <li>Pencarian dan filter data berdasarkan kriteria</li>
                <li>Sorting data berdasarkan nama, lokasi, jalur</li>
                <li>Pagination untuk navigasi data yang mudah</li>
                <li>Export data ke Excel, CSV, atau PDF</li>
                <li>Statistik dan analisis data real-time</li>
            </ul>
            <p>Jelajahi data komprehensif tentang infrastruktur kereta di Jakarta dan sekitarnya.</p>
            <div style="text-align: center; margin-top: 20px;">
                <button class="btn btn-primary" onclick="window.location.href='/table'">Lihat Data</button>
            </div>
        </div>
    </div>

    <div id="featureModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('featureModal')">&times;</span>
            <h2 id="featureTitle">Fitur Detail</h2>
            <div id="featureContent"></div>
        </div>
    </div>

    <script>
        // Header scroll effect
        window.addEventListener('scroll', () => {
            const header = document.getElementById('header');
            if (window.scrollY > 100) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Modal functions
        function showMap() {
            document.getElementById('mapModal').style.display = 'block';
        }

        function showLogin() {
            document.getElementById('loginModal').style.display = 'block';
        }

        function showTables() {
            document.getElementById('dataModal').style.display = 'block';
        }

        function closeModal(modalId) {
            document.getElementById(modalId).style.display = 'none';
        }

        function showFeatureDetail(feature) {
            const modal = document.getElementById('featureModal');
            const title = document.getElementById('featureTitle');
            const content = document.getElementById('featureContent');

            const features = {
                mapping: {
                    title: '🗺️ Pemetaan Real-Time',
                    content: `
                        <p>Fitur pemetaan real-time JAKTRACK memungkinkan Anda untuk:</p>
                        <ul>
                            <li><strong>Visualisasi Live:</strong> Melihat posisi stasiun secara real-time</li>
                            <li><strong>Update Otomatis:</strong> Data yang selalu ter-update secara otomatis</li>
                            <li><strong>Interactive Markers:</strong> Klik marker untuk info detail stasiun</li>
                            <li><strong>Layer Control:</strong> Kontrol layer peta sesuai kebutuhan</li>
                            <li><strong>Geolocation:</strong> Temukan stasiun terdekat dari lokasi Anda</li>
                            <li><strong>Route Planning:</strong> Rencanakan rute perjalanan kereta</li>
                        </ul>
                        <p>Teknologi yang digunakan: Leaflet.js, WebSocket, REST API</p>
                    `
                },
                analysis: {
                    title: '📊 Analisis Spasial',
                    content: `
                        <p>Tools analisis spasial yang tersedia:</p>
                        <ul>
                            <li><strong>Buffer Analysis:</strong> Analisis area cakupan stasiun</li>
                            <li><strong>Density Analysis:</strong> Analisis kepadatan stasiun per wilayah</li>
                            <li><strong>Distance Calculation:</strong> Perhitungan jarak antar stasiun</li>
                            <li><strong>Accessibility Analysis:</strong> Analisis aksesibilitas transportasi</li>
                            <li><strong>Coverage Analysis:</strong> Analisis cakupan layanan kereta</li>
                            <li><strong>Statistical Reports:</strong> Laporan statistik spasial</li>
                        </ul>
                        <p>Semua analisis dilengkapi dengan visualisasi grafik dan export hasil.</p>
                    `
                },
                management: {
                    title: '⚙️ Manajemen Data',
                    content: `
                        <p>Sistem manajemen data yang lengkap:</p>
                        <ul>
                            <li><strong>CRUD Operations:</strong> Create, Read, Update, Delete data stasiun</li>
                            <li><strong>Bulk Import:</strong> Import data dalam jumlah besar dari Excel/CSV</li>
                            <li><strong>Data Validation:</strong> Validasi otomatis untuk konsistensi data</li>
                            <li><strong>Version Control:</strong> Tracking perubahan data dengan history</li>
                            <li><strong>Backup & Restore:</strong> Sistem backup otomatis dan restore</li>
                            <li><strong>User Management:</strong> Kontrol akses berdasarkan role pengguna</li>
                        </ul>
                        <p>Interface yang user-friendly dengan form wizard dan drag-drop upload.</p>
                    `
                },
                visualization: {
                    title: '📈 Visualisasi Interaktif',
                    content: `
                        <p>Berbagai cara visualisasi data yang menarik:</p>
                        <ul>
                            <li><strong>Interactive Maps:</strong> Peta interaktif dengan berbagai layer</li>
                            <li><strong>Charts & Graphs:</strong> Grafik batang, pie, line, dan scatter plot</li>
                            <li><strong>Heatmaps:</strong> Peta panas untuk visualisasi density</li>
                            <li><strong>3D Visualization:</strong> Visualisasi 3D untuk data kompleks</li>
                            <li><strong>Dashboard:</strong> Dashboard real-time dengan KPI metrics</li>
                            <li><strong>Custom Themes:</strong> Tema kustomisasi untuk branding</li>
                        </ul>
                        <p>Semua visualisasi responsif dan dapat di-embed ke website lain.</p>
                    `
                }
            };

            const selectedFeature = features[feature];
            title.textContent = selectedFeature.title;
            content.innerHTML = selectedFeature.content;
            modal.style.display = 'block';
        }

        // Close modal when clicking outside
        window.onclick = function(event) {
            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                if (event.target === modal) {
                    modal.style.display = 'none';
                }
            });
        }

        // Feature cards animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        document.querySelectorAll('.feature-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });

        // Create more floating particles dynamically
        function createParticles() {
            for (let i = 0; i < 15; i++) {
                const particle = document.createElement('div');
                particle.className = 'particle';
                particle.style.top = Math.random() * 100 + '%';
                particle.style.left = Math.random() * 100 + '%';
                particle.style.animationDelay = Math.random() * 6 + 's';
                particle.style.animationDuration = (Math.random() * 3 + 4) + 's';
                document.body.appendChild(particle);
            }
        }

        createParticles();

        // Button interaction effects
        document.querySelectorAll('.btn').forEach(btn => {
            btn.addEventListener('mouseover', function() {
                this.style.transform = 'translateY(-3px) scale(1.05)';
            });

            btn.addEventListener('mouseout', function() {
                this.style.transform = 'translateY(0) scale(1)';
            });
        });

        // Add parallax effect to hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const hero = document.querySelector('.hero-content');
            if (hero) {
                hero.style.transform = `translateY(${scrolled * 0.1}px)`;
            }
        });

        // Keyboard navigation for modals
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modals = document.querySelectorAll('.modal');
                modals.forEach(modal => {
                    if (modal.style.display === 'block') {
                        modal.style.display = 'none';
                    }
                });
            }
        });

        // Add loading animation for buttons
        function addLoadingState(button, duration = 2000) {
            const originalText = button.textContent;
            button.textContent = '⏳ Loading...';
            button.disabled = true;
            button.style.opacity = '0.7';

            setTimeout(() => {
                button.textContent = originalText;
                button.disabled = false;
                button.style.opacity = '1';
            }, duration);
        }

        // Enhanced button interactions
        document.querySelectorAll('.btn-primary').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!this.disabled) {
                    addLoadingState(this);
                }
            });
        });

        // Add ripple effect to cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.addEventListener('click', function(e) {
                const ripple = document.createElement('div');
                const rect = this.getBoundingClientRect();
                const size = Math.max(rect.width, rect.height);
                const x = e.clientX - rect.left - size / 2;
                const y = e.clientY - rect.top - size / 2;

                ripple.style.cssText = `
                    position: absolute;
                    width: ${size}px;
                    height: ${size}px;
                    left: ${x}px;
                    top: ${y}px;
                    background: rgba(255, 255, 255, 0.1);
                    border-radius: 50%;
                    transform: scale(0);
                    animation: ripple 0.6s ease-out;
                    pointer-events: none;
                `;

                this.appendChild(ripple);

                setTimeout(() => {
                    ripple.remove();
                }, 600);
            });
        });

        // Add ripple animation
        const style = document.createElement('style');
        style.textContent = `
            @keyframes ripple {
                to {
                    transform: scale(2);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);

        // Add tooltip functionality
        function createTooltip(element, text) {
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = text;
            tooltip.style.cssText = `
                position: absolute;
                background: rgba(0, 0, 0, 0.9);
                color: white;
                padding: 8px 12px;
                border-radius: 6px;
                font-size: 14px;
                white-space: nowrap;
                z-index: 1000;
                opacity: 0;
                transition: opacity 0.3s ease;
                pointer-events: none;
            `;

            element.addEventListener('mouseenter', (e) => {
                document.body.appendChild(tooltip);
                const rect = element.getBoundingClientRect();
                tooltip.style.left = rect.left + rect.width / 2 - tooltip.offsetWidth / 2 + 'px';
                tooltip.style.top = rect.top - tooltip.offsetHeight - 10 + 'px';
                tooltip.style.opacity = '1';
            });

            element.addEventListener('mouseleave', () => {
                tooltip.style.opacity = '0';
                setTimeout(() => {
                    if (tooltip.parentNode) {
                        tooltip.parentNode.removeChild(tooltip);
                    }
                }, 300);
            });
        }

        // Add tooltips to navigation links
        document.querySelectorAll('.nav-links a').forEach(link => {
            const tooltips = {
                'Beranda': 'Kembali ke halaman utama',
                'Fitur': 'Lihat fitur-fitur JAKTRACK',
                'Tentang': 'Informasi tentang pengembang',
                'Login': 'Masuk sebagai administrator',
                'Data': 'Lihat data stasiun lengkap'
            };

            const text = link.textContent.trim();
            if (tooltips[text]) {
                createTooltip(link, tooltips[text]);
            }
        });

        // Add smooth transitions for section changes
        const sections = document.querySelectorAll('section');
        const navLinks = document.querySelectorAll('.nav-links a[href^="#"]');

        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === `#${id}`) {
                            link.classList.add('active');
                        }
                    });
                }
            });
        }, {
            threshold: 0.6
        });

        sections.forEach(section => {
            sectionObserver.observe(section);
        });

        // Add active state styling
        const activeStyle = document.createElement('style');
        activeStyle.textContent = `
            .nav-links a.active {
                color: #4ecdc4 !important;
            }
            .nav-links a.active::after {
                width: 100% !important;
            }
        `;
        document.head.appendChild(activeStyle);

        console.log('🚀 JAKTRACK Landing Page loaded successfully!');
        console.log('✨ All interactive features are now functional');
    </script>
</body>

</html>
