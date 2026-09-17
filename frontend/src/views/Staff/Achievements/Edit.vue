<template>
  <AppLayout>
    <div class="page-container">
      <div class="page-header">
        <div>
          <button class="back-button" @click="goBack">
            <ArrowLeft :size="18" />
            Kembali
          </button>

          <h1>Edit Prestasi</h1>
          <p>Perbarui data prestasi siswa.</p>
        </div>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="state-card">
        <div class="spinner"></div>
        <p>Memuat data prestasi...</p>
      </div>

      <!-- Not Found -->
      <div v-else-if="!achievement" class="state-card">
        <div class="empty-icon">
          <CircleAlert :size="28" />
        </div>

        <h3>Data prestasi tidak ditemukan</h3>
        <p>Data yang ingin kamu edit tidak tersedia.</p>

        <button class="btn btn-primary" @click="goBack">
          Kembali ke Data Prestasi
        </button>
      </div>

      <!-- Form -->
      <div v-else class="form-card">
        <form @submit.prevent="handleSubmit">
          <!-- Data Siswa -->
          <div class="form-section">
            <div class="section-title">
              <UserRound :size="20" />

              <div>
                <h2>Data Siswa</h2>
                <p>Siswa yang memiliki prestasi.</p>
              </div>
            </div>

            <div class="form-grid">
              <div class="form-group full-width">
                <label for="student"> Siswa <span>*</span> </label>

                <select id="student" v-model="form.studentId" disabled>
                  <option value="">Pilih siswa</option>

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
                <p>Perbarui informasi prestasi siswa.</p>
              </div>
            </div>

            <div class="form-grid">
              <div class="form-group">
                <label for="achievement"> Nama Prestasi <span>*</span> </label>

                <select
                  id="achievement"
                  v-model="form.categoryId"
                  @change="handleCategoryChange"
                >
                  <option value="">Pilih prestasi</option>

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
                <label>Tingkat</label>

                <input
                  :value="selectedCategory?.tingkat || '-'"
                  type="text"
                  readonly
                  class="readonly-input"
                />
              </div>

              <div class="form-group">
                <label>Poin</label>

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
                  placeholder="Tambahkan keterangan prestasi..."
                ></textarea>
              </div>
            </div>
          </div>

          <!-- Informasi -->
          <div class="info-box">
            <CircleAlert :size="19" />

            <div>
              <strong>Perhatian</strong>

              <p>
                Jika kategori prestasi diubah, poin yang tercatat juga akan
                mengikuti poin dari kategori tersebut.
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
              :disabled="submitting"
            >
              <Save :size="18" />
              {{ submitting ? "Menyimpan..." : "Simpan Perubahan" }}
            </button>
          </div>
        </form>
      </div>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from "vue";
import { useRoute, useRouter } from "vue-router";

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

const route = useRoute();
const router = useRouter();

/*
|--------------------------------------------------------------------------
| State
|--------------------------------------------------------------------------
*/

const loading = ref(true);
const submitting = ref(false);
const achievement = ref(null);

const students = ref([]);
const categories = ref([]);

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

const getErrorMessage = (error) => {
  return (
    error?.response?.data?.message || "Terjadi kesalahan. Silakan coba lagi."
  );
};

const normalizeStudent = (student) => {
  return {
    id: Number(student.id),
    nama:
      student.user?.name ||
      student.user?.nama ||
      student.nama ||
      student.name ||
      "-",
    nis: student.nis || "-",
    tingkat: student.tingkat || "-",
    jurusan: student.jurusan || "-",
    nomorKelas: student.nomor_kelas ?? student.nomorKelas ?? null,
  };
};

const normalizeCategory = (category) => {
  return {
    id: Number(category.id),
    namaPrestasi:
      category.nama_prestasi ||
      category.namaPrestasi ||
      category.nama ||
      category.name ||
      "-",
    poin: Number(category.poin ?? 0),
    tingkat: category.tingkat || "-",
    status: category.status,
  };
};

/*
|--------------------------------------------------------------------------
| Load Students
|--------------------------------------------------------------------------
*/

const loadStudents = async () => {
  const response = await api.get("/staff/students", {
    params: {
      status: "aktif",
      per_page: 100,
    },
  });

  students.value = unwrapData(response.data).map(normalizeStudent);
};

/*
|--------------------------------------------------------------------------
| Load Categories
|--------------------------------------------------------------------------
*/

const loadCategories = async () => {
  const response = await api.get("/staff/achievement-categories", {
    params: {
      per_page: 100,
    },
  });

  categories.value = unwrapData(response.data)
    .map(normalizeCategory)
    .filter((category) => {
      return (
        category.status === undefined ||
        category.status === null ||
        category.status === true ||
        category.status === 1 ||
        category.status === "aktif"
      );
    });
};

/*
|--------------------------------------------------------------------------
| Load Achievement
|--------------------------------------------------------------------------
*/

const loadAchievement = async () => {
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

  achievement.value = data;

  form.studentId = String(data.student_id ?? data.student?.id ?? "");
  form.categoryId = String(data.category_id ?? data.category?.id ?? "");
  form.tanggalPrestasi = data.tanggal_prestasi || "";
  form.keterangan = data.keterangan || "";
};

/*
|--------------------------------------------------------------------------
| Load Data
|--------------------------------------------------------------------------
*/

const loadData = async () => {
  loading.value = true;

  try {
    await Promise.all([loadStudents(), loadCategories(), loadAchievement()]);
  } catch (error) {
    console.error("Gagal memuat data edit prestasi:", error);

    achievement.value = null;

    window.alert(getErrorMessage(error));
  } finally {
    loading.value = false;
  }
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
| Methods
|--------------------------------------------------------------------------
*/

const handleCategoryChange = () => {
  errors.categoryId = "";
};

const handleSubmit = async () => {
  if (!validateForm()) {
    return;
  }

  const id = Number(route.params.id);

  if (!id) {
    window.alert("ID prestasi tidak valid.");
    return;
  }

  submitting.value = true;

  try {
    const payload = {
      category_id: Number(form.categoryId),
      tanggal_prestasi: form.tanggalPrestasi,
      keterangan: form.keterangan.trim() || null,
    };

    const response = await api.put(`/staff/achievements/${id}`, payload);

    achievement.value = response.data?.data || achievement.value;

    window.alert(response.data?.message || "Prestasi berhasil diperbarui.");

    router.push(`/staff/prestasi/${id}`);
  } catch (error) {
    console.error("Gagal memperbarui prestasi:", error);

    const validationErrors = error?.response?.data?.errors;

    if (validationErrors) {
      errors.categoryId = validationErrors.category_id?.[0] || "";

      errors.tanggalPrestasi = validationErrors.tanggal_prestasi?.[0] || "";
    }

    window.alert(getErrorMessage(error));
  } finally {
    submitting.value = false;
  }
};

const goBack = () => {
  router.push(`/staff/prestasi/${route.params.id}`);
};

/*
|--------------------------------------------------------------------------
| Lifecycle
|--------------------------------------------------------------------------
*/

onMounted(() => {
  loadData();
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

.form-group select:disabled {
  background: #f7f9fc;
  color: #64748b;
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
  display: flex;
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
  color: #fff;
}

.btn-primary:hover:not(:disabled) {
  background: #1d4ed8;
}

.state-card {
  background: #ffffff;
  border: 1px solid #e8edf5;
  border-radius: 14px;
  min-height: 300px;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  text-align: center;
  padding: 30px;
}

.state-card p {
  color: #718096;
  font-size: 14px;
}

.state-card h3 {
  margin: 16px 0 4px;
  color: #172033;
}

.empty-icon {
  width: 58px;
  height: 58px;
  border-radius: 50%;
  background: #eff6ff;
  color: #2563eb;
  display: flex;
  align-items: center;
  justify-content: center;
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
