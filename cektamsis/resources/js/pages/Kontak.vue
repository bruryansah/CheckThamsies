<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const activeMenu = ref('kontak');
const isMobileMenuOpen = ref(false);

const form = useForm({
    nama: '',
    email: '',
    subject: '',
    pesan: '',
});

const submitMessage = ref('');

function setActiveMenu(menu: string) {
    activeMenu.value = menu;
    isMobileMenuOpen.value = false;
}

function toggleMobileMenu() {
    isMobileMenuOpen.value = !isMobileMenuOpen.value;
}

function submitForm() {
    if (!form.nama || !form.email || !form.pesan) {
        submitMessage.value = 'Mohon lengkapi semua field yang wajib diisi.';
        return;
    }

    submitMessage.value = '';

    form.post(route('kontak.store'), {
        onSuccess: () => {
            form.reset();
            submitMessage.value = 'Pesan Anda berhasil dikirim! Tim kami akan merespons dalam 24 jam.';

            setTimeout(() => {
                submitMessage.value = '';
            }, 5000);
        },
        onError: () => {
            submitMessage.value = 'Terjadi kesalahan saat mengirim pesan. Silakan coba lagi.';
        },
    });
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
// Contact info data
const contactInfo = ref([
    {
        icon: '📍',
        title: 'Alamat Sekolah',
        content: 'Jl. Garuda No. 44, Kemayoran\nJakarta Pusat 10450\nDKI Jakarta, Indonesia',
        color: 'from-blue-500 to-cyan-500',
    },
    {
        icon: '📞',
        title: 'Telepon',
        content: '(021) 4208347',
        color: 'from-green-500 to-emerald-500',
    },
    {
        icon: '✉️',
        title: 'Email',
        content: 'info@smktamansiswa2jkt.sch.id',
        color: 'from-purple-500 to-pink-500',
    },
    {
        icon: '🌐',
        title: 'Website',
        content: 'www.smktamansiswa2jakarta.sch.id\nSistem Absensi Digital',
        color: 'from-orange-500 to-red-500',
    },
]);

const supportOptions = ref([
    {
        icon: '🎧',
        title: 'Live Chat',
        description: 'Bantuan real-time melalui chat',
        action: 'Mulai Chat',
        color: 'from-blue-500 to-cyan-500',
    },
    {
        icon: '📧',
        title: 'Email Support',
        description: 'Kirim email untuk bantuan teknis',
        action: 'Kirim Email',
        color: 'from-green-500 to-emerald-500',
    },
    {
        icon: '📞',
        title: 'Telepon',
        description: 'Hubungi langsung tim support',
        action: 'Telepon Sekarang',
        color: 'from-purple-500 to-pink-500',
    },
]);
</script>

<template>
    <div class="contact-page">
        <Head title="Kontak - Sistem Absensi Digital SMK Tamansiswa 2 Jakarta" />

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
                        <span class="breadcrumb-active">Kontak</span>
                    </div>

                    <h1 class="page-title">Hubungi <span class="text-gradient">Tim Kami</span></h1>

                    <p class="page-description">
                        Ada pertanyaan tentang sistem absensi digital? Tim support kami siap membantu Anda dengan respons cepat dan solusi terbaik.
                    </p>
                </div>
            </section>

            <!-- Contact Form & Info Section -->
            <section class="contact-section animate-on-scroll">
                <div class="section-container">
                    <div class="contact-grid">
                        <!-- Contact Form -->
                        <div class="form-section">
                            <div class="form-card">
                                <div class="form-header">
                                    <div class="form-title">
                                        <span class="form-icon">✉️</span>
                                        <h2>Kirim Pesan</h2>
                                    </div>
                                    <p class="form-subtitle">Sampaikan pertanyaan, saran, atau keluhan Anda. Kami akan merespons dengan cepat.</p>
                                </div>

                                <!-- Message Display -->
                                <div
                                    v-if="submitMessage"
                                    :class="[
                                        'form-message',
                                        {
                                            success: submitMessage.includes('berhasil'),
                                            error: !submitMessage.includes('berhasil'),
                                        },
                                    ]"
                                >
                                    <div class="message-icon">
                                        <span v-if="submitMessage.includes('berhasil')">✅</span>
                                        <span v-else>❌</span>
                                    </div>
                                    <div class="message-content">{{ submitMessage }}</div>
                                </div>

                                <!-- Contact Form -->
                                <form @submit.prevent="submitForm" class="contact-form">
                                    <div class="form-row">
                                        <div class="form-group">
                                            <label for="nama">Nama Lengkap *</label>
                                            <input type="text" id="nama" v-model="form.nama" placeholder="Masukkan nama lengkap Anda" required />
                                            <div v-if="form.errors?.nama" class="error-text">
                                                {{ form.errors.nama }}
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label for="email">Alamat Email *</label>
                                            <input type="email" id="email" v-model="form.email" placeholder="nama@email.com" required />
                                            <div v-if="form.errors?.email" class="error-text">
                                                {{ form.errors.email }}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="subject">Subjek Pesan</label>
                                        <input type="text" id="subject" v-model="form.subject" placeholder="Masukkan subjek pesan Anda" />
                                        <div v-if="form.errors?.subject" class="error-text">
                                            {{ form.errors.subject }}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="pesan">Pesan Anda *</label>
                                        <textarea
                                            id="pesan"
                                            v-model="form.pesan"
                                            rows="6"
                                            placeholder="Tuliskan pesan, pertanyaan, atau keluhan Anda di sini..."
                                            required
                                        ></textarea>
                                        <div v-if="form.errors?.pesan" class="error-text">
                                            {{ form.errors.pesan }}
                                        </div>
                                    </div>

                                    <button type="submit" class="submit-btn" :disabled="form.processing">
                                        <span v-if="!form.processing" class="btn-content">
                                            <span class="btn-icon">📤</span>
                                            <span>Kirim Pesan</span>
                                            <span class="btn-arrow">→</span>
                                        </span>
                                        <span v-else class="btn-content">
                                            <span class="loading-spinner"></span>
                                            <span>Mengirim...</span>
                                        </span>
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Contact Information -->
                        <div class="info-section">
                            <!-- Contact Details -->
                            <div class="info-card contact-info">
                                <div class="card-header">
                                    <h3 class="card-title">
                                        <span class="title-icon">📍</span>
                                        Informasi Kontak
                                    </h3>
                                    <p class="card-subtitle">Berbagai cara untuk menghubungi kami</p>
                                </div>

                                <div class="contact-items">
                                    <div v-for="(item, index) in contactInfo" :key="index" class="contact-item">
                                        <div :class="`contact-icon bg-gradient-to-br ${item.color}`">
                                            {{ item.icon }}
                                        </div>
                                        <div class="contact-content">
                                            <h4 class="contact-title">{{ item.title }}</h4>
                                            <p class="contact-text">{{ item.content }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
.contact-page {
    min-height: 100vh;
    background: #ffffff;
    overflow-x: hidden;
}

/* Navigation - Same as other pages */
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

/* Page Header */
.page-header {
    padding: 80px 0 60px;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.page-header::before {
    content: '';
    position: absolute;
    top: 0;
    right: 0;
    width: 300px;
    height: 300px;
    background: radial-gradient(circle, rgba(59, 130, 246, 0.1), transparent);
    border-radius: 50%;
    animation: float 6s ease-in-out infinite;
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

.header-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 24px;
    position: relative;
    z-index: 2;
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
    max-width: 700px;
    margin: 0 auto 40px;
}

.support-stats {
    display: flex;
    justify-content: center;
    gap: 40px;
    margin-top: 40px;
}

.stat-item {
    text-align: center;
    padding: 20px;
    background: white;
    border-radius: 16px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    min-width: 120px;
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
    margin-top: 8px;
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
    margin-bottom: 60px;
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

/* Contact Section */
.contact-section {
    padding: 100px 0;
    background: white;
}

.contact-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 60px;
    align-items: start;
}

/* Form Section */
.form-section {
    width: 100%;
}

.form-card {
    background: white;
    border: 2px solid #f3f4f6;
    border-radius: 24px;
    padding: 40px;
    transition: all 0.3s ease;
}

.form-card:hover {
    border-color: #e5e7eb;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
}

.form-header {
    margin-bottom: 32px;
}

.form-title {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
}

.form-title h2 {
    font-size: 28px;
    font-weight: 800;
    color: #1f2937;
}

.form-icon {
    font-size: 32px;
}

.form-subtitle {
    font-size: 16px;
    color: #6b7280;
    line-height: 1.6;
}

/* Message Display */
.form-message {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 32px;
    font-weight: 500;
}

.form-message.success {
    background: linear-gradient(135deg, #dcfce7, #bbf7d0);
    color: #16a34a;
    border: 1px solid #86efac;
}

.form-message.error {
    background: linear-gradient(135deg, #fee2e2, #fecaca);
    color: #dc2626;
    border: 1px solid #f87171;
}

.message-icon {
    font-size: 20px;
}

.message-content {
    flex: 1;
    font-size: 14px;
}

/* Contact Form */
.contact-form {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    display: flex;
    color: black;
    flex-direction: column;
    gap: 8px;
}

.form-group label {
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.form-group input,
.form-group textarea {
    padding: 14px 16px;
    border: 2px solid #e5e7eb;
    border-radius: 12px;
    font-size: 15px;
    transition: all 0.3s ease;
    background: #fafbfc;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: #9ca3af;
}

.form-group textarea {
    resize: vertical;
    min-height: 140px;
    font-family: inherit;
}

.error-text {
    color: #dc2626;
    font-size: 13px;
    font-weight: 500;
}

/* Submit Button */
.submit-btn {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    border: none;
    padding: 18px 32px;
    border-radius: 16px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin-top: 8px;
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.3);
}

.submit-btn:hover:not(:disabled) {
    transform: translateY(-3px);
    box-shadow: 0 15px 40px rgba(59, 130, 246, 0.4);
}

.submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

.btn-content {
    display: flex;
    align-items: center;
    gap: 8px;
}

.btn-icon {
    font-size: 18px;
}

.btn-arrow {
    font-size: 16px;
    transition: transform 0.3s ease;
}

.submit-btn:hover .btn-arrow {
    transform: translateX(4px);
}

.loading-spinner {
    width: 18px;
    height: 18px;
    border: 2px solid transparent;
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}

/* Info Section */
.info-section {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.info-card {
    background: white;
    border: 2px solid #f3f4f6;
    border-radius: 20px;
    padding: 32px;
    transition: all 0.3s ease;
}

.info-card:hover {
    border-color: #e5e7eb;
    box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
    transform: translateY(-4px);
}

.card-header {
    margin-bottom: 24px;
}

.card-title {
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 22px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 8px;
}

.title-icon {
    font-size: 24px;
}

.card-subtitle {
    color: #6b7280;
    font-size: 14px;
}

/* Contact Items */
.contact-items {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 16px;
    background: #f8fafc;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.contact-item:hover {
    background: #f1f5f9;
    transform: translateX(4px);
}

.contact-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    flex-shrink: 0;
}

.contact-content {
    flex: 1;
}

.contact-title {
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

.contact-text {
    font-size: 14px;
    color: #6b7280;
    line-height: 1.5;
    white-space: pre-line;
}

/* Schedule Card */
.schedule-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.schedule-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 16px;
    background: #f8fafc;
    border-radius: 10px;
    transition: all 0.3s ease;
}

.schedule-item:hover {
    background: #f1f5f9;
}

.schedule-day {
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}

.schedule-time {
    font-weight: 600;
    font-size: 14px;
    padding: 4px 8px;
    border-radius: 6px;
}

.schedule-time.active {
    color: #16a34a;
    background: rgba(34, 197, 94, 0.1);
}

.schedule-time.closed {
    color: #dc2626;
    background: rgba(239, 68, 68, 0.1);
}

.schedule-note {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-top: 20px;
    padding: 14px 16px;
    background: linear-gradient(135deg, rgba(59, 130, 246, 0.05), rgba(37, 99, 235, 0.02));
    border-radius: 10px;
    border-left: 3px solid #3b82f6;
}

.note-icon {
    font-size: 18px;
    margin-top: 2px;
}

.schedule-note p {
    font-size: 13px;
    color: #6b7280;
    margin: 0;
    line-height: 1.5;
}

/* Support Options */
.support-options {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.support-option {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.support-option:hover {
    background: #f1f5f9;
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
}

.support-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
    color: white;
    flex-shrink: 0;
}

.support-content {
    flex: 1;
}

.support-title {
    font-size: 16px;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 4px;
}

.support-desc {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 12px;
    line-height: 1.4;
}

.support-btn {
    background: transparent;
    color: #3b82f6;
    border: 2px solid #3b82f6;
    padding: 6px 14px;
    border-radius: 8px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.support-btn:hover {
    background: #3b82f6;
    color: white;
    transform: translateY(-1px);
}

/* Map Section */
.map-section {
    padding: 100px 0;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
}

.map-container {
    background: white;
    border-radius: 24px;
    overflow: hidden;
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
    border: 2px solid #f3f4f6;
}

.map-placeholder {
    height: 400px;
    background: linear-gradient(135deg, #f8fafc, #e2e8f0);
    display: flex;
    align-items: center;
    justify-content: center;
    position: relative;
}

.map-content {
    text-align: center;
    max-width: 500px;
    padding: 40px;
    position: relative;
    z-index: 2;
}

.location-pin {
    font-size: 64px;
    margin-bottom: 20px;
    animation: bounce 2s infinite;
}

@keyframes bounce {
    0%,
    20%,
    50%,
    80%,
    100% {
        transform: translateY(0);
    }
    40% {
        transform: translateY(-10px);
    }
    60% {
        transform: translateY(-5px);
    }
}

.location-title {
    font-size: 24px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 12px;
}

.location-address {
    color: #6b7280;
    margin-bottom: 32px;
    font-size: 16px;
    line-height: 1.6;
}

.map-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}

.map-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 12px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.map-btn.primary {
    background: linear-gradient(135deg, #3b82f6, #1d4ed8);
    color: white;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.map-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
}

.map-btn.secondary {
    background: white;
    color: #374151;
    border-color: #d1d5db;
}

.map-btn.secondary:hover {
    border-color: #3b82f6;
    color: #3b82f6;
    transform: translateY(-2px);
}

/* FAQ Section */
.faq-section {
    padding: 100px 0;
    background: white;
}

.faq-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 32px;
}

.faq-item {
    background: white;
    border: 2px solid #f3f4f6;
    border-radius: 20px;
    padding: 32px;
    transition: all 0.3s ease;
    text-align: center;
}

.faq-item:hover {
    border-color: #3b82f6;
    box-shadow: 0 15px 35px rgba(59, 130, 246, 0.1);
    transform: translateY(-4px);
}

.faq-icon {
    font-size: 48px;
    margin-bottom: 20px;
    display: block;
}

.faq-question {
    font-size: 20px;
    font-weight: 700;
    color: #1f2937;
    margin-bottom: 16px;
    line-height: 1.3;
}

.faq-answer {
    color: #6b7280;
    line-height: 1.6;
    font-size: 15px;
    text-align: left;
}

/* Footer - Same as other pages */
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

/* Responsive Design */
@media (max-width: 1024px) {
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 40px;
    }

    .page-title {
        font-size: 40px;
    }

    .section-title {
        font-size: 36px;
    }

    .support-stats {
        gap: 24px;
    }

    .faq-grid {
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
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

    .page-title {
        font-size: 32px;
    }

    .page-description {
        font-size: 18px;
    }

    .section-title {
        font-size: 32px;
    }

    .support-stats {
        flex-direction: column;
        gap: 16px;
        align-items: center;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 20px;
    }

    .form-card,
    .info-card {
        padding: 24px;
    }

    .map-actions {
        flex-direction: column;
        align-items: center;
    }

    .map-btn {
        width: 100%;
        max-width: 250px;
        justify-content: center;
    }

    .faq-grid {
        grid-template-columns: 1fr;
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

    .page-description {
        font-size: 16px;
    }

    .section-title {
        font-size: 28px;
    }

    .stat-item {
        padding: 16px;
        min-width: 100px;
    }

    .stat-number {
        font-size: 24px;
    }

    .form-card,
    .info-card {
        padding: 20px;
    }

    .contact-item {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }

    .support-option {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }

    .map-content {
        padding: 24px 20px;
    }

    .location-pin {
        font-size: 48px;
    }

    .location-title {
        font-size: 20px;
    }

    .faq-item {
        padding: 24px;
    }

    .faq-icon {
        font-size: 40px;
    }

    .faq-question {
        font-size: 18px;
    }
}

/* Focus & Accessibility */
.submit-btn:focus,
.support-btn:focus,
.map-btn:focus,
.nav-link:focus,
.social-btn:focus,
.mobile-menu-toggle:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

.form-group input:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #3b82f6;
    background: white;
    box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
}

/* Reduced Motion */
@media (prefers-reduced-motion: reduce) {
    .animate-on-scroll {
        transition: none;
    }

    .location-pin {
        animation: none;
    }

    .page-header::before {
        animation: none;
    }

    .nav-menu {
        transition: none;
    }

    .mobile-overlay {
        transition: none;
    }
}

/* Print Styles */
@media print {
    .navbar,
    .footer,
    .mobile-menu-toggle,
    .mobile-overlay,
    .support-card {
        display: none;
    }

    .contact-page {
        background: white;
    }

    .form-card,
    .info-card,
    .faq-item {
        box-shadow: none;
        border: 1px solid #e2e8f0;
        break-inside: avoid;
    }

    .page-title {
        font-size: 24pt;
    }

    .section-title {
        font-size: 18pt;
    }
}
</style>
