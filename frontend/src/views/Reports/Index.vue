<template>
  <AppLayout>
    <div class="report-page">
      <!-- HEADER -->
      <div class="page-header">
        <div>
          <h1>{{ isStaff ? "Laporan" : "Laporan Saya" }}</h1>
          <p>
            {{
              isStaff
                ? "Kelola dan lihat laporan data siswa."
                : "Lihat ringkasan data pelanggaran, prestasi, dan penanganan Anda."
            }}
          </p>
        </div>
      </div>

      <!-- ================= STAFF ================= -->
      <template v-if="isStaff">
        <!-- TAB -->
        <div class="report-tabs">
          <button
            :class="{ active: activeTab === 'ringkasan' }"
            @click="changeTab('ringkasan')"
          >
            Ringkasan Siswa
          </button>

          <button
            :class="{ active: activeTab === 'pelanggaran' }"
            @click="changeTab('pelanggaran')"
          >
            Pelanggaran
          </button>

          <button
            :class="{ active: activeTab === 'prestasi' }"
            @click="changeTab('prestasi')"
          >
            Prestasi
          </button>

          <button
            :class="{ active: activeTab === 'penanganan' }"
            @click="changeTab('penanganan')"
          >
            Penanganan
          </button>
        </div>

        <!-- FILTER -->
        <div class="filter-card">
          <div class="filter-header">
            <div>
              <h2>Filter Laporan</h2>
              <p>Gunakan filter untuk menampilkan data tertentu.</p>
            </div>

            <button class="btn-reset" @click="resetFilter">Reset</button>
          </div>

          <div class="filter-grid">
            <div class="form-group">
              <label>Siswa</label>

              <select v-model="filters.studentId">
                <option value="">Semua Siswa</option>

                <option
                  v-for="student in students"
                  :key="student.id"
                  :value="student.id"
                >
                  {{ student.name }}
                </option>
              </select>
            </div>

            <div class="form-group">
              <label>Tingkat</label>

              <select v-model="filters.tingkat">
                <option value="">Semua Tingkat</option>
                <option value="10">Kelas 10</option>
                <option value="11">Kelas 11</option>
                <option value="12">Kelas 12</option>
              </select>
            </div>

            <div class="form-group">
              <label>Jurusan</label>

              <select v-model="filters.jurusan">
                <option value="">Semua Jurusan</option>
                <option value="RPL">RPL</option>
                <option value="TSM">TSM</option>
                <option value="TKR">TKR</option>
              </select>
            </div>

            <div class="form-group">
              <label>Nomor Kelas</label>

              <select v-model="filters.nomorKelas">
                <option value="">Semua Kelas</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
                <option value="5">5</option>
              </select>
            </div>

            <!-- FILTER PELANGGARAN -->
            <template v-if="activeTab === 'pelanggaran'">
              <div class="form-group">
                <label>Status</label>

                <select v-model="filters.status">
                  <option value="">Semua Status</option>
                  <option value="aktif">Aktif</option>
                  <option value="dibatalkan">Dibatalkan</option>
                </select>
              </div>

              <div class="form-group">
                <label>Tanggal Mulai</label>

                <input v-model="filters.tanggalMulai" type="date" />
              </div>

              <div class="form-group">
                <label>Tanggal Selesai</label>

                <input v-model="filters.tanggalSelesai" type="date" />
              </div>
            </template>

            <!-- FILTER PRESTASI -->
            <template v-if="activeTab === 'prestasi'">
              <div class="form-group">
                <label>Status</label>

                <select v-model="filters.status">
                  <option value="">Semua Status</option>
                  <option value="aktif">Aktif</option>
                  <option value="dibatalkan">Dibatalkan</option>
                </select>
              </div>

              <div class="form-group">
                <label>Tanggal Mulai</label>

                <input v-model="filters.tanggalMulai" type="date" />
              </div>

              <div class="form-group">
                <label>Tanggal Selesai</label>

                <input v-model="filters.tanggalSelesai" type="date" />
              </div>
            </template>

            <!-- FILTER PENANGANAN -->
            <template v-if="activeTab === 'penanganan'">
              <div class="form-group">
                <label>Tahap</label>

                <select v-model="filters.tahap">
                  <option value="">Semua Tahap</option>
                  <option value="wali_kelas">Wali Kelas</option>
                  <option value="bk">BK</option>
                  <option value="kesiswaan">Kesiswaan</option>
                  <option value="kepala_sekolah">
                    Kepala Sekolah
                  </option>
                </select>
              </div>

              <div class="form-group">
                <label>Status</label>

                <select v-model="filters.status">
                  <option value="">Semua Status</option>
                  <option value="menunggu">Menunggu</option>
                  <option value="diproses">Diproses</option>
                  <option value="selesai">Selesai</option>
                </select>
              </div>

              <div class="form-group">
                <label>Tanggal Mulai</label>

                <input v-model="filters.tanggalMulai" type="date" />
              </div>

              <div class="form-group">
                <label>Tanggal Selesai</label>

                <input v-model="filters.tanggalSelesai" type="date" />
              </div>
            </template>
          </div>
        </div>

        <!-- ERROR -->
        <div v-if="errorMessage" class="error-alert">
          {{ errorMessage }}
        </div>

        <!-- LOADING -->
        <div v-if="loading" class="loading-state">
          Memuat data laporan...
        </div>

        <!-- RINGKASAN SISWA -->
        <div
          v-else-if="activeTab === 'ringkasan'"
          class="report-card"
        >
          <div class="card-header">
            <div>
              <h2>Ringkasan Siswa</h2>
              <p>Rekap poin pelanggaran dan prestasi siswa.</p>
            </div>
          </div>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Siswa</th>
                  <th>NIS</th>
                  <th>Tingkat</th>
                  <th>Jurusan</th>
                  <th>Kelas</th>
                  <th>Poin Pelanggaran</th>
                  <th>Poin Prestasi</th>
                  <th>Net Poin</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(student, index) in studentsReport"
                  :key="student.id"
                >
                  <td>{{ index + 1 }}</td>

                  <td>
                    <div class="student-cell">
                      <div class="student-avatar">
                        {{ getInitial(student.name) }}
                      </div>

                      <span>{{ student.name }}</span>
                    </div>
                  </td>

                  <td>{{ student.nis }}</td>
                  <td>{{ student.tingkat }}</td>
                  <td>{{ student.jurusan }}</td>
                  <td>{{ student.nomorKelas }}</td>

                  <td>
                    <span class="point negative">
                      {{ student.violationPoints }}
                    </span>
                  </td>

                  <td>
                    <span class="point positive">
                      {{ student.achievementPoints }}
                    </span>
                  </td>

                  <td>
                    <strong>
                      {{ student.netPoints }}
                    </strong>
                  </td>
                </tr>

                <tr v-if="studentsReport.length === 0">
                  <td colspan="9" class="empty-state">
                    Tidak ada data siswa.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- PELANGGARAN -->
        <div
          v-else-if="activeTab === 'pelanggaran'"
          class="report-card"
        >
          <div class="card-header">
            <div>
              <h2>Laporan Pelanggaran</h2>
              <p>Data pelanggaran siswa.</p>
            </div>

            <div class="export-actions">
              <button
                class="btn-export excel"
                :disabled="exporting"
                @click="exportReport('violations', 'excel')"
              >
                {{ exporting ? "Export..." : "Export Excel" }}
              </button>

              <button
                class="btn-export pdf"
                :disabled="exporting"
                @click="exportReport('violations', 'pdf')"
              >
                {{ exporting ? "Export..." : "Export PDF" }}
              </button>
            </div>
          </div>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Siswa</th>
                  <th>Pelanggaran</th>
                  <th>Poin</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(item, index) in violations"
                  :key="item.id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.student }}</td>
                  <td>{{ item.violation }}</td>

                  <td>
                    <span class="point negative">
                      {{ item.points }}
                    </span>
                  </td>

                  <td>{{ item.date }}</td>

                  <td>
                    <span class="status-badge">
                      {{ formatStatus(item.status) }}
                    </span>
                  </td>
                </tr>

                <tr v-if="violations.length === 0">
                  <td colspan="6" class="empty-state">
                    Tidak ada data pelanggaran.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- PRESTASI -->
        <div
          v-else-if="activeTab === 'prestasi'"
          class="report-card"
        >
          <div class="card-header">
            <div>
              <h2>Laporan Prestasi</h2>
              <p>Data prestasi siswa.</p>
            </div>

            <div class="export-actions">
              <button
                class="btn-export excel"
                :disabled="exporting"
                @click="exportReport('achievements', 'excel')"
              >
                {{ exporting ? "Export..." : "Export Excel" }}
              </button>

              <button
                class="btn-export pdf"
                :disabled="exporting"
                @click="exportReport('achievements', 'pdf')"
              >
                {{ exporting ? "Export..." : "Export PDF" }}
              </button>
            </div>
          </div>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Siswa</th>
                  <th>Prestasi</th>
                  <th>Tingkat</th>
                  <th>Poin</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(item, index) in achievements"
                  :key="item.id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.student }}</td>
                  <td>{{ item.achievement }}</td>
                  <td>{{ item.level }}</td>

                  <td>
                    <span class="point positive">
                      {{ item.points }}
                    </span>
                  </td>

                  <td>{{ item.date }}</td>

                  <td>
                    <span class="status-badge">
                      {{ formatStatus(item.status) }}
                    </span>
                  </td>
                </tr>

                <tr v-if="achievements.length === 0">
                  <td colspan="7" class="empty-state">
                    Tidak ada data prestasi.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- PENANGANAN -->
        <div
          v-else-if="activeTab === 'penanganan'"
          class="report-card"
        >
          <div class="card-header">
            <div>
              <h2>Laporan Penanganan</h2>
              <p>Data proses penanganan siswa.</p>
            </div>

            <div class="export-actions">
              <button
                class="btn-export excel"
                :disabled="exporting"
                @click="exportReport('interventions', 'excel')"
              >
                {{ exporting ? "Export..." : "Export Excel" }}
              </button>

              <button
                class="btn-export pdf"
                :disabled="exporting"
                @click="exportReport('interventions', 'pdf')"
              >
                {{ exporting ? "Export..." : "Export PDF" }}
              </button>
            </div>
          </div>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Siswa</th>
                  <th>Tahap</th>
                  <th>Poin Saat Penanganan</th>
                  <th>Status</th>
                  <th>Tanggal Mulai</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(item, index) in interventions"
                  :key="item.id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.student }}</td>
                  <td>{{ formatStage(item.stage) }}</td>
                  <td>{{ item.points }}</td>

                  <td>
                    <span class="status-badge">
                      {{ formatStatus(item.status) }}
                    </span>
                  </td>

                  <td>{{ item.date }}</td>
                </tr>

                <tr v-if="interventions.length === 0">
                  <td colspan="6" class="empty-state">
                    Tidak ada data penanganan.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </template>

      <!-- ================= STUDENT ================= -->
      <template v-else-if="isStudent">
        <div class="student-report-info">
          <div>
            <h2>Ringkasan Saya</h2>
            <p>
              Berikut data pelanggaran, prestasi, dan penanganan Anda.
            </p>
          </div>
        </div>

        <!-- TAB SISWA -->
        <div class="report-tabs">
          <button
            :class="{ active: activeStudentTab === 'pelanggaran' }"
            @click="changeStudentTab('pelanggaran')"
          >
            Pelanggaran Saya
          </button>

          <button
            :class="{ active: activeStudentTab === 'prestasi' }"
            @click="changeStudentTab('prestasi')"
          >
            Prestasi Saya
          </button>

          <button
            :class="{ active: activeStudentTab === 'penanganan' }"
            @click="changeStudentTab('penanganan')"
          >
            Penanganan Saya
          </button>
        </div>

        <!-- ERROR SISWA -->
        <div v-if="errorMessage" class="error-alert">
          {{ errorMessage }}
        </div>

        <!-- LOADING SISWA -->
        <div v-if="loading" class="loading-state">
          Memuat data laporan...
        </div>

        <!-- STUDENT PELANGGARAN -->
        <div
          v-else-if="activeStudentTab === 'pelanggaran'"
          class="report-card"
        >
          <div class="card-header">
            <div>
              <h2>Pelanggaran Saya</h2>
              <p>Riwayat pelanggaran Anda.</p>
            </div>

            <div class="export-actions">
              <button
                class="btn-export excel"
                :disabled="exporting"
                @click="exportReport('violations', 'excel')"
              >
                {{ exporting ? "Export..." : "Export Excel" }}
              </button>

              <button
                class="btn-export pdf"
                :disabled="exporting"
                @click="exportReport('violations', 'pdf')"
              >
                {{ exporting ? "Export..." : "Export PDF" }}
              </button>
            </div>
          </div>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Pelanggaran</th>
                  <th>Poin</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(item, index) in studentViolations"
                  :key="item.id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.violation }}</td>

                  <td>
                    <span class="point negative">
                      {{ item.points }}
                    </span>
                  </td>

                  <td>{{ item.date }}</td>

                  <td>
                    <span class="status-badge">
                      {{ formatStatus(item.status) }}
                    </span>
                  </td>
                </tr>

                <tr v-if="studentViolations.length === 0">
                  <td colspan="5" class="empty-state">
                    Belum ada data pelanggaran.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- STUDENT PRESTASI -->
        <div
          v-else-if="activeStudentTab === 'prestasi'"
          class="report-card"
        >
          <div class="card-header">
            <div>
              <h2>Prestasi Saya</h2>
              <p>Riwayat prestasi Anda.</p>
            </div>

            <div class="export-actions">
              <button
                class="btn-export excel"
                :disabled="exporting"
                @click="exportReport('achievements', 'excel')"
              >
                {{ exporting ? "Export..." : "Export Excel" }}
              </button>

              <button
                class="btn-export pdf"
                :disabled="exporting"
                @click="exportReport('achievements', 'pdf')"
              >
                {{ exporting ? "Export..." : "Export PDF" }}
              </button>
            </div>
          </div>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Prestasi</th>
                  <th>Tingkat</th>
                  <th>Poin</th>
                  <th>Tanggal</th>
                  <th>Status</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(item, index) in studentAchievements"
                  :key="item.id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ item.achievement }}</td>
                  <td>{{ item.level }}</td>

                  <td>
                    <span class="point positive">
                      {{ item.points }}
                    </span>
                  </td>

                  <td>{{ item.date }}</td>

                  <td>
                    <span class="status-badge">
                      {{ formatStatus(item.status) }}
                    </span>
                  </td>
                </tr>

                <tr v-if="studentAchievements.length === 0">
                  <td colspan="6" class="empty-state">
                    Belum ada data prestasi.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- STUDENT PENANGANAN -->
        <div
          v-else-if="activeStudentTab === 'penanganan'"
          class="report-card"
        >
          <div class="card-header">
            <div>
              <h2>Penanganan Saya</h2>
              <p>Riwayat proses penanganan Anda.</p>
            </div>

            <div class="export-actions">
              <button
                class="btn-export excel"
                :disabled="exporting"
                @click="exportReport('interventions', 'excel')"
              >
                {{ exporting ? "Export..." : "Export Excel" }}
              </button>

              <button
                class="btn-export pdf"
                :disabled="exporting"
                @click="exportReport('interventions', 'pdf')"
              >
                {{ exporting ? "Export..." : "Export PDF" }}
              </button>
            </div>
          </div>

          <div class="table-wrapper">
            <table>
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tahap</th>
                  <th>Poin Saat Penanganan</th>
                  <th>Status</th>
                  <th>Tanggal Mulai</th>
                </tr>
              </thead>

              <tbody>
                <tr
                  v-for="(item, index) in studentInterventions"
                  :key="item.id"
                >
                  <td>{{ index + 1 }}</td>
                  <td>{{ formatStage(item.stage) }}</td>
                  <td>{{ item.points }}</td>

                  <td>
                    <span class="status-badge">
                      {{ formatStatus(item.status) }}
                    </span>
                  </td>

                  <td>{{ item.date }}</td>
                </tr>

                <tr v-if="studentInterventions.length === 0">
                  <td colspan="5" class="empty-state">
                    Belum ada data penanganan.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </template>
    </div>
  </AppLayout>
</template>

<script setup>
import { computed, onMounted, ref, watch } from "vue";
import AppLayout from "../../layouts/AppLayout.vue";
import api from "../../utils/api";
import { getUser } from "../../utils/auth";

const user = getUser();

const isStaff = computed(() => user?.role === "staff");
const isStudent = computed(() => user?.role === "student");

const activeTab = ref("ringkasan");
const activeStudentTab = ref("pelanggaran");

const loading = ref(false);
const exporting = ref(false);
const errorMessage = ref("");

const filters = ref({
  studentId: "",
  tingkat: "",
  jurusan: "",
  nomorKelas: "",
  status: "",
  tahap: "",
  tanggalMulai: "",
  tanggalSelesai: "",
});

const students = ref([]);
const studentsReport = ref([]);
const violations = ref([]);
const achievements = ref([]);
const interventions = ref([]);

const studentViolations = ref([]);
const studentAchievements = ref([]);
const studentInterventions = ref([]);

const getResponseData = (response) => {
  return response?.data?.data ?? response?.data ?? [];
};

const normalizeStudent = (student) => {
  return {
    id: student.id ?? student.student_id,
    name: student.name ?? student.user?.name ?? student.user?.nama ?? "-",
    nis: student.nis ?? "-",
    tingkat: String(student.tingkat ?? ""),
    jurusan: student.jurusan ?? "-",
    nomorKelas: student.nomor_kelas ?? student.nomorKelas ?? "-",
    violationPoints: Number(student.violation_points ?? 0),
    achievementPoints: Number(student.achievement_points ?? 0),
    netPoints: Number(
      student.net_points ??
        Number(student.achievement_points ?? 0) -
          Number(student.violation_points ?? 0),
    ),
  };
};

const normalizeViolation = (item) => {
  return {
    id: item.id,
    studentId: item.student_id,

    student:
      item.student?.user?.name ??
      item.student?.user?.nama ??
      item.student?.name ??
      "-",

    violation:
      item.category?.nama_pelanggaran ??
      item.violation?.nama_pelanggaran ??
      item.violation?.name ??
      "-",

    points: Number(item.poin_tercatat ?? item.point ?? item.poin ?? 0),

    date: item.tanggal_kejadian ?? item.date ?? "-",

    status: item.status ?? "-",

    tingkat: String(item.student?.tingkat ?? ""),

    jurusan: item.student?.jurusan ?? "",

    nomorKelas: item.student?.nomor_kelas ?? "",
  };
};

const normalizeAchievement = (item) => {
  return {
    id: item.id,
    studentId: item.student_id,

    student:
      item.student?.user?.name ??
      item.student?.user?.nama ??
      item.student?.name ??
      "-",

    achievement:
      item.category?.nama_prestasi ??
      item.achievement?.nama_prestasi ??
      item.achievement?.name ??
      "-",

    level: item.category?.tingkat ?? item.tingkat ?? "-",

    points: Number(item.poin_tercatat ?? item.point ?? item.poin ?? 0),

    date: item.tanggal_prestasi ?? item.date ?? "-",

    status: item.status ?? "-",

    tingkat: String(item.student?.tingkat ?? ""),

    jurusan: item.student?.jurusan ?? "",

    nomorKelas: item.student?.nomor_kelas ?? "",
  };
};

const normalizeIntervention = (item) => {
  return {
    id: item.id,
    studentId: item.student_id,

    student:
      item.student?.user?.name ??
      item.student?.user?.nama ??
      item.student?.name ??
      "-",

    stage: item.tahap ?? "-",

    points: Number(
      item.poin_saat_penanganan ?? item.point ?? item.poin ?? 0,
    ),

    status: item.status ?? "-",

    date: item.tanggal_mulai ?? item.date ?? "-",

    tingkat: String(item.student?.tingkat ?? ""),

    jurusan: item.student?.jurusan ?? "",

    nomorKelas: item.student?.nomor_kelas ?? "",
  };
};

const buildFilters = () => {
  const params = {};

  if (filters.value.studentId) {
    params.student_id = filters.value.studentId;
  }

  if (filters.value.tingkat) {
    params.tingkat = filters.value.tingkat;
  }

  if (filters.value.jurusan) {
    params.jurusan = filters.value.jurusan;
  }

  if (filters.value.nomorKelas) {
    params.nomor_kelas = filters.value.nomorKelas;
  }

  if (filters.value.status) {
    params.status = filters.value.status;
  }

  if (filters.value.tahap) {
    params.tahap = filters.value.tahap;
  }

  if (filters.value.tanggalMulai) {
    params.tanggal_mulai = filters.value.tanggalMulai;
  }

  if (filters.value.tanggalSelesai) {
    params.tanggal_selesai = filters.value.tanggalSelesai;
  }

  return params;
};

const loadStudents = async () => {
  try {
    const response = await api.get("/staff/students", {
      params: {
        per_page: 1000,
      },
    });

    const data = getResponseData(response);

    const studentsData = Array.isArray(data)
      ? data
      : (data?.data ?? []);

    students.value = studentsData.map(normalizeStudent);
  } catch (error) {
    console.error("Gagal memuat siswa:", error);
  }
};

const loadStaffStudentReport = async () => {
  const response = await api.get("/staff/reports/students", {
    params: buildFilters(),
  });

  const data = getResponseData(response);

  studentsReport.value = Array.isArray(data)
    ? data.map(normalizeStudent)
    : [];
};

const loadViolations = async () => {
  const response = await api.get("/staff/violations", {
    params: buildFilters(),
  });

  const data = getResponseData(response);

  const violationsData = Array.isArray(data)
    ? data
    : (data?.data ?? []);

  violations.value = violationsData.map(normalizeViolation);
};

const loadAchievements = async () => {
  const response = await api.get("/staff/achievements", {
    params: buildFilters(),
  });

  const data = getResponseData(response);

  const achievementsData = Array.isArray(data)
    ? data
    : (data?.data ?? []);

  achievements.value = achievementsData.map(normalizeAchievement);
};

const loadInterventions = async () => {
  const response = await api.get("/staff/interventions", {
    params: buildFilters(),
  });

  const data = getResponseData(response);

  const interventionsData = Array.isArray(data)
    ? data
    : (data?.data ?? []);

  interventions.value = interventionsData.map(normalizeIntervention);
};

const loadStaffReport = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    if (activeTab.value === "ringkasan") {
      await loadStaffStudentReport();
    }

    if (activeTab.value === "pelanggaran") {
      await loadViolations();
    }

    if (activeTab.value === "prestasi") {
      await loadAchievements();
    }

    if (activeTab.value === "penanganan") {
      await loadInterventions();
    }
  } catch (error) {
    console.error("Gagal memuat laporan:", error);

    errorMessage.value =
      error?.response?.data?.message ??
      "Gagal memuat data laporan.";
  } finally {
    loading.value = false;
  }
};

const loadStudentViolations = async () => {
  const response = await api.get("/student/violations");

  const data = getResponseData(response);

  const violationsData = Array.isArray(data)
    ? data
    : (data?.data ?? []);

  studentViolations.value = violationsData.map(normalizeViolation);
};

const loadStudentAchievements = async () => {
  const response = await api.get("/student/achievements");

  const data = getResponseData(response);

  const achievementsData = Array.isArray(data)
    ? data
    : (data?.data ?? []);

  studentAchievements.value =
    achievementsData.map(normalizeAchievement);
};

const loadStudentInterventions = async () => {
  const response = await api.get("/student/interventions");

  const data = getResponseData(response);

  const interventionsData = Array.isArray(data)
    ? data
    : (data?.data ?? []);

  studentInterventions.value =
    interventionsData.map(normalizeIntervention);
};

const loadStudentReport = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    if (activeStudentTab.value === "pelanggaran") {
      await loadStudentViolations();
    }

    if (activeStudentTab.value === "prestasi") {
      await loadStudentAchievements();
    }

    if (activeStudentTab.value === "penanganan") {
      await loadStudentInterventions();
    }
  } catch (error) {
    console.error("Gagal memuat laporan siswa:", error);

    errorMessage.value =
      error?.response?.data?.message ??
      "Gagal memuat data laporan.";
  } finally {
    loading.value = false;
  }
};

const changeTab = async (tab) => {
  activeTab.value = tab;

  if (isStaff.value) {
    await loadStaffReport();
  }
};

const changeStudentTab = async (tab) => {
  activeStudentTab.value = tab;

  if (isStudent.value) {
    await loadStudentReport();
  }
};

const resetFilter = async () => {
  filters.value = {
    studentId: "",
    tingkat: "",
    jurusan: "",
    nomorKelas: "",
    status: "",
    tahap: "",
    tanggalMulai: "",
    tanggalSelesai: "",
  };

  if (isStaff.value) {
    await loadStaffReport();
  }
};

const getInitial = (name) => {
  if (!name) return "?";

  return name
    .split(" ")
    .map((word) => word.charAt(0))
    .slice(0, 2)
    .join("")
    .toUpperCase();
};

const formatStatus = (status) => {
  if (!status) return "-";

  return String(status)
    .replaceAll("_", " ")
    .replace(/\b\w/g, (char) => char.toUpperCase());
};

const formatStage = (stage) => {
  if (!stage) return "-";

  const stages = {
    wali_kelas: "Wali Kelas",
    bk: "BK",
    kesiswaan: "Kesiswaan",
    kepala_sekolah: "Kepala Sekolah",
  };

  return stages[stage] ?? formatStatus(stage);
};

const getExportEndpoint = (type, format) => {
  if (isStaff.value) {
    return `/staff/reports/${type}/${format}`;
  }

  return `/student/reports/${type}/${format}`;
};

const getExportFilename = (type, format) => {
  const names = {
    violations: "laporan-pelanggaran",
    achievements: "laporan-prestasi",
    interventions: "laporan-intervention",
  };

  return `${names[type] ?? "laporan"}.${
    format === "excel" ? "xlsx" : "pdf"
  }`;
};

const exportReport = async (type, format) => {
  exporting.value = true;
  errorMessage.value = "";

  try {
    const response = await api.get(
      getExportEndpoint(type, format),
      {
        params: isStaff.value ? buildFilters() : {},
        responseType: "blob",
      },
    );

    const blob = new Blob([response.data], {
      type:
        format === "excel"
          ? "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
          : "application/pdf",
    });

    const url = window.URL.createObjectURL(blob);

    const link = document.createElement("a");

    link.href = url;
    link.download = getExportFilename(type, format);

    document.body.appendChild(link);
    link.click();
    link.remove();

    window.URL.revokeObjectURL(url);
  } catch (error) {
    console.error("Gagal export laporan:", error);

    errorMessage.value =
      error?.response?.data?.message ??
      "Gagal mengexport laporan.";
  } finally {
    exporting.value = false;
  }
};

watch(
  () => [
    filters.value.studentId,
    filters.value.tingkat,
    filters.value.jurusan,
    filters.value.nomorKelas,
    filters.value.status,
    filters.value.tahap,
    filters.value.tanggalMulai,
    filters.value.tanggalSelesai,
  ],
  async () => {
    if (isStaff.value) {
      await loadStaffReport();
    }
  },
);

onMounted(async () => {
  if (isStaff.value) {
    await Promise.all([
      loadStudents(),
      loadStaffReport(),
    ]);
  }

  if (isStudent.value) {
    await loadStudentReport();
  }
});
</script>

<style scoped>
.report-page {
  width: 100%;
}

.page-header {
  margin-bottom: 24px;
}

.page-header h1 {
  margin: 0 0 6px;
  font-size: 28px;
  font-weight: 700;
}

.page-header p {
  margin: 0;
  color: #64748b;
}

.report-tabs {
  display: flex;
  gap: 8px;
  margin-bottom: 20px;
  padding: 6px;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  overflow-x: auto;
}

.report-tabs button {
  border: 0;
  background: transparent;
  padding: 11px 18px;
  border-radius: 8px;
  color: #64748b;
  font-weight: 600;
  cursor: pointer;
  white-space: nowrap;
}

.report-tabs button:hover {
  background: #f1f5f9;
}

.report-tabs button.active {
  background: #2563eb;
  color: #fff;
}

.filter-card,
.report-card,
.student-report-info {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  margin-bottom: 20px;
}

.filter-card {
  padding: 20px;
}

.filter-header,
.card-header,
.student-report-info {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 16px;
}

.filter-header {
  margin-bottom: 20px;
}

.filter-header h2,
.card-header h2,
.student-report-info h2 {
  margin: 0 0 5px;
  font-size: 18px;
}

.filter-header p,
.card-header p,
.student-report-info p {
  margin: 0;
  color: #64748b;
  font-size: 14px;
}

.filter-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 7px;
}

.form-group label {
  font-size: 13px;
  font-weight: 600;
  color: #334155;
}

.form-group input,
.form-group select {
  width: 100%;
  box-sizing: border-box;
  padding: 10px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  background: #fff;
  color: #334155;
  outline: none;
}

.form-group input:focus,
.form-group select:focus {
  border-color: #2563eb;
}

.btn-reset {
  border: 1px solid #cbd5e1;
  background: #fff;
  color: #475569;
  padding: 9px 15px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
}

.btn-reset:hover {
  background: #f8fafc;
}

.report-card {
  overflow: hidden;
}

.card-header {
  padding: 20px;
  border-bottom: 1px solid #e2e8f0;
}

.export-actions {
  display: flex;
  gap: 8px;
}

.btn-export {
  border: 0;
  padding: 9px 14px;
  border-radius: 8px;
  cursor: pointer;
  font-weight: 600;
}

.btn-export:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-export.excel {
  background: #dcfce7;
  color: #166534;
}

.btn-export.pdf {
  background: #fee2e2;
  color: #991b1b;
}

.table-wrapper {
  width: 100%;
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  min-width: 850px;
}

th,
td {
  padding: 14px 18px;
  text-align: left;
  border-bottom: 1px solid #e2e8f0;
  font-size: 14px;
}

th {
  background: #f8fafc;
  color: #475569;
  font-weight: 700;
  white-space: nowrap;
}

td {
  color: #334155;
}

tbody tr:hover {
  background: #f8fafc;
}

.student-cell {
  display: flex;
  align-items: center;
  gap: 10px;
  min-width: 180px;
}

.student-avatar {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: #dbeafe;
  color: #2563eb;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 12px;
  font-weight: 700;
  flex-shrink: 0;
}

.point {
  font-weight: 700;
}

.point.negative {
  color: #dc2626;
}

.point.positive {
  color: #16a34a;
}

.status-badge {
  display: inline-flex;
  align-items: center;
  padding: 5px 9px;
  border-radius: 999px;
  background: #eff6ff;
  color: #2563eb;
  font-size: 12px;
  font-weight: 600;
}

.empty-state {
  text-align: center;
  padding: 35px;
  color: #94a3b8;
}

.loading-state {
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 40px;
  text-align: center;
  color: #64748b;
  margin-bottom: 20px;
}

.error-alert {
  margin-bottom: 20px;
  padding: 13px 16px;
  border: 1px solid #fecaca;
  border-radius: 10px;
  background: #fef2f2;
  color: #b91c1c;
  font-size: 14px;
}

.student-report-info {
  padding: 20px;
}

@media (max-width: 1100px) {
  .filter-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 700px) {
  .filter-grid {
    grid-template-columns: 1fr;
  }

  .filter-header,
  .card-header,
  .student-report-info {
    align-items: flex-start;
    flex-direction: column;
  }

  .export-actions {
    width: 100%;
  }

  .btn-export {
    flex: 1;
  }

  .report-tabs {
    margin-bottom: 16px;
  }
}
</style>
