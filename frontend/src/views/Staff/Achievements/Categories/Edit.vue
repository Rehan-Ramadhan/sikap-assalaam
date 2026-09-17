<template>
  <AppLayout>
    <div class="page-header">
      <div class="header-left">
        <button class="btn-back" @click="goBack">
          <ArrowLeft :size="18" />
          <span>Kembali</span>
        </button>

        <div>
          <h1>Edit Kategori Prestasi</h1>
          <p>Perbarui informasi kategori prestasi.</p>
        </div>
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
      <div class="empty-icon">📂</div>

      <h3>Kategori tidak ditemukan</h3>

      <p>
        {{
          errorMessage ||
          "Data kategori prestasi yang ingin diedit tidak tersedia."
        }}
      </p>

      <button class="btn btn-primary" @click="goBack">
        Kembali ke Kategori
      </button>
    </div>

    <template v-else>
      <div class="form-layout">
        <!-- Form -->
        <div class="card form-card">
          <div class="card-title">
            <h3>Informasi Kategori</h3>
            <p>Ubah data kategori prestasi sesuai kebutuhan.</p>
          </div>

          <div v-if="errorMessage" class="alert-error">
            <CircleAlert :size="18" />
            <span>{{ errorMessage }}</span>
          </div>

          <form @submit.prevent="handleSubmit">
            <!-- Nama Prestasi -->
            <div class="form-group">
              <label for="namaPrestasi"> Nama Prestasi <span>*</span> </label>

              <input
                id="namaPrestasi"
                v-model="form.namaPrestasi"
                type="text"
                maxlength="100"
                placeholder="Contoh: Juara 1 Lomba Coding"
              />

              <small v-if="errors.namaPrestasi">
                {{ errors.namaPrestasi }}
              </small>
            </div>

            <!-- Poin -->
            <div class="form-group">
              <label for="poin"> Poin <span>*</span> </label>

              <input
                id="poin"
                v-model.number="form.poin"
                type="number"
                min="1"
                placeholder="Masukkan jumlah poin"
              />

              <small v-if="errors.poin">
                {{ errors.poin }}
              </small>
            </div>

            <!-- Tingkat -->
            <div class="form-group">
              <label for="tingkat"> Tingkat <span>*</span> </label>

              <select id="tingkat" v-model="form.tingkat">
                <option value="" disabled>Pilih tingkat prestasi</option>

                <option value="sekolah">Sekolah</option>

                <option value="kecamatan">Kecamatan</option>

                <option value="kabupaten">Kabupaten</option>

                <option value="provinsi">Provinsi</option>

                <option value="nasional">Nasional</option>

                <option value="internasional">Internasional</option>
              </select>

              <small v-if="errors.tingkat">
                {{ errors.tingkat }}
              </small>
            </div>

            <!-- Deskripsi -->
            <div class="form-group">
              <label for="deskripsi"> Deskripsi </label>

              <textarea
                id="deskripsi"
                v-model="form.deskripsi"
                rows="5"
                placeholder="Masukkan deskripsi kategori prestasi..."
              ></textarea>
            </div>

            <!-- Status -->
            <div class="form-group">
              <label> Status </label>

              <label class="switch-wrapper">
                <input v-model="form.status" type="checkbox" />

                <span class="switch"></span>

                <span class="switch-label">
                  {{ form.status ? "Aktif" : "Nonaktif" }}
                </span>
              </label>
            </div>

            <!-- Actions -->
            <div class="form-actions">
              <button
                type="button"
                class="btn btn-secondary"
                :disabled="saving"
                @click="goBack"
              >
                Batal
              </button>

              <button type="submit" class="btn btn-primary" :disabled="saving">
                {{ saving ? "Menyimpan..." : "Simpan Perubahan" }}
              </button>
            </div>
          </form>
        </div>

        <!-- Preview -->
        <div class="card preview-card">
          <div class="card-title">
            <h3>Preview</h3>
            <p>Tampilan informasi kategori.</p>
          </div>

          <div class="preview-content">
            <div class="preview-icon">
              <Trophy :size="34" :stroke-width="1.8" />
            </div>

            <h2>
              {{ form.namaPrestasi || "Nama Prestasi" }}
            </h2>

            <span
              class="status-badge"
              :class="form.status ? 'status-active' : 'status-inactive'"
            >
              {{ form.status ? "Aktif" : "Nonaktif" }}
            </span>

            <div class="preview-info">
              <div class="preview-item">
                <span>Tingkat</span>

                <strong>
                  {{ formatTingkat(form.tingkat) }}
                </strong>
              </div>

              <div class="preview-item">
                <span>Poin</span>

                <strong class="point-value"> +{{ form.poin || 0 }} </strong>
              </div>
            </div>

            <div class="preview-description">
              <span>Deskripsi</span>

              <p>
                {{ form.deskripsi || "Belum ada deskripsi kategori." }}
              </p>
            </div>
          </div>
        </div>
      </div>
    </template>
  </AppLayout>
</template>

<script setup>
import { reactive, ref, onMounted } from "vue";
import { ArrowLeft, CircleAlert, Trophy } from "lucide-vue-next";
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
const saving = ref(false);
const errorMessage = ref("");

const form = reactive({
  namaPrestasi: "",
  poin: "",
  tingkat: "",
  deskripsi: "",
  status: true,
});

const errors = reactive({
  namaPrestasi: "",
  poin: "",
  tingkat: "",
});

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

    category.value = data;

    form.namaPrestasi = data.nama_prestasi ?? "";
    form.poin = data.poin ?? "";
    form.tingkat = data.tingkat ?? "";
    form.deskripsi = data.deskripsi ?? "";
    form.status = Boolean(data.status);
  } catch (error) {
    console.error("Gagal mengambil kategori prestasi:", error);

    category.value = null;

    errorMessage.value =
      error.response?.data?.message || "Gagal memuat kategori prestasi.";
  } finally {
    loading.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

const clearErrors = () => {
  errors.namaPrestasi = "";
  errors.poin = "";
  errors.tingkat = "";
  errorMessage.value = "";
};

const validateForm = () => {
  clearErrors();

  let valid = true;

  if (!form.namaPrestasi.trim()) {
    errors.namaPrestasi = "Nama prestasi wajib diisi.";
    valid = false;
  }

  if (!form.poin || Number(form.poin) < 1) {
    errors.poin = "Poin minimal 1.";
    valid = false;
  }

  if (!form.tingkat) {
    errors.tingkat = "Tingkat prestasi wajib dipilih.";
    valid = false;
  }

  return valid;
};

const formatTingkat = (tingkat) => {
  const labels = {
    sekolah: "Sekolah",
    kecamatan: "Kecamatan",
    kabupaten: "Kabupaten",
    provinsi: "Provinsi",
    nasional: "Nasional",
    internasional: "Internasional",
  };

  return labels[tingkat] || "Belum dipilih";
};

/*
|--------------------------------------------------------------------------
| Update
|--------------------------------------------------------------------------
*/

const handleSubmit = async () => {
  if (!validateForm()) return;

  saving.value = true;

  try {
    const payload = {
      nama_prestasi: form.namaPrestasi.trim(),
      poin: Number(form.poin),
      tingkat: form.tingkat,
      deskripsi: form.deskripsi.trim() || null,
      status: form.status,
    };

    await api.put(
      `/staff/achievement-categories/${category.value.id}`,
      payload,
    );

    alert("Kategori prestasi berhasil diperbarui.");

    router.push(`/staf/prestasi/kategori/${category.value.id}`);
  } catch (error) {
    console.error("Gagal memperbarui kategori prestasi:", error);

    if (error.response?.status === 422) {
      const validationErrors = error.response.data?.errors || {};

      errors.namaPrestasi = validationErrors.nama_prestasi?.[0] || "";

      errors.poin = validationErrors.poin?.[0] || "";

      errors.tingkat = validationErrors.tingkat?.[0] || "";

      errorMessage.value =
        error.response.data?.message || "Periksa kembali data yang dimasukkan.";
    } else {
      errorMessage.value =
        error.response?.data?.message || "Gagal memperbarui kategori prestasi.";
    }
  } finally {
    saving.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

const goBack = () => {
  router.push("/staf/prestasi/kategori");
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
  align-items: flex-start;
  gap: 16px;
  margin-bottom: 24px;
}

.header-left {
  display: flex;
  align-items: flex-start;
  gap: 16px;
}

.page-header h1 {
  margin: 0 0 6px;
  color: #172033;
  font-size: 28px;
  font-weight: 700;
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

.form-layout {
  display: grid;
  grid-template-columns: minmax(0, 1fr) 360px;
  gap: 20px;
  align-items: start;
}

.card {
  background: #fff;
  border: 1px solid #eef2f7;
  border-radius: 14px;
  box-shadow: 0 2px 10px rgba(15, 23, 42, 0.06);
}

.form-card {
  padding: 24px;
}

.preview-card {
  padding: 24px;
  position: sticky;
  top: 20px;
}

.card-title {
  padding-bottom: 18px;
  margin-bottom: 22px;
  border-bottom: 1px solid #eef2f7;
}

.card-title h3 {
  margin: 0 0 5px;
  color: #172033;
  font-size: 18px;
}

.card-title p {
  margin: 0;
  color: #6b7280;
  font-size: 13px;
}

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

.form-group {
  margin-bottom: 20px;
}

.form-group > label {
  display: block;
  margin-bottom: 8px;
  color: #374151;
  font-size: 14px;
  font-weight: 600;
}

.form-group > label span {
  color: #dc2626;
}

.form-group input[type="text"],
.form-group input[type="number"],
.form-group select,
.form-group textarea {
  width: 100%;
  box-sizing: border-box;
  border: 1px solid #dbe2ea;
  border-radius: 8px;
  background: #fff;
  color: #172033;
  padding: 11px 13px;
  font-size: 14px;
  outline: none;
  transition: 0.2s;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-group textarea {
  resize: vertical;
  line-height: 1.6;
}

.form-group small {
  display: block;
  margin-top: 6px;
  color: #dc2626;
  font-size: 12px;
}

.switch-wrapper {
  display: inline-flex !important;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  margin: 0 !important;
}

.switch-wrapper input {
  display: none;
}

.switch {
  width: 44px;
  height: 24px;
  border-radius: 999px;
  background: #cbd5e1;
  position: relative;
  transition: 0.2s;
}

.switch::after {
  content: "";
  position: absolute;
  top: 3px;
  left: 3px;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: #fff;
  transition: 0.2s;
}

.switch-wrapper input:checked + .switch {
  background: #2563eb;
}

.switch-wrapper input:checked + .switch::after {
  transform: translateX(20px);
}

.switch-label {
  color: #374151;
  font-size: 14px;
  font-weight: 600;
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding-top: 8px;
  margin-top: 10px;
  border-top: 1px solid #eef2f7;
}

.btn {
  border: none;
  border-radius: 8px;
  padding: 11px 16px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-primary {
  background: #2563eb;
  color: #fff;
}

.btn-primary:hover:not(:disabled) {
  background: #1d4ed8;
}

.btn-secondary {
  background: #f1f5f9;
  color: #475569;
}

.btn-secondary:hover:not(:disabled) {
  background: #e2e8f0;
}

.preview-content {
  text-align: center;
}

.preview-icon {
  width: 72px;
  height: 72px;
  margin: 0 auto 16px;
  border-radius: 50%;
  background: #eff6ff;
  color: #2563eb;
  display: flex;
  align-items: center;
  justify-content: center;
}

.preview-content h2 {
  margin: 0 auto 12px;
  max-width: 280px;
  color: #172033;
  font-size: 19px;
  line-height: 1.4;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 6px 10px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 600;
}

.status-active {
  background: #dcfce7;
  color: #15803d;
}

.status-inactive {
  background: #f3f4f6;
  color: #6b7280;
}

.preview-info {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 10px;
  margin-top: 24px;
}

.preview-item {
  padding: 14px 10px;
  border-radius: 10px;
  background: #f8fafc;
}

.preview-item span,
.preview-description > span {
  display: block;
  margin-bottom: 6px;
  color: #64748b;
  font-size: 12px;
}

.preview-item strong {
  color: #2563eb;
  font-size: 14px;
}

.preview-item .point-value {
  color: #15803d;
}

.preview-description {
  margin-top: 14px;
  padding: 15px;
  border-radius: 10px;
  background: #f8fafc;
  text-align: left;
}

.preview-description p {
  margin: 0;
  color: #475569;
  font-size: 13px;
  line-height: 1.6;
}

.empty-state {
  padding: 60px 20px;
  background: #fff;
  border: 1px solid #eef2f7;
  border-radius: 14px;
  text-align: center;
}

.empty-icon {
  margin-bottom: 12px;
  font-size: 42px;
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

@media (max-width: 900px) {
  .form-layout {
    grid-template-columns: 1fr;
  }

  .preview-card {
    position: static;
  }
}

@media (max-width: 640px) {
  .header-left {
    gap: 10px;
  }

  .page-header h1 {
    font-size: 22px;
  }

  .form-card,
  .preview-card {
    padding: 18px;
  }

  .form-actions {
    flex-direction: column-reverse;
  }

  .form-actions .btn {
    width: 100%;
  }
}
</style>
