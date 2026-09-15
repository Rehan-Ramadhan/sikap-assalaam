<template>
  <AppLayout>
    <div class="student-show">

      <!-- Header -->
      <div class="page-header">
        <div>
          <button class="back-btn" @click="goBack">
            <ArrowLeft :size="18" />
            Kembali
          </button>

          <div class="title-row">
            <div class="student-avatar">
              {{ getInitial(student.nama) }}
            </div>

            <div>
              <span class="section-label">
                PROFIL SISWA
              </span>

              <h1>{{ student.nama }}</h1>

              <p>
                NIS {{ student.nis }}
              </p>
            </div>
          </div>
        </div>

        <button
          v-if="student.id"
          class="btn btn-primary"
          @click="goToEdit"
        >
          <Pencil :size="17" />
          Edit Data
        </button>
      </div>

      <!-- Status -->
      <div class="status-card">
        <div class="status-left">
          <div class="status-icon">
            <UserRound :size="19" />
          </div>

          <div>
            <span>Status Siswa</span>
            <strong>{{ student.status }}</strong>
          </div>
        </div>

        <span
          class="status-badge"
          :class="getStatusClass(student.status)"
        >
          {{ student.status }}
        </span>
      </div>

      <!-- Content -->
      <div class="content-grid">

        <!-- Akademik -->
        <section class="info-card">

          <div class="card-heading">
            <div class="heading-icon">
              <GraduationCap :size="19" />
            </div>

            <div>
              <h2>Informasi Akademik</h2>
              <p>Informasi akademik siswa</p>
            </div>
          </div>

          <div class="info-grid">

            <div class="info-item">
              <span>NIS</span>
              <strong>{{ student.nis }}</strong>
            </div>

            <div class="info-item">
              <span>Tingkat</span>
              <strong>Kelas {{ student.tingkat }}</strong>
            </div>

            <div class="info-item">
              <span>Jurusan</span>
              <strong>{{ student.jurusan }}</strong>
            </div>

            <div class="info-item">
              <span>Nomor Kelas</span>
              <strong>{{ student.nomorKelas }}</strong>
            </div>

            <div class="info-item">
              <span>Tahun Masuk</span>
              <strong>{{ student.tahunMasuk }}</strong>
            </div>

          </div>

        </section>

        <!-- Pribadi -->
        <section class="info-card">

          <div class="card-heading">
            <div class="heading-icon">
              <UserRound :size="19" />
            </div>

            <div>
              <h2>Informasi Pribadi</h2>
              <p>Informasi pribadi siswa</p>
            </div>
          </div>

          <div class="info-grid">

            <div class="info-item info-full">
              <span>Nama Lengkap</span>
              <strong>{{ student.nama }}</strong>
            </div>

            <div class="info-item">
              <span>Jenis Kelamin</span>
              <strong>{{ student.jenisKelamin }}</strong>
            </div>

            <div class="info-item">
              <span>Status</span>
              <strong>{{ student.status }}</strong>
            </div>

            <div class="info-item info-full">
              <span>Email</span>
              <strong>{{ student.email }}</strong>
            </div>

          </div>

        </section>

      </div>

      <!-- Quick Info -->
      <section class="account-card">

        <div class="account-icon">
          <Mail :size="19" />
        </div>

        <div class="account-content">
          <span>Akun Siswa</span>
          <strong>{{ student.email }}</strong>
          <p>
            Email digunakan siswa untuk masuk ke sistem SIKAP Assalaam.
          </p>
        </div>

      </section>

    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import {
  ArrowLeft,
  Pencil,
  UserRound,
  GraduationCap,
  Mail
} from 'lucide-vue-next'

import AppLayout from '../../../layouts/AppLayout.vue'

const route = useRoute()
const router = useRouter()

const dummyStudents = {
  1: {
    id: 1,
    nama: 'Ahmad Fauzan',
    nis: '2026001',
    tingkat: '10',
    jurusan: 'RPL',
    nomorKelas: 1,
    jenisKelamin: 'Laki-laki',
    tahunMasuk: 2026,
    status: 'Aktif',
    email: 'ahmad.fauzan@email.com'
  },

  2: {
    id: 2,
    nama: 'Muhammad Rizky',
    nis: '2026002',
    tingkat: '10',
    jurusan: 'TKR',
    nomorKelas: 2,
    jenisKelamin: 'Laki-laki',
    tahunMasuk: 2026,
    status: 'Aktif',
    email: 'muhammad.rizky@email.com'
  },

  3: {
    id: 3,
    nama: 'Siti Aisyah',
    nis: '2025001',
    tingkat: '11',
    jurusan: 'RPL',
    nomorKelas: 1,
    jenisKelamin: 'Perempuan',
    tahunMasuk: 2025,
    status: 'Aktif',
    email: 'siti.aisyah@email.com'
  }
}

const student = ref(
  dummyStudents[route.params.id] || {
    id: null,
    nama: 'Siswa Tidak Ditemukan',
    nis: '-',
    tingkat: '-',
    jurusan: '-',
    nomorKelas: '-',
    jenisKelamin: '-',
    tahunMasuk: '-',
    status: '-',
    email: '-'
  }
)

const goBack = () => {
  router.push('/staff/siswa')
}

const goToEdit = () => {
  router.push(`/staff/siswa/${student.value.id}/edit`)
}

const getInitial = (name) => {
  if (!name || name === '-') {
    return '?'
  }

  return name
    .split(' ')
    .slice(0, 2)
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
}

const getStatusClass = (status) => {
  if (status === 'Aktif') {
    return 'status-active'
  }

  if (status === 'Nonaktif') {
    return 'status-inactive'
  }

  if (status === 'Lulus') {
    return 'status-graduated'
  }

  return ''
}
</script>

<style scoped>
.student-show {
  width: 100%;
}

.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 24px;
  margin-bottom: 24px;
}

.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  border: none;
  background: transparent;
  color: #64748b;
  padding: 0;
  margin-bottom: 18px;
  cursor: pointer;
  font-size: 14px;
  font-weight: 600;
}

.back-btn:hover {
  color: #2563eb;
}

.title-row {
  display: flex;
  align-items: center;
  gap: 15px;
}

.student-avatar {
  width: 58px;
  height: 58px;
  flex-shrink: 0;
  border-radius: 15px;
  background: #eff6ff;
  color: #2563eb;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 17px;
  font-weight: 600;
}

.section-label {
  display: block;
  margin-bottom: 5px;
  color: #2563eb;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.08em;
}

.title-row h1 {
  margin: 0;
  color: #172033;
  font-size: 27px;
  font-weight: 600;
  letter-spacing: -0.02em;
}

.title-row p {
  margin: 5px 0 0;
  color: #64748b;
  font-size: 13px;
}

.btn {
  min-height: 42px;
  padding: 0 16px;
  border-radius: 9px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border: none;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s ease;
}

.btn-primary {
  background: #2563eb;
  color: #ffffff;
}

.btn-primary:hover {
  background: #1d4ed8;
}

.status-card {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 17px 20px;
  margin-bottom: 20px;
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
}

.status-left {
  display: flex;
  align-items: center;
  gap: 12px;
}

.status-icon {
  width: 40px;
  height: 40px;
  border-radius: 10px;
  background: #eff6ff;
  color: #2563eb;
  display: flex;
  align-items: center;
  justify-content: center;
}

.status-left div:last-child {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.status-left span {
  color: #94a3b8;
  font-size: 12px;
}

.status-left strong {
  color: #334155;
  font-size: 14px;
  font-weight: 600;
}

.status-badge {
  padding: 6px 11px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
}

.status-active {
  background: #ecfdf5;
  color: #15803d;
}

.status-inactive {
  background: #fef2f2;
  color: #dc2626;
}

.status-graduated {
  background: #f1f5f9;
  color: #475569;
}

.content-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 20px;
}

.info-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  padding: 22px;
}

.card-heading {
  display: flex;
  align-items: center;
  gap: 11px;
  padding-bottom: 18px;
  margin-bottom: 20px;
  border-bottom: 1px solid #f1f5f9;
}

.heading-icon {
  width: 38px;
  height: 38px;
  flex-shrink: 0;
  border-radius: 10px;
  background: #eff6ff;
  color: #2563eb;
  display: flex;
  align-items: center;
  justify-content: center;
}

.card-heading h2 {
  margin: 0 0 3px;
  color: #334155;
  font-size: 16px;
  font-weight: 600;
}

.card-heading p {
  margin: 0;
  color: #94a3b8;
  font-size: 12px;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 20px 18px;
}

.info-item {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.info-item span {
  color: #94a3b8;
  font-size: 12px;
}

.info-item strong {
  color: #334155;
  font-size: 14px;
  font-weight: 500;
  word-break: break-word;
}

.info-full {
  grid-column: 1 / -1;
}

.account-card {
  display: flex;
  align-items: center;
  gap: 13px;
  margin-top: 20px;
  padding: 18px 20px;
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
}

.account-icon {
  width: 40px;
  height: 40px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  background: #eff6ff;
  color: #2563eb;
}

.account-content {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.account-content > span {
  color: #94a3b8;
  font-size: 12px;
}

.account-content strong {
  color: #334155;
  font-size: 14px;
  font-weight: 500;
}

.account-content p {
  margin: 2px 0 0;
  color: #94a3b8;
  font-size: 12px;
}

@media (max-width: 800px) {
  .page-header {
    align-items: flex-start;
    flex-direction: column;
  }

  .content-grid {
    grid-template-columns: 1fr;
  }

  .btn-primary {
    width: 100%;
  }
}

@media (max-width: 550px) {
  .title-row h1 {
    font-size: 23px;
  }

  .title-row {
    align-items: flex-start;
  }

  .status-card {
    align-items: flex-start;
    gap: 15px;
    flex-direction: column;
  }

  .info-grid {
    grid-template-columns: 1fr;
  }

  .info-full {
    grid-column: auto;
  }

  .account-card {
    align-items: flex-start;
  }
}
</style>