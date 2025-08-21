<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { onMounted, ref } from 'vue';

const activeMenu = ref('kontak');
const isOpen = ref(false);

// Menggunakan useForm dari Inertia.js untuk form handling
const form = useForm({
    nama: '',
    email: '',
    subject: '',
    pesan: '',
});

const submitMessage = ref('');

function setActiveMenu(menu: string) {
    activeMenu.value = menu;
}

// Fungsi submit menggunakan Inertia.js
function submitForm() {
    // Validasi client-side sederhana
    if (!form.nama || !form.email || !form.pesan) {
        submitMessage.value = 'Mohon lengkapi semua field yang wajib diisi.';
        return;
    }

    // Clear previous messages
    submitMessage.value = '';

    // Submit menggunakan Inertia.js
    form.post(route('kontak.store'), {
        onSuccess: () => {
            form.reset();
            submitMessage.value = 'Pesan Anda berhasil dikirim! Kami akan merespons dalam 1-2 hari kerja.';

            // Clear success message after 5 seconds
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
</script>

<template>
    <div class="contact-page">
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
            <div class="breadcrumb animate-on-scroll"><span class="breadcrumb-icon">🏠</span> Beranda / Kontak</div>
            <h1 class="page-title animate-on-scroll">
                Hubungi Kami
                <span class="title-subtitle">SMK Tamansiswa 2 Jakarta</span>
            </h1>

            <div class="content">
                <!-- Contact Banner -->
                <div class="contact-banner animate-on-scroll">
                    <div class="banner-overlay"></div>
                    <div class="banner-content">
                        <div class="banner-text">
                            <h2>Ada Pertanyaan atau Butuh Bantuan?</h2>
                            <p>Tim kami siap membantu Anda dengan segala pertanyaan seputar sistem absensi digital</p>
                            <div class="contact-highlight">
                                <span class="highlight-icon">📞</span>
                                <span class="highlight-text">Respons Cepat dalam 24 Jam</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Content Grid -->
                <div class="contact-grid animate-on-scroll">
                    <!-- Contact Form -->
                    <div class="contact-form-section">
                        <div class="form-card">
                            <div class="form-header">
                                <h3>
                                    <span class="form-icon">✉️</span>
                                    Kirim Pesan
                                </h3>
                                <p>Sampaikan pertanyaan, saran, atau keluhan Anda kepada kami</p>
                            </div>

                            <!-- Success/Error Message -->
                            <div
                                v-if="submitMessage"
                                class="form-message"
                                :class="{ success: submitMessage.includes('berhasil'), error: !submitMessage.includes('berhasil') }"
                            >
                                {{ submitMessage }}
                            </div>

                            <form @submit.prevent="submitForm" class="contact-form">
                                <div class="form-row">
                                    <div class="form-group">
                                        <label for="nama">Nama Lengkap *</label>
                                        <input type="text" id="nama" v-model="form.nama" placeholder="Masukkan nama lengkap" required />
                                        <div v-if="form.errors.nama" class="error-message">{{ form.errors.nama }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">Email *</label>
                                        <input type="email" id="email" v-model="form.email" placeholder="nama@email.com" required />
                                        <div v-if="form.errors.email" class="error-message">{{ form.errors.email }}</div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="subject">Subjek</label>
                                    <input
                                        type="text"
                                        id="subject"
                                        v-model="form.subject"
                                        placeholder="Masukkan subjek pesan Anda"
                                        class="form-input"
                                    />
                                    <div v-if="form.errors.subject" class="error-message">{{ form.errors.subject }}</div>
                                </div>

                                <div class="form-group">
                                    <label for="pesan">Pesan *</label>
                                    <textarea
                                        id="pesan"
                                        v-model="form.pesan"
                                        placeholder="Tuliskan pesan Anda di sini..."
                                        rows="6"
                                        required
                                    ></textarea>
                                    <div v-if="form.errors.pesan" class="error-message">{{ form.errors.pesan }}</div>
                                </div>

                                <button type="submit" class="submit-btn" :disabled="form.processing">
                                    <span v-if="!form.processing" class="btn-content">
                                        <span class="btn-icon">📤</span>
                                        Kirim Pesan
                                    </span>
                                    <span v-else class="btn-content">
                                        <span class="loading-spinner"></span>
                                        Mengirim...
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="contact-info-section">
                        <!-- Contact Details -->
                        <div class="info-card">
                            <h3>
                                <span class="info-icon">📍</span>
                                Informasi Kontak
                            </h3>

                            <div class="contact-items">
                                <div class="contact-item">
                                    <div class="item-icon">📍</div>
                                    <div class="item-content">
                                        <h4>Alamat Sekolah</h4>
                                        <p>Jl.Garuda No.44 Kemayoran <br />Jakarta Pusat<br />DKI Jakarta, Indonesia</p>
                                    </div>
                                </div>

                                <div class="contact-item">
                                    <div class="item-icon">📞</div>
                                    <div class="item-content">
                                        <h4>Telepon</h4>
                                        <p>(021) 7888-9999<br />(021) 7888-8888</p>
                                    </div>
                                </div>

                                <div class="contact-item">
                                    <div class="item-icon">✉️</div>
                                    <div class="item-content">
                                        <h4>Email</h4>
                                        <p>info@smktamansiswa2jkt.sch.id<br />admin@smktamansiswa2jkt.sch.id</p>
                                    </div>
                                </div>

                                <div class="contact-item">
                                    <div class="item-icon">🌐</div>
                                    <div class="item-content">
                                        <h4>Website</h4>
                                        <p>www.smktamansiswa2jakarta.sch.id</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Office Hours -->
                        <div class="info-card">
                            <h3>
                                <span class="info-icon">🕒</span>
                                Jam Operasional
                            </h3>

                            <div class="hours-list">
                                <div class="hour-item">
                                    <span class="day">Senin - Jumat</span>
                                    <span class="time">07:00 - 16:00 WIB</span>
                                </div>
                                <div class="hour-item">
                                    <span class="day">Sabtu</span>
                                    <span class="time">07:00 - 12:00 WIB</span>
                                </div>
                                <div class="hour-item closed">
                                    <span class="day">Minggu</span>
                                    <span class="time">Tutup</span>
                                </div>
                            </div>

                            <div class="note">
                                <p><strong>Catatan:</strong> Untuk dukungan sistem absensi digital, tim IT tersedia setiap hari kerja.</p>
                            </div>
                        </div>

                        <!-- Quick Support -->
                        <div>
                            <div></div>
                        </div>
                    </div>
                </div>

                <!-- Map Section -->
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

.contact-page {
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

/* ====== Contact Banner ====== */
.contact-banner {
    height: 280px;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    border-radius: 20px;
    width: 100%;
    display: flex;
    align-items: center;
    padding: 40px;
    box-shadow: 0 20px 40px rgba(37, 99, 235, 0.3);
    position: relative;
    overflow: hidden;
    margin-bottom: 32px;
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

.banner-text h2 {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 16px;
    text-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
}

.banner-text p {
    font-size: 1.1rem;
    margin-bottom: 20px;
    opacity: 0.95;
    text-shadow: 0 1px 5px rgba(0, 0, 0, 0.2);
}

.contact-highlight {
    display: flex;
    align-items: center;
    gap: 8px;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 12px 20px;
    border-radius: 12px;
    font-weight: 500;
}

.highlight-icon {
    font-size: 18px;
}

.banner-stats {
    display: flex;
    gap: 24px;
}

.stat-item {
    text-align: center;
    background: rgba(255, 255, 255, 0.2);
    backdrop-filter: blur(10px);
    padding: 20px;
    border-radius: 16px;
    min-width: 100px;
}

.stat-number {
    font-size: 2rem;
    font-weight: 800;
    margin-bottom: 8px;
}

.stat-label {
    font-size: 0.9rem;
    opacity: 0.9;
}

/* ====== Contact Grid ====== */
.contact-grid {
    display: grid;
    grid-template-columns: 1fr 400px;
    gap: 32px;
    margin-bottom: 32px;
}

/* ====== Contact Form ====== */
.form-card {
    background: white;
    border-radius: 20px;
    padding: 32px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
}

.form-header {
    margin-bottom: 24px;
}

.form-header h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.form-icon {
    font-size: 24px;
}

.form-header p {
    color: #64748b;
    font-size: 14px;
}

.form-message {
    padding: 16px;
    border-radius: 12px;
    margin-bottom: 24px;
    font-weight: 500;
}

.form-message.success {
    background: #dcfce7;
    color: #16a34a;
    border: 1px solid #bbf7d0;
}

.form-message.error {
    background: #fee2e2;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.contact-form {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 16px;
}

.form-group {
    display: flex;
    flex-direction: column;
    gap: 8px;
    color: black;
}

.form-group label {
    font-weight: 600;
    color: #1e293b;
    font-size: 14px;
}

.form-group input,
.form-group select,
.form-group textarea {
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-group input::placeholder,
.form-group textarea::placeholder {
    color: #94a3b8;
}

.form-group textarea {
    resize: vertical;
    min-height: 120px;
}

/* Error messages styling */
.error-message {
    color: #dc2626;
    font-size: 12px;
    margin-top: 4px;
    font-weight: 500;
}

.submit-btn {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
    border: none;
    padding: 16px 32px;
    border-radius: 12px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    margin-top: 8px;
}

.submit-btn:hover:not(:disabled) {
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
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
    font-size: 16px;
}

.loading-spinner {
    width: 16px;
    height: 16px;
    border: 2px solid transparent;
    border-top: 2px solid white;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}

/* ====== Contact Info Cards ====== */
.contact-info-section {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.info-card {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
}

.info-card h3 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.info-icon {
    font-size: 20px;
}

.contact-items {
    display: flex;
    flex-direction: column;
    gap: 20px;
}

.contact-item {
    display: flex;
    gap: 16px;
    align-items: flex-start;
}

.item-icon {
    font-size: 20px;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.1), rgba(59, 130, 246, 0.05));
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.item-content h4 {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 6px;
    font-size: 14px;
}

.item-content p {
    color: #64748b;
    font-size: 13px;
    line-height: 1.5;
}

/* Office Hours */
.hours-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.hour-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px;
    background: #f8fafc;
    border-radius: 8px;
    font-size: 14px;
}

.hour-item .day {
    font-weight: 600;
    color: #1e293b;
}

.hour-item .time {
    color: #16a34a;
    font-weight: 600;
}

.hour-item.closed .time {
    color: #dc2626;
}

.note {
    margin-top: 16px;
    padding: 12px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.05), rgba(59, 130, 246, 0.02));
    border-radius: 8px;
    border-left: 3px solid #2563eb;
}

.note p {
    font-size: 13px;
    color: #64748b;
    margin: 0;
}

/* Support Card */
.support-card {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    border: 2px solid rgba(37, 99, 235, 0.1);
}

.support-options {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.support-option {
    display: flex;
    gap: 16px;
    padding: 16px;
    background: white;
    border-radius: 12px;
    transition: all 0.3s ease;
}

.support-option:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
}

.support-icon {
    font-size: 24px;
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.support-content {
    flex: 1;
}

.support-content h4 {
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 6px;
    font-size: 14px;
}

.support-content p {
    color: #64748b;
    font-size: 13px;
    margin-bottom: 12px;
}

.support-btn {
    background: transparent;
    color: #2563eb;
    border: 2px solid #2563eb;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.support-btn:hover {
    background: #2563eb;
    color: white;
    transform: translateY(-1px);
}

/* ====== Map Section ====== */
.map-section {
    margin-top: 32px;
}

.map-header {
    text-align: center;
    margin-bottom: 24px;
}

.map-header h3 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.map-icon {
    font-size: 24px;
}

.map-header p {
    color: #64748b;
    font-size: 14px;
}

.map-container {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    border: 1px solid rgba(226, 232, 240, 0.8);
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
    max-width: 400px;
    padding: 40px;
}

.location-pin {
    font-size: 4rem;
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

.map-content h4 {
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin-bottom: 8px;
}

.map-content p {
    color: #64748b;
    margin-bottom: 24px;
    font-size: 14px;
}

.map-actions {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.map-btn {
    padding: 12px 24px;
    border-radius: 10px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s ease;
    border: none;
}

.map-btn:not(.secondary) {
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
}

.map-btn:not(.secondary):hover {
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
}

.map-btn.secondary {
    background: transparent;
    color: #2563eb;
    border: 2px solid #2563eb;
}

.map-btn.secondary:hover {
    background: #2563eb;
    color: white;
    transform: translateY(-2px);
}

/* ====== Footer ====== */
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
@media (max-width: 1200px) {
    .contact-grid {
        grid-template-columns: 1fr;
        gap: 24px;
    }

    .contact-info-section {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 24px;
    }
}

@media (max-width: 1024px) {
    .banner-content {
        flex-direction: column;
        text-align: center;
        gap: 24px;
    }

    .banner-stats {
        justify-content: center;
    }

    .form-row {
        grid-template-columns: 1fr;
        gap: 20px;
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

    .contact-banner {
        height: auto;
        min-height: 200px;
        padding: 24px 20px;
    }

    .banner-text h2 {
        font-size: 1.5rem;
    }

    .banner-text p {
        font-size: 0.95rem;
    }

    .banner-stats {
        flex-direction: column;
        gap: 12px;
    }

    .stat-item {
        padding: 16px;
    }

    .page-title {
        font-size: 2rem;
    }

    .form-card {
        padding: 24px 20px;
    }

    .contact-info-section {
        grid-template-columns: 1fr;
    }

    .map-content {
        padding: 24px 20px;
    }

    .map-actions {
        flex-direction: column;
        align-items: center;
    }

    .map-btn {
        min-width: 180px;
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

    .contact-banner {
        padding: 20px 16px;
    }

    .banner-text h2 {
        font-size: 1.25rem;
    }

    .page-title {
        font-size: 1.75rem;
    }

    .title-subtitle {
        font-size: 1rem;
    }

    .form-card,
    .info-card {
        padding: 20px 16px;
    }

    .contact-item {
        flex-direction: column;
        gap: 12px;
    }

    .support-option {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }

    .mobile-menu-toggle {
        display: flex;
    }
}

/* ====== Loading and Hover Effects ====== */
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

@keyframes fadeIn {
    from {
        opacity: 0;
    }
    to {
        opacity: 1;
    }
}

.contact-banner {
    animation: slideInUp 1s ease-out 0.2s both;
}

.form-card {
    animation: slideInUp 1s ease-out 0.4s both;
}

.info-card:nth-child(1) {
    animation: slideInUp 1s ease-out 0.5s both;
}

.info-card:nth-child(2) {
    animation: slideInUp 1s ease-out 0.6s both;
}

.info-card:nth-child(3) {
    animation: slideInUp 1s ease-out 0.7s both;
}

.map-section {
    animation: slideInUp 1s ease-out 0.8s both;
}

/* ====== Accessibility ====== */
.submit-btn:focus,
.support-btn:focus,
.map-btn:focus {
    outline: 2px solid #2563eb;
    outline-offset: 2px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
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
