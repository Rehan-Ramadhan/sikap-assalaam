<template>
  <AppLayout>
    <div class="page-container">
      <!-- Header -->
      <div class="page-header">
        <div>
          <h1>Kategori Prestasi</h1>
          <p>Kelola kategori dan poin prestasi siswa.</p>
        </div>

        <button class="btn btn-primary" @click="goToCreate">
          <Plus :size="18" />
          Tambah Kategori
        </button>
      </div>

      <!-- Statistik -->
      <div class="stats-grid">
        <div class="stat-card">
          <div class="stat-icon blue">
            <Trophy :size="21" />
          </div>

          <div>
            <span>Total Kategori</span>
            <strong>{{ categories.length }}</strong>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon green">
            <CircleCheck :size="21" />
          </div>

          <div>
            <span>Kategori Aktif</span>
            <strong>{{ activeCount }}</strong>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon gray">
            <CircleX :size="21" />
          </div>

          <div>
            <span>Kategori Nonaktif</span>
            <strong>{{ inactiveCount }}</strong>
          </div>
        </div>

        <div class="stat-card">
          <div class="stat-icon orange">
            <Medal :size="21" />
          </div>

          <div>
            <span>Poin Tertinggi</span>
            <strong>{{ highestPoint }} Poin</strong>
          </div>
        </div>
      </div>

      <!-- Filter -->
      <div class="filter-card">
        <div class="search-box">
          <Search :size="18" />

          <input
            v-model="search"
            type="text"
            placeholder="Cari nama prestasi..."
          />
        </div>

        <select v-model="filterTingkat">
          <option value="">Semua Tingkat</option>
          <option value="sekolah">Sekolah</option>
          <option value="kecamatan">Kecamatan</option>
          <option value="kabupaten">Kabupaten</option>
          <option value="provinsi">Provinsi</option>
          <option value="nasional">Nasional</option>
          <option value="internasional">Internasional</option>
        </select>

        <select v-model="filterStatus">
          <option value="">Semua Status</option>
          <option value="aktif">Aktif</option>
          <option value="nonaktif">Nonaktif</option>
        </select>
      </div>

      <!-- Error -->
      <div v-if="errorMessage" class="alert-error">
        <CircleAlert :size="18" />
        <span>{{ errorMessage }}</span>

        <button @click="loadCategories">Coba lagi</button>
      </div>

      <!-- Table -->
      <div class="table-card">
        <div class="table-header">
          <div>
            <h2>Daftar Kategori Prestasi</h2>
            <p>{{ filteredCategories.length }} kategori ditemukan</p>
          </div>
        </div>

        <div v-if="loading" class="loading-state">
          <div class="loading-spinner"></div>
          <p>Memuat kategori prestasi...</p>
        </div>

        <div v-else class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th>No</th>
                <th>Nama Prestasi</th>
                <th>Tingkat</th>
                <th>Poin</th>
                <th>Deskripsi</th>
                <th>Status</th>
                <th>Aksi</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="(category, index) in filteredCategories"
                :key="category.id"
              >
                <td>
                  {{ index + 1 }}
                </td>

                <td>
                  <div class="achievement-info">
                    <div class="achievement-icon">
                      <Trophy :size="18" />
                    </div>

                    <div class="achievement-details">
                      <strong>{{ category.namaPrestasi }}</strong>
                    </div>
                  </div>
                </td>

                <td>
                  <span
                    class="level-badge"
                    :class="`level-${category.tingkat}`"
                  >
                    {{ formatTingkat(category.tingkat) }}
                  </span>
                </td>

                <td>
                  <span class="point-badge"> +{{ category.poin }} </span>
                </td>

                <td>
                  <span class="description">
                    {{ category.deskripsi || "-" }}
                  </span>
                </td>

                <td>
                  <span
                    class="status-badge"
                    :class="category.status ? 'active' : 'inactive'"
                  >
                    <span class="status-dot"></span>
                    {{ category.status ? "Aktif" : "Nonaktif" }}
                  </span>
                </td>

                <td>
                  <div class="action-buttons">
                    <button
                      class="action-btn view"
                      title="Lihat detail"
                      @click="goToShow(category.id)"
                    >
                      <Eye :size="17" />
                    </button>

                    <button
                      class="action-btn edit"
                      title="Edit"
                      @click="goToEdit(category.id)"
                    >
                      <Pencil :size="17" />
                    </button>

                    <button
                      class="action-btn delete"
                      title="Hapus"
                      :disabled="deletingId === category.id"
                      @click="deleteCategory(category)"
                    >
                      <Trash2 :size="17" />
                    </button>
                  </div>
                </td>
              </tr>

              <tr v-if="filteredCategories.length === 0">
                <td colspan="7">
                  <div class="empty-state">
                    <div class="empty-icon">
                      <Trophy :size="26" />
                    </div>

                    <h3>Kategori tidak ditemukan</h3>

                    <p>
                      Tidak ada kategori prestasi yang sesuai dengan pencarian.
                    </p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, ref, onMounted } from "vue";
import { useRouter } from "vue-router";

import AppLayout from "../../../../layouts/AppLayout.vue";
import api from "../../../../utils/api";

import {
  CircleAlert,
  CircleCheck,
  CircleX,
  Eye,
  Medal,
  Pencil,
  Plus,
  Search,
  Trash2,
  Trophy,
} from "lucide-vue-next";

const router = useRouter();

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const categories = ref([]);

const search = ref("");
const filterTingkat = ref("");
const filterStatus = ref("");

const loading = ref(false);
const deletingId = ref(null);
const errorMessage = ref("");

/*
|--------------------------------------------------------------------------
| Load Data
|--------------------------------------------------------------------------
*/

const loadCategories = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await api.get("/staff/achievement-categories");

    categories.value = normalizeCategories(response.data);
  } catch (error) {
    console.error("Gagal mengambil kategori prestasi:", error);

    errorMessage.value =
      error.response?.data?.message || "Gagal memuat kategori prestasi.";
  } finally {
    loading.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| Normalize Response
|--------------------------------------------------------------------------
*/

const normalizeCategories = (response) => {
  if (Array.isArray(response)) {
    return response.map(normalizeCategory);
  }

  if (Array.isArray(response?.data)) {
    return response.data.map(normalizeCategory);
  }

  if (response?.data?.data && Array.isArray(response.data.data)) {
    return response.data.data.map(normalizeCategory);
  }

  return [];
};

const normalizeCategory = (category) => {
  return {
    id: category.id,
    namaPrestasi: category.nama_prestasi ?? category.namaPrestasi ?? "",
    poin: Number(category.poin ?? 0),
    tingkat: category.tingkat ?? "",
    deskripsi: category.deskripsi ?? "",
    status: Boolean(category.status),
  };
};

/*
|--------------------------------------------------------------------------
| Computed
|--------------------------------------------------------------------------
*/

const activeCount = computed(() => {
  return categories.value.filter((category) => category.status).length;
});

const inactiveCount = computed(() => {
  return categories.value.filter((category) => !category.status).length;
});

const highestPoint = computed(() => {
  if (categories.value.length === 0) return 0;

  return Math.max(
    ...categories.value.map((category) => Number(category.poin) || 0),
  );
});

const filteredCategories = computed(() => {
  const keyword = search.value.trim().toLowerCase();

  return categories.value.filter((category) => {
    const matchesSearch =
      !keyword ||
      category.namaPrestasi.toLowerCase().includes(keyword) ||
      category.deskripsi?.toLowerCase().includes(keyword);

    const matchesTingkat =
      !filterTingkat.value || category.tingkat === filterTingkat.value;

    const matchesStatus =
      !filterStatus.value ||
      (filterStatus.value === "aktif" && category.status) ||
      (filterStatus.value === "nonaktif" && !category.status);

    return matchesSearch && matchesTingkat && matchesStatus;
  });
});

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

const formatTingkat = (tingkat) => {
  const names = {
    sekolah: "Sekolah",
    kecamatan: "Kecamatan",
    kabupaten: "Kabupaten",
    provinsi: "Provinsi",
    nasional: "Nasional",
    internasional: "Internasional",
  };

  return names[tingkat] || tingkat;
};

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

const goToCreate = () => {
  router.push("/staf/prestasi/kategori/create");
};

const goToShow = (id) => {
  router.push(`/staf/prestasi/kategori/${id}`);
};

const goToEdit = (id) => {
  router.push(`/staf/prestasi/kategori/${id}/edit`);
};

/*
|--------------------------------------------------------------------------
| Delete
|--------------------------------------------------------------------------
*/

const deleteCategory = async (category) => {
  const confirmed = confirm(
    `Apakah kamu yakin ingin menghapus kategori "${category.namaPrestasi}"?`,
  );

  if (!confirmed) return;

  deletingId.value = category.id;
  errorMessage.value = "";

  try {
    await api.delete(`/staff/achievement-categories/${category.id}`);

    categories.value = categories.value.filter(
      (item) => item.id !== category.id,
    );

    alert("Kategori prestasi berhasil dihapus.");
  } catch (error) {
    console.error("Gagal menghapus kategori prestasi:", error);

    errorMessage.value =
      error.response?.data?.message || "Kategori prestasi gagal dihapus.";
  } finally {
    deletingId.value = null;
  }
};

/*
|--------------------------------------------------------------------------
| Mounted
|--------------------------------------------------------------------------
*/

onMounted(() => {
  loadCategories();
});
</script>

<style scoped>
.page-container {
  width: 100%;
}

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 24px;
}

.page-header h1 {
  margin: 0 0 6px;
  color: #172033;
  font-size: 28px;
  font-weight: 700;
}

.page-header p {
  margin: 0;
  color: #718096;
  font-size: 14px;
}

.btn {
  min-height: 42px;
  padding: 0 17px;
  border: none;
  border-radius: 9px;
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

.btn-primary {
  background: #2563eb;
  color: white;
}

.btn-primary:hover {
  background: #1d4ed8;
}

/* Stats */

.stats-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 12px;
  margin-bottom: 18px;
}

.stat-card {
  height: 74px;
  min-height: 0;
  box-sizing: border-box;

  display: flex;
  align-items: center;
  gap: 11px;

  padding: 12px 15px;

  background: white;
  border: 1px solid #e8ebf2;
  border-radius: 10px;
  box-shadow: 0 2px 8px rgba(30, 41, 59, 0.03);
}

.stat-icon {
  width: 44px;
  height: 44px;
  border-radius: 10px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.stat-icon.blue {
  background: #eff6ff;
  color: #2563eb;
}

.stat-icon.green {
  background: #ecfdf5;
  color: #059669;
}

.stat-icon.gray {
  background: #f1f5f9;
  color: #64748b;
}

.stat-icon.orange {
  background: #fff7ed;
  color: #ea580c;
}

.stat-card span {
  display: block;
  margin-bottom: 4px;
  color: #718096;
  font-size: 12px;
}

.stat-card strong {
  color: #172033;
  font-size: 20px;
}

/* Filter */

.filter-card {
  background: white;
  border: 1px solid #e8edf5;
  border-radius: 12px;
  padding: 16px;
  display: flex;
  gap: 12px;
  margin-bottom: 20px;
}

.search-box {
  flex: 1;
  min-height: 42px;
  border: 1px solid #d9e0ea;
  border-radius: 9px;
  display: flex;
  align-items: center;
  gap: 9px;
  padding: 0 13px;
  color: #94a3b8;
}

.search-box input {
  width: 100%;
  border: none;
  outline: none;
  font-family: inherit;
  font-size: 14px;
  color: #172033;
}

.filter-card select {
  min-width: 170px;
  height: 42px;
  padding: 0 12px;
  border: 1px solid #d9e0ea;
  border-radius: 9px;
  background: white;
  color: #475569;
  font-family: inherit;
  font-size: 14px;
  outline: none;
}

/* Alert */

.alert-error {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
  padding: 13px 15px;
  border: 1px solid #fecaca;
  border-radius: 10px;
  background: #fef2f2;
  color: #b91c1c;
  font-size: 13px;
}

.alert-error button {
  margin-left: auto;
  border: none;
  background: transparent;
  color: #b91c1c;
  font-family: inherit;
  font-size: 13px;
  font-weight: 700;
  cursor: pointer;
}

/* Table */

.table-card {
  background: white;
  border: 1px solid #e8edf5;
  border-radius: 12px;
  overflow: hidden;
}

.table-header {
  padding: 20px;
  border-bottom: 1px solid #edf1f7;
}

.table-header h2 {
  margin: 0 0 4px;
  color: #172033;
  font-size: 16px;
}

.table-header p {
  margin: 0;
  color: #718096;
  font-size: 13px;
}

.table-wrapper {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background: #f8fafc;
}

th {
  padding: 13px 16px;
  color: #64748b;
  font-size: 12px;
  font-weight: 700;
  text-align: left;
  white-space: nowrap;
  border-bottom: 1px solid #edf1f7;
}

td {
  padding: 15px 16px;
  color: #475569;
  font-size: 13px;
  border-bottom: 1px solid #edf1f7;
  vertical-align: middle;
}

tbody tr:last-child td {
  border-bottom: none;
}

tbody tr:hover {
  background: #fafcff;
}

/* Loading */

.loading-state {
  min-height: 280px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 12px;
  color: #718096;
  font-size: 13px;
}

.loading-spinner {
  width: 28px;
  height: 28px;
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

/* Achievement */

.achievement-info {
  display: flex;
  align-items: center;
  gap: 11px;
  min-width: 230px;
}

.achievement-icon {
  width: 38px;
  height: 38px;
  flex-shrink: 0;
  border-radius: 9px;
  background: #eff6ff;
  color: #2563eb;
  display: flex;
  align-items: center;
  justify-content: center;
}

.achievement-details strong {
  display: block;
  color: #172033;
  font-size: 13px;
  font-weight: 600;
  line-height: 1.5;
}

/* Level */

.level-badge {
  display: inline-flex;
  align-items: center;
  padding: 5px 9px;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 600;
  white-space: nowrap;
}

.level-sekolah {
  background: #f1f5f9;
  color: #475569;
}

.level-kecamatan {
  background: #eff6ff;
  color: #2563eb;
}

.level-kabupaten {
  background: #ecfdf5;
  color: #047857;
}

.level-provinsi {
  background: #fff7ed;
  color: #c2410c;
}

.level-nasional {
  background: #fef2f2;
  color: #b91c1c;
}

.level-internasional {
  background: #f5f3ff;
  color: #6d28d9;
}

/* Point */

.point-badge {
  display: inline-flex;
  align-items: center;
  padding: 5px 9px;
  border-radius: 6px;
  background: #eff6ff;
  color: #2563eb;
  font-size: 12px;
  font-weight: 700;
}

/* Description */

.description {
  display: block;
  max-width: 240px;
  color: #64748b;
  line-height: 1.5;
}

/* Status */

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

/* Actions */

.action-buttons {
  display: flex;
  align-items: center;
  gap: 7px;
}

.action-btn {
  width: 34px;
  height: 34px;
  border: none;
  border-radius: 7px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  transition: 0.2s;
}

.action-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.action-btn.view {
  background: #eff6ff;
  color: #2563eb;
}

.action-btn.view:hover {
  background: #dbeafe;
}

.action-btn.edit {
  background: #fffbeb;
  color: #d97706;
}

.action-btn.edit:hover {
  background: #fef3c7;
}

.action-btn.delete {
  background: #fef2f2;
  color: #dc2626;
}

.action-btn.delete:hover {
  background: #fee2e2;
}

/* Empty */

.empty-state {
  min-height: 230px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
}

.empty-icon {
  width: 54px;
  height: 54px;
  border-radius: 50%;
  background: #eff6ff;
  color: #2563eb;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 12px;
}

.empty-state h3 {
  margin: 0 0 5px;
  color: #172033;
  font-size: 15px;
}

.empty-state p {
  margin: 0;
  color: #718096;
  font-size: 13px;
}

/* Responsive */

@media (max-width: 1000px) {
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 768px) {
  .page-header {
    align-items: flex-start;
    flex-direction: column;
  }

  .page-header .btn {
    width: 100%;
  }

  .filter-card {
    flex-direction: column;
  }

  .filter-card select {
    width: 100%;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }
}
</style>
