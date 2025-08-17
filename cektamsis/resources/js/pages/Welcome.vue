<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const activeMenu = ref('beranda');
const isOpen = ref(false); // state untuk toggle menu

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

    // Navbar scroll effect with null safety
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
</script>

<template>
    <div class="welcome-page">
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
                        <Link href="/kontak" :class="{ active: activeMenu === 'siswa' }" @click="setActiveMenu('siswa')">
                            <span class="nav-icon">📥</span> Kontak
                        </Link>
                    </li>
                </ul>
            </div>

            <div class="auth-buttons">
                <Link href="/login" class="btn-login"> <span class="auth-icon">🔑</span> Login </Link>
                <Link href="/register" class="btn-register"> <span class="auth-icon">📋</span> Register </Link>
            </div>

            <!-- Mobile menu toggle -->
            <div class="mobile-menu-toggle">
                <span></span>
                <span></span>
                <span></span>
            </div>
        </nav>

        <!-- Main Container -->
        <div class="main-container">
            <div class="breadcrumb animate-on-scroll"><span class="breadcrumb-icon">🏠</span> Beranda / Beranda</div>
            <h1 class="page-title animate-on-scroll">
                Sistem Absensi Digital
                <span class="title-subtitle">SMK Tamansiswa 2 Jakarta</span>
            </h1>

            <div class="content">
                <!-- Welcome Banner -->
                <div class="banner animate-on-scroll">
                    <div class="banner-overlay"></div>
                    <div class="banner-content">
                        <div class="welcome-text">
                            <h2>Selamat Datang di Sistem Absensi Digital</h2>
                            <p>Kelola absensi siswa dan mata pelajaran dengan mudah, cepat, dan akurat</p>
                            <div class="current-time">
                                <span class="time-icon">🕒</span>
                                <span class="time-text">{{
                                    new Date().toLocaleString('id-ID', {
                                        weekday: 'long',
                                        year: 'numeric',
                                        month: 'long',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit',
                                    })
                                }}</span>
                            </div>
                        </div>
                        <div class="quick-actions">
                            <Link href="/login" class="quick-btn primary">
                                <span class="quick-icon">✅</span>
                                <span>Absensi Sekarang</span>
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Main Content Grid -->
                <div class="content-grid animate-on-scroll">
                    <div class="left-content"></div>
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

.welcome-page {
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

/* Staggered animation delays */
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

.mobile-menu-toggle {
    display: none;
    flex-direction: column;
    cursor: pointer;
    gap: 4px;
}

.mobile-menu-toggle span {
    width: 25px;
    height: 3px;
    background: #2563eb;
    border-radius: 2px;
    transition: all 0.3s ease;
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
    gap: 32px;
}

/* Banner */
.banner {
    height: 300px;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    border-radius: 20px;
    width: 100%;
    display: flex;
    align-items: center;
    padding: 40px;
    box-shadow: 0 20px 40px rgba(37, 99, 235, 0.3);
    position: relative;
    overflow: hidden;
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><circle cx="20" cy="20" r="2" fill="white" opacity="0.1"/><circle cx="80" cy="40" r="1.5" fill="white" opacity="0.1"/><circle cx="40" cy="80" r="1" fill="white" opacity="0.1"/></svg>');
    border-radius: 20px;
}

.banner-content {
    position: relative;
    z-index: 2;
    color: white;
    width: 100%;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.welcome-text h2 {
    font-size: 2.2rem;
    font-weight: 700;
    margin-bottom: 16px;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.welcome-text p {
    font-size: 1.1rem;
    margin-bottom: 20px;
    opacity: 0.95;
    text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
}

.current-time {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 500;
}

.time-icon {
    font-size: 18px;
}

.quick-actions {
    display: flex;
    gap: 16px;
    flex-direction: column;
}

.quick-btn {
    padding: 14px 24px;
    border-radius: 12px;
    text-decoration: none;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 10px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    min-width: 180px;
    justify-content: center;
}

.quick-btn.primary {
    background: white;
    color: #2563eb;
    box-shadow: 0 4px 15px rgba(255, 255, 255, 0.3);
}

.quick-btn.primary:hover {
    background: #f8fafc;
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(255, 255, 255, 0.4);
}

.quick-btn.secondary {
    background: rgba(255, 255, 255, 0.2);
    color: white;
    border: 1px solid rgba(255, 255, 255, 0.3);
    backdrop-filter: blur(10px);
}

.quick-btn.secondary:hover {
    background: rgba(255, 255, 255, 0.3);
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
}

.quick-icon {
    font-size: 18px;
}

/* Stats Section */
.stats-section {
    margin: 20px 0;
}

.section-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.section-icon {
    font-size: 24px;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
}

.stat-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    gap: 16px;
}

.stat-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.15);
}

.stat-icon {
    font-size: 2.5rem;
    width: 60px;
    height: 60px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.stat-card.total .stat-icon {
    background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
}

.stat-card.present .stat-icon {
    background: linear-gradient(135deg, #d1fae5, #a7f3d0);
}

.stat-card.permission .stat-icon {
    background: linear-gradient(135deg, #dbeafe, #bfdbfe);
}

.stat-card.sick .stat-icon {
    background: linear-gradient(135deg, #fed7d7, #fbb6ce);
}

.stat-card.absent .stat-icon {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
}

.stat-info {
    flex: 1;
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
}

.stat-label {
    font-size: 0.9rem;
    color: #64748b;
    font-weight: 500;
    margin-top: 4px;
}

.stat-percentage {
    font-size: 0.8rem;
    color: #10b981;
    font-weight: 600;
    margin-top: 2px;
}

.stat-card.absent .stat-percentage {
    color: #ef4444;
}

/* Content Grid */
.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 32px;
    margin-top: 20px;
}

/* Features Section */
.features-section {
    margin-bottom: 32px;
}

.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.feature-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-align: center;
}

.feature-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.feature-icon {
    font-size: 3rem;
    margin-bottom: 16px;
    display: block;
}

.feature-card h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 12px;
}

.feature-card p {
    color: #64748b;
    line-height: 1.6;
    margin-bottom: 16px;
    font-size: 14px;
}

.feature-link {
    color: #2563eb;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    transition: color 0.3s ease;
}

.feature-link:hover {
    color: #1d4ed8;
}

/* Activity Section */
.activity-section {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
}

.activity-list {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.activity-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.activity-item:hover {
    background: #f1f5f9;
    transform: translateX(4px);
}

.activity-time {
    font-weight: 700;
    color: #2563eb;
    font-size: 14px;
    min-width: 50px;
}

.activity-info {
    flex: 1;
}

.activity-name {
    font-weight: 600;
    color: #1e293b;
    font-size: 15px;
}

.activity-class {
    font-size: 13px;
    color: #64748b;
    margin-top: 2px;
}

.activity-status {
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 4px;
}

.activity-status.hadir {
    background: #dcfce7;
    color: #16a34a;
}

.activity-status.terlambat {
    background: #fef3c7;
    color: #d97706;
}

.activity-status.sakit {
    background: #fee2e2;
    color: #dc2626;
}

.activity-footer {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid #e2e8f0;
    text-align: center;
}

.btn-view-all {
    color: #2563eb;
    text-decoration: none;
    font-weight: 600;
    font-size: 14px;
    padding: 8px 16px;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-view-all:hover {
    background: rgba(37, 99, 235, 0.1);
    color: #1d4ed8;
}

/* Sidebar */
.right-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.sidebar-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
}

.sidebar-title {
    font-size: 1.1rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}

.sidebar-icon {
    font-size: 18px;
}

/* Quick Actions List */
.quick-actions-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.quick-action-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    color: #475569;
    text-decoration: none;
    border-radius: 10px;
    transition: all 0.3s ease;
    font-weight: 500;
    font-size: 14px;
}

.quick-action-item:hover {
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(59, 130, 246, 0.05));
    color: #2563eb;
    transform: translateX(4px);
}

.action-icon {
    font-size: 16px;
    width: 20px;
    text-align: center;
}

/* System Info */
.system-info {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-item {
    padding: 12px;
    background: #f8fafc;
    border-radius: 10px;
    border-left: 3px solid #2563eb;
}

.info-item strong {
    color: #1e293b;
    display: block;
    margin-bottom: 4px;
    font-size: 13px;
}

.info-item p {
    color: #64748b;
    font-size: 13px;
    margin: 0;
    font-weight: 500;
}

/* Help List */
.help-list {
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.help-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 12px;
    color: #475569;
    text-decoration: none;
    border-radius: 8px;
    transition: all 0.3s ease;
    font-size: 14px;
    font-weight: 500;
}

.help-item:hover {
    background: #f1f5f9;
    color: #2563eb;
}

.help-icon {
    font-size: 16px;
    width: 18px;
    text-align: center;
}

/* Footer */
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

/* Responsive Design */
@media (max-width: 1200px) {
    .content-grid {
        grid-template-columns: 1fr;
    }

    .right-sidebar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
    }
}

@media (max-width: 1024px) {
    .nav-links {
        gap: 24px;
    }

    .banner-content {
        flex-direction: column;
        text-align: center;
        gap: 24px;
    }

    .quick-actions {
        flex-direction: row;
        justify-content: center;
    }

    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    }

    .features-grid {
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
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

    .banner {
        height: auto;
        min-height: 250px;
        padding: 24px;
    }

    .welcome-text h2 {
        font-size: 1.8rem;
    }

    .welcome-text p {
        font-size: 1rem;
    }

    .quick-actions {
        flex-direction: column;
        width: 100%;
        gap: 12px;
    }

    .quick-btn {
        min-width: auto;
        width: 100%;
        max-width: 250px;
    }

    .page-title {
        font-size: 2rem;
    }

    .stats-grid {
        grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
        gap: 16px;
    }

    .stat-card {
        padding: 16px;
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }

    .stat-icon {
        width: 50px;
        height: 50px;
        font-size: 2rem;
    }

    .stat-number {
        font-size: 1.5rem;
    }

    .features-grid {
        grid-template-columns: 1fr;
        gap: 16px;
    }

    .activity-item {
        flex-direction: column;
        align-items: flex-start;
        gap: 8px;
    }

    .activity-time {
        min-width: auto;
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

    .banner {
        padding: 20px 16px;
    }

    .welcome-text h2 {
        font-size: 1.5rem;
        margin-bottom: 12px;
    }

    .welcome-text p {
        font-size: 0.9rem;
        margin-bottom: 16px;
    }

    .current-time {
        padding: 10px 16px;
        font-size: 13px;
    }

    .page-title {
        font-size: 1.75rem;
    }

    .title-subtitle {
        font-size: 1rem;
    }

    .section-title {
        font-size: 1.25rem;
    }

    .stats-grid {
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }

    .stat-card {
        padding: 12px;
    }

    .stat-icon {
        width: 40px;
        height: 40px;
        font-size: 1.5rem;
    }

    .stat-number {
        font-size: 1.25rem;
    }

    .sidebar-card,
    .activity-section,
    .feature-card {
        padding: 16px;
    }

    .mobile-menu-toggle {
        display: flex;
    }
}

/* Loading and Hover Effects */
@keyframes slideInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes pulse {
    0%,
    100% {
        opacity: 1;
        transform: scale(1);
    }
    50% {
        opacity: 0.9;
        transform: scale(1.05);
    }
}

.banner-content h2 {
    animation: slideInUp 1s ease-out 0.3s both;
}

.banner-content p {
    animation: slideInUp 1s ease-out 0.5s both;
}

.current-time {
    animation: slideInUp 1s ease-out 0.7s both;
}

.quick-actions {
    animation: slideInUp 1s ease-out 0.9s both;
}

.logo:hover {
    animation: pulse 1.5s infinite;
}

/* Accessibility */
.btn-login:focus,
.btn-register:focus,
.quick-btn:focus,
.feature-link:focus,
.help-item:focus {
    outline: 2px solid #2563eb;
    outline-offset: 2px;
}

.nav-links li a:focus {
    outline: 2px solid #2563eb;
    outline-offset: 2px;
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Custom scrollbar */
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
