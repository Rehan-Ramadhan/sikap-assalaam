<template>
  <AppLayout>
    <div class="violation-page">

      <!-- HEADER -->
      <div class="page-header">
        <div>
          <h1>Data Pelanggaran</h1>
          <p>Kelola dan pantau data pelanggaran siswa.</p>
        </div>

        <button class="btn-primary" @click="goToCreate">
          <Plus :size="18" />
          <span>Catat Pelanggaran</span>
        </button>
      </div>

      <!-- STATISTIK -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon total">
            <ClipboardList :size="18" />
          </div>

          <div>
            <span>Total Pelanggaran</span>
            <strong>{{ students.length }}</strong>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon active">
            <CircleCheck :size="18" />
          </div>

          <div>
            <span>Pelanggaran Aktif</span>
            <strong>{{ activeCount }}</strong>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon warning">
            <TriangleAlert :size="18" />
          </div>

          <div>
            <span>Total Poin Aktif</span>
            <strong>{{ totalPoints }}</strong>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon cancelled">
            <CircleX :size="18" />
          </div>

          <div>
            <span>Dibatalkan</span>
            <strong>{{ cancelledCount }}</strong>
          </div>
        </div>
      </div>

      <!-- FILTER -->
      <div class="filter-card">
        <div class="search-box">
          <Search :size="18" />
          <input
            v-model="search"
            type="text"
            placeholder="Cari nama siswa atau pelanggaran..."
          />
        </div>

        <select v-model="filterKategori">
          <option value="">Semua Kategori</option>
          <option value="ringan">Ringan</option>
          <option value="sedang">Sedang</option>
          <option value="berat">Berat</option>
        </select>

        <select v-model="filterStatus">
          <option value="">Semua Status</option>
          <option value="aktif">Aktif</option>
          <option value="dibatalkan">Dibatalkan</option>
        </select>

        <button
          v-if="search || filterKategori || filterStatus"
          class="btn-reset"
          @click="resetFilter"
        >
          <RotateCcw :size="16" />
          Reset
        </button>
      </div>

      <!-- TABLE -->
      <div class="table-card">

        <div class="table-header">
          <div>
            <h2>Daftar Pelanggaran</h2>
            <span>{{ filteredStudents.length }} data ditemukan</span>
          </div>
        </div>

        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th width="60">No</th>
                <th>Siswa</th>
                <th>Pelanggaran</th>
                <th>Kategori</th>
                <th>Poin</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th width="130">Aksi</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="(student, index) in filteredStudents"
                :key="student.id"
              >
                <td>{{ index + 1 }}</td>

                <!-- SISWA -->
                <td>
                  <div class="student-info">
                    <div class="student-avatar">
                      {{ getInitial(student.nama) }}
                    </div>

                    <div>
                      <strong>{{ student.nama }}</strong>
                      <span>{{ student.nis }}</span>
                    </div>
                  </div>
                </td>

                <!-- PELANGGARAN -->
                <td>
                  <div class="violation-name">
                    {{ student.pelanggaran }}
                  </div>
                </td>

                <!-- KATEGORI -->
                <td>
                  <span
                    class="category-badge"
                    :class="student.kategori"
                  >
                    {{ capitalize(student.kategori) }}
                  </span>
                </td>

                <!-- POIN -->
                <td>
                  <span class="point-value">
                    {{ student.poin }}
                  </span>
                </td>

                <!-- TANGGAL -->
                <td>
                  {{ formatDate(student.tanggal) }}
                </td>

                <!-- STATUS -->
                <td>
                  <span
                    class="status-badge"
                    :class="student.status"
                  >
                    <span class="status-dot"></span>
                    {{ student.status === 'aktif' ? 'Aktif' : 'Dibatalkan' }}
                  </span>
                </td>

                <!-- AKSI -->
                <td>
                  <div class="action-buttons">

                    <button
                      class="action-btn view"
                      title="Lihat"
                      @click="goToShow(student.id)"
                    >
                      <Eye :size="17" />
                    </button>

                    <button
                      class="action-btn edit"
                      title="Edit"
                      :disabled="student.status === 'dibatalkan'"
                      @click="goToEdit(student.id)"
                    >
                      <Pencil :size="17" />
                    </button>

                    <button
                      class="action-btn delete"
                      title="Batalkan"
                      :disabled="student.status === 'dibatalkan'"
                      @click="cancelViolation(student.id)"
                    >
                      <XCircle :size="17" />
                    </button>

                  </div>
                </td>
              </tr>

              <!-- EMPTY -->
              <tr v-if="filteredStudents.length === 0">
                <td colspan="8">
                  <div class="empty-state">
                    <div class="empty-icon">
                      <ClipboardX :size="28" />
                    </div>

                    <strong>Data tidak ditemukan</strong>
                    <span>
                      Tidak ada data pelanggaran yang sesuai dengan pencarian.
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
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'

import AppLayout from '../../../layouts/AppLayout.vue'

import {
  Plus,
  ClipboardList,
  CircleCheck,
  TriangleAlert,
  CircleX,
  Search,
  RotateCcw,
  Eye,
  Pencil,
  XCircle,
  ClipboardX
} from 'lucide-vue-next'

const router = useRouter()

/*
|--------------------------------------------------------------------------
| Dummy Data
|--------------------------------------------------------------------------
*/

const students = ref([
  {
    id: 1,
    nama: 'Ahmad Fauzan',
    nis: '2026001',
    pelanggaran: 'Terlambat masuk sekolah',
    kategori: 'sedang',
    poin: 10,
    tanggal: '2026-09-14',
    status: 'aktif'
  },
  {
    id: 2,
    nama: 'Muhammad Rizky',
    nis: '2026002',
    pelanggaran: 'Tidak memakai atribut lengkap',
    kategori: 'ringan',
    poin: 5,
    tanggal: '2026-09-13',
    status: 'aktif'
  },
  {
    id: 3,
    nama: 'Siti Aisyah',
    nis: '2025001',
    pelanggaran: 'Tidak mengikuti kegiatan sekolah',
    kategori: 'sedang',
    poin: 10,
    tanggal: '2026-09-12',
    status: 'aktif'
  },
  {
    id: 4,
    nama: 'Rizky Ramadhan',
    nis: '2025008',
    pelanggaran: 'Merokok di lingkungan sekolah',
    kategori: 'berat',
    poin: 25,
    tanggal: '2026-09-10',
    status: 'aktif'
  },
  {
    id: 5,
    nama: 'Nabila Putri',
    nis: '2024005',
    pelanggaran: 'Tidak mengerjakan tugas',
    kategori: 'ringan',
    poin: 3,
    tanggal: '2026-09-09',
    status: 'dibatalkan'
  },
  {
    id: 6,
    nama: 'Fajar Maulana',
    nis: '2026009',
    pelanggaran: 'Keluar kelas tanpa izin',
    kategori: 'sedang',
    poin: 8,
    tanggal: '2026-09-08',
    status: 'aktif'
  }
])

/*
|--------------------------------------------------------------------------
| Filter
|--------------------------------------------------------------------------
*/

const search = ref('')
const filterKategori = ref('')
const filterStatus = ref('')

const filteredStudents = computed(() => {
  return students.value.filter((student) => {
    const keyword = search.value.toLowerCase()

    const matchSearch =
      student.nama.toLowerCase().includes(keyword) ||
      student.nis.toLowerCase().includes(keyword) ||
      student.pelanggaran.toLowerCase().includes(keyword)

    const matchKategori =
      !filterKategori.value ||
      student.kategori === filterKategori.value

    const matchStatus =
      !filterStatus.value ||
      student.status === filterStatus.value

    return matchSearch && matchKategori && matchStatus
  })
})

/*
|--------------------------------------------------------------------------
| Statistik
|--------------------------------------------------------------------------
*/

const activeCount = computed(() => {
  return students.value.filter(
    student => student.status === 'aktif'
  ).length
})

const cancelledCount = computed(() => {
  return students.value.filter(
    student => student.status === 'dibatalkan'
  ).length
})

const totalPoints = computed(() => {
  return students.value
    .filter(student => student.status === 'aktif')
    .reduce((total, student) => total + student.poin, 0)
})

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

const goToCreate = () => {
  router.push('/staff/pelanggaran/create')
}

const goToShow = (id) => {
  router.push(`/staff/pelanggaran/${id}`)
}

const goToEdit = (id) => {
  router.push(`/staff/pelanggaran/${id}/edit`)
}

/*
|--------------------------------------------------------------------------
| Cancel Dummy Data
|--------------------------------------------------------------------------
*/

const cancelViolation = (id) => {
  const student = students.value.find(
    student => student.id === id
  )

  if (!student) return

  const reason = window.prompt(
    'Masukkan alasan pembatalan pelanggaran:'
  )

  if (!reason || !reason.trim()) {
    return
  }

  student.status = 'dibatalkan'
}

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

const resetFilter = () => {
  search.value = ''
  filterKategori.value = ''
  filterStatus.value = ''
}

const capitalize = (value) => {
  if (!value) return ''

  return value.charAt(0).toUpperCase() + value.slice(1)
}

const getInitial = (name) => {
  if (!name) return '?'

  return name
    .split(' ')
    .slice(0, 2)
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'short',
    year: 'numeric'
  })
}
</script>

<style scoped>
.violation-page {
  padding: 28px;
}

/* HEADER */

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 24px;
}

.page-header h1 {
  margin: 0 0 6px;
  font-size: 25px;
  font-weight: 700;
  color: #172033;
}

.page-header p {
  margin: 0;
  color: #7b8497;
  font-size: 14px;
}

.btn-primary {
  display: flex;
  align-items: center;
  gap: 8px;
  border: none;
  border-radius: 10px;
  padding: 11px 17px;
  background: #2563eb;
  color: white;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s;
}

.btn-primary:hover {
  background: #1d4ed8;
  transform: translateY(-1px);
}

/* STATISTIK */

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
  width: 36px;
  height: 36px;
  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 9px;
}

.stat-icon.total {
  background: #e8f0ff;
  color: #2563eb;
}

.stat-icon.active {
  background: #e9f9ef;
  color: #16a34a;
}

.stat-icon.warning {
  background: #fff6df;
  color: #d97706;
}

.stat-icon.cancelled {
  background: #fef0f0;
  color: #dc2626;
}

.stat-card span {
  display: block;
  margin-bottom: 2px;
  color: #7b8497;
  font-size: 11px;
  line-height: 1.2;
}

.stat-card strong {
  display: block;
  font-size: 18px;
  line-height: 1.2;
  color: #172033;
}

/* FILTER */

.filter-card {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 16px;
  margin-bottom: 18px;
  background: white;
  border: 1px solid #e8ebf2;
  border-radius: 13px;
}

.search-box {
  flex: 1;
  min-width: 260px;
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 0 13px;
  height: 42px;
  border: 1px solid #dfe3eb;
  border-radius: 9px;
  color: #8992a5;
}

.search-box input {
  width: 100%;
  border: none;
  outline: none;
  background: transparent;
  font-size: 13px;
  color: #172033;
}

.filter-card select {
  height: 42px;
  padding: 0 12px;
  border: 1px solid #dfe3eb;
  border-radius: 9px;
  background: white;
  color: #4b5563;
  outline: none;
  cursor: pointer;
}

.btn-reset {
  height: 42px;
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 0 13px;
  border: 1px solid #dfe3eb;
  border-radius: 9px;
  background: white;
  color: #667085;
  cursor: pointer;
}

.btn-reset:hover {
  background: #f7f8fa;
}

/* TABLE */

.table-card {
  background: white;
  border: 1px solid #e8ebf2;
  border-radius: 13px;
  overflow: hidden;
  box-shadow: 0 3px 12px rgba(30, 41, 59, 0.04);
}

.table-header {
  padding: 18px 20px;
  border-bottom: 1px solid #edf0f4;
}

.table-header h2 {
  margin: 0 0 4px;
  font-size: 16px;
  color: #172033;
}

.table-header span {
  font-size: 12px;
  color: #8992a5;
}

.table-wrapper {
  width: 100%;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background: #f8faff;
}

th {
  padding: 13px 14px;
  text-align: left;
  color: #667085;
  font-size: 12px;
  font-weight: 600;
  white-space: nowrap;
}

td {
  padding: 14px;
  border-top: 1px solid #f0f2f5;
  color: #475467;
  font-size: 13px;
  white-space: nowrap;
}

tbody tr {
  transition: 0.15s;
}

tbody tr:hover {
  background: #fafcff;
}

/* STUDENT */

.student-info {
  display: flex;
  align-items: center;
  gap: 10px;
}

.student-avatar {
  width: 35px;
  height: 35px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #eaf1ff;
  color: #2563eb;
  font-size: 11px;
  font-weight: 700;
}

.student-info strong {
  display: block;
  margin-bottom: 3px;
  color: #172033;
  font-size: 13px;
}

.student-info span {
  display: block;
  color: #98a1b2;
  font-size: 11px;
}

/* VIOLATION */

.violation-name {
  max-width: 220px;
  overflow: hidden;
  text-overflow: ellipsis;
  color: #344054;
  font-weight: 500;
}

/* BADGES */

.category-badge,
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 9px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 600;
}

.category-badge.ringan {
  background: #ecfdf3;
  color: #15803d;
}

.category-badge.sedang {
  background: #fff7e6;
  color: #b45309;
}

.category-badge.berat {
  background: #fef2f2;
  color: #dc2626;
}

.status-badge.aktif {
  background: #ecfdf3;
  color: #15803d;
}

.status-badge.dibatalkan {
  background: #f3f4f6;
  color: #6b7280;
}

.status-dot {
  width: 5px;
  height: 5px;
  border-radius: 50%;
  background: currentColor;
}

.point-value {
  display: inline-flex;
  min-width: 30px;
  justify-content: center;
  padding: 5px 8px;
  border-radius: 7px;
  background: #f1f5ff;
  color: #2563eb;
  font-weight: 700;
}

/* ACTION */

.action-buttons {
  display: flex;
  align-items: center;
  gap: 6px;
}

.action-btn {
  width: 32px;
  height: 32px;
  display: flex;
  align-items: center;
  justify-content: center;
  border: 1px solid #e3e7ee;
  border-radius: 8px;
  background: white;
  cursor: pointer;
  transition: 0.15s;
}

.action-btn.view {
  color: #2563eb;
}

.action-btn.edit {
  color: #d97706;
}

.action-btn.delete {
  color: #dc2626;
}

.action-btn:hover:not(:disabled) {
  background: #f5f7fb;
  transform: translateY(-1px);
}

.action-btn:disabled {
  opacity: 0.35;
  cursor: not-allowed;
}

/* EMPTY */

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 55px 20px;
}

.empty-icon {
  width: 58px;
  height: 58px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 12px;
  border-radius: 50%;
  background: #f2f4f7;
  color: #98a2b3;
}

.empty-state strong {
  margin-bottom: 5px;
  color: #344054;
  font-size: 14px;
}

.empty-state span {
  color: #98a2b3;
  font-size: 12px;
}

/* RESPONSIVE */

@media (max-width: 1100px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }

  .filter-card {
    flex-wrap: wrap;
  }

  .search-box {
    flex-basis: 100%;
  }
}

@media (max-width: 700px) {
  .violation-page {
    padding: 18px;
  }

  .page-header {
    align-items: flex-start;
    flex-direction: column;
  }

  .btn-primary {
    width: 100%;
    justify-content: center;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }

  .filter-card select,
  .btn-reset {
    flex: 1;
  }
}
</style>