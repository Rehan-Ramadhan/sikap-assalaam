<template>
  <AppLayout>
    <!-- Page Header -->
    <div class="page-header">
      <div>
        <h1>Data Prestasi</h1>
        <p>Kelola data prestasi siswa.</p>
      </div>

      <div class="header-actions">
        <button
          class="btn btn-secondary"
          @click="goCategories"
        >
          <FolderOpen :size="17" />
          <span>Kategori Prestasi</span>
        </button>

        <button
          class="btn btn-primary"
          @click="goCreate"
        >
          <Plus :size="17" />
          <span>Catat Prestasi</span>
        </button>
      </div>
    </div>

    <!-- Statistics -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon blue">
          <Trophy :size="22" />
        </div>

        <div class="stat-content">
          <span>Total Prestasi</span>
          <strong>{{ totalPrestasi }}</strong>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon green">
          <CircleCheck :size="22" />
        </div>

        <div class="stat-content">
          <span>Prestasi Aktif</span>
          <strong>{{ totalAktif }}</strong>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon orange">
          <Star :size="22" />
        </div>

        <div class="stat-content">
          <span>Total Poin</span>
          <strong>{{ totalPoin }}</strong>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon purple">
          <Users :size="22" />
        </div>

        <div class="stat-content">
          <span>Siswa Berprestasi</span>
          <strong>{{ totalSiswaBerprestasi }}</strong>
        </div>
      </div>
    </div>

    <!-- Filter -->
    <div class="filter-card">
      <div class="search-box">
        <Search :size="18" />

        <input
          v-model="search"
          type="text"
          placeholder="Cari nama siswa atau prestasi..."
        />
      </div>

      <select v-model="filterTingkat">
        <option value="">Semua Tingkat</option>
        <option value="sekolah">Sekolah</option>
        <option value="kecamatan">Kecamatan</option>
        <option value="kabupaten">Kabupaten</option>
        <option value="provinsi">Provinsi</option>
        <option value="nasional">Nasional</option>
        <option value="internasional">Internasional</option>
      </select>

      <select v-model="filterStatus">
        <option value="">Semua Status</option>
        <option value="aktif">Aktif</option>
        <option value="dibatalkan">Dibatalkan</option>
      </select>
    </div>

    <!-- Table -->
    <div class="table-card">
      <div class="table-header">
        <div>
          <h3>Daftar Prestasi</h3>
          <p>
            Menampilkan {{ filteredAchievements.length }} data prestasi.
          </p>
        </div>
      </div>

      <div class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>No</th>
              <th>Siswa</th>
              <th>Prestasi</th>
              <th>Tingkat</th>
              <th>Poin</th>
              <th>Tanggal</th>
              <th>Status</th>
              <th>Aksi</th>
            </tr>
          </thead>

          <tbody>
            <tr
              v-for="(achievement, index) in filteredAchievements"
              :key="achievement.id"
            >
              <!-- No -->
              <td>
                {{ index + 1 }}
              </td>

              <!-- Siswa -->
              <td>
                <div class="student-info">
                  <div class="student-avatar">
                    {{ getInitial(achievement.siswa.nama) }}
                  </div>

                  <div class="student-details">
                    <strong>
                      {{ achievement.siswa.nama }}
                    </strong>

                    <span>
                      {{ achievement.siswa.nis }}
                    </span>
                  </div>
                </div>
              </td>

              <!-- Prestasi -->
              <td>
                <div class="achievement-info">
                  <strong>
                    {{ achievement.prestasi }}
                  </strong>

                  <span>
                    {{ achievement.keterangan }}
                  </span>
                </div>
              </td>

              <!-- Tingkat -->
              <td>
                <span
                  class="level-badge"
                  :class="`level-${achievement.tingkat}`"
                >
                  {{ formatTingkat(achievement.tingkat) }}
                </span>
              </td>

              <!-- Poin -->
              <td>
                <span class="point-badge">
                  +{{ achievement.poin }}
                </span>
              </td>

              <!-- Tanggal -->
              <td>
                {{ formatDate(achievement.tanggal) }}
              </td>

              <!-- Status -->
              <td>
                <span
                  class="status-badge"
                  :class="
                    achievement.status === 'aktif'
                      ? 'status-active'
                      : 'status-cancelled'
                  "
                >
                  {{
                    achievement.status === 'aktif'
                      ? 'Aktif'
                      : 'Dibatalkan'
                  }}
                </span>
              </td>

              <!-- Aksi -->
              <td>
                <div class="action-buttons">
                  <button
                    class="action-btn action-view"
                    title="Lihat detail"
                    @click="goShow(achievement.id)"
                  >
                    <Eye :size="17" />
                  </button>

                  <button
                    v-if="achievement.status === 'aktif'"
                    class="action-btn action-edit"
                    title="Edit prestasi"
                    @click="goEdit(achievement.id)"
                  >
                    <Pencil :size="17" />
                  </button>

                  <button
                    v-if="achievement.status === 'aktif'"
                    class="action-btn action-cancel"
                    title="Batalkan prestasi"
                    @click="cancelAchievement(achievement)"
                  >
                    <Ban :size="17" />
                  </button>
                </div>
              </td>
            </tr>

            <!-- Empty State -->
            <tr v-if="filteredAchievements.length === 0">
              <td colspan="8">
                <div class="empty-state">
                  <div class="empty-icon">
                    <Trophy :size="36" />
                  </div>

                  <h3>Data prestasi tidak ditemukan</h3>

                  <p>
                    Tidak ada data yang sesuai dengan pencarian
                    atau filter yang dipilih.
                  </p>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'

import {
  Ban,
  CircleCheck,
  Eye,
  FolderOpen,
  Pencil,
  Plus,
  Search,
  Star,
  Trophy,
  Users
} from 'lucide-vue-next'

import AppLayout from '../../../layouts/AppLayout.vue'

const router = useRouter()

const search = ref('')
const filterTingkat = ref('')
const filterStatus = ref('')

/*
 * Dummy data sementara.
 * Nanti data ini akan diganti dengan response API.
 */
const achievements = ref([
  {
    id: 1,
    siswa: {
      nama: 'Ahmad Fauzan',
      nis: '2024001'
    },
    prestasi: 'Juara 1 Lomba Coding',
    keterangan: 'Lomba coding tingkat sekolah',
    tingkat: 'sekolah',
    poin: 20,
    tanggal: '2026-08-10',
    status: 'aktif'
  },
  {
    id: 2,
    siswa: {
      nama: 'Muhammad Rizky',
      nis: '2024002'
    },
    prestasi: 'Juara 1 Olimpiade Matematika',
    keterangan: 'Olimpiade matematika tingkat kecamatan',
    tingkat: 'kecamatan',
    poin: 30,
    tanggal: '2026-08-08',
    status: 'aktif'
  },
  {
    id: 3,
    siswa: {
      nama: 'Siti Aisyah',
      nis: '2024003'
    },
    prestasi: 'Juara 2 Lomba Futsal',
    keterangan: 'Kompetisi futsal tingkat kabupaten',
    tingkat: 'kabupaten',
    poin: 25,
    tanggal: '2026-08-05',
    status: 'aktif'
  },
  {
    id: 4,
    siswa: {
      nama: 'Dimas Pratama',
      nis: '2024004'
    },
    prestasi: 'Juara 1 Pencak Silat',
    keterangan: 'Kejuaraan pencak silat tingkat provinsi',
    tingkat: 'provinsi',
    poin: 40,
    tanggal: '2026-07-28',
    status: 'aktif'
  },
  {
    id: 5,
    siswa: {
      nama: 'Nurul Hidayah',
      nis: '2024005'
    },
    prestasi: 'Juara Nasional Robotik',
    keterangan: 'Kompetisi robotik tingkat nasional',
    tingkat: 'nasional',
    poin: 60,
    tanggal: '2026-07-20',
    status: 'aktif'
  },
  {
    id: 6,
    siswa: {
      nama: 'Fajar Maulana',
      nis: '2024006'
    },
    prestasi: 'Finalis Kompetisi Internasional',
    keterangan: 'Kompetisi teknologi tingkat internasional',
    tingkat: 'internasional',
    poin: 100,
    tanggal: '2026-07-15',
    status: 'dibatalkan'
  }
])

/* =========================
   Computed Statistics
========================= */

const totalPrestasi = computed(() => {
  return achievements.value.length
})

const totalAktif = computed(() => {
  return achievements.value.filter(
    item => item.status === 'aktif'
  ).length
})

const totalPoin = computed(() => {
  return achievements.value
    .filter(item => item.status === 'aktif')
    .reduce((total, item) => total + item.poin, 0)
})

const totalSiswaBerprestasi = computed(() => {
  const students = achievements.value
    .filter(item => item.status === 'aktif')
    .map(item => item.siswa.nis)

  return new Set(students).size
})

/* =========================
   Filter
========================= */

const filteredAchievements = computed(() => {
  return achievements.value.filter(item => {
    const keyword = search.value
      .toLowerCase()
      .trim()

    const matchesSearch =
      !keyword ||
      item.siswa.nama.toLowerCase().includes(keyword) ||
      item.siswa.nis.toLowerCase().includes(keyword) ||
      item.prestasi.toLowerCase().includes(keyword)

    const matchesTingkat =
      !filterTingkat.value ||
      item.tingkat === filterTingkat.value

    const matchesStatus =
      !filterStatus.value ||
      item.status === filterStatus.value

    return (
      matchesSearch &&
      matchesTingkat &&
      matchesStatus
    )
  })
})

/* =========================
   Helpers
========================= */

const getInitial = (name) => {
  if (!name) return '?'

  return name
    .split(' ')
    .map(word => word.charAt(0))
    .slice(0, 2)
    .join('')
    .toUpperCase()
}

const formatTingkat = (tingkat) => {
  const labels = {
    sekolah: 'Sekolah',
    kecamatan: 'Kecamatan',
    kabupaten: 'Kabupaten',
    provinsi: 'Provinsi',
    nasional: 'Nasional',
    internasional: 'Internasional'
  }

  return labels[tingkat] || tingkat
}

const formatDate = (date) => {
  if (!date) return '-'

  return new Date(date).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  })
}

/* =========================
   Navigation
========================= */

const goCreate = () => {
  router.push('/staff/prestasi/create')
}

const goCategories = () => {
  router.push('/staff/prestasi/kategori')
}

const goShow = (id) => {
  router.push(`/staff/prestasi/${id}`)
}

const goEdit = (id) => {
  router.push(`/staff/prestasi/${id}/edit`)
}

/* =========================
   Cancel
========================= */

const cancelAchievement = (achievement) => {
  const confirmed = confirm(
    `Batalkan prestasi "${achievement.prestasi}" milik ${achievement.siswa.nama}?`
  )

  if (!confirmed) return

  achievement.status = 'dibatalkan'

  alert('Prestasi berhasil dibatalkan.')
}
</script>

<style scoped>
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 20px;
  margin-bottom: 24px;
}

.page-header h1 {
  margin: 0 0 6px;
  color: #172033;
  font-size: 28px;
  font-weight: 700;
}

.page-header p {
  margin: 0;
  color: #6b7280;
  font-size: 14px;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 10px;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border: none;
  border-radius: 8px;
  padding: 11px 16px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s;
}

.btn-primary {
  background: #2563eb;
  color: #fff;
}

.btn-primary:hover {
  background: #1d4ed8;
}

.btn-secondary {
  background: #eff6ff;
  color: #2563eb;
  border: 1px solid #dbeafe;
}

.btn-secondary:hover {
  background: #dbeafe;
}

/* =========================
   Statistics
========================= */

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  margin-bottom: 18px;
}

.stat-card {
  height: 74px;
  min-height: 0;
  box-sizing: border-box;

  display: flex;
  align-items: center;
  gap: 11px;

  padding: 12px 15px;

  background: white;
  border: 1px solid #e8ebf2;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(30, 41, 59, 0.03);
}

.stat-icon {
  width: 46px;
  height: 46px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
}

.stat-icon.blue {
  background: #eff6ff;
  color: #2563eb;
}

.stat-icon.green {
  background: #ecfdf5;
  color: #16a34a;
}

.stat-icon.orange {
  background: #fff7ed;
  color: #ea580c;
}

.stat-icon.purple {
  background: #f5f3ff;
  color: #7c3aed;
}

.stat-content {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.stat-content span {
  color: #6b7280;
  font-size: 13px;
}

.stat-content strong {
  color: #172033;
  font-size: 22px;
}

/* =========================
   Filter
========================= */

.filter-card {
  display: flex;
  align-items: center;
  gap: 12px;
  margin-bottom: 20px;
  padding: 16px;
  background: #fff;
  border: 1px solid #eef2f7;
  border-radius: 12px;
}

.search-box {
  position: relative;
  flex: 1;
  display: flex;
  align-items: center;
}

.search-box svg {
  position: absolute;
  left: 13px;
  color: #94a3b8;
}

.search-box input,
.filter-card select {
  width: 100%;
  box-sizing: border-box;
  border: 1px solid #dbe2ea;
  border-radius: 8px;
  background: #fff;
  color: #172033;
  padding: 11px 13px;
  font-size: 14px;
  outline: none;
}

.search-box input {
  padding-left: 40px;
}

.filter-card select {
  width: 180px;
}

.search-box input:focus,
.filter-card select:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

/* =========================
   Table
========================= */

.table-card {
  background: #fff;
  border: 1px solid #eef2f7;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
  overflow: hidden;
}

.table-header {
  padding: 20px;
  border-bottom: 1px solid #eef2f7;
}

.table-header h3 {
  margin: 0 0 5px;
  color: #172033;
  font-size: 17px;
}

.table-header p {
  margin: 0;
  color: #6b7280;
  font-size: 13px;
}

.table-wrapper {
  width: 100%;
  overflow-x: auto;
}

table {
  width: 100%;
  min-width: 1050px;
  border-collapse: collapse;
}

thead {
  background: #f8fafc;
}

th {
  padding: 14px 16px;
  color: #64748b;
  font-size: 12px;
  font-weight: 700;
  text-align: left;
  white-space: nowrap;
}

td {
  padding: 15px 16px;
  border-top: 1px solid #f1f5f9;
  color: #475569;
  font-size: 13px;
  vertical-align: middle;
}

tbody tr:hover {
  background: #fafcff;
}

/* =========================
   Student
========================= */

.student-info {
  display: flex;
  align-items: center;
  gap: 10px;
  min-width: 190px;
}

.student-avatar {
  width: 38px;
  height: 38px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #dbeafe;
  color: #2563eb;
  font-size: 12px;
  font-weight: 700;
}

.student-details {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.student-details strong {
  color: #172033;
  font-size: 13px;
}

.student-details span {
  color: #94a3b8;
  font-size: 11px;
}

/* =========================
   Achievement
========================= */

.achievement-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
  min-width: 190px;
}

.achievement-info strong {
  color: #172033;
  font-size: 13px;
}

.achievement-info span {
  color: #94a3b8;
  font-size: 11px;
}

/* =========================
   Badges
========================= */

.level-badge,
.point-badge,
.status-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 999px;
  padding: 6px 10px;
  font-size: 11px;
  font-weight: 600;
  white-space: nowrap;
}

.level-sekolah {
  background: #eff6ff;
  color: #2563eb;
}

.level-kecamatan {
  background: #ecfeff;
  color: #0891b2;
}

.level-kabupaten {
  background: #f0fdf4;
  color: #16a34a;
}

.level-provinsi {
  background: #fff7ed;
  color: #ea580c;
}

.level-nasional {
  background: #fef2f2;
  color: #dc2626;
}

.level-internasional {
  background: #f5f3ff;
  color: #7c3aed;
}

.point-badge {
  background: #dcfce7;
  color: #15803d;
}

.status-active {
  background: #dcfce7;
  color: #15803d;
}

.status-cancelled {
  background: #fee2e2;
  color: #dc2626;
}

/* =========================
   Actions
========================= */

.action-buttons {
  display: flex;
  align-items: center;
  gap: 6px;
}

.action-btn {
  width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  border-radius: 7px;
  cursor: pointer;
  transition: 0.2s;
}

.action-view {
  background: #eff6ff;
  color: #2563eb;
}

.action-view:hover {
  background: #dbeafe;
}

.action-edit {
  background: #fff7ed;
  color: #ea580c;
}

.action-edit:hover {
  background: #ffedd5;
}

.action-cancel {
  background: #fef2f2;
  color: #dc2626;
}

.action-cancel:hover {
  background: #fee2e2;
}

/* =========================
   Empty State
========================= */

.empty-state {
  padding: 50px 20px;
  text-align: center;
}

.empty-icon {
  width: 64px;
  height: 64px;
  margin: 0 auto 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #eff6ff;
  color: #2563eb;
}

.empty-state h3 {
  margin: 0 0 7px;
  color: #172033;
  font-size: 16px;
}

.empty-state p {
  margin: 0;
  color: #94a3b8;
  font-size: 13px;
}

/* =========================
   Responsive
========================= */

@media (max-width: 1100px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
  }

  .header-actions {
    width: 100%;
    flex-direction: column;
  }

  .header-actions .btn {
    width: 100%;
  }

  .filter-card {
    flex-direction: column;
    align-items: stretch;
  }

  .filter-card select {
    width: 100%;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>