<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const activeMenu = ref('beranda');
const isMobileMenuOpen = ref(false);

function setActiveMenu(menu: string) {
    activeMenu.value = menu;
    isMobileMenuOpen.value = false;
}

function toggleMobileMenu() {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
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
        { threshold: 0.1 },
    );

    document.querySelectorAll('.animate-on-scroll').forEach((el) => {
        observer.observe(el);
    });

    // Navbar scroll effect
    const navbar = document.querySelector('.navbar') as HTMLElement;
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

// Real-time clock
const currentTime = ref(new Date());
onMounted(() => {
    setInterval(() => {
        currentTime.value = new Date();
    }, 1000);
});

// Features data
const features = ref([
    {
        icon: '📱',
        title: 'QR Code Scanner',
        description: 'Sistem absensi cepat dengan teknologi QR Code yang akurat dan real-time',
        color: 'from-blue-500 to-cyan-500',
    },
    {
        icon: '🗺️',
        title: 'GPS Location',
        description: 'Verifikasi lokasi dengan GPS untuk memastikan siswa berada di sekolah',
        color: 'from-green-500 to-emerald-500',
    },
    {
        icon: '📊',
        title: 'Real-time Analytics',
        description: 'Dashboard analitik real-time untuk monitoring kehadiran siswa',
        color: 'from-purple-500 to-pink-500',
    },
    {
        icon: '📋',
        title: 'Digital Reports',
        description: 'Laporan kehadiran digital yang dapat diakses kapan saja',
        color: 'from-orange-500 to-red-500',
    },
]);
</script>

<template>
    <div class="attendance-page">
        <Head title="Sistem Absensi Digital - SMK Tamansiswa 2 Jakarta" />

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
            <!-- Hero Section -->
            <section class="hero-section animate-on-scroll">
                <!-- Background Pattern -->
                <div class="hero-background">
                    <div class="bg-pattern"></div>
                    <div class="bg-gradient-orb bg-orb-1"></div>
                    <div class="bg-gradient-orb bg-orb-2"></div>
                    <div class="bg-gradient-orb bg-orb-3"></div>
                </div>

                <div class="hero-container">
                    <div class="hero-content">
                        <h1 class="hero-title">
                            Absensi Digital dengan
                            <span class="text-gradient">QR Code & GPS</span>
                        </h1>
                        <p class="hero-description">
                            Hadirkan solusi absensi siswa yang inovatif dengan QR Code dan GPS, tanpa ribet dan lebih efisien.
                        </p>

                        <div class="hero-actions">
                            <Link href="/login" class="cta-primary">
                                <span class="cta-icon">🚀</span>
                                <span>Mulai Absensi</span>
                            </Link>
                            <Link href="/tentang" class="cta-secondary">
                                <span class="cta-icon">📖</span>
                                <span>Pelajari Lebih</span>
                            </Link>
                        </div>
                    </div>

                    <div class="hero-visual">
                        <!-- Background Elements -->
                        <div class="visual-background">
                            <div class="bg-circle bg-circle-1"></div>
                            <div class="bg-circle bg-circle-2"></div>
                            <div class="bg-circle bg-circle-3"></div>

                            <!-- Grid Pattern -->
                            <div class="grid-pattern"></div>

                            <!-- Floating Icons -->
                            <div class="floating-icon float-1">📱</div>
                            <div class="floating-icon float-2">📍</div>
                            <div class="floating-icon float-3">✅</div>
                            <div class="floating-icon float-4">📊</div>
                            <div class="floating-icon float-5">🔒</div>
                        </div>

                        <div class="phone-mockup">                             
                        <div class="phone-screen">                                 
                            <div class="qr-scanner-ui">                                     
                                <div class="scanner-header">                                         
                                    <div class="scanner-title">QR Scanner</div>                                         
                                    <div class="scanner-status">Ready</div>                                     
                                </div>                                     
                                <div class="scanner-area">                                         
                                    <div class="scanner-frame">                                             
                                        <div class="corner tl"></div>                                             
                                        <div class="corner tr"></div>                                             
                                        <div class="corner bl"></div>                                             
                                        <div class="corner br"></div>                                             
                                        <div class="scan-line"></div>                                         
                                    </div>                                         
                                    <div class="qr-code">
                                        <!-- Ganti div qr-pattern dengan img -->
                                        <img src="/rickroll.png" alt="QR Code" class="qr-image" />
                                    </div>                                     
                                </div>                                     
                                <div class="scanner-info">                                         
                                    <div class="location-indicator">                                             
                                        <span class="location-icon">📍</span>                                             
                                        <span>SMK Tamansiswa 2 Jakarta</span>                                         
                                    </div>                                     
                                    </div>                                 
                                </div>                             
                            </div>                         
                        </div>
                    </div>
                </div>
            </section>

            <!-- Current Time Display -->
            <section class="time-section animate-on-scroll">
                <div class="time-container">
                    <div class="time-icon">🕐</div>
                    <div class="time-info">
                        <div class="current-time">
                            {{ currentTime.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }) }}
                        </div>
                        <div class="current-date">
                            {{
                                currentTime.toLocaleDateString('id-ID', {
                                    weekday: 'long',
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                })
                            }}
                        </div>
                    </div>
                    <div class="attendance-status">
                        <div class="status-indicator active"></div>
                        <span>Sistem Aktif</span>
                    </div>
                </div>
            </section>

            <!-- How It Works Section -->
            <section class="process-section animate-on-scroll">
                <div class="section-container">
                    <div class="section-header">
                        <h2 class="section-title">Cara <span class="text-gradient">Kerja</span></h2>
                        <p class="section-subtitle">Proses absensi yang sederhana dan efektif</p>
                    </div>

                    <div class="process-flow">
                        <div class="process-step">
                            <div class="step-number">01</div>
                            <div class="step-icon">📱</div>
                            <h3 class="step-title">Scan QR Code</h3>
                            <p class="step-description">Siswa memindai QR Code yang tersedia di kelas</p>
                        </div>
                        <div class="process-arrow">→</div>
                        <div class="process-step">
                            <div class="step-number">02</div>
                            <div class="step-icon">📍</div>
                            <h3 class="step-title">Verifikasi GPS</h3>
                            <p class="step-description">Sistem memverifikasi lokasi siswa berada di sekolah</p>
                        </div>
                        <div class="process-arrow">→</div>
                        <div class="process-step">
                            <div class="step-number">03</div>
                            <div class="step-icon">✅</div>
                            <h3 class="step-title">Konfirmasi</h3>
                            <p class="step-description">Kehadiran tercatat secara otomatis dan real-time</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="cta-section animate-on-scroll">
                <div class="cta-container">
                    <div class="cta-content">
                        <h2 class="cta-title">Siap untuk mulai absensi digital?</h2>
                        <p class="cta-description">Bergabunglah dengan sistem absensi modern SMK Tamansiswa 2 Jakarta</p>
                        <div class="cta-actions">
                            <Link href="/login" class="cta-btn-primary">
                                <span>Login Sekarang</span>
                                <span class="btn-arrow">→</span>
                            </Link>
                        </div>
                    </div>
                    <div class="cta-decoration">
                        <div class="decoration-circle"></div>
                        <div class="decoration-dots"></div>
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
    font-family:
        'Inter',
        -apple-system,
        BlinkMacSystemFont,
        'Segoe UI',
        Roboto,
        sans-serif;
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
    0%,
    100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

/* Page Layout */
.attendance-page {
    min-height: 100vh;
    background: #ffffff;
    overflow-x: hidden;
}

/* Navigation */
.navbar {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(229, 231, 235, 0.5);
    z-index: 1000;
    transition: all 0.3s ease;
}

.navbar.scrolled {
    background: rgba(255, 255, 255, 0.95);
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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

.brand-subtitle {
    font-size: 12px;
    color: #6b7280;
    font-weight: 500;
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

/* Hero Section - IMPROVED BACKGROUND */
.hero-section {
    padding: 80px 0 120px;
    position: relative;
    overflow: hidden;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 50%, #f1f5f9 100%);
}

/* Hero Background Elements */
.hero-background {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    overflow: hidden;
    z-index: 0;
}

.bg-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image:
        radial-gradient(circle at 25% 25%, rgba(59, 130, 246, 0.05) 0%, transparent 50%),
        radial-gradient(circle at 75% 75%, rgba(139, 92, 246, 0.05) 0%, transparent 50%),
        linear-gradient(45deg, transparent 49%, rgba(6, 182, 212, 0.03) 50%, transparent 51%);
    background-size:
        800px 800px,
        600px 600px,
        100px 100px;
    animation: pattern-move 20s ease-in-out infinite;
}

@keyframes pattern-move {
    0%,
    100% {
        transform: translate(0, 0) rotate(0deg);
    }
    50% {
        transform: translate(20px, -20px) rotate(1deg);
    }
}

.bg-gradient-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(40px);
    opacity: 0.3;
    animation: float-orb 8s ease-in-out infinite;
}

.bg-orb-1 {
    width: 300px;
    height: 300px;
    background: linear-gradient(135deg, #3b82f6, #06b6d4);
    top: 10%;
    left: 10%;
    animation-delay: 0s;
}

.bg-orb-2 {
    width: 400px;
    height: 400px;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    top: 40%;
    right: 5%;
    animation-delay: 2s;
}

.bg-orb-3 {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, #06b6d4, #10b981);
    bottom: 20%;
    left: 40%;
    animation-delay: 4s;
}

@keyframes float-orb {
    0%,
    100% {
        transform: translate(0, 0) scale(1);
    }
    33% {
        transform: translate(20px, -30px) scale(1.1);
    }
    66% {
        transform: translate(-15px, 20px) scale(0.9);
    }
}

.hero-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 80px;
    align-items: center;
    position: relative;
    z-index: 1;
}

.hero-content {
    display: flex;
    flex-direction: column;
    gap: 32px;
}

.hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: rgba(59, 130, 246, 0.1);
    color: #3b82f6;
    border-radius: 20px;
    font-size: 14px;
    font-weight: 500;
    width: fit-content;
}

.badge-icon {
    font-size: 16px;
}

.hero-title {
    font-size: 48px;
    font-weight: 800;
    line-height: 1.2;
    color: #1f2937;
}

.hero-description {
    font-size: 18px;
    color: #6b7280;
    line-height: 1.6;
    max-width: 500px;
}

.hero-stats {
    display: flex;
    gap: 32px;
}

.stat-item {
    text-align: center;
}

.stat-number {
    font-size: 32px;
    font-weight: 800;
    color: #3b82f6;
    line-height: 1;
}

.stat-label {
    font-size: 14px;
    color: #6b7280;
    margin-top: 4px;
}

.hero-actions {
    display: flex;
    gap: 16px;
    align-items: center;
}

.cta-primary,
.cta-secondary {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 16px 32px;
    border-radius: 12px;
    font-weight: 600;
    text-decoration: none;
    transition: all 0.3s ease;
}

.cta-primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
}

.cta-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 35px rgba(59, 130, 246, 0.4);
}

.cta-secondary {
    background: white;
    color: #374151;
    border: 2px solid #e5e7eb;
}

.cta-secondary:hover {
    border-color: #3b82f6;
    color: #3b82f6;
}

/* Hero Visual - IMPROVED */
.hero-visual {
    position: relative;
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 2;
}

/* Visual Background - NEW */
.visual-background {
    position: absolute;
    top: -100px;
    left: -100px;
    right: -100px;
    bottom: -100px;
    z-index: 0;
}

.bg-circle {
    position: absolute;
    border-radius: 50%;
    opacity: 0.1;
}

.bg-circle-1 {
    width: 200px;
    height: 200px;
    background: linear-gradient(135deg, #3b82f6, #06b6d4);
    top: 0;
    right: 0;
    animation: rotate-slow 15s linear infinite;
}

.bg-circle-2 {
    width: 150px;
    height: 150px;
    background: linear-gradient(135deg, #8b5cf6, #ec4899);
    bottom: 50px;
    left: 0;
    animation: rotate-slow 20s linear infinite reverse;
}

.bg-circle-3 {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #06b6d4, #10b981);
    top: 50%;
    right: 20%;
    animation: rotate-slow 12s linear infinite;
}

@keyframes rotate-slow {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Grid Pattern */
.grid-pattern {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image:
        linear-gradient(rgba(59, 130, 246, 0.1) 1px, transparent 1px), linear-gradient(90deg, rgba(59, 130, 246, 0.1) 1px, transparent 1px);
    background-size: 50px 50px;
    opacity: 0.3;
    animation: grid-move 10s ease-in-out infinite;
}

@keyframes grid-move {
    0%,
    100% {
        transform: translate(0, 0);
    }
    50% {
        transform: translate(10px, 10px);
    }
}

/* Floating Icons - IMPROVED */
.floating-icon {
    position: absolute;
    font-size: 24px;
    opacity: 0.15;
    z-index: 1;
    animation: float-gentle 6s ease-in-out infinite;
}

.float-1 {
    top: 10%;
    left: 5%;
    animation-delay: 0s;
}

.float-2 {
    top: 20%;
    right: 10%;
    animation-delay: 1.2s;
}

.float-3 {
    bottom: 30%;
    left: 8%;
    animation-delay: 2.4s;
}

.float-4 {
    bottom: 20%;
    right: 5%;
    animation-delay: 3.6s;
}

.float-5 {
    top: 50%;
    left: 2%;
    animation-delay: 4.8s;
}

@keyframes float-gentle {
    0%,
    100% {
        transform: translateY(0px) rotate(0deg);
        opacity: 0.15;
    }
    50% {
        transform: translateY(-15px) rotate(5deg);
        opacity: 0.25;
    }
}

.phone-mockup {
    width: 300px;
    height: 600px;
    background: linear-gradient(145deg, #1e293b, #334155);
    border-radius: 40px;
    padding: 20px;
    box-shadow:
        0 25px 60px rgba(0, 0, 0, 0.3),
        0 8px 20px rgba(0, 0, 0, 0.15),
        inset 0 1px 0 rgba(255, 255, 255, 0.1);
    position: relative;
    z-index: 3;
    animation: phone-glow 4s ease-in-out infinite;
}

@keyframes phone-glow {
    0%,
    100% {
        box-shadow:
            0 25px 60px rgba(0, 0, 0, 0.3),
            0 8px 20px rgba(0, 0, 0, 0.15),
            inset 0 1px 0 rgba(255, 255, 255, 0.1),
            0 0 0 rgba(59, 130, 246, 0);
    }
    50% {
        box-shadow:
            0 30px 70px rgba(0, 0, 0, 0.35),
            0 12px 30px rgba(0, 0, 0, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.1),
            0 0 40px rgba(59, 130, 246, 0.2);
    }
}

.phone-screen {
    width: 100%;
    height: 100%;
    background: #ffffff;
    border-radius: 30px;
    overflow: hidden;
    position: relative;
    box-shadow: inset 0 2px 10px rgba(0, 0, 0, 0.1);
}

.qr-scanner-ui {
    padding: 24px;
    height: 100%;
    display: flex;
    flex-direction: column;
    background: linear-gradient(180deg, #ffffff 0%, #f8fafc 100%);
}

.scanner-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 32px;
}

.scanner-title {
    font-size: 20px;
    font-weight: 700;
    color: #1f2937;
}

.scanner-status {
    padding: 6px 14px;
    background: linear-gradient(135deg, #10b981, #059669);
    color: white;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    box-shadow: 0 2px 8px rgba(16, 185, 129, 0.3);
    animation: status-pulse 2s ease-in-out infinite;
}

@keyframes status-pulse {
    0%,
    100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.scanner-area {
    flex: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}

.scanner-frame {
    width: 200px;
    height: 200px;
    position: relative;
    border: 2px solid #3b82f6;
    border-radius: 12px;
    background: rgba(59, 130, 246, 0.05);
}

.corner {
    position: absolute;
    width: 20px;
    height: 20px;
    border: 3px solid #3b82f6;
}

.corner.tl {
    top: -3px;
    left: -3px;
    border-right: none;
    border-bottom: none;
    border-radius: 8px 0 0 0;
}
.corner.tr {
    top: -3px;
    right: -3px;
    border-left: none;
    border-bottom: none;
    border-radius: 0 8px 0 0;
}
.corner.bl {
    bottom: -3px;
    left: -3px;
    border-right: none;
    border-top: none;
    border-radius: 0 0 0 8px;
}
.corner.br {
    bottom: -3px;
    right: -3px;
    border-left: none;
    border-top: none;
    border-radius: 0 0 8px 0;
}

.scan-line {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, transparent, #3b82f6, transparent);
    border-radius: 2px;
    animation: scan 2s linear infinite;
    box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
}

@keyframes scan {
    0% {
        top: 0;
        opacity: 1;
    }
    100% {
        top: calc(100% - 3px);
        opacity: 0.3;
    }
}

.qr-code {
    position: absolute;
    width: 120px;
    height: 120px;
    background: white;
    border-radius: 8px;
    box-shadow:
        0 8px 25px rgba(0, 0, 0, 0.15),
        0 2px 10px rgba(0, 0, 0, 0.1);
    overflow: hidden;
}

.qr-pattern {
    width: 100%;
    height: 100%;
    background-image:
        repeating-linear-gradient(0deg, #1f2937, #1f2937 3px, transparent 3px, transparent 6px),
        repeating-linear-gradient(90deg, #1f2937, #1f2937 3px, transparent 3px, transparent 6px);
    opacity: 0.9;
    background-size: 12px 12px;
}

.scanner-info {
    margin-top: 24px;
    text-align: center;
}

.location-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 12px 20px;
    background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
    border-radius: 25px;
    font-size: 14px;
    color: #374151;
    font-weight: 500;
    border: 1px solid rgba(59, 130, 246, 0.1);
}

.location-icon {
    color: #3b82f6;
}

/* Time Section */
.time-section {
    padding: 60px 0;
    background: white;
}

.time-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 32px;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    border-radius: 20px;
    padding: 40px;
}

.time-icon {
    font-size: 48px;
    color: #3b82f6;
}

.time-info {
    text-align: center;
}

.current-time {
    font-size: 48px;
    font-weight: 800;
    color: #1f2937;
    line-height: 1;
    font-family: 'SF Mono', 'Monaco', 'Inconsolata', monospace;
}

.current-date {
    font-size: 18px;
    color: #6b7280;
    margin-top: 8px;
}

.attendance-status {
    display: flex;
    align-items: center;
    gap: 12px;
    color: black;
    font-weight: bold;
    background: white;
    padding: 12px 24px;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.status-indicator {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background: #10b981;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% {
        opacity: 1;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0.7);
    }
    70% {
        opacity: 1;
        box-shadow: 0 0 0 10px rgba(16, 185, 129, 0);
    }
    100% {
        opacity: 1;
        box-shadow: 0 0 0 0 rgba(16, 185, 129, 0);
    }
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

/* Process Section */
.process-section {
    padding: 120px 0;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.process-flow {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 40px;
}

.process-step {
    text-align: center;
    max-width: 280px;
    position: relative;
}

.step-number {
    position: absolute;
    top: -12px;
    right: -12px;
    width: 32px;
    height: 32px;
    background: #3b82f6;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 14px;
    z-index: 2;
}

.step-icon {
    width: 100px;
    height: 100px;
    background: white;
    border: 3px solid #e5e7eb;
    border-radius: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 40px;
    margin: 0 auto 24px;
    transition: all 0.3s ease;
}

.process-step:hover .step-icon {
    border-color: #3b82f6;
    transform: scale(1.05);
}

.step-title {
    font-size: 20px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 12px;
}

.step-description {
    color: #6b7280;
    line-height: 1.6;
}

.process-arrow {
    font-size: 32px;
    color: #cbd5e1;
    animation: arrow-bounce 2s ease-in-out infinite;
}

@keyframes arrow-bounce {
    0%,
    100% {
        transform: translateX(0);
    }
    50% {
        transform: translateX(5px);
    }
}

/* CTA Section */
.cta-section {
    padding: 120px 0;
    background: linear-gradient(135deg, #1e293b, #334155);
    position: relative;
    overflow: hidden;
}

.cta-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    position: relative;
    z-index: 2;
}

.cta-content {
    text-align: center;
}

.cta-title {
    font-size: 48px;
    font-weight: 800;
    color: white;
    margin-bottom: 20px;
    line-height: 1.2;
}

.cta-description {
    font-size: 20px;
    color: #cbd5e1;
    margin-bottom: 40px;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.cta-actions {
    display: flex;
    justify-content: center;
}

.cta-btn-primary {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 20px 40px;
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border-radius: 16px;
    font-weight: 700;
    font-size: 18px;
    text-decoration: none;
    transition: all 0.3s ease;
    box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
}

.cta-btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(59, 130, 246, 0.5);
}

.btn-arrow {
    font-size: 20px;
    transition: transform 0.3s ease;
}

.cta-btn-primary:hover .btn-arrow {
    transform: translateX(5px);
}

.cta-decoration {
    position: absolute;
    top: 0;
    right: 0;
    width: 400px;
    height: 400px;
    opacity: 0.1;
    pointer-events: none;
}

.decoration-circle {
    width: 300px;
    height: 300px;
    border: 2px solid white;
    border-radius: 50%;
    position: absolute;
    top: 50px;
    right: 50px;
    animation: rotate 20s linear infinite;
}

.decoration-dots {
    width: 200px;
    height: 200px;
    background-image: radial-gradient(circle, white 2px, transparent 2px);
    background-size: 20px 20px;
    position: absolute;
    top: 100px;
    right: 100px;
    border-radius: 50%;
    animation: rotate 15s linear infinite reverse;
}

@keyframes rotate {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
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

.footer-subtitle {
    font-size: 14px;
    color: #9ca3af;
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

/* Responsive Design - IMPROVED MOBILE VISUAL */
@media (max-width: 1024px) {
    .hero-container {
        grid-template-columns: 1fr;
        gap: 40px;
        text-align: center;
    }

    .hero-content {
        order: 2;
    }

    .hero-visual {
        order: 1;
    }

    .hero-title {
        font-size: 40px;
    }

    .phone-mockup {
        width: 280px;
        height: 560px;
        margin: 0 auto;
    }

    .process-flow {
        flex-direction: column;
        gap: 60px;
    }

    .process-arrow {
        transform: rotate(90deg);
    }

    .bg-orb-1,
    .bg-orb-2,
    .bg-orb-3 {
        opacity: 0.15;
    }

    .visual-background {
        top: -50px;
        left: -50px;
        right: -50px;
        bottom: -50px;
    }
}

@media (max-width: 768px) {
    .mobile-menu-toggle {
        display: flex;
    }

    .mobile-close-btn {
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

    .hero-section {
        padding: 40px 0 60px;
    }

    .hero-container {
        gap: 30px;
        padding: 0 16px;
    }

    .hero-title {
        font-size: 28px;
        line-height: 1.3;
    }

    .hero-description {
        font-size: 16px;
        margin: 0 auto;
    }

    .hero-actions {
        flex-direction: column;
        gap: 12px;
    }

    .cta-primary,
    .cta-secondary {
        width: 100%;
        justify-content: center;
        padding: 14px 24px;
    }

    .section-title {
        font-size: 28px;
    }

    .time-container {
        flex-direction: column;
        gap: 20px;
        padding: 24px 16px;
    }

    .current-time {
        font-size: 32px;
    }

    .cta-title {
        font-size: 28px;
    }

    .footer-container {
        flex-direction: column;
        gap: 24px;
        text-align: center;
    }

    /* MOBILE PHONE MOCKUP - BETTER PROPORTIONS */
    .phone-mockup {
        width: 240px;
        height: 480px;
        padding: 12px;
        margin: 0 auto;
    }

    .phone-screen {
        border-radius: 24px;
    }

    .qr-scanner-ui {
        padding: 16px;
    }

    .scanner-header {
        margin-bottom: 20px;
    }

    .scanner-title {
        font-size: 18px;
    }

    .scanner-status {
        padding: 4px 10px;
        font-size: 11px;
    }

    .scanner-frame {
        width: 160px;
        height: 160px;
    }

    .qr-code {
        width: 100px;
        height: 100px;
    }

    .scanner-info {
        margin-top: 16px;
    }

    .location-indicator {
        padding: 8px 16px;
        font-size: 12px;
    }

    /* MOBILE VISUAL BACKGROUND - SIMPLIFIED */
    .floating-icon {
        font-size: 16px;
        opacity: 0.08;
    }

    .bg-circle-1,
    .bg-circle-2,
    .bg-circle-3 {
        opacity: 0.06;
    }

    .bg-orb-1,
    .bg-orb-2,
    .bg-orb-3 {
        opacity: 0.1;
        filter: blur(30px);
    }

    .grid-pattern {
        opacity: 0.15;
        background-size: 30px 30px;
    }

    .bg-pattern {
        opacity: 0.3;
        background-size:
            400px 400px,
            300px 300px,
            60px 60px;
    }
}

@media (max-width: 480px) {
    .navbar-container {
        padding: 0 16px;
        height: 70px;
    }

    .brand-title {
        font-size: 16px;
    }

    .brand-logo {
        width: 40px;
        height: 40px;
    }

    .login-btn {
        padding: 8px 16px;
        font-size: 14px;
        gap: 6px;
    }

    .hero-section {
        padding: 20px 0 40px;
    }

    .section-container {
        padding: 0 16px;
    }

    .hero-title {
        font-size: 24px;
        line-height: 1.4;
    }

    .hero-description {
        font-size: 15px;
    }

    .hero-stats {
        gap: 16px;
    }

    .stat-number {
        font-size: 20px;
    }

    .stat-label {
        font-size: 12px;
    }

    /* EXTRA SMALL MOBILE PHONE */
    .phone-mockup {
        width: 200px;
        height: 400px;
        padding: 10px;
    }

    .phone-screen {
        border-radius: 20px;
    }

    .qr-scanner-ui {
        padding: 12px;
    }

    .scanner-header {
        margin-bottom: 16px;
    }

    .scanner-title {
        font-size: 16px;
    }

    .scanner-frame {
        width: 120px;
        height: 120px;
    }

    .qr-code {
        width: 80px;
        height: 80px;
    }

    .corner {
        width: 16px;
        height: 16px;
        border-width: 2px;
    }

    .scan-line {
        height: 2px;
    }

    .time-container {
        padding: 20px 16px;
        margin: 0 16px;
    }

    .current-time {
        font-size: 24px;
    }

    .current-date {
        font-size: 13px;
    }

    .section-title {
        font-size: 22px;
    }

    .section-subtitle {
        font-size: 16px;
    }

    .cta-title {
        font-size: 20px;
    }

    .cta-description {
        font-size: 16px;
    }

    .cta-btn-primary {
        padding: 14px 20px;
        font-size: 15px;
    }

    /* HIDE COMPLEX ANIMATIONS ON SMALL SCREENS */
    .floating-icon {
        display: none;
    }

    .bg-pattern {
        opacity: 0.2;
    }

    .bg-orb-1,
    .bg-orb-2,
    .bg-orb-3 {
        display: none;
    }

    .grid-pattern {
        display: none;
    }

    .bg-circle-1,
    .bg-circle-2,
    .bg-circle-3 {
        display: none;
    }

    /* SIMPLIFIED HERO BACKGROUND FOR PERFORMANCE */
    .hero-background {
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    }

    .visual-background {
        display: none;
    }

    /* MAINTAIN VISUAL HIERARCHY */
    .hero-visual {
        position: relative;
        z-index: 2;
    }

    .phone-mockup {
        box-shadow:
            0 10px 30px rgba(0, 0, 0, 0.2),
            0 4px 15px rgba(0, 0, 0, 0.1);
        animation: none;
    }
}
</style>
