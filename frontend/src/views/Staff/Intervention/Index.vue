<template>
  <AppLayout>
    <div class="page-container">
      <!-- Header -->
      <div class="page-header">
        <div>
          <h1>Penanganan Siswa</h1>
          <p>
            Pantau dan kelola proses penanganan siswa berdasarkan tahapan yang
            ditentukan.
          </p>
        </div>
      </div>

      <!-- Statistics -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon blue">
            <ClipboardList :size="22" />
          </div>

          <div class="stat-content">
            <span>Total Penanganan</span>
            <strong>{{ totalInterventions }}</strong>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon yellow">
            <Clock3 :size="22" />
          </div>

          <div class="stat-content">
            <span>Menunggu</span>
            <strong>{{ waitingCount }}</strong>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green">
            <CheckCircle2 :size="22" />
          </div>

          <div class="stat-content">
            <span>Selesai</span>
            <strong>{{ completedCount }}</strong>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon purple">
            <Users :size="22" />
          </div>

          <div class="stat-content">
            <span>Siswa Ditangani</span>
            <strong>{{ handledStudentCount }}</strong>
          </div>
        </div>
      </div>

      <!-- Filter -->
      <div class="filter-card">
        <div class="search-box">
          <Search :size="19" />
          <input
            v-model="search"
            type="text"
            placeholder="Cari nama siswa atau pelanggaran..."
          />
        </div>

        <div class="filter-group">
          <select v-model="selectedStage">
            <option value="">Semua Tahap</option>
            <option value="wali_kelas">Wali Kelas</option>
            <option value="bk">BK</option>
            <option value="kesiswaan">Kesiswaan</option>
            <option value="kepala_sekolah">Kepala Sekolah</option>
          </select>

          <select v-model="selectedStatus">
            <option value="">Semua Status</option>
            <option value="menunggu">Menunggu</option>
            <option value="diproses">Diproses</option>
            <option value="selesai">Selesai</option>
          </select>
        </div>
      </div>

      <!-- Table -->
      <div class="table-card">
        <div class="table-wrapper">
          <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Siswa</th>
                    <th>Pelanggaran</th>
                    <th>Tahap</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="(intervention, index) in filteredInterventions"
                    :key="intervention.id"
                >
                    <td>
                    {{ index + 1 }}
                    </td>

                    <!-- Siswa -->
                    <td>
                    <div class="student-info">
                        <div class="student-avatar">
                        {{ getInitial(intervention.siswa.nama) }}
                        </div>

                        <div class="student-details">
                        <strong>{{ intervention.siswa.nama }}</strong>
                        <span>{{ intervention.siswa.nis }}</span>
                        </div>
                    </div>
                    </td>

                    <!-- Pelanggaran -->
                    <td>
                    <div class="violation-info">
                        <strong>{{ intervention.pelanggaran }}</strong>
                        <span>{{ intervention.poin }} poin</span>
                    </div>
                    </td>

                    <!-- Tahap -->
                    <td>
                    <span
                        class="stage-badge"
                        :class="getStageClass(intervention.tahap)"
                    >
                        {{ getStageLabel(intervention.tahap) }}
                    </span>
                    </td>

                    <!-- Status -->
                    <td>
                    <span
                        class="status-badge"
                        :class="getStatusClass(intervention.status)"
                    >
                        <span class="status-dot"></span>
                        {{ getStatusLabel(intervention.status) }}
                    </span>
                    </td>

                    <!-- Aksi -->
                    <td>
                    <div class="action-buttons">
                        <button
                        class="action-button view"
                        title="Lihat detail"
                        @click="goToShow(intervention.id)"
                        >
                        <Eye :size="17" />
                        </button>

                        <button
                        class="action-button edit"
                        title="Edit penanganan"
                        @click="goToEdit(intervention.id)"
                        >
                        <Pencil :size="17" />
                        </button>
                    </div>
                    </td>
                </tr>

                <!-- Empty State -->
                <tr v-if="filteredInterventions.length === 0">
                    <td colspan="6">
                    <div class="empty-state">
                        <ClipboardList :size="40" />
                        <strong>Data penanganan tidak ditemukan</strong>
                        <span>
                        Coba ubah kata pencarian atau filter yang digunakan.
                        </span>
                    </div>
                    </td>
                </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'

import {
  ClipboardList,
  Clock3,
  CheckCircle2,
  Users,
  Search,
  Eye,
  Pencil
} from 'lucide-vue-next'

import AppLayout from '../../../layouts/AppLayout.vue'

const router = useRouter()

const search = ref('')
const selectedStage = ref('')
const selectedStatus = ref('')

/*
|--------------------------------------------------------------------------
| Dummy Data
|--------------------------------------------------------------------------
| Data ini sementara untuk frontend.
| Nanti akan diganti dengan response dari API Laravel.
*/

const interventions = ref([
  {
    id: 1,
    siswa: {
      nama: 'Ahmad Fauzan',
      nis: '2024001'
    },
    pelanggaran: 'Terlambat masuk sekolah',
    poin: 10,
    tahap: 'wali_kelas',
    petugas: 'Budi Santoso',
    jabatan: 'Wali Kelas',
    status: 'diproses',
    tanggalMulai: '10 Sep 2026'
  },
  {
    id: 2,
    siswa: {
      nama: 'Rizky Ramadhan',
      nis: '2024002'
    },
    pelanggaran: 'Tidak mengikuti kegiatan sekolah',
    poin: 20,
    tahap: 'bk',
    petugas: 'Siti Aminah',
    jabatan: 'BK',
    status: 'menunggu',
    tanggalMulai: '9 Sep 2026'
  },
  {
    id: 3,
    siswa: {
      nama: 'Dimas Saputra',
      nis: '2024003'
    },
    pelanggaran: 'Membawa barang terlarang',
    poin: 30,
    tahap: 'kesiswaan',
    petugas: 'Andi Pratama',
    jabatan: 'Kesiswaan',
    status: 'diproses',
    tanggalMulai: '8 Sep 2026'
  },
  {
    id: 4,
    siswa: {
      nama: 'Fajar Maulana',
      nis: '2024004'
    },
    pelanggaran: 'Bolos sekolah',
    poin: 40,
    tahap: 'kepala_sekolah',
    petugas: 'Drs. Ahmad Hidayat',
    jabatan: 'Kepala Sekolah',
    status: 'selesai',
    tanggalMulai: '5 Sep 2026'
  },
  {
    id: 5,
    siswa: {
      nama: 'Raka Firmansyah',
      nis: '2024005'
    },
    pelanggaran: 'Tidak menggunakan atribut lengkap',
    poin: 10,
    tahap: 'wali_kelas',
    petugas: 'Budi Santoso',
    jabatan: 'Wali Kelas',
    status: 'selesai',
    tanggalMulai: '3 Sep 2026'
  },
  {
    id: 6,
    siswa: {
      nama: 'Ilham Maulana',
      nis: '2024006'
    },
    pelanggaran: 'Pelanggaran tata tertib',
    poin: 25,
    tahap: 'bk',
    petugas: 'Siti Aminah',
    jabatan: 'BK',
    status: 'menunggu',
    tanggalMulai: '2 Sep 2026'
  }
])

/*
|--------------------------------------------------------------------------
| Computed
|--------------------------------------------------------------------------
*/

const totalInterventions = computed(() => {
  return interventions.value.length
})

const waitingCount = computed(() => {
  return interventions.value.filter(
    item => item.status === 'menunggu'
  ).length
})

const completedCount = computed(() => {
  return interventions.value.filter(
    item => item.status === 'selesai'
  ).length
})

const handledStudentCount = computed(() => {
  return new Set(
    interventions.value.map(item => item.siswa.nis)
  ).size
})

const filteredInterventions = computed(() => {
  const keyword = search.value.toLowerCase().trim()

  return interventions.value.filter(item => {
    const matchesSearch =
      !keyword ||
      item.siswa.nama.toLowerCase().includes(keyword) ||
      item.siswa.nis.toLowerCase().includes(keyword) ||
      item.pelanggaran.toLowerCase().includes(keyword)

    const matchesStage =
      !selectedStage.value ||
      item.tahap === selectedStage.value

    const matchesStatus =
      !selectedStatus.value ||
      item.status === selectedStatus.value

    return matchesSearch && matchesStage && matchesStatus
  })
})

/*
|--------------------------------------------------------------------------
| Helper
|--------------------------------------------------------------------------
*/

const getInitial = name => {
  if (!name) return '?'

  return name
    .split(' ')
    .map(word => word.charAt(0))
    .slice(0, 2)
    .join('')
    .toUpperCase()
}

const getStageLabel = stage => {
  const labels = {
    wali_kelas: 'Wali Kelas',
    bk: 'BK',
    kesiswaan: 'Kesiswaan',
    kepala_sekolah: 'Kepala Sekolah'
  }

  return labels[stage] || stage
}

const getStageClass = stage => {
  return `stage-${stage}`
}

const getStatusLabel = status => {
  const labels = {
    menunggu: 'Menunggu',
    diproses: 'Diproses',
    selesai: 'Selesai'
  }

  return labels[status] || status
}

const getStatusClass = status => {
  return `status-${status}`
}

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

const goToShow = id => {
  router.push(`/staff/penanganan/${id}`)
}

const goToEdit = id => {
  router.push(`/staff/penanganan/${id}/edit`)
}
</script>

<style scoped>
.page-container {
  width: 100%;
}

/* Header */

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  margin-bottom: 24px;
}

.page-header h1 {
  margin: 0 0 6px;
  color: #172033;
  font-size: 26px;
  font-weight: 700;
}

.page-header p {
  margin: 0;
  color: #64748b;
  font-size: 14px;
}

/* Statistics */

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
  width: 44px;
  height: 44px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
}

.stat-icon.blue {
  color: #2563eb;
  background: #eff6ff;
}

.stat-icon.yellow {
  color: #d97706;
  background: #fffbeb;
}

.stat-icon.green {
  color: #16a34a;
  background: #f0fdf4;
}

.stat-icon.purple {
  color: #7c3aed;
  background: #f5f3ff;
}

.stat-content {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.stat-content span {
  color: #64748b;
  font-size: 13px;
}

.stat-content strong {
  color: #172033;
  font-size: 22px;
}

/* Filter */

.filter-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
  margin-bottom: 20px;
  padding: 16px;
  background: #ffffff;
  border: 1px solid #e5eaf1;
  border-radius: 12px;
}

.search-box {
  width: 100%;
  max-width: 420px;
  height: 42px;
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 0 13px;
  border: 1px solid #dce3ec;
  border-radius: 8px;
  background: #ffffff;
}

.search-box svg {
  color: #94a3b8;
  flex-shrink: 0;
}

.search-box input {
  width: 100%;
  border: none;
  outline: none;
  color: #172033;
  font-size: 14px;
}

.search-box input::placeholder {
  color: #94a3b8;
}

.filter-group {
  display: flex;
  gap: 10px;
}

.filter-group select {
  min-width: 160px;
  height: 42px;
  padding: 0 12px;
  border: 1px solid #dce3ec;
  border-radius: 8px;
  background: #ffffff;
  color: #475569;
  font-size: 14px;
  outline: none;
  cursor: pointer;
}

/* Table */

.table-card {
  overflow: hidden;
  background: #ffffff;
  border: 1px solid #e5eaf1;
  border-radius: 12px;
}

.table-wrapper {
  width: 100%;
  overflow-x: hidden;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background: #f8fafc;
}

th {
  padding: 14px 16px;
  color: #64748b;
  font-size: 12px;
  font-weight: 600;
  text-align: left;
  white-space: nowrap;
  border-bottom: 1px solid #e5eaf1;
}

td {
  padding: 15px 16px;
  color: #475569;
  font-size: 13px;
  border-bottom: 1px solid #eef2f6;
  vertical-align: middle;
}

tbody tr:last-child td {
  border-bottom: none;
}

tbody tr:hover {
  background: #f8fbff;
}

/* Student */

.student-info {
  display: flex;
  align-items: center;
  gap: 11px;
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
  background: #eaf2ff;
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
  font-weight: 600;
}

.student-details span {
  color: #94a3b8;
  font-size: 11px;
}

/* Violation */

.violation-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
  min-width: 170px;
}

.violation-info strong {
  color: #334155;
  font-size: 13px;
  font-weight: 600;
}

.violation-info span {
  color: #94a3b8;
  font-size: 11px;
}

/* Point */

.point-badge {
  display: inline-flex;
  align-items: center;
  padding: 5px 9px;
  border-radius: 6px;
  background: #eff6ff;
  color: #2563eb;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
}

/* Stage */

.stage-badge {
  display: inline-flex;
  align-items: center;
  padding: 5px 9px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 600;
  white-space: nowrap;
}

.stage-wali_kelas {
  background: #eff6ff;
  color: #2563eb;
}

.stage-bk {
  background: #f5f3ff;
  color: #7c3aed;
}

.stage-kesiswaan {
  background: #fff7ed;
  color: #ea580c;
}

.stage-kepala_sekolah {
  background: #fdf2f8;
  color: #db2777;
}

/* Staff */

.staff-info {
  display: flex;
  flex-direction: column;
  gap: 3px;
  min-width: 120px;
}

.staff-info strong {
  color: #334155;
  font-size: 12px;
  font-weight: 600;
}

.staff-info span {
  color: #94a3b8;
  font-size: 11px;
}

/* Status */

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 9px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 600;
  white-space: nowrap;
}

.status-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
}

.status-menunggu {
  background: #fffbeb;
  color: #b45309;
}

.status-menunggu .status-dot {
  background: #f59e0b;
}

.status-diproses {
  background: #eff6ff;
  color: #2563eb;
}

.status-diproses .status-dot {
  background: #3b82f6;
}

.status-selesai {
  background: #f0fdf4;
  color: #15803d;
}

.status-selesai .status-dot {
  background: #22c55e;
}

/* Actions */

.action-buttons {
  display: flex;
  align-items: center;
  gap: 6px;
}

.action-button {
  width: 34px;
  height: 34px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: none;
  border-radius: 7px;
  cursor: pointer;
  transition: 0.2s ease;
}

.action-button.view {
  color: #2563eb;
  background: #eff6ff;
}

.action-button.view:hover {
  background: #dbeafe;
}

.action-button.edit {
  color: #d97706;
  background: #fffbeb;
}

.action-button.edit:hover {
  background: #fef3c7;
}

/* Empty */

.empty-state {
  min-height: 220px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  color: #94a3b8;
}

.empty-state svg {
  margin-bottom: 6px;
  color: #cbd5e1;
}

.empty-state strong {
  color: #475569;
  font-size: 14px;
}

.empty-state span {
  color: #94a3b8;
  font-size: 12px;
}

/* Responsive */

@media (max-width: 1100px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .filter-card {
    flex-direction: column;
    align-items: stretch;
  }

  .search-box {
    max-width: none;
  }

  .filter-group {
    width: 100%;
  }

  .filter-group select {
    flex: 1;
  }
}

@media (max-width: 640px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }

  .filter-group {
    flex-direction: column;
  }

  .filter-group select {
    width: 100%;
  }

  .page-header h1 {
    font-size: 22px;
  }
}
</style>