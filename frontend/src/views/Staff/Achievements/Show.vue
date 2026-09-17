<template>
  <AppLayout>
    <div class="page-header">
      <div>
        <button class="back-button" @click="goBack">
          <ArrowLeft :size="18" />
          Kembali
        </button>

        <h1>Detail Prestasi</h1>
        <p>Informasi lengkap prestasi siswa.</p>
      </div>

      <button
        v-if="achievement?.status === 'aktif'"
        class="edit-button"
        @click="goToEdit"
      >
        <Pencil :size="18" />
        Edit Prestasi
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="loading-card">
      <div class="spinner"></div>
      <p>Memuat data prestasi...</p>
    </div>

    <!-- Detail -->
    <template v-else-if="achievement">
      <div class="detail-grid">
        <!-- Informasi Siswa -->
        <div class="detail-card student-card">
          <div class="student-avatar">
            {{ getInitial(achievement.namaSiswa) }}
          </div>

          <h2>{{ achievement.namaSiswa }}</h2>
          <span class="nis">{{ achievement.nis }}</span>

          <div class="student-info">
            <div>
              <span>Tingkat</span>
              <strong>Kelas {{ achievement.tingkatSiswa }}</strong>
            </div>

            <div>
              <span>Jurusan</span>
              <strong>{{ achievement.jurusan }}</strong>
            </div>

            <div>
              <span>Kelas</span>
              <strong>{{ achievement.nomorKelas }}</strong>
            </div>
          </div>
        </div>

        <!-- Detail Prestasi -->
        <div class="detail-card">
          <div class="card-header">
            <div>
              <h2>Informasi Prestasi</h2>
              <p>Detail pencapaian siswa.</p>
            </div>

            <span class="status-badge" :class="achievement.status">
              {{ getStatusLabel(achievement.status) }}
            </span>
          </div>

          <div class="achievement-title">
            <div class="achievement-icon">
              <Trophy :size="28" />
            </div>

            <div>
              <span>Nama Prestasi</span>
              <h2>{{ achievement.namaPrestasi }}</h2>
            </div>
          </div>

          <div class="info-list">
            <div class="info-item">
              <span>Tingkat Prestasi</span>

              <strong>
                <span class="level-badge" :class="achievement.tingkat">
                  {{ getLevelLabel(achievement.tingkat) }}
                </span>
              </strong>
            </div>

            <div class="info-item">
              <span>Poin Tercatat</span>

              <strong>
                <span class="point-badge">
                  +{{ achievement.poinTercatat }} Poin
                </span>
              </strong>
            </div>

            <div class="info-item">
              <span>Tanggal Prestasi</span>

              <strong>
                {{ formatDate(achievement.tanggalPrestasi) }}
              </strong>
            </div>

            <div class="info-item">
              <span>Dicatat Oleh</span>

              <strong>{{ achievement.dicatatOleh }}</strong>
            </div>
          </div>

          <div class="description-section">
            <span>Keterangan</span>

            <p>
              {{ achievement.keterangan || "Tidak ada keterangan." }}
            </p>
          </div>
        </div>
      </div>

      <!-- Informasi Pembatalan -->
      <div
        v-if="achievement.status === 'dibatalkan'"
        class="detail-card cancellation-card"
      >
        <div class="cancellation-header">
          <div class="warning-icon">
            <CircleAlert :size="22" />
          </div>

          <div>
            <h2>Informasi Pembatalan</h2>
            <p>Prestasi ini telah dibatalkan.</p>
          </div>
        </div>

        <div class="info-list">
          <div class="info-item">
            <span>Dibatalkan Oleh</span>
            <strong>{{ achievement.cancelledBy }}</strong>
          </div>

          <div class="info-item">
            <span>Waktu Pembatalan</span>
            <strong>
              {{ formatDateTime(achievement.cancelledAt) }}
            </strong>
          </div>
        </div>

        <div class="description-section">
          <span>Alasan Pembatalan</span>

          <p>
            {{ achievement.cancelReason || "Tidak ada alasan." }}
          </p>
        </div>
      </div>
    </template>

    <!-- Not Found -->
    <div v-else class="not-found">
      <div class="not-found-icon">
        <Trophy :size="32" />
      </div>

      <h2>Data prestasi tidak ditemukan</h2>

      <p>Data prestasi yang Anda cari tidak tersedia.</p>

      <button class="back-button large" @click="goBack">
        <ArrowLeft :size="18" />
        Kembali ke Data Prestasi
      </button>
    </div>
  </AppLayout>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";

import AppLayout from "../../../layouts/AppLayout.vue";
import api from "../../../utils/api";

import { ArrowLeft, CircleAlert, Pencil, Trophy } from "lucide-vue-next";

const route = useRoute();
const router = useRouter();

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const loading = ref(true);
const achievement = ref(null);

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

const getErrorMessage = (error) => {
  return (
    error?.response?.data?.message ||
    "Terjadi kesalahan saat memuat data prestasi."
  );
};

const normalizeAchievement = (data) => {
  const student = data.student || {};
  const user = student.user || {};
  const category = data.category || {};
  const staff = data.staff || {};
  const staffUser = staff.user || {};
  const cancelledBy = data.cancelled_by || data.cancelledBy || null;

  return {
    id: Number(data.id),

    namaSiswa: user.name || user.nama || student.nama || student.name || "-",

    nis: student.nis || "-",

    tingkatSiswa: student.tingkat || "-",

    jurusan: student.jurusan || "-",

    nomorKelas: student.nomor_kelas ?? student.nomorKelas ?? "-",

    namaPrestasi:
      category.nama_prestasi ||
      category.namaPrestasi ||
      category.nama ||
      category.name ||
      "-",

    tingkat: category.tingkat || "-",

    poinTercatat: Number(data.poin_tercatat ?? 0),

    tanggalPrestasi: data.tanggal_prestasi || "",

    keterangan: data.keterangan || "",

    status: data.status || "aktif",

    dicatatOleh: staffUser.name || staffUser.nama || staff.nama || "-",

    cancelledBy: cancelledBy?.name || cancelledBy?.nama || "-",

    cancelledAt: data.cancelled_at || null,

    cancelReason: data.cancel_reason || "",
  };
};

/*
|--------------------------------------------------------------------------
| Load Data
|--------------------------------------------------------------------------
*/

const loadAchievement = async () => {
  loading.value = true;

  try {
    const id = Number(route.params.id);

    if (!id) {
      achievement.value = null;
      return;
    }

    const response = await api.get(`/staff/achievements/${id}`);

    const data = response.data?.data;

    if (!data) {
      achievement.value = null;
      return;
    }

    achievement.value = normalizeAchievement(data);
  } catch (error) {
    console.error("Gagal memuat detail prestasi:", error);

    achievement.value = null;

    window.alert(getErrorMessage(error));
  } finally {
    loading.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| Formatting
|--------------------------------------------------------------------------
*/

const getInitial = (name) => {
  if (!name) {
    return "?";
  }

  return name
    .split(" ")
    .filter(Boolean)
    .map((word) => word.charAt(0))
    .slice(0, 2)
    .join("")
    .toUpperCase();
};

const getLevelLabel = (level) => {
  const labels = {
    sekolah: "Sekolah",
    kecamatan: "Kecamatan",
    kabupaten: "Kabupaten",
    provinsi: "Provinsi",
    nasional: "Nasional",
    internasional: "Internasional",
  };

  return labels[level] || level || "-";
};

const getStatusLabel = (status) => {
  return status === "aktif" ? "Aktif" : "Dibatalkan";
};

const formatDate = (date) => {
  if (!date) {
    return "-";
  }

  const parsedDate = new Date(date);

  if (Number.isNaN(parsedDate.getTime())) {
    return "-";
  }

  return new Intl.DateTimeFormat("id-ID", {
    day: "2-digit",
    month: "long",
    year: "numeric",
  }).format(parsedDate);
};

const formatDateTime = (date) => {
  if (!date) {
    return "-";
  }

  const parsedDate = new Date(date);

  if (Number.isNaN(parsedDate.getTime())) {
    return "-";
  }

  return new Intl.DateTimeFormat("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  }).format(parsedDate);
};

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

const goBack = () => {
  router.push("/staff/prestasi");
};

const goToEdit = () => {
  if (!achievement.value) {
    return;
  }

  router.push(`/staff/prestasi/${achievement.value.id}/edit`);
};

/*
|--------------------------------------------------------------------------
| Lifecycle
|--------------------------------------------------------------------------
*/

onMounted(() => {
  loadAchievement();
});
</script>

<style scoped>
.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 24px;
}

.page-header h1 {
  margin: 12px 0 6px;
  font-size: 28px;
  font-weight: 700;
  color: #172033;
}

.page-header p {
  margin: 0;
  color: #64748b;
  font-size: 14px;
}

.back-button,
.edit-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  padding: 10px 16px;
  border: none;
  border-radius: 8px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
}

.back-button {
  background: #f1f5f9;
  color: #475569;
}

.back-button:hover {
  background: #e2e8f0;
}

.back-button.large {
  margin-top: 20px;
}

.edit-button {
  background: #2563eb;
  color: white;
}

.edit-button:hover {
  background: #1d4ed8;
}

.detail-grid {
  display: grid;
  grid-template-columns: 280px minmax(0, 1fr);
  gap: 20px;
  margin-bottom: 20px;
}

.detail-card {
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  overflow: hidden;
}

.student-card {
  height: fit-content;
  padding: 30px 24px;
  text-align: center;
}

.student-avatar {
  width: 80px;
  height: 80px;
  margin: 0 auto 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #dbeafe;
  color: #2563eb;
  font-size: 24px;
  font-weight: 700;
}

.student-card h2 {
  margin: 0 0 5px;
  color: #172033;
  font-size: 20px;
}

.nis {
  color: #64748b;
  font-size: 13px;
}

.student-info {
  display: grid;
  grid-template-columns: 1fr;
  margin-top: 24px;
  border-top: 1px solid #f1f5f9;
}

.student-info div {
  padding: 12px 0;
  border-bottom: 1px solid #f1f5f9;
}

.student-info div:last-child {
  border-bottom: none;
}

.student-info span,
.info-item span,
.description-section > span {
  display: block;
  margin-bottom: 5px;
  color: #64748b;
  font-size: 12px;
}

.student-info strong,
.info-item strong {
  color: #172033;
  font-size: 14px;
  font-weight: 600;
}

.card-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 20px;
  padding: 22px 24px;
  border-bottom: 1px solid #e5e7eb;
}

.card-header h2 {
  margin: 0 0 4px;
  color: #172033;
  font-size: 18px;
}

.card-header p {
  margin: 0;
  color: #64748b;
  font-size: 13px;
}

.status-badge,
.level-badge,
.point-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 5px 10px;
  border-radius: 999px;
  font-size: 11px;
  font-weight: 600;
  white-space: nowrap;
}

.status-badge.aktif {
  background: #dcfce7;
  color: #15803d;
}

.status-badge.dibatalkan {
  background: #fee2e2;
  color: #b91c1c;
}

.achievement-title {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 24px;
  background: #f8fafc;
  border-bottom: 1px solid #e5e7eb;
}

.achievement-icon {
  width: 54px;
  height: 54px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  background: #dbeafe;
  color: #2563eb;
}

.achievement-title span {
  color: #64748b;
  font-size: 12px;
}

.achievement-title h2 {
  margin: 4px 0 0;
  color: #172033;
  font-size: 18px;
}

.info-list {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
}

.info-item {
  padding: 18px 24px;
  border-bottom: 1px solid #f1f5f9;
}

.info-item:nth-child(odd) {
  border-right: 1px solid #f1f5f9;
}

.level-badge.sekolah {
  background: #f1f5f9;
  color: #475569;
}

.level-badge.kecamatan {
  background: #e0f2fe;
  color: #0369a1;
}

.level-badge.kabupaten {
  background: #dbeafe;
  color: #1d4ed8;
}

.level-badge.provinsi {
  background: #ede9fe;
  color: #6d28d9;
}

.level-badge.nasional {
  background: #fef3c7;
  color: #b45309;
}

.level-badge.internasional {
  background: #fce7f3;
  color: #be185d;
}

.point-badge {
  background: #dcfce7;
  color: #15803d;
}

.description-section {
  padding: 20px 24px;
}

.description-section p {
  margin: 0;
  color: #475569;
  font-size: 14px;
  line-height: 1.7;
}

.cancellation-card {
  margin-bottom: 20px;
}

.cancellation-header {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 20px 24px;
  border-bottom: 1px solid #e5e7eb;
}

.warning-icon {
  width: 42px;
  height: 42px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  border-radius: 10px;
  background: #fee2e2;
  color: #dc2626;
}

.cancellation-header h2 {
  margin: 0 0 4px;
  color: #172033;
  font-size: 17px;
}

.cancellation-header p {
  margin: 0;
  color: #64748b;
  font-size: 13px;
}

.loading-card {
  min-height: 300px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
}

.loading-card p {
  margin: 14px 0 0;
  color: #64748b;
  font-size: 14px;
}

.spinner {
  width: 30px;
  height: 30px;
  border: 3px solid #dbeafe;
  border-top-color: #2563eb;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

.not-found {
  padding: 60px 24px;
  background: white;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  text-align: center;
}

.not-found-icon {
  width: 64px;
  height: 64px;
  margin: 0 auto 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #f1f5f9;
  color: #64748b;
}

.not-found h2 {
  margin: 0 0 8px;
  color: #172033;
  font-size: 20px;
}

.not-found p {
  margin: 0;
  color: #64748b;
  font-size: 14px;
}

@media (max-width: 900px) {
  .detail-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 640px) {
  .page-header {
    align-items: stretch;
    flex-direction: column;
  }

  .edit-button {
    width: 100%;
  }

  .card-header {
    flex-direction: column;
  }

  .info-list {
    grid-template-columns: 1fr;
  }

  .info-item:nth-child(odd) {
    border-right: none;
  }

  .achievement-title {
    align-items: flex-start;
  }
}
</style>
