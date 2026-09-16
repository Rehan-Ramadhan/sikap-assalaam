<template>
  <div class="landing-page">

    <!-- =========================
         NAVBAR
    ========================== -->
    <header class="landing-navbar">
      <div class="landing-navbar-inner">

        <!-- Brand -->
        <router-link to="/" class="landing-brand">
          <div class="landing-brand-icon">
            <GraduationCap :size="24" stroke-width="2.3" />
          </div>

          <div class="landing-brand-text">
            <strong>
              SIKAP <span>ASSALAAM</span>
            </strong>

            <small>
              SISTEM INFORMASI KESISWAAN
            </small>
          </div>
        </router-link>

        <!-- Navigation -->
        <nav class="landing-nav">
          <a href="#fitur">Fitur</a>
          <a href="#tentang">Tentang</a>
          <a href="#keunggulan">Keunggulan</a>

          <button
            type="button"
            class="landing-login-button"
            @click="goToLogin"
          >
            <LogIn :size="17" />
            <span>Masuk SIKAP Assalaam</span>
          </button>
        </nav>

      </div>
    </header>


    <!-- =========================
         HERO
    ========================== -->
    <main>

      <section class="hero-section">

        <div class="hero-container">

          <!-- Hero Left -->
          <div class="hero-content">

            <div class="hero-badge">
              <Sparkles :size="15" />
              <span>Sistem Informasi Kesiswaan Modern</span>
            </div>

            <h1>
              Membangun
              <span>Kedisiplinan,</span>
              Mengapresiasi
              <em>Prestasi.</em>
            </h1>

            <p class="hero-description">
              SIKAP Assalaam adalah sistem informasi kesiswaan
              yang membantu sekolah mengelola data siswa,
              kedisiplinan, prestasi, dan pembinaan secara
              terintegrasi.
            </p>

            <div class="hero-actions">

              <button
                type="button"
                class="hero-primary-button"
                @click="goToLogin"
              >
                <span>Masuk ke Sistem</span>
                <ArrowUpRight :size="19" />
              </button>

              <a
                href="#tentang"
                class="hero-secondary-button"
              >
                Pelajari lebih lanjut
                <ArrowDown :size="17" />
              </a>

            </div>

            <div class="hero-trust">

              <div class="trust-item">
                <div class="trust-icon">
                  <ShieldCheck :size="17" />
                </div>

                <span>Data Terintegrasi</span>
              </div>

              <div class="trust-divider"></div>

              <div class="trust-item">
                <div class="trust-icon">
                  <CheckCircle2 :size="17" />
                </div>

                <span>Mudah Digunakan</span>
              </div>

            </div>

          </div>


          <!-- Hero Right / Dashboard Preview -->
          <div class="hero-visual">

            <div class="visual-glow"></div>

            <div class="dashboard-preview">

              <!-- Browser Header -->
              <div class="preview-topbar">

                <div class="preview-dots">
                  <span></span>
                  <span></span>
                  <span></span>
                </div>

                <div class="preview-address">
                  sikap.assalaam.sch.id
                </div>

              </div>


              <!-- Dashboard Content -->
              <div class="preview-body">

                <!-- Mini Sidebar -->
                <aside class="preview-sidebar">

                  <div class="preview-sidebar-logo">
                    <div>
                      <GraduationCap :size="15" />
                    </div>

                    <span>SIKAP</span>
                  </div>

                  <div class="preview-menu active">
                    <LayoutDashboard :size="14" />
                    <span>Dashboard</span>
                  </div>

                  <div class="preview-menu">
                    <Users :size="14" />
                    <span>Siswa</span>
                  </div>

                  <div class="preview-menu">
                    <ClipboardCheck :size="14" />
                    <span>Pelanggaran</span>
                  </div>

                  <div class="preview-menu">
                    <Award :size="14" />
                    <span>Prestasi</span>
                  </div>

                  <div class="preview-menu">
                    <FileText :size="14" />
                    <span>Laporan</span>
                  </div>

                </aside>


                <!-- Dashboard Main -->
                <div class="preview-main">

                  <div class="preview-heading">

                    <div>
                      <span>Dashboard</span>
                      <strong>Selamat Datang</strong>
                    </div>

                    <div class="preview-profile">
                      <div class="preview-profile-avatar">
                        A
                      </div>
                    </div>

                  </div>


                  <!-- Stats -->
                  <div class="preview-stats">

                    <div class="preview-stat-card">

                      <div class="preview-stat-icon blue">
                        <Users :size="15" />
                      </div>

                      <div>
                        <small>Total Siswa</small>
                        <strong>1,248</strong>
                      </div>

                    </div>


                    <div class="preview-stat-card">

                      <div class="preview-stat-icon green">
                        <Award :size="15" />
                      </div>

                      <div>
                        <small>Prestasi</small>
                        <strong>186</strong>
                      </div>

                    </div>


                    <div class="preview-stat-card">

                      <div class="preview-stat-icon orange">
                        <AlertTriangle :size="15" />
                      </div>

                      <div>
                        <small>Pelanggaran</small>
                        <strong>42</strong>
                      </div>

                    </div>

                  </div>


                  <!-- Chart -->
                  <div class="preview-chart-card">

                    <div class="preview-card-header">
                      <div>
                        <small>Ringkasan Data</small>
                        <strong>Perkembangan Kesiswaan</strong>
                      </div>

                      <BarChart3 :size="17" />
                    </div>

                    <div class="fake-chart">

                      <div class="chart-y-labels">
                        <span>100</span>
                        <span>75</span>
                        <span>50</span>
                        <span>25</span>
                        <span>0</span>
                      </div>

                      <div class="chart-area">

                        <div class="chart-grid">
                          <span></span>
                          <span></span>
                          <span></span>
                          <span></span>
                          <span></span>
                        </div>

                        <svg
                          class="chart-line"
                          viewBox="0 0 400 150"
                          preserveAspectRatio="none"
                        >
                          <path
                            d="M0,115 C35,105 50,120 78,95 C105,70 118,88 145,72 C175,54 180,74 210,58 C245,40 255,56 282,42 C315,25 335,39 360,20 C380,8 392,17 400,10"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="4"
                            stroke-linecap="round"
                          />
                        </svg>

                        <div class="chart-labels">
                          <span>Jan</span>
                          <span>Feb</span>
                          <span>Mar</span>
                          <span>Apr</span>
                          <span>Mei</span>
                          <span>Jun</span>
                        </div>

                      </div>

                    </div>

                  </div>


                  <!-- Bottom Cards -->
                  <div class="preview-bottom">

                    <div class="preview-information-card">

                      <div class="preview-card-header">
                        <div>
                          <small>Informasi Terbaru</small>
                          <strong>Aktivitas Kesiswaan</strong>
                        </div>

                        <Bell :size="16" />
                      </div>

                      <div class="preview-notification">

                        <div class="notification-icon green">
                          <Award :size="14" />
                        </div>

                        <div>
                          <strong>Prestasi Baru</strong>
                          <span>Siswa meraih juara tingkat provinsi</span>
                        </div>

                      </div>

                      <div class="preview-notification">

                        <div class="notification-icon orange">
                          <AlertCircle :size="14" />
                        </div>

                        <div>
                          <strong>Pelanggaran Tercatat</strong>
                          <span>Terdapat data pelanggaran baru</span>
                        </div>

                      </div>

                    </div>


                    <div class="preview-attendance-card">

                      <div class="preview-card-header">
                        <div>
                          <small>Kehadiran</small>
                          <strong>Hari Ini</strong>
                        </div>

                        <CalendarCheck :size="16" />
                      </div>

                      <div class="attendance-number">
                        94<span>%</span>
                      </div>

                      <div class="attendance-progress">
                        <span></span>
                      </div>

                      <small class="attendance-description">
                        Kehadiran siswa hari ini
                      </small>

                    </div>

                  </div>

                </div>

              </div>

            </div>


            <!-- Floating Card -->
            <div class="floating-card floating-achievement">

              <div class="floating-icon green">
                <Award :size="18" />
              </div>

              <div>
                <strong>Prestasi Baru</strong>
                <span>+10 poin siswa</span>
              </div>

            </div>


            <div class="floating-card floating-attendance">

              <div class="floating-icon blue">
                <CheckCircle2 :size="18" />
              </div>

              <div>
                <strong>Data Terpantau</strong>
                <span>Semua sistem aktif</span>
              </div>

            </div>

          </div>

        </div>

      </section>


      <!-- =========================
           LOGO / TRUST STRIP
      ========================== -->
      <section class="trust-section">

        <div class="trust-container">

          <span class="trust-title">
            Mendukung pengelolaan kesiswaan yang lebih terintegrasi
          </span>

          <div class="trust-line"></div>

          <div class="trust-features">

            <div>
              <Database :size="18" />
              <span>Data Terpusat</span>
            </div>

            <div>
              <ShieldCheck :size="18" />
              <span>Terorganisir</span>
            </div>

            <div>
              <BarChart3 :size="18" />
              <span>Berbasis Data</span>
            </div>

            <div>
              <Smartphone :size="18" />
              <span>Responsif</span>
            </div>

          </div>

        </div>

      </section>


      <!-- =========================
           TENTANG
      ========================== -->
      <section
        id="tentang"
        class="about-section"
      >

        <div class="section-container">

          <div class="section-label">
            <span>01</span>
            TENTANG SIKAP ASSALAAM
          </div>

          <div class="about-grid">

            <div class="about-title">

              <h2>
                Satu sistem untuk
                <span>mengelola</span>
                perkembangan siswa.
              </h2>

            </div>

            <div class="about-content">

              <p>
                SIKAP Assalaam dirancang sebagai sistem informasi
                kesiswaan yang membantu sekolah dalam mengelola
                berbagai data siswa secara lebih terstruktur.
              </p>

              <p>
                Mulai dari data siswa, pelanggaran, prestasi,
                penanganan siswa, hingga laporan dapat dikelola
                dalam satu sistem yang saling terhubung.
              </p>

              <div class="about-highlight">

                <div class="about-highlight-icon">
                  <Lightbulb :size="20" />
                </div>

                <div>
                  <strong>
                    Teknologi untuk pendidikan yang lebih baik
                  </strong>

                  <span>
                    Membantu proses administrasi menjadi lebih
                    sederhana, terarah, dan mudah dipantau.
                  </span>
                </div>

              </div>

            </div>

          </div>

        </div>

      </section>


      <!-- =========================
           KEUNGGULAN
      ========================== -->
      <section
        id="keunggulan"
        class="advantages-section"
      >

        <div class="section-container">

          <div class="section-heading centered">

            <div class="section-label centered-label">
              <span>02</span>
              KEUNGGULAN
            </div>

            <h2>
              Dibuat untuk kebutuhan
              <span>sekolah modern.</span>
            </h2>

            <p>
              SIKAP Assalaam membantu menyederhanakan proses
              pengelolaan kesiswaan tanpa menghilangkan
              informasi penting yang dibutuhkan sekolah.
            </p>

          </div>


          <div class="advantages-grid">

            <article class="advantage-card">

              <div class="advantage-number">
                01
              </div>

              <div class="advantage-icon">
                <Database :size="22" />
              </div>

              <h3>
                Data Terpusat
              </h3>

              <p>
                Informasi siswa, pelanggaran, prestasi, dan
                pembinaan tersimpan dalam satu sistem.
              </p>

              <div class="advantage-arrow">
                <ArrowUpRight :size="18" />
              </div>

            </article>


            <article class="advantage-card">

              <div class="advantage-number">
                02
              </div>

              <div class="advantage-icon">
                <ClipboardCheck :size="22" />
              </div>

              <h3>
                Kedisiplinan Terpantau
              </h3>

              <p>
                Pencatatan pelanggaran dan proses penanganan
                dapat dilakukan dengan lebih terstruktur.
              </p>

              <div class="advantage-arrow">
                <ArrowUpRight :size="18" />
              </div>

            </article>


            <article class="advantage-card">

              <div class="advantage-number">
                03
              </div>

              <div class="advantage-icon">
                <Award :size="22" />
              </div>

              <h3>
                Prestasi Terdokumentasi
              </h3>

              <p>
                Prestasi siswa dapat dicatat dan didokumentasikan
                sehingga perkembangan positif siswa tetap terpantau.
              </p>

              <div class="advantage-arrow">
                <ArrowUpRight :size="18" />
              </div>

            </article>


            <article class="advantage-card">

              <div class="advantage-number">
                04
              </div>

              <div class="advantage-icon">
                <FileBarChart :size="22" />
              </div>

              <h3>
                Laporan Lebih Mudah
              </h3>

              <p>
                Data kesiswaan dapat digunakan sebagai dasar
                pembuatan laporan dan evaluasi sekolah.
              </p>

              <div class="advantage-arrow">
                <ArrowUpRight :size="18" />
              </div>

            </article>

          </div>

        </div>

      </section>


      <!-- =========================
           FITUR
      ========================== -->
      <section
        id="fitur"
        class="features-section"
      >

        <div class="section-container">

          <div class="features-layout">

            <div class="features-heading">

              <div class="section-label">
                <span>03</span>
                FITUR UTAMA
              </div>

              <h2>
                Semua kebutuhan
                <span>kesiswaan</span>
                dalam satu tempat.
              </h2>

              <p>
                Kelola informasi kesiswaan dengan alur yang
                lebih jelas dan mudah dipahami.
              </p>

              <button
                type="button"
                class="feature-login-button"
                @click="goToLogin"
              >
                Mulai Menggunakan
                <ArrowUpRight :size="18" />
              </button>

            </div>


            <div class="features-list">

              <div class="feature-item">

                <div class="feature-icon">
                  <Users :size="22" />
                </div>

                <div class="feature-info">
                  <span>01</span>

                  <div>
                    <h3>
                      Data Siswa
                    </h3>

                    <p>
                      Kelola informasi siswa secara terstruktur
                      dan mudah diakses.
                    </p>
                  </div>
                </div>

                <ArrowUpRight
                  :size="19"
                  class="feature-arrow"
                />

              </div>


              <div class="feature-item">

                <div class="feature-icon">
                  <ClipboardCheck :size="22" />
                </div>

                <div class="feature-info">
                  <span>02</span>

                  <div>
                    <h3>
                      Pelanggaran
                    </h3>

                    <p>
                      Catat dan pantau riwayat pelanggaran
                      siswa beserta poin kedisiplinannya.
                    </p>
                  </div>
                </div>

                <ArrowUpRight
                  :size="19"
                  class="feature-arrow"
                />

              </div>


              <div class="feature-item">

                <div class="feature-icon">
                  <Award :size="22" />
                </div>

                <div class="feature-info">
                  <span>03</span>

                  <div>
                    <h3>
                      Prestasi
                    </h3>

                    <p>
                      Dokumentasikan berbagai pencapaian siswa
                      berdasarkan tingkat prestasinya.
                    </p>
                  </div>
                </div>

                <ArrowUpRight
                  :size="19"
                  class="feature-arrow"
                />

              </div>


              <div class="feature-item">

                <div class="feature-icon">
                  <HeartHandshake :size="22" />
                </div>

                <div class="feature-info">
                  <span>04</span>

                  <div>
                    <h3>
                      Penanganan Siswa
                    </h3>

                    <p>
                      Membantu proses pembinaan dan penanganan
                      siswa secara bertahap.
                    </p>
                  </div>
                </div>

                <ArrowUpRight
                  :size="19"
                  class="feature-arrow"
                />

              </div>


              <div class="feature-item">

                <div class="feature-icon">
                  <FileBarChart :size="22" />
                </div>

                <div class="feature-info">
                  <span>05</span>

                  <div>
                    <h3>
                      Laporan
                    </h3>

                    <p>
                      Sajikan data kesiswaan menjadi informasi
                      yang lebih mudah dipantau.
                    </p>
                  </div>
                </div>

                <ArrowUpRight
                  :size="19"
                  class="feature-arrow"
                />

              </div>

            </div>

          </div>

        </div>

      </section>


      <!-- =========================
           CTA
      ========================== -->
      <section class="cta-section">

        <div class="cta-container">

          <div class="cta-decoration cta-decoration-one"></div>
          <div class="cta-decoration cta-decoration-two"></div>

          <div class="cta-content">

            <div class="cta-icon">
              <GraduationCap :size="25" />
            </div>

            <span class="cta-label">
              SIKAP ASSALAAM
            </span>

            <h2>
              Bersama membangun
              <span>generasi yang disiplin.</span>
            </h2>

            <p>
              Kelola kesiswaan dengan lebih terarah,
              terintegrasi, dan mudah dipantau.
            </p>

            <button
              type="button"
              class="cta-button"
              @click="goToLogin"
            >
              Masuk ke SIKAP Assalaam
              <ArrowUpRight :size="19" />
            </button>

          </div>

        </div>

      </section>

    </main>


    <!-- =========================
         FOOTER
    ========================== -->
    <footer class="landing-footer">

      <div class="footer-container">

        <div class="footer-brand">

          <router-link
            to="/"
            class="landing-brand footer-brand-link"
          >

            <div class="landing-brand-icon">
              <GraduationCap :size="22" />
            </div>

            <div class="landing-brand-text">
              <strong>
                SIKAP <span>ASSALAAM</span>
              </strong>

              <small>
                SISTEM INFORMASI KESISWAAN
              </small>
            </div>

          </router-link>

          <p>
            Sistem informasi kesiswaan untuk mendukung
            pengelolaan siswa yang lebih terintegrasi.
          </p>

        </div>


        <div class="footer-links">

          <div class="footer-column">

            <strong>
              Navigasi
            </strong>

            <a href="#tentang">
              Tentang
            </a>

            <a href="#keunggulan">
              Keunggulan
            </a>

            <a href="#fitur">
              Fitur
            </a>

          </div>


          <div class="footer-column">

            <strong>
              Sistem
            </strong>

            <button
              type="button"
              @click="goToLogin"
            >
              Masuk
            </button>

            <span>
              Dashboard
            </span>

            <span>
              Kesiswaan
            </span>

          </div>

        </div>

      </div>


      <div class="footer-bottom">

        <span>
          © {{ currentYear }} SIKAP Assalaam.
          All rights reserved.
        </span>

        <span>
          Sistem Informasi Kesiswaan
        </span>

      </div>

    </footer>

  </div>
</template>


<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'

import {
  GraduationCap,
  LogIn,
  Sparkles,
  ArrowUpRight,
  ArrowDown,
  ShieldCheck,
  CheckCircle2,
  LayoutDashboard,
  Users,
  ClipboardCheck,
  Award,
  FileText,
  AlertTriangle,
  BarChart3,
  Bell,
  AlertCircle,
  CalendarCheck,
  Database,
  Smartphone,
  Lightbulb,
  FileBarChart,
  HeartHandshake
} from 'lucide-vue-next'

const router = useRouter()

const currentYear = computed(() => {
  return new Date().getFullYear()
})

const goToLogin = () => {
  router.push('/login')
}
</script>

<style scoped>
/* ========================================
   BASE
======================================== */

.landing-page {
  min-height: 100vh;
  background: #ffffff;
  color: #172033;
  overflow-x: hidden;
}

.landing-page * {
  box-sizing: border-box;
}

.landing-page button,
.landing-page a {
  font-family: inherit;
}


/* ========================================
   NAVBAR
======================================== */

.landing-navbar {
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  z-index: 1000;

  background: rgba(255, 255, 255, 0.94);

  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);

  border-bottom: 1px solid #edf0f3;
}

.landing-navbar-inner {
  width: 100%;
  max-width: 1280px;

  height: 78px;

  margin: 0 auto;
  padding: 0 32px;

  display: flex;
  align-items: center;
  justify-content: space-between;
}


/* Brand */

.landing-brand {
  display: inline-flex;
  align-items: center;
  gap: 12px;

  color: #172033;
  text-decoration: none;
}

.landing-brand-icon {
  width: 42px;
  height: 42px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 12px;

  background: #2563eb;
  color: #ffffff;

  box-shadow:
    0 8px 20px rgba(37, 99, 235, 0.18);
}

.landing-brand-text {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.landing-brand-text strong {
  font-family: 'DM Sans', sans-serif;

  font-size: 19px;
  line-height: 1;

  font-weight: 700;
  letter-spacing: -0.5px;
}

.landing-brand-text strong span {
  color: #2563eb;
}

.landing-brand-text small {
  font-family: 'DM Sans', sans-serif;

  font-size: 8px;
  line-height: 1;

  font-weight: 700;
  letter-spacing: 1.15px;

  color: #7b8594;
}


/* Navigation */

.landing-nav {
  display: flex;
  align-items: center;
  gap: 32px;
}

.landing-nav a {
  position: relative;

  color: #526071;

  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  font-weight: 500;

  text-decoration: none;

  transition: color 0.2s ease;
}

.landing-nav a::after {
  content: '';

  position: absolute;
  left: 0;
  right: 0;
  bottom: -7px;

  height: 2px;

  border-radius: 99px;

  background: #2563eb;

  transform: scaleX(0);
  transform-origin: center;

  transition: transform 0.2s ease;
}

.landing-nav a:hover {
  color: #2563eb;
}

.landing-nav a:hover::after {
  transform: scaleX(1);
}


/* Login */

.landing-login-button {
  height: 46px;

  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 9px;

  padding: 0 20px;

  border: 1px solid #bfdbfe;
  border-radius: 999px;

  background: #eff6ff;
  color: #1d4ed8;

  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  font-weight: 700;

  cursor: pointer;

  transition:
    color 0.2s ease,
    background 0.2s ease,
    transform 0.2s ease,
    box-shadow 0.2s ease;
}

.landing-login-button:hover {
  color: #ffffff;
  background: #2563eb;

  transform: translateY(-1px);

  box-shadow:
    0 10px 24px rgba(37, 99, 235, 0.18);
}


/* ========================================
   HERO
======================================== */

.hero-section {
  position: relative;

  min-height: 790px;

  padding-top: 150px;
  padding-bottom: 90px;

  background:
    radial-gradient(
      circle at 82% 30%,
      rgba(37, 99, 235, 0.08),
      transparent 28%
    ),
    linear-gradient(
      180deg,
      #ffffff 0%,
      #f8faff 100%
    );
}

.hero-container {
  width: 100%;
  max-width: 1280px;

  min-height: 600px;

  margin: 0 auto;
  padding: 0 32px;

  display: grid;
  grid-template-columns: 0.9fr 1.1fr;

  align-items: center;

  gap: 45px;
}


/* Hero Content */

.hero-content {
  position: relative;
  z-index: 2;
}

.hero-badge {
  width: fit-content;

  display: inline-flex;
  align-items: center;
  gap: 8px;

  padding: 9px 15px;

  margin-bottom: 27px;

  border: 1px solid #dbeafe;
  border-radius: 999px;

  background: #eff6ff;
  color: #1d4ed8;

  font-family: 'DM Sans', sans-serif;
  font-size: 12px;
  font-weight: 600;
}

.hero-content h1 {
  max-width: 650px;

  margin: 0;

  font-family: 'Playfair Display', serif;

  font-size: clamp(55px, 5.6vw, 82px);
  font-weight: 600;

  line-height: 0.99;
  letter-spacing: -3px;

  color: #172033;
}

.hero-content h1 span,
.hero-content h1 em {
  color: #2563eb;
  font-style: normal;
}

.hero-description {
  max-width: 600px;

  margin: 29px 0 0;

  font-family: 'DM Sans', sans-serif;

  font-size: 17px;
  line-height: 1.8;

  color: #647084;
}


/* Hero Actions */

.hero-actions {
  margin-top: 34px;

  display: flex;
  align-items: center;
  gap: 23px;
}

.hero-primary-button {
  height: 51px;

  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 10px;

  padding: 0 21px;

  border: none;
  border-radius: 10px;

  background: #2563eb;
  color: #ffffff;

  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  font-weight: 700;

  cursor: pointer;

  box-shadow:
    0 10px 25px rgba(37, 99, 235, 0.18);

  transition:
    background 0.2s ease,
    transform 0.2s ease,
    box-shadow 0.2s ease;
}

.hero-primary-button:hover {
  background: #1d4ed8;

  transform: translateY(-2px);

  box-shadow:
    0 14px 30px rgba(37, 99, 235, 0.23);
}

.hero-secondary-button {
  display: inline-flex;
  align-items: center;
  gap: 7px;

  color: #526071;

  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  font-weight: 600;

  text-decoration: none;

  transition: color 0.2s ease;
}

.hero-secondary-button:hover {
  color: #2563eb;
}


/* Hero Trust */

.hero-trust {
  margin-top: 34px;

  display: flex;
  align-items: center;
  gap: 18px;
}

.trust-item {
  display: flex;
  align-items: center;
  gap: 8px;

  color: #7a8493;

  font-family: 'DM Sans', sans-serif;
  font-size: 12px;
  font-weight: 500;
}

.trust-icon {
  width: 27px;
  height: 27px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 8px;

  background: #eff6ff;
  color: #2563eb;
}

.trust-divider {
  width: 1px;
  height: 22px;

  background: #e5e9ee;
}


/* ========================================
   HERO VISUAL
======================================== */

.hero-visual {
  position: relative;

  min-height: 570px;

  display: flex;
  align-items: center;
  justify-content: center;
}

.visual-glow {
  position: absolute;

  width: 500px;
  height: 500px;

  border-radius: 50%;

  background:
    radial-gradient(
      circle,
      rgba(37, 99, 235, 0.10),
      transparent 68%
    );

  filter: blur(10px);
}


/* Dashboard */

.dashboard-preview {
  position: relative;

  z-index: 2;

  width: min(650px, 100%);

  background: #ffffff;

  border: 1px solid #e7ebef;
  border-radius: 18px;

  overflow: hidden;

  box-shadow:
    0 30px 70px rgba(24, 39, 58, 0.13),
    0 5px 20px rgba(24, 39, 58, 0.05);

  transform: perspective(1400px) rotateY(-3deg) rotateX(1deg);
}


/* Browser bar */

.preview-topbar {
  height: 38px;

  display: flex;
  align-items: center;

  padding: 0 13px;

  background: #f8fafb;

  border-bottom: 1px solid #edf0f3;
}

.preview-dots {
  display: flex;
  gap: 5px;
}

.preview-dots span {
  width: 7px;
  height: 7px;

  border-radius: 50%;

  background: #d7dde3;
}

.preview-address {
  width: 190px;
  height: 21px;

  display: flex;
  align-items: center;
  justify-content: center;

  margin: 0 auto;

  border-radius: 5px;

  background: #eef1f4;

  color: #98a1ae;

  font-family: 'DM Sans', sans-serif;
  font-size: 7px;
}


/* Preview Body */

.preview-body {
  min-height: 430px;

  display: flex;
}


/* Sidebar */

.preview-sidebar {
  width: 125px;

  flex-shrink: 0;

  padding: 16px 10px;

  background: #fbfcfd;

  border-right: 1px solid #edf0f3;
}

.preview-sidebar-logo {
  display: flex;
  align-items: center;
  gap: 7px;

  margin-bottom: 22px;

  color: #172033;

  font-family: 'DM Sans', sans-serif;
  font-size: 11px;
  font-weight: 700;
}

.preview-sidebar-logo div {
  width: 23px;
  height: 23px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 6px;

  background: #2563eb;
  color: #ffffff;
}

.preview-menu {
  min-height: 29px;

  display: flex;
  align-items: center;
  gap: 7px;

  padding: 0 7px;
  margin-bottom: 5px;

  border-radius: 6px;

  color: #7d8794;

  font-family: 'DM Sans', sans-serif;
  font-size: 8px;
  font-weight: 500;
}

.preview-menu.active {
  color: #2563eb;

  background: #eff6ff;
}


/* Main */

.preview-main {
  min-width: 0;
  flex: 1;

  padding: 20px;
}

.preview-heading {
  display: flex;
  align-items: center;
  justify-content: space-between;

  margin-bottom: 17px;
}

.preview-heading > div:first-child {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.preview-heading small {
  font-family: 'DM Sans', sans-serif;
  font-size: 7px;

  color: #9aa3af;
}

.preview-heading strong {
  font-family: 'DM Sans', sans-serif;
  font-size: 15px;

  color: #172033;
}

.preview-profile-avatar {
  width: 29px;
  height: 29px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background: #eff6ff;
  color: #2563eb;

  font-family: 'DM Sans', sans-serif;
  font-size: 9px;
  font-weight: 700;
}


/* Stats */

.preview-stats {
  display: grid;
  grid-template-columns: repeat(3, 1fr);

  gap: 9px;

  margin-bottom: 11px;
}

.preview-stat-card {
  min-height: 62px;

  display: flex;
  align-items: center;
  gap: 8px;

  padding: 9px;

  border: 1px solid #edf0f3;
  border-radius: 9px;

  background: #ffffff;
}

.preview-stat-icon {
  width: 28px;
  height: 28px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 7px;
}

.preview-stat-icon.blue {
  background: #edf4ff;
  color: #3478e5;
}

.preview-stat-icon.green {
  background: #eff6ff;
  color: #2563eb;
}

.preview-stat-icon.orange {
  background: #fff5e9;
  color: #e68b22;
}

.preview-stat-card > div:last-child {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.preview-stat-card small {
  font-family: 'DM Sans', sans-serif;
  font-size: 6px;
  color: #9aa3af;
}

.preview-stat-card strong {
  font-family: 'DM Sans', sans-serif;
  font-size: 12px;
  color: #172033;
}


/* Chart */

.preview-chart-card,
.preview-information-card,
.preview-attendance-card {
  border: 1px solid #edf0f3;
  border-radius: 10px;

  background: #ffffff;
}

.preview-chart-card {
  padding: 13px;

  margin-bottom: 10px;
}

.preview-card-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
}

.preview-card-header > div {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.preview-card-header small {
  font-family: 'DM Sans', sans-serif;
  font-size: 6px;
  color: #9aa3af;
}

.preview-card-header strong {
  font-family: 'DM Sans', sans-serif;
  font-size: 9px;
  color: #172033;
}

.preview-card-header > svg {
  color: #9ca6b2;
}


/* Fake chart */

.fake-chart {
  height: 150px;

  display: flex;

  margin-top: 12px;
}

.chart-y-labels {
  width: 25px;

  display: flex;
  flex-direction: column;
  justify-content: space-between;

  padding: 0 0 18px;

  font-family: 'DM Sans', sans-serif;
  font-size: 6px;

  color: #b1b8c2;
}

.chart-area {
  position: relative;

  flex: 1;

  min-width: 0;
}

.chart-grid {
  position: absolute;
  inset: 0 0 20px;

  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.chart-grid span {
  width: 100%;
  height: 1px;

  background: #f0f2f5;
}

.chart-line {
  position: absolute;

  left: 0;
  right: 0;
  top: 5px;

  width: 100%;
  height: calc(100% - 25px);

  color: #2563eb;
}

.chart-labels {
  position: absolute;

  left: 0;
  right: 0;
  bottom: 0;

  display: flex;
  justify-content: space-between;

  font-family: 'DM Sans', sans-serif;
  font-size: 6px;

  color: #aab2bc;
}


/* Bottom */

.preview-bottom {
  display: grid;
  grid-template-columns: 1.35fr 0.65fr;

  gap: 10px;
}

.preview-information-card,
.preview-attendance-card {
  padding: 12px;
}

.preview-notification {
  display: flex;
  align-items: center;
  gap: 8px;

  margin-top: 11px;
}

.notification-icon {
  width: 27px;
  height: 27px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 7px;
}

.notification-icon.green {
  background: #eff6ff;
  color: #2563eb;
}

.notification-icon.orange {
  background: #fff5e9;
  color: #e68b22;
}

.preview-notification > div:last-child {
  min-width: 0;

  display: flex;
  flex-direction: column;
  gap: 2px;
}

.preview-notification strong {
  font-family: 'DM Sans', sans-serif;
  font-size: 7px;
  color: #384354;
}

.preview-notification span {
  overflow: hidden;

  font-family: 'DM Sans', sans-serif;
  font-size: 6px;

  color: #a0a8b3;

  text-overflow: ellipsis;
  white-space: nowrap;
}


/* Attendance */

.attendance-number {
  margin-top: 17px;

  font-family: 'DM Sans', sans-serif;
  font-size: 27px;
  font-weight: 700;

  color: #2563eb;
}

.attendance-number span {
  font-size: 13px;
  font-weight: 500;
}

.attendance-progress {
  height: 5px;

  margin-top: 10px;

  overflow: hidden;

  border-radius: 99px;

  background: #e8eefc;
}

.attendance-progress span {
  display: block;

  width: 94%;
  height: 100%;

  border-radius: inherit;

  background: #2563eb;
}

.attendance-description {
  display: block;

  margin-top: 7px;

  font-family: 'DM Sans', sans-serif;
  font-size: 6px;

  color: #a0a8b3;
}


/* Floating */

.floating-card {
  position: absolute;

  z-index: 4;

  display: flex;
  align-items: center;
  gap: 9px;

  padding: 10px 13px;

  border: 1px solid #e9edf0;
  border-radius: 10px;

  background: rgba(255, 255, 255, 0.97);

  box-shadow:
    0 15px 35px rgba(24, 39, 58, 0.12);
}

.floating-achievement {
  left: -20px;
  bottom: 86px;
}

.floating-attendance {
  right: -17px;
  top: 90px;
}

.floating-icon {
  width: 31px;
  height: 31px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 8px;
}

.floating-icon.green {
  color: #2563eb;
  background: #eff6ff;
}

.floating-icon.blue {
  color: #3478e5;
  background: #edf4ff;
}

.floating-card > div:last-child {
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.floating-card strong {
  font-family: 'DM Sans', sans-serif;
  font-size: 9px;
  color: #263244;
}

.floating-card span {
  font-family: 'DM Sans', sans-serif;
  font-size: 7px;
  color: #9aa3af;
}


/* ========================================
   TRUST
======================================== */

.trust-section {
  border-top: 1px solid #edf0f3;
  border-bottom: 1px solid #edf0f3;

  background: #fbfcfd;
}

.trust-container {
  width: 100%;
  max-width: 1280px;

  min-height: 110px;

  margin: 0 auto;
  padding: 0 32px;

  display: flex;
  align-items: center;
  gap: 32px;
}

.trust-title {
  max-width: 220px;

  font-family: 'DM Sans', sans-serif;
  font-size: 11px;
  line-height: 1.5;
  font-weight: 600;

  color: #727d8d;
}

.trust-line {
  width: 1px;
  height: 35px;

  background: #e2e6eb;
}

.trust-features {
  flex: 1;

  display: grid;
  grid-template-columns: repeat(4, 1fr);

  gap: 20px;
}

.trust-features > div {
  display: flex;
  align-items: center;
  gap: 9px;

  color: #667182;

  font-family: 'DM Sans', sans-serif;
  font-size: 11px;
  font-weight: 600;
}

.trust-features svg {
  color: #2563eb;
}


/* ========================================
   GENERAL SECTION
======================================== */

.section-container {
  width: 100%;
  max-width: 1180px;

  margin: 0 auto;
  padding: 0 32px;
}

.section-label {
  display: flex;
  align-items: center;
  gap: 10px;

  color: #2563eb;

  font-family: 'DM Sans', sans-serif;
  font-size: 10px;
  font-weight: 700;

  letter-spacing: 1.4px;
}

.section-label span {
  display: inline-flex;
  align-items: center;
  justify-content: center;

  width: 27px;
  height: 27px;

  border: 1px solid #dbeafe;
  border-radius: 50%;

  font-size: 9px;

  letter-spacing: 0;
}

.section-heading h2,
.about-title h2,
.features-heading h2,
.cta-content h2 {
  font-family: 'Playfair Display', serif;
  font-weight: 600;
  letter-spacing: -1.5px;
}

.section-heading h2 span,
.about-title h2 span,
.features-heading h2 span,
.cta-content h2 span {
  color: #2563eb;
}


/* ========================================
   ABOUT
======================================== */

.about-section {
  padding: 125px 0;

  background: #ffffff;
}

.about-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;

  gap: 90px;

  margin-top: 48px;
}

.about-title h2 {
  max-width: 510px;

  margin: 0;

  font-size: clamp(42px, 4.5vw, 61px);
  line-height: 1.06;

  color: #172033;
}

.about-content {
  padding-top: 7px;
}

.about-content > p {
  margin: 0 0 18px;

  font-family: 'DM Sans', sans-serif;
  font-size: 16px;
  line-height: 1.8;

  color: #6a7585;
}

.about-highlight {
  display: flex;
  align-items: flex-start;
  gap: 14px;

  margin-top: 28px;
  padding: 20px;

  border: 1px solid #dbeafe;
  border-radius: 13px;

  background: #f8fbff;
}

.about-highlight-icon {
  width: 40px;
  height: 40px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 10px;

  background: #eff6ff;
  color: #2563eb;
}

.about-highlight > div:last-child {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.about-highlight strong {
  font-family: 'DM Sans', sans-serif;
  font-size: 13px;
  font-weight: 700;

  color: #253044;
}

.about-highlight span {
  font-family: 'DM Sans', sans-serif;
  font-size: 12px;
  line-height: 1.6;

  color: #7a8493;
}


/* ========================================
   ADVANTAGES
======================================== */

.advantages-section {
  padding: 125px 0;

  background: #f8faff;
}

.section-heading.centered {
  max-width: 720px;

  margin: 0 auto;

  text-align: center;
}

.centered-label {
  justify-content: center;
}

.section-heading h2 {
  margin: 25px 0 14px;

  font-size: clamp(42px, 4.5vw, 59px);
  line-height: 1.05;

  color: #172033;
}

.section-heading p {
  max-width: 620px;

  margin: 0 auto;

  font-family: 'DM Sans', sans-serif;
  font-size: 15px;
  line-height: 1.8;

  color: #737e8e;
}

.advantages-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);

  gap: 14px;

  margin-top: 55px;
}

.advantage-card {
  position: relative;

  min-height: 300px;

  padding: 27px 24px;

  border: 1px solid #e5eaf2;
  border-radius: 14px;

  background: #ffffff;

  overflow: hidden;

  transition:
    transform 0.25s ease,
    box-shadow 0.25s ease,
    border-color 0.25s ease;
}

.advantage-card:hover {
  transform: translateY(-5px);

  border-color: #bfdbfe;

  box-shadow:
    0 18px 35px rgba(30, 64, 175, 0.08);
}

.advantage-number {
  position: absolute;

  top: 19px;
  right: 20px;

  font-family: 'Playfair Display', serif;
  font-size: 27px;

  color: #e7edf8;
}

.advantage-icon {
  width: 45px;
  height: 45px;

  display: flex;
  align-items: center;
  justify-content: center;

  margin-bottom: 27px;

  border-radius: 11px;

  background: #eff6ff;
  color: #2563eb;
}

.advantage-card h3 {
  margin: 0 0 11px;

  font-family: 'DM Sans', sans-serif;
  font-size: 16px;
  font-weight: 700;

  color: #263144;
}

.advantage-card p {
  margin: 0;

  font-family: 'DM Sans', sans-serif;
  font-size: 12px;
  line-height: 1.75;

  color: #7b8594;
}

.advantage-arrow {
  position: absolute;

  right: 22px;
  bottom: 20px;

  color: #b4bec7;

  transition:
    color 0.2s ease,
    transform 0.2s ease;
}

.advantage-card:hover .advantage-arrow {
  color: #2563eb;

  transform: translate(2px, -2px);
}


/* ========================================
   FEATURES
======================================== */

.features-section {
  padding: 125px 0;

  background: #ffffff;
}

.features-layout {
  display: grid;
  grid-template-columns: 0.8fr 1.2fr;

  gap: 95px;

  align-items: start;
}

.features-heading h2 {
  margin: 26px 0 17px;

  font-size: clamp(42px, 4vw, 57px);
  line-height: 1.06;

  color: #172033;
}

.features-heading p {
  max-width: 430px;

  margin: 0;

  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  line-height: 1.8;

  color: #747f8f;
}

.feature-login-button {
  margin-top: 30px;

  height: 47px;

  display: inline-flex;
  align-items: center;
  gap: 9px;

  padding: 0 18px;

  border: 1px solid #dbeafe;
  border-radius: 9px;

  background: #f5f9ff;
  color: #1d4ed8;

  font-family: 'DM Sans', sans-serif;
  font-size: 13px;
  font-weight: 700;

  cursor: pointer;

  transition:
    color 0.2s ease,
    background 0.2s ease;
}

.feature-login-button:hover {
  color: #ffffff;
  background: #2563eb;
}


/* Feature list */

.features-list {
  border-top: 1px solid #e7ebef;
}

.feature-item {
  min-height: 110px;

  display: flex;
  align-items: center;
  gap: 17px;

  padding: 20px 4px;

  border-bottom: 1px solid #e7ebef;

  transition:
    padding 0.2s ease,
    background 0.2s ease;
}

.feature-item:hover {
  padding-left: 12px;
  padding-right: 12px;

  background: #f8fbff;
}

.feature-icon {
  width: 45px;
  height: 45px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 11px;

  background: #eff6ff;
  color: #2563eb;
}

.feature-info {
  flex: 1;

  display: flex;
  align-items: flex-start;
  gap: 20px;
}

.feature-info > span {
  padding-top: 2px;

  font-family: 'DM Sans', sans-serif;
  font-size: 9px;
  font-weight: 700;

  color: #a3acb7;
}

.feature-info > div {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.feature-info h3 {
  margin: 0;

  font-family: 'DM Sans', sans-serif;
  font-size: 15px;
  font-weight: 700;

  color: #283346;
}

.feature-info p {
  margin: 0;

  font-family: 'DM Sans', sans-serif;
  font-size: 11px;
  line-height: 1.6;

  color: #7d8795;
}

.feature-arrow {
  color: #aeb7c1;

  transition:
    color 0.2s ease,
    transform 0.2s ease;
}

.feature-item:hover .feature-arrow {
  color: #2563eb;

  transform: translate(2px, -2px);
}


/* ========================================
   CTA
======================================== */

.cta-section {
  padding: 0 32px 110px;

  background: #ffffff;
}

.cta-container {
  position: relative;

  max-width: 1180px;

  min-height: 390px;

  margin: 0 auto;

  display: flex;
  align-items: center;
  justify-content: center;

  overflow: hidden;

  border-radius: 24px;

  background:
    radial-gradient(
      circle at 10% 10%,
      rgba(255, 255, 255, 0.12),
      transparent 25%
    ),
    #123b72;
}

.cta-decoration {
  position: absolute;

  border: 1px solid rgba(255, 255, 255, 0.08);

  border-radius: 50%;
}

.cta-decoration-one {
  width: 420px;
  height: 420px;

  left: -160px;
  top: -170px;
}

.cta-decoration-two {
  width: 500px;
  height: 500px;

  right: -230px;
  bottom: -270px;
}

.cta-content {
  position: relative;
  z-index: 2;

  max-width: 650px;

  text-align: center;
}

.cta-icon {
  width: 49px;
  height: 49px;

  display: flex;
  align-items: center;
  justify-content: center;

  margin: 0 auto 16px;

  border: 1px solid rgba(255, 255, 255, 0.18);
  border-radius: 13px;

  background: rgba(255, 255, 255, 0.09);
  color: #ffffff;
}

.cta-label {
  font-family: 'DM Sans', sans-serif;
  font-size: 9px;
  font-weight: 700;

  letter-spacing: 2px;

  color: #93c5fd;
}

.cta-content h2 {
  margin: 17px 0 14px;

  font-size: clamp(40px, 4.3vw, 58px);
  line-height: 1.04;

  color: #ffffff;
}

.cta-content h2 span {
  color: #60a5fa;
}

.cta-content p {
  margin: 0;

  font-family: 'DM Sans', sans-serif;
  font-size: 14px;
  line-height: 1.7;

  color: rgba(255, 255, 255, 0.68);
}

.cta-button {
  height: 49px;

  display: inline-flex;
  align-items: center;
  gap: 9px;

  margin-top: 27px;

  padding: 0 20px;

  border: none;
  border-radius: 9px;

  background: #ffffff;
  color: #1d4ed8;

  font-family: 'DM Sans', sans-serif;
  font-size: 13px;
  font-weight: 700;

  cursor: pointer;

  transition:
    transform 0.2s ease,
    box-shadow 0.2s ease;
}

.cta-button:hover {
  transform: translateY(-2px);

  box-shadow:
    0 12px 25px rgba(0, 0, 0, 0.15);
}


/* ========================================
   FOOTER
======================================== */

.landing-footer {
  background: #f8faff;
  border-top: 1px solid #e5eaf2;
}

.footer-container {
  width: 100%;
  max-width: 1180px;

  margin: 0 auto;
  padding: 70px 32px;

  display: flex;
  justify-content: space-between;

  gap: 70px;
}

.footer-brand {
  max-width: 360px;
}

.footer-brand-link {
  margin-bottom: 18px;
}

.footer-brand > p {
  margin: 0;

  font-family: 'DM Sans', sans-serif;
  font-size: 12px;
  line-height: 1.7;

  color: #7b8592;
}

.footer-links {
  display: flex;
  gap: 100px;
}

.footer-column {
  min-width: 100px;

  display: flex;
  flex-direction: column;
  align-items: flex-start;

  gap: 11px;
}

.footer-column strong {
  margin-bottom: 6px;

  font-family: 'DM Sans', sans-serif;
  font-size: 12px;
  font-weight: 700;

  color: #263144;
}

.footer-column a,
.footer-column button,
.footer-column span {
  padding: 0;

  border: none;
  background: transparent;

  font-family: 'DM Sans', sans-serif;
  font-size: 11px;

  color: #7a8492;

  text-decoration: none;
}

.footer-column a:hover,
.footer-column button:hover {
  color: #2563eb;
}

.footer-column button {
  cursor: pointer;
}

.footer-bottom {
  width: 100%;
  max-width: 1180px;

  margin: 0 auto;
  padding: 18px 32px;

  display: flex;
  align-items: center;
  justify-content: space-between;

  border-top: 1px solid #e3e8f1;

  font-family: 'DM Sans', sans-serif;
  font-size: 10px;

  color: #929ba6;
}


/* ========================================
   RESPONSIVE
======================================== */

@media (max-width: 1100px) {

  .hero-container {
    grid-template-columns: 1fr;

    gap: 60px;
  }

  .hero-content {
    max-width: 760px;

    margin: 0 auto;

    text-align: center;
  }

  .hero-badge {
    margin-left: auto;
    margin-right: auto;
  }

  .hero-description {
    margin-left: auto;
    margin-right: auto;
  }

  .hero-actions {
    justify-content: center;
  }

  .hero-trust {
    justify-content: center;
  }

  .hero-visual {
    max-width: 760px;

    width: 100%;

    margin: 0 auto;
  }

  .advantages-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .features-layout {
    gap: 55px;
  }
}


@media (max-width: 900px) {

  .landing-navbar-inner {
    padding: 0 20px;
  }

  .landing-nav {
    gap: 15px;
  }

  .landing-nav a {
    display: none;
  }

  .landing-login-button {
    height: 43px;

    padding: 0 16px;
  }

  .hero-section {
    padding-top: 125px;
  }

  .hero-container {
    padding: 0 20px;
  }

  .hero-content h1 {
    font-size: clamp(48px, 10vw, 72px);
    letter-spacing: -2px;
  }

  .about-grid {
    grid-template-columns: 1fr;

    gap: 35px;
  }

  .features-layout {
    grid-template-columns: 1fr;

    gap: 55px;
  }

  .features-heading p {
    max-width: 650px;
  }

  .trust-container {
    flex-direction: column;

    align-items: flex-start;

    padding-top: 28px;
    padding-bottom: 28px;

    gap: 20px;
  }

  .trust-line {
    width: 100%;
    height: 1px;
  }

  .trust-features {
    width: 100%;
  }

}


@media (max-width: 650px) {

  .landing-brand-text small {
    display: none;
  }

  .landing-brand-text strong {
    font-size: 16px;
  }

  .landing-brand-icon {
    width: 38px;
    height: 38px;
  }

  .landing-login-button span {
    display: none;
  }

  .landing-login-button {
    width: 42px;

    padding: 0;
  }

  .hero-section {
    min-height: auto;

    padding-top: 115px;
    padding-bottom: 70px;
  }

  .hero-content h1 {
    font-size: clamp(43px, 13vw, 62px);
    line-height: 1.01;
  }

  .hero-description {
    font-size: 14px;
    line-height: 1.75;
  }

  .hero-actions {
    flex-direction: column;

    gap: 17px;
  }

  .hero-primary-button {
    width: 100%;
  }

  .hero-trust {
    flex-direction: column;

    gap: 12px;
  }

  .trust-divider {
    width: 35px;
    height: 1px;
  }

  .hero-visual {
    min-height: 400px;
  }

  .dashboard-preview {
    transform: none;

    border-radius: 13px;
  }

  .preview-sidebar {
    display: none;
  }

  .preview-main {
    padding: 12px;
  }

  .preview-stats {
    grid-template-columns: 1fr;

    gap: 6px;
  }

  .preview-stat-card {
    min-height: 48px;
  }

  .preview-bottom {
    grid-template-columns: 1fr;
  }

  .floating-card {
    display: none;
  }

  .trust-features {
    grid-template-columns: repeat(2, 1fr);
  }

  .section-container {
    padding: 0 20px;
  }

  .about-section,
  .advantages-section,
  .features-section {
    padding: 85px 0;
  }

  .about-title h2,
  .section-heading h2,
  .features-heading h2 {
    font-size: 42px;
  }

  .advantages-grid {
    grid-template-columns: 1fr;
  }

  .advantage-card {
    min-height: 270px;
  }

  .feature-item {
    align-items: flex-start;
  }

  .feature-info {
    gap: 10px;
  }

  .feature-info p {
    font-size: 10px;
  }

  .feature-arrow {
    display: none;
  }

  .cta-section {
    padding: 0 15px 80px;
  }

  .cta-container {
    min-height: 420px;

    padding: 35px 20px;

    border-radius: 18px;
  }

  .cta-content h2 {
    font-size: 40px;
  }

  .footer-container {
    flex-direction: column;

    padding: 55px 20px;

    gap: 40px;
  }

  .footer-links {
    gap: 60px;
  }

  .footer-bottom {
    padding: 18px 20px;

    flex-direction: column;
    align-items: flex-start;

    gap: 7px;
  }

}
</style>