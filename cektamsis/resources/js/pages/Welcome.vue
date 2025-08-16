<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { Link } from '@inertiajs/vue3'

const activeMenu = ref('beranda')

function setActiveMenu(menu: string) {
  activeMenu.value = menu
}

// Animation on scroll
onMounted(() => {
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('animate-in')
      }
    })
  }, {
    threshold: 0.1
  })

  document.querySelectorAll('.animate-on-scroll').forEach(el => {
    observer.observe(el)
  })

  // Navbar scroll effect
  const navbar = document.querySelector('.navbar')
  window.addEventListener('scroll', () => {
    if (window.scrollY > 50) {
      navbar.classList.add('scrolled')
    } else {
      navbar.classList.remove('scrolled')
    }
  })
})
</script>

<template>
  <div class="welcome-page">
    <!-- Navbar -->
    <nav class="navbar">
      <div class="navbar-left">
        <img src="https://smktamansiswa2jakarta.sch.id/wp-content/uploads/2022/03/LOGO-TAMSIS-MATERIAL-DESIGN.png" alt="Logo SMK Tamansiswa 2 Jakarta" class="logo" />
        <div class="brand">
          <span class="brand-title">SMK Tamansiswa 2 Jakarta</span>
          <span class="brand-subtitle">"Urip Mung Mampir Ngombe"</span>
        </div>
      </div>

      <div class="nav-center">
        <ul class="nav-links">
          <li>
            <Link href="/" :class="{ active: activeMenu === 'beranda' }" @click="setActiveMenu('beranda')"> Beranda </Link>
          </li>
          <li>
            <Link href="/tentang" :class="{ active: activeMenu === 'tentang' }" @click="setActiveMenu('tentang')"> Tentang </Link>
          </li>
          <li>
            <Link href="/fitur" :class="{ active: activeMenu === 'fitur' }" @click="setActiveMenu('fitur')"> Fitur </Link>
          </li>
          <li>
            <Link href="/kontak" :class="{ active: activeMenu === 'kontak' }" @click="setActiveMenu('kontak')"> Kontak </Link>
          </li>
        </ul>
      </div>

      <div class="auth-buttons">
        <Link href="/login" class="btn-login">Login</Link>
        <Link href="/register" class="btn-register">Register</Link>
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
      <div class="breadcrumb animate-on-scroll">Beranda / {{ activeMenu }}</div>
      <h1 class="page-title animate-on-scroll">{{ activeMenu.charAt(0).toUpperCase() + activeMenu.slice(1) }}</h1>

      <div class="content">
        <!-- Banner with school motto -->
        <div class="banner animate-on-scroll">
          <div class="banner-overlay"></div>
          <div class="banner-content">
            <h2>Selamat Datang di SMK Tamansiswa 2 Jakarta</h2>
            <p>Mendidik dengan Sistem Among, Menciptakan Generasi Mandiri dan Berkarakter</p>
            <div class="banner-actions">
              <Link href="/tentang" class="btn-learn-more">Pelajari Lebih Lanjut</Link>
              <Link href="/register" class="btn-join-now">Daftar Sekarang</Link>
            </div>
          </div>
        </div>

        <!-- Main Content Grid -->
        <div class="content-grid animate-on-scroll">
          <div class="left-content">
            <div class="big-box">
              <h3>Tentang SMK Tamansiswa 2 Jakarta</h3>
              <p>SMK Tamansiswa 2 Jakarta adalah sekolah menengah kejuruan yang berkomitmen untuk menciptakan lulusan yang berkualitas dan berkarakter. Dengan filosofi pendidikan Ki Hadjar Dewantara, kami menerapkan sistem among yang mengutamakan pengembangan potensi siswa secara holistik.</p>
              
              <h4>Keunggulan Kami:</h4>
              <ul>
                <li><strong>Kurikulum Terdepan:</strong> Menggabungkan teori dan praktik industri terkini</li>
                <li><strong>Fasilitas Modern:</strong> Laboratorium dan workshop berstandar industri</li>
                <li><strong>Guru Berpengalaman:</strong> Tenaga pendidik profesional dan bersertifikat</li>
                <li><strong>Kerjasama Industri:</strong> Partnership dengan berbagai perusahaan ternama</li>
                <li><strong>Program Magang:</strong> Pengalaman kerja langsung di dunia industri</li>
              </ul>

              <div class="cta-section">
                <p><strong>Ingin tahu lebih banyak tentang sekolah kami?</strong></p>
                <Link href="/tentang" class="btn-secondary">Lihat Profil Sekolah Lengkap</Link>
              </div>
            </div>

            <!-- News Section -->
            <div class="news-section animate-on-scroll">
              <div class="section-header">
                <h3>Berita & Pengumuman Terbaru</h3>
                <div class="section-line"></div>
              </div>
              <div class="news-grid">
                <div class="news-card">
                  <div class="news-image"></div>
                  <div class="news-content">
                    <div class="news-date">15 Agustus 2024</div>
                    <h4>Pendaftaran Siswa Baru 2024/2025</h4>
                    <p>Pendaftaran siswa baru telah dibuka! Dapatkan kesempatan belajar di SMK terbaik dengan berbagai jurusan unggulan.</p>
                    <Link href="/register" class="news-link">Daftar Sekarang →</Link>
                  </div>
                </div>
                <div class="news-card">
                  <div class="news-image news-image-2"></div>
                  <div class="news-content">
                    <div class="news-date">10 Agustus 2024</div>
                    <h4>Workshop Kewirausahaan</h4>
                    <p>Siswa-siswi SMK Tamansiswa 2 mengikuti workshop kewirausahaan untuk mengembangkan jiwa bisnis dan kreativitas.</p>
                    <Link href="/tentang" class="news-link">Baca Selengkapnya →</Link>
                  </div>
                </div>
                <div class="news-card">
                  <div class="news-image news-image-3"></div>
                  <div class="news-content">
                    <div class="news-date">5 Agustus 2024</div>
                    <h4>Prestasi Lomba Nasional</h4>
                    <p>Siswa kami meraih juara 1 dalam lomba keterampilan teknik nasional. Membanggakan!</p>
                    <Link href="/tentang" class="news-link">Lihat Prestasi →</Link>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <div class="right-sidebar">
            <div class="small-box animate-on-scroll">
              <h3>Informasi Penting</h3>
              <div class="quick-info">
                <div class="info-item">
                  <strong>📚 Tahun Ajaran Baru</strong>
                  <p>Dimulai 15 Juli 2024</p>
                </div>
                <div class="info-item">
                  <strong>📝 Pendaftaran</strong>
                  <p>Masih dibuka hingga akhir Agustus</p>
                </div>
                <div class="info-item">
                  <strong>🏆 Akreditasi</strong>
                  <p>Terakreditasi A</p>
                </div>
                <div class="info-item">
                  <strong>👥 Jumlah Siswa</strong>
                  <p>1.200+ siswa aktif</p>
                </div>
              </div>
            </div>

            <div class="small-box animate-on-scroll">
              <h3>Program Keahlian</h3>
              <ul>
                <li>Teknik Komputer & Jaringan</li>
                <li>Rekayasa Perangkat Lunak</li>
                <li>Multimedia</li>
                <li>Akuntansi & Keuangan</li>
                <li>Administrasi Perkantoran</li>
                <li>Pemasaran</li>
              </ul>
              <div class="cta-small">
                <Link href="/tentang" class="btn-outline-small">Lihat Semua Program →</Link>
              </div>
            </div>

            <div class="small-box animate-on-scroll">
              <h3>Kontak Cepat</h3>
              <div class="contact-info">
                <div class="contact-item">
                  <span class="contact-icon">📍</span>
                  <div>
                    <strong>Alamat</strong>
                    <p>Jl. Raya Pasar Minggu, Jakarta Selatan</p>
                  </div>
                </div>
                <div class="contact-item">
                  <span class="contact-icon">📞</span>
                  <div>
                    <strong>Telepon</strong>
                    <p>(021) 1234-5678</p>
                  </div>
                </div>
                <div class="contact-item">
                  <span class="contact-icon">✉️</span>
                  <div>
                    <strong>Email</strong>
                    <p>info@smktamansiswa2jakarta.sch.id</p>
                  </div>
                </div>
              </div>
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
          <span class="footer-text">© 2024 SMK TAMANSISWA 2 JAKARTA | "Ing Ngarsa Sung Tuladha, Ing Madya Mangun Karsa, Tut Wuri Handayani"</span>
        </div>
        <div class="footer-right">
          <a href="https://facebook.com/smktamansiswa2jakarta" class="social-link" target="_blank" title="Facebook">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="social-icon">
              <path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"></path>
            </svg>
          </a>
          <a href="https://instagram.com/smktamansiswa2jakarta" class="social-link" target="_blank" title="Instagram">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="social-icon">
              <rect x="2" y="2" width="20" height="20" rx="5" ry="5"></rect>
              <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"></path>
              <line x1="17.5" y1="6.5" x2="17.51" y2="6.5"></line>
            </svg>
          </a>
          <a href="https://youtube.com/@smktamansiswa2jakarta375?si=XuMQeADTidosYWLq" class="social-link" target="_blank" title="YouTube">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="social-icon">
              <path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 2A29 29 0 0 0 1 11.75a29 29 0 0 0 .46 5.33A2.78 2.78 0 0 0 3.4 19c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-2 29 29 0 0 0 .46-5.25 29 29 0 0 0-.46-5.33z"></path>
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
    background-color: #f8fafc;
    display: flex;
    flex-direction: column;
}

/* ====== Animations ====== */
.animate-on-scroll {
  opacity: 0;
  transform: translateY(30px);
  transition: opacity 0.8s cubic-bezier(0.4, 0, 0.2, 1), transform 0.8s cubic-bezier(0.4, 0, 0.2, 1);
}

.animate-on-scroll.animate-in {
  opacity: 1;
  transform: translateY(0);
}

/* Staggered animation delays */
.animate-on-scroll:nth-child(1) { transition-delay: 0.1s; }
.animate-on-scroll:nth-child(2) { transition-delay: 0.2s; }
.animate-on-scroll:nth-child(3) { transition-delay: 0.3s; }
.animate-on-scroll:nth-child(4) { transition-delay: 0.4s; }
.animate-on-scroll:nth-child(5) { transition-delay: 0.5s; }

/* ====== Navbar ====== */
.navbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    padding: 16px 24px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    position: sticky;
    top: 0;
    z-index: 100;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.navbar.scrolled {
    padding: 12px 24px;
    background: rgba(255, 255, 255, 0.98);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.navbar-left {
    display: flex;
    align-items: center;
    gap: 16px;
    flex-shrink: 0;
}

.logo {
    width: 45px;
    height: 45px;
    object-fit: contain;
    transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.logo:hover {
    transform: rotate(15deg) scale(1.1);
}

.brand {
    display: flex;
    flex-direction: column;
}

.brand-title {
    font-weight: 700;
    font-size: 19px;
    color: #1e293b;
    line-height: 1.2;
}

.brand-subtitle {
    font-size: 13px;
    color: #64748b;
    font-style: italic;
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
    gap: 40px;
    margin: 0;
    padding: 0;
}

.nav-links li a {
    position: relative;
    color: #64748b;
    font-size: 16px;
    font-weight: 500;
    text-decoration: none;
    padding: 8px 16px;
    border-radius: 8px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.nav-links li a:hover {
    color: #2563eb;
    background: rgba(37, 99, 235, 0.1);
}

.nav-links li a.active {
    color: #2563eb;
    font-weight: 600;
    background: rgba(37, 99, 235, 0.1);
}

.auth-buttons {
    display: flex;
    gap: 12px;
    flex-shrink: 0;
}

.btn-login {
    padding: 10px 20px;
    border: 2px solid #2563eb;
    background: transparent;
    color: #2563eb;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
}

.btn-login:hover {
    background-color: #2563eb;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
}

.btn-register {
    padding: 10px 20px;
    background: linear-gradient(135deg, #2563eb, #1d4ed8);
    color: white;
    border: 2px solid transparent;
    border-radius: 8px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
}

.btn-register:hover {
    background: linear-gradient(135deg, #1d4ed8, #1e40af);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
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
    max-width: 1200px;
    margin: 0 auto;
    width: 100%;
    padding: 0 24px;
}

.breadcrumb {
    font-size: 14px;
    margin: 32px 0 12px 0;
    color: #64748b;
    font-weight: 500;
}

.page-title {
    margin: 0 0 32px 0;
    font-size: 36px;
    font-weight: 800;
    color: #1e293b;
    position: relative;
    display: inline-block;
}

.page-title::after {
    content: '';
    position: absolute;
    bottom: -12px;
    left: 0;
    width: 60px;
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

.banner {
    height: 400px;
    background: url('https://smktamansiswa2jakarta.sch.id/wp-content/uploads/2022/03/gedung-sekolah.jpg');
    background-size: cover;
    background-position: center;
    border-radius: 16px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    text-align: center;
    padding: 40px;
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    position: relative;
    overflow: hidden;
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.8), rgba(29, 78, 216, 0.6));
    border-radius: 16px;
}

.banner-content {
    position: relative;
    z-index: 2;
    max-width: 800px;
}

.banner-content h2 {
    font-size: 3rem;
    font-weight: 800;
    margin-bottom: 1.5rem;
    text-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    line-height: 1.2;
}

.banner-content p {
    font-size: 1.3rem;
    margin-bottom: 2rem;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    opacity: 0.95;
    line-height: 1.5;
}

.banner-actions {
    display: flex;
    gap: 16px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-learn-more, .btn-join-now {
    padding: 12px 32px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 12px;
    text-decoration: none;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-learn-more {
    background: rgba(255, 255, 255, 0.2);
    border: 2px solid rgba(255, 255, 255, 0.5);
    color: white;
    backdrop-filter: blur(10px);
}

.btn-learn-more:hover {
    background: white;
    color: #2563eb;
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.btn-join-now {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: white;
    border: 2px solid transparent;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
}

.btn-join-now:hover {
    background: linear-gradient(135deg, #d97706, #b45309);
    transform: translateY(-2px);
    box-shadow: 0 10px 25px rgba(245, 158, 11, 0.4);
}

.content-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 32px;
}

.left-content .big-box {
    min-height: 400px;
    background: white;
    border-radius: 16px;
    padding: 32px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(226, 232, 240, 0.5);
}

.big-box h3 {
    color: #2563eb;
    margin-bottom: 20px;
    font-size: 1.75rem;
    font-weight: 700;
}

.big-box h4 {
    color: #1e293b;
    margin: 24px 0 16px 0;
    font-size: 1.25rem;
    font-weight: 600;
}

.big-box p, .big-box ul {
    margin-bottom: 20px;
    line-height: 1.7;
    color: #475569;
    font-size: 16px;
}

.big-box ul {
    padding-left: 0;
    list-style: none;
}

.big-box li {
    margin-bottom: 12px;
    padding-left: 20px;
    position: relative;
}

.big-box li::before {
    content: '✓';
    position: absolute;
    left: 0;
    color: #10b981;
    font-weight: bold;
}

.cta-section {
    margin-top: 32px;
    padding: 24px;
    background: linear-gradient(135deg, rgba(37, 99, 235, 0.05), rgba(59, 130, 246, 0.05));
    border-radius: 12px;
    border-left: 4px solid #2563eb;
}

.btn-secondary {
    display: inline-block;
    margin-top: 16px;
    padding: 12px 24px;
    background: linear-gradient(135deg, #2563eb, #3b82f6);
    color: white;
    text-decoration: none;
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
}

.btn-secondary:hover {
    background: linear-gradient(135deg, #1d4ed8, #2563eb);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
}

.news-section {
    margin-top: 40px;
}

.section-header {
    margin-bottom: 32px;
}

.section-header h3 {
    color: #1e293b;
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 8px;
}

.section-line {
    width: 80px;
    height: 4px;
    background: linear-gradient(90deg, #2563eb, #3b82f6);
    border-radius: 2px;
}

.news-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
    gap: 24px;
}

.news-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(226, 232, 240, 0.5);
}

.news-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
}

.news-image {
    height: 180px;
    background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
    background-size: cover;
    background-position: center;
}

.news-card:nth-child(1) .news-image {
    background-image: linear-gradient(rgba(37, 99, 235, 0.1), rgba(37, 99, 235, 0.1)), url('https://smktamansiswa2jakarta.sch.id/wp-content/uploads/2023/05/ppdb-2023.jpg');
}

.news-image-2 {
    background-image: linear-gradient(rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.1)), url('https://smktamansiswa2jakarta.sch.id/wp-content/uploads/2023/04/workshop-kewirausahaan.jpg');
}

.news-image-3 {
    background-image: linear-gradient(rgba(245, 158, 11, 0.1), rgba(245, 158, 11, 0.1)), url('https://via.placeholder.com/400x200/f59e0b/ffffff?text=Prestasi');
}

.news-content {
    padding: 20px;
}

.news-date {
    color: #64748b;
    font-size: 13px;
    font-weight: 500;
    margin-bottom: 8px;
}

.news-card h4 {
    color: #1e293b;
    font-size: 1.125rem;
    font-weight: 600;
    margin-bottom: 12px;
    line-height: 1.4;
}

.news-card p {
    color: #64748b;
    font-size: 14px;
    line-height: 1.6;
    margin-bottom: 16px;
}

.news-link {
    color: #2563eb;
    font-weight: 600;
    font-size: 14px;
    text-decoration: none;
    transition: color 0.3s ease;
}

.news-link:hover {
    color: #1d4ed8;
}

.right-sidebar {
    display: flex;
    flex-direction: column;
    gap: 24px;
}

.small-box {
    background: white;
    border-radius: 16px;
    padding: 24px;
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(226, 232, 240, 0.5);
}

.small-box h3 {
    color: #2563eb;
    margin-bottom: 20px;
    font-size: 1.25rem;
    font-weight: 700;
}

.small-box ul {
    list-style: none;
    padding: 0;
}

.small-box li {
    padding: 12px 0;
    border-bottom: 1px solid #f1f5f9;
    color: #475569;
    font-weight: 500;
    transition: color 0.3s ease;
}

.small-box li:last-child {
    border-bottom: none;
}

.small-box li:hover {
    color: #2563eb;
}

.small-box li::before {
    content: '▸';
    color: #2563eb;
    display: inline-block;
    width: 1em;
    margin-right: 8px;
    font-weight: bold;
}

.quick-info {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.info-item {
    background: linear-gradient(135deg, #f8fafc, #f1f5f9);
    padding: 16px;
    border-radius: 12px;
    border-left: 4px solid #2563eb;
    transition: transform 0.3s ease;
}

.info-item:hover {
    transform: translateX(4px);
}

.info-item strong {
    color: #1e293b;
    display: block;
    margin-bottom: 6px;
    font-size: 14px;
}

.info-item p {
    color: #64748b;
    font-size: 14px;
    margin: 0;
    font-weight: 500;
}

.contact-info {
    display: flex;
    flex-direction: column;
    gap: 16px;
}

.contact-item {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.contact-icon {
    font-size: 18px;
    width: 24px;
    text-align: center;
    flex-shrink: 0;
}

.contact-item strong {
    color: #1e293b;
    font-size: 14px;
    display: block;
    margin-bottom: 4px;
}

.contact-item p {
    color: #64748b;
    font-size: 13px;
    margin: 0;
}

.cta-small {
    margin-top: 20px;
    text-align: center;
}

.btn-outline-small {
    display: inline-block;
    padding: 8px 16px;
    border: 2px solid #2563eb;
    color: #2563eb;
    text-decoration: none;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 600;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.btn-outline-small:hover {
    background: #2563eb;
    color: white;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
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
@media (max-width: 1024px) {
    .nav-links {
        gap: 24px;
    }
    
    .banner-content h2 {
        font-size: 2.5rem;
    }
    
    .banner-content p {
        font-size: 1.1rem;
    }
    
    .content-grid {
        grid-template-columns: 1fr;
    }
    
    .right-sidebar {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 24px;
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
        gap: 16px;
        flex-wrap: wrap;
    }
    
    .nav-links li a {
        padding: 6px 12px;
        font-size: 14px;
    }
    
    .auth-buttons {
        gap: 8px;
    }

    .btn-login, .btn-register {
        padding: 8px 16px;
        font-size: 13px;
    }
    
    .main-container {
        padding: 0 16px;
    }

    .banner {
        height: 300px;
        padding: 20px;
    }
    
    .banner-content h2 {
        font-size: 2rem;
    }

    .banner-content p {
        font-size: 1rem;
    }

    .banner-actions {
        gap: 12px;
    }

    .btn-learn-more, .btn-join-now {
        padding: 10px 20px;
        font-size: 14px;
    }
    
    .page-title {
        font-size: 2rem;
    }
    
    .big-box, .small-box {
        padding: 20px;
    }

    .news-grid {
        grid-template-columns: 1fr;
        gap: 20px;
    }
    
    .footer-content {
        flex-direction: column;
        gap: 24px;
        text-align: center;
    }
    
    .footer-left {
        flex-direction: column;
        max-width: 100%;
        text-align: center;
        gap: 12px;
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

    .nav-links {
        justify-content: center;
    }

    .nav-links li a {
        padding: 4px 8px;
        font-size: 13px;
    }

    .btn-login, .btn-register {
        padding: 6px 12px;
        font-size: 12px;
    }
    
    .banner {
        height: 250px;
        padding: 16px;
    }
    
    .banner-content h2 {
        font-size: 1.5rem;
        margin-bottom: 1rem;
    }
    
    .banner-content p {
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }

    .banner-actions {
        flex-direction: column;
        gap: 8px;
    }

    .btn-learn-more, .btn-join-now {
        padding: 8px 16px;
        font-size: 13px;
        width: 100%;
        max-width: 200px;
    }
    
    .page-title {
        font-size: 1.75rem;
    }

    .section-header h3 {
        font-size: 1.5rem;
    }
    
    .big-box, .small-box {
        padding: 16px;
    }

    .news-grid {
        grid-template-columns: 1fr;
    }

    .news-card {
        margin: 0 -4px;
    }

    .right-sidebar {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .mobile-menu-toggle {
        display: flex;
    }
}

/* ====== Additional Enhancements ====== */
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

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.8;
    }
}

.banner-content h2 {
    animation: fadeInUp 1s ease-out 0.5s both;
}

.banner-content p {
    animation: fadeInUp 1s ease-out 0.7s both;
}

.banner-actions {
    animation: fadeInUp 1s ease-out 0.9s both;
}

.logo:hover {
    animation: pulse 2s infinite;
}

/* Smooth scrolling for the entire page */
html {
    scroll-behavior: smooth;
}

/* Focus states for accessibility */
.btn-login:focus,
.btn-register:focus,
.btn-secondary:focus,
.btn-outline-small:focus {
    outline: 2px solid #2563eb;
    outline-offset: 2px;
}

.nav-links li a:focus {
    outline: 2px solid #2563eb;
    outline-offset: 2px;
    border-radius: 4px;
}
</style>