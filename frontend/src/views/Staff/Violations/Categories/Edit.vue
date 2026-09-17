<template>
  <div class="page">
    <div class="page-header">
      <div>
        <button class="back-button" @click="goBack">
          <ArrowLeft :size="17" />
          kembali
        </button>

        <p class="eyebrow">pelanggaran</p>
        <h1>detail kategori pelanggaran</h1>
        <p class="description">Informasi lengkap kategori pelanggaran.</p>
      </div>

      <div v-if="category" class="header-actions">
        <button class="secondary-button" @click="goToEdit">
          <Pencil :size="17" />
          edit
        </button>

        <button class="danger-button" @click="deleteCategory">
          <Trash2 :size="17" />
          hapus
        </button>
      </div>
    </div>

    <div v-if="loading" class="state">
      <LoaderCircle class="spin" :size="30" />
      <p>memuat detail kategori...</p>
    </div>

    <div v-else-if="error" class="alert error-alert">
      <AlertCircle :size="18" />
      <span>{{ error }}</span>

      <button class="retry-button" @click="fetchCategory">coba lagi</button>
    </div>

    <template v-else-if="category">
      <div class="overview-card">
        <div class="category-icon">
          <ShieldAlert :size="26" />
        </div>

        <div class="overview-content">
          <div class="title-row">
            <h2>{{ category.nama_pelanggaran }}</h2>

            <span
              class="status-badge"
              :class="category.status ? 'active' : 'inactive'"
            >
              <span class="status-dot"></span>
              {{ category.status ? "aktif" : "nonaktif" }}
            </span>
          </div>

          <p>
            {{
              category.deskripsi || "tidak ada deskripsi untuk kategori ini."
            }}
          </p>
        </div>
      </div>

      <div class="detail-grid">
        <div class="detail-card">
          <div class="detail-icon level">
            <Layers :size="20" />
          </div>

          <div>
            <span>tingkat pelanggaran</span>
            <strong class="level-text" :class="`level-${category.kategori}`">
              {{ category.kategori }}
            </strong>
          </div>
        </div>

        <div class="detail-card">
          <div class="detail-icon point">
            <CircleAlert :size="20" />
          </div>

          <div>
            <span>poin pelanggaran</span>
            <strong>{{ category.poin }} poin</strong>
          </div>
        </div>

        <div class="detail-card">
          <div class="detail-icon status">
            <CircleCheck :size="20" />
          </div>

          <div>
            <span>status</span>
            <strong>
              {{ category.status ? "aktif" : "nonaktif" }}
            </strong>
          </div>
        </div>

        <div class="detail-card">
          <div class="detail-icon id">
            <Hash :size="20" />
          </div>

          <div>
            <span>id kategori</span>
            <strong>#{{ category.id }}</strong>
          </div>
        </div>
      </div>

      <div class="info-card">
        <div class="info-heading">
          <FileText :size="19" />
          <div>
            <h2>deskripsi</h2>
            <p>Penjelasan kategori pelanggaran.</p>
          </div>
        </div>

        <div class="description-content">
          {{ category.deskripsi || "tidak ada deskripsi." }}
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import {
  AlertCircle,
  ArrowLeft,
  CircleAlert,
  CircleCheck,
  FileText,
  Hash,
  Layers,
  LoaderCircle,
  Pencil,
  ShieldAlert,
  Trash2,
} from "lucide-vue-next";

import api from "../../../../utils/api";

const route = useRoute();
const router = useRouter();

const category = ref(null);
const loading = ref(false);
const error = ref("");

const fetchCategory = async () => {
  loading.value = true;
  error.value = "";

  try {
    const response = await api.get(
      `/staff/violation-categories/${route.params.id}`,
    );

    category.value = response.data?.data || null;

    if (!category.value) {
      error.value = "kategori pelanggaran tidak ditemukan.";
    }
  } catch (err) {
    console.error("Gagal mengambil detail kategori:", err);

    error.value =
      err.response?.data?.message ||
      "detail kategori pelanggaran gagal dimuat.";
  } finally {
    loading.value = false;
  }
};

const goBack = () => {
  router.push({
    name: "staf.pelanggaran.kategori",
  });
};

const goToEdit = () => {
  router.push({
    name: "staf.pelanggaran.kategori.edit",
    params: { id: route.params.id },
  });
};

const deleteCategory = async () => {
  if (!category.value) return;

  const confirmed = window.confirm(
    `hapus kategori "${category.value.nama_pelanggaran}"?`,
  );

  if (!confirmed) return;

  try {
    await api.delete(`/staff/violation-categories/${route.params.id}`);

    router.push({
      name: "staf.pelanggaran.kategori",
    });
  } catch (err) {
    console.error("Gagal menghapus kategori:", err);

    window.alert(
      err.response?.data?.message || "kategori pelanggaran gagal dihapus.",
    );
  }
};

onMounted(fetchCategory);
</script>

<style scoped>
.page {
  max-width: 1100px;
  margin: 0 auto;
}

.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 24px;
  margin-bottom: 24px;
}

.back-button {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  margin-bottom: 22px;
  padding: 0;
  border: 0;
  background: transparent;
  color: #64748b;
  font-size: 13px;
  cursor: pointer;
}

.back-button:hover {
  color: #172033;
}

.eyebrow {
  margin: 0 0 6px;
  color: #64748b;
  font-size: 13px;
  font-weight: 600;
}

.page-header h1 {
  margin: 0;
  color: #172033;
  font-size: 28px;
  font-weight: 700;
  letter-spacing: -0.03em;
}

.description {
  margin: 8px 0 0;
  color: #64748b;
  font-size: 14px;
}

.header-actions {
  display: flex;
  gap: 8px;
}

.secondary-button,
.danger-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 7px;
  min-height: 40px;
  padding: 0 14px;
  border-radius: 9px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}

.secondary-button {
  border: 1px solid #dbe0e6;
  background: #fff;
  color: #475569;
}

.secondary-button:hover {
  background: #f8fafc;
}

.danger-button {
  border: 1px solid #fecaca;
  background: #fff;
  color: #dc2626;
}

.danger-button:hover {
  background: #fef2f2;
}

.alert {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 13px 15px;
  border-radius: 10px;
  font-size: 14px;
}

.error-alert {
  border: 1px solid #fecaca;
  background: #fef2f2;
  color: #b91c1c;
}

.retry-button {
  margin-left: auto;
  border: 0;
  background: transparent;
  color: inherit;
  font-weight: 600;
  cursor: pointer;
}

.state {
  display: flex;
  min-height: 350px;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  color: #64748b;
}

.state p {
  margin: 10px 0 0;
  font-size: 14px;
}

.spin {
  animation: spin 1s linear infinite;
}

.overview-card {
  display: flex;
  align-items: center;
  gap: 18px;
  margin-bottom: 18px;
  padding: 24px;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  background: #fff;
}

.category-icon {
  display: grid;
  width: 58px;
  height: 58px;
  flex-shrink: 0;
  place-items: center;
  border-radius: 14px;
  background: #fff7ed;
  color: #ea580c;
}

.overview-content {
  min-width: 0;
  flex: 1;
}

.title-row {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.title-row h2 {
  margin: 0;
  color: #172033;
  font-size: 21px;
}

.overview-content p {
  margin: 7px 0 0;
  color: #64748b;
  font-size: 14px;
  line-height: 1.6;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 5px 9px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
}

.status-badge.active {
  background: #ecfdf5;
  color: #047857;
}

.status-badge.inactive {
  background: #f1f5f9;
  color: #64748b;
}

.status-dot {
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: currentColor;
}

.detail-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 14px;
  margin-bottom: 18px;
}

.detail-card {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 18px;
  border: 1px solid #e5e7eb;
  border-radius: 12px;
  background: #fff;
}

.detail-icon {
  display: grid;
  width: 40px;
  height: 40px;
  flex-shrink: 0;
  place-items: center;
  border-radius: 10px;
}

.detail-icon.level {
  background: #eff6ff;
  color: #2563eb;
}

.detail-icon.point {
  background: #fff7ed;
  color: #ea580c;
}

.detail-icon.status {
  background: #ecfdf5;
  color: #059669;
}

.detail-icon.id {
  background: #f1f5f9;
  color: #64748b;
}

.detail-card span {
  display: block;
  margin-bottom: 4px;
  color: #64748b;
  font-size: 11px;
}

.detail-card strong {
  display: block;
  color: #172033;
  font-size: 14px;
}

.level-text {
  text-transform: capitalize;
}

.level-ringan {
  color: #047857 !important;
}

.level-sedang {
  color: #b45309 !important;
}

.level-berat {
  color: #b91c1c !important;
}

.info-card {
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  background: #fff;
}

.info-heading {
  display: flex;
  align-items: center;
  gap: 11px;
  padding: 18px 20px;
  border-bottom: 1px solid #e5e7eb;
  color: #2563eb;
}

.info-heading h2 {
  margin: 0;
  color: #172033;
  font-size: 15px;
}

.info-heading p {
  margin: 3px 0 0;
  color: #64748b;
  font-size: 12px;
}

.description-content {
  padding: 20px;
  color: #475569;
  font-size: 14px;
  line-height: 1.7;
  white-space: pre-wrap;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

@media (max-width: 900px) {
  .detail-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 700px) {
  .page-header {
    align-items: stretch;
    flex-direction: column;
  }

  .header-actions {
    align-self: flex-start;
  }

  .overview-card {
    align-items: flex-start;
  }

  .detail-grid {
    grid-template-columns: 1fr;
  }
}
</style>
