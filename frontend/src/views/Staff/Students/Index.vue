<template>
  <AppLayout>
    <div class="student-page">
      <!-- HEADER -->
      <div class="page-header">
        <div>
          <h1>Data Siswa</h1>
          <p>Kelola data siswa yang terdaftar di SIKAP Assalaam.</p>
        </div>

        <button class="btn-primary" @click="goToCreate">
          <Plus :size="18" />
          <span>Tambah Siswa</span>
        </button>
      </div>

      <!-- ERROR -->
      <div v-if="errorMessage" class="alert alert-error">
        <CircleAlert :size="18" />

        <span>{{ errorMessage }}</span>

        <button type="button" class="retry-btn" @click="fetchStudents">
          Coba lagi
        </button>
      </div>

      <!-- SEARCH -->
      <div class="filter-card">
        <div class="search-box">
          <Search :size="18" />

          <input
            v-model="searchQuery"
            type="text"
            placeholder="Cari nama siswa atau NIS..."
            :disabled="loading"
          />
        </div>
      </div>

      <!-- TABLE -->
      <div class="table-card">
        <div class="table-header">
          <div>
            <h2>Daftar Siswa</h2>

            <span v-if="!loading">
              {{ filteredStudents.length }} data siswa ditemukan
            </span>

            <span v-else> Memuat data siswa... </span>
          </div>
        </div>

        <div v-if="loading" class="loading-state">
          <LoaderCircle :size="26" class="spin" />
          <span>Memuat data siswa...</span>
        </div>

        <div v-else class="table-wrapper">
          <table>
            <thead>
              <tr>
                <th class="col-no">No</th>
                <th>Siswa</th>
                <th>Tingkat</th>
                <th>Jurusan</th>
                <th>Kelas</th>
                <th>Jenis Kelamin</th>
                <th class="col-action">Aksi</th>
              </tr>
            </thead>

            <tbody>
              <tr
                v-for="(student, index) in filteredStudents"
                :key="student.id"
              >
                <!-- NO -->
                <td class="text-center">
                  {{ index + 1 }}
                </td>

                <!-- SISWA -->
                <td>
                  <div class="student-info">
                    <div class="student-avatar">
                      {{ getInitial(student.nama) }}
                    </div>

                    <div class="student-details">
                      <strong>{{ student.nama }}</strong>
                      <span>{{ student.nis }}</span>
                    </div>
                  </div>
                </td>

                <!-- TINGKAT -->
                <td>
                  <span class="level-badge"> Kelas {{ student.tingkat }} </span>
                </td>

                <!-- JURUSAN -->
                <td>
                  <span class="jurusan-text">
                    {{ student.jurusan }}
                  </span>
                </td>

                <!-- KELAS -->
                <td>
                  {{ student.nomorKelas }}
                </td>

                <!-- JENIS KELAMIN -->
                <td>
                  {{ student.jenisKelamin }}
                </td>

                <!-- AKSI -->
                <td>
                  <div class="action-buttons">
                    <button
                      class="action-btn view"
                      title="Lihat detail"
                      @click="goToShow(student.id)"
                    >
                      <Eye :size="17" />
                    </button>

                    <button
                      class="action-btn edit"
                      title="Edit siswa"
                      @click="goToEdit(student.id)"
                    >
                      <Pencil :size="17" />
                    </button>

                    <button
                      class="action-btn delete"
                      title="Hapus siswa"
                      :disabled="deleting"
                      @click="deleteStudent(student)"
                    >
                      <LoaderCircle v-if="deleting" :size="17" class="spin" />

                      <Trash2 v-else :size="17" />
                    </button>
                  </div>
                </td>
              </tr>

              <!-- EMPTY -->
              <tr v-if="filteredStudents.length === 0">
                <td colspan="7">
                  <div class="empty-state">
                    <div class="empty-icon">
                      <Users :size="28" />
                    </div>

                    <strong>Data siswa tidak ditemukan</strong>

                    <span> Tidak ada siswa yang sesuai dengan pencarian. </span>
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
import { computed, onMounted, ref } from "vue";
import { useRouter } from "vue-router";

import {
  Plus,
  Search,
  Eye,
  Pencil,
  Trash2,
  Users,
  LoaderCircle,
  CircleAlert,
} from "lucide-vue-next";

import AppLayout from "../../../layouts/AppLayout.vue";
import api from "../../../utils/api";

const router = useRouter();

const searchQuery = ref("");
const students = ref([]);

const loading = ref(false);
const deleting = ref(false);
const errorMessage = ref("");

const normalizeStudent = (student) => {
  return {
    id: student?.id,
    nama: student?.name ?? student?.nama ?? "-",
    nis: student?.nis ?? "-",
    tingkat: String(student?.tingkat ?? student?.level ?? "-"),
    jurusan: student?.jurusan ?? student?.major ?? "-",
    nomorKelas:
      student?.nomor_kelas ??
      student?.nomorKelas ??
      student?.class_number ??
      "-",
    jenisKelamin: normalizeGender(
      student?.jenis_kelamin ?? student?.jenisKelamin ?? student?.gender,
    ),
    tahunMasuk:
      student?.tahun_masuk ?? student?.tahunMasuk ?? student?.entry_year ?? "-",
    status: normalizeStatus(student?.status),
    email: student?.email ?? student?.user?.email ?? "-",
  };
};

const normalizeGender = (gender) => {
  if (!gender) return "-";

  const value = String(gender).toLowerCase();

  if (value === "l" || value === "laki-laki" || value === "male") {
    return "Laki-laki";
  }

  if (value === "p" || value === "perempuan" || value === "female") {
    return "Perempuan";
  }

  return gender;
};

const normalizeStatus = (status) => {
  if (!status) return "-";

  const value = String(status).toLowerCase();

  if (value === "aktif" || value === "active") {
    return "Aktif";
  }

  if (value === "nonaktif" || value === "inactive") {
    return "Nonaktif";
  }

  if (value === "lulus" || value === "graduated") {
    return "Lulus";
  }

  return status;
};

const fetchStudents = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await api.get("/staff/students");

    const data = response.data?.data ?? response.data;

    if (Array.isArray(data)) {
      students.value = data.map(normalizeStudent);
    } else if (Array.isArray(data?.data)) {
      students.value = data.data.map(normalizeStudent);
    } else {
      students.value = [];
    }
  } catch (error) {
    console.error("Gagal mengambil data siswa:", error);

    errorMessage.value =
      error.response?.data?.message ||
      "Tidak dapat mengambil data siswa dari server Laravel.";
  } finally {
    loading.value = false;
  }
};

onMounted(fetchStudents);

const filteredStudents = computed(() => {
  const query = searchQuery.value.trim().toLowerCase();

  if (!query) {
    return students.value;
  }

  return students.value.filter((student) => {
    const nama = String(student.nama ?? "").toLowerCase();
    const nis = String(student.nis ?? "").toLowerCase();

    return nama.includes(query) || nis.includes(query);
  });
});

const getInitial = (name) => {
  if (!name || name === "-") return "?";

  return name
    .split(" ")
    .filter(Boolean)
    .map((word) => word.charAt(0))
    .slice(0, 2)
    .join("")
    .toUpperCase();
};

const goToCreate = () => {
  router.push("/staff/siswa/create");
};

const goToShow = (id) => {
  router.push(`/staff/siswa/${id}`);
};

const goToEdit = (id) => {
  router.push(`/staff/siswa/${id}/edit`);
};

const deleteStudent = async (student) => {
  const confirmed = window.confirm(
    `Apakah kamu yakin ingin menghapus siswa "${student.nama}"?`,
  );

  if (!confirmed) {
    return;
  }

  deleting.value = true;
  errorMessage.value = "";

  try {
    await api.delete(`/staff/students/${student.id}`);

    students.value = students.value.filter((item) => item.id !== student.id);
  } catch (error) {
    console.error("Gagal menghapus siswa:", error);

    errorMessage.value =
      error.response?.data?.message || "Gagal menghapus data siswa.";
  } finally {
    deleting.value = false;
  }
};
</script>

<style scoped>
.student-page {
  width: 100%;
}

/* =========================
   HEADER
========================= */

.page-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 24px;
}

.page-header h1 {
  margin: 0 0 6px;
  font-size: 25px;
  font-weight: 700;
  color: #172033;
}

.page-header p {
  margin: 0;
  color: #7b8497;
  font-size: 14px;
}

/* =========================
   BUTTON
========================= */

.btn-primary {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 8px;

  border: none;
  border-radius: 10px;

  padding: 11px 17px;

  background: #2563eb;
  color: #ffffff;

  font-size: 14px;
  font-weight: 600;

  cursor: pointer;
  transition: 0.2s ease;
}

.btn-primary:hover {
  background: #1d4ed8;
  transform: translateY(-1px);
}

/* =========================
   FILTER
========================= */

.filter-card {
  display: flex;
  align-items: center;

  padding: 16px;
  margin-bottom: 18px;

  background: #ffffff;

  border: 1px solid #e8ebf2;
  border-radius: 13px;
}

.search-box {
  width: 100%;
  max-width: 420px;

  height: 42px;

  display: flex;
  align-items: center;
  gap: 9px;

  padding: 0 13px;

  box-sizing: border-box;

  border: 1px solid #dfe3eb;
  border-radius: 9px;

  color: #8992a5;

  transition: 0.2s ease;
}

.search-box:focus-within {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.08);
}

.search-box input {
  width: 100%;
  height: 100%;

  border: none;
  outline: none;

  background: transparent;

  color: #172033;
  font-size: 13px;
}

.search-box input::placeholder {
  color: #9ca3af;
}

/* =========================
   TABLE
========================= */

.table-card {
  background: #ffffff;

  border: 1px solid #e8ebf2;
  border-radius: 13px;

  overflow: hidden;

  box-shadow: 0 3px 12px rgba(30, 41, 59, 0.04);
}

.table-header {
  padding: 18px 20px;

  border-bottom: 1px solid #edf0f4;
}

.table-header h2 {
  margin: 0 0 4px;

  font-size: 16px;
  font-weight: 600;

  color: #172033;
}

.table-header span {
  font-size: 12px;
  color: #8992a5;
}

/* =========================
   TABLE WRAPPER
========================= */

.table-wrapper {
  width: 100%;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
}

thead {
  background: #f8faff;
}

th {
  padding: 13px 14px;

  text-align: left;

  color: #667085;

  font-size: 12px;
  font-weight: 600;

  white-space: nowrap;

  border-bottom: 1px solid #edf0f4;
}

td {
  padding: 14px;

  color: #475467;

  font-size: 13px;
  font-weight: 400;

  white-space: nowrap;

  border-bottom: 1px solid #f0f2f5;
}

tbody tr {
  transition: 0.15s ease;
}

tbody tr:hover {
  background: #fafcff;
}

tbody tr:last-child td {
  border-bottom: none;
}

/* =========================
   COLUMN
========================= */

.col-no {
  width: 55px;
}

.col-action {
  width: 125px;
}

.text-center {
  text-align: center;
}

/* =========================
   STUDENT
========================= */

.student-info {
  display: flex;
  align-items: center;
  gap: 10px;

  min-width: 190px;
}

.student-avatar {
  width: 35px;
  height: 35px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 50%;

  background: #eaf1ff;
  color: #2563eb;

  font-size: 11px;
  font-weight: 700;
}

.student-details {
  min-width: 0;
}

.student-details strong {
  display: block;

  margin-bottom: 3px;

  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;

  color: #172033;

  font-size: 13px;
  font-weight: 500;
}

.student-details span {
  display: block;

  color: #98a1b2;

  font-size: 11px;
}

/* =========================
   TINGKAT
========================= */

.level-badge {
  display: inline-flex;
  align-items: center;

  padding: 5px 9px;

  border-radius: 20px;

  background: #eaf1ff;
  color: #2563eb;

  font-size: 11px;
  font-weight: 600;
}

/* =========================
   JURUSAN
========================= */

.jurusan-text {
  color: #344054;
  font-weight: 500;
}

/* =========================
   ACTION
========================= */

.action-buttons {
  display: flex;
  align-items: center;
  gap: 6px;
}

.action-btn {
  width: 32px;
  height: 32px;

  display: flex;
  align-items: center;
  justify-content: center;

  border: 1px solid #e3e7ee;
  border-radius: 8px;

  background: #ffffff;

  cursor: pointer;

  transition: 0.15s ease;
}

.action-btn.view {
  color: #2563eb;
}

.action-btn.view:hover {
  background: #eff6ff;
  border-color: #bfdbfe;
}

.action-btn.edit {
  color: #d97706;
}

.action-btn.edit:hover {
  background: #fffbeb;
  border-color: #fde68a;
}

.action-btn.delete {
  color: #dc2626;
}

.action-btn.delete:hover {
  background: #fef2f2;
  border-color: #fecaca;
}

/* =========================
   EMPTY
========================= */

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;

  padding: 55px 20px;
}

.empty-icon {
  width: 58px;
  height: 58px;

  display: flex;
  align-items: center;
  justify-content: center;

  margin-bottom: 12px;

  border-radius: 50%;

  background: #f2f4f7;
  color: #98a2b3;
}

.empty-state strong {
  margin-bottom: 5px;

  color: #344054;

  font-size: 14px;
  font-weight: 600;
}

.empty-state span {
  color: #98a2b3;
  font-size: 12px;
}

/* =========================
   RESPONSIVE
========================= */

@media (max-width: 900px) {
  table {
    min-width: 850px;
  }
}

@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
    align-items: flex-start;
  }

  .btn-primary {
    width: 100%;
  }

  .filter-card {
    padding: 12px;
  }

  .search-box {
    max-width: none;
  }
}
</style>
