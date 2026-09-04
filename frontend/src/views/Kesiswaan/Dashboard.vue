<template>
  <AppLayout role="kesiswaan">

    <div class="dashboard">

      <!-- HEADER -->
      <div class="dashboard-header">
        <div>
          <h1>Dashboard Kesiswaan </h1>

          <p>
            Pantau kedisiplinan dan prestasi siswa secara keseluruhan.
          </p>
        </div>

        <div class="dashboard-date">
            <div class="date-icon">
                <CalendarDays :size="20" />
            </div>

            <div class="date-info">
                <span>Hari ini</span>
                <strong>{{ currentDate }}</strong>
            </div>
        </div>
      </div>


      <!-- STAT CARDS -->
    <div class="stats-grid">

    <!-- TOTAL SISWA -->
    <div class="stat-card stat-blue">

        <div class="stat-card-decoration"></div>

        <div class="stat-card-top">
        <div class="stat-icon">
            <Users :size="22" />
        </div>

        <span class="stat-label">DATA SISWA</span>
        </div>

        <div class="stat-info">
        <span>Total Siswa</span>
        <strong>350</strong>
        </div>

        <div class="stat-description">
        Siswa terdaftar dalam sistem
        </div>

    </div>


    <!-- PELANGGARAN -->
    <div class="stat-card stat-red">

        <div class="stat-card-decoration"></div>

        <div class="stat-card-top">
        <div class="stat-icon">
            <TriangleAlert :size="22" />
        </div>

        <span class="stat-label">DISIPLIN</span>
        </div>

        <div class="stat-info">
        <span>Total Pelanggaran</span>
        <strong>42</strong>
        </div>

        <div class="stat-description">
        Catatan pelanggaran siswa
        </div>

    </div>


    <!-- PRESTASI -->
    <div class="stat-card stat-green">

        <div class="stat-card-decoration"></div>

        <div class="stat-card-top">
        <div class="stat-icon">
            <Trophy :size="22" />
        </div>

        <span class="stat-label">PRESTASI</span>
        </div>

        <div class="stat-info">
        <span>Total Prestasi</span>
        <strong>28</strong>
        </div>

        <div class="stat-description">
        Prestasi siswa tercatat
        </div>

    </div>


    <!-- POIN -->
    <div class="stat-card stat-purple">

        <div class="stat-card-decoration"></div>

        <div class="stat-card-top">
        <div class="stat-icon">
            <Star :size="22" />
        </div>

        <span class="stat-label">POIN</span>
        </div>

        <div class="stat-info">
        <span>Poin Tertinggi</span>
        <strong>120</strong>
        </div>

        <div class="stat-description">
        Poin tertinggi siswa
        </div>

    </div>

    </div>


      <!-- MAIN DASHBOARD -->
      <div class="dashboard-main">

        <div class="chart-section">
            <PointTrendChart />
        </div>

        <div class="summary-card">

            <div class="summary-header">
            <div>
                <span class="section-label">RINGKASAN</span>
                <h3>Ringkasan Poin</h3>
            </div>
            </div>

            <div class="summary-total">
            <span>Total poin terpantau</span>
            <strong>158</strong>
            </div>

            <div class="summary-item">
            <div class="summary-item-left">
                <span class="summary-dot violation"></span>
                <span>Pelanggaran</span>
            </div>

            <strong>42</strong>
            </div>

            <div class="summary-item">
            <div class="summary-item-left">
                <span class="summary-dot achievement"></span>
                <span>Prestasi</span>
            </div>

            <strong>116</strong>
            </div>

            <div class="summary-message">
            <div class="summary-message-icon">
                ✓
            </div>

            <div>
                <strong>Perkembangan positif</strong>
                <p>
                Poin pelanggaran menunjukkan tren menurun,
                sementara poin prestasi meningkat.
                </p>
            </div>
            </div>

        </div>

        </div>


      <!-- ACTIVITY -->
      <div class="dashboard-grid">

        <div class="dashboard-card">

          <div class="card-header">
            <div>
              <h3>Pelanggaran Terbaru</h3>
              <p>Data pelanggaran terbaru</p>
            </div>

            <button class="view-all-btn">
              Lihat semua
              <ArrowRight :size="16" />
            </button>
          </div>


          <div class="activity-list">

            <div class="activity-item">
              <div class="activity-icon red">
                <TriangleAlert :size="18" />
              </div>

              <div class="activity-info">
                <strong>Ahmad Fauzan</strong>
                <span>Terlambat masuk sekolah</span>
              </div>

              <div class="activity-point negative">
                -10
              </div>
            </div>


            <div class="activity-item">
              <div class="activity-icon orange">
                <TriangleAlert :size="18" />
              </div>

              <div class="activity-info">
                <strong>Muhammad Rizky</strong>
                <span>Tidak memakai atribut lengkap</span>
              </div>

              <div class="activity-point negative">
                -5
              </div>
            </div>


            <div class="activity-item">
              <div class="activity-icon yellow">
                <TriangleAlert :size="18" />
              </div>

              <div class="activity-info">
                <strong>Fajar Ramadhan</strong>
                <span>Tidak mengikuti apel</span>
              </div>

              <div class="activity-point negative">
                -5
              </div>
            </div>

          </div>

        </div>


        <div class="dashboard-card">

          <div class="card-header">
            <div>
              <h3>Prestasi Terbaru</h3>
              <p>Prestasi siswa terbaru</p>
            </div>

            <button class="view-all-btn">
              Lihat semua
              <ArrowRight :size="16" />
            </button>
          </div>


          <div class="activity-list">

            <div class="activity-item">
              <div class="activity-icon blue">
                <Trophy :size="18" />
              </div>

              <div class="activity-info">
                <strong>Ahmad Zaki</strong>
                <span>Juara 1 Olimpiade Matematika</span>
              </div>

              <div class="activity-point positive-point">
                +30
              </div>
            </div>


            <div class="activity-item">
              <div class="activity-icon blue">
                <Medal :size="18" />
              </div>

              <div class="activity-info">
                <strong>Rizky Maulana</strong>
                <span>Juara 2 Lomba Tahfidz</span>
              </div>

              <div class="activity-point positive-point">
                +25
              </div>
            </div>


            <div class="activity-item">
              <div class="activity-icon blue">
                <Award :size="18" />
              </div>

              <div class="activity-info">
                <strong>Fajar Hidayat</strong>
                <span>Juara 3 Futsal</span>
              </div>

              <div class="activity-point positive-point">
                +15
              </div>
            </div>

          </div>

        </div>

      </div>

    </div>

  </AppLayout>
</template>


<script setup>
import { computed } from 'vue'

import AppLayout from '../../components/AppLayout.vue'
import PointTrendChart from '../../components/PointTrendChart.vue'

import {
  Users,
  TriangleAlert,
  Trophy,
  Star,
  TrendingUp,
  TrendingDown,
  BarChart3,
  Bell,
  ArrowRight,
  Medal,
  Award,
  CalendarDays
} from 'lucide-vue-next'


const currentDate = computed(() => {
  return new Intl.DateTimeFormat('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  }).format(new Date())
})
</script>