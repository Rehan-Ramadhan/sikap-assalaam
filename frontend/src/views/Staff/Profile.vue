<template>
  <div class="profile-page">
    <!-- HEADER -->
    <div class="page-header">
      <div>
        <h1>Profil</h1>
        <p>Kelola informasi akun dan profil kamu.</p>
      </div>
    </div>

    <!-- LOADING -->
    <div v-if="loading" class="loading-state">
      <div class="loading-spinner"></div>
      <span>Memuat profil...</span>
    </div>

    <!-- CONTENT -->
    <div v-else class="profile-grid">
      <!-- PROFILE CARD -->
      <section class="profile-card profile-summary">
        <div class="profile-cover"></div>

        <div class="profile-summary-content">
          <div class="profile-avatar">
            <User :size="32" />
          </div>

          <div class="profile-name">
            <h2>{{ profile.name || "Staf" }}</h2>
            <span>{{ positionLabel }}</span>
          </div>

          <div class="profile-meta">
            <div class="meta-item">
              <Mail :size="16" />

              <div>
                <span>Email</span>
                <strong>{{ profile.email || "-" }}</strong>
              </div>
            </div>

            <div class="meta-item">
              <ShieldCheck :size="16" />

              <div>
                <span>Role</span>
                <strong>
                  {{ profile.role === "staff" ? "Staf" : profile.role || "-" }}
                </strong>
              </div>
            </div>

            <div class="meta-item">
              <UserRound :size="16" />

              <div>
                <span>Jenis Kelamin</span>
                <strong>{{ genderLabel }}</strong>
              </div>
            </div>
          </div>
        </div>
      </section>

      <!-- FORM CARD -->
      <section class="profile-card profile-form-card">
        <div class="card-header">
          <div>
            <h2>Informasi Profil</h2>
            <p>Perbarui informasi akun kamu.</p>
          </div>
        </div>

        <form class="profile-form" @submit.prevent="updateProfile">
          <!-- NAME -->
          <div class="form-group">
            <label for="name"> Nama Lengkap </label>

            <div class="input-wrapper">
              <User :size="17" />

              <input
                id="name"
                v-model="form.name"
                type="text"
                placeholder="Masukkan nama lengkap"
                :disabled="saving"
              />
            </div>
          </div>

          <!-- EMAIL -->
          <div class="form-group">
            <label for="email"> Email </label>

            <div class="input-wrapper">
              <Mail :size="17" />

              <input
                id="email"
                v-model="form.email"
                type="email"
                placeholder="Masukkan email"
                :disabled="saving"
              />
            </div>
          </div>

          <!-- GENDER -->
          <div class="form-group">
            <label for="jenis_kelamin"> Jenis Kelamin </label>

            <div class="input-wrapper">
              <Users :size="17" />

              <select
                id="jenis_kelamin"
                v-model="form.jenis_kelamin"
                :disabled="saving"
              >
                <option value="">Pilih jenis kelamin</option>

                <option value="L">Laki-laki</option>

                <option value="P">Perempuan</option>
              </select>
            </div>
          </div>

          <!-- PASSWORD SECTION -->
          <div class="password-section">
            <div class="section-title">
              <div>
                <h3>Ubah Password</h3>
                <p>Kosongkan jika tidak ingin mengubah password.</p>
              </div>
            </div>

            <!-- PASSWORD -->
            <div class="form-group">
              <label for="password"> Password Baru </label>

              <div class="input-wrapper">
                <Lock :size="17" />

                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="Masukkan password baru"
                  :disabled="saving"
                />

                <button
                  type="button"
                  class="password-toggle"
                  :title="
                    showPassword ? 'Sembunyikan password' : 'Tampilkan password'
                  "
                  @click="showPassword = !showPassword"
                >
                  <EyeOff v-if="showPassword" :size="17" />

                  <Eye v-else :size="17" />
                </button>
              </div>
            </div>

            <!-- PASSWORD CONFIRMATION -->
            <div class="form-group">
              <label for="password_confirmation"> Konfirmasi Password </label>

              <div class="input-wrapper">
                <Lock :size="17" />

                <input
                  id="password_confirmation"
                  v-model="form.password_confirmation"
                  :type="showPassword ? 'text' : 'password'"
                  placeholder="Konfirmasi password baru"
                  :disabled="saving"
                />
              </div>
            </div>
          </div>

          <!-- ERROR -->
          <div v-if="errorMessage" class="alert alert-error">
            <AlertCircle :size="17" />

            <span>
              {{ errorMessage }}
            </span>
          </div>

          <!-- SUCCESS -->
          <div v-if="successMessage" class="alert alert-success">
            <CheckCircle :size="17" />

            <span>
              {{ successMessage }}
            </span>
          </div>

          <!-- ACTION -->
          <div class="form-actions">
            <button
              type="button"
              class="btn btn-secondary"
              :disabled="saving"
              @click="resetForm"
            >
              Batal
            </button>

            <button type="submit" class="btn btn-primary" :disabled="saving">
              <Loader2 v-if="saving" class="spin" :size="17" />

              <Save v-else :size="17" />

              <span>
                {{ saving ? "Menyimpan..." : "Simpan Perubahan" }}
              </span>
            </button>
          </div>
        </form>
      </section>
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from "vue";

import {
  User,
  UserRound,
  Users,
  Mail,
  Lock,
  Eye,
  EyeOff,
  ShieldCheck,
  Save,
  Loader2,
  AlertCircle,
  CheckCircle,
} from "lucide-vue-next";

import api from "../../utils/api";

/*
|--------------------------------------------------------------------------
| STATE
|--------------------------------------------------------------------------
*/

const loading = ref(true);

const saving = ref(false);

const errorMessage = ref("");

const successMessage = ref("");

const showPassword = ref(false);

const profile = reactive({
  id: null,
  name: "",
  email: "",
  jenis_kelamin: "",
  role: "",
  student: null,
  staff: null,
});

const form = reactive({
  name: "",
  email: "",
  jenis_kelamin: "",
  password: "",
  password_confirmation: "",
});

/*
|--------------------------------------------------------------------------
| POSITION
|--------------------------------------------------------------------------
*/

const positionNames = {
  wali_kelas: "Wali Kelas",
  bk: "BK",
  kesiswaan: "Kesiswaan",
  kepala_sekolah: "Kepala Sekolah",
};

const positionLabel = computed(() => {
  if (profile.role === "student") {
    return "Siswa";
  }

  const jabatan = profile.staff?.jabatan;

  return positionNames[jabatan] || "Staf";
});

/*
|--------------------------------------------------------------------------
| GENDER
|--------------------------------------------------------------------------
*/

const genderLabel = computed(() => {
  if (profile.jenis_kelamin === "L") {
    return "Laki-laki";
  }

  if (profile.jenis_kelamin === "P") {
    return "Perempuan";
  }

  return "-";
});

/*
|--------------------------------------------------------------------------
| LOAD PROFILE
|--------------------------------------------------------------------------
*/

const fetchProfile = async () => {
  loading.value = true;
  errorMessage.value = "";

  try {
    const response = await api.get("/profile");

    const data = response?.data?.data || {};

    Object.assign(profile, data);

    Object.assign(form, {
      name: data.name || "",
      email: data.email || "",
      jenis_kelamin: data.jenis_kelamin || "",
      password: "",
      password_confirmation: "",
    });
  } catch (error) {
    console.error("Gagal mengambil profil:", error);

    errorMessage.value =
      error?.response?.data?.message || "Gagal memuat profil.";
  } finally {
    loading.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| RESET FORM
|--------------------------------------------------------------------------
*/

const resetForm = () => {
  Object.assign(form, {
    name: profile.name || "",
    email: profile.email || "",
    jenis_kelamin: profile.jenis_kelamin || "",
    password: "",
    password_confirmation: "",
  });

  errorMessage.value = "";
  successMessage.value = "";
};

/*
|--------------------------------------------------------------------------
| UPDATE PROFILE
|--------------------------------------------------------------------------
*/

const updateProfile = async () => {
  saving.value = true;

  errorMessage.value = "";
  successMessage.value = "";

  try {
    const payload = {
      name: form.name.trim(),
      email: form.email.trim(),
      jenis_kelamin: form.jenis_kelamin,
    };

    /*
     * Password hanya dikirim
     * jika memang diisi.
     */
    if (form.password) {
      payload.password = form.password;

      payload.password_confirmation = form.password_confirmation;
    }

    const response = await api.put("/profile", payload);

    const data = response?.data?.data || {};

    Object.assign(profile, data);

    form.password = "";
    form.password_confirmation = "";

    successMessage.value =
      response?.data?.message || "Profil berhasil diperbarui.";
  } catch (error) {
    console.error("Gagal memperbarui profil:", error);

    const validationErrors = error?.response?.data?.errors;

    if (validationErrors) {
      const firstError = Object.values(validationErrors)[0];

      if (Array.isArray(firstError) && firstError.length) {
        errorMessage.value = firstError[0];
      } else {
        errorMessage.value = "Data profil tidak valid.";
      }
    } else {
      errorMessage.value =
        error?.response?.data?.message || "Gagal memperbarui profil.";
    }
  } finally {
    saving.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| LIFECYCLE
|--------------------------------------------------------------------------
*/

onMounted(() => {
  fetchProfile();
});
</script>

<style scoped>
/* ========================================
   PAGE
======================================== */

.profile-page {
  padding: 28px;
}

/* ========================================
   HEADER
======================================== */

.page-header {
  margin-bottom: 24px;
}

.page-header h1 {
  margin: 0;
  color: #172033;
  font-size: 22px;
  font-weight: 700;
  letter-spacing: -0.3px;
}

.page-header p {
  margin: 5px 0 0;
  color: #8a94a6;
  font-size: 13px;
}

/* ========================================
   GRID
======================================== */

.profile-grid {
  display: grid;
  grid-template-columns:
    minmax(260px, 0.75fr)
    minmax(450px, 1.25fr);
  gap: 22px;
  align-items: start;
}

/* ========================================
   CARD
======================================== */

.profile-card {
  overflow: hidden;
  background: #ffffff;
  border: 1px solid #e8edf5;
  border-radius: 14px;
  box-shadow: 0 2px 8px rgba(15, 23, 42, 0.025);
}

/* ========================================
   PROFILE SUMMARY
======================================== */

.profile-cover {
  height: 100px;

  background: linear-gradient(135deg, #2563eb, #3b82f6);
}

.profile-summary-content {
  padding: 0 22px 24px;
}

/* ========================================
   AVATAR
======================================== */

.profile-avatar {
  width: 68px;
  height: 68px;

  display: flex;
  align-items: center;
  justify-content: center;

  margin-top: -34px;
  margin-bottom: 13px;

  border: 4px solid #ffffff;
  border-radius: 18px;

  background: linear-gradient(135deg, #2563eb, #3b82f6);

  color: #ffffff;

  box-shadow: 0 5px 15px rgba(37, 99, 235, 0.2);
}

/* ========================================
   NAME
======================================== */

.profile-name h2 {
  margin: 0;

  color: #172033;

  font-size: 18px;
  font-weight: 700;
}

.profile-name span {
  display: inline-block;

  margin-top: 4px;

  color: #64748b;

  font-size: 12px;
  font-weight: 500;
}

/* ========================================
   PROFILE META
======================================== */

.profile-meta {
  display: flex;
  flex-direction: column;

  gap: 15px;

  margin-top: 25px;
  padding-top: 20px;

  border-top: 1px solid #edf1f6;
}

.meta-item {
  display: flex;
  align-items: flex-start;

  gap: 11px;

  color: #64748b;
}

.meta-item > svg {
  flex-shrink: 0;

  margin-top: 2px;

  color: #2563eb;
}

.meta-item div {
  display: flex;
  flex-direction: column;

  min-width: 0;
}

.meta-item span {
  color: #9aa4b3;
  font-size: 10px;
}

.meta-item strong {
  margin-top: 3px;

  overflow: hidden;

  text-overflow: ellipsis;
  white-space: nowrap;

  color: #334155;

  font-size: 12px;
  font-weight: 600;
}

/* ========================================
   FORM CARD
======================================== */

.profile-form-card {
  padding: 24px;
}

.card-header {
  padding-bottom: 20px;
  border-bottom: 1px solid #edf1f6;
}

.card-header h2 {
  margin: 0;

  color: #172033;

  font-size: 15px;
  font-weight: 700;
}

.card-header p {
  margin: 4px 0 0;

  color: #8a94a6;

  font-size: 11px;
}

/* ========================================
   FORM
======================================== */

.profile-form {
  padding-top: 20px;
}

.form-group {
  margin-bottom: 18px;
}

.form-group label {
  display: block;

  margin-bottom: 7px;

  color: #334155;

  font-size: 11px;
  font-weight: 650;
}

/* ========================================
   INPUT
======================================== */

.input-wrapper {
  height: 42px;

  display: flex;
  align-items: center;

  padding: 0 12px;

  border: 1px solid #e1e7ef;
  border-radius: 9px;

  background: #ffffff;

  color: #94a0b2;

  transition:
    border-color 0.2s ease,
    box-shadow 0.2s ease;
}

.input-wrapper:focus-within {
  border-color: #b9d2ff;

  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.07);
}

.input-wrapper input,
.input-wrapper select {
  flex: 1;

  width: 100%;
  height: 100%;

  margin-left: 9px;

  border: none;
  outline: none;

  background: transparent;

  color: #334155;

  font-size: 12px;
}

.input-wrapper input::placeholder {
  color: #a0a9b8;
}

.input-wrapper select {
  cursor: pointer;
}

/* ========================================
   PASSWORD
======================================== */

.password-section {
  margin-top: 24px;
  padding-top: 21px;

  border-top: 1px solid #edf1f6;
}

.section-title {
  margin-bottom: 18px;
}

.section-title h3 {
  margin: 0;

  color: #334155;

  font-size: 13px;
  font-weight: 700;
}

.section-title p {
  margin: 4px 0 0;

  color: #9aa4b3;

  font-size: 10px;
}

/* ========================================
   PASSWORD TOGGLE
======================================== */

.password-toggle {
  width: 30px;
  height: 30px;

  display: flex;
  align-items: center;
  justify-content: center;

  flex-shrink: 0;

  border: none;
  border-radius: 6px;

  background: transparent;

  color: #94a0b2;

  cursor: pointer;
}

.password-toggle:hover {
  background: #f5f7fa;
  color: #475569;
}

/* ========================================
   ALERT
======================================== */

.alert {
  display: flex;
  align-items: center;

  gap: 8px;

  margin-top: 8px;
  padding: 10px 12px;

  border-radius: 8px;

  font-size: 11px;
}

.alert-error {
  background: #fff1f2;
  border: 1px solid #fecdd3;
  color: #be123c;
}

.alert-success {
  background: #ecfdf5;
  border: 1px solid #a7f3d0;
  color: #047857;
}

/* ========================================
   ACTION
======================================== */

.form-actions {
  display: flex;
  align-items: center;
  justify-content: flex-end;

  gap: 9px;

  margin-top: 22px;
  padding-top: 20px;

  border-top: 1px solid #edf1f6;
}

.btn {
  height: 38px;

  display: inline-flex;
  align-items: center;
  justify-content: center;

  gap: 7px;

  padding: 0 14px;

  border-radius: 8px;

  font-size: 11px;
  font-weight: 600;

  cursor: pointer;

  transition:
    background 0.2s ease,
    border-color 0.2s ease,
    color 0.2s ease,
    opacity 0.2s ease;
}

.btn:disabled {
  opacity: 0.55;
  cursor: not-allowed;
}

.btn-secondary {
  border: 1px solid #e1e7ef;
  background: #ffffff;
  color: #64748b;
}

.btn-secondary:hover:not(:disabled) {
  background: #f8fafc;
  border-color: #d5dce6;
}

.btn-primary {
  border: 1px solid #2563eb;
  background: #2563eb;
  color: #ffffff;

  box-shadow: 0 3px 8px rgba(37, 99, 235, 0.16);
}

.btn-primary:hover:not(:disabled) {
  background: #1d4ed8;
  border-color: #1d4ed8;
}

/* ========================================
   LOADING
======================================== */

.loading-state {
  min-height: 300px;

  display: flex;
  flex-direction: column;

  align-items: center;
  justify-content: center;

  gap: 10px;

  color: #94a0b2;

  font-size: 12px;
}

.loading-spinner {
  width: 25px;
  height: 25px;

  border: 2px solid #e5eaf2;
  border-top-color: #2563eb;

  border-radius: 50%;

  animation: profile-spin 0.7s linear infinite;
}

@keyframes profile-spin {
  to {
    transform: rotate(360deg);
  }
}

.spin {
  animation: profile-spin 0.7s linear infinite;
}

/* ========================================
   RESPONSIVE
======================================== */

@media (max-width: 900px) {
  .profile-grid {
    grid-template-columns: 1fr;
  }
}

@media (max-width: 600px) {
  .profile-page {
    padding: 20px 16px;
  }

  .profile-form-card {
    padding: 18px;
  }

  .profile-summary-content {
    padding-left: 18px;
    padding-right: 18px;
  }

  .form-actions {
    flex-direction: column-reverse;
  }

  .btn {
    width: 100%;
  }
}
</style>
