<template>
  <AppLayout>
    <div class="dashboard">
      <!-- HEADER -->
      <div class="dashboard-header">
        <div>
          <h1>{{ dashboardTitle }}</h1>

          <p>Pantau kedisiplinan dan prestasi siswa secara keseluruhan.</p>
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

      <!-- LOADING -->
      <div v-if="loading" class="dashboard-state">Memuat data dashboard...</div>

      <!-- ERROR -->
      <div v-else-if="errorMessage" class="dashboard-state dashboard-error">
        {{ errorMessage }}

        <button type="button" class="retry-button" @click="fetchDashboard">
          Coba lagi
        </button>
      </div>

      <!-- DASHBOARD -->
      <template v-else>
        <!-- STAT CARDS -->
        <div class="stats-grid">
          <!-- TOTAL SISWA -->
          <div class="stat-card stat-blue">
            <div class="stat-card-decoration"></div>

            <div class="stat-card-top">
              <div class="stat-icon">
                <Users :size="22" />
              </div>

              <span class="stat-label"> DATA SISWA </span>
            </div>

            <div class="stat-info">
              <span>Total Siswa</span>
              <strong>{{ totalStudents }}</strong>
            </div>

            <div class="stat-description">Siswa terdaftar dalam sistem</div>
          </div>

          <!-- PELANGGARAN -->
          <div class="stat-card stat-red">
            <div class="stat-card-decoration"></div>

            <div class="stat-card-top">
              <div class="stat-icon">
                <TriangleAlert :size="22" />
              </div>

              <span class="stat-label"> DISIPLIN </span>
            </div>

            <div class="stat-info">
              <span>Total Pelanggaran</span>
              <strong>{{ totalViolations }}</strong>
            </div>

            <div class="stat-description">Catatan pelanggaran siswa</div>
          </div>

          <!-- PRESTASI -->
          <div class="stat-card stat-green">
            <div class="stat-card-decoration"></div>

            <div class="stat-card-top">
              <div class="stat-icon">
                <Trophy :size="22" />
              </div>

              <span class="stat-label"> PRESTASI </span>
            </div>

            <div class="stat-info">
              <span>Total Prestasi</span>
              <strong>{{ totalAchievements }}</strong>
            </div>

            <div class="stat-description">Prestasi siswa tercatat</div>
          </div>

          <!-- POIN -->
          <div class="stat-card stat-purple">
            <div class="stat-card-decoration"></div>

            <div class="stat-card-top">
              <div class="stat-icon">
                <Star :size="22" />
              </div>

              <span class="stat-label"> POIN </span>
            </div>

            <div class="stat-info">
              <span>Poin Tertinggi</span>
              <strong>{{ highestPoint }}</strong>
            </div>

            <div class="stat-description">Poin tertinggi siswa</div>
          </div>
        </div>

        <!-- MAIN DASHBOARD -->
        <div class="dashboard-main">
          <div class="chart-section">
            <PointTrendChart :data="pointTrend" />
          </div>

          <!-- SUMMARY -->
          <div class="summary-card">
            <div class="summary-header">
              <div>
                <span class="section-label"> RINGKASAN </span>

                <h3>Ringkasan Poin</h3>
              </div>
            </div>

            <div class="summary-total">
              <span>Total poin terpantau</span>

              <strong>
                {{ totalPoints }}
              </strong>
            </div>

            <div class="summary-item">
              <div class="summary-item-left">
                <span class="summary-dot violation"></span>
                <span>Pelanggaran</span>
              </div>

              <strong>
                {{ totalViolationPoints }}
              </strong>
            </div>

            <div class="summary-item">
              <div class="summary-item-left">
                <span class="summary-dot achievement"></span>
                <span>Prestasi</span>
              </div>

              <strong>
                {{ totalAchievementPoints }}
              </strong>
            </div>

            <div class="summary-message">
              <div class="summary-message-icon">✓</div>

              <div>
                <strong>
                  {{ summaryTitle }}
                </strong>

                <p>
                  {{ summaryMessage }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- ACTIVITY -->
        <div class="dashboard-grid">
          <!-- PELANGGARAN TERBARU -->
          <div class="dashboard-card">
            <div class="card-header">
              <div>
                <h2 class="section-title">Pelanggaran Terbaru</h2>

                <p class="section-description">Data pelanggaran terbaru</p>
              </div>

              <button
                type="button"
                class="view-all-btn"
                @click="goTo('/staf/pelanggaran')"
              >
                Lihat semua
                <ArrowRight :size="16" />
              </button>
            </div>

            <div v-if="recentViolations.length" class="activity-list">
              <div
                v-for="item in recentViolations"
                :key="item.id"
                class="activity-item"
              >
                <div class="activity-icon" :class="item.color || 'red'">
                  <TriangleAlert :size="18" />
                </div>

                <div class="activity-info">
                  <strong>
                    {{ item.student }}
                  </strong>

                  <span>
                    {{ item.description }}
                  </span>
                </div>

                <div class="activity-point negative">
                  {{ formatPoint(item.point) }}
                </div>
              </div>
            </div>

            <div v-else class="empty-activity">Belum ada data pelanggaran.</div>
          </div>

          <!-- PRESTASI TERBARU -->
          <div class="dashboard-card">
            <div class="card-header">
              <div>
                <h2 class="section-title">Prestasi Terbaru</h2>

                <p class="section-description">Data prestasi terbaru</p>
              </div>

              <button
                type="button"
                class="view-all-btn"
                @click="goTo('/staf/prestasi')"
              >
                Lihat semua
                <ArrowRight :size="16" />
              </button>
            </div>

            <div v-if="recentAchievements.length" class="activity-list">
              <div
                v-for="item in recentAchievements"
                :key="item.id"
                class="activity-item"
              >
                <div class="activity-icon blue">
                  <Trophy :size="18" />
                </div>

                <div class="activity-info">
                  <strong>
                    {{ item.student }}
                  </strong>

                  <span>
                    {{ item.description }}
                  </span>
                </div>

                <div class="activity-point positive-point">
                  {{ formatPositivePoint(item.point) }}
                </div>
              </div>
            </div>

            <div v-else class="empty-activity">Belum ada data prestasi.</div>
          </div>
        </div>
      </template>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";

import {
  Users,
  TriangleAlert,
  Trophy,
  Star,
  CalendarDays,
  ArrowRight,
} from "lucide-vue-next";

import AppLayout from "../../layouts/AppLayout.vue";
import PointTrendChart from "../../components/charts/PointTrendChart.vue";

import api from "../../utils/api";
import { getStaffPosition } from "../../utils/auth";

/*
|--------------------------------------------------------------------------
| ROUTER
|--------------------------------------------------------------------------
*/

const router = useRouter();

/*
|--------------------------------------------------------------------------
| POSITION
|--------------------------------------------------------------------------
*/

const jabatan = getStaffPosition();

const positionNames = {
  wali_kelas: "Wali Kelas",
  bk: "BK",
  kesiswaan: "Kesiswaan",
  kepala_sekolah: "Kepala Sekolah",
};

/*
|--------------------------------------------------------------------------
| DASHBOARD TITLE
|--------------------------------------------------------------------------
*/

const dashboardTitle = computed(() => {
  return `Dashboard ${positionNames[jabatan] || "Staf"}`;
});

/*
|--------------------------------------------------------------------------
| CURRENT DATE
|--------------------------------------------------------------------------
*/

const currentDate = computed(() => {
  return new Intl.DateTimeFormat("id-ID", {
    weekday: "long",
    day: "numeric",
    month: "long",
    year: "numeric",
  }).format(new Date());
});

/*
|--------------------------------------------------------------------------
| STATE
|--------------------------------------------------------------------------
*/

const loading = ref(true);
const errorMessage = ref("");

const totalStudents = ref(0);
const totalViolations = ref(0);
const totalAchievements = ref(0);
const highestPoint = ref(0);

const totalPoints = ref(0);
const totalViolationPoints = ref(0);
const totalAchievementPoints = ref(0);

const pointTrend = ref([]);

const recentViolations = ref([]);
const recentAchievements = ref([]);

/*
|--------------------------------------------------------------------------
| SUMMARY
|--------------------------------------------------------------------------
*/

const summaryTitle = computed(() => {
  if (totalAchievementPoints.value > totalViolationPoints.value) {
    return "Perkembangan positif";
  }

  if (totalViolationPoints.value > totalAchievementPoints.value) {
    return "Perlu perhatian";
  }

  return "Perkembangan stabil";
});

const summaryMessage = computed(() => {
  if (totalAchievementPoints.value > totalViolationPoints.value) {
    return "Poin prestasi saat ini lebih tinggi dibandingkan poin pelanggaran.";
  }

  if (totalViolationPoints.value > totalAchievementPoints.value) {
    return "Poin pelanggaran masih lebih tinggi dibandingkan poin prestasi.";
  }

  return "Perbandingan poin pelanggaran dan prestasi saat ini relatif seimbang.";
});

/*
|--------------------------------------------------------------------------
| FORMAT DATA
|--------------------------------------------------------------------------
*/

const number = (value) => {
  const parsed = Number(value);

  return Number.isFinite(parsed) ? parsed : 0;
};

const formatPoint = (value) => {
  const point = number(value);

  return point > 0 ? `-${point}` : "0";
};

const formatPositivePoint = (value) => {
  const point = number(value);

  return point > 0 ? `+${point}` : "0";
};

/*
|--------------------------------------------------------------------------
| NORMALIZE DASHBOARD RESPONSE
|--------------------------------------------------------------------------
*/

const normalizeDashboard = (payload) => {
  /*
   * Response Laravel:
   *
   * {
   *   success: true,
   *   message: "...",
   *   data: {
   *     summary: {
   *       total_students: 0,
   *       total_violations: 0,
   *       total_violation_points: 0,
   *       total_achievements: 0,
   *       total_achievement_points: 0,
   *       pending_interventions: 0
   *     },
   *     recent_violations: [],
   *     recent_achievements: []
   *   }
   * }
   */

  const data = payload?.data ?? {};
  const summary = data?.summary ?? {};

  /*
   * STATISTICS
   */

  totalStudents.value = number(summary.total_students);

  totalViolations.value = number(summary.total_violations);

  totalAchievements.value = number(summary.total_achievements);

  /*
   * POINT SUMMARY
   */

  totalViolationPoints.value = number(summary.total_violation_points);

  totalAchievementPoints.value = number(summary.total_achievement_points);

  totalPoints.value = totalViolationPoints.value + totalAchievementPoints.value;

  /*
   * BACKEND SAAT INI BELUM MENGIRIM HIGHEST POINT.
   *
   * Untuk sementara gunakan nilai poin terbesar
   * dari data aktivitas terbaru.
   */

  const violationPoints = (data.recent_violations ?? []).map((item) =>
    number(item.poin_tercatat),
  );

  const achievementPoints = (data.recent_achievements ?? []).map((item) =>
    number(item.poin_tercatat),
  );

  highestPoint.value = Math.max(0, ...violationPoints, ...achievementPoints);

  /*
   * CHART
   *
   * Backend saat ini belum mengirim point_trend,
   * sehingga tetap gunakan array kosong.
   */

  pointTrend.value = data.point_trend ?? [];

  /*
   * RECENT VIOLATIONS
   */

  recentViolations.value = (data.recent_violations ?? []).map(
    (item, index) => ({
      id: item.id ?? index,

      student:
        item.student?.user?.name ??
        item.student?.user?.nama ??
        item.student?.name ??
        "Siswa",

      description: item.category?.nama_pelanggaran ?? "Pelanggaran",

      point: number(item.poin_tercatat),

      color: ["red", "orange", "yellow"][index % 3],
    }),
  );

  /*
   * RECENT ACHIEVEMENTS
   */

  recentAchievements.value = (data.recent_achievements ?? []).map(
    (item, index) => ({
      id: item.id ?? index,

      student:
        item.student?.user?.name ??
        item.student?.user?.nama ??
        item.student?.name ??
        "Siswa",

      description:
        item.category?.nama_prestasi ?? item.category?.nama ?? "Prestasi",

      point: number(item.poin_tercatat),
    }),
  );
};

/*
|--------------------------------------------------------------------------
| FETCH DASHBOARD
|--------------------------------------------------------------------------
*/

const fetchDashboard = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await api.get("/staff/dashboard");

    normalizeDashboard(response.data);
  } catch (error) {
    console.error("Gagal mengambil dashboard staff:", error);

    if (error.response?.status === 401) {
      errorMessage.value = "Sesi login sudah berakhir. Silakan login kembali.";

      return;
    }

    if (error.response?.status === 403) {
      errorMessage.value = "Anda tidak memiliki akses ke dashboard staff.";

      return;
    }

    if (error.response?.status === 404) {
      errorMessage.value =
        "Endpoint dashboard staff belum tersedia di Laravel.";

      return;
    }

    errorMessage.value =
      error.response?.data?.message ||
      "Gagal mengambil data dashboard dari server Laravel.";
  } finally {
    loading.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| NAVIGATION
|--------------------------------------------------------------------------
*/

const goTo = (path) => {
  router.push(path);
};

/*
|--------------------------------------------------------------------------
| MOUNT
|--------------------------------------------------------------------------
*/

onMounted(() => {
  fetchDashboard();
});
</script>

<style scoped>
.retry-button {
  display: block;

  margin: 14px auto 0;

  padding: 9px 15px;

  border: none;

  border-radius: 8px;

  background: #2563eb;

  color: white;

  font-size: 12px;

  font-weight: 600;

  cursor: pointer;
}

.retry-button:hover {
  background: #1d4ed8;
}

.card-header {
  display: flex;

  align-items: flex-start;

  justify-content: space-between;

  gap: 15px;

  margin-bottom: 8px;
}

.empty-activity {
  padding: 28px 10px;

  text-align: center;

  color: #94a3b8;

  font-size: 12px;
}

@media (max-width: 600px) {
  .card-header {
    align-items: flex-start;
  }

  .view-all-btn {
    flex-shrink: 0;
  }
}
</style>
