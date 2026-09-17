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
          <h1>Detail Penanganan</h1>
          <p>Informasi lengkap mengenai proses penanganan siswa.</p>
        </div>

        <button
          v-if="intervention && intervention.status !== 'selesai'"
          class="primary-button"
          @click="goToEdit"
        >
          <Pencil :size="17" />
          Edit Penanganan
        </button>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="state-card">
        <LoaderCircle :size="30" class="loading-icon" />
        <strong>Memuat data...</strong>
        <span>Silakan tunggu sebentar.</span>
      </div>

      <!-- Error -->
      <div v-else-if="error" class="state-card">
        <ClipboardList :size="40" />
        <strong>Gagal memuat data</strong>
        <span>{{ error }}</span>

        <button class="secondary-button" @click="fetchIntervention">
          Coba Lagi
        </button>
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

      <!-- Detail -->
      <template v-else>
        <!-- Student Card -->
        <div class="student-card">
          <div class="student-avatar">
            {{ getInitial(intervention.siswa.nama) }}
          </div>

          <div class="student-main">
            <div class="student-heading">
              <div>
                <h2>{{ intervention.siswa.nama }}</h2>
                <span>NIS {{ intervention.siswa.nis }}</span>
              </div>

              <span
                class="status-badge"
                :class="getStatusClass(intervention.status)"
              >
                <span class="status-dot"></span>
                {{ getStatusLabel(intervention.status) }}
              </span>
            </div>

            <div class="student-meta">
              <span>
                <GraduationCap :size="15" />
                {{ intervention.siswa.tingkat }}
              </span>

              <span>
                <BookOpen :size="15" />
                {{ intervention.siswa.jurusan }}
              </span>

              <span>
                <School :size="15" />
                Kelas {{ intervention.siswa.kelas }}
              </span>
            </div>
          </div>
        </div>

        <!-- Main Grid -->
        <div class="detail-grid">
          <!-- Left -->
          <div class="detail-card">
            <div class="card-header">
              <div>
                <h3>Informasi Penanganan</h3>
                <p>Data utama proses penanganan siswa.</p>
              </div>

              <ClipboardList :size="21" />
            </div>

            <div class="detail-list">
              <div class="detail-item">
                <span>Tahap Penanganan</span>

                <strong>
                  <span
                    class="stage-badge"
                    :class="getStageClass(intervention.tahap)"
                  >
                    {{ getStageLabel(intervention.tahap) }}
                  </span>
                </strong>
              </div>

              <div class="detail-item">
                <span>Poin Saat Penanganan</span>
                <strong class="point-value">
                  {{ intervention.poin }} poin
                </strong>
              </div>

              <div class="detail-item">
                <span>Threshold</span>
                <strong>
                  {{ intervention.threshold }}
                </strong>
              </div>

              <div class="detail-item">
                <span>Tanggal Mulai</span>
                <strong>
                  {{ intervention.tanggalMulai || "-" }}
                </strong>
              </div>

              <div class="detail-item">
                <span>Tanggal Selesai</span>
                <strong>
                  {{ intervention.tanggalSelesai || "-" }}
                </strong>
              </div>

              <div class="detail-item">
                <span>Petugas</span>

                <div class="staff-detail">
                  <strong>{{ intervention.petugas }}</strong>
                  <span>{{ intervention.jabatan }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Right -->
          <div class="detail-card">
            <div class="card-header">
              <div>
                <h3>Pelanggaran</h3>
                <p>Pelanggaran yang memicu penanganan.</p>
              </div>

              <AlertTriangle :size="21" />
            </div>

            <div class="violation-card">
              <div class="violation-icon">
                <AlertTriangle :size="20" />
              </div>

              <div class="violation-content">
                <strong>{{ intervention.pelanggaran }}</strong>

                <div class="violation-meta">
                  <span> {{ intervention.poin }} poin </span>

                  <span>
                    {{ intervention.tanggalPelanggaran || "-" }}
                  </span>
                </div>
              </div>
            </div>

            <div class="note-box">
              <span>Catatan Penanganan</span>

              <p>
                {{ intervention.catatan || "Belum ada catatan." }}
              </p>
            </div>
          </div>
        </div>

        <!-- Logs -->
        <div class="detail-card logs-card">
          <div class="card-header">
            <div>
              <h3>Riwayat Penanganan</h3>
              <p>Riwayat perubahan status dan catatan penanganan.</p>
            </div>

            <History :size="21" />
          </div>

          <div v-if="intervention.logs.length > 0" class="timeline">
            <div
              v-for="(log, index) in intervention.logs"
              :key="log.id"
              class="timeline-item"
            >
              <div class="timeline-line">
                <div
                  class="timeline-dot"
                  :class="getStatusClass(log.status)"
                ></div>

                <div
                  v-if="index < intervention.logs.length - 1"
                  class="timeline-connector"
                ></div>
              </div>

              <div class="timeline-content">
                <div class="timeline-top">
                  <div>
                    <strong>
                      {{ getStatusLabel(log.status) }}
                    </strong>

                    <span>
                      {{ log.staff }}
                    </span>
                  </div>

                  <time>{{ log.createdAt }}</time>
                </div>

                <p v-if="log.catatan">
                  {{ log.catatan }}
                </p>

                <span v-else class="no-note"> Tidak ada catatan. </span>
              </div>
            </div>
          </div>

          <div v-else class="empty-logs">
            <History :size="34" />
            <strong>Belum ada riwayat</strong>
            <span>Belum terdapat log penanganan.</span>
          </div>
        </div>
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
  LoaderCircle,
  ClipboardList,
  GraduationCap,
  BookOpen,
  School,
  AlertTriangle,
  History,
} from "lucide-vue-next";

import AppLayout from "../../../layouts/AppLayout.vue";
import api from "../../../utils/api";

const route = useRoute();
const router = useRouter();

const loading = ref(true);
const error = ref("");
const intervention = ref(null);

/*
|--------------------------------------------------------------------------
| Fetch
|--------------------------------------------------------------------------
*/

const fetchIntervention = async () => {
  loading.value = true;
  error.value = "";
  intervention.value = null;

  try {
    const response = await api.get(`/staff/interventions/${route.params.id}`);

    const data = response?.data?.data;

    if (!data) {
      intervention.value = null;
      return;
    }

    intervention.value = normalizeIntervention(data);
  } catch (err) {
    console.error("Gagal mengambil detail penanganan:", err);

    if (err?.response?.status === 404) {
      intervention.value = null;
    } else {
      error.value =
        err?.response?.data?.message ||
        "Terjadi kesalahan saat mengambil detail penanganan.";
    }
  } finally {
    loading.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| Normalize
|--------------------------------------------------------------------------
*/

const normalizeIntervention = (item) => {
  const student = item?.student;
  const user = student?.user;

  const staff = item?.staff;
  const staffUser = staff?.user;

  const threshold = item?.threshold;

  const logs = Array.isArray(item?.logs) ? item.logs : [];

  return {
    id: item?.id,

    siswa: {
      nama: user?.name || user?.nama || student?.name || student?.nama || "-",

      nis: student?.nis || "-",

      tingkat: student?.tingkat ? `Kelas ${student.tingkat}` : "-",

      jurusan: student?.jurusan || "-",

      kelas: student?.nomor_kelas || student?.kelas || "-",
    },

    pelanggaran:
      item?.pelanggaran ||
      item?.violation?.category?.nama_pelanggaran ||
      item?.studentViolation?.category?.nama_pelanggaran ||
      item?.student_violation?.category?.nama_pelanggaran ||
      "-",

    poin:
      item?.poin ??
      item?.poin_tercatat ??
      item?.studentViolation?.poin_tercatat ??
      item?.student_violation?.poin_tercatat ??
      0,

    tanggalPelanggaran: formatDate(
      item?.tanggal_pelanggaran ||
        item?.studentViolation?.tanggal_kejadian ||
        item?.student_violation?.tanggal_kejadian,
    ),

    tahap: item?.tahap || "-",

    status: item?.status || "-",

    petugas:
      staffUser?.name || staffUser?.nama || staff?.name || staff?.nama || "-",

    jabatan: staff?.jabatan || staff?.position || "-",

    threshold:
      threshold?.poin != null
        ? `${threshold.poin} poin`
        : threshold?.nama || threshold?.name || "-",

    tanggalMulai: formatDate(item?.tanggal_mulai),

    tanggalSelesai: formatDate(item?.tanggal_selesai),

    catatan: item?.catatan || null,

    logs: logs.map((log) => {
      const logStaff = log?.staff;
      const logStaffUser = logStaff?.user;

      return {
        id: log?.id,

        status: log?.status || "-",

        staff:
          logStaffUser?.name ||
          logStaffUser?.nama ||
          logStaff?.name ||
          logStaff?.nama ||
          "-",

        createdAt: formatDateTime(log?.created_at),

        catatan: log?.catatan || null,
      };
    }),
  };
};

/*
|--------------------------------------------------------------------------
| Helper
|--------------------------------------------------------------------------
*/

const getInitial = (name) => {
  if (!name) return "?";

  return name
    .split(" ")
    .map((word) => word.charAt(0))
    .slice(0, 2)
    .join("")
    .toUpperCase();
};

const getStageLabel = (stage) => {
  const labels = {
    wali_kelas: "Wali Kelas",
    bk: "BK",
    kesiswaan: "Kesiswaan",
    kepala_sekolah: "Kepala Sekolah",
  };

  return labels[stage] || stage;
};

const getStageClass = (stage) => {
  return `stage-${stage}`;
};

const getStatusLabel = (status) => {
  const labels = {
    menunggu: "Menunggu",
    diproses: "Diproses",
    selesai: "Selesai",
  };

  return labels[status] || status;
};

const getStatusClass = (status) => {
  return `status-${status}`;
};

const formatDate = (value) => {
  if (!value) return null;

  const date = new Date(value);

  if (Number.isNaN(date.getTime())) {
    return value;
  }

  return date.toLocaleDateString("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric",
  });
};

const formatDateTime = (value) => {
  if (!value) return "-";

  const date = new Date(value);

  if (Number.isNaN(date.getTime())) {
    return value;
  }

  return date.toLocaleString("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

const goBack = () => {
  router.push("/staf/penanganan");
};

const goToEdit = () => {
  router.push(`/staf/penanganan/${route.params.id}/edit`);
};

onMounted(fetchIntervention);
</script>

<style scoped>
.page-container {
  width: 100%;
}

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

.page-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 20px;
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

.primary-button,
.secondary-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  height: 40px;
  padding: 0 15px;
  border-radius: 8px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}

.primary-button {
  border: 1px solid #2563eb;
  background: #2563eb;
  color: #ffffff;
}

.primary-button:hover {
  background: #1d4ed8;
}

.secondary-button {
  border: 1px solid #dbe2ea;
  background: #ffffff;
  color: #475569;
}

.secondary-button:hover {
  background: #f8fafc;
}

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

.state-card .secondary-button {
  margin-top: 10px;
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

.student-card {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 20px;
  margin-bottom: 20px;
  background: #ffffff;
  border: 1px solid #e5eaf1;
  border-radius: 12px;
}

.student-avatar {
  width: 58px;
  height: 58px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  background: #eaf2ff;
  color: #2563eb;
  font-size: 17px;
  font-weight: 700;
}

.student-main {
  flex: 1;
  min-width: 0;
}

.student-heading {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 15px;
}

.student-heading h2 {
  margin: 0 0 4px;
  color: #172033;
  font-size: 18px;
}

.student-heading > div > span {
  color: #94a3b8;
  font-size: 12px;
}

.student-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 16px;
  margin-top: 12px;
}

.student-meta span {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  color: #64748b;
  font-size: 12px;
}

.detail-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
  margin-bottom: 20px;
}

.detail-card {
  background: #ffffff;
  border: 1px solid #e5eaf1;
  border-radius: 12px;
  overflow: hidden;
}

.card-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 15px;
  padding: 18px 20px;
  border-bottom: 1px solid #eef2f6;
}

.card-header svg {
  color: #2563eb;
  flex-shrink: 0;
}

.card-header h3 {
  margin: 0 0 4px;
  color: #172033;
  font-size: 15px;
}

.card-header p {
  margin: 0;
  color: #94a3b8;
  font-size: 11px;
}

.detail-list {
  padding: 4px 20px;
}

.detail-item {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  padding: 14px 0;
  border-bottom: 1px solid #f1f4f8;
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-item > span {
  color: #64748b;
  font-size: 12px;
}

.detail-item > strong {
  color: #334155;
  font-size: 13px;
  text-align: right;
}

.point-value {
  color: #2563eb !important;
}

.staff-detail {
  display: flex;
  flex-direction: column;
  gap: 3px;
  text-align: right;
}

.staff-detail strong {
  color: #334155;
  font-size: 13px;
}

.staff-detail span {
  color: #94a3b8;
  font-size: 11px;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 9px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 600;
  white-space: nowrap;
}

.status-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
}

.status-menunggu {
  background: #fffbeb;
  color: #b45309;
}

.status-menunggu .status-dot {
  background: #f59e0b;
}

.status-diproses {
  background: #eff6ff;
  color: #2563eb;
}

.status-diproses .status-dot {
  background: #3b82f6;
}

.status-selesai {
  background: #f0fdf4;
  color: #15803d;
}

.status-selesai .status-dot {
  background: #22c55e;
}

.stage-badge {
  display: inline-flex;
  align-items: center;
  padding: 5px 9px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 600;
  white-space: nowrap;
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

.violation-card {
  display: flex;
  align-items: center;
  gap: 13px;
  margin: 18px 20px;
  padding: 14px;
  background: #f8fafc;
  border: 1px solid #eef2f6;
  border-radius: 9px;
}

.violation-icon {
  width: 40px;
  height: 40px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 9px;
  background: #fff7ed;
  color: #ea580c;
}

.violation-content {
  min-width: 0;
}

.violation-content strong {
  display: block;
  margin-bottom: 5px;
  color: #334155;
  font-size: 13px;
}

.violation-meta {
  display: flex;
  gap: 12px;
}

.violation-meta span {
  color: #94a3b8;
  font-size: 11px;
}

.note-box {
  margin: 0 20px 20px;
  padding: 14px;
  background: #f8fbff;
  border: 1px solid #e2ebf8;
  border-radius: 9px;
}

.note-box > span {
  display: block;
  margin-bottom: 6px;
  color: #64748b;
  font-size: 11px;
  font-weight: 600;
}

.note-box p {
  margin: 0;
  color: #475569;
  font-size: 12px;
  line-height: 1.6;
}

.logs-card {
  margin-bottom: 20px;
}

.timeline {
  padding: 20px;
}

.timeline-item {
  display: flex;
  gap: 14px;
}

.timeline-line {
  position: relative;
  width: 14px;
  flex-shrink: 0;
  display: flex;
  justify-content: center;
}

.timeline-dot {
  position: relative;
  z-index: 2;
  width: 11px;
  height: 11px;
  margin-top: 4px;
  border-radius: 50%;
}

.timeline-dot.status-menunggu {
  background: #f59e0b;
}

.timeline-dot.status-diproses {
  background: #3b82f6;
}

.timeline-dot.status-selesai {
  background: #22c55e;
}

.timeline-connector {
  position: absolute;
  top: 15px;
  bottom: -8px;
  width: 1px;
  background: #dce3ec;
}

.timeline-content {
  flex: 1;
  padding-bottom: 22px;
}

.timeline-top {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 15px;
}

.timeline-top > div {
  display: flex;
  flex-direction: column;
  gap: 3px;
}

.timeline-top strong {
  color: #334155;
  font-size: 13px;
}

.timeline-top span {
  color: #94a3b8;
  font-size: 11px;
}

.timeline-top time {
  color: #94a3b8;
  font-size: 11px;
  white-space: nowrap;
}

.timeline-content p {
  margin: 8px 0 0;
  color: #64748b;
  font-size: 12px;
  line-height: 1.6;
}

.no-note {
  display: block;
  margin-top: 8px;
  color: #cbd5e1;
  font-size: 11px;
  font-style: italic;
}

.empty-logs {
  min-height: 180px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 7px;
  color: #94a3b8;
}

.empty-logs svg {
  color: #cbd5e1;
  margin-bottom: 5px;
}

.empty-logs strong {
  color: #475569;
  font-size: 13px;
}

.empty-logs span {
  font-size: 11px;
}

@media (max-width: 900px) {
  .detail-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 640px) {
  .page-header {
    flex-direction: column;
  }

  .primary-button {
    width: 100%;
  }

  .student-card {
    align-items: flex-start;
  }

  .student-heading {
    flex-direction: column;
  }

  .student-meta {
    flex-direction: column;
    gap: 7px;
  }

  .detail-item {
    align-items: flex-start;
    flex-direction: column;
    gap: 6px;
  }

  .detail-item > strong,
  .staff-detail {
    text-align: left;
  }

  .timeline-top {
    flex-direction: column;
    gap: 5px;
  }
}
</style>
