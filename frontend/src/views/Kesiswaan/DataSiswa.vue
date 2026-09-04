<template>
  <AppLayout role="kesiswaan">

    <div class="page-header">
      <div>
        <span class="section-label">DATA MASTER</span>
        <h1>Data Siswa</h1>
        <p>Kelola data siswa Assalaam</p>
      </div>

      <button class="add-btn" @click="openAddModal">
        <Plus :size="17" />
        Tambah Siswa
      </button>
    </div>


    <!-- TABLE CARD -->
    <div class="table-card">

      <!-- TOOLBAR -->
      <div class="table-toolbar">

        <div class="search-box">
          <Search :size="17" />

          <input
            v-model="search"
            type="text"
            placeholder="Cari nama atau NIS..."
          />
        </div>

        <div class="student-total">
          Total <strong>{{ filteredStudents.length }}</strong> siswa
        </div>

      </div>


      <!-- TABLE -->
      <div class="table-wrapper">

        <table>

          <thead>
            <tr>
              <th>No</th>
              <th>Nama</th>
              <th>NIS</th>
              <th>Tingkat</th>
              <th>Jurusan</th>
              <th>Nomor Kelas</th>
              <th>Jenis Kelamin</th>
              <th>Aksi</th>
            </tr>
          </thead>

          <tbody>

            <tr
              v-for="(student, index) in filteredStudents"
              :key="student.id"
            >

              <td>{{ index + 1 }}</td>

              <td>
                <div class="student-name">
                  <div class="student-avatar">
                    {{ getInitial(student.nama) }}
                  </div>

                  <strong>{{ student.nama }}</strong>
                </div>
              </td>

              <td>{{ student.nis }}</td>

              <td>{{ student.tingkat }}</td>

              <td>{{ student.jurusan }}</td>

              <td>
                <span class="class-badge">
                  {{ student.nomorKelas }}
                </span>
              </td>

              <td>{{ student.jenisKelamin }}</td>

              <td>
                <div class="action-buttons">

                    <button
                        class="detail-btn"
                        @click="openDetail(student)"
                    >
                        <Eye :size="15" />
                        Detail
                    </button>

                    <button
                        class="edit-btn"
                        @click="openEditModal(student)"
                        title="Edit siswa"
                    >
                        <Pencil :size="15" />
                    </button>

                    <button
                        class="delete-btn"
                        @click="deleteStudent(student)"
                        title="Hapus siswa"
                    >
                        <Trash2 :size="15" />
                    </button>

                    </div>
              </td>

            </tr>


            <!-- EMPTY -->
            <tr v-if="filteredStudents.length === 0">

              <td colspan="8">

                <div class="empty-state">

                  <div class="empty-icon">
                    <Users :size="25" />
                  </div>

                  <strong>Data siswa tidak ditemukan</strong>

                  <span>
                    Coba gunakan kata pencarian yang berbeda.
                  </span>

                </div>

              </td>

            </tr>

          </tbody>

        </table>

      </div>

    </div>


    <!-- DETAIL MODAL -->
    <div
      v-if="selectedStudent"
      class="modal-overlay"
      @click.self="closeDetail"
    >

      <div class="detail-modal">

        <div class="modal-header">

          <div>
            <span class="section-label">DETAIL SISWA</span>
            <h2>Informasi Siswa</h2>
          </div>

          <button
            class="close-btn"
            @click="closeDetail"
          >
            <X :size="19" />
          </button>

        </div>


        <!-- PROFILE -->
        <div class="student-profile">

          <div class="profile-avatar">
            {{ getInitial(selectedStudent.nama) }}
          </div>

          <div>
            <h3>{{ selectedStudent.nama }}</h3>
            <span>NIS {{ selectedStudent.nis }}</span>
          </div>

        </div>


        <!-- DETAIL GRID -->
        <div class="detail-grid">

          <div class="detail-item">
            <span>NIS</span>
            <strong>{{ selectedStudent.nis }}</strong>
          </div>

          <div class="detail-item">
            <span>Nama</span>
            <strong>{{ selectedStudent.nama }}</strong>
          </div>

          <div class="detail-item">
            <span>Tingkat</span>
            <strong>{{ selectedStudent.tingkat }}</strong>
          </div>

          <div class="detail-item">
            <span>Jurusan</span>
            <strong>{{ selectedStudent.jurusan }}</strong>
          </div>

          <div class="detail-item">
            <span>Nomor Kelas</span>
            <strong>{{ selectedStudent.nomorKelas }}</strong>
          </div>

          <div class="detail-item">
            <span>Jenis Kelamin</span>
            <strong>{{ selectedStudent.jenisKelamin }}</strong>
          </div>

          <div class="detail-item">
            <span>Tahun Masuk</span>
            <strong>{{ selectedStudent.tahunMasuk }}</strong>
          </div>

          <div class="detail-item">
            <span>Status Siswa</span>

            <span
              class="status-badge"
              :class="getStatusClass(selectedStudent.status)"
            >
              {{ selectedStudent.status }}
            </span>
          </div>

        </div>


        <div class="modal-footer">

          <button
            class="modal-close-btn"
            @click="closeDetail"
          >
            Tutup
          </button>

          <button
            class="modal-edit-btn"
            @click="openEditFromDetail"
          >
            <Pencil :size="15" />
            Edit Data
          </button>

        </div>

      </div>

    </div>


    <!-- ADD / EDIT MODAL -->
    <div
      v-if="showForm"
      class="modal-overlay"
      @click.self="closeForm"
    >

      <div class="form-modal">

        <div class="modal-header">

          <div>
            <span class="section-label">
              {{ isEdit ? 'EDIT DATA' : 'DATA BARU' }}
            </span>

            <h2>
              {{ isEdit ? 'Edit Siswa' : 'Tambah Siswa' }}
            </h2>
          </div>

          <button
            class="close-btn"
            @click="closeForm"
          >
            <X :size="19" />
          </button>

        </div>


        <form @submit.prevent="saveStudent">

          <div class="form-grid">

            <div class="form-group form-full">
              <label>Nama Siswa</label>

              <input
                v-model="form.nama"
                type="text"
                placeholder="Masukkan nama siswa"
                required
              />
            </div>


            <div class="form-group">
              <label>NIS</label>

              <input
                v-model="form.nis"
                type="text"
                placeholder="Contoh: 23001"
                required
              />
            </div>


            <div class="form-group">
              <label>Tingkat</label>

              <select v-model="form.tingkat" required>
                <option value="">Pilih tingkat</option>
                <option value="X">X</option>
                <option value="XI">XI</option>
                <option value="XII">XII</option>
              </select>
            </div>


            <div class="form-group">
              <label>Jurusan</label>

              <input
                v-model="form.jurusan"
                type="text"
                placeholder="Contoh: IPA"
                required
              />
            </div>


            <div class="form-group">
              <label>Nomor Kelas</label>

              <input
                v-model="form.nomorKelas"
                type="text"
                placeholder="Contoh: 1"
                required
              />
            </div>


            <div class="form-group">
              <label>Jenis Kelamin</label>

              <select
                v-model="form.jenisKelamin"
                required
              >
                <option value="">Pilih jenis kelamin</option>
                <option value="Laki-laki">Laki-laki</option>
                <option value="Perempuan">Perempuan</option>
              </select>
            </div>


            <div class="form-group">
              <label>Tahun Masuk</label>

              <input
                v-model="form.tahunMasuk"
                type="number"
                placeholder="Contoh: 2023"
                required
              />
            </div>


            <div class="form-group">
              <label>Status Siswa</label>

              <select
                v-model="form.status"
                required
              >
                <option value="">Pilih status</option>
                <option value="Aktif">Aktif</option>
                <option value="Nonaktif">Nonaktif</option>
                <option value="Lulus">Lulus</option>
              </select>
            </div>

          </div>


          <div class="modal-footer">

            <button
              type="button"
              class="modal-close-btn"
              @click="closeForm"
            >
              Batal
            </button>

            <button
              type="submit"
              class="modal-edit-btn"
            >
              <Save :size="15" />
              {{ isEdit ? 'Simpan Perubahan' : 'Simpan Siswa' }}
            </button>

          </div>

        </form>

      </div>

    </div>

  </AppLayout>
</template>


<script setup>
import { ref, computed } from 'vue'

import AppLayout from '../../components/AppLayout.vue'

import {
  Search,
  Plus,
  Eye,
  Pencil,
  X,
  Users,
  Save,
  Trash2
} from 'lucide-vue-next'

/* =========================
   DATA DUMMY
========================= */

const students = ref([
  {
    id: 1,
    nama: 'Ahmad Fauzan',
    nis: '23001',
    tingkat: 'X',
    jurusan: 'IPA',
    nomorKelas: '1',
    jenisKelamin: 'Laki-laki',
    tahunMasuk: '2023',
    status: 'Aktif'
  },
  {
    id: 2,
    nama: 'Fajar Ramadhan',
    nis: '23002',
    tingkat: 'X',
    jurusan: 'IPS',
    nomorKelas: '2',
    jenisKelamin: 'Laki-laki',
    tahunMasuk: '2023',
    status: 'Aktif'
  },
  {
    id: 3,
    nama: 'Muhammad Rizky',
    nis: '22015',
    tingkat: 'XI',
    jurusan: 'IPA',
    nomorKelas: '1',
    jenisKelamin: 'Laki-laki',
    tahunMasuk: '2022',
    status: 'Aktif'
  },
  {
    id: 4,
    nama: 'Siti Aisyah',
    nis: '22018',
    tingkat: 'XI',
    jurusan: 'IPS',
    nomorKelas: '2',
    jenisKelamin: 'Perempuan',
    tahunMasuk: '2022',
    status: 'Aktif'
  },
  {
    id: 5,
    nama: 'Rafi Maulana',
    nis: '21008',
    tingkat: 'XII',
    jurusan: 'IPA',
    nomorKelas: '1',
    jenisKelamin: 'Laki-laki',
    tahunMasuk: '2021',
    status: 'Lulus'
  }
])


/* =========================
   SEARCH
========================= */

const search = ref('')

const filteredStudents = computed(() => {
  const keyword = search.value.toLowerCase().trim()

  if (!keyword) {
    return students.value
  }

  return students.value.filter(student =>
    student.nama.toLowerCase().includes(keyword) ||
    student.nis.toLowerCase().includes(keyword)
  )
})


/* =========================
   DETAIL
========================= */

const selectedStudent = ref(null)

const openDetail = (student) => {
  selectedStudent.value = student
}

const closeDetail = () => {
  selectedStudent.value = null
}


/* =========================
   FORM
========================= */

const showForm = ref(false)
const isEdit = ref(false)

const form = ref({
  id: null,
  nama: '',
  nis: '',
  tingkat: '',
  jurusan: '',
  nomorKelas: '',
  jenisKelamin: '',
  tahunMasuk: '',
  status: 'Aktif'
})


const resetForm = () => {
  form.value = {
    id: null,
    nama: '',
    nis: '',
    tingkat: '',
    jurusan: '',
    nomorKelas: '',
    jenisKelamin: '',
    tahunMasuk: '',
    status: 'Aktif'
  }
}


const openAddModal = () => {
  isEdit.value = false

  resetForm()

  showForm.value = true
}


const openEditModal = (student) => {
  isEdit.value = true

  form.value = {
    ...student
  }

  showForm.value = true
}


const openEditFromDetail = () => {
  const student = selectedStudent.value

  closeDetail()

  openEditModal(student)
}


const closeForm = () => {
  showForm.value = false

  resetForm()
}


const saveStudent = () => {

  if (isEdit.value) {

    const index = students.value.findIndex(
      student => student.id === form.value.id
    )

    if (index !== -1) {
      students.value[index] = {
        ...form.value
      }
    }

  } else {

    const newStudent = {
      ...form.value,
      id: Date.now()
    }

    students.value.push(newStudent)
  }

  closeForm()
}

const deleteStudent = (student) => {
  const confirmed = window.confirm(
    `Apakah kamu yakin ingin menghapus siswa "${student.nama}"?`
  )

  if (!confirmed) {
    return
  }

  students.value = students.value.filter(
    item => item.id !== student.id
  )
}

/* =========================
   HELPERS
========================= */

const getInitial = (name) => {
  return name
    .split(' ')
    .map(word => word[0])
    .slice(0, 2)
    .join('')
    .toUpperCase()
}


const getStatusClass = (status) => {
  if (status === 'Aktif') {
    return 'active'
  }

  if (status === 'Nonaktif') {
    return 'inactive'
  }

  if (status === 'Lulus') {
    return 'graduated'
  }

  return ''
}
</script>


<style scoped>

/* =========================
   PAGE HEADER
========================= */

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 20px;
}

.page-header h1 {
  margin: 0;
  font-size: 25px;
  color: #0f172a;
}

.page-header p {
  margin: 5px 0 0;
  color: #94a3b8;
  font-size: 12px;
}

.section-label {
  display: inline-block;
  margin-bottom: 5px;
  font-size: 9px;
  font-weight: 700;
  letter-spacing: 1px;
  color: #2563eb;
}


/* =========================
   ADD BUTTON
========================= */

.add-btn {
  display: flex;
  align-items: center;
  gap: 7px;

  border: none;
  border-radius: 10px;

  padding: 10px 15px;

  background: #2563eb;
  color: white;

  font-size: 12px;
  font-weight: 600;

  cursor: pointer;

  box-shadow: 0 5px 14px rgba(37, 99, 235, 0.2);

  transition: all 0.2s ease;
}

.add-btn:hover {
  background: #1d4ed8;
  transform: translateY(-1px);
}


/* =========================
   TABLE CARD
========================= */

.table-card {
  background: white;
  border: 1px solid #e8edf5;
  border-radius: 18px;
  box-shadow: 0 4px 18px rgba(15, 23, 42, 0.04);
  overflow: hidden;
}


/* =========================
   TOOLBAR
========================= */

.table-toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 15px;

  padding: 18px 20px;

  border-bottom: 1px solid #eef2f7;
}

.search-box {
  width: 320px;

  display: flex;
  align-items: center;
  gap: 9px;

  padding: 9px 12px;

  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 9px;

  color: #94a3b8;
}

.search-box:focus-within {
  border-color: #93c5fd;
  background: white;
}

.search-box input {
  width: 100%;

  border: none;
  outline: none;
  background: transparent;

  font-size: 12px;
  color: #334155;
}

.search-box input::placeholder {
  color: #94a3b8;
}

.student-total {
  font-size: 11px;
  color: #94a3b8;
}

.student-total strong {
  color: #2563eb;
}


/* =========================
   TABLE
========================= */

.table-wrapper {
  width: 100%;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  min-width: 950px;
}

thead {
  background: #f8fafc;
}

th {
  padding: 13px 16px;

  text-align: left;

  font-size: 10px;
  font-weight: 700;
  color: #64748b;

  white-space: nowrap;
}

td {
  padding: 13px 16px;

  border-top: 1px solid #f1f5f9;

  font-size: 12px;
  color: #475569;

  white-space: nowrap;
}

tbody tr {
  transition: background 0.15s ease;
}

tbody tr:hover {
  background: #f8fbff;
}


/* =========================
   STUDENT
========================= */

.student-name {
  display: flex;
  align-items: center;
  gap: 9px;
}

.student-name strong {
  color: #1e293b;
  font-size: 12px;
}

.student-avatar {
  width: 31px;
  height: 31px;

  display: flex;
  align-items: center;
  justify-content: center;

  flex-shrink: 0;

  border-radius: 9px;

  background: #eff6ff;
  color: #2563eb;

  font-size: 10px;
  font-weight: 700;
}

.class-badge {
  display: inline-flex;

  padding: 4px 8px;

  border-radius: 6px;

  background: #f1f5f9;
  color: #475569;

  font-size: 10px;
  font-weight: 600;
}


/* =========================
   ACTION
========================= */

.action-buttons {
  display: flex;
  align-items: center;
  gap: 6px;
}

.detail-btn,
.edit-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;

  border: none;
  cursor: pointer;

  border-radius: 7px;
}

.detail-btn {
  gap: 5px;

  padding: 7px 9px;

  background: #eff6ff;
  color: #2563eb;

  font-size: 10px;
  font-weight: 600;
}

.detail-btn:hover {
  background: #dbeafe;
}

.edit-btn {
  width: 30px;
  height: 30px;

  background: #f8fafc;
  color: #64748b;
}

.edit-btn:hover {
  background: #f1f5f9;
  color: #2563eb;
}
.delete-btn {
  width: 30px;
  height: 30px;

  display: inline-flex;
  align-items: center;
  justify-content: center;

  border: none;
  border-radius: 7px;

  background: #fff1f2;
  color: #ef4444;

  cursor: pointer;

  transition: all 0.2s ease;
}

.delete-btn:hover {
  background: #fee2e2;
  color: #dc2626;
}

/* =========================
   EMPTY
========================= */

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  padding: 55px 20px;
}

.empty-icon {
  width: 50px;
  height: 50px;

  display: flex;
  align-items: center;
  justify-content: center;

  margin-bottom: 12px;

  border-radius: 13px;

  background: #eff6ff;
  color: #2563eb;
}

.empty-state strong {
  font-size: 13px;
  color: #334155;
}

.empty-state span {
  margin-top: 4px;
  font-size: 11px;
  color: #94a3b8;
}


/* =========================
   MODAL
========================= */

.modal-overlay {
  position: fixed;
  inset: 0;

  z-index: 1000;

  display: flex;
  align-items: center;
  justify-content: center;

  padding: 20px;

  background: rgba(15, 23, 42, 0.45);

  backdrop-filter: blur(3px);
}

.detail-modal,
.form-modal {
  width: 100%;
  max-width: 650px;

  max-height: 90vh;

  overflow-y: auto;

  background: white;

  border-radius: 18px;

  box-shadow: 0 25px 60px rgba(15, 23, 42, 0.2);
}

.modal-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;

  padding: 22px;

  border-bottom: 1px solid #eef2f7;
}

.modal-header h2 {
  margin: 0;

  font-size: 19px;
  color: #0f172a;
}

.close-btn {
  width: 32px;
  height: 32px;

  display: flex;
  align-items: center;
  justify-content: center;

  border: none;
  border-radius: 8px;

  background: #f8fafc;
  color: #64748b;

  cursor: pointer;
}

.close-btn:hover {
  background: #f1f5f9;
}


/* =========================
   PROFILE
========================= */

.student-profile {
  display: flex;
  align-items: center;
  gap: 13px;

  margin: 20px 22px;

  padding: 15px;

  background: #eff6ff;

  border-radius: 13px;
}

.profile-avatar {
  width: 48px;
  height: 48px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 12px;

  background: #2563eb;
  color: white;

  font-size: 14px;
  font-weight: 700;
}

.student-profile h3 {
  margin: 0;

  font-size: 15px;
  color: #1e293b;
}

.student-profile span {
  display: block;

  margin-top: 4px;

  font-size: 11px;
  color: #64748b;
}


/* =========================
   DETAIL GRID
========================= */

.detail-grid {
  display: grid;

  grid-template-columns: 1fr 1fr;

  gap: 1px;

  margin: 0 22px;

  background: #e2e8f0;

  border: 1px solid #e2e8f0;

  border-radius: 12px;

  overflow: hidden;
}

.detail-item {
  display: flex;
  flex-direction: column;
  gap: 5px;

  padding: 13px;

  background: white;
}

.detail-item span:first-child {
  font-size: 10px;
  color: #94a3b8;
}

.detail-item strong {
  font-size: 12px;
  color: #334155;
}


/* =========================
   STATUS
========================= */

.status-badge {
  width: fit-content;

  padding: 4px 8px;

  border-radius: 6px;

  font-size: 10px !important;
  font-weight: 600;
}

.status-badge.active {
  background: #dcfce7;
  color: #15803d;
}

.status-badge.inactive {
  background: #fee2e2;
  color: #dc2626;
}

.status-badge.graduated {
  background: #e0e7ff;
  color: #4338ca;
}


/* =========================
   FORM
========================= */

.form-modal {
  max-width: 700px;
}

.form-grid {
  display: grid;

  grid-template-columns: 1fr 1fr;

  gap: 15px;

  padding: 22px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-full {
  grid-column: 1 / -1;
}

.form-group label {
  font-size: 11px;
  font-weight: 600;
  color: #475569;
}

.form-group input,
.form-group select {
  width: 100%;
  box-sizing: border-box;

  border: 1px solid #e2e8f0;

  border-radius: 8px;

  padding: 10px 11px;

  outline: none;

  background: white;

  color: #334155;

  font-size: 12px;
}

.form-group input:focus,
.form-group select:focus {
  border-color: #60a5fa;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
}


/* =========================
   MODAL FOOTER
========================= */

.modal-footer {
  display: flex;
  justify-content: flex-end;
  gap: 8px;

  padding: 17px 22px;

  border-top: 1px solid #eef2f7;
}

.modal-close-btn,
.modal-edit-btn {
  display: inline-flex;
  align-items: center;
  gap: 6px;

  border: none;

  border-radius: 8px;

  padding: 9px 13px;

  font-size: 11px;
  font-weight: 600;

  cursor: pointer;
}

.modal-close-btn {
  background: #f1f5f9;
  color: #475569;
}

.modal-edit-btn {
  background: #2563eb;
  color: white;
}

.modal-edit-btn:hover {
  background: #1d4ed8;
}


/* =========================
   RESPONSIVE
========================= */

@media (max-width: 700px) {

  .page-header {
    align-items: flex-start;
    flex-direction: column;
  }

  .add-btn {
    width: 100%;
    justify-content: center;
  }

  .table-toolbar {
    align-items: stretch;
    flex-direction: column;
  }

  .search-box {
    width: auto;
  }

  .detail-grid {
    grid-template-columns: 1fr;
  }

  .form-grid {
    grid-template-columns: 1fr;
  }

  .form-full {
    grid-column: auto;
  }

}

</style>