<template>
  <AppLayout>
    <div class="page-container">
      <!-- Header -->
      <div class="page-header">
        <div>
          <button class="back-button" @click="goBack">
            <ArrowLeft :size="18" />
            Kembali
          </button>

          <h1>Tambah Kategori Prestasi</h1>
          <p>Tambahkan kategori prestasi baru beserta poinnya.</p>
        </div>
      </div>

      <!-- Error -->
      <div v-if="errorMessage" class="alert-error">
        <CircleAlert :size="18" />
        <span>{{ errorMessage }}</span>
      </div>

      <!-- Form -->
      <div class="form-card">
        <form @submit.prevent="handleSubmit">
          <div class="form-section">
            <div class="section-title">
              <Trophy :size="20" />

              <div>
                <h2>Informasi Kategori</h2>
                <p>
                  Isi informasi kategori prestasi yang akan digunakan saat
                  mencatat prestasi siswa.
                </p>
              </div>
            </div>

            <div class="form-grid">
              <!-- Nama Prestasi -->
              <div class="form-group full-width">
                <label for="namaPrestasi"> Nama Prestasi <span>*</span> </label>

                <input
                  id="namaPrestasi"
                  v-model="form.namaPrestasi"
                  type="text"
                  maxlength="100"
                  placeholder="Contoh: Juara 1 Olimpiade Matematika"
                />

                <small v-if="errors.namaPrestasi" class="error-text">
                  {{ errors.namaPrestasi }}
                </small>
              </div>

              <!-- Poin -->
              <div class="form-group">
                <label for="poin"> Poin <span>*</span> </label>

                <div class="point-input">
                  <Plus :size="17" />

                  <input
                    id="poin"
                    v-model.number="form.poin"
                    type="number"
                    min="1"
                    placeholder="Contoh: 100"
                  />
                </div>

                <small class="helper-text"> Poin minimal adalah 1. </small>

                <small v-if="errors.poin" class="error-text">
                  {{ errors.poin }}
                </small>
              </div>

              <!-- Tingkat -->
              <div class="form-group">
                <label for="tingkat"> Tingkat <span>*</span> </label>

                <select id="tingkat" v-model="form.tingkat">
                  <option value="">Pilih tingkat</option>
                  <option value="sekolah">Sekolah</option>
                  <option value="kecamatan">Kecamatan</option>
                  <option value="kabupaten">Kabupaten</option>
                  <option value="provinsi">Provinsi</option>
                  <option value="nasional">Nasional</option>
                  <option value="internasional">Internasional</option>
                </select>

                <small v-if="errors.tingkat" class="error-text">
                  {{ errors.tingkat }}
                </small>
              </div>

              <!-- Deskripsi -->
              <div class="form-group full-width">
                <label for="deskripsi">Deskripsi</label>

                <textarea
                  id="deskripsi"
                  v-model="form.deskripsi"
                  rows="5"
                  placeholder="Tambahkan deskripsi kategori prestasi..."
                ></textarea>

                <small class="helper-text">
                  Deskripsi bersifat opsional.
                </small>
              </div>

              <!-- Status -->
              <div class="form-group">
                <label for="status">Status</label>

                <select id="status" v-model="form.status">
                  <option :value="true">Aktif</option>
                  <option :value="false">Nonaktif</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Preview -->
          <div class="preview-section">
            <div class="preview-title">
              <Eye :size="18" />
              <span>Preview</span>
            </div>

            <div class="preview-card">
              <div class="preview-icon">
                <Trophy :size="20" />
              </div>

              <div class="preview-content">
                <strong>
                  {{ form.namaPrestasi || "Nama Prestasi" }}
                </strong>

                <div class="preview-meta">
                  <span
                    class="level-badge"
                    :class="
                      form.tingkat ? `level-${form.tingkat}` : 'level-default'
                    "
                  >
                    {{ formatTingkat(form.tingkat) }}
                  </span>

                  <span class="point-badge"> +{{ form.poin || 0 }} Poin </span>

                  <span
                    class="status-badge"
                    :class="form.status ? 'active' : 'inactive'"
                  >
                    <span class="status-dot"></span>
                    {{ form.status ? "Aktif" : "Nonaktif" }}
                  </span>
                </div>
              </div>
            </div>
          </div>

          <!-- Info -->
          <div class="info-box">
            <CircleAlert :size="19" />

            <div>
              <strong>Informasi</strong>

              <p>
                Kategori prestasi yang berstatus aktif dapat digunakan saat
                mencatat prestasi siswa.
              </p>
            </div>
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
              <Save :size="18" />
              {{ saving ? "Menyimpan..." : "Simpan Kategori" }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";

import AppLayout from "../../../../layouts/AppLayout.vue";
import api from "../../../../utils/api";

import {
  ArrowLeft,
  CircleAlert,
  Eye,
  Plus,
  Save,
  Trophy,
} from "lucide-vue-next";

const router = useRouter();

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/

const form = reactive({
  namaPrestasi: "",
  poin: "",
  tingkat: "",
  deskripsi: "",
  status: true,
});

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const saving = ref(false);
const errorMessage = ref("");

const errors = reactive({
  namaPrestasi: "",
  poin: "",
  tingkat: "",
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

  return names[tingkat] || "Pilih Tingkat";
};

const clearErrors = () => {
  errors.namaPrestasi = "";
  errors.poin = "";
  errors.tingkat = "";
  errorMessage.value = "";
};

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

const validateForm = () => {
  clearErrors();

  let valid = true;

  if (!form.namaPrestasi.trim()) {
    errors.namaPrestasi = "Nama prestasi wajib diisi.";
    valid = false;
  }

  if (!form.poin || Number(form.poin) < 1) {
    errors.poin = "Poin wajib diisi minimal 1.";
    valid = false;
  }

  if (!form.tingkat) {
    errors.tingkat = "Tingkat prestasi wajib dipilih.";
    valid = false;
  }

  return valid;
};

/*
|--------------------------------------------------------------------------
| API
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

    const response = await api.post("/staff/achievement-categories", payload);

    const createdCategory = response.data?.data ?? response.data;

    alert("Kategori prestasi berhasil disimpan.");

    if (createdCategory?.id) {
      router.push(`/staf/prestasi/kategori/${createdCategory.id}`);
    } else {
      router.push("/staf/prestasi/kategori");
    }
  } catch (error) {
    console.error("Gagal menyimpan kategori prestasi:", error);

    if (error.response?.status === 422) {
      const validationErrors = error.response.data?.errors || {};

      errors.namaPrestasi = validationErrors.nama_prestasi?.[0] || "";

      errors.poin = validationErrors.poin?.[0] || "";

      errors.tingkat = validationErrors.tingkat?.[0] || "";

      errorMessage.value =
        error.response.data?.message || "Periksa kembali data yang dimasukkan.";
    } else {
      errorMessage.value =
        error.response?.data?.message || "Gagal menyimpan kategori prestasi.";
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
</script>

<style scoped>
.page-container {
  width: 100%;
  max-width: 1100px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 24px;
}

.page-header h1 {
  margin: 14px 0 6px;
  color: #172033;
  font-size: 28px;
  font-weight: 700;
}

.page-header p {
  margin: 0;
  color: #718096;
  font-size: 14px;
}

.back-button {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 0;
  border: none;
  background: transparent;
  color: #2563eb;
  font-family: inherit;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.back-button:hover {
  color: #1d4ed8;
}

.form-card {
  overflow: hidden;
  background: #ffffff;
  border: 1px solid #e8edf5;
  border-radius: 14px;
}

.form-section {
  padding: 28px;
  border-bottom: 1px solid #edf1f7;
}

.section-title {
  display: flex;
  align-items: flex-start;
  gap: 12px;
  margin-bottom: 24px;
  color: #2563eb;
}

.section-title h2 {
  margin: 0 0 4px;
  color: #172033;
  font-size: 17px;
  font-weight: 700;
}

.section-title p {
  margin: 0;
  color: #718096;
  font-size: 13px;
  line-height: 1.5;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 20px;
}

.full-width {
  grid-column: 1 / -1;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group label {
  color: #374151;
  font-size: 14px;
  font-weight: 600;
}

.form-group label span {
  color: #dc2626;
}

.form-group input,
.form-group select,
.form-group textarea {
  box-sizing: border-box;
  width: 100%;
  border: 1px solid #d9e0ea;
  border-radius: 9px;
  background: #ffffff;
  color: #172033;
  font-family: inherit;
  font-size: 14px;
  outline: none;
  transition:
    border-color 0.2s,
    box-shadow 0.2s;
}

.form-group input,
.form-group select {
  height: 44px;
  padding: 0 13px;
}

.form-group textarea {
  min-height: 110px;
  padding: 12px 13px;
  resize: vertical;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.point-input {
  height: 44px;
  box-sizing: border-box;
  display: flex;
  align-items: center;
  gap: 7px;
  padding-left: 13px;
  border: 1px solid #d9e0ea;
  border-radius: 9px;
  color: #2563eb;
}

.point-input:focus-within {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.point-input input {
  height: 42px;
  border: none;
  padding: 0 10px 0 0;
  box-shadow: none !important;
}

.helper-text {
  color: #94a3b8;
  font-size: 12px;
}

.error-text {
  color: #dc2626;
  font-size: 12px;
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

/* Preview */

.preview-section {
  padding: 24px 28px 0;
}

.preview-title {
  display: flex;
  align-items: center;
  gap: 8px;
  margin-bottom: 12px;
  color: #475569;
  font-size: 14px;
  font-weight: 700;
}

.preview-card {
  display: flex;
  align-items: center;
  gap: 13px;
  padding: 16px;
  border: 1px solid #e8edf5;
  border-radius: 10px;
  background: #f8fafc;
}

.preview-icon {
  width: 42px;
  height: 42px;
  flex-shrink: 0;
  border-radius: 9px;
  background: #eff6ff;
  color: #2563eb;
  display: flex;
  align-items: center;
  justify-content: center;
}

.preview-content {
  min-width: 0;
}

.preview-content strong {
  display: block;
  margin-bottom: 8px;
  color: #172033;
  font-size: 14px;
}

.preview-meta {
  display: flex;
  flex-wrap: wrap;
  gap: 7px;
}

/* Badges */

.level-badge,
.point-badge,
.status-badge {
  display: inline-flex;
  align-items: center;
  white-space: nowrap;
  border-radius: 6px;
  font-size: 11px;
  font-weight: 600;
}

.level-badge {
  padding: 5px 9px;
}

.level-default {
  background: #f1f5f9;
  color: #64748b;
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

.point-badge {
  padding: 5px 9px;
  background: #eff6ff;
  color: #2563eb;
}

.status-badge {
  gap: 6px;
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

/* Info */

.info-box {
  display: flex;
  gap: 12px;
  margin: 24px 28px 0;
  padding: 14px 16px;
  border: 1px solid #bfdbfe;
  border-radius: 10px;
  background: #eff6ff;
  color: #2563eb;
}

.info-box strong {
  display: block;
  margin-bottom: 4px;
  font-size: 14px;
}

.info-box p {
  margin: 0;
  color: #475569;
  font-size: 13px;
  line-height: 1.6;
}

/* Actions */

.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  margin-top: 24px;
  padding: 24px 28px;
  border-top: 1px solid #edf1f7;
}

.btn {
  min-height: 42px;
  padding: 0 18px;
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

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-secondary {
  background: #f1f5f9;
  color: #475569;
}

.btn-secondary:hover:not(:disabled) {
  background: #e2e8f0;
}

.btn-primary {
  background: #2563eb;
  color: #ffffff;
}

.btn-primary:hover:not(:disabled) {
  background: #1d4ed8;
}

/* Responsive */

@media (max-width: 768px) {
  .form-section {
    padding: 20px;
  }

  .form-grid {
    grid-template-columns: 1fr;
  }

  .full-width {
    grid-column: auto;
  }

  .preview-section {
    padding: 20px 20px 0;
  }

  .info-box {
    margin-left: 20px;
    margin-right: 20px;
  }

  .form-actions {
    flex-direction: column-reverse;
    padding: 20px;
  }

  .btn {
    width: 100%;
  }
}
</style>
