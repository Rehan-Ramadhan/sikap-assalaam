<template>
  <AppLayout>
    <div class="page-container">
      <!-- Header -->
      <div class="page-header">
        <div>
          <button class="back-button" @click="goBack">
            <ArrowLeft :size="18" />
            Kembali
          </button>

          <h1>Catat Prestasi</h1>
          <p>Tambahkan data prestasi siswa.</p>
        </div>
      </div>

      <!-- Form -->
      <div class="form-card">
        <form @submit.prevent="handleSubmit">
          <!-- Data Siswa -->
          <div class="form-section">
            <div class="section-title">
              <UserRound :size="20" />
              <div>
                <h2>Data Siswa</h2>
                <p>Pilih siswa yang memperoleh prestasi.</p>
              </div>
            </div>

            <div class="form-grid">
              <div class="form-group full-width">
                <label for="student">
                  Siswa <span>*</span>
                </label>

                <select id="student" v-model="form.studentId">
                  <option value="">Pilih siswa</option>

                  <option
                    v-for="student in students"
                    :key="student.id"
                    :value="student.id"
                  >
                    {{ student.nama }} - {{ student.nis }}
                  </option>
                </select>

                <small v-if="errors.studentId" class="error-text">
                  {{ errors.studentId }}
                </small>
              </div>
            </div>
          </div>

          <!-- Data Prestasi -->
          <div class="form-section">
            <div class="section-title">
              <Trophy :size="20" />
              <div>
                <h2>Data Prestasi</h2>
                <p>Masukkan informasi prestasi siswa.</p>
              </div>
            </div>

            <div class="form-grid">
              <div class="form-group">
                <label for="achievement">
                  Nama Prestasi <span>*</span>
                </label>

                <select
                  id="achievement"
                  v-model="form.categoryId"
                  @change="handleCategoryChange"
                >
                  <option value="">Pilih prestasi</option>

                  <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                  >
                    {{ category.namaPrestasi }}
                  </option>
                </select>

                <small v-if="errors.categoryId" class="error-text">
                  {{ errors.categoryId }}
                </small>
              </div>

              <div class="form-group">
                <label for="tingkat">Tingkat</label>

                <input
                  id="tingkat"
                  :value="selectedCategory?.tingkat || '-'"
                  type="text"
                  readonly
                  class="readonly-input"
                />
              </div>

              <div class="form-group">
                <label for="poin">Poin</label>

                <div class="point-display">
                  <Plus :size="17" />
                  {{ selectedCategory?.poin || 0 }}
                </div>
              </div>

              <div class="form-group">
                <label for="tanggal">
                  Tanggal Prestasi <span>*</span>
                </label>

                <input
                  id="tanggal"
                  v-model="form.tanggalPrestasi"
                  type="date"
                />

                <small v-if="errors.tanggalPrestasi" class="error-text">
                  {{ errors.tanggalPrestasi }}
                </small>
              </div>

              <div class="form-group full-width">
                <label for="keterangan">Keterangan</label>

                <textarea
                  id="keterangan"
                  v-model="form.keterangan"
                  rows="5"
                  placeholder="Tambahkan keterangan prestasi jika diperlukan..."
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Informasi -->
          <div class="info-box">
            <CircleAlert :size="19" />

            <div>
              <strong>Informasi</strong>
              <p>
                Poin prestasi dicatat sebagai poin prestasi siswa dan
                tidak mengurangi poin pelanggaran.
              </p>
            </div>
          </div>

          <!-- Actions -->
          <div class="form-actions">
            <button
              type="button"
              class="btn btn-secondary"
              @click="goBack"
            >
              Batal
            </button>

            <button type="submit" class="btn btn-primary">
              <Save :size="18" />
              Simpan Prestasi
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, reactive } from 'vue'
import { useRouter } from 'vue-router'

import AppLayout from '../../../layouts/AppLayout.vue'

import {
  ArrowLeft,
  CircleAlert,
  Plus,
  Save,
  Trophy,
  UserRound
} from 'lucide-vue-next'

const router = useRouter()

/*
|--------------------------------------------------------------------------
| Dummy Data
|--------------------------------------------------------------------------
*/

const students = [
  {
    id: 1,
    nama: 'Ahmad Fauzan',
    nis: '2024001',
    tingkat: '11',
    jurusan: 'RPL',
    nomorKelas: 1
  },
  {
    id: 2,
    nama: 'Siti Aisyah',
    nis: '2024002',
    tingkat: '10',
    jurusan: 'RPL',
    nomorKelas: 2
  },
  {
    id: 3,
    nama: 'Muhammad Rizky',
    nis: '2024003',
    tingkat: '12',
    jurusan: 'TKR',
    nomorKelas: 1
  },
  {
    id: 4,
    nama: 'Nurul Hidayah',
    nis: '2024004',
    tingkat: '11',
    jurusan: 'TSM',
    nomorKelas: 2
  }
]

const categories = [
  {
    id: 1,
    namaPrestasi: 'Juara 1 Lomba Akademik',
    poin: 50,
    tingkat: 'sekolah'
  },
  {
    id: 2,
    namaPrestasi: 'Juara 1 Lomba Tingkat Kecamatan',
    poin: 75,
    tingkat: 'kecamatan'
  },
  {
    id: 3,
    namaPrestasi: 'Juara 2 Olimpiade Kabupaten',
    poin: 100,
    tingkat: 'kabupaten'
  },
  {
    id: 4,
    namaPrestasi: 'Juara 1 Kompetisi Provinsi',
    poin: 150,
    tingkat: 'provinsi'
  },
  {
    id: 5,
    namaPrestasi: 'Juara Nasional',
    poin: 250,
    tingkat: 'nasional'
  }
]

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/

const form = reactive({
  studentId: '',
  categoryId: '',
  tanggalPrestasi: '',
  keterangan: ''
})

const errors = reactive({
  studentId: '',
  categoryId: '',
  tanggalPrestasi: ''
})

/*
|--------------------------------------------------------------------------
| Computed
|--------------------------------------------------------------------------
*/

const selectedCategory = computed(() => {
  return categories.find(
    category => category.id === Number(form.categoryId)
  ) || null
})

/*
|--------------------------------------------------------------------------
| Methods
|--------------------------------------------------------------------------
*/

const handleCategoryChange = () => {
  errors.categoryId = ''
}

const validateForm = () => {
  errors.studentId = ''
  errors.categoryId = ''
  errors.tanggalPrestasi = ''

  let valid = true

  if (!form.studentId) {
    errors.studentId = 'Siswa wajib dipilih.'
    valid = false
  }

  if (!form.categoryId) {
    errors.categoryId = 'Prestasi wajib dipilih.'
    valid = false
  }

  if (!form.tanggalPrestasi) {
    errors.tanggalPrestasi = 'Tanggal prestasi wajib diisi.'
    valid = false
  }

  return valid
}

const handleSubmit = () => {
  if (!validateForm()) {
    return
  }

  const student = students.find(
    item => item.id === Number(form.studentId)
  )

  const category = categories.find(
    item => item.id === Number(form.categoryId)
  )

  const achievementData = {
    studentId: Number(form.studentId),
    studentName: student?.nama,
    categoryId: Number(form.categoryId),
    namaPrestasi: category?.namaPrestasi,
    tingkat: category?.tingkat,
    poin: category?.poin,
    tanggalPrestasi: form.tanggalPrestasi,
    keterangan: form.keterangan.trim(),
    status: 'aktif'
  }

  console.log('Data prestasi:', achievementData)

  alert('Data prestasi berhasil disimpan.')

  router.push('/staff/prestasi')
}

const goBack = () => {
  router.push('/staff/prestasi')
}
</script>

<style scoped>
.page-container {
  max-width: 1100px;
  margin: 0 auto;
  width: 100%;
}

.page-header {
  margin-bottom: 24px;
}

.page-header h1 {
  margin: 14px 0 6px;
  font-size: 28px;
  font-weight: 700;
  color: #172033;
}

.page-header p {
  margin: 0;
  color: #718096;
  font-size: 14px;
}

.back-button {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  border: none;
  background: transparent;
  color: #2563eb;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  padding: 0;
}

.back-button:hover {
  color: #1d4ed8;
}

.form-card {
  background: #ffffff;
  border: 1px solid #e8edf5;
  border-radius: 14px;
  overflow: hidden;
}

.form-section {
  padding: 28px;
  border-bottom: 1px solid #edf1f7;
}

.section-title {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  margin-bottom: 24px;
  color: #2563eb;
}

.section-title h2 {
  margin: 0 0 4px;
  color: #172033;
  font-size: 17px;
  font-weight: 700;
}

.section-title p {
  margin: 0;
  color: #718096;
  font-size: 13px;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 20px;
}

.full-width {
  grid-column: 1 / -1;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group label {
  color: #374151;
  font-size: 14px;
  font-weight: 600;
}

.form-group label span {
  color: #dc2626;
}

.form-group input,
.form-group select,
.form-group textarea {
  width: 100%;
  box-sizing: border-box;
  border: 1px solid #d9e0ea;
  border-radius: 9px;
  background: #fff;
  color: #172033;
  font-family: inherit;
  font-size: 14px;
  outline: none;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.form-group input,
.form-group select {
  height: 44px;
  padding: 0 13px;
}

.form-group textarea {
  padding: 12px 13px;
  resize: vertical;
  min-height: 110px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.readonly-input {
  background: #f7f9fc !important;
  color: #64748b !important;
  cursor: not-allowed;
}

.point-display {
  height: 44px;
  box-sizing: border-box;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 0 13px;
  border-radius: 9px;
  background: #eff6ff;
  color: #2563eb;
  font-size: 14px;
  font-weight: 700;
}

.error-text {
  color: #dc2626;
  font-size: 12px;
}

.info-box {
  margin: 24px 28px 0;
  padding: 14px 16px;
  display: flex;
  gap: 12px;
  border: 1px solid #bfdbfe;
  border-radius: 10px;
  background: #eff6ff;
  color: #2563eb;
}

.info-box strong {
  display: block;
  margin-bottom: 4px;
  font-size: 14px;
}

.info-box p {
  margin: 0;
  color: #475569;
  font-size: 13px;
  line-height: 1.6;
}

.form-actions {
  padding: 24px 28px;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  border-top: 1px solid #edf1f7;
  margin-top: 24px;
}

.btn {
  min-height: 42px;
  padding: 0 18px;
  border-radius: 9px;
  border: none;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  font-family: inherit;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s;
}

.btn-secondary {
  background: #f1f5f9;
  color: #475569;
}

.btn-secondary:hover {
  background: #e2e8f0;
}

.btn-primary {
  background: #2563eb;
  color: #fff;
}

.btn-primary:hover {
  background: #1d4ed8;
}

@media (max-width: 768px) {
  .page-container {
    width: 100%;
  }

  .form-section {
    padding: 20px;
  }

  .form-grid {
    grid-template-columns: 1fr;
  }

  .full-width {
    grid-column: auto;
  }

  .info-box {
    margin-left: 20px;
    margin-right: 20px;
  }

  .form-actions {
    padding: 20px;
    flex-direction: column-reverse;
  }

  .btn {
    width: 100%;
  }
}
</style>