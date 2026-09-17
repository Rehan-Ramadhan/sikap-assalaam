<template>
  <AppLayout>
    <div class="student-show">
      <!-- Loading -->
      <div v-if="loading" class="state-card">
        <LoaderCircle :size="22" class="loading-icon" />
        <span>Memuat data siswa...</span>
      </div>

      <!-- Error -->
      <div v-else-if="errorMessage" class="state-card error-state">
        <CircleAlert :size="22" />
        <div>
          <strong>Gagal memuat data siswa</strong>
          <p>{{ errorMessage }}</p>
        </div>
      </div>

      <!-- Content -->
      <template v-else>
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
                <span class="section-label"> PROFIL SISWA </span>

                <h1>{{ student.nama }}</h1>

                <p>NIS {{ student.nis }}</p>
              </div>
            </div>
          </div>

          <button v-if="student.id" class="btn btn-primary" @click="goToEdit">
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

          <span class="status-badge" :class="getStatusClass(student.status)">
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
            <p>Email digunakan siswa untuk masuk ke sistem SIKAP Assalaam.</p>
          </div>
        </section>
      </template>
    </div>
  </AppLayout>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";

import {
  ArrowLeft,
  Pencil,
  UserRound,
  GraduationCap,
  Mail,
  LoaderCircle,
  CircleAlert,
} from "lucide-vue-next";

import AppLayout from "../../../layouts/AppLayout.vue";
import api from "../../../utils/api";

const route = useRoute();
const router = useRouter();

const loading = ref(true);
const errorMessage = ref("");

const student = ref({
  id: null,
  nama: "-",
  nis: "-",
  tingkat: "-",
  jurusan: "-",
  nomorKelas: "-",
  jenisKelamin: "-",
  tahunMasuk: "-",
  status: "-",
  email: "-",
});

const normalizeGender = (gender) => {
  if (!gender) return "-";

  const value = String(gender).toLowerCase();

  if (value === "l" || value === "laki-laki" || value === "male") {
    return "Laki-laki";
  }

  if (value === "p" || value === "perempuan" || value === "female") {
    return "Perempuan";
  }

  return gender;
};

const normalizeStatus = (status) => {
  if (!status) return "-";

  const value = String(status).toLowerCase();

  if (value === "aktif" || value === "active") {
    return "Aktif";
  }

  if (value === "nonaktif" || value === "inactive") {
    return "Nonaktif";
  }

  if (value === "lulus" || value === "graduated") {
    return "Lulus";
  }

  return status;
};

const normalizeStudent = (data) => {
  return {
    id: data?.id ?? null,
    nama: data?.user?.name ?? data?.name ?? data?.nama ?? "-",
    nis: data?.nis ?? "-",
    tingkat: String(data?.tingkat ?? "-"),
    jurusan: data?.jurusan ?? "-",
    nomorKelas: data?.nomor_kelas ?? data?.nomorKelas ?? "-",
    jenisKelamin: normalizeGender(
      data?.jenis_kelamin ?? data?.jenisKelamin ?? data?.user?.jenis_kelamin,
    ),
    tahunMasuk: data?.tahun_masuk ?? data?.tahunMasuk ?? "-",
    status: normalizeStatus(data?.status),
    email: data?.user?.email ?? data?.email ?? "-",
  };
};

const fetchStudent = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await api.get(`/staff/students/${route.params.id}`);

    const data = response.data?.data ?? response.data?.student ?? response.data;

    student.value = normalizeStudent(data);
  } catch (error) {
    console.error("Gagal mengambil detail siswa:", error);

    errorMessage.value =
      error.response?.data?.message ||
      "Data siswa tidak ditemukan atau gagal diambil dari server Laravel.";
  } finally {
    loading.value = false;
  }
};

onMounted(fetchStudent);

const goBack = () => {
  router.push("/staff/siswa");
};

const goToEdit = () => {
  if (!student.value.id) return;

  router.push(`/staff/siswa/${student.value.id}/edit`);
};

const getInitial = (name) => {
  if (!name || name === "-") {
    return "?";
  }

  return name
    .split(" ")
    .filter(Boolean)
    .slice(0, 2)
    .map((word) => word.charAt(0))
    .join("")
    .toUpperCase();
};

const getStatusClass = (status) => {
  if (status === "Aktif") {
    return "status-active";
  }

  if (status === "Nonaktif") {
    return "status-inactive";
  }

  if (status === "Lulus") {
    return "status-graduated";
  }

  return "";
};
</script>

<style scoped>
.student-show {
  width: 100%;
}

.state-card {
  min-height: 180px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  color: #64748b;
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  font-size: 14px;
}

.error-state {
  justify-content: flex-start;
  padding: 24px;
  color: #dc2626;
}

.error-state div {
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.error-state strong {
  color: #334155;
  font-size: 14px;
}

.error-state p {
  margin: 0;
  color: #64748b;
  font-size: 13px;
}

.loading-icon {
  animation: spin 1s linear infinite;
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

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
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

  .error-state {
    align-items: flex-start;
  }
}
</style>
