<template>
  <AppLayout>
    <div class="detail-page">

      <div class="page-header">
        <div>
          <span class="eyebrow">DETAIL PELANGGARAN</span>
          <h1>Detail Pelanggaran</h1>
          <p>Informasi lengkap catatan pelanggaran siswa.</p>
        </div>

        <div class="header-actions">
          <button class="back-btn" @click="goBack">
            <ArrowLeft :size="17" />
            Kembali
          </button>

          <button class="edit-btn" @click="goToEdit">
            <Pencil :size="17" />
            Edit
          </button>
        </div>
      </div>

      <!-- SUMMARY -->
      <div class="summary-card">

        <div class="student-avatar">
          {{ violation.studentName.charAt(0) }}
        </div>

        <div class="student-info">
          <span class="label">SISWA</span>
          <h2>{{ violation.studentName }}</h2>
          <p>NIS {{ violation.nis }}</p>
        </div>

        <div class="summary-point">
          <span>Total Poin</span>
          <strong>{{ violation.points }}</strong>
          <small>poin</small>
        </div>

        <span
          class="status-badge"
          :class="violation.status"
        >
          {{ violation.status === 'aktif' ? 'Aktif' : 'Dibatalkan' }}
        </span>

      </div>

      <div class="detail-grid">

        <!-- DETAIL -->
        <div class="detail-card">

          <div class="card-title">
            <AlertTriangle :size="18" />
            <h2>Informasi Pelanggaran</h2>
          </div>

          <div class="detail-list">

            <div class="detail-item">
              <span>Jenis Pelanggaran</span>
              <strong>{{ violation.violationName }}</strong>
            </div>

            <div class="detail-item">
              <span>Kategori</span>

              <span
                class="category-badge"
                :class="`category-${violation.category}`"
              >
                {{ capitalize(violation.category) }}
              </span>
            </div>

            <div class="detail-item">
              <span>Poin Tercatat</span>
              <strong class="point">{{ violation.points }} poin</strong>
            </div>

            <div class="detail-item">
              <span>Tanggal Kejadian</span>
              <strong>{{ formatDate(violation.date) }}</strong>
            </div>

            <div class="detail-item full">
              <span>Keterangan</span>
              <p>{{ violation.description }}</p>
            </div>

          </div>

        </div>

        <!-- HANDLING -->
        <div class="detail-card">

          <div class="card-title">
            <ClipboardCheck :size="18" />
            <h2>Status Penanganan</h2>
          </div>

          <div class="handling-box">

            <div class="handling-icon">
              <Clock3 :size="20" />
            </div>

            <div>
              <span>Status</span>
              <strong>
                {{ formatHandling(violation.handlingStatus) }}
              </strong>
            </div>

          </div>

          <div class="detail-list compact">

            <div class="detail-item">
              <span>Dicatat Oleh</span>
              <strong>{{ violation.recordedBy }}</strong>
            </div>

            <div class="detail-item">
              <span>Dibuat Pada</span>
              <strong>{{ violation.createdAt }}</strong>
            </div>

          </div>

        </div>

      </div>

      <!-- CANCELLED -->
      <div
        v-if="violation.status === 'dibatalkan'"
        class="cancelled-card"
      >
        <div class="cancelled-icon">
          <Ban :size="20" />
        </div>

        <div>
          <strong>Pelanggaran telah dibatalkan</strong>
          <p>
            Alasan pembatalan:
            {{ violation.cancelReason }}
          </p>
        </div>
      </div>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'

import AppLayout from '../../../layouts/AppLayout.vue'

import {
  ArrowLeft,
  Pencil,
  AlertTriangle,
  ClipboardCheck,
  Clock3,
  Ban
} from 'lucide-vue-next'

const router = useRouter()
const route = useRoute()

const violation = ref({
  id: route.params.id,
  studentName: 'Muhammad Rizky',
  nis: '2026002',
  violationName: 'Tidak Menggunakan Atribut Lengkap',
  category: 'sedang',
  points: 10,
  date: '2026-09-14',
  description: 'Siswa tidak menggunakan atribut sekolah lengkap saat mengikuti kegiatan belajar.',
  status: 'aktif',
  handlingStatus: 'wali_kelas',
  recordedBy: 'Ahmad Fauzan',
  createdAt: '14 September 2026, 07:42',
  cancelReason: ''
})

const capitalize = (value) => {
  return value.charAt(0).toUpperCase() + value.slice(1)
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('id-ID', {
    day: '2-digit',
    month: 'long',
    year: 'numeric'
  })
}

const formatHandling = (status) => {
  const labels = {
    belum_ditangani: 'Belum Ditangani',
    wali_kelas: 'Wali Kelas',
    bk: 'BK',
    kesiswaan: 'Kesiswaan',
    kepala_sekolah: 'Kepala Sekolah'
  }

  return labels[status] || status
}

const goBack = () => {
  router.push('/staff/pelanggaran')
}

const goToEdit = () => {
  router.push(`/staff/pelanggaran/${violation.value.id}/edit`)
}
</script>

<style scoped>
.detail-page {
  padding: 28px;
}

.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 22px;
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

.header-actions {
  display: flex;
  gap: 8px;
}

.back-btn,
.edit-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  padding: 10px 14px;
  border-radius: 9px;
  font-family: inherit;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}

.back-btn {
  border: 1px solid #dfe5ed;
  background: white;
  color: #475569;
}

.edit-btn {
  border: none;
  background: #2563eb;
  color: white;
}

.summary-card {
  display: flex;
  align-items: center;
  gap: 15px;
  padding: 20px;
  border: 1px solid #e8edf5;
  border-radius: 14px;
  background: white;
  margin-bottom: 18px;
}

.student-avatar {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 52px;
  height: 52px;
  border-radius: 13px;
  background: #eff6ff;
  color: #2563eb;
  font-size: 19px;
  font-weight: 600;
}

.student-info {
  flex: 1;
}

.student-info .label {
  color: #94a3b8;
  font-size: 10px;
  font-weight: 700;
  letter-spacing: .07em;
}

.student-info h2 {
  margin: 3px 0;
  color: #172033;
  font-size: 18px;
  font-weight: 600;
}

.student-info p {
  margin: 0;
  color: #94a3b8;
  font-size: 12px;
}

.summary-point {
  text-align: right;
  margin-right: 20px;
}

.summary-point span,
.summary-point small {
  color: #94a3b8;
  font-size: 11px;
}

.summary-point strong {
  margin: 0 4px;
  color: #dc2626;
  font-size: 23px;
  font-weight: 600;
}

.status-badge,
.category-badge {
  display: inline-flex;
  border-radius: 20px;
  padding: 6px 10px;
  font-size: 11px;
  font-weight: 500;
}

.status-badge.aktif {
  color: #15803d;
  background: #f0fdf4;
}

.status-badge.dibatalkan {
  color: #64748b;
  background: #f1f5f9;
}

.category-ringan {
  color: #2563eb;
  background: #eff6ff;
}

.category-sedang {
  color: #b45309;
  background: #fffbeb;
}

.category-berat {
  color: #dc2626;
  background: #fef2f2;
}

.detail-grid {
  display: grid;
  grid-template-columns: 1.3fr 1fr;
  gap: 18px;
}

.detail-card {
  padding: 22px;
  border: 1px solid #e8edf5;
  border-radius: 14px;
  background: white;
}

.card-title {
  display: flex;
  align-items: center;
  gap: 9px;
  padding-bottom: 16px;
  border-bottom: 1px solid #edf1f6;
  color: #2563eb;
}

.card-title h2 {
  margin: 0;
  color: #172033;
  font-size: 15px;
  font-weight: 600;
}

.detail-list {
  margin-top: 4px;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  gap: 20px;
  padding: 14px 0;
  border-bottom: 1px solid #f1f3f6;
}

.detail-item.full {
  display: block;
}

.detail-item > span:first-child {
  color: #94a3b8;
  font-size: 12px;
}

.detail-item strong {
  color: #334155;
  font-size: 13px;
  font-weight: 500;
  text-align: right;
}

.detail-item.full p {
  margin: 8px 0 0;
  color: #475569;
  font-size: 13px;
  line-height: 1.7;
}

.detail-item .point {
  color: #dc2626;
}

.handling-box {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 18px 0;
  padding: 14px;
  border-radius: 10px;
  background: #f8fafc;
}

.handling-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: #eff6ff;
  color: #2563eb;
}

.handling-box span {
  display: block;
  margin-bottom: 3px;
  color: #94a3b8;
  font-size: 11px;
}

.handling-box strong {
  color: #334155;
  font-size: 13px;
  font-weight: 500;
}

.detail-list.compact .detail-item {
  padding: 11px 0;
}

.cancelled-card {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  margin-top: 18px;
  padding: 16px;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  background: #f8fafc;
}

.cancelled-icon {
  color: #64748b;
}

.cancelled-card strong {
  color: #334155;
  font-size: 13px;
}

.cancelled-card p {
  margin: 5px 0 0;
  color: #64748b;
  font-size: 12px;
}

@media (max-width: 800px) {
  .detail-grid {
    grid-template-columns: 1fr;
  }

  .summary-card {
    align-items: flex-start;
    flex-wrap: wrap;
  }

  .summary-point {
    margin-left: 67px;
    margin-right: 0;
    text-align: left;
  }
}

@media (max-width: 600px) {
  .detail-page {
    padding: 18px;
  }

  .page-header {
    align-items: flex-start;
    flex-direction: column;
  }
}
</style>