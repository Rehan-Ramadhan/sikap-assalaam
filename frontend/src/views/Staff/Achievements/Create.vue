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

          <h1>Catat Prestasi</h1>
          <p>Tambahkan data prestasi siswa.</p>
        </div>
      </div>

      <!-- Error -->
      <div v-if="error" class="error-box">
        <CircleAlert :size="18" />
        <span>{{ error }}</span>
      </div>

      <!-- Form -->
      <div class="form-card">
        <form @submit.prevent="handleSubmit">
          <!-- Data Siswa -->
          <div class="form-section">
            <div class="section-title">
              <UserRound :size="20" />
              <div>
                <h2>Data Siswa</h2>
                <p>Pilih siswa yang memperoleh prestasi.</p>
              </div>
            </div>

            <div class="form-grid">
              <div class="form-group full-width">
                <label for="student"> Siswa <span>*</span> </label>

                <select
                  id="student"
                  v-model="form.studentId"
                  :disabled="loadingStudents || submitting"
                >
                  <option value="">
                    {{ loadingStudents ? "Memuat siswa..." : "Pilih siswa" }}
                  </option>

                  <option
                    v-for="student in students"
                    :key="student.id"
                    :value="student.id"
                  >
                    {{ student.nama }} - {{ student.nis }}
                  </option>
                </select>

                <small v-if="errors.studentId" class="error-text">
                  {{ errors.studentId }}
                </small>
              </div>
            </div>
          </div>

          <!-- Data Prestasi -->
          <div class="form-section">
            <div class="section-title">
              <Trophy :size="20" />
              <div>
                <h2>Data Prestasi</h2>
                <p>Masukkan informasi prestasi siswa.</p>
              </div>
            </div>

            <div class="form-grid">
              <div class="form-group">
                <label for="achievement"> Nama Prestasi <span>*</span> </label>

                <select
                  id="achievement"
                  v-model="form.categoryId"
                  :disabled="loadingCategories || submitting"
                  @change="handleCategoryChange"
                >
                  <option value="">
                    {{
                      loadingCategories
                        ? "Memuat kategori..."
                        : "Pilih prestasi"
                    }}
                  </option>

                  <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                  >
                    {{ category.namaPrestasi }}
                  </option>
                </select>

                <small v-if="errors.categoryId" class="error-text">
                  {{ errors.categoryId }}
                </small>
              </div>

              <div class="form-group">
                <label for="tingkat">Tingkat</label>

                <input
                  id="tingkat"
                  :value="selectedCategory?.tingkat || '-'"
                  type="text"
                  readonly
                  class="readonly-input"
                />
              </div>

              <div class="form-group">
                <label for="poin">Poin</label>

                <div class="point-display">
                  <Plus :size="17" />
                  {{ selectedCategory?.poin || 0 }}
                </div>
              </div>

              <div class="form-group">
                <label for="tanggal"> Tanggal Prestasi <span>*</span> </label>

                <input
                  id="tanggal"
                  v-model="form.tanggalPrestasi"
                  type="date"
                  :disabled="submitting"
                />

                <small v-if="errors.tanggalPrestasi" class="error-text">
                  {{ errors.tanggalPrestasi }}
                </small>
              </div>

              <div class="form-group full-width">
                <label for="keterangan">Keterangan</label>

                <textarea
                  id="keterangan"
                  v-model="form.keterangan"
                  rows="5"
                  :disabled="submitting"
                  placeholder="Tambahkan keterangan prestasi jika diperlukan..."
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Informasi -->
          <div class="info-box">
            <CircleAlert :size="19" />

            <div>
              <strong>Informasi</strong>
              <p>
                Poin prestasi dicatat sebagai poin prestasi siswa dan tidak
                mengurangi poin pelanggaran.
              </p>
            </div>
          </div>

          <!-- Actions -->
          <div class="form-actions">
            <button
              type="button"
              class="btn btn-secondary"
              :disabled="submitting"
              @click="goBack"
            >
              Batal
            </button>

            <button
              type="submit"
              class="btn btn-primary"
              :disabled="submitting || loadingStudents || loadingCategories"
            >
              <Save v-if="!submitting" :size="18" />
              <span v-if="submitting" class="loading-spinner"></span>

              {{ submitting ? "Menyimpan..." : "Simpan Prestasi" }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from "vue";
import { useRouter } from "vue-router";

import AppLayout from "../../../layouts/AppLayout.vue";
import api from "../../../utils/api";

import {
  ArrowLeft,
  CircleAlert,
  Plus,
  Save,
  Trophy,
  UserRound,
} from "lucide-vue-next";

const router = useRouter();

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const students = ref([]);
const categories = ref([]);

const loadingStudents = ref(false);
const loadingCategories = ref(false);
const submitting = ref(false);

const error = ref("");

/*
|--------------------------------------------------------------------------
| Form
|--------------------------------------------------------------------------
*/

const form = reactive({
  studentId: "",
  categoryId: "",
  tanggalPrestasi: "",
  keterangan: "",
});

const errors = reactive({
  studentId: "",
  categoryId: "",
  tanggalPrestasi: "",
});

/*
|--------------------------------------------------------------------------
| Computed
|--------------------------------------------------------------------------
*/

const selectedCategory = computed(() => {
  return (
    categories.value.find(
      (category) => category.id === Number(form.categoryId),
    ) || null
  );
});

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

const getErrorMessage = (requestError, fallback) => {
  return (
    requestError?.response?.data?.message ||
    requestError?.response?.data?.errors?.student_id?.[0] ||
    requestError?.response?.data?.errors?.category_id?.[0] ||
    requestError?.response?.data?.errors?.tanggal_prestasi?.[0] ||
    fallback
  );
};

const unwrapData = (payload) => {
  const data = payload?.data;

  if (Array.isArray(data)) {
    return data;
  }

  if (Array.isArray(data?.data)) {
    return data.data;
  }

  return [];
};

const normalizeStudent = (student) => {
  return {
    id: student.id,
    nama: student.user?.name || student.nama || student.name || "-",
    nis: student.nis || "-",
    tingkat: student.tingkat || "",
    jurusan: student.jurusan || "",
    nomorKelas: student.nomor_kelas || "",
  };
};

const normalizeCategory = (category) => {
  return {
    id: category.id,
    namaPrestasi:
      category.nama_prestasi || category.namaPrestasi || category.name || "-",
    poin: Number(category.poin || 0),
    tingkat: category.tingkat || "-",
  };
};

/*
|--------------------------------------------------------------------------
| Load Students
|--------------------------------------------------------------------------
*/

const loadStudents = async () => {
  loadingStudents.value = true;

  try {
    const response = await api.get("/staff/students", {
      params: {
        status: "aktif",
        per_page: 100,
      },
    });

    students.value = unwrapData(response.data)
      .map(normalizeStudent)
      .sort((a, b) => a.nama.localeCompare(b.nama));
  } catch (requestError) {
    error.value = getErrorMessage(requestError, "Data siswa gagal dimuat.");
  } finally {
    loadingStudents.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| Load Achievement Categories
|--------------------------------------------------------------------------
*/

const loadCategories = async () => {
  loadingCategories.value = true;

  try {
    const response = await api.get("/staff/achievement-categories", {
      params: {
        status: 1,
        per_page: 100,
      },
    });

    categories.value = unwrapData(response.data)
      .map(normalizeCategory)
      .sort((a, b) => a.namaPrestasi.localeCompare(b.namaPrestasi));
  } catch (requestError) {
    error.value = getErrorMessage(
      requestError,
      "Kategori prestasi gagal dimuat.",
    );
  } finally {
    loadingCategories.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| Category
|--------------------------------------------------------------------------
*/

const handleCategoryChange = () => {
  errors.categoryId = "";
};

/*
|--------------------------------------------------------------------------
| Validation
|--------------------------------------------------------------------------
*/

const validateForm = () => {
  errors.studentId = "";
  errors.categoryId = "";
  errors.tanggalPrestasi = "";

  let valid = true;

  if (!form.studentId) {
    errors.studentId = "Siswa wajib dipilih.";
    valid = false;
  }

  if (!form.categoryId) {
    errors.categoryId = "Prestasi wajib dipilih.";
    valid = false;
  }

  if (!form.tanggalPrestasi) {
    errors.tanggalPrestasi = "Tanggal prestasi wajib diisi.";
    valid = false;
  }

  return valid;
};

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

const handleSubmit = async () => {
  if (!validateForm()) {
    return;
  }

  error.value = "";
  submitting.value = true;

  try {
    await api.post("/staff/achievements", {
      student_id: Number(form.studentId),
      category_id: Number(form.categoryId),
      tanggal_prestasi: form.tanggalPrestasi,
      keterangan: form.keterangan.trim() || null,
    });

    alert("Data prestasi berhasil disimpan.");

    router.push("/staff/prestasi");
  } catch (requestError) {
    const validationErrors = requestError?.response?.data?.errors;

    if (validationErrors?.student_id?.[0]) {
      errors.studentId = validationErrors.student_id[0];
    }

    if (validationErrors?.category_id?.[0]) {
      errors.categoryId = validationErrors.category_id[0];
    }

    if (validationErrors?.tanggal_prestasi?.[0]) {
      errors.tanggalPrestasi = validationErrors.tanggal_prestasi[0];
    }

    error.value = getErrorMessage(
      requestError,
      "Data prestasi gagal disimpan.",
    );
  } finally {
    submitting.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

const goBack = () => {
  router.push("/staff/prestasi");
};

/*
|--------------------------------------------------------------------------
| Lifecycle
|--------------------------------------------------------------------------
*/

onMounted(async () => {
  await Promise.all([loadStudents(), loadCategories()]);
});
</script>

<style scoped>
.page-container {
  max-width: 1100px;
  margin: 0 auto;
  width: 100%;
}

.page-header {
  margin-bottom: 24px;
}

.page-header h1 {
  margin: 14px 0 6px;
  font-size: 28px;
  font-weight: 700;
  color: #172033;
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
  border: none;
  background: transparent;
  color: #2563eb;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  padding: 0;
}

.back-button:hover {
  color: #1d4ed8;
}

.back-button:disabled {
  cursor: not-allowed;
  opacity: 0.6;
}

.error-box {
  margin-bottom: 20px;
  padding: 13px 16px;
  display: flex;
  align-items: center;
  gap: 10px;
  border: 1px solid #fecaca;
  border-radius: 10px;
  background: #fef2f2;
  color: #dc2626;
  font-size: 13px;
  line-height: 1.5;
}

.form-card {
  background: #ffffff;
  border: 1px solid #e8edf5;
  border-radius: 14px;
  overflow: hidden;
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
  width: 100%;
  box-sizing: border-box;
  border: 1px solid #d9e0ea;
  border-radius: 9px;
  background: #fff;
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
  padding: 12px 13px;
  resize: vertical;
  min-height: 110px;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.form-group input:disabled,
.form-group select:disabled,
.form-group textarea:disabled {
  background: #f8fafc;
  color: #94a3b8;
  cursor: not-allowed;
}

.readonly-input {
  background: #f7f9fc !important;
  color: #64748b !important;
  cursor: not-allowed;
}

.point-display {
  height: 44px;
  box-sizing: border-box;
  display: inline-flex;
  align-items: center;
  gap: 6px;
  padding: 0 13px;
  border-radius: 9px;
  background: #eff6ff;
  color: #2563eb;
  font-size: 14px;
  font-weight: 700;
}

.error-text {
  color: #dc2626;
  font-size: 12px;
}

.info-box {
  margin: 24px 28px 0;
  padding: 14px 16px;
  display: flex;
  gap: 12px;
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

.form-actions {
  padding: 24px 28px;
  display: flex;
  justify-content: flex-end;
  gap: 12px;
  border-top: 1px solid #edf1f7;
  margin-top: 24px;
}

.btn {
  min-height: 42px;
  padding: 0 18px;
  border-radius: 9px;
  border: none;
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
  cursor: not-allowed;
  opacity: 0.6;
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
  color: #fff;
}

.btn-primary:hover:not(:disabled) {
  background: #1d4ed8;
}

.loading-spinner {
  width: 16px;
  height: 16px;
  border: 2px solid rgba(255, 255, 255, 0.4);
  border-top-color: #ffffff;
  border-radius: 50%;
  animation: spin 0.7s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

@media (max-width: 768px) {
  .page-container {
    width: 100%;
  }

  .form-section {
    padding: 20px;
  }

  .form-grid {
    grid-template-columns: 1fr;
  }

  .full-width {
    grid-column: auto;
  }

  .info-box {
    margin-left: 20px;
    margin-right: 20px;
  }

  .form-actions {
    padding: 20px;
    flex-direction: column-reverse;
  }

  .btn {
    width: 100%;
  }
}
</style>
