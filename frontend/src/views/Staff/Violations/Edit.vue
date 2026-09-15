<template>
  <AppLayout>
    <div class="form-page">

      <div class="page-header">
        <div>
          <span class="eyebrow">PELANGGARAN</span>
          <h1>Edit Pelanggaran</h1>
          <p>Perbarui informasi catatan pelanggaran siswa.</p>
        </div>

        <button class="back-btn" @click="goBack">
          <ArrowLeft :size="17" />
          Kembali
        </button>
      </div>

      <form class="form-card" @submit.prevent="updateViolation">

        <div class="form-section">

          <div class="section-heading">
            <div class="section-icon">
              <UserRound :size="18" />
            </div>

            <div>
              <h2>Informasi Siswa</h2>
              <p>Informasi siswa yang melakukan pelanggaran.</p>
            </div>
          </div>

          <div class="student-preview">
            <div class="avatar">
              {{ student.name.charAt(0) }}
            </div>

            <div>
              <strong>{{ student.name }}</strong>
              <span>NIS {{ student.nis }}</span>
            </div>
          </div>

        </div>

        <div class="form-section">

          <div class="section-heading">
            <div class="section-icon">
              <AlertTriangle :size="18" />
            </div>

            <div>
              <h2>Detail Pelanggaran</h2>
              <p>Perbarui informasi pelanggaran.</p>
            </div>
          </div>

          <div class="form-grid">

            <div class="form-group">
              <label>Kategori Pelanggaran <span>*</span></label>

              <select v-model="form.categoryId" required>
                <option
                  v-for="category in categories"
                  :key="category.id"
                  :value="category.id"
                >
                  {{ category.name }} — {{ category.points }} poin
                </option>
              </select>
            </div>

            <div class="form-group">
              <label>Tanggal Kejadian <span>*</span></label>

              <input
                v-model="form.date"
                type="date"
                required
              />
            </div>

            <div class="form-group full">
              <label>Keterangan</label>

              <textarea
                v-model="form.description"
                rows="5"
                placeholder="Tambahkan keterangan..."
              ></textarea>
            </div>

          </div>
        </div>

        <div v-if="selectedCategory" class="preview-box">

          <div class="preview-icon">
            <AlertTriangle :size="19" />
          </div>

          <div>
            <strong>{{ selectedCategory.name }}</strong>

            <span>
              Kategori {{ capitalize(selectedCategory.category) }}
              · {{ selectedCategory.points }} poin
            </span>
          </div>

        </div>

        <div class="form-footer">

          <button
            type="button"
            class="secondary-btn"
            @click="goBack"
          >
            Batal
          </button>

          <button type="submit" class="primary-btn">
            <Save :size="17" />
            Simpan Perubahan
          </button>

        </div>

      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'

import AppLayout from '../../../layouts/AppLayout.vue'

import {
  ArrowLeft,
  UserRound,
  AlertTriangle,
  Save
} from 'lucide-vue-next'

const router = useRouter()
const route = useRoute()

const student = {
  name: 'Muhammad Rizky',
  nis: '2026002'
}

const categories = [
  {
    id: 1,
    name: 'Terlambat Masuk Sekolah',
    category: 'ringan',
    points: 5
  },
  {
    id: 2,
    name: 'Tidak Menggunakan Atribut Lengkap',
    category: 'sedang',
    points: 10
  },
  {
    id: 3,
    name: 'Meninggalkan Kelas Tanpa Izin',
    category: 'sedang',
    points: 15
  },
  {
    id: 4,
    name: 'Berkelahi di Lingkungan Sekolah',
    category: 'berat',
    points: 30
  },
  {
    id: 5,
    name: 'Membawa Barang Terlarang',
    category: 'berat',
    points: 40
  }
]

const form = ref({
  categoryId: 2,
  date: '2026-09-14',
  description:
    'Siswa tidak menggunakan atribut sekolah lengkap saat mengikuti kegiatan belajar.'
})

const selectedCategory = computed(() => {
  return categories.find(
    item => item.id === Number(form.value.categoryId)
  )
})

const capitalize = (value) => {
  return value.charAt(0).toUpperCase() + value.slice(1)
}

const goBack = () => {
  router.push(`/staff/pelanggaran/${route.params.id}`)
}

const updateViolation = () => {
  alert(
    'Mode frontend: perubahan dummy belum dikirim ke server Laravel.'
  )

  router.push(`/staff/pelanggaran/${route.params.id}`)
}
</script>

<style scoped>
.form-page {
  max-width: 950px;
  margin: 0 auto;
  padding: 28px;
}

.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 24px;
}

.eyebrow {
  display: block;
  margin-bottom: 7px;
  color: #2563eb;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: .08em;
}

.page-header h1 {
  margin: 0;
  color: #172033;
  font-size: 27px;
  font-weight: 600;
}

.page-header p {
  margin: 7px 0 0;
  color: #64748b;
  font-size: 14px;
}

.back-btn,
.secondary-btn,
.primary-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border-radius: 9px;
  font-family: inherit;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}

.back-btn {
  padding: 10px 14px;
  border: 1px solid #dfe5ed;
  background: white;
  color: #475569;
}

.form-card {
  overflow: hidden;
  border: 1px solid #e8edf5;
  border-radius: 14px;
  background: white;
}

.form-section {
  padding: 24px;
  border-bottom: 1px solid #edf1f6;
}

.section-heading {
  display: flex;
  gap: 12px;
  margin-bottom: 20px;
}

.section-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 38px;
  height: 38px;
  border-radius: 10px;
  background: #eff6ff;
  color: #2563eb;
}

.section-heading h2 {
  margin: 0;
  color: #172033;
  font-size: 16px;
  font-weight: 600;
}

.section-heading p {
  margin: 4px 0 0;
  color: #94a3b8;
  font-size: 12px;
}

.student-preview {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 13px;
  border: 1px solid #e8edf5;
  border-radius: 10px;
  background: #fafbfc;
}

.avatar {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: #eff6ff;
  color: #2563eb;
  font-weight: 600;
}

.student-preview strong {
  display: block;
  margin-bottom: 3px;
  color: #334155;
  font-size: 13px;
  font-weight: 500;
}

.student-preview span {
  color: #94a3b8;
  font-size: 11px;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 18px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 7px;
}

.form-group.full {
  grid-column: 1 / -1;
}

.form-group label {
  color: #475569;
  font-size: 13px;
  font-weight: 500;
}

.form-group label span {
  color: #dc2626;
}

.form-group input,
.form-group select,
.form-group textarea {
  box-sizing: border-box;
  width: 100%;
  border: 1px solid #dfe5ed;
  border-radius: 9px;
  outline: none;
  background: white;
  color: #172033;
  font-family: inherit;
  font-size: 13px;
}

.form-group input,
.form-group select {
  height: 42px;
  padding: 0 12px;
}

.form-group textarea {
  padding: 12px;
  resize: vertical;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px #eff6ff;
}

.preview-box {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 20px 24px 0;
  padding: 14px;
  border: 1px solid #dbeafe;
  border-radius: 10px;
  background: #eff6ff;
}

.preview-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 38px;
  height: 38px;
  border-radius: 9px;
  background: white;
  color: #2563eb;
}

.preview-box strong {
  display: block;
  margin-bottom: 3px;
  color: #1e3a8a;
  font-size: 13px;
}

.preview-box span {
  color: #64748b;
  font-size: 12px;
}

.form-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 18px 24px;
  background: #fafbfc;
}

.secondary-btn {
  padding: 10px 16px;
  border: 1px solid #dfe5ed;
  background: white;
  color: #475569;
}

.primary-btn {
  padding: 10px 16px;
  border: none;
  background: #2563eb;
  color: white;
}

.primary-btn:hover {
  background: #1d4ed8;
}

@media (max-width: 650px) {
  .form-page {
    padding: 18px;
  }

  .page-header {
    align-items: flex-start;
    flex-direction: column;
  }

  .form-grid {
    grid-template-columns: 1fr;
  }

  .form-group.full {
    grid-column: auto;
  }
}
</style>