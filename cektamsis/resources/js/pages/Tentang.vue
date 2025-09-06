<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
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

// Data sekolah SMK Tamansiswa 2 Jakarta
const schoolInfo = ref({
    name: 'SMK Tamansiswa 2 Jakarta',
    established: '1981',
    accreditation: 'A (Unggul)',
    address: 'Jl. Taman Siswa No. 18, Kramat, Jakarta Pusat 10450',
    phone: '(021) 3190747',
    email: 'smktamansiswa2@jakarta.sch.id',
    website: 'https://smktamansiswa2jakarta.sch.id',
});

// Jurusan yang tersedia - Updated with correct majors
const majors = ref([
    {
        code: 'RPL',
        name: 'Rekayasa Perangkat Lunak',
        icon: '💻',
        description: 'Jurusan yang mempelajari pengembangan aplikasi, web development, dan sistem informasi modern.',
        prospects: ['Software Developer', 'Web Developer', 'System Analyst', 'Mobile App Developer', 'Database Administrator'],
    },
    {
        code: 'TKJ',
        name: 'Teknik Komputer dan Jaringan',
        icon: '🔧',
        description: 'Jurusan yang fokus pada jaringan komputer, hardware, infrastruktur IT, dan troubleshooting sistem.',
        prospects: ['Network Administrator', 'IT Support', 'System Administrator', 'Network Engineer', 'Cybersecurity Specialist'],
    },
    {
        code: 'TP',
        name: 'Teknik Pemesinan',
        icon: '⚙️',
        description: 'Jurusan yang mempelajari pengoperasian mesin produksi, CNC, dan teknologi manufaktur modern.',
        prospects: ['Machine Operator', 'CNC Programmer', 'Production Supervisor', 'Quality Control Inspector', 'Manufacturing Engineer'],
    },
    {
        code: 'TITL',
        name: 'Teknik Instalasi Tenaga Listrik',
        icon: '⚡',
        description: 'Jurusan yang fokus pada instalasi listrik, sistem tenaga, dan teknologi kelistrikan.',
        prospects: ['Electrical Technician', 'Power Plant Operator', 'Electrical Installer', 'Maintenance Engineer', 'Electrical Inspector'],
    },
    {
        code: 'TKR',
        name: 'Teknik Kendaraan Ringan',
        icon: '🚗',
        description: 'Jurusan yang mempelajari perawatan, perbaikan, dan teknologi kendaraan ringan modern.',
        prospects: ['Automotive Technician', 'Service Advisor', 'Parts Specialist', 'Automotive Inspector', 'Workshop Supervisor'],
    },
    {
        code: 'TSM',
        name: 'Teknik dan Bisnis Sepeda Motor',
        icon: '🏍️',
        description: 'Jurusan yang mengkombinasikan teknik sepeda motor dengan aspek bisnis dan entrepreneurship.',
        prospects: ['Motorcycle Technician', 'Service Manager', 'Spare Parts Sales', 'Motorcycle Business Owner', 'Technical Consultant'],
    },
]);

// Statistik sekolah
const stats = ref([
    { number: '1,247', label: 'Total Siswa', icon: '👥' },
    { number: '87', label: 'Guru Aktif', icon: '👨‍🏫' },
    { number: '36', label: 'Kelas', icon: '🏫' },
    { number: '48', label: 'Mata Pelajaran', icon: '📚' },
]);
</script>

<template>
    <Head title="Tentang SMK Tamansiswa 2 Jakarta" />
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
                </div>
            </div>

            <div class="nav-center">
                <ul class="nav-links">
                    <li>
                        <Link href="/" :class="{ active: activeMenu === 'beranda' }" @click="setActiveMenu('beranda')">
                            <span class="nav-icon">🏠</span> <span class="nav-text">Beranda</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/tentang" :class="{ active: activeMenu === 'tentang' }" @click="setActiveMenu('tentang')">
                            <span class="nav-icon">📝</span> <span class="nav-text">Tentang</span>
                        </Link>
                    </li>
                    <li>
                        <Link href="/kontak" :class="{ active: activeMenu === 'kontak' }" @click="setActiveMenu('kontak')">
                            <span class="nav-icon">📥</span> <span class="nav-text">Kontak</span>
                        </Link>
                    </li>
                </ul>
            </div>

            <div class="auth-buttons">
                <Link href="/login" class="btn-login"> 
                    <span class="auth-icon">🔑</span> 
                    <span class="auth-text">Login</span>
                </Link>
            </div>
        </nav>

        <!-- Main Container -->
        <div class="main-container">
            <div class="breadcrumb animate-on-scroll">
                <span class="breadcrumb-icon">🏠</span>
                Beranda / Tentang
            </div>

            <h1 class="page-title animate-on-scroll">
                Tentang
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

                <!-- School Info Section -->
                <div class="school-info-section animate-on-scroll">
                    <div class="section-header">
                        <h3 class="section-title">
                            <span class="section-icon">🏫</span>
                            Informasi Sekolah
                        </h3>
                    </div>
                    <div class="school-info-grid">
                        <div class="info-card">
                            <div class="info-icon">📅</div>
                            <h4>Tahun Berdiri</h4>
                            <p>{{ schoolInfo.established }}</p>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">🏆</div>
                            <h4>Akreditasi</h4>
                            <p>{{ schoolInfo.accreditation }}</p>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">📍</div>
                            <h4>Alamat</h4>
                            <p>{{ schoolInfo.address }}</p>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">📞</div>
                            <h4>Telepon</h4>
                            <p>{{ schoolInfo.phone }}</p>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">📧</div>
                            <h4>Email</h4>
                            <p>{{ schoolInfo.email }}</p>
                        </div>
                        <div class="info-card">
                            <div class="info-icon">🌐</div>
                            <h4>Website</h4>
                            <p><a :href="schoolInfo.website" target="_blank">smktamansiswa2jakarta.sch.id</a></p>
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

                <!-- Majors Section -->
                <div class="majors-section animate-on-scroll">
                    <div class="section-header">
                        <h3 class="section-title">
                            <span class="section-icon">📚</span>
                            Program Keahlian
                        </h3>
                        <p class="section-subtitle">6 Jurusan unggulan</p>
                    </div>
                    <div class="majors-grid">
                        <div class="major-card" v-for="major in majors" :key="major.code">
                            <div class="major-header">
                                <span class="major-icon">{{ major.icon }}</span>
                                <div class="major-info">
                                    <h4>{{ major.name }}</h4>
                                    <span class="major-code">({{ major.code }})</span>
                                </div>
                            </div>
                            <p class="major-description">{{ major.description }}</p>
                            <div class="major-prospects">
                                
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Section -->
                <div class="contact-section animate-on-scroll">
                    <div class="contact-card">
                        <div class="contact-header">
                            <h3>Butuh Bantuan atau Informasi Lebih Lanjut?</h3>
                            <p>Tim support kami siap membantu Anda</p>
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
                    <div class="footer-info">
                        <span class="footer-text">
                            <a href="https://smktamansiswa2jakarta.sch.id" target="_blank"> © 2025 SMK Tamansiswa 2 Jakarta </a>
                        </span>
                    </div>
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
    font-family: 'Inter', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

.about-page {
    min-height: 100vh;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    display: flex;
    flex-direction: column;
}

/* ====== Animations ====== */
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

/* ====== Navbar ====== */
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

.btn-login {
    padding: 10px 20px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 10px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 6px;
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

.auth-icon {
    font-size: 14px;
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
    margin-bottom: 48px;
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
    transition: all 0.3s ease;
}

.hero-stat:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
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
    color: #1e293b;
    font-weight: 600;
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

/* ====== School Info Section ====== */
.school-info-section {
    margin: 48px 0;
}

.school-info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 24px;
}

.info-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-align: center;
}

.info-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.info-icon {
    font-size: 2.5rem;
    margin-bottom: 16px;
    display: block;
}

.info-card h4 {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

.info-card p {
    color: #64748b;
    line-height: 1.6;
    font-size: 0.9rem;
}

.info-card a {
    color: #2563eb;
    text-decoration: none;
    font-weight: 500;
}

.info-card a:hover {
    text-decoration: underline;
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

/* ====== Majors Section ====== */
.majors-section {
    margin: 48px 0;
}

.majors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 24px;
}

.major-card {
    background: white;
    border-radius: 16px;
    padding: 28px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.major-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.major-header {
    display: flex;
    align-items: center;
    gap: 16px;
    margin-bottom: 16px;
}

.major-icon {
    font-size: 2.5rem;
}

.major-info h4 {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 4px;
}

.major-code {
    font-size: 0.9rem;
    color: #2563eb;
    font-weight: 600;
}

.major-description {
    color: #64748b;
    line-height: 1.6;
    font-size: 0.95rem;
    margin-bottom: 20px;
}

.major-prospects h5 {
    font-size: 1rem;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 12px;
}

.prospect-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
}

.prospect-tag {
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(59, 130, 246, 0.1));
    color: #2563eb;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    border: 1px solid rgba(37, 99, 235, 0.2);
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

.contact-icon {
    font-size: 18px;
}

/* ====== Footer ====== */
.footer {
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
}

.footer-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.footer-text {
    font-size: 14px;
    color: #e2e8f0;
    line-height: 1.6;
}

.footer-text a {
    color: #e2e8f0;
    text-decoration: none;
    font-weight: 500;
}

.footer-text a:hover {
    color: white;
    text-decoration: underline;
}

.footer-subtitle {
    font-size: 12px;
    color: #94a3b8;
    font-weight: 400;
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
@media (max-width: 1200px) {
    .main-container {
        padding: 0 20px;
    }
    
    .navbar {
        padding: 16px 20px;
    }
    
    .hero-content {
        grid-template-columns: 1fr;
        text-align: center;
        gap: 32px;
    }
    
    .hero-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 1024px) {
    .vision-mission-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    
    .nav-links {
        gap: 20px;
    }
    
    .majors-grid {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    }
    
    .school-info-grid {
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    }
}

@media (max-width: 768px) {
    .navbar {
        flex-direction: column;
        gap: 16px;
        padding: 16px 20px;
    }
    
    .navbar-left {
        width: 100%;
        justify-content: space-between;
    }
    
    .nav-center {
        width: 100%;
        justify-content: flex-start;
    }
    
    .nav-links {
        gap: 16px;
        flex-wrap: wrap;
        width: 100%;
    }
    
    .nav-links li a {
        padding: 8px 12px;
        font-size: 13px;
    }
    
    .nav-text {
        display: none;
    }
    
    .auth-buttons {
        position: absolute;
        top: 16px;
        right: 20px;
    }
    
    .btn-login {
        padding: 8px 12px;
        font-size: 12px;
    }
    
    .auth-text {
        display: none;
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
    
    .majors-grid,
    .school-info-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .vision-card,
    .mission-card,
    .major-card,
    .info-card {
        padding: 20px;
    }
    
    .contact-card {
        padding: 32px 20px;
    }
    
    .contact-header h3 {
        font-size: 1.5rem;
    }
    
    .footer-content {
        flex-direction: column;
        gap: 20px;
        text-align: center;
    }
    
    .footer-left {
        flex-direction: column;
        gap: 12px;
        max-width: 100%;
    }
    
    .prospect-tags {
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .navbar {
        padding: 12px 16px;
    }
    
    .brand-title {
        font-size: 16px;
    }
    
    .nav-links li a {
        padding: 8px;
        font-size: 12px;
    }
    
    .btn-login {
        padding: 8px;
        min-width: auto;
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
    .major-card,
    .info-card {
        padding: 16px;
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
    
    .major-header {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }
    
    .major-icon {
        font-size: 2rem;
    }
}

@media (max-width: 360px) {
    .main-container {
        padding: 0 12px;
    }
    
    .navbar {
        padding: 12px;
    }
    
    .hero-section {
        padding: 20px 12px;
    }
    
    .contact-card {
        padding: 20px 12px;
    }
    
    .vision-card,
    .mission-card,
    .major-card,
    .info-card {
        padding: 12px;
    }
    
    .nav-links {
        gap: 8px;
    }
    
    .nav-links li a {
        padding: 6px;
    }
}

/* ====== Accessibility ====== */
.contact-btn:focus,
.nav-links li a:focus,
.social-link:focus {
    outline: 2px solid #2563eb;
    outline-offset: 2px;
}

@media (prefers-reduced-motion: reduce) {
    .animate-on-scroll {
        transition: none;
    }
    
    .illustration-circle {
        animation: none;
    }
    
    .illustration-icon {
        animation: none;
    }
}

/* ====== Print styles ====== */
@media print {
    .navbar,
    .footer,
    .contact-section {
        display: none;
    }
    
    .about-page {
        background: white;
    }
    
    .hero-section,
    .major-card,
    .info-card,
    .vision-card,
    .mission-card {
        box-shadow: none;
        border: 1px solid #e2e8f0;
    }
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