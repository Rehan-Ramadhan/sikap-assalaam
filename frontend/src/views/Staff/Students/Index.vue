<template>
  <AppLayout>
    <div class="student-page">

      <!-- HEADER -->
      <div class="page-header">
        <div>
          <h1>Data Siswa</h1>
          <p>Kelola data siswa yang terdaftar di SIKAP Assalaam.</p>
        </div>

        <button class="btn-primary" @click="goToCreate">
          <Plus :size="18" />
          <span>Tambah Siswa</span>
        </button>
      </div>

      <!-- SEARCH -->
      <div class="filter-card">
        <div class="search-box">
          <Search :size="18" />
          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari nama atau NIS..."
          />
        </div>
      </div>

      <!-- TABLE -->
      <div class="table-card">

        <div class="table-header">
          <div>
            <h2>Daftar Siswa</h2>
            <span>{{ filteredStudents.length }} data siswa ditemukan</span>
          </div>
        </div>

        <div class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th class="col-no">No</th>
                <th>Nama</th>
                <th>NIS</th>
                <th>Tingkat</th>
                <th>Jurusan</th>
                <th>Kelas</th>
                <th>Jenis Kelamin</th>
                <th class="col-action">Aksi</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="(student, index) in filteredStudents"
                :key="student.id"
              >
                <!-- NO -->
                <td class="text-center">
                  {{ index + 1 }}
                </td>

                <!-- NAMA -->
                <td>
                  <div class="student-info">
                    <div class="student-avatar">
                      <User :size="17" />
                    </div>

                    <strong>{{ student.nama }}</strong>
                  </div>
                </td>

                <!-- NIS -->
                <td>
                  {{ student.nis }}
                </td>

                <!-- TINGKAT -->
                <td>
                  <span class="level-badge">
                    Kelas {{ student.tingkat }}
                  </span>
                </td>

                <!-- JURUSAN -->
                <td>
                  {{ student.jurusan }}
                </td>

                <!-- KELAS -->
                <td>
                  {{ student.nomorKelas }}
                </td>

                <!-- JENIS KELAMIN -->
                <td>
                  {{ student.jenisKelamin }}
                </td>

                <!-- AKSI -->
                <td>
                  <div class="action-buttons">

                    <button
                      class="action-btn detail"
                      title="Lihat detail"
                      @click="goToShow(student.id)"
                    >
                      <Eye :size="17" />
                    </button>

                    <button
                      class="action-btn edit"
                      title="Edit siswa"
                      @click="goToEdit(student.id)"
                    >
                      <Pencil :size="17" />
                    </button>

                    <button
                      class="action-btn delete"
                      title="Hapus siswa"
                      @click="deleteStudent(student)"
                    >
                      <Trash2 :size="17" />
                    </button>

                  </div>
                </td>
              </tr>

              <!-- EMPTY -->
              <tr v-if="filteredStudents.length === 0">
                <td colspan="8">
                  <div class="empty-state">
                    <div class="empty-icon">
                      <Users :size="28" />
                    </div>

                    <strong>Data siswa tidak ditemukan</strong>

                    <span>
                      Tidak ada siswa yang sesuai dengan pencarian.
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
  Plus,
  Search,
  User,
  Eye,
  Pencil,
  Trash2,
  Users
} from 'lucide-vue-next'

import AppLayout from '../../../layouts/AppLayout.vue'

const router = useRouter()

const searchQuery = ref('')

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
    tingkat: '10',
    jurusan: 'RPL',
    nomorKelas: 1,
    jenisKelamin: 'Laki-laki'
  },
  {
    id: 2,
    nama: 'Muhammad Rizky',
    nis: '2026002',
    tingkat: '10',
    jurusan: 'TKR',
    nomorKelas: 2,
    jenisKelamin: 'Laki-laki'
  },
  {
    id: 3,
    nama: 'Siti Aisyah',
    nis: '2025001',
    tingkat: '11',
    jurusan: 'RPL',
    nomorKelas: 1,
    jenisKelamin: 'Perempuan'
  }
])

/*
|--------------------------------------------------------------------------
| Search
|--------------------------------------------------------------------------
*/

const filteredStudents = computed(() => {
  const query = searchQuery.value
    .trim()
    .toLowerCase()

  if (!query) {
    return students.value
  }

  return students.value.filter((student) => {
    const nama = String(student.nama ?? '').toLowerCase()
    const nis = String(student.nis ?? '').toLowerCase()

    return (
      nama.includes(query) ||
      nis.includes(query)
    )
  })
})

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

const goToCreate = () => {
  router.push('/staff/siswa/create')
}

const goToShow = (id) => {
  router.push(`/staff/siswa/${id}`)
}

const goToEdit = (id) => {
  router.push(`/staff/siswa/${id}/edit`)
}

/*
|--------------------------------------------------------------------------
| Delete Dummy Data
|--------------------------------------------------------------------------
*/

const deleteStudent = (student) => {
  const confirmed = window.confirm(
    `Apakah kamu yakin ingin menghapus siswa "${student.nama}"?`
  )

  if (!confirmed) {
    return
  }

  students.value = students.value.filter(
    (item) => item.id !== student.id
  )
}
</script>

<style scoped>
/* PAGE */

.student-page {
  width: 100%;
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
  line-height: 1.2;
  font-weight: 700;
  color: #172033;
}

.page-header p {
  margin: 0;
  color: #7b8497;
  font-size: 14px;
}

/* BUTTON */

.btn-primary {
  height: 42px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;

  padding: 0 17px;

  border: none;
  border-radius: 10px;

  background: #2563eb;
  color: #ffffff;

  font-size: 14px;
  font-weight: 600;

  cursor: pointer;
  transition: 0.2s ease;
}

.btn-primary:hover {
  background: #1d4ed8;
  transform: translateY(-1px);
}

/* FILTER */

.filter-card {
  display: flex;
  align-items: center;

  padding: 14px;
  margin-bottom: 18px;

  background: #ffffff;
  border: 1px solid #e8ebf2;
  border-radius: 13px;

  box-shadow: 0 2px 8px rgba(30, 41, 59, 0.03);
}

.search-box {
  width: 100%;
  max-width: 420px;

  height: 42px;

  display: flex;
  align-items: center;
  gap: 9px;

  padding: 0 13px;

  box-sizing: border-box;

  border: 1px solid #dfe3eb;
  border-radius: 9px;

  color: #8992a5;
}

.search-box:focus-within {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.08);
}

.search-box input {
  width: 100%;
  height: 100%;

  border: none;
  outline: none;

  background: transparent;

  color: #172033;
  font-size: 13px;
}

.search-box input::placeholder {
  color: #9ca3af;
}

/* TABLE */

.table-card {
  background: #ffffff;

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
  font-weight: 600;

  color: #172033;
}

.table-header span {
  font-size: 12px;
  color: #8992a5;
}

/* TABLE WRAPPER */

.table-wrapper {
  width: 100%;
  overflow-x: auto;
}

/* TABLE */

table {
  width: 100%;
  border-collapse: collapse;
  table-layout: fixed;
}

thead {
  background: #f8faff;
}

th {
  padding: 13px 14px;

  text-align: left;
  white-space: nowrap;

  color: #667085;

  font-size: 12px;
  font-weight: 600;

  border-bottom: 1px solid #edf0f4;
}

td {
  padding: 13px 14px;

  color: #475467;

  font-size: 13px;
  font-weight: 400;

  border-bottom: 1px solid #f0f2f5;

  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

tbody tr {
  transition: 0.15s ease;
}

tbody tr:hover {
  background: #fafcff;
}

tbody tr:last-child td {
  border-bottom: none;
}

/* COLUMN WIDTH */

.col-no {
  width: 50px;
}

.col-action {
  width: 120px;
}

.text-center {
  text-align: center;
}

/* STUDENT */

.student-info {
  display: flex;
  align-items: center;
  gap: 10px;

  min-width: 0;
}

.student-avatar {
  width: 34px;
  height: 34px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 9px;

  background: #eff6ff;
  color: #2563eb;
}

.student-info strong {
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;

  color: #172033;

  font-size: 13px;
  font-weight: 500;
}

/* LEVEL */

.level-badge {
  display: inline-flex;
  align-items: center;

  padding: 5px 9px;

  border-radius: 7px;

  background: #eff6ff;
  color: #2563eb;

  font-size: 11px;
  font-weight: 600;
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

  display: inline-flex;
  align-items: center;
  justify-content: center;

  border: 1px solid #e3e7ee;
  border-radius: 8px;

  background: #ffffff;

  cursor: pointer;

  transition: 0.15s ease;
}

.action-btn.detail {
  color: #2563eb;
}

.action-btn.detail:hover {
  background: #eff6ff;
  border-color: #bfdbfe;
}

.action-btn.edit {
  color: #d97706;
}

.action-btn.edit:hover {
  background: #fffbeb;
  border-color: #fde68a;
}

.action-btn.delete {
  color: #dc2626;
}

.action-btn.delete:hover {
  background: #fef2f2;
  border-color: #fecaca;
}

/* EMPTY */

.empty-state {
  min-height: 220px;

  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  padding: 30px;
}

.empty-icon {
  width: 54px;
  height: 54px;

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

@media (max-width: 900px) {
  table {
    min-width: 850px;
  }
}

@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .btn-primary {
    width: 100%;
  }

  .filter-card {
    padding: 12px;
  }

  .search-box {
    max-width: none;
  }
}
</style>