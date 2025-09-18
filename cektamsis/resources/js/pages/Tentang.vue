<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { computed, onMounted, ref } from 'vue';

const activeMenu = ref('tentang');
const isMobileMenuOpen = ref(false);

interface Props {
    totalSiswa: number;
    totalGuru: number;
    totalKelas: number;
    totalJurusan: number;
}

const props = defineProps<Props>();

// Fixed: Direct access to props values
const stats = computed(() => ({
    totalSiswa: props.totalSiswa || 0,
    totalGuru: props.totalGuru || 0,
    totalKelas: props.totalKelas || 0,
    totalJurusan: props.totalJurusan || 0,
}));

function setActiveMenu(menu: string) {
    activeMenu.value = menu;
    isMobileMenuOpen.value = false;
}

function toggleMobileMenu() {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
}

// Animation on scroll with navbar hide/show functionality
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

    // Navbar scroll effect with show/hide functionality
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        let lastScrollY = window.scrollY;
        let ticking = false;

        const updateNavbar = () => {
            const scrollY = window.scrollY;

            if (scrollY < 10) {
                // Always show navbar at top
                navbar.classList.remove('navbar-hidden');
                navbar.classList.remove('scrolled');
            } else if (scrollY < 80) {
                // Show navbar but add scrolled effect
                navbar.classList.remove('navbar-hidden');
                navbar.classList.add('scrolled');
            } else if (scrollY > lastScrollY && scrollY > 100) {
                // Scrolling down - hide navbar
                navbar.classList.add('navbar-hidden');
                navbar.classList.add('scrolled');
            } else if (scrollY < lastScrollY) {
                // Scrolling up - show navbar
                navbar.classList.remove('navbar-hidden');
                navbar.classList.add('scrolled');
            }

            lastScrollY = scrollY;
            ticking = false;
        };

        const onScroll = () => {
            if (!ticking) {
                requestAnimationFrame(updateNavbar);
                ticking = true;
            }
        };

        window.addEventListener('scroll', onScroll, { passive: true });
    }
});

// School data
const schoolInfo = ref({
    name: 'SMK Tamansiswa 2 Jakarta',
    established: '1981',
    accreditation: 'A (Unggul)',
    address: 'Jl. Taman Siswa No. 18, Kramat, Jakarta Pusat 10450',
    phone: '(021) 3190747',
    email: 'smktamansiswa2@jakarta.sch.id',
    website: 'https://smktamansiswa2jakarta.sch.id',
});

const majors = ref([
    {
        code: 'RPL',
        name: 'Rekayasa Perangkat Lunak',
        icon: '💻',
        description: 'Jurusan yang mempelajari pengembangan aplikasi, web development, dan sistem informasi modern.',
        color: 'from-blue-500 to-cyan-500',
        skills: ['Programming', 'Database', 'Web Development', 'Mobile Apps'],
    },
    {
        code: 'TKJ',
        name: 'Teknik Komputer dan Jaringan',
        icon: '🌐',
        description: 'Jurusan yang fokus pada jaringan komputer, hardware, infrastruktur IT, dan troubleshooting sistem.',
        color: 'from-green-500 to-emerald-500',
        skills: ['Network Administration', 'Hardware', 'System Security', 'IT Support'],
    },
    {
        code: 'TP',
        name: 'Teknik Pemesinan',
        icon: '⚙️',
        description: 'Jurusan yang mempelajari pengoperasian mesin produksi, CNC, dan teknologi manufaktur modern.',
        color: 'from-purple-500 to-pink-500',
        skills: ['CNC Operation', 'Manufacturing', 'Quality Control', 'CAD/CAM'],
    },
    {
        code: 'TITL',
        name: 'Teknik Instalasi Tenaga Listrik',
        icon: '⚡',
        description: 'Jurusan yang fokus pada instalasi listrik, sistem tenaga, dan teknologi kelistrikan.',
        color: 'from-yellow-500 to-orange-500',
        skills: ['Electrical Installation', 'Power Systems', 'PLC', 'Industrial Automation'],
    },
    {
        code: 'TKR',
        name: 'Teknik Kendaraan Ringan',
        icon: '🚗',
        description: 'Jurusan yang mempelajari perawatan, perbaikan, dan teknologi kendaraan ringan modern.',
        color: 'from-red-500 to-pink-500',
        skills: ['Engine Repair', 'Electrical Systems', 'Diagnostics', 'Maintenance'],
    },
    {
        code: 'TSM',
        name: 'Teknik dan Bisnis Sepeda Motor',
        icon: '🏍️',
        description: 'Jurusan yang mengkombinasikan teknik sepeda motor dengan aspek bisnis dan entrepreneurship.',
        color: 'from-indigo-500 to-purple-500',
        skills: ['Motorcycle Repair', 'Business Management', 'Marketing', 'Entrepreneurship'],
    },
]);

const systemFeatures = ref([
    {
        icon: '📱',
        title: 'QR Code Technology',
        description: 'Sistem absensi menggunakan teknologi QR Code untuk kemudahan dan kecepatan',
        color: 'from-blue-500 to-cyan-500',
    },
    {
        icon: '🗺️',
        title: 'GPS Verification',
        description: 'Verifikasi lokasi dengan GPS untuk memastikan kehadiran di area sekolah',
        color: 'from-green-500 to-emerald-500',
    },
    {
        icon: '📊',
        title: 'Real-time Dashboard',
        description: 'Monitoring kehadiran secara real-time dengan dashboard yang informatif',
        color: 'from-purple-500 to-pink-500',
    },
    {
        icon: '📈',
        title: 'Analytics & Reports',
        description: 'Laporan dan analisis data kehadiran yang komprehensif dan akurat',
        color: 'from-orange-500 to-red-500',
    },
]);
</script>

<template>
    <div class="about-page">
        <Head title="Tentang Sistem Absensi Digital - SMK Tamansiswa 2 Jakarta" />

        <!-- Navigation -->
        <nav class="navbar">
            <div class="navbar-container">
                <div class="navbar-brand">
                    <img
                        src="https://smktamansiswa2jakarta.sch.id/wp-content/uploads/2022/03/LOGO-TAMSIS-MATERIAL-DESIGN.png"
                        alt="SMK Tamansiswa 2 Logo"
                        class="brand-logo"
                    />
                    <div class="brand-text">
                        <h1 class="brand-title">SMK Tamansiswa 2</h1>
                    </div>
                </div>

                <div class="nav-menu" :class="{ 'mobile-open': isMobileMenuOpen }">
                    <Link href="/" :class="['nav-link', { active: activeMenu === 'beranda' }]" @click="setActiveMenu('beranda')">
                        <span class="nav-icon">🏠</span>
                        <span>Beranda</span>
                    </Link>
                    <Link href="/tentang" :class="['nav-link', { active: activeMenu === 'tentang' }]" @click="setActiveMenu('tentang')">
                        <span class="nav-icon">📋</span>
                        <span>Tentang</span>
                    </Link>
                    <Link href="/kontak" :class="['nav-link', { active: activeMenu === 'kontak' }]" @click="setActiveMenu('kontak')">
                        <span class="nav-icon">📞</span>
                        <span>Kontak</span>
                    </Link>
                </div>

                <div class="nav-actions">
                    <Link href="/login" class="login-btn">
                        <span class="login-icon">🔐</span>
                        <span>Login</span>
                    </Link>
                    <button class="mobile-menu-toggle" @click="toggleMobileMenu" :class="{ active: isMobileMenuOpen }">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                </div>
            </div>
        </nav>

        <!-- Mobile Menu Overlay -->
        <div class="mobile-overlay" :class="{ active: isMobileMenuOpen }" @click="toggleMobileMenu"></div>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Page Header -->
            <section class="page-header animate-on-scroll">
                <div class="header-container">
                    <div class="breadcrumb">
                        <span class="breadcrumb-icon">🏠</span>
                        <span>Beranda</span>
                        <span class="breadcrumb-separator">→</span>
                        <span class="breadcrumb-active">Tentang</span>
                    </div>

                    <h1 class="page-title">
                        Tentang
                        <span class="text-gradient">Sistem Absensi Digital</span>
                    </h1>

                    <p class="page-description">Digitalisasi absensi siswa dengan QR Code dan GPS untuk manajemen kehadiran yang lebih cerdas.</p>
                </div>
            </section>

            <!-- Hero Stats -->
            <section class="stats-section animate-on-scroll">
                <div class="stats-container">
                    <div class="stats-grid">
                        <!-- Total Siswa -->
                        <div class="stat-card delay-0">
                            <div class="stat-icon bg-gradient-to-br from-blue-500 to-cyan-500">👥</div>
                            <div class="stat-info">
                                <div class="stat-number">{{ stats.totalSiswa }}</div>
                                <div class="stat-label">Total Siswa</div>
                            </div>
                        </div>

                        <!-- Guru Profesional -->
                        <div class="stat-card delay-1">
                            <div class="stat-icon bg-gradient-to-br from-green-500 to-emerald-500">👨‍🏫</div>
                            <div class="stat-info">
                                <div class="stat-number">{{ stats.totalGuru }}</div>
                                <div class="stat-label">Guru Profesional</div>
                            </div>
                        </div>

                        <!-- Ruang Kelas -->
                        <div class="stat-card delay-2">
                            <div class="stat-icon bg-gradient-to-br from-purple-500 to-pink-500">🏫</div>
                            <div class="stat-info">
                                <div class="stat-number">{{ stats.totalKelas }}</div>
                                <div class="stat-label">Ruang Kelas</div>
                            </div>
                        </div>

                        <!-- Program Keahlian -->
                        <div class="stat-card delay-3">
                            <div class="stat-icon bg-gradient-to-br from-orange-500 to-red-500">📚</div>
                            <div class="stat-info">
                                <div class="stat-number">{{ stats.totalJurusan }}</div>
                                <div class="stat-label">Program Keahlian</div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- School Info Section -->
            <section class="school-info-section animate-on-scroll">
                <div class="section-container">
                    <div class="section-header">
                        <h2 class="section-title">Informasi <span class="text-gradient">Sekolah</span></h2>
                        <p class="section-subtitle">SMK Tamansiswa 2 Jakarta</p>
                    </div>

                    <div class="info-grid">
                        <div class="info-card">
                            <div class="info-icon bg-gradient-to-br from-blue-500 to-cyan-500">📅</div>
                            <div class="info-content">
                                <h3>Tahun Berdiri</h3>
                                <p>{{ schoolInfo.established }}</p>
                                <span class="info-detail">43+ tahun pengalaman</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-icon bg-gradient-to-br from-green-500 to-emerald-500">🏆</div>
                            <div class="info-content">
                                <h3>Akreditasi</h3>
                                <p>{{ schoolInfo.accreditation }}</p>
                                <span class="info-detail">Standar pendidikan tinggi</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-icon bg-gradient-to-br from-purple-500 to-pink-500">📍</div>
                            <div class="info-content">
                                <h3>Lokasi</h3>
                                <p>Jakarta Pusat</p>
                                <span class="info-detail">Strategis & mudah diakses</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-icon bg-gradient-to-br from-orange-500 to-red-500">📞</div>
                            <div class="info-content">
                                <h3>Kontak</h3>
                                <p>{{ schoolInfo.phone }}</p>
                                <span class="info-detail">Siap melayani</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-icon bg-gradient-to-br from-indigo-500 to-purple-500">📧</div>
                            <div class="info-content">
                                <h3>Email</h3>
                                <p>{{ schoolInfo.email }}</p>
                                <span class="info-detail">Komunikasi digital</span>
                            </div>
                        </div>

                        <div class="info-card">
                            <div class="info-icon bg-gradient-to-br from-teal-500 to-cyan-500">🌐</div>
                            <div class="info-content">
                                <h3>Website</h3>
                                <p><a :href="schoolInfo.website" target="_blank" class="info-link">Visit Website</a></p>
                                <span class="info-detail">Informasi lengkap online</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Vision & Mission Section -->
            <section class="vision-mission-section animate-on-scroll">
                <div class="section-container">
                    <div class="vm-grid">
                        <div class="vision-card">
                            <div class="vm-icon">🎯</div>
                            <h3 class="vm-title">Visi</h3>
                            <p class="vm-description">
                                Menjadi sistem absensi digital terdepan yang mendukung transformasi pendidikan menuju era digital dengan teknologi
                                yang mudah digunakan, akurat, dan terpercaya untuk menciptakan lingkungan belajar yang lebih efisien dan modern.
                            </p>
                            <div class="vm-decoration vision"></div>
                        </div>

                        <div class="mission-card">
                            <div class="vm-icon">🚀</div>
                            <h3 class="vm-title">Misi</h3>
                            <div class="mission-list">
                                <div class="mission-item">
                                    <span class="mission-check">✓</span>
                                    <span>Menyediakan sistem absensi yang user-friendly dan reliable</span>
                                </div>
                                <div class="mission-item">
                                    <span class="mission-check">✓</span>
                                    <span>Meningkatkan efisiensi pengelolaan data kehadiran siswa</span>
                                </div>
                                <div class="mission-item">
                                    <span class="mission-check">✓</span>
                                    <span>Memberikan transparansi informasi kepada orang tua</span>
                                </div>
                                <div class="mission-item">
                                    <span class="mission-check">✓</span>
                                    <span>Mendukung pengambilan keputusan berbasis data</span>
                                </div>
                                <div class="mission-item">
                                    <span class="mission-check">✓</span>
                                    <span>Mengoptimalkan proses administratif sekolah</span>
                                </div>
                            </div>
                            <div class="vm-decoration mission"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Majors Section -->
            <section class="majors-section animate-on-scroll">
                <div class="section-container">
                    <div class="section-header">
                        <h2 class="section-title">Program <span class="text-gradient">Keahlian</span></h2>
                        <p class="section-subtitle">6 jurusan unggulan yang mempersiapkan siswa untuk masa depan</p>
                    </div>

                    <div class="majors-grid">
                        <div v-for="(major, index) in majors" :key="index" :class="`major-card delay-${index % 3}`">
                            <div class="major-header">
                                <div :class="`major-icon bg-gradient-to-br ${major.color}`">
                                    {{ major.icon }}
                                </div>
                                <div class="major-info">
                                    <h3 class="major-title">{{ major.name }}</h3>
                                    <span class="major-code">({{ major.code }})</span>
                                </div>
                            </div>

                            <p class="major-description">{{ major.description }}</p>

                            <div class="major-overlay"></div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact CTA Section -->
            <section class="contact-cta-section animate-on-scroll">
                <div class="cta-container">
                    <div class="cta-content">
                        <div class="cta-icon">💬</div>
                        <h2 class="cta-title">Butuh Informasi Lebih Lanjut?</h2>
                        <p class="cta-description">
                            Tim support kami siap membantu menjawab pertanyaan Anda tentang sistem absensi digital dan program pendidikan kami.
                        </p>
                        <div class="cta-actions">
                            <Link href="/kontak" class="cta-btn-primary">
                                <span class="btn-icon">📞</span>
                                <span>Hubungi Kami</span>
                                <span class="btn-arrow">→</span>
                            </Link>
                        </div>
                    </div>

                    <div class="cta-visual">
                        <div class="floating-shapes">
                            <div class="shape shape-1"></div>
                            <div class="shape shape-2"></div>
                            <div class="shape shape-3"></div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-container">
                <div class="footer-brand">
                    <img
                        src="https://smktamansiswa2jakarta.sch.id/wp-content/uploads/2022/03/LOGO-TAMSIS-MATERIAL-DESIGN.png"
                        alt="SMK Tamansiswa 2"
                        class="footer-logo"
                    />
                    <div class="footer-info">
                        <div class="footer-title">SMK Tamansiswa 2 Jakarta</div>
                        <a href="https://smktamansiswa2jakarta.sch.id" target="_blank" class="footer-link"> smktamansiswa2jakarta.sch.id </a>
                    </div>
                </div>
                <div class="footer-socials">
                    <a href="https://facebook.com/smktamansiswa2jakarta" class="social-btn" target="_blank">
                        <svg viewBox="0 0 24 24" fill="currentColor">
                            <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z" />
                        </svg>
                    </a>
                    <a href="https://instagram.com/smktamansiswa2jakarta" class="social-btn" target="_blank">
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
                    <a href="https://youtube.com/@smktamansiswa2jakarta375?si=XuMQeADTidosYWLq" class="social-btn" target="_blank">
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
            <div class="footer-bottom">
                <p>&copy; 2025 SMK Tamansiswa 2 Jakarta. All rights reserved.</p>
            </div>
        </footer>
    </div>
</template>

<style scoped>
/* Reset & Base */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    line-height: 1.6;
    color: #1f2937;
}

/* Animations */
.animate-on-scroll {
    opacity: 0;
    transform: translateY(40px);
    transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-on-scroll.animate-in {
    opacity: 1;
    transform: translateY(0);
}

.delay-0 {
    transition-delay: 0ms;
}
.delay-1 {
    transition-delay: 100ms;
}
.delay-2 {
    transition-delay: 200ms;
}
.delay-3 {
    transition-delay: 300ms;
}

/* Utility Classes */
.text-gradient {
    background: linear-gradient(135deg, #3b82f6, #8b5cf6, #06b6d4);
    background-clip: text;
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-size: 200% 200%;
    animation: gradient 3s ease infinite;
}

@keyframes gradient {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

/* Page Layout */
.about-page {
    min-height: 100vh;
    background: #ffffff;
    overflow-x: hidden;
}

/* ============ NAVBAR WITH HIDE/SHOW FUNCTIONALITY ============ */
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(229, 231, 235, 0.5);
    z-index: 1000;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    transform: translateY(0);
}

.navbar.scrolled {
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
}

.navbar.navbar-hidden {
    transform: translateY(-100%);
}

.navbar-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    height: 80px;
}

.navbar-brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.brand-logo {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    object-fit: cover;
}

.brand-text {
    display: flex;
    flex-direction: column;
}

.brand-title {
    font-size: 20px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.2;
}

.nav-menu {
    display: flex;
    align-items: center;
    gap: 32px;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 500;
    text-decoration: none;
    color: #6b7280;
    transition: all 0.2s ease;
}

.nav-link:hover,
.nav-link.active {
    color: #3b82f6;
    background: rgba(59, 130, 246, 0.1);
}

.nav-icon {
    font-size: 18px;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 16px;
}

.login-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.login-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
}

.mobile-menu-toggle {
    display: none;
    flex-direction: column;
    gap: 4px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 8px;
}

.mobile-menu-toggle span {
    width: 24px;
    height: 3px;
    background: #374151;
    border-radius: 2px;
    transition: all 0.3s ease;
}

.mobile-menu-toggle.active span:nth-child(1) {
    transform: rotate(45deg) translate(5px, 5px);
}

.mobile-menu-toggle.active span:nth-child(2) {
    opacity: 0;
}

.mobile-menu-toggle.active span:nth-child(3) {
    transform: rotate(-45deg) translate(5px, -5px);
}

.mobile-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(0, 0, 0, 0.5);
    z-index: 999;
    opacity: 0;
    visibility: hidden;
    transition: all 0.3s ease;
}

.mobile-overlay.active {
    opacity: 1;
    visibility: visible;
}

/* Main Content */
.main-content {
    padding-top: 80px;
}

/* Page Header */
.page-header {
    padding: 80px 0 60px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    text-align: center;
}

.header-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
}

.breadcrumb {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 14px;
    color: #6b7280;
    margin-bottom: 32px;
    font-weight: 500;
}

.breadcrumb-icon {
    font-size: 16px;
}

.breadcrumb-separator {
    color: #d1d5db;
}

.breadcrumb-active {
    color: #3b82f6;
    font-weight: 600;
}

.page-title {
    font-size: 48px;
    font-weight: 800;
    line-height: 1.2;
    color: #1f2937;
    margin-bottom: 24px;
}

.page-description {
    font-size: 20px;
    color: #6b7280;
    line-height: 1.6;
    max-width: 800px;
    margin: 0 auto;
}

/* Stats Section */
.stats-section {
    padding: 80px 0;
    background: white;
}

.stats-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 32px;
}

.stat-card {
    background: white;
    border: 2px solid #f3f4f6;
    border-radius: 20px;
    padding: 32px;
    display: flex;
    align-items: center;
    gap: 20px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.stat-card:hover {
    transform: translateY(-8px);
    border-color: #e5e7eb;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.stat-icon {
    width: 70px;
    height: 70px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: white;
    flex-shrink: 0;
}

.stat-info {
    flex: 1;
}

.stat-number {
    font-size: 32px;
    font-weight: 800;
    color: #1f2937;
    line-height: 1;
}

.stat-label {
    font-size: 16px;
    color: #6b7280;
    margin-top: 4px;
    font-weight: 500;
}

/* Section Styles */
.section-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
}

.section-header {
    text-align: center;
    margin-bottom: 80px;
}

.section-title {
    font-size: 42px;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 16px;
    line-height: 1.2;
}

.section-subtitle {
    font-size: 18px;
    color: #6b7280;
    max-width: 600px;
    margin: 0 auto;
}

/* School Info Section */
.school-info-section {
    padding: 120px 0;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 32px;
}

.info-card {
    background: white;
    border-radius: 20px;
    padding: 32px;
    display: flex;
    align-items: center;
    gap: 20px;
    border: 2px solid transparent;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.info-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border-color: rgba(59, 130, 246, 0.2);
}

.info-icon {
    width: 70px;
    height: 70px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 28px;
    color: white;
    flex-shrink: 0;
}

.info-content {
    flex: 1;
}

.info-content h3 {
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
}

.info-content p {
    font-size: 16px;
    color: #374151;
    font-weight: 600;
    margin-bottom: 4px;
}

.info-detail {
    font-size: 14px;
    color: #6b7280;
}

.info-link {
    color: #3b82f6;
    text-decoration: none;
    font-weight: 600;
}

.info-link:hover {
    text-decoration: underline;
}

/* Vision Mission Section */
.vision-mission-section {
    padding: 120px 0;
    background: linear-gradient(135deg, #1e293b, #334155);
    position: relative;
    overflow: hidden;
}

.vm-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 60px;
    align-items: start;
}

.vision-card,
.mission-card {
    background: rgba(255, 255, 255, 0.05);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 24px;
    padding: 40px;
    color: white;
    position: relative;
    overflow: hidden;
}

.vm-icon {
    font-size: 48px;
    margin-bottom: 24px;
    display: block;
}

.vm-title {
    font-size: 28px;
    font-weight: 800;
    margin-bottom: 20px;
    color: white;
}

.vm-description {
    font-size: 16px;
    line-height: 1.7;
    color: rgba(255, 255, 255, 0.9);
}

.mission-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.mission-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.mission-check {
    width: 24px;
    height: 24px;
    background: #10b981;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 14px;
    font-weight: 700;
    color: white;
    flex-shrink: 0;
}

.mission-item span:last-child {
    flex: 1;
    line-height: 1.6;
    color: rgba(255, 255, 255, 0.9);
}

.vm-decoration {
    position: absolute;
    bottom: -50px;
    right: -50px;
    width: 150px;
    height: 150px;
    border: 2px solid rgba(255, 255, 255, 0.1);
    border-radius: 50%;
    pointer-events: none;
}

.vm-decoration.vision {
    animation: rotate 20s linear infinite;
}

.vm-decoration.mission {
    animation: rotate 25s linear infinite reverse;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Majors Section */
.majors-section {
    padding: 120px 0;
    background: white;
}

.majors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 32px;
}

.major-card {
    background: white;
    border: 2px solid #f3f4f6;
    border-radius: 24px;
    padding: 32px;
    transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    overflow: hidden;
}

.major-card:hover {
    transform: translateY(-12px);
    border-color: #e5e7eb;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
}

.major-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
}

.major-icon {
    width: 70px;
    height: 70px;
    border-radius: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 32px;
    color: white;
    flex-shrink: 0;
}

.major-info {
    flex: 1;
}

.major-title {
    font-size: 20px;
    font-weight: 700;
    color: #1f2937;
    line-height: 1.3;
    margin-bottom: 4px;
}

.major-code {
    font-size: 14px;
    color: #3b82f6;
    font-weight: 600;
    background: rgba(59, 130, 246, 0.1);
    padding: 2px 8px;
    border-radius: 12px;
}

.major-description {
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 24px;
    font-size: 15px;
}

.major-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(135deg, #3b82f6, #8b5cf6);
    transform: scaleX(0);
    transition: transform 0.4s ease;
}

.major-card:hover .major-overlay {
    transform: scaleX(1);
}

/* Contact CTA Section */
.contact-cta-section {
    padding: 120px 0;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    position: relative;
    overflow: hidden;
}

.cta-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 80px;
    align-items: center;
}

.cta-content {
    text-align: left;
}

.cta-icon {
    font-size: 64px;
    margin-bottom: 24px;
    display: block;
}

.cta-title {
    font-size: 42px;
    font-weight: 800;
    color: #1f2937;
    margin-bottom: 20px;
    line-height: 1.2;
}

.cta-description {
    font-size: 18px;
    color: #6b7280;
    line-height: 1.6;
    margin-bottom: 40px;
    max-width: 500px;
}

.cta-actions {
    display: flex;
    gap: 16px;
    flex-wrap: wrap;
}

.cta-btn-primary {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 32px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
}

.cta-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(59, 130, 246, 0.4);
}

.btn-icon {
    font-size: 18px;
}

.btn-arrow {
    font-size: 16px;
    transition: transform 0.3s ease;
}

.cta-btn-primary:hover .btn-arrow {
    transform: translateX(4px);
}

.cta-visual {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}

.floating-shapes {
    position: relative;
    width: 300px;
    height: 300px;
}

.shape {
    position: absolute;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.1), rgba(139, 92, 246, 0.1));
    animation: float 6s ease-in-out infinite;
}

.shape-1 {
    width: 120px;
    height: 120px;
    top: 20px;
    left: 40px;
    animation-delay: 0s;
}

.shape-2 {
    width: 80px;
    height: 80px;
    top: 100px;
    right: 60px;
    animation-delay: 2s;
}

.shape-3 {
    width: 60px;
    height: 60px;
    bottom: 40px;
    left: 80px;
    animation-delay: 4s;
}

@keyframes float {
    0%, 100% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.5;
    }
    50% {
        transform: translateY(-20px) rotate(180deg);
        opacity: 0.8;
    }
}

/* Footer */
.footer {
    background: #111827;
    color: white;
}

.footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 60px 24px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.footer-brand {
    display: flex;
    align-items: center;
    gap: 16px;
}

.footer-logo {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    object-fit: cover;
}

.footer-info {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.footer-title {
    font-size: 18px;
    font-weight: 700;
    color: white;
}

.footer-link {
    font-size: 14px;
    color: #60a5fa;
    text-decoration: none;
    margin-top: 4px;
}

.footer-link:hover {
    text-decoration: underline;
}

.footer-socials {
    display: flex;
    gap: 16px;
}

.social-btn {
    width: 48px;
    height: 48px;
    background: rgba(59, 130, 246, 0.1);
    border: 1px solid rgba(59, 130, 246, 0.2);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #60a5fa;
    transition: all 0.3s ease;
}

.social-btn:hover {
    background: #3b82f6;
    color: white;
    transform: translateY(-2px);
}

.social-btn svg {
    width: 20px;
    height: 20px;
}

.footer-bottom {
    max-width: 1200px;
    margin: 0 auto;
    padding: 24px;
    border-top: 1px solid #374151;
    text-align: center;
    color: #9ca3af;
    font-size: 14px;
}

/* Responsive Design */
@media (max-width: 1024px) {
    .page-title {
        font-size: 40px;
    }
    .section-title {
        font-size: 36px;
    }
    .vm-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    .cta-container {
        grid-template-columns: 1fr;
        gap: 60px;
        text-align: center;
    }
}

@media (max-width: 768px) {
    .mobile-menu-toggle {
        display: flex;
    }
    .nav-menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 300px;
        height: 100vh;
        background: white;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
        padding: 80px 32px 32px;
        gap: 16px;
        box-shadow: -5px 0 20px rgba(0, 0, 0, 0.1);
        transition: right 0.3s ease;
        z-index: 1001;
    }
    .nav-menu.mobile-open {
        right: 0;
    }
    .mobile-overlay {
        display: block;
    }
    .nav-link {
        width: 100%;
        padding: 16px;
        font-size: 18px;
        border-radius: 12px;
    }
    .page-title {
        font-size: 32px;
    }
    .section-title {
        font-size: 32px;
    }
    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 24px;
    }
    .info-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    .majors-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }
    .major-header {
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 16px;
    }
    .cta-title {
        font-size: 32px;
    }
    .footer-container {
        flex-direction: column;
        gap: 32px;
        text-align: center;
    }
}

@media (max-width: 480px) {
    .navbar-container {
        padding: 0 16px;
    }
    .brand-title {
        font-size: 16px;
    }
    .section-container {
        padding: 0 16px;
    }
    .page-title {
        font-size: 28px;
    }
    .section-title {
        font-size: 28px;
    }
    .stat-card {
        padding: 24px;
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
    .info-card {
        padding: 24px;
        flex-direction: column;
        text-align: center;
        gap: 16px;
    }
    .major-card {
        padding: 24px;
    }
    .vm-title {
        font-size: 24px;
    }
    .cta-title {
        font-size: 28px;
    }
    .floating-shapes {
        width: 200px;
        height: 200px;
    }
}
</style>