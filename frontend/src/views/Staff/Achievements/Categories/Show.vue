<template>
  <AppLayout>
    <div class="page-header">
      <div class="header-left">
        <button class="btn-back" @click="goBack">
          <ArrowLeft :size="18" />
          <span>Kembali</span>
        </button>

        <div>
          <h1>Detail Kategori Prestasi</h1>
          <p>Informasi lengkap kategori prestasi siswa.</p>
        </div>
      </div>

      <div v-if="category" class="header-actions">
        <button class="btn btn-warning" @click="goEdit">
          <Pencil :size="17" />
          <span>Edit Kategori</span>
        </button>
      </div>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="empty-state">
      <div class="loading-spinner"></div>

      <h3>Memuat kategori...</h3>

      <p>Data kategori prestasi sedang diambil.</p>
    </div>

    <!-- Not Found / Error -->
    <div v-else-if="!category" class="empty-state">
      <div class="empty-icon">
        <FolderOpen :size="42" :stroke-width="1.7" />
      </div>

      <h3>Kategori tidak ditemukan</h3>

      <p>
        {{
          errorMessage ||
          "Data kategori prestasi yang kamu cari tidak tersedia."
        }}
      </p>

      <button class="btn btn-primary" @click="goBack">
        Kembali ke Kategori
      </button>
    </div>

    <template v-else>
      <!-- Main Info -->
      <div class="detail-grid">
        <!-- Left Card -->
        <div class="card category-profile-card">
          <div class="category-icon">
            <Trophy :size="36" :stroke-width="1.8" />
          </div>

          <h2>{{ category.namaPrestasi }}</h2>

          <span
            class="status-badge"
            :class="category.status ? 'status-active' : 'status-inactive'"
          >
            {{ category.status ? "Aktif" : "Nonaktif" }}
          </span>

          <div class="category-level">
            <span class="level-label">Tingkat</span>

            <strong>
              {{ formatTingkat(category.tingkat) }}
            </strong>
          </div>
        </div>

        <!-- Right Card -->
        <div class="card detail-card">
          <div class="card-title">
            <h3>Informasi Kategori</h3>
          </div>

          <div class="detail-list">
            <div class="detail-item">
              <span class="detail-label"> Nama Prestasi </span>

              <span class="detail-value">
                {{ category.namaPrestasi }}
              </span>
            </div>

            <div class="detail-item">
              <span class="detail-label"> Tingkat </span>

              <span class="detail-value">
                <span class="level-badge">
                  {{ formatTingkat(category.tingkat) }}
                </span>
              </span>
            </div>

            <div class="detail-item">
              <span class="detail-label"> Poin </span>

              <span class="detail-value">
                <span class="point-badge"> +{{ category.poin }} </span>
              </span>
            </div>

            <div class="detail-item">
              <span class="detail-label"> Status </span>

              <span class="detail-value">
                <span
                  class="status-badge"
                  :class="category.status ? 'status-active' : 'status-inactive'"
                >
                  {{ category.status ? "Aktif" : "Nonaktif" }}
                </span>
              </span>
            </div>

            <div class="detail-item detail-description">
              <span class="detail-label"> Deskripsi </span>

              <span class="detail-value description-text">
                {{ category.deskripsi || "Tidak ada deskripsi." }}
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Info -->
      <div class="card info-card">
        <div class="info-icon">
          <Info :size="22" :stroke-width="2" />
        </div>

        <div>
          <h3>Tentang kategori prestasi</h3>

          <p>
            Kategori ini digunakan sebagai acuan ketika mencatat prestasi siswa.
            Nilai poin akan tersimpan pada data prestasi siswa saat kategori
            digunakan.
          </p>

          <p v-if="!category.status">
            Kategori saat ini nonaktif sehingga sebaiknya tidak digunakan untuk
            pencatatan prestasi baru.
          </p>
        </div>
      </div>
    </template>
  </AppLayout>
</template>

<script setup>
import { ref, onMounted } from "vue";
import {
  ArrowLeft,
  CircleAlert,
  Pencil,
  Trophy,
  Info,
  FolderOpen,
} from "lucide-vue-next";
import { useRoute, useRouter } from "vue-router";

import AppLayout from "../../../../layouts/AppLayout.vue";
import api from "../../../../utils/api";

const route = useRoute();
const router = useRouter();

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const category = ref(null);
const loading = ref(false);
const errorMessage = ref("");

/*
|--------------------------------------------------------------------------
| Load Category
|--------------------------------------------------------------------------
*/

const loadCategory = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await api.get(
      `/staff/achievement-categories/${route.params.id}`,
    );

    const data = response.data?.data ?? response.data;

    if (!data?.id) {
      category.value = null;
      return;
    }

    category.value = {
      id: data.id,
      namaPrestasi: data.nama_prestasi ?? data.namaPrestasi ?? "",
      poin: Number(data.poin ?? 0),
      tingkat: data.tingkat ?? "",
      deskripsi: data.deskripsi ?? "",
      status: Boolean(data.status),
    };
  } catch (error) {
    console.error("Gagal mengambil detail kategori prestasi:", error);

    category.value = null;

    errorMessage.value =
      error.response?.data?.message || "Gagal memuat detail kategori prestasi.";
  } finally {
    loading.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

const formatTingkat = (tingkat) => {
  const labels = {
    sekolah: "Sekolah",
    kecamatan: "Kecamatan",
    kabupaten: "Kabupaten",
    provinsi: "Provinsi",
    nasional: "Nasional",
    internasional: "Internasional",
  };

  return labels[tingkat] || tingkat;
};

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

const goBack = () => {
  router.push("/staf/prestasi/kategori");
};

const goEdit = () => {
  if (!category.value) return;

  router.push(`/staf/prestasi/kategori/${category.value.id}/edit`);
};

/*
|--------------------------------------------------------------------------
| Mounted
|--------------------------------------------------------------------------
*/

onMounted(() => {
  loadCategory();
});
</script>

<style scoped>
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  gap: 20px;
  margin-bottom: 24px;
}

.header-left {
  display: flex;
  align-items: flex-start;
  gap: 16px;
}

.page-header h1 {
  margin: 0 0 6px;
  font-size: 28px;
  font-weight: 700;
  color: #172033;
}

.page-header p {
  margin: 0;
  color: #6b7280;
  font-size: 14px;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  border: none;
  background: #fff;
  color: #2563eb;
  padding: 10px 14px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
  box-shadow: 0 1px 4px rgba(0, 0, 0, 0.08);
  transition: 0.2s;
}

.btn-back:hover {
  background: #eff6ff;
}

.btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border: none;
  border-radius: 8px;
  padding: 11px 16px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s;
}

.btn-primary {
  background: #2563eb;
  color: white;
}

.btn-primary:hover {
  background: #1d4ed8;
}

.btn-warning {
  background: #f59e0b;
  color: white;
}

.btn-warning:hover {
  background: #d97706;
}

.detail-grid {
  display: grid;
  grid-template-columns: 300px 1fr;
  gap: 20px;
  margin-bottom: 20px;
}

.card {
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 2px 10px rgba(15, 23, 42, 0.06);
  border: 1px solid #eef2f7;
}

.category-profile-card {
  padding: 30px 24px;
  text-align: center;
}

.category-icon {
  width: 78px;
  height: 78px;
  margin: 0 auto 18px;
  border-radius: 50%;
  background: #eff6ff;
  color: #2563eb;
  display: flex;
  align-items: center;
  justify-content: center;
}

.category-profile-card h2 {
  margin: 0 0 12px;
  color: #172033;
  font-size: 20px;
  line-height: 1.4;
}

.category-level {
  margin-top: 24px;
  padding-top: 20px;
  border-top: 1px solid #eef2f7;
}

.level-label {
  display: block;
  color: #6b7280;
  font-size: 13px;
  margin-bottom: 5px;
}

.category-level strong {
  color: #2563eb;
  font-size: 16px;
}

.detail-card {
  padding: 24px;
}

.card-title {
  padding-bottom: 16px;
  border-bottom: 1px solid #eef2f7;
  margin-bottom: 4px;
}

.card-title h3 {
  margin: 0;
  font-size: 18px;
  color: #172033;
}

.detail-list {
  display: flex;
  flex-direction: column;
}

.detail-item {
  display: grid;
  grid-template-columns: 180px 1fr;
  gap: 20px;
  padding: 17px 0;
  border-bottom: 1px solid #f1f5f9;
}

.detail-item:last-child {
  border-bottom: none;
}

.detail-label {
  color: #6b7280;
  font-size: 14px;
}

.detail-value {
  color: #172033;
  font-size: 14px;
  font-weight: 600;
}

.detail-description {
  align-items: flex-start;
}

.description-text {
  line-height: 1.7;
  font-weight: 400;
}

.status-badge,
.level-badge,
.point-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
  padding: 6px 10px;
}

.status-active {
  background: #dcfce7;
  color: #15803d;
}

.status-inactive {
  background: #f3f4f6;
  color: #6b7280;
}

.level-badge {
  background: #eff6ff;
  color: #2563eb;
}

.point-badge {
  background: #dcfce7;
  color: #15803d;
  font-size: 13px;
}

.info-card {
  display: flex;
  gap: 16px;
  padding: 20px;
  background: #f8fbff;
}

.info-icon {
  flex-shrink: 0;
  width: 42px;
  height: 42px;
  border-radius: 10px;
  background: #dbeafe;
  color: #2563eb;
  display: flex;
  align-items: center;
  justify-content: center;
}

.info-card h3 {
  margin: 0 0 8px;
  color: #172033;
  font-size: 15px;
}

.info-card p {
  margin: 0 0 8px;
  color: #64748b;
  font-size: 13px;
  line-height: 1.6;
}

.info-card p:last-child {
  margin-bottom: 0;
}

.empty-state {
  background: #fff;
  border-radius: 14px;
  padding: 60px 20px;
  text-align: center;
  border: 1px solid #eef2f7;
}

.empty-icon {
  display: flex;
  justify-content: center;
  color: #94a3b8;
  margin-bottom: 12px;
}

.empty-state h3 {
  margin: 0 0 8px;
  color: #172033;
}

.empty-state p {
  margin: 0 0 20px;
  color: #6b7280;
}

.loading-spinner {
  width: 30px;
  height: 30px;
  margin: 0 auto 14px;
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

/* Responsive */

@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
  }

  .header-left {
    width: 100%;
  }

  .header-actions {
    width: 100%;
  }

  .header-actions .btn {
    width: 100%;
  }

  .detail-grid {
    grid-template-columns: 1fr;
  }

  .detail-item {
    grid-template-columns: 1fr;
    gap: 7px;
  }
}
</style>
