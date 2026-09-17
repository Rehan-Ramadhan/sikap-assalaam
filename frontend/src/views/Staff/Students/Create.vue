<template>
  <AppLayout>
    <div class="student-page">
      <!-- Header -->
      <div class="page-header">
        <div>
          <button type="button" class="back-btn" @click="goBack">
            <ArrowLeft :size="18" />
            Kembali
          </button>

          <h1>Tambah Siswa</h1>
          <p>Tambahkan data siswa baru ke dalam sistem SIKAP Assalaam.</p>
        </div>
      </div>

      <!-- Error -->
      <div v-if="errorMessage" class="alert alert-error" role="alert">
        <CircleAlert :size="18" />
        <span>{{ errorMessage }}</span>
      </div>

      <!-- Form -->
      <form class="form-card" @submit.prevent="saveStudent">
        <!-- Data Siswa -->
        <div class="form-section">
          <div class="section-title">
            <h2>Data Siswa</h2>
            <p>Masukkan informasi dasar siswa.</p>
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
                inputmode="numeric"
                placeholder="Contoh: 123456"
                required
              />
            </div>

            <!-- Jenis Kelamin -->
            <div class="form-group">
              <label for="jenisKelamin"> Jenis Kelamin <span>*</span> </label>

              <select id="jenisKelamin" v-model="form.jenisKelamin" required>
                <option value="" disabled>Pilih jenis kelamin</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
            </div>

            <!-- Tingkat -->
            <div class="form-group">
              <label for="tingkat"> Tingkat <span>*</span> </label>

              <select id="tingkat" v-model="form.tingkat" required>
                <option value="" disabled>Pilih tingkat</option>
                <option value="10">Kelas 10</option>
                <option value="11">Kelas 11</option>
                <option value="12">Kelas 12</option>
              </select>
            </div>

            <!-- Jurusan -->
            <div class="form-group">
              <label for="jurusan"> Jurusan <span>*</span> </label>

              <select id="jurusan" v-model="form.jurusan" required>
                <option value="" disabled>Pilih jurusan</option>
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
            <h2>Akun Siswa</h2>
            <p>Email dan password digunakan siswa untuk login ke sistem.</p>
          </div>

          <div class="form-grid">
            <!-- Email -->
            <div class="form-group">
              <label for="email"> Email <span>*</span> </label>

              <input
                id="email"
                v-model="form.email"
                type="email"
                placeholder="Contoh: siswa@email.com"
                autocomplete="email"
                required
              />
            </div>

            <!-- Password -->
            <div class="form-group">
              <label for="password"> Password <span>*</span> </label>

              <input
                id="password"
                v-model="form.password"
                type="password"
                minlength="6"
                placeholder="Minimal 6 karakter"
                autocomplete="new-password"
                required
              />
            </div>
          </div>
        </div>

        <!-- Actions -->
        <div class="form-actions">
          <button
            type="button"
            class="btn btn-secondary"
            :disabled="loading"
            @click="goBack"
          >
            Batal
          </button>

          <button type="submit" class="btn btn-primary" :disabled="loading">
            <LoaderCircle v-if="loading" :size="18" class="spin" />

            <Save v-else :size="18" />

            {{ loading ? "Menyimpan..." : "Simpan Siswa" }}
          </button>
        </div>
      </form>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

import { ArrowLeft, CircleAlert, Save, LoaderCircle } from "lucide-vue-next";

import AppLayout from "../../../layouts/AppLayout.vue";
import api from "../../../utils/api";

const router = useRouter();

const loading = ref(false);
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

const goBack = () => {
  router.push("/staff/siswa");
};

const saveStudent = async () => {
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

  if (!form.value.password) {
    errorMessage.value = "Password wajib diisi.";
    return;
  }

  if (form.value.password.length < 6) {
    errorMessage.value = "Password minimal 6 karakter.";
    return;
  }

  loading.value = true;

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
      password: form.value.password,
    };

    const response = await api.post("/staff/students", payload);

    const studentId =
      response.data?.data?.id ??
      response.data?.student?.id ??
      response.data?.id;

    if (studentId) {
      router.push(`/staff/siswa/${studentId}`);
    } else {
      router.push("/staff/siswa");
    }
  } catch (error) {
    console.error("Gagal menambahkan siswa:", error);

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
        "Terjadi kesalahan saat menambahkan siswa.";
    } else {
      errorMessage.value = "Tidak dapat terhubung ke server Laravel.";
    }
  } finally {
    loading.value = false;
  }
};
</script>

<style scoped>
/* PAGE */

.student-page {
  width: 100%;
}

/* HEADER */

.page-header {
  display: flex;
  align-items: flex-start;
  justify-content: space-between;
  gap: 20px;
  margin-bottom: 22px;
}

.page-header h1 {
  margin: 10px 0 5px;

  font-size: 25px;
  line-height: 1.2;
  font-weight: 700;

  color: #172033;
}

.page-header p {
  margin: 0;

  color: #7b8497;
  font-size: 14px;
}

/* BACK */

.back-btn {
  display: inline-flex;
  align-items: center;
  gap: 7px;

  padding: 0;

  border: none;
  background: transparent;

  color: #2563eb;

  font-size: 13px;
  font-weight: 600;

  cursor: pointer;

  transition: 0.2s ease;
}

.back-btn:hover {
  color: #1d4ed8;
  transform: translateX(-2px);
}

/* ALERT */

.alert {
  display: flex;
  align-items: center;
  gap: 10px;

  padding: 12px 14px;
  margin-bottom: 18px;

  border-radius: 10px;

  font-size: 13px;
}

.alert-error {
  background: #fef2f2;
  color: #b91c1c;
  border: 1px solid #fecaca;
}

/* FORM CARD */

.form-card {
  background: #ffffff;

  border: 1px solid #e8ebf2;
  border-radius: 13px;

  overflow: hidden;

  box-shadow: 0 3px 12px rgba(30, 41, 59, 0.04);
}

/* SECTION */

.form-section {
  padding: 20px 22px;

  border-bottom: 1px solid #edf0f4;
}

.section-title {
  margin-bottom: 18px;
}

.section-title h2 {
  margin: 0 0 4px;

  color: #172033;

  font-size: 16px;
  font-weight: 600;
}

.section-title p {
  margin: 0;

  color: #8992a5;

  font-size: 12px;
}

/* FORM GRID */

.form-grid {
  display: grid;

  grid-template-columns: repeat(2, minmax(0, 1fr));

  gap: 16px 18px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-group.full {
  grid-column: 1 / -1;
}

/* LABEL */

.form-group label {
  color: #475467;

  font-size: 12px;
  font-weight: 600;
}

.form-group label span {
  color: #dc2626;
}

/* INPUT */

.form-group input,
.form-group select {
  width: 100%;
  min-height: 40px;

  box-sizing: border-box;

  padding: 8px 11px;

  border: 1px solid #dfe3eb;
  border-radius: 9px;

  background: #ffffff;

  color: #172033;

  font-size: 13px;

  outline: none;

  transition: 0.2s ease;
}

.form-group input:hover,
.form-group select:hover {
  border-color: #cbd2df;
}

.form-group input:focus,
.form-group select:focus {
  border-color: #3b82f6;

  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.08);
}

.form-group input::placeholder {
  color: #a0a7b5;
}

/* SELECT */

.form-group select {
  cursor: pointer;
}

/* ACTION */

.form-actions {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 10px;

  padding: 16px 22px;

  background: #fafbfc;
}

/* BUTTON */

.btn {
  min-height: 40px;

  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;

  padding: 0 15px;

  border-radius: 9px;

  font-size: 13px;
  font-weight: 600;

  cursor: pointer;

  transition: 0.2s ease;
}

.btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* PRIMARY */

.btn-primary {
  border: none;

  background: #2563eb;
  color: #ffffff;
}

.btn-primary:hover:not(:disabled) {
  background: #1d4ed8;
  transform: translateY(-1px);
}

/* SECONDARY */

.btn-secondary {
  border: 1px solid #dfe3eb;

  background: #ffffff;
  color: #475467;
}

.btn-secondary:hover:not(:disabled) {
  background: #f8fafc;
  border-color: #cbd2df;
}

/* LOADING */

.spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

/* RESPONSIVE */

@media (max-width: 768px) {
  .page-header {
    flex-direction: column;
  }

  .page-header h1 {
    font-size: 23px;
  }

  .form-grid {
    grid-template-columns: 1fr;
  }

  .form-group.full {
    grid-column: auto;
  }

  .form-section {
    padding: 18px;
  }

  .form-actions {
    padding: 14px 18px;

    flex-direction: column-reverse;
  }

  .btn {
    width: 100%;
  }
}
</style>
