<template>
  <AppLayout>
    <div class="report-page">

      <!-- HEADER -->
      <div class="page-header">
        <div>
          <h1>{{ isStaff ? 'Laporan' : 'Laporan Saya' }}</h1>
          <p>
            {{
              isStaff
                ? 'Kelola dan lihat laporan data siswa.'
                : 'Lihat ringkasan data pelanggaran, prestasi, dan penanganan Anda.'
            }}
          </p>
        </div>
      </div>

      <!-- ================= STAFF ================= -->
      <template v-if="isStaff">

        <!-- TAB -->
        <div class="report-tabs">
          <button
            :class="{ active: activeTab === 'ringkasan' }"
            @click="activeTab = 'ringkasan'"
          >
            Ringkasan Siswa
          </button>

          <button
            :class="{ active: activeTab === 'pelanggaran' }"
            @click="activeTab = 'pelanggaran'"
          >
            Pelanggaran
          </button>

          <button
            :class="{ active: activeTab === 'prestasi' }"
            @click="activeTab = 'prestasi'"
          >
            Prestasi
          </button>

          <button
            :class="{ active: activeTab === 'penanganan' }"
            @click="activeTab = 'penanganan'"
          >
            Penanganan
          </button>
        </div>

        <!-- FILTER -->
        <div class="filter-card">

          <div class="filter-header">
            <div>
              <h2>Filter Laporan</h2>
              <p>Gunakan filter untuk menampilkan data tertentu.</p>
            </div>

            <button class="btn-reset" @click="resetFilter">
              Reset
            </button>
          </div>

          <div class="filter-grid">

            <div class="form-group">
              <label>Siswa</label>

              <select v-model="filters.studentId">
                <option value="">Semua Siswa</option>
                <option
                  v-for="student in students"
                  :key="student.id"
                  :value="student.id"
                >
                  {{ student.name }}
                </option>
              </select>
            </div>

            <div class="form-group">
              <label>Tingkat</label>

              <select v-model="filters.tingkat">
                <option value="">Semua Tingkat</option>
                <option value="10">Kelas 10</option>
                <option value="11">Kelas 11</option>
                <option value="12">Kelas 12</option>
              </select>
            </div>

            <div class="form-group">
              <label>Jurusan</label>

              <select v-model="filters.jurusan">
                <option value="">Semua Jurusan</option>
                <option value="RPL">RPL</option>
                <option value="TSM">TSM</option>
                <option value="TKR">TKR</option>
              </select>
            </div>

            <div class="form-group">
              <label>Nomor Kelas</label>

              <select v-model="filters.nomorKelas">
                <option value="">Semua Kelas</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
            </div>

            <!-- FILTER PELANGGARAN -->
            <template v-if="activeTab === 'pelanggaran'">

              <div class="form-group">
                <label>Status</label>

                <select v-model="filters.status">
                  <option value="">Semua Status</option>
                  <option value="aktif">Aktif</option>
                  <option value="dibatalkan">Dibatalkan</option>
                </select>
              </div>

              <div class="form-group">
                <label>Tanggal Mulai</label>
                <input
                  v-model="filters.tanggalMulai"
                  type="date"
                />
              </div>

              <div class="form-group">
                <label>Tanggal Selesai</label>
                <input
                  v-model="filters.tanggalSelesai"
                  type="date"
                />
              </div>

            </template>

            <!-- FILTER PRESTASI -->
            <template v-if="activeTab === 'prestasi'">

              <div class="form-group">
                <label>Status</label>

                <select v-model="filters.status">
                  <option value="">Semua Status</option>
                  <option value="aktif">Aktif</option>
                  <option value="dibatalkan">Dibatalkan</option>
                </select>
              </div>

              <div class="form-group">
                <label>Tanggal Mulai</label>
                <input
                  v-model="filters.tanggalMulai"
                  type="date"
                />
              </div>

              <div class="form-group">
                <label>Tanggal Selesai</label>
                <input
                  v-model="filters.tanggalSelesai"
                  type="date"
                />
              </div>

            </template>

            <!-- FILTER PENANGANAN -->
            <template v-if="activeTab === 'penanganan'">

              <div class="form-group">
                <label>Tahap</label>

                <select v-model="filters.tahap">
                  <option value="">Semua Tahap</option>
                  <option value="wali_kelas">Wali Kelas</option>
                  <option value="bk">BK</option>
                  <option value="kesiswaan">Kesiswaan</option>
                  <option value="kepala_sekolah">
                    Kepala Sekolah
                  </option>
                </select>
              </div>

              <div class="form-group">
                <label>Status</label>

                <select v-model="filters.status">
                  <option value="">Semua Status</option>
                  <option value="menunggu">Menunggu</option>
                  <option value="diproses">Diproses</option>
                  <option value="selesai">Selesai</option>
                </select>
              </div>

              <div class="form-group">
                <label>Tanggal Mulai</label>
                <input
                  v-model="filters.tanggalMulai"
                  type="date"
                />
              </div>

              <div class="form-group">
                <label>Tanggal Selesai</label>
                <input
                  v-model="filters.tanggalSelesai"
                  type="date"
                />
              </div>

            </template>

          </div>
        </div>

        <!-- RINGKASAN SISWA -->
        <div v-if="activeTab === 'ringkasan'" class="report-card">

          <div class="card-header">
            <div>
              <h2>Ringkasan Siswa</h2>
              <p>Rekap poin pelanggaran dan prestasi siswa.</p>
            </div>

            <div class="export-actions">
              <button class="btn-export excel">
                Export Excel
              </button>

              <button class="btn-export pdf">
                Export PDF
              </button>
            </div>
          </div>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Siswa</th>
                  <th>NIS</th>
                  <th>Tingkat</th>
                  <th>Jurusan</th>
                  <th>Kelas</th>
                  <th>Poin Pelanggaran</th>
                  <th>Poin Prestasi</th>
                  <th>Net Poin</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(student, index) in filteredStudents"
                  :key="student.id"
                >
                  <td>{{ index + 1 }}</td>

                  <td>
                    <div class="student-cell">
                      <div class="student-avatar">
                        {{ getInitial(student.name) }}
                      </div>

                      <span>{{ student.name }}</span>
                    </div>
                  </td>

                  <td>{{ student.nis }}</td>
                  <td>{{ student.tingkat }}</td>
                  <td>{{ student.jurusan }}</td>
                  <td>{{ student.nomorKelas }}</td>

                  <td>
                    <span class="point negative">
                      {{ student.violationPoints }}
                    </span>
                  </td>

                  <td>
                    <span class="point positive">
                      {{ student.achievementPoints }}
                    </span>
                  </td>

                  <td>
                    <strong>
                      {{ student.netPoints }}
                    </strong>
                  </td>
                </tr>

                <tr v-if="filteredStudents.length === 0">
                  <td colspan="9" class="empty-state">
                    Tidak ada data siswa.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- PELANGGARAN -->
        <div v-if="activeTab === 'pelanggaran'" class="report-card">

          <div class="card-header">
            <div>
              <h2>Laporan Pelanggaran</h2>
              <p>Data pelanggaran siswa.</p>
            </div>

            <div class="export-actions">
              <button class="btn-export excel">
                Export Excel
              </button>

              <button class="btn-export pdf">
                Export PDF
              </button>
            </div>
          </div>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Siswa</th>
                  <th>Pelanggaran</th>
                  <th>Poin</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(item, index) in filteredViolations"
                  :key="item.id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.student }}</td>
                  <td>{{ item.violation }}</td>
                  <td>
                    <span class="point negative">
                      {{ item.points }}
                    </span>
                  </td>
                  <td>{{ item.date }}</td>
                  <td>
                    <span class="status-badge">
                      {{ item.status }}
                    </span>
                  </td>
                </tr>

                <tr v-if="filteredViolations.length === 0">
                  <td colspan="6" class="empty-state">
                    Tidak ada data pelanggaran.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- PRESTASI -->
        <div v-if="activeTab === 'prestasi'" class="report-card">

          <div class="card-header">
            <div>
              <h2>Laporan Prestasi</h2>
              <p>Data prestasi siswa.</p>
            </div>

            <div class="export-actions">
              <button class="btn-export excel">
                Export Excel
              </button>

              <button class="btn-export pdf">
                Export PDF
              </button>
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
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(item, index) in filteredAchievements"
                  :key="item.id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.student }}</td>
                  <td>{{ item.achievement }}</td>
                  <td>{{ item.level }}</td>

                  <td>
                    <span class="point positive">
                      {{ item.points }}
                    </span>
                  </td>

                  <td>{{ item.date }}</td>

                  <td>
                    <span class="status-badge">
                      {{ item.status }}
                    </span>
                  </td>
                </tr>

                <tr v-if="filteredAchievements.length === 0">
                  <td colspan="7" class="empty-state">
                    Tidak ada data prestasi.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- PENANGANAN -->
        <div v-if="activeTab === 'penanganan'" class="report-card">

          <div class="card-header">
            <div>
              <h2>Laporan Penanganan</h2>
              <p>Data proses penanganan siswa.</p>
            </div>

            <div class="export-actions">
              <button class="btn-export excel">
                Export Excel
              </button>

              <button class="btn-export pdf">
                Export PDF
              </button>
            </div>
          </div>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Siswa</th>
                  <th>Tahap</th>
                  <th>Poin Saat Penanganan</th>
                  <th>Status</th>
                  <th>Tanggal Mulai</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(item, index) in filteredInterventions"
                  :key="item.id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.student }}</td>
                  <td>{{ item.stage }}</td>
                  <td>{{ item.points }}</td>

                  <td>
                    <span class="status-badge">
                      {{ item.status }}
                    </span>
                  </td>

                  <td>{{ item.date }}</td>
                </tr>

                <tr v-if="filteredInterventions.length === 0">
                  <td colspan="6" class="empty-state">
                    Tidak ada data penanganan.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </template>

      <!-- ================= STUDENT ================= -->
      <template v-else-if="isStudent">

        <div class="student-report-info">
          <div>
            <h2>Ringkasan Saya</h2>
            <p>
              Berikut data pelanggaran, prestasi, dan penanganan Anda.
            </p>
          </div>
        </div>

        <!-- TAB SISWA -->
        <div class="report-tabs">
          <button
            :class="{ active: activeStudentTab === 'pelanggaran' }"
            @click="activeStudentTab = 'pelanggaran'"
          >
            Pelanggaran Saya
          </button>

          <button
            :class="{ active: activeStudentTab === 'prestasi' }"
            @click="activeStudentTab = 'prestasi'"
          >
            Prestasi Saya
          </button>

          <button
            :class="{ active: activeStudentTab === 'penanganan' }"
            @click="activeStudentTab = 'penanganan'"
          >
            Penanganan Saya
          </button>
        </div>

        <!-- STUDENT PELANGGARAN -->
        <div
          v-if="activeStudentTab === 'pelanggaran'"
          class="report-card"
        >
          <div class="card-header">
            <div>
              <h2>Pelanggaran Saya</h2>
              <p>Riwayat pelanggaran Anda.</p>
            </div>

            <div class="export-actions">
              <button class="btn-export excel">
                Export Excel
              </button>

              <button class="btn-export pdf">
                Export PDF
              </button>
            </div>
          </div>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Pelanggaran</th>
                  <th>Poin</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(item, index) in studentViolations"
                  :key="item.id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.violation }}</td>

                  <td>
                    <span class="point negative">
                      {{ item.points }}
                    </span>
                  </td>

                  <td>{{ item.date }}</td>

                  <td>
                    <span class="status-badge">
                      {{ item.status }}
                    </span>
                  </td>
                </tr>

                <tr v-if="studentViolations.length === 0">
                  <td colspan="5" class="empty-state">
                    Belum ada data pelanggaran.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- STUDENT PRESTASI -->
        <div
          v-if="activeStudentTab === 'prestasi'"
          class="report-card"
        >
          <div class="card-header">
            <div>
              <h2>Prestasi Saya</h2>
              <p>Riwayat prestasi Anda.</p>
            </div>

            <div class="export-actions">
              <button class="btn-export excel">
                Export Excel
              </button>

              <button class="btn-export pdf">
                Export PDF
              </button>
            </div>
          </div>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Prestasi</th>
                  <th>Tingkat</th>
                  <th>Poin</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(item, index) in studentAchievements"
                  :key="item.id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.achievement }}</td>
                  <td>{{ item.level }}</td>

                  <td>
                    <span class="point positive">
                      {{ item.points }}
                    </span>
                  </td>

                  <td>{{ item.date }}</td>

                  <td>
                    <span class="status-badge">
                      {{ item.status }}
                    </span>
                  </td>
                </tr>

                <tr v-if="studentAchievements.length === 0">
                  <td colspan="6" class="empty-state">
                    Belum ada data prestasi.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- STUDENT PENANGANAN -->
        <div
          v-if="activeStudentTab === 'penanganan'"
          class="report-card"
        >
          <div class="card-header">
            <div>
              <h2>Penanganan Saya</h2>
              <p>Riwayat proses penanganan Anda.</p>
            </div>

            <div class="export-actions">
              <button class="btn-export excel">
                Export Excel
              </button>

              <button class="btn-export pdf">
                Export PDF
              </button>
            </div>
          </div>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tahap</th>
                  <th>Poin Saat Penanganan</th>
                  <th>Status</th>
                  <th>Tanggal Mulai</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(item, index) in studentInterventions"
                  :key="item.id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.stage }}</td>
                  <td>{{ item.points }}</td>

                  <td>
                    <span class="status-badge">
                      {{ item.status }}
                    </span>
                  </td>

                  <td>{{ item.date }}</td>
                </tr>

                <tr v-if="studentInterventions.length === 0">
                  <td colspan="5" class="empty-state">
                    Belum ada data penanganan.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

      </template>

    </div>
  </AppLayout>
</template>

<script setup>
import { computed, ref } from 'vue'
import AppLayout from '../../layouts/AppLayout.vue'
import { getUser } from '../../utils/auth'

const user = getUser()

const isStaff = computed(() => user?.role === 'staf')
const isStudent = computed(() => user?.role === 'siswa')

const activeTab = ref('ringkasan')
const activeStudentTab = ref('pelanggaran')

const filters = ref({
  studentId: '',
  tingkat: '',
  jurusan: '',
  nomorKelas: '',
  status: '',
  tahap: '',
  tanggalMulai: '',
  tanggalSelesai: ''
})

/*
|--------------------------------------------------------------------------
| DUMMY DATA
|--------------------------------------------------------------------------
| Nanti bagian ini akan diganti dengan data dari API Laravel.
*/

const students = ref([
  {
    id: 1,
    name: 'Ahmad Fauzan',
    nis: '2024001',
    tingkat: '10',
    jurusan: 'RPL',
    nomorKelas: 1,
    violationPoints: 20,
    achievementPoints: 10,
    netPoints: -10
  },
  {
    id: 2,
    name: 'Budi Santoso',
    nis: '2024002',
    tingkat: '11',
    jurusan: 'TKR',
    nomorKelas: 2,
    violationPoints: 10,
    achievementPoints: 20,
    netPoints: 10
  },
  {
    id: 3,
    name: 'Citra Lestari',
    nis: '2024003',
    tingkat: '12',
    jurusan: 'TSM',
    nomorKelas: 1,
    violationPoints: 5,
    achievementPoints: 15,
    netPoints: 10
  }
])

const violations = ref([
  {
    id: 1,
    student: 'Ahmad Fauzan',
    studentId: 1,
    violation: 'Terlambat masuk sekolah',
    points: 10,
    date: '2026-08-20',
    status: 'Aktif',
    tingkat: '10',
    jurusan: 'RPL',
    nomorKelas: 1
  },
  {
    id: 2,
    student: 'Budi Santoso',
    studentId: 2,
    violation: 'Tidak memakai atribut lengkap',
    points: 5,
    date: '2026-08-21',
    status: 'Aktif',
    tingkat: '11',
    jurusan: 'TKR',
    nomorKelas: 2
  }
])

const achievements = ref([
  {
    id: 1,
    student: 'Ahmad Fauzan',
    studentId: 1,
    achievement: 'Juara Lomba Web Design',
    level: 'Kabupaten',
    points: 10,
    date: '2026-08-15',
    status: 'Aktif',
    tingkat: '10',
    jurusan: 'RPL',
    nomorKelas: 1
  },
  {
    id: 2,
    student: 'Citra Lestari',
    studentId: 3,
    achievement: 'Juara Olimpiade Sekolah',
    level: 'Sekolah',
    points: 15,
    date: '2026-08-18',
    status: 'Aktif',
    tingkat: '12',
    jurusan: 'TSM',
    nomorKelas: 1
  }
])

const interventions = ref([
  {
    id: 1,
    student: 'Ahmad Fauzan',
    studentId: 1,
    stage: 'BK',
    points: 20,
    status: 'Diproses',
    date: '2026-08-22',
    tingkat: '10',
    jurusan: 'RPL',
    nomorKelas: 1
  },
  {
    id: 2,
    student: 'Budi Santoso',
    studentId: 2,
    stage: 'Wali Kelas',
    points: 10,
    status: 'Selesai',
    date: '2026-08-23',
    tingkat: '11',
    jurusan: 'TKR',
    nomorKelas: 2
  }
])

/*
|--------------------------------------------------------------------------
| FILTER RINGKASAN SISWA
|--------------------------------------------------------------------------
*/

const filteredStudents = computed(() => {
  return students.value.filter((student) => {
    return (
      (!filters.value.studentId ||
        student.id === Number(filters.value.studentId)) &&
      (!filters.value.tingkat ||
        student.tingkat === filters.value.tingkat) &&
      (!filters.value.jurusan ||
        student.jurusan === filters.value.jurusan) &&
      (!filters.value.nomorKelas ||
        student.nomorKelas === Number(filters.value.nomorKelas))
    )
  })
})

/*
|--------------------------------------------------------------------------
| FILTER PELANGGARAN
|--------------------------------------------------------------------------
*/

const filteredViolations = computed(() => {
  return violations.value.filter((item) => {
    return (
      (!filters.value.studentId ||
        item.studentId === Number(filters.value.studentId)) &&
      (!filters.value.tingkat ||
        item.tingkat === filters.value.tingkat) &&
      (!filters.value.jurusan ||
        item.jurusan === filters.value.jurusan) &&
      (!filters.value.nomorKelas ||
        item.nomorKelas === Number(filters.value.nomorKelas)) &&
      (!filters.value.status ||
        item.status.toLowerCase() === filters.value.status)
    )
  })
})

/*
|--------------------------------------------------------------------------
| FILTER PRESTASI
|--------------------------------------------------------------------------
*/

const filteredAchievements = computed(() => {
  return achievements.value.filter((item) => {
    return (
      (!filters.value.studentId ||
        item.studentId === Number(filters.value.studentId)) &&
      (!filters.value.tingkat ||
        item.tingkat === filters.value.tingkat) &&
      (!filters.value.jurusan ||
        item.jurusan === filters.value.jurusan) &&
      (!filters.value.nomorKelas ||
        item.nomorKelas === Number(filters.value.nomorKelas)) &&
      (!filters.value.status ||
        item.status.toLowerCase() === filters.value.status)
    )
  })
})

/*
|--------------------------------------------------------------------------
| FILTER PENANGANAN
|--------------------------------------------------------------------------
*/

const filteredInterventions = computed(() => {
  return interventions.value.filter((item) => {
    return (
      (!filters.value.studentId ||
        item.studentId === Number(filters.value.studentId)) &&
      (!filters.value.tingkat ||
        item.tingkat === filters.value.tingkat) &&
      (!filters.value.jurusan ||
        item.jurusan === filters.value.jurusan) &&
      (!filters.value.nomorKelas ||
        item.nomorKelas === Number(filters.value.nomorKelas)) &&
      (!filters.value.status ||
        item.status.toLowerCase() === filters.value.status) &&
      (!filters.value.tahap ||
        item.stage.toLowerCase().replaceAll(' ', '_') === filters.value.tahap)
    )
  })
})

/*
|--------------------------------------------------------------------------
| DATA LAPORAN SISWA
|--------------------------------------------------------------------------
| Untuk sementara menggunakan data dummy.
| Nanti diganti dengan endpoint student-side.
*/

const studentViolations = computed(() => {
  return [
    {
      id: 1,
      violation: 'Terlambat masuk sekolah',
      points: 10,
      date: '2026-08-20',
      status: 'Aktif'
    }
  ]
})

const studentAchievements = computed(() => {
  return [
    {
      id: 1,
      achievement: 'Juara Lomba Web Design',
      level: 'Kabupaten',
      points: 10,
      date: '2026-08-15',
      status: 'Aktif'
    }
  ]
})

const studentInterventions = computed(() => {
  return [
    {
      id: 1,
      stage: 'BK',
      points: 20,
      status: 'Diproses',
      date: '2026-08-22'
    }
  ]
})

/*
|--------------------------------------------------------------------------
| FUNCTIONS
|--------------------------------------------------------------------------
*/

const resetFilter = () => {
  filters.value = {
    studentId: '',
    tingkat: '',
    jurusan: '',
    nomorKelas: '',
    status: '',
    tahap: '',
    tanggalMulai: '',
    tanggalSelesai: ''
  }
}

const getInitial = (name) => {
  if (!name) return '?'

  return name
    .split(' ')
    .map((word) => word.charAt(0))
    .slice(0, 2)
    .join('')
    .toUpperCase()
}
</script>

<style scoped>
.report-page {
  width: 100%;
}

.page-header {
  margin-bottom: 24px;
}

.page-header h1 {
  margin: 0 0 6px;
  font-size: 28px;
  font-weight: 700;
}

.page-header p {
  margin: 0;
  color: #64748b;
}

.report-tabs {
  display: flex;
  gap: 8px;
  margin-bottom: 20px;
  padding: 6px;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  overflow-x: auto;
}

.report-tabs button {
  border: 0;
  background: transparent;
  padding: 11px 18px;
  border-radius: 8px;
  color: #64748b;
  font-weight: 600;
  cursor: pointer;
  white-space: nowrap;
}

.report-tabs button:hover {
  background: #f1f5f9;
}

.report-tabs button.active {
  background: #2563eb;
  color: #fff;
}

.filter-card,
.report-card,
.student-report-info {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  margin-bottom: 20px;
}

.filter-card {
  padding: 20px;
}

.filter-header,
.card-header,
.student-report-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 16px;
}

.filter-header {
  margin-bottom: 20px;
}

.filter-header h2,
.card-header h2,
.student-report-info h2 {
  margin: 0 0 5px;
  font-size: 18px;
}

.filter-header p,
.card-header p,
.student-report-info p {
  margin: 0;
  color: #64748b;
  font-size: 14px;
}

.filter-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 7px;
}

.form-group label {
  font-size: 13px;
  font-weight: 600;
  color: #334155;
}

.form-group input,
.form-group select {
  width: 100%;
  box-sizing: border-box;
  padding: 10px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  background: #fff;
  color: #334155;
  outline: none;
}

.form-group input:focus,
.form-group select:focus {
  border-color: #2563eb;
}

.btn-reset {
  border: 1px solid #cbd5e1;
  background: #fff;
  color: #475569;
  padding: 9px 15px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
}

.btn-reset:hover {
  background: #f8fafc;
}

.report-card {
  overflow: hidden;
}

.card-header {
  padding: 20px;
  border-bottom: 1px solid #e2e8f0;
}

.export-actions {
  display: flex;
  gap: 8px;
}

.btn-export {
  border: 0;
  padding: 9px 14px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
}

.btn-export.excel {
  background: #dcfce7;
  color: #166534;
}

.btn-export.pdf {
  background: #fee2e2;
  color: #991b1b;
}

.table-wrapper {
  width: 100%;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  min-width: 850px;
}

th,
td {
  padding: 14px 18px;
  text-align: left;
  border-bottom: 1px solid #e2e8f0;
  font-size: 14px;
}

th {
  background: #f8fafc;
  color: #475569;
  font-weight: 700;
  white-space: nowrap;
}

td {
  color: #334155;
}

tbody tr:hover {
  background: #f8fafc;
}

.student-cell {
  display: flex;
  align-items: center;
  gap: 10px;
  min-width: 180px;
}

.student-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: #dbeafe;
  color: #2563eb;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 700;
  flex-shrink: 0;
}

.point {
  font-weight: 700;
}

.point.negative {
  color: #dc2626;
}

.point.positive {
  color: #16a34a;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 5px 9px;
  border-radius: 999px;
  background: #eff6ff;
  color: #2563eb;
  font-size: 12px;
  font-weight: 600;
}

.empty-state {
  text-align: center;
  padding: 35px;
  color: #94a3b8;
}

.student-report-info {
  padding: 20px;
}

@media (max-width: 1100px) {
  .filter-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 700px) {
  .filter-grid {
    grid-template-columns: 1fr;
  }

  .filter-header,
  .card-header,
  .student-report-info {
    align-items: flex-start;
    flex-direction: column;
  }

  .export-actions {
    width: 100%;
  }

  .btn-export {
    flex: 1;
  }

  .report-tabs {
    margin-bottom: 16px;
  }
}
</style>