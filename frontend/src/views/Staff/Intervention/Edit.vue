<template>
  <AppLayout>
    <div class="page-container">
      <!-- Back -->
      <button class="back-button" @click="goBack">
        <ArrowLeft :size="18" />
        Kembali
      </button>

      <!-- Header -->
      <div class="page-header">
        <div>
          <h1>Edit Penanganan</h1>
          <p>
            Perbarui status dan catatan proses penanganan siswa.
          </p>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="state-card">
        <LoaderCircle :size="30" class="loading-icon" />
        <strong>Memuat data...</strong>
        <span>Silakan tunggu sebentar.</span>
      </div>

      <!-- Not Found -->
      <div v-else-if="!intervention" class="state-card">
        <ClipboardList :size="40" />
        <strong>Data penanganan tidak ditemukan</strong>
        <span>Data yang Anda cari tidak tersedia.</span>

        <button class="secondary-button" @click="goBack">
          Kembali ke Penanganan
        </button>
      </div>

      <!-- Form -->
      <template v-else>
        <form class="form-card" @submit.prevent="handleSubmit">
          <!-- Student Summary -->
          <div class="student-summary">
            <div class="student-avatar">
              {{ getInitial(intervention.siswa.nama) }}
            </div>

            <div class="student-info">
              <strong>{{ intervention.siswa.nama }}</strong>
              <span>
                NIS {{ intervention.siswa.nis }}
                · {{ intervention.siswa.tingkat }}
                · Kelas {{ intervention.siswa.kelas }}
              </span>
            </div>
          </div>

          <!-- Information -->
          <div class="section">
            <div class="section-header">
              <div>
                <h3>Informasi Penanganan</h3>
                <p>
                  Informasi ini tidak dapat diubah dari halaman ini.
                </p>
              </div>

              <ClipboardList :size="20" />
            </div>

            <div class="info-grid">
              <div class="info-item">
                <span>Pelanggaran</span>
                <strong>{{ intervention.pelanggaran }}</strong>
              </div>

              <div class="info-item">
                <span>Poin Saat Penanganan</span>
                <strong class="point-value">
                  {{ intervention.poin }} poin
                </strong>
              </div>

              <div class="info-item">
                <span>Tahap</span>

                <strong>
                  <span
                    class="stage-badge"
                    :class="getStageClass(intervention.tahap)"
                  >
                    {{ getStageLabel(intervention.tahap) }}
                  </span>
                </strong>
              </div>

              <div class="info-item">
                <span>Tanggal Mulai</span>
                <strong>{{ intervention.tanggalMulai }}</strong>
              </div>

              <div class="info-item">
                <span>Petugas</span>
                <strong>{{ intervention.petugas }}</strong>
              </div>

              <div class="info-item">
                <span>Threshold</span>
                <strong>{{ intervention.threshold }}</strong>
              </div>
            </div>
          </div>

          <!-- Editable -->
          <div class="section">
            <div class="section-header">
              <div>
                <h3>Pembaruan Penanganan</h3>
                <p>
                  Ubah status dan tambahkan catatan penanganan.
                </p>
              </div>

              <FilePenLine :size="20" />
            </div>

            <!-- Status -->
            <div class="form-group">
              <label for="status">
                Status <span>*</span>
              </label>

              <select
                id="status"
                v-model="form.status"
                :class="{ error: errors.status }"
              >
                <option value="">Pilih status</option>
                <option value="menunggu">Menunggu</option>
                <option value="diproses">Diproses</option>
                <option value="selesai">Selesai</option>
              </select>

              <small v-if="errors.status" class="error-message">
                {{ errors.status }}
              </small>
            </div>

            <!-- Catatan -->
            <div class="form-group">
              <label for="catatan">
                Catatan
              </label>

              <textarea
                id="catatan"
                v-model="form.catatan"
                rows="6"
                placeholder="Tuliskan catatan mengenai proses penanganan..."
                :class="{ error: errors.catatan }"
              ></textarea>

              <div class="textarea-footer">
                <small v-if="errors.catatan" class="error-message">
                  {{ errors.catatan }}
                </small>

                <small class="character-count">
                  {{ form.catatan.length }} karakter
                </small>
              </div>
            </div>
          </div>

          <!-- Info -->
          <div class="info-box">
            <Info :size="18" />

            <div>
              <strong>Informasi</strong>
              <p>
                Setiap perubahan status akan dicatat sebagai riwayat
                penanganan dan dapat dilihat pada halaman detail.
              </p>
            </div>
          </div>

          <!-- Actions -->
          <div class="form-actions">
            <button
              type="button"
              class="secondary-button"
              @click="goBack"
            >
              Batal
            </button>

            <button
              type="submit"
              class="primary-button"
              :disabled="saving"
            >
              <LoaderCircle
                v-if="saving"
                :size="17"
                class="button-loading"
              />

              <Save v-else :size="17" />

              {{ saving ? 'Menyimpan...' : 'Simpan Perubahan' }}
            </button>
          </div>
        </form>
      </template>
    </div>
  </AppLayout>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import {
  ArrowLeft,
  ClipboardList,
  FilePenLine,
  Info,
  Save,
  LoaderCircle
} from 'lucide-vue-next'

import AppLayout from '../../../layouts/AppLayout.vue'

const route = useRoute()
const router = useRouter()

const loading = ref(true)
const saving = ref(false)

const intervention = ref(null)

const form = ref({
  status: '',
  catatan: ''
})

const errors = ref({
  status: '',
  catatan: ''
})

/*
|--------------------------------------------------------------------------
| Dummy Data
|--------------------------------------------------------------------------
| Sementara menggunakan data dummy.
| Nanti akan diganti dengan GET API.
*/

const dummyData = [
  {
    id: 1,

    siswa: {
      nama: 'Ahmad Fauzan',
      nis: '2024001',
      tingkat: 'Kelas 10',
      jurusan: 'RPL',
      kelas: '1'
    },

    pelanggaran: 'Terlambat masuk sekolah',
    poin: 10,

    tahap: 'wali_kelas',
    status: 'diproses',

    petugas: 'Budi Santoso',
    jabatan: 'Wali Kelas',

    threshold: '10 poin',

    tanggalMulai: '10 Sep 2026',

    catatan:
      'Siswa diberikan pembinaan terkait kedisiplinan waktu masuk sekolah.'
  },

  {
    id: 2,

    siswa: {
      nama: 'Rizky Ramadhan',
      nis: '2024002',
      tingkat: 'Kelas 11',
      jurusan: 'TKR',
      kelas: '2'
    },

    pelanggaran: 'Tidak mengikuti kegiatan sekolah',
    poin: 20,

    tahap: 'bk',
    status: 'menunggu',

    petugas: 'Siti Aminah',
    jabatan: 'BK',

    threshold: '20 poin',

    tanggalMulai: '9 Sep 2026',

    catatan: ''
  },

  {
    id: 3,

    siswa: {
      nama: 'Dimas Saputra',
      nis: '2024003',
      tingkat: 'Kelas 12',
      jurusan: 'TSM',
      kelas: '1'
    },

    pelanggaran: 'Membawa barang terlarang',
    poin: 30,

    tahap: 'kesiswaan',
    status: 'diproses',

    petugas: 'Andi Pratama',
    jabatan: 'Kesiswaan',

    threshold: '30 poin',

    tanggalMulai: '8 Sep 2026',

    catatan:
      'Siswa sedang dalam proses pembinaan oleh bagian kesiswaan.'
  },

  {
    id: 4,

    siswa: {
      nama: 'Fajar Maulana',
      nis: '2024004',
      tingkat: 'Kelas 12',
      jurusan: 'RPL',
      kelas: '2'
    },

    pelanggaran: 'Bolos sekolah',
    poin: 40,

    tahap: 'kepala_sekolah',
    status: 'selesai',

    petugas: 'Drs. Ahmad Hidayat',
    jabatan: 'Kepala Sekolah',

    threshold: '40 poin',

    tanggalMulai: '5 Sep 2026',

    catatan:
      'Penanganan telah selesai setelah dilakukan pembinaan bersama siswa dan orang tua.'
  }
]

/*
|--------------------------------------------------------------------------
| Load Data
|--------------------------------------------------------------------------
*/

onMounted(() => {
  const id = Number(route.params.id)

  const data = dummyData.find(item => item.id === id)

  if (data) {
    intervention.value = data

    form.value.status = data.status
    form.value.catatan = data.catatan || ''
  }

  loading.value = false
})

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

const validateForm = () => {
  errors.value = {
    status: '',
    catatan: ''
  }

  let valid = true

  if (!form.value.status) {
    errors.value.status = 'Status wajib dipilih.'
    valid = false
  }

  if (form.value.catatan.length > 5000) {
    errors.value.catatan =
      'Catatan maksimal 5000 karakter.'
    valid = false
  }

  return valid
}

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

const handleSubmit = async () => {
  if (!validateForm()) {
    return
  }

  saving.value = true

  try {
    /*
     * Payload ini sengaja dibuat sama dengan validasi
     * InterventionController@update Laravel:
     *
     * {
     *   status: 'diproses',
     *   catatan: '...'
     * }
     */

    const payload = {
      status: form.value.status,
      catatan: form.value.catatan.trim() || null
    }

    console.log('Update intervention:', {
      id: intervention.value.id,
      ...payload
    })

    /*
     * API belum dipasang.
     * Nanti:
     *
     * await api.put(
     *   `/staff/interventions/${intervention.value.id}`,
     *   payload
     * )
     */

    await new Promise(resolve => setTimeout(resolve, 500))

    alert('Penanganan berhasil diperbarui.')

    router.push(
      `/staff/penanganan/${intervention.value.id}`
    )
  } catch (error) {
    console.error(
      'Gagal memperbarui penanganan:',
      error
    )

    alert('Terjadi kesalahan saat memperbarui penanganan.')
  } finally {
    saving.value = false
  }
}

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

/*
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
*/

const goBack = () => {
  router.push(
    `/staff/penanganan/${route.params.id}`
  )
}
</script>

<style scoped>
.page-container {
  width: 100%;
}

/* Back */

.back-button {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  margin-bottom: 18px;
  padding: 0;
  border: none;
  background: transparent;
  color: #64748b;
  font-size: 13px;
  cursor: pointer;
}

.back-button:hover {
  color: #2563eb;
}

/* Header */

.page-header {
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

/* State */

.state-card {
  min-height: 300px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 8px;
  background: #ffffff;
  border: 1px solid #e5eaf1;
  border-radius: 12px;
  color: #94a3b8;
}

.state-card strong {
  color: #475569;
  font-size: 14px;
}

.state-card span {
  font-size: 12px;
}

.loading-icon {
  color: #2563eb;
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Form */

.form-card {
  background: #ffffff;
  border: 1px solid #e5eaf1;
  border-radius: 12px;
  overflow: hidden;
}

/* Student */

.student-summary {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 20px;
  background: #f8fbff;
  border-bottom: 1px solid #e5eaf1;
}

.student-avatar {
  width: 50px;
  height: 50px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #eaf2ff;
  color: #2563eb;
  font-size: 15px;
  font-weight: 700;
}

.student-info {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.student-info strong {
  color: #172033;
  font-size: 15px;
}

.student-info span {
  color: #94a3b8;
  font-size: 11px;
}

/* Section */

.section {
  padding: 22px 24px;
  border-bottom: 1px solid #eef2f6;
}

.section-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 15px;
  margin-bottom: 20px;
}

.section-header svg {
  color: #2563eb;
  flex-shrink: 0;
}

.section-header h3 {
  margin: 0 0 4px;
  color: #172033;
  font-size: 15px;
}

.section-header p {
  margin: 0;
  color: #94a3b8;
  font-size: 11px;
}

/* Info Grid */

.info-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 6px;
  padding: 13px;
  background: #f8fafc;
  border: 1px solid #eef2f6;
  border-radius: 8px;
}

.info-item > span {
  color: #94a3b8;
  font-size: 11px;
}

.info-item > strong {
  color: #334155;
  font-size: 13px;
}

.point-value {
  color: #2563eb !important;
}

/* Stage */

.stage-badge {
  display: inline-flex;
  width: fit-content;
  align-items: center;
  padding: 5px 9px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 600;
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

/* Form Group */

.form-group {
  margin-bottom: 20px;
}

.form-group:last-child {
  margin-bottom: 0;
}

.form-group label {
  display: block;
  margin-bottom: 7px;
  color: #334155;
  font-size: 13px;
  font-weight: 600;
}

.form-group label span {
  color: #dc2626;
}

.form-group select,
.form-group textarea {
  width: 100%;
  box-sizing: border-box;
  border: 1px solid #dce3ec;
  border-radius: 8px;
  background: #ffffff;
  color: #334155;
  font-family: inherit;
  font-size: 13px;
  outline: none;
  transition: 0.2s ease;
}

.form-group select {
  height: 42px;
  padding: 0 12px;
  cursor: pointer;
}

.form-group textarea {
  display: block;
  min-height: 130px;
  padding: 12px;
  resize: vertical;
  line-height: 1.6;
}

.form-group select:focus,
.form-group textarea:focus {
  border-color: #93b4f4;
  box-shadow: 0 0 0 3px #eff6ff;
}

.form-group select.error,
.form-group textarea.error {
  border-color: #ef4444;
}

.form-group textarea::placeholder {
  color: #94a3b8;
}

/* Textarea */

.textarea-footer {
  display: flex;
  justify-content: space-between;
  gap: 10px;
  margin-top: 5px;
}

.character-count {
  margin-left: auto;
  color: #94a3b8;
  font-size: 10px;
}

.error-message {
  color: #dc2626;
  font-size: 11px;
}

/* Info */

.info-box {
  display: flex;
  gap: 10px;
  margin: 20px 24px;
  padding: 13px 14px;
  background: #eff6ff;
  border: 1px solid #dbeafe;
  border-radius: 8px;
  color: #2563eb;
}

.info-box svg {
  flex-shrink: 0;
  margin-top: 1px;
}

.info-box strong {
  display: block;
  margin-bottom: 3px;
  color: #1e40af;
  font-size: 12px;
}

.info-box p {
  margin: 0;
  color: #64748b;
  font-size: 11px;
  line-height: 1.5;
}

/* Actions */

.form-actions {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 10px;
  padding: 18px 24px;
  background: #fafbfc;
  border-top: 1px solid #eef2f6;
}

.primary-button,
.secondary-button {
  height: 40px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 0 15px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s ease;
}

.primary-button {
  border: 1px solid #2563eb;
  background: #2563eb;
  color: #ffffff;
}

.primary-button:hover:not(:disabled) {
  background: #1d4ed8;
}

.primary-button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.secondary-button {
  border: 1px solid #dce3ec;
  background: #ffffff;
  color: #475569;
}

.secondary-button:hover {
  background: #f8fafc;
}

.button-loading {
  animation: spin 1s linear infinite;
}

/* Responsive */

@media (max-width: 900px) {
  .info-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 640px) {
  .page-header h1 {
    font-size: 22px;
  }

  .student-summary {
    align-items: flex-start;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }

  .section {
    padding: 20px 16px;
  }

  .info-box {
    margin-left: 16px;
    margin-right: 16px;
  }

  .form-actions {
    flex-direction: column-reverse;
    padding: 16px;
  }

  .primary-button,
  .secondary-button {
    width: 100%;
  }
}
</style>