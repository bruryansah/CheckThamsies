<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const activeMenu = ref('tentang');

function setActiveMenu(menu: string) {
    activeMenu.value = menu;
}

// Animation on scroll
onMounted(() => {
    const observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-in');
                }
            });
        },
        {
            threshold: 0.1,
        },
    );

    document.querySelectorAll('.animate-on-scroll').forEach((el) => {
        observer.observe(el);
    });

    // Navbar scroll effect
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }
});

// Data untuk halaman tentang
const features = ref([
    {
        icon: '📱',
        title: 'Teknologi Modern',
        description: 'Menggunakan teknologi web terdepan untuk memberikan pengalaman pengguna yang optimal dan responsif di semua perangkat.',
    },
    {
        icon: '⚡',
        title: 'Real-time Processing',
        description: 'Data absensi diproses secara real-time dengan sinkronisasi otomatis ke database sekolah untuk akurasi maksimal.',
    },
    {
        icon: '🔒',
        title: 'Keamanan Tinggi',
        description: 'Sistem keamanan berlapis dengan enkripsi data dan autentikasi multi-level untuk melindungi privasi siswa.',
    },
    {
        icon: '📊',
        title: 'Analytics & Reporting',
        description: 'Dashboard analitik komprehensif dengan laporan kehadiran yang detail dan mudah dipahami oleh semua stakeholder.',
    },
]);

const benefits = ref([
    {
        icon: '🎯',
        title: 'Efisiensi Maksimal',
        description: 'Mengurangi waktu pencatatan absensi dari 15 menit menjadi hanya 2 menit per kelas.',
        percentage: '85%',
    },
    {
        icon: '📈',
        title: 'Akurasi Data',
        description: 'Tingkat akurasi data mencapai 99.8% dengan sistem validasi berlapis.',
        percentage: '99.8%',
    },
    {
        icon: '🔔',
        title: 'Notifikasi Instan',
        description: 'Pemberitahuan real-time kepada orang tua tentang kehadiran siswa.',
        percentage: '100%',
    },
    {
        icon: '💾',
        title: 'Penyimpanan Aman',
        description: 'Data tersimpan aman dengan backup otomatis dan sistem recovery.',
        percentage: '100%',
    },
]);

const timeline = ref([
    {
        year: '2023',
        title: 'Perencanaan Sistem',
        description: 'Analisis kebutuhan dan desain arsitektur sistem absensi digital.',
        status: 'completed',
    },
    {
        year: '2024',
        title: 'Pengembangan & Testing',
        description: 'Fase pengembangan aplikasi dan pengujian menyeluruh di lingkungan sekolah.',
        status: 'completed',
    },
    {
        year: '2024',
        title: 'Implementasi Penuh',
        description: 'Peluncuran sistem secara menyeluruh di seluruh kelas dan mata pelajaran.',
        status: 'current',
    },
    {
        year: '2025',
        title: 'Ekspansi Fitur',
        description: 'Pengembangan fitur lanjutan seperti AI analytics dan integrasi sistem pembelajaran.',
        status: 'upcoming',
    },
]);

const stats = ref([
    { number: '1,247', label: 'Total Siswa', icon: '👥' },
    { number: '87', label: 'Guru Aktif', icon: '👨‍🏫' },
    { number: '36', label: 'Kelas', icon: '🏫' },
    { number: '45', label: 'Mata Pelajaran', icon: '📚' },
]);
</script>

<template>
    <div class="about-page">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-left">
                <img
                    src="https://smktamansiswa2jakarta.sch.id/wp-content/uploads/2022/03/LOGO-TAMSIS-MATERIAL-DESIGN.png"
                    alt="Logo SMK Tamansiswa 2 Jakarta"
                    class="logo"
                />
                <div class="brand">
                    <span class="brand-title">Sistem Absensi SMK Tamansiswa 2</span>
                    <span class="brand-subtitle">"Digital Attendance System"</span>
                </div>
            </div>

            <div class="nav-center">
                <ul class="nav-links">
                    <li>
                        <Link href="/" :class="{ active: activeMenu === 'beranda' }" @click="setActiveMenu('beranda')">
                            <span class="nav-icon">🏠</span> Beranda
                        </Link>
                    </li>
                    <li>
                        <Link href="/tentang" :class="{ active: activeMenu === 'tentang' }" @click="setActiveMenu('tentang')">
                            <span class="nav-icon">📝</span> Tentang
                        </Link>
                    </li>
                    <li>
                        <Link href="/kontak" :class="{ active: activeMenu === 'kontak' }" @click="setActiveMenu('kontak')">
                            <span class="nav-icon">📥</span> Kontak
                        </Link>
                    </li>
                </ul>
            </div>

            <div class="auth-buttons">
                <Link href="/login" class="btn-login"> <span class="auth-icon">🔑</span> Login </Link>
                <Link href="/register" class="btn-register"> <span class="auth-icon">📋</span> Register </Link>
            </div>
        </nav>

        <!-- Main Container -->
        <div class="main-container">
            <div class="breadcrumb animate-on-scroll"><span class="breadcrumb-icon">🏠</span> Beranda / Tentang Sistem</div>

            <h1 class="page-title animate-on-scroll">
                Tentang Sistem Absensi Digital
                <span class="title-subtitle">SMK Tamansiswa 2 Jakarta</span>
            </h1>

            <div class="content">
                <!-- Hero Section -->
                <div class="hero-section animate-on-scroll">
                    <div class="hero-content">
                        <div class="hero-text">
                            <h2>Revolusi Digital dalam Manajemen Kehadiran</h2>
                            <p>
                                Sistem Absensi Digital SMK Tamansiswa 2 Jakarta adalah solusi inovatif yang mengubah cara tradisional pencatatan
                                kehadiran menjadi proses yang efisien, akurat, dan real-time. Dikembangkan khusus untuk memenuhi kebutuhan sekolah
                                modern dengan teknologi terdepan.
                            </p>
                            <div class="hero-stats">
                                <div class="hero-stat" v-for="stat in stats" :key="stat.label">
                                    <span class="hero-stat-icon">{{ stat.icon }}</span>
                                    <span class="hero-stat-number">{{ stat.number }}</span>
                                    <span class="hero-stat-label">{{ stat.label }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="hero-image">
                            <div class="hero-illustration">
                                <div class="illustration-circle primary"></div>
                                <div class="illustration-circle secondary"></div>
                                <div class="illustration-circle tertiary"></div>
                                <div class="illustration-icon">📱</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vision Mission Section -->
                <div class="vision-mission-section animate-on-scroll">
                    <div class="section-header">
                        <h3 class="section-title">
                            <span class="section-icon">🎯</span>
                            Visi & Misi
                        </h3>
                    </div>
                    <div class="vision-mission-grid">
                        <div class="vision-card">
                            <div class="vm-icon">🌟</div>
                            <h4>Visi</h4>
                            <p>
                                Menjadi sistem absensi digital terdepan yang mendukung transformasi pendidikan menuju era digital dengan teknologi
                                yang mudah digunakan, akurat, dan terpercaya untuk menciptakan lingkungan belajar yang lebih efisien dan modern.
                            </p>
                        </div>
                        <div class="mission-card">
                            <div class="vm-icon">🚀</div>
                            <h4>Misi</h4>
                            <ul>
                                <li>Menyediakan sistem absensi yang user-friendly dan reliable</li>
                                <li>Meningkatkan efisiensi pengelolaan data kehadiran siswa</li>
                                <li>Memberikan transparansi informasi kepada orang tua</li>
                                <li>Mendukung pengambilan keputusan berbasis data</li>
                                <li>Mengoptimalkan proses administratif sekolah</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="contact-section animate-on-scroll">
                    <div class="contact-card">
                        <div class="contact-header">
                            <h3>Butuh Bantuan atau Informasi Lebih Lanjut?</h3>
                            <p>Tim support kami siap membantu Anda 24/7</p>
                        </div>
                        <div class="contact-actions">
                            <Link href="/kontak" class="contact-btn primary">
                                <span class="contact-icon">📞</span>
                                Hubungi Kami
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-left">
                    <img
                        src="https://smktamansiswa2jakarta.sch.id/wp-content/uploads/2022/03/LOGO-TAMSIS-MATERIAL-DESIGN.png"
                        alt="Logo SMK Tamansiswa 2 Jakarta"
                        class="footer-logo"
                    />
                    <span class="footer-text">© 2024 SMK TAMANSISWA 2 JAKARTA | RPL</span>
                </div>
                <div class="footer-right">
                    <a href="https://facebook.com/smktamansiswa2jakarta" class="social-link" target="_blank" title="Facebook">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="social-icon"
                        >
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
                        </svg>
                    </a>
                    <a href="https://instagram.com/smktamansiswa2jakarta" class="social-link" target="_blank" title="Instagram">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="social-icon"
                        >
                            <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
                            <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
                            <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
                        </svg>
                    </a>
                    <a href="https://youtube.com/@smktamansiswa2jakarta375?si=XuMQeADTidosYWLq" class="social-link" target="_blank" title="YouTube">
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            width="24"
                            height="24"
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            class="social-icon"
                        >
                            <path
                                d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"
                            ></path>
                            <polygon points="9.75 15.02 15.5 11.75 9.75 8.48 9.75 15.02"></polygon>
                        </svg>
                    </a>
                </div>
            </div>
        </footer>
    </div>
</template>

<style scoped>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.about-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    display: flex;
    flex-direction: column;
}

/* ====== Animations (inherit from main page) ====== */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(30px);
    transition:
        opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1),
        transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-on-scroll.animate-in {
    opacity: 1;
    transform: translateY(0);
}

.animate-on-scroll:nth-child(1) {
    transition-delay: 0.1s;
}
.animate-on-scroll:nth-child(2) {
    transition-delay: 0.2s;
}
.animate-on-scroll:nth-child(3) {
    transition-delay: 0.3s;
}
.animate-on-scroll:nth-child(4) {
    transition-delay: 0.4s;
}
.animate-on-scroll:nth-child(5) {
    transition-delay: 0.5s;
}

/* ====== Navbar (same as main page) ====== */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(20px);
    padding: 16px 24px;
    box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 100;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.navbar.scrolled {
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.98);
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.15);
}

.navbar-left {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-shrink: 0;
}

.logo {
    width: 48px;
    height: 48px;
    object-fit: contain;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-radius: 8px;
    padding: 4px;
    background: linear-gradient(135deg, #f0f9ff, #dbeafe);
}

.logo:hover {
    transform: scale(1.1) rotate(5deg);
}

.brand {
    display: flex;
    flex-direction: column;
}

.brand-title {
    font-weight: 700;
    font-size: 18px;
    color: #1e293b;
    line-height: 1.2;
}

.brand-subtitle {
    font-size: 12px;
    color: #2563eb;
    font-weight: 500;
    margin-top: 2px;
}

.nav-center {
    flex: 1;
    display: flex;
    justify-content: center;
}

.nav-links {
    display: flex;
    list-style: none;
    gap: 32px;
    margin: 0;
    padding: 0;
}

.nav-links li a {
    position: relative;
    color: #64748b;
    font-size: 15px;
    font-weight: 500;
    text-decoration: none;
    padding: 10px 16px;
    border-radius: 10px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 8px;
}

.nav-icon {
    font-size: 16px;
    filter: grayscale(1);
    transition: filter 0.3s ease;
}

.nav-links li a:hover {
    color: #2563eb;
    background: rgba(37, 99, 235, 0.1);
    transform: translateY(-2px);
}

.nav-links li a:hover .nav-icon {
    filter: grayscale(0);
}

.nav-links li a.active {
    color: #2563eb;
    font-weight: 600;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(59, 130, 246, 0.1));
    box-shadow: 0 2px 10px rgba(37, 99, 235, 0.2);
}

.nav-links li a.active .nav-icon {
    filter: grayscale(0);
}

.auth-buttons {
    display: flex;
    gap: 12px;
    flex-shrink: 0;
}

.btn-login,
.btn-register {
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 6px;
}

.auth-icon {
    font-size: 14px;
}

.btn-login {
    border: 2px solid #2563eb;
    background: transparent;
    color: #2563eb;
}

.btn-login:hover {
    background-color: #2563eb;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
}

.btn-register {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
    border: 2px solid transparent;
    box-shadow: 0 2px 8px rgba(37, 99, 235, 0.3);
}

.btn-register:hover {
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
}

/* ====== Main Container ====== */
.main-container {
    flex: 1;
    max-width: 1400px;
    margin: 0 auto;
    width: 100%;
    padding: 0 24px;
}

.breadcrumb {
    font-size: 14px;
    margin: 32px 0 16px 0;
    color: #64748b;
    font-weight: 500;
    display: flex;
    align-items: center;
    gap: 8px;
}

.breadcrumb-icon {
    font-size: 16px;
}

.page-title {
    margin: 0 0 32px 0;
    font-size: 2.5rem;
    font-weight: 800;
    color: #1e293b;
    position: relative;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.title-subtitle {
    font-size: 1.2rem;
    color: #64748b;
    font-weight: 500;
}

.page-title::after {
    content: '';
    position: absolute;
    bottom: -16px;
    left: 0;
    width: 80px;
    height: 5px;
    background: linear-gradient(90deg, #2563eb, #3b82f6);
    border-radius: 3px;
}

/* ====== Content ====== */
.content {
    display: flex;
    flex-direction: column;
    gap: 48px;
}

/* ====== Hero Section ====== */
.hero-section {
    background: white;
    border-radius: 20px;
    padding: 48px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    position: relative;
    overflow: hidden;
}

.hero-content {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 48px;
    align-items: center;
}

.hero-text h2 {
    font-size: 2.2rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 20px;
    line-height: 1.2;
}

.hero-text p {
    font-size: 1.1rem;
    color: #64748b;
    line-height: 1.7;
    margin-bottom: 32px;
}

.hero-stats {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
}

.hero-stat {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
    text-align: center;
}

.hero-stat-icon {
    font-size: 2rem;
    margin-bottom: 8px;
}

.hero-stat-number {
    font-size: 1.5rem;
    font-weight: 800;
    color: #2563eb;
    line-height: 1;
}

.hero-stat-label {
    font-size: 0.9rem;
    color: #64748b;
    font-weight: 500;
    margin-top: 4px;
}

.hero-image {
    display: flex;
    justify-content: center;
    align-items: center;
}

.hero-illustration {
    position: relative;
    width: 200px;
    height: 200px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.illustration-circle {
    position: absolute;
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
}

.illustration-circle.primary {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(59, 130, 246, 0.1));
    animation-delay: 0s;
}

.illustration-circle.secondary {
    width: 150px;
    height: 150px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.2), rgba(59, 130, 246, 0.2));
    animation-delay: -2s;
}

.illustration-circle.tertiary {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.3), rgba(59, 130, 246, 0.3));
    animation-delay: -4s;
}

.illustration-icon {
    font-size: 4rem;
    z-index: 10;
    position: relative;
    animation: pulse 3s ease-in-out infinite;
}

@keyframes float {
    0%,
    100% {
        transform: translateY(0px) rotate(0deg);
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
    }
}

@keyframes pulse {
    0%,
    100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.1);
    }
}

/* ====== Section Headers ====== */
.section-header {
    text-align: center;
    margin-bottom: 32px;
}

.section-title {
    font-size: 1.8rem;
    font-weight: 800;
    color: #1e293b;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
}

.section-icon {
    font-size: 2rem;
}

.section-subtitle {
    font-size: 1rem;
    color: #64748b;
    font-weight: 500;
}

/* ====== Vision Mission Section ====== */
.vision-mission-section {
    margin: 48px 0;
}

.vision-mission-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 32px;
}

.vision-card,
.mission-card {
    background: white;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-align: center;
}

.vision-card:hover,
.mission-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.vm-icon {
    font-size: 3rem;
    margin-bottom: 16px;
    display: block;
}

.vision-card h4,
.mission-card h4 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2563eb;
    margin-bottom: 16px;
}

.vision-card p {
    color: #64748b;
    line-height: 1.7;
    font-size: 1rem;
}

.mission-card ul {
    list-style: none;
    padding: 0;
    text-align: left;
}

.mission-card li {
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 12px;
    position: relative;
    padding-left: 24px;
}

.mission-card li:before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #10b981;
    font-weight: bold;
}

/* ====== Features Section ====== */
.features-section {
    margin: 48px 0;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 24px;
}

.feature-card {
    background: white;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.feature-card:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #2563eb, #3b82f6);
    transform: scaleX(0);
    transition: transform 0.3s ease;
}

.feature-card:hover:before {
    transform: scaleX(1);
}

.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.feature-icon {
    font-size: 3.5rem;
    margin-bottom: 20px;
    display: block;
}

.feature-card h4 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 16px;
}

.feature-card p {
    color: #64748b;
    line-height: 1.6;
    font-size: 0.95rem;
}

/* ====== Benefits Section ====== */
.benefits-section {
    margin: 48px 0;
}

.benefits-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.benefit-card {
    background: white;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.benefit-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.benefit-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 16px;
}

.benefit-icon {
    font-size: 2.5rem;
}

.benefit-percentage {
    font-size: 1.5rem;
    font-weight: 800;
    color: #2563eb;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(59, 130, 246, 0.1));
    padding: 8px 16px;
    border-radius: 20px;
}

.benefit-card h4 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 12px;
}

.benefit-card p {
    color: #64748b;
    line-height: 1.6;
    font-size: 0.95rem;
    margin-bottom: 16px;
}

.benefit-progress {
    height: 6px;
    background: #e2e8f0;
    border-radius: 3px;
    overflow: hidden;
}

.progress-bar {
    height: 100%;
    background: linear-gradient(90deg, #2563eb, #3b82f6);
    border-radius: 3px;
    transition: width 2s ease-in-out;
}

/* ====== Timeline Section ====== */
.timeline-section {
    margin: 48px 0;
}

.timeline-container {
    position: relative;
    max-width: 800px;
    margin: 0 auto;
}

.timeline-line {
    position: absolute;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 100%;
    background: linear-gradient(to bottom, #2563eb, #3b82f6);
    border-radius: 2px;
}

.timeline-items {
    display: flex;
    flex-direction: column;
    gap: 40px;
}

.timeline-item {
    position: relative;
    display: flex;
    align-items: center;
    gap: 24px;
}

.timeline-item:nth-child(even) {
    flex-direction: row-reverse;
}

.timeline-dot {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    font-weight: bold;
    color: white;
    z-index: 2;
    position: relative;
}

.timeline-item.completed .timeline-dot {
    background: linear-gradient(135deg, #10b981, #059669);
}

.timeline-item.current .timeline-dot {
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    animation: pulse-timeline 2s infinite;
}

.timeline-item.upcoming .timeline-dot {
    background: linear-gradient(135deg, #64748b, #475569);
}

@keyframes pulse-timeline {
    0%,
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.4);
    }
    50% {
        transform: scale(1.1);
        box-shadow: 0 0 0 10px rgba(37, 99, 235, 0);
    }
}

.timeline-content {
    flex: 1;
    background: white;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
    transition: all 0.3s ease;
}

.timeline-content:hover {
    transform: translateY(-4px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.timeline-year {
    font-size: 1.1rem;
    font-weight: 800;
    color: #2563eb;
    margin-bottom: 8px;
}

.timeline-content h4 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

.timeline-content p {
    color: #64748b;
    line-height: 1.6;
    font-size: 0.95rem;
}

/* ====== Technology Section ====== */
.tech-section {
    margin: 48px 0;
}

.tech-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.tech-category {
    background: white;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.tech-category:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.tech-category h4 {
    font-size: 1.3rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 20px;
    text-align: center;
    position: relative;
}

.tech-category h4:after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    transform: translateX(-50%);
    width: 40px;
    height: 3px;
    background: linear-gradient(90deg, #2563eb, #3b82f6);
    border-radius: 2px;
}

.tech-items {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 12px;
}

.tech-item {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 12px;
    text-align: center;
    font-size: 0.9rem;
    font-weight: 600;
    color: #475569;
    transition: all 0.3s ease;
}

.tech-item:hover {
    background: #2563eb;
    color: white;
    border-color: #2563eb;
    transform: translateY(-2px);
}

/* ====== Contact Section ====== */
.contact-section {
    margin: 48px 0;
}

.contact-card {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    border-radius: 20px;
    padding: 48px;
    color: white;
    text-align: center;
    position: relative;
    overflow: hidden;
}

.contact-card:before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="1.5" fill="white" opacity="0.1"/><circle cx="40" cy="80" r="1" fill="white" opacity="0.1"/></svg>');
}

.contact-header {
    position: relative;
    z-index: 2;
    margin-bottom: 32px;
}

.contact-header h3 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 12px;
}

.contact-header p {
    font-size: 1.1rem;
    opacity: 0.9;
}

.contact-actions {
    position: relative;
    z-index: 2;
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}

.contact-btn {
    padding: 14px 28px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    min-width: 160px;
    justify-content: center;
}

.contact-btn.primary {
    background: white;
    color: #2563eb;
}

.contact-btn.primary:hover {
    background: #f8fafc;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(255, 255, 255, 0.3);
}

.contact-btn.secondary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.contact-btn.secondary:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.contact-icon {
    font-size: 18px;
}

/* ====== Footer (same as main page) ====== */
.footer {
    margin-top: 80px;
    background: linear-gradient(135deg, #1e293b, #334155);
    color: white;
}

.footer-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 32px 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.footer-left {
    display: flex;
    align-items: center;
    gap: 16px;
    max-width: 70%;
}

.footer-logo {
    width: 40px;
    height: 40px;
    object-fit: contain;
    flex-shrink: 0;
    filter: brightness(0) invert(1);
}

.footer-text {
    font-size: 14px;
    color: #e2e8f0;
    line-height: 1.6;
}

.footer-right {
    display: flex;
    gap: 16px;
}

.social-link {
    color: #cbd5e1;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    width: 44px;
    height: 44px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
}

.social-link:hover {
    color: white;
    background: #2563eb;
    transform: translateY(-3px);
    box-shadow: 0 10px 25px rgba(37, 99, 235, 0.3);
}

.social-icon {
    width: 20px;
    height: 20px;
}

/* ====== Responsive Design ====== */
@media (max-width: 1024px) {
    .hero-content {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 32px;
    }

    .vision-mission-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .hero-stats {
        grid-template-columns: repeat(4, 1fr);
    }

    .timeline-line {
        left: 25px;
        transform: none;
    }

    .timeline-item {
        flex-direction: row;
        padding-left: 60px;
    }

    .timeline-item:nth-child(even) {
        flex-direction: row;
    }

    .timeline-dot {
        position: absolute;
        left: 0;
    }
}

@media (max-width: 768px) {
    .navbar {
        flex-wrap: wrap;
        padding: 16px;
    }

    .nav-center {
        order: 3;
        width: 100%;
        margin-top: 16px;
        justify-content: flex-start;
    }

    .nav-links {
        gap: 12px;
        flex-wrap: wrap;
    }

    .nav-links li a {
        padding: 8px 12px;
        font-size: 13px;
    }

    .nav-icon {
        font-size: 14px;
    }

    .auth-buttons {
        gap: 8px;
    }

    .btn-login,
    .btn-register {
        padding: 8px 12px;
        font-size: 12px;
    }

    .main-container {
        padding: 0 16px;
    }

    .page-title {
        font-size: 2rem;
    }

    .title-subtitle {
        font-size: 1rem;
    }

    .hero-section {
        padding: 32px 20px;
    }

    .hero-text h2 {
        font-size: 1.8rem;
    }

    .hero-text p {
        font-size: 1rem;
    }

    .hero-stats {
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    .section-title {
        font-size: 1.5rem;
        flex-direction: column;
        gap: 8px;
    }

    .features-grid,
    .benefits-grid,
    .tech-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .vision-card,
    .mission-card,
    .feature-card,
    .benefit-card,
    .tech-category {
        padding: 20px;
    }

    .contact-card {
        padding: 32px 20px;
    }

    .contact-header h3 {
        font-size: 1.5rem;
    }

    .contact-actions {
        flex-direction: column;
        align-items: center;
    }

    .contact-btn {
        width: 100%;
        max-width: 280px;
    }

    .timeline-container {
        padding-left: 20px;
    }

    .timeline-item {
        padding-left: 40px;
    }

    .footer-content {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }

    .footer-left {
        flex-direction: column;
        gap: 12px;
    }

    .footer-links {
        gap: 16px;
    }
}

@media (max-width: 480px) {
    .navbar {
        padding: 12px;
    }

    .brand-title {
        font-size: 16px;
    }

    .brand-subtitle {
        font-size: 11px;
    }

    .nav-links li a {
        padding: 6px 8px;
        font-size: 12px;
    }

    .auth-buttons {
        flex-direction: column;
        width: 100%;
        gap: 6px;
    }

    .btn-login,
    .btn-register {
        width: 100%;
        justify-content: center;
    }

    .hero-section {
        padding: 24px 16px;
    }

    .hero-text h2 {
        font-size: 1.5rem;
    }

    .hero-text p {
        font-size: 0.9rem;
    }

    .page-title {
        font-size: 1.75rem;
    }

    .title-subtitle {
        font-size: 0.9rem;
    }

    .section-title {
        font-size: 1.3rem;
    }

    .hero-stats {
        grid-template-columns: 1fr;
        gap: 12px;
    }

    .hero-stat {
        padding: 16px;
    }

    .vision-card,
    .mission-card,
    .feature-card,
    .benefit-card,
    .tech-category,
    .timeline-content {
        padding: 16px;
    }

    .tech-items {
        grid-template-columns: 1fr;
    }

    .contact-card {
        padding: 24px 16px;
    }

    .contact-header h3 {
        font-size: 1.3rem;
    }

    .contact-header p {
        font-size: 1rem;
    }
}

/* ====== Accessibility ====== */
.contact-btn:focus,
.nav-links li a:focus {
    outline: 2px solid #2563eb;
    outline-offset: 2px;
}

/* ====== Smooth scrolling ====== */
html {
    scroll-behavior: smooth;
}

/* ====== Custom scrollbar ====== */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f5f9;
}

::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>
