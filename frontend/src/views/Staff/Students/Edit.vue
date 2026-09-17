<template>
  <AppLayout>
    <div class="student-page">
      <!-- Header -->
      <div class="page-header">
        <div>
          <button class="back-btn" @click="goBack" :disabled="saving">
            <ArrowLeft :size="18" />
            Kembali
          </button>

          <h1>Edit Data Siswa</h1>
          <p>Perbarui informasi siswa yang tersimpan di sistem.</p>
        </div>
      </div>

      <!-- Error -->
      <div v-if="errorMessage" class="alert alert-error">
        <CircleAlert :size="18" />
        <span>{{ errorMessage }}</span>
      </div>

      <!-- Loading -->
      <div v-if="loading" class="state-card">
        <LoaderCircle :size="20" class="loading-icon" />
        <span>Memuat data siswa...</span>
      </div>

      <!-- Form -->
      <form v-else class="form-card" @submit.prevent="updateStudent">
        <!-- Data Siswa -->
        <div class="form-section">
          <div class="section-title">
            <div class="section-icon">
              <GraduationCap :size="19" />
            </div>

            <div>
              <h2>Data Siswa</h2>
              <p>Perbarui informasi dasar siswa.</p>
            </div>
          </div>

          <div class="form-grid">
            <!-- Nama -->
            <div class="form-group full">
              <label for="nama"> Nama Lengkap <span>*</span> </label>

              <input
                id="nama"
                v-model="form.nama"
                type="text"
                placeholder="Masukkan nama lengkap"
                autocomplete="name"
                required
              />
            </div>

            <!-- NIS -->
            <div class="form-group">
              <label for="nis"> NIS <span>*</span> </label>

              <input
                id="nis"
                v-model="form.nis"
                type="text"
                placeholder="Masukkan NIS"
                required
              />
            </div>

            <!-- Jenis Kelamin -->
            <div class="form-group">
              <label for="jenisKelamin"> Jenis Kelamin <span>*</span> </label>

              <select id="jenisKelamin" v-model="form.jenisKelamin" required>
                <option value="">Pilih jenis kelamin</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
            </div>

            <!-- Tingkat -->
            <div class="form-group">
              <label for="tingkat"> Tingkat <span>*</span> </label>

              <select id="tingkat" v-model="form.tingkat" required>
                <option value="">Pilih tingkat</option>
                <option value="10">Kelas 10</option>
                <option value="11">Kelas 11</option>
                <option value="12">Kelas 12</option>
              </select>
            </div>

            <!-- Jurusan -->
            <div class="form-group">
              <label for="jurusan"> Jurusan <span>*</span> </label>

              <select id="jurusan" v-model="form.jurusan" required>
                <option value="">Pilih jurusan</option>
                <option value="RPL">RPL</option>
                <option value="TSM">TSM</option>
                <option value="TKR">TKR</option>
              </select>
            </div>

            <!-- Nomor Kelas -->
            <div class="form-group">
              <label for="nomorKelas"> Nomor Kelas <span>*</span> </label>

              <input
                id="nomorKelas"
                v-model="form.nomorKelas"
                type="number"
                min="1"
                placeholder="Contoh: 1"
                required
              />
            </div>

            <!-- Tahun Masuk -->
            <div class="form-group">
              <label for="tahunMasuk"> Tahun Masuk <span>*</span> </label>

              <input
                id="tahunMasuk"
                v-model="form.tahunMasuk"
                type="number"
                min="2000"
                max="2100"
                placeholder="Contoh: 2026"
                required
              />
            </div>

            <!-- Status -->
            <div class="form-group">
              <label for="status"> Status <span>*</span> </label>

              <select id="status" v-model="form.status" required>
                <option value="aktif">Aktif</option>
                <option value="nonaktif">Nonaktif</option>
                <option value="lulus">Lulus</option>
              </select>
            </div>
          </div>
        </div>

        <!-- Akun -->
        <div class="form-section">
          <div class="section-title">
            <div class="section-icon">
              <LockKeyhole :size="18" />
            </div>

            <div>
              <h2>Akun Siswa</h2>
              <p>Perbarui email atau password akun siswa.</p>
            </div>
          </div>

          <div class="form-grid">
            <!-- Email -->
            <div class="form-group">
              <label for="email"> Email <span>*</span> </label>

              <input
                id="email"
                v-model="form.email"
                type="email"
                placeholder="Masukkan email"
                autocomplete="email"
                required
              />
            </div>

            <!-- Password -->
            <div class="form-group">
              <label for="password"> Password Baru </label>

              <input
                id="password"
                v-model="form.password"
                type="password"
                minlength="6"
                placeholder="Kosongkan jika tidak diubah"
                autocomplete="new-password"
              />

              <small> Kosongkan jika password tidak ingin diubah. </small>
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
          <button
            type="button"
            class="btn btn-secondary"
            @click="goBack"
            :disabled="saving"
          >
            Batal
          </button>

          <button type="submit" class="btn btn-primary" :disabled="saving">
            <LoaderCircle v-if="saving" :size="17" class="loading-icon" />

            <Save v-else :size="18" />

            {{ saving ? "Menyimpan..." : "Simpan Perubahan" }}
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { onMounted, ref } from "vue";
import { useRoute, useRouter } from "vue-router";

import {
  ArrowLeft,
  CircleAlert,
  Save,
  GraduationCap,
  LockKeyhole,
  LoaderCircle,
} from "lucide-vue-next";

import AppLayout from "../../../layouts/AppLayout.vue";
import api from "../../../utils/api";

const route = useRoute();
const router = useRouter();

const studentId = route.params.id;

const loading = ref(true);
const saving = ref(false);
const errorMessage = ref("");

const form = ref({
  nama: "",
  nis: "",
  tingkat: "",
  jurusan: "",
  nomorKelas: "",
  jenisKelamin: "",
  tahunMasuk: "",
  status: "aktif",
  email: "",
  password: "",
});

const normalizeForm = (data) => {
  return {
    nama: data?.user?.name ?? data?.name ?? data?.nama ?? "",
    nis: data?.nis ?? "",
    tingkat: String(data?.tingkat ?? ""),
    jurusan: data?.jurusan ?? "",
    nomorKelas: data?.nomor_kelas ?? data?.nomorKelas ?? "",
    jenisKelamin:
      data?.jenis_kelamin ??
      data?.jenisKelamin ??
      data?.user?.jenis_kelamin ??
      "",
    tahunMasuk: data?.tahun_masuk ?? data?.tahunMasuk ?? "",
    status: data?.status ?? "aktif",
    email: data?.user?.email ?? data?.email ?? "",
    password: "",
  };
};

const fetchStudent = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await api.get(`/staff/students/${studentId}`);

    const data = response.data?.data ?? response.data?.student ?? response.data;

    form.value = normalizeForm(data);
  } catch (error) {
    console.error("Gagal mengambil data siswa:", error);

    errorMessage.value =
      error.response?.data?.message ||
      "Data siswa tidak ditemukan atau gagal diambil dari server Laravel.";
  } finally {
    loading.value = false;
  }
};

onMounted(fetchStudent);

const goBack = () => {
  router.push(`/staff/siswa/${studentId}`);
};

const updateStudent = async () => {
  errorMessage.value = "";

  if (!form.value.nama.trim()) {
    errorMessage.value = "Nama lengkap wajib diisi.";
    return;
  }

  if (!form.value.nis.trim()) {
    errorMessage.value = "NIS wajib diisi.";
    return;
  }

  if (!form.value.tingkat) {
    errorMessage.value = "Tingkat wajib dipilih.";
    return;
  }

  if (!form.value.jurusan) {
    errorMessage.value = "Jurusan wajib dipilih.";
    return;
  }

  if (!form.value.nomorKelas) {
    errorMessage.value = "Nomor kelas wajib diisi.";
    return;
  }

  if (!form.value.jenisKelamin) {
    errorMessage.value = "Jenis kelamin wajib dipilih.";
    return;
  }

  if (!form.value.tahunMasuk) {
    errorMessage.value = "Tahun masuk wajib diisi.";
    return;
  }

  if (!form.value.email.trim()) {
    errorMessage.value = "Email wajib diisi.";
    return;
  }

  if (form.value.password && form.value.password.length < 6) {
    errorMessage.value = "Password baru minimal 6 karakter.";
    return;
  }

  saving.value = true;

  try {
    const payload = {
      name: form.value.nama.trim(),
      nis: form.value.nis.trim(),
      tingkat: form.value.tingkat,
      jurusan: form.value.jurusan,
      nomor_kelas: Number(form.value.nomorKelas),
      jenis_kelamin: form.value.jenisKelamin,
      tahun_masuk: Number(form.value.tahunMasuk),
      status: form.value.status,
      email: form.value.email.trim(),
    };

    if (form.value.password) {
      payload.password = form.value.password;
    }

    await api.put(`/staff/students/${studentId}`, payload);

    router.push(`/staff/siswa/${studentId}`);
  } catch (error) {
    console.error("Gagal memperbarui siswa:", error);

    if (error.response?.status === 422) {
      const errors = error.response.data?.errors;

      if (errors) {
        const firstError = Object.values(errors)?.[0]?.[0];

        errorMessage.value =
          firstError || "Data siswa yang dimasukkan tidak valid.";
      } else {
        errorMessage.value =
          error.response.data?.message ||
          "Data siswa yang dimasukkan tidak valid.";
      }
    } else if (error.response) {
      errorMessage.value =
        error.response.data?.message ||
        "Terjadi kesalahan saat memperbarui data siswa.";
    } else {
      errorMessage.value = "Tidak dapat terhubung ke server Laravel.";
    }
  } finally {
    saving.value = false;
  }
};
</script>

<style scoped>
.student-page {
  width: 100%;
}

/* Header */
.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 24px;
}

.page-header h1 {
  margin: 12px 0 6px;
  font-size: 28px;
  font-weight: 600;
  letter-spacing: -0.02em;
  color: #172033;
}

.page-header p {
  margin: 0;
  color: #6b7280;
  font-size: 14px;
}

/* Back */
.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  border: none;
  background: transparent;
  color: #64748b;
  padding: 0;
  cursor: pointer;
  font-size: 14px;
  font-weight: 600;
}

.back-btn:hover:not(:disabled) {
  color: #2563eb;
}

.back-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* Alert */
.alert {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 13px 16px;
  border-radius: 10px;
  margin-bottom: 20px;
  font-size: 14px;
}

.alert-error {
  background: #fef2f2;
  color: #b91c1c;
  border: 1px solid #fecaca;
}

/* State */
.state-card {
  min-height: 180px;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 24px;
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  color: #64748b;
  font-size: 14px;
}

.loading-icon {
  animation: spin 1s linear infinite;
}

/* Form Card */
.form-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  overflow: hidden;
}

/* Section */
.form-section {
  padding: 24px;
  border-bottom: 1px solid #eef0f4;
}

.section-title {
  display: flex;
  align-items: center;
  gap: 11px;
  margin-bottom: 22px;
}

.section-icon {
  width: 38px;
  height: 38px;
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 10px;
  background: #eff6ff;
  color: #2563eb;
}

.section-title h2 {
  margin: 0 0 4px;
  font-size: 17px;
  font-weight: 600;
  color: #334155;
}

.section-title p {
  margin: 0;
  color: #94a3b8;
  font-size: 12px;
}

/* Form Grid */
.form-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 18px 20px;
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
  font-size: 13px;
  font-weight: 600;
  color: #475569;
}

.form-group label span {
  color: #dc2626;
}

.form-group input,
.form-group select {
  width: 100%;
  box-sizing: border-box;
  min-height: 43px;
  padding: 10px 12px;
  border: 1px solid #d7dce5;
  border-radius: 9px;
  background: #ffffff;
  color: #172033;
  font-size: 14px;
  outline: none;
  transition: 0.2s ease;
}

.form-group input:focus,
.form-group select:focus {
  border-color: #3b82f6;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-group input::placeholder {
  color: #9ca3af;
}

.form-group small {
  color: #94a3b8;
  font-size: 12px;
}

/* Actions */
.form-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
  padding: 18px 24px;
  background: #fafbfc;
}

.btn {
  min-height: 42px;
  padding: 0 16px;
  border-radius: 9px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  border: none;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
  transition: 0.2s ease;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.btn-primary {
  background: #2563eb;
  color: #ffffff;
}

.btn-primary:hover:not(:disabled) {
  background: #1d4ed8;
}

.btn-secondary {
  background: #ffffff;
  color: #374151;
  border: 1px solid #d7dce5;
}

.btn-secondary:hover:not(:disabled) {
  background: #f3f4f6;
}

.btn .loading-icon {
  width: 17px;
  height: 17px;
}

/* Animation */
@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* Responsive */
@media (max-width: 768px) {
  .page-header h1 {
    font-size: 24px;
  }

  .form-grid {
    grid-template-columns: 1fr;
  }

  .form-group.full {
    grid-column: auto;
  }

  .form-section {
    padding: 20px;
  }

  .form-actions {
    padding: 16px 20px;
    flex-direction: column-reverse;
  }

  .btn {
    width: 100%;
  }
}
</style>
