<template>
  <AppLayout>
    <div class="form-page">
      <div class="page-header">
        <div>
          <span class="eyebrow">PELANGGARAN</span>
          <h1>Tambah Pelanggaran</h1>
          <p>Catat pelanggaran yang dilakukan oleh siswa.</p>
        </div>

        <button class="back-btn" @click="goBack">
          <ArrowLeft :size="17" />
          Kembali
        </button>
      </div>

      <form class="form-card" @submit.prevent="saveViolation">
        <div class="form-section">
          <div class="section-heading">
            <div class="section-icon">
              <UserRound :size="18" />
            </div>

            <div>
              <h2>Informasi Siswa</h2>
              <p>Pilih siswa yang melakukan pelanggaran.</p>
            </div>
          </div>

          <div class="form-group">
            <label>Siswa <span>*</span></label>

            <select v-model="form.studentId" required>
              <option value="">Pilih siswa</option>
              <option
                v-for="student in students"
                :key="student.id"
                :value="student.id"
              >
                {{ student.name }} — {{ student.nis }}
              </option>
            </select>
          </div>
        </div>

        <div class="form-section">
          <div class="section-heading">
            <div class="section-icon">
              <AlertTriangle :size="18" />
            </div>

            <div>
              <h2>Detail Pelanggaran</h2>
              <p>Masukkan informasi pelanggaran siswa.</p>
            </div>
          </div>

          <div class="form-grid">
            <div class="form-group">
              <label>Kategori Pelanggaran <span>*</span></label>

              <select v-model="form.categoryId" required>
                <option value="">Pilih pelanggaran</option>

                <option
                  v-for="category in categories"
                  :key="category.id"
                  :value="category.id"
                >
                  {{ category.name }} — {{ category.points }} poin
                </option>
              </select>
            </div>

            <div class="form-group">
              <label>Tanggal Kejadian <span>*</span></label>

              <input v-model="form.date" type="date" required />
            </div>

            <div class="form-group full">
              <label>Keterangan</label>

              <textarea
                v-model="form.description"
                rows="5"
                placeholder="Tambahkan keterangan jika diperlukan..."
              ></textarea>
            </div>
          </div>
        </div>

        <!-- PREVIEW -->
        <div v-if="selectedCategory" class="preview-box">
          <div class="preview-icon">
            <AlertTriangle :size="19" />
          </div>

          <div>
            <strong>{{ selectedCategory.name }}</strong>

            <span>
              Kategori {{ capitalize(selectedCategory.category) }} ·
              {{ selectedCategory.points }} poin
            </span>
          </div>
        </div>

        <div class="form-footer">
          <button type="button" class="secondary-btn" @click="goBack">
            Batal
          </button>

          <button
            type="submit"
            class="primary-btn"
            :disabled="submitting || loading"
          >
            <Save :size="17" />
            {{ submitting ? "Menyimpan..." : "Simpan Pelanggaran" }}
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import { useRouter } from "vue-router";

import AppLayout from "../../../layouts/AppLayout.vue";
import api from "../../../utils/api";

import { ArrowLeft, UserRound, AlertTriangle, Save } from "lucide-vue-next";

const router = useRouter();

const students = ref([]);
const categories = ref([]);
const loading = ref(true);
const submitting = ref(false);
const error = ref("");

const form = ref({
  studentId: "",
  categoryId: "",
  date: new Date().toISOString().split("T")[0],
  description: "",
});

const unwrapData = (response) => {
  const data = response?.data?.data ?? response?.data;

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
    name: student.user?.name || student.name || student.nama || "Siswa",
    nis: student.nis || "-",
  };
};

const normalizeCategory = (category) => {
  return {
    id: category.id,
    name:
      category.name || category.nama || category.nama_kategori || "Kategori",
    category:
      category.tingkat || category.category || category.kategori || "ringan",
    points: Number(category.poin ?? category.points ?? 0),
    status: category.status,
  };
};

const loadData = async () => {
  loading.value = true;
  error.value = "";

  try {
    const [studentsResponse, categoriesResponse] = await Promise.all([
      api.get("/staff/students", {
        params: {
          status: "aktif",
          per_page: 100,
        },
      }),
      api.get("/staff/violation-categories", {
        params: {
          per_page: 100,
        },
      }),
    ]);

    students.value = unwrapData(studentsResponse).map(normalizeStudent);

    categories.value = unwrapData(categoriesResponse)
      .map(normalizeCategory)
      .filter((category) => {
        return (
          category.status === undefined ||
          category.status === true ||
          category.status === 1 ||
          category.status === "aktif"
        );
      });
  } catch (err) {
    error.value =
      err.response?.data?.message || "Data siswa dan kategori gagal dimuat.";
  } finally {
    loading.value = false;
  }
};

const selectedCategory = computed(() => {
  return categories.value.find(
    (item) => item.id === Number(form.value.categoryId),
  );
});

const capitalize = (value) => {
  if (!value) return "";

  return value.charAt(0).toUpperCase() + value.slice(1);
};

const goBack = () => {
  router.push("/staff/pelanggaran");
};

const saveViolation = async () => {
  if (submitting.value) return;

  submitting.value = true;
  error.value = "";

  try {
    await api.post("/staff/violations", {
      student_id: Number(form.value.studentId),
      category_id: Number(form.value.categoryId),
      tanggal_kejadian: form.value.date,
      keterangan: form.value.description.trim() || null,
    });

    window.alert("Pelanggaran berhasil dicatat.");
    router.push("/staff/pelanggaran");
  } catch (err) {
    error.value = err.response?.data?.message || "Pelanggaran gagal dicatat.";
  } finally {
    submitting.value = false;
  }
};

onMounted(loadData);
</script>

<style scoped>
.form-page {
  padding: 28px;
  max-width: 950px;
  margin: 0 auto;
}

.page-header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 24px;
}

.eyebrow {
  display: block;
  margin-bottom: 7px;
  color: #2563eb;
  font-size: 11px;
  font-weight: 700;
  letter-spacing: 0.08em;
}

.page-header h1 {
  margin: 0;
  color: #172033;
  font-size: 27px;
  font-weight: 600;
  letter-spacing: -0.02em;
}

.page-header p {
  margin: 7px 0 0;
  color: #64748b;
  font-size: 14px;
}

.back-btn,
.secondary-btn,
.primary-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border-radius: 9px;
  font-family: inherit;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
}

.back-btn {
  padding: 10px 14px;
  border: 1px solid #dfe5ed;
  background: white;
  color: #475569;
}

.form-card {
  overflow: hidden;
  border: 1px solid #e8edf5;
  border-radius: 14px;
  background: white;
}

.form-section {
  padding: 24px;
  border-bottom: 1px solid #edf1f6;
}

.section-heading {
  display: flex;
  gap: 12px;
  margin-bottom: 22px;
}

.section-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  width: 38px;
  height: 38px;
  border-radius: 10px;
  background: #eff6ff;
  color: #2563eb;
}

.section-heading h2 {
  margin: 0;
  color: #172033;
  font-size: 16px;
  font-weight: 600;
}

.section-heading p {
  margin: 4px 0 0;
  color: #94a3b8;
  font-size: 12px;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 18px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 7px;
}

.form-group.full {
  grid-column: 1 / -1;
}

.form-group label {
  color: #475569;
  font-size: 13px;
  font-weight: 500;
}

.form-group label span {
  color: #dc2626;
}

.form-group input,
.form-group select,
.form-group textarea {
  box-sizing: border-box;
  width: 100%;
  border: 1px solid #dfe5ed;
  border-radius: 9px;
  outline: none;
  background: white;
  color: #172033;
  font-family: inherit;
  font-size: 13px;
}

.form-group input,
.form-group select {
  height: 42px;
  padding: 0 12px;
}

.form-group textarea {
  padding: 12px;
  resize: vertical;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #2563eb;
  box-shadow: 0 0 0 3px #eff6ff;
}

.preview-box {
  display: flex;
  align-items: center;
  gap: 12px;
  margin: 20px 24px 0;
  padding: 14px;
  border: 1px solid #dbeafe;
  border-radius: 10px;
  background: #eff6ff;
}

.preview-icon {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 38px;
  height: 38px;
  border-radius: 9px;
  background: white;
  color: #2563eb;
}

.preview-box strong {
  display: block;
  margin-bottom: 3px;
  color: #1e3a8a;
  font-size: 13px;
}

.preview-box span {
  color: #64748b;
  font-size: 12px;
}

.form-footer {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 18px 24px;
  background: #fafbfc;
}

.secondary-btn {
  padding: 10px 16px;
  border: 1px solid #dfe5ed;
  background: white;
  color: #475569;
}

.primary-btn {
  padding: 10px 16px;
  border: none;
  background: #2563eb;
  color: white;
}

.primary-btn:hover {
  background: #1d4ed8;
}

@media (max-width: 650px) {
  .form-page {
    padding: 18px;
  }

  .page-header {
    align-items: flex-start;
    flex-direction: column;
  }

  .form-grid {
    grid-template-columns: 1fr;
  }

  .form-group.full {
    grid-column: auto;
  }
}
</style>
