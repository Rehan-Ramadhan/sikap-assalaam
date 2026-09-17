<template>
  <div class="page">
    <div class="page-header">
      <div>
        <button class="back-button" @click="goBack">
          <ArrowLeft :size="17" />
          kembali
        </button>

        <p class="eyebrow">pelanggaran</p>
        <h1>tambah kategori pelanggaran</h1>
        <p class="description">
          Tambahkan kategori pelanggaran baru ke dalam sistem.
        </p>
      </div>
    </div>

    <div v-if="error" class="alert error-alert">
      <AlertCircle :size="18" />
      <span>{{ error }}</span>
    </div>

    <form class="form-card" @submit.prevent="submit">
      <div class="section">
        <div class="section-heading">
          <h2>informasi kategori</h2>
          <p>Isi informasi dasar kategori pelanggaran.</p>
        </div>

        <div class="form-grid">
          <div class="field full">
            <label for="nama_pelanggaran">
              nama pelanggaran <span>*</span>
            </label>

            <input
              id="nama_pelanggaran"
              v-model="form.nama_pelanggaran"
              type="text"
              maxlength="100"
              placeholder="contoh: terlambat masuk sekolah"
              :class="{ invalid: errors.nama_pelanggaran }"
            />

            <small v-if="errors.nama_pelanggaran">
              {{ errors.nama_pelanggaran }}
            </small>
          </div>

          <div class="field">
            <label for="kategori"> tingkat pelanggaran <span>*</span> </label>

            <select
              id="kategori"
              v-model="form.kategori"
              :class="{ invalid: errors.kategori }"
            >
              <option value="" disabled>pilih tingkat</option>
              <option value="ringan">ringan</option>
              <option value="sedang">sedang</option>
              <option value="berat">berat</option>
            </select>

            <small v-if="errors.kategori">
              {{ errors.kategori }}
            </small>
          </div>

          <div class="field">
            <label for="poin"> poin <span>*</span> </label>

            <input
              id="poin"
              v-model.number="form.poin"
              type="number"
              min="1"
              placeholder="contoh: 5"
              :class="{ invalid: errors.poin }"
            />

            <small v-if="errors.poin">
              {{ errors.poin }}
            </small>
          </div>

          <div class="field full">
            <label for="deskripsi">deskripsi</label>

            <textarea
              id="deskripsi"
              v-model="form.deskripsi"
              rows="5"
              placeholder="jelaskan detail pelanggaran jika diperlukan..."
              :class="{ invalid: errors.deskripsi }"
            ></textarea>

            <small v-if="errors.deskripsi">
              {{ errors.deskripsi }}
            </small>
          </div>

          <div class="field full">
            <div class="switch-wrapper">
              <div>
                <label>status kategori</label>
                <p>Kategori aktif dapat digunakan saat mencatat pelanggaran.</p>
              </div>

              <button
                type="button"
                class="switch"
                :class="{ active: form.status }"
                :aria-pressed="form.status"
                @click="form.status = !form.status"
              >
                <span></span>
              </button>
            </div>
          </div>
        </div>
      </div>

      <div class="form-footer">
        <button type="button" class="secondary-button" @click="goBack">
          batal
        </button>

        <button type="submit" class="primary-button" :disabled="submitting">
          <LoaderCircle v-if="submitting" class="spin" :size="18" />
          <Save v-else :size="18" />
          {{ submitting ? "menyimpan..." : "simpan kategori" }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { AlertCircle, ArrowLeft, LoaderCircle, Save } from "lucide-vue-next";

import api from "../../../../utils/api";

const router = useRouter();

const form = reactive({
  nama_pelanggaran: "",
  poin: null,
  kategori: "",
  deskripsi: "",
  status: true,
});

const errors = reactive({
  nama_pelanggaran: "",
  poin: "",
  kategori: "",
  deskripsi: "",
});

const error = ref("");
const submitting = ref(false);

const resetErrors = () => {
  Object.keys(errors).forEach((key) => {
    errors[key] = "";
  });

  error.value = "";
};

const mapValidationErrors = (validationErrors) => {
  Object.entries(validationErrors || {}).forEach(([field, messages]) => {
    if (field in errors) {
      errors[field] = Array.isArray(messages) ? messages[0] : messages;
    }
  });
};

const submit = async () => {
  resetErrors();
  submitting.value = true;

  try {
    const response = await api.post("/staff/violation-categories", {
      nama_pelanggaran: form.nama_pelanggaran.trim(),
      poin: Number(form.poin),
      kategori: form.kategori,
      deskripsi: form.deskripsi.trim() || null,
      status: Boolean(form.status),
    });

    const id = response.data?.data?.id;

    if (id) {
      router.push({
        name: "staf.pelanggaran.kategori.show",
        params: { id },
      });

      return;
    }

    router.push({
      name: "staf.pelanggaran.kategori",
    });
  } catch (err) {
    console.error("Gagal menambahkan kategori:", err);

    if (err.response?.status === 422) {
      mapValidationErrors(err.response.data?.errors);

      error.value =
        err.response.data?.message || "periksa kembali data yang dimasukkan.";
    } else {
      error.value =
        err.response?.data?.message ||
        "kategori pelanggaran gagal ditambahkan.";
    }
  } finally {
    submitting.value = false;
  }
};

const goBack = () => {
  router.push({
    name: "staf.pelanggaran.kategori",
  });
};
</script>

<style scoped>
.page {
  max-width: 1000px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 24px;
}

.back-button {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  margin-bottom: 22px;
  padding: 0;
  border: 0;
  background: transparent;
  color: #64748b;
  font-size: 13px;
  cursor: pointer;
}

.back-button:hover {
  color: #172033;
}

.eyebrow {
  margin: 0 0 6px;
  color: #64748b;
  font-size: 13px;
  font-weight: 600;
}

.page-header h1 {
  margin: 0;
  color: #172033;
  font-size: 28px;
  font-weight: 700;
  letter-spacing: -0.03em;
}

.description {
  margin: 8px 0 0;
  color: #64748b;
  font-size: 14px;
}

.alert {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 20px;
  padding: 13px 15px;
  border-radius: 10px;
  font-size: 14px;
}

.error-alert {
  border: 1px solid #fecaca;
  background: #fef2f2;
  color: #b91c1c;
}

.form-card {
  overflow: hidden;
  border: 1px solid #e5e7eb;
  border-radius: 14px;
  background: #fff;
}

.section {
  padding: 24px;
}

.section-heading {
  margin-bottom: 22px;
}

.section-heading h2 {
  margin: 0;
  color: #172033;
  font-size: 17px;
}

.section-heading p {
  margin: 5px 0 0;
  color: #64748b;
  font-size: 13px;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 20px;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 7px;
}

.field.full {
  grid-column: 1 / -1;
}

.field label {
  color: #334155;
  font-size: 13px;
  font-weight: 600;
}

.field label span {
  color: #dc2626;
}

.field input,
.field select,
.field textarea {
  width: 100%;
  box-sizing: border-box;
  border: 1px solid #dbe0e6;
  border-radius: 9px;
  outline: 0;
  color: #172033;
  background: #fff;
  font-family: inherit;
  font-size: 14px;
  transition: 0.2s ease;
}

.field input,
.field select {
  height: 42px;
  padding: 0 12px;
}

.field textarea {
  resize: vertical;
  padding: 11px 12px;
  line-height: 1.5;
}

.field input:focus,
.field select:focus,
.field textarea:focus {
  border-color: #93c5fd;
  box-shadow: 0 0 0 3px #eff6ff;
}

.field input.invalid,
.field select.invalid,
.field textarea.invalid {
  border-color: #fca5a5;
}

.field small {
  color: #dc2626;
  font-size: 12px;
}

.switch-wrapper {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 20px;
  padding: 14px;
  border: 1px solid #e5e7eb;
  border-radius: 10px;
  background: #fafbfc;
}

.switch-wrapper label {
  display: block;
  margin-bottom: 4px;
}

.switch-wrapper p {
  margin: 0;
  color: #64748b;
  font-size: 12px;
  font-weight: 400;
}

.switch {
  position: relative;
  width: 46px;
  height: 26px;
  flex-shrink: 0;
  padding: 0;
  border: 0;
  border-radius: 999px;
  background: #cbd5e1;
  cursor: pointer;
  transition: 0.2s ease;
}

.switch span {
  position: absolute;
  top: 4px;
  left: 4px;
  width: 18px;
  height: 18px;
  border-radius: 50%;
  background: #fff;
  transition: 0.2s ease;
}

.switch.active {
  background: #2563eb;
}

.switch.active span {
  left: 24px;
}

.form-footer {
  display: flex;
  align-items: center;
  justify-content: flex-end;
  gap: 10px;
  padding: 18px 24px;
  border-top: 1px solid #e5e7eb;
  background: #fafbfc;
}

.primary-button,
.secondary-button {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  gap: 8px;
  min-height: 42px;
  padding: 0 16px;
  border-radius: 9px;
  font-size: 14px;
  font-weight: 600;
  cursor: pointer;
}

.primary-button {
  border: 0;
  background: #2563eb;
  color: #fff;
}

.primary-button:hover:not(:disabled) {
  background: #1d4ed8;
}

.primary-button:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

.secondary-button {
  border: 1px solid #dbe0e6;
  background: #fff;
  color: #475569;
}

.secondary-button:hover {
  background: #f8fafc;
}

.spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  to {
    transform: rotate(360deg);
  }
}

@media (max-width: 700px) {
  .form-grid {
    grid-template-columns: 1fr;
  }

  .field.full {
    grid-column: auto;
  }

  .section {
    padding: 18px;
  }

  .form-footer {
    padding: 16px 18px;
  }
}
</style>
