<template>
  <div class="page">
    <div class="page-header">
      <div>
        <p class="eyebrow">pelanggaran</p>
        <h1>kategori pelanggaran</h1>
        <p class="description">
          Kelola kategori pelanggaran yang digunakan dalam sistem poin siswa.
        </p>
      </div>

      <button class="primary-button" @click="goToCreate">
        <Plus :size="18" />
        tambah kategori
      </button>
    </div>

    <div v-if="error" class="alert error-alert">
      <AlertCircle :size="18" />
      <span>{{ error }}</span>
      <button class="retry-button" @click="fetchCategories">coba lagi</button>
    </div>

    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">
          <Layers :size="20" />
        </div>
        <div>
          <span class="stat-label">total kategori</span>
          <strong>{{ categories.length }}</strong>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon light">
          <CircleCheck :size="20" />
        </div>
        <div>
          <span class="stat-label">aktif</span>
          <strong>{{ activeCount }}</strong>
        </div>
      </div>

      <div class="stat-card">
        <div class="stat-icon muted">
          <CirclePause :size="20" />
        </div>
        <div>
          <span class="stat-label">nonaktif</span>
          <strong>{{ inactiveCount }}</strong>
        </div>
      </div>
    </div>

    <div class="content-card">
      <div class="toolbar">
        <div class="search-box">
          <Search :size="18" />
          <input
            v-model="search"
            type="text"
            placeholder="cari kategori pelanggaran..."
          />
        </div>

        <select v-model="statusFilter" class="filter-select">
          <option value="">semua status</option>
          <option value="active">aktif</option>
          <option value="inactive">nonaktif</option>
        </select>
      </div>

      <div v-if="loading" class="state">
        <LoaderCircle class="spin" :size="28" />
        <p>memuat kategori pelanggaran...</p>
      </div>

      <div v-else-if="filteredCategories.length === 0" class="empty-state">
        <div class="empty-icon">
          <Layers :size="24" />
        </div>

        <h3>
          {{
            categories.length === 0
              ? "belum ada kategori"
              : "kategori tidak ditemukan"
          }}
        </h3>

        <p>
          {{
            categories.length === 0
              ? "Tambahkan kategori pelanggaran untuk mulai menggunakan sistem."
              : "Coba ubah kata kunci pencarian atau filter."
          }}
        </p>

        <button
          v-if="categories.length === 0"
          class="primary-button"
          @click="goToCreate"
        >
          <Plus :size="18" />
          tambah kategori
        </button>
      </div>

      <div v-else class="table-wrapper">
        <table>
          <thead>
            <tr>
              <th>kategori pelanggaran</th>
              <th>tingkat</th>
              <th>poin</th>
              <th>status</th>
              <th class="action-column">aksi</th>
            </tr>
          </thead>

          <tbody>
            <tr v-for="category in filteredCategories" :key="category.id">
              <td>
                <div class="category-cell">
                  <div class="category-icon">
                    <ShieldAlert :size="18" />
                  </div>

                  <div>
                    <strong>{{ category.nama_pelanggaran }}</strong>

                    <p v-if="category.deskripsi">
                      {{ category.deskripsi }}
                    </p>
                    <p v-else class="muted-text">tidak ada deskripsi</p>
                  </div>
                </div>
              </td>

              <td>
                <span class="level-badge" :class="`level-${category.kategori}`">
                  {{ category.kategori }}
                </span>
              </td>

              <td>
                <span class="point-value">
                  {{ category.poin }}
                </span>
              </td>

              <td>
                <span
                  class="status-badge"
                  :class="category.status ? 'active' : 'inactive'"
                >
                  <span class="status-dot"></span>
                  {{ category.status ? "aktif" : "nonaktif" }}
                </span>
              </td>

              <td>
                <div class="actions">
                  <button
                    class="icon-button"
                    title="lihat"
                    @click="goToShow(category.id)"
                  >
                    <Eye :size="17" />
                  </button>

                  <button
                    class="icon-button"
                    title="edit"
                    @click="goToEdit(category.id)"
                  >
                    <Pencil :size="17" />
                  </button>

                  <button
                    class="icon-button danger"
                    title="hapus"
                    @click="deleteCategory(category)"
                  >
                    <Trash2 :size="17" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, ref } from "vue";
import { useRouter } from "vue-router";
import {
  AlertCircle,
  CircleCheck,
  CirclePause,
  Eye,
  Layers,
  LoaderCircle,
  Pencil,
  Plus,
  Search,
  ShieldAlert,
  Trash2,
} from "lucide-vue-next";

import api from "../../../utils/api";

const router = useRouter();

const categories = ref([]);
const loading = ref(false);
const error = ref("");
const search = ref("");
const statusFilter = ref("");

const activeCount = computed(
  () => categories.value.filter((category) => Boolean(category.status)).length,
);

const inactiveCount = computed(
  () => categories.value.filter((category) => !Boolean(category.status)).length,
);

const filteredCategories = computed(() => {
  const keyword = search.value.trim().toLowerCase();

  return categories.value.filter((category) => {
    const matchesSearch =
      !keyword ||
      category.nama_pelanggaran?.toLowerCase().includes(keyword) ||
      category.kategori?.toLowerCase().includes(keyword) ||
      category.deskripsi?.toLowerCase().includes(keyword);

    const matchesStatus =
      !statusFilter.value ||
      (statusFilter.value === "active" && Boolean(category.status)) ||
      (statusFilter.value === "inactive" && !Boolean(category.status));

    return matchesSearch && matchesStatus;
  });
});

const fetchCategories = async () => {
  loading.value = true;
  error.value = "";

  try {
    const response = await api.get("/staff/violation-categories");

    categories.value = Array.isArray(response.data?.data)
      ? response.data.data
      : [];
  } catch (err) {
    console.error("Gagal mengambil kategori pelanggaran:", err);

    error.value =
      err.response?.data?.message || "kategori pelanggaran gagal dimuat.";
  } finally {
    loading.value = false;
  }
};

const goToCreate = () => {
  router.push({ name: "staf.pelanggaran.kategori.create" });
};

const goToShow = (id) => {
  router.push({
    name: "staf.pelanggaran.kategori.show",
    params: { id },
  });
};

const goToEdit = (id) => {
  router.push({
    name: "staf.pelanggaran.kategori.edit",
    params: { id },
  });
};

const deleteCategory = async (category) => {
  const confirmed = window.confirm(
    `hapus kategori "${category.nama_pelanggaran}"?`,
  );

  if (!confirmed) return;

  try {
    await api.delete(`/staff/violation-categories/${category.id}`);

    categories.value = categories.value.filter(
      (item) => item.id !== category.id,
    );
  } catch (err) {
    console.error("Gagal menghapus kategori:", err);

    window.alert(
      err.response?.data?.message || "kategori pelanggaran gagal dihapus.",
    );
  }
};

onMounted(fetchCategories);
</script>

<style scoped>
.page {
  max-width: 1400px;
  margin: 0 auto;
}

.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 24px;
  margin-bottom: 28px;
}

.eyebrow {
  margin: 0 0 6px;
  color: #64748b;
  font-size: 13px;
  font-weight: 600;
  text-transform: lowercase;
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

.primary-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  min-height: 42px;
  padding: 0 16px;
  border: 0;
  border-radius: 10px;
  background: #2563eb;
  color: #fff;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s ease;
}

.primary-button:hover {
  background: #1d4ed8;
}

.alert {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
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

.stats-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 16px;
  margin-bottom: 20px;
}

.stat-card {
  display: flex;
  align-items: center;
  gap: 14px;
  padding: 18px;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  background: #fff;
}

.stat-icon {
  display: grid;
  width: 42px;
  height: 42px;
  place-items: center;
  border-radius: 10px;
  background: #eff6ff;
  color: #2563eb;
}

.stat-icon.light {
  background: #ecfdf5;
  color: #059669;
}

.stat-icon.muted {
  background: #f8fafc;
  color: #64748b;
}

.stat-label {
  display: block;
  margin-bottom: 3px;
  color: #64748b;
  font-size: 12px;
}

.stat-card strong {
  color: #172033;
  font-size: 21px;
}

.content-card {
  overflow: hidden;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  background: #fff;
}

.toolbar {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 18px;
  border-bottom: 1px solid #e5e7eb;
}

.search-box {
  display: flex;
  align-items: center;
  flex: 1;
  gap: 9px;
  height: 40px;
  padding: 0 12px;
  border: 1px solid #dbe0e6;
  border-radius: 9px;
  color: #94a3b8;
}

.search-box:focus-within {
  border-color: #93c5fd;
}

.search-box input {
  width: 100%;
  border: 0;
  outline: 0;
  color: #172033;
  font-size: 14px;
  background: transparent;
}

.filter-select {
  height: 40px;
  min-width: 145px;
  padding: 0 12px;
  border: 1px solid #dbe0e6;
  border-radius: 9px;
  outline: 0;
  color: #475569;
  background: #fff;
  font-size: 14px;
}

.state,
.empty-state {
  display: flex;
  min-height: 300px;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  padding: 40px;
  text-align: center;
}

.state {
  color: #64748b;
}

.state p {
  margin: 10px 0 0;
  font-size: 14px;
}

.spin {
  animation: spin 1s linear infinite;
}

.empty-icon {
  display: grid;
  width: 50px;
  height: 50px;
  place-items: center;
  margin-bottom: 14px;
  border-radius: 12px;
  background: #f1f5f9;
  color: #64748b;
}

.empty-state h3 {
  margin: 0;
  color: #172033;
  font-size: 16px;
}

.empty-state p {
  max-width: 420px;
  margin: 7px 0 18px;
  color: #64748b;
  font-size: 14px;
}

.table-wrapper {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th {
  padding: 13px 18px;
  border-bottom: 1px solid #e5e7eb;
  color: #64748b;
  font-size: 12px;
  font-weight: 600;
  text-align: left;
  white-space: nowrap;
}

td {
  padding: 16px 18px;
  border-bottom: 1px solid #f1f5f9;
  color: #334155;
  font-size: 14px;
}

tbody tr:last-child td {
  border-bottom: 0;
}

tbody tr:hover {
  background: #fafbfc;
}

.category-cell {
  display: flex;
  align-items: center;
  gap: 12px;
  min-width: 280px;
}

.category-icon {
  display: grid;
  width: 38px;
  height: 38px;
  flex-shrink: 0;
  place-items: center;
  border-radius: 9px;
  background: #fff7ed;
  color: #ea580c;
}

.category-cell strong {
  display: block;
  color: #172033;
  font-size: 14px;
}

.category-cell p {
  max-width: 350px;
  margin: 4px 0 0;
  overflow: hidden;
  color: #64748b;
  font-size: 12px;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.muted-text {
  color: #94a3b8 !important;
}

.level-badge,
.status-badge {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
}

.level-badge {
  padding: 5px 9px;
  text-transform: capitalize;
}

.level-ringan {
  background: #ecfdf5;
  color: #047857;
}

.level-sedang {
  background: #fffbeb;
  color: #b45309;
}

.level-berat {
  background: #fef2f2;
  color: #b91c1c;
}

.point-value {
  color: #172033;
  font-weight: 700;
}

.status-badge {
  padding: 5px 9px;
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

.action-column {
  width: 130px;
  text-align: right;
}

.actions {
  display: flex;
  justify-content: flex-end;
  gap: 5px;
}

.icon-button {
  display: grid;
  width: 34px;
  height: 34px;
  place-items: center;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  background: #fff;
  color: #64748b;
  cursor: pointer;
  transition: 0.2s ease;
}

.icon-button:hover {
  border-color: #cbd5e1;
  background: #f8fafc;
  color: #172033;
}

.icon-button.danger:hover {
  border-color: #fecaca;
  background: #fef2f2;
  color: #dc2626;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

@media (max-width: 800px) {
  .page-header {
    align-items: stretch;
    flex-direction: column;
  }

  .primary-button {
    align-self: flex-start;
  }

  .stats-grid {
    grid-template-columns: 1fr;
  }

  .toolbar {
    flex-direction: column;
    align-items: stretch;
  }

  .filter-select {
    width: 100%;
  }
}
</style>
