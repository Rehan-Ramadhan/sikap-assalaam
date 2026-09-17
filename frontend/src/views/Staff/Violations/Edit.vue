<template>
  <div class="space-y-6">
    <!-- Header -->
    <div>
      <button
        type="button"
        class="mb-4 inline-flex items-center gap-2 text-sm font-medium text-gray-500 transition hover:text-gray-900"
        @click="goBack"
      >
        <ArrowLeft class="h-4 w-4" />
        Kembali
      </button>

      <h1 class="text-2xl font-bold text-gray-900">Edit Pelanggaran</h1>

      <p class="mt-1 text-sm text-gray-500">Perbarui data pelanggaran siswa.</p>
    </div>

    <!-- Loading -->
    <div
      v-if="loading"
      class="flex min-h-[300px] items-center justify-center rounded-xl border border-gray-200 bg-white"
    >
      <div class="flex items-center gap-2 text-sm text-gray-500">
        <Loader2 class="h-5 w-5 animate-spin" />
        Memuat data...
      </div>
    </div>

    <!-- Error -->
    <div
      v-else-if="error"
      class="rounded-xl border border-red-200 bg-red-50 px-5 py-4"
    >
      <div class="flex items-start gap-3">
        <CircleAlert class="mt-0.5 h-5 w-5 shrink-0 text-red-600" />

        <div>
          <p class="text-sm font-medium text-red-800">Terjadi kesalahan</p>

          <p class="mt-1 text-sm text-red-700">
            {{ error }}
          </p>
        </div>
      </div>
    </div>

    <!-- Content -->
    <template v-else>
      <!-- Cancelled warning -->
      <div
        v-if="form.status === 'dibatalkan'"
        class="rounded-xl border border-red-200 bg-red-50 px-5 py-4"
      >
        <div class="flex items-start gap-3">
          <Ban class="mt-0.5 h-5 w-5 shrink-0 text-red-600" />

          <div>
            <p class="text-sm font-semibold text-red-800">
              Pelanggaran sudah dibatalkan
            </p>

            <p class="mt-1 text-sm text-red-700">
              Pelanggaran yang sudah dibatalkan tidak dapat diubah.
            </p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Form -->
        <div class="lg:col-span-2">
          <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-5 py-4">
              <h2 class="font-semibold text-gray-900">Informasi Pelanggaran</h2>

              <p class="mt-1 text-sm text-gray-500">
                Ubah informasi yang diperlukan.
              </p>
            </div>

            <form class="space-y-5 p-5" @submit.prevent="submit">
              <!-- Student -->
              <div>
                <label class="mb-2 block text-sm font-medium text-gray-700">
                  Siswa
                </label>

                <div
                  class="rounded-lg border border-gray-200 bg-gray-50 px-4 py-3"
                >
                  <p class="text-sm font-medium text-gray-900">
                    {{ form.studentName || "-" }}
                  </p>

                  <p class="mt-1 text-xs text-gray-500">
                    NIS: {{ form.nis || "-" }}
                  </p>
                </div>
              </div>

              <!-- Category -->
              <div>
                <label
                  for="category"
                  class="mb-2 block text-sm font-medium text-gray-700"
                >
                  Kategori Pelanggaran
                  <span class="text-red-500">*</span>
                </label>

                <select
                  id="category"
                  v-model="form.categoryId"
                  :disabled="form.status === 'dibatalkan'"
                  required
                  class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-gray-100"
                >
                  <option value="" disabled>Pilih kategori pelanggaran</option>

                  <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="String(category.id)"
                  >
                    {{ category.name }} · {{ category.points }} poin
                  </option>
                </select>

                <p v-if="selectedCategory" class="mt-2 text-xs text-gray-500">
                  Kategori:
                  <span class="font-medium">
                    {{ capitalize(selectedCategory.category) }}
                  </span>
                  ·
                  {{ selectedCategory.points }} poin
                </p>
              </div>

              <!-- Date -->
              <div>
                <label
                  for="date"
                  class="mb-2 block text-sm font-medium text-gray-700"
                >
                  Tanggal Kejadian
                  <span class="text-red-500">*</span>
                </label>

                <input
                  id="date"
                  v-model="form.date"
                  type="date"
                  :disabled="form.status === 'dibatalkan'"
                  required
                  class="w-full rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-gray-100"
                />
              </div>

              <!-- Description -->
              <div>
                <label
                  for="description"
                  class="mb-2 block text-sm font-medium text-gray-700"
                >
                  Keterangan
                </label>

                <textarea
                  id="description"
                  v-model="form.description"
                  rows="5"
                  :disabled="form.status === 'dibatalkan'"
                  placeholder="Masukkan keterangan pelanggaran..."
                  class="w-full resize-none rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100 disabled:cursor-not-allowed disabled:bg-gray-100"
                />
              </div>

              <!-- Submit Error -->
              <div
                v-if="submitError"
                class="rounded-lg border border-red-200 bg-red-50 px-4 py-3"
              >
                <div class="flex items-start gap-2">
                  <CircleAlert class="mt-0.5 h-4 w-4 shrink-0 text-red-600" />

                  <p class="text-sm text-red-700">
                    {{ submitError }}
                  </p>
                </div>
              </div>

              <!-- Footer -->
              <div
                class="flex flex-col-reverse gap-3 border-t border-gray-200 pt-5 sm:flex-row sm:justify-end"
              >
                <button
                  type="button"
                  class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
                  :disabled="submitting"
                  @click="goBack"
                >
                  Batal
                </button>

                <button
                  v-if="form.status !== 'dibatalkan'"
                  type="submit"
                  :disabled="submitting || !form.categoryId || !form.date"
                  class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700 disabled:cursor-not-allowed disabled:opacity-60"
                >
                  <Loader2 v-if="submitting" class="h-4 w-4 animate-spin" />

                  <Save v-else class="h-4 w-4" />

                  {{ submitting ? "Menyimpan..." : "Simpan Perubahan" }}
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Preview -->
        <div>
          <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-5 py-4">
              <h2 class="font-semibold text-gray-900">Ringkasan</h2>
            </div>

            <div class="space-y-4 p-5">
              <!-- Student -->
              <div>
                <p class="text-xs font-medium uppercase text-gray-400">Siswa</p>

                <p class="mt-1 text-sm font-medium text-gray-900">
                  {{ form.studentName || "-" }}
                </p>

                <p class="mt-1 text-xs text-gray-500">
                  NIS: {{ form.nis || "-" }}
                </p>
              </div>

              <!-- Category -->
              <div>
                <p class="text-xs font-medium uppercase text-gray-400">
                  Pelanggaran
                </p>

                <p class="mt-1 text-sm font-medium text-gray-900">
                  {{ selectedCategory?.name || "-" }}
                </p>
              </div>

              <!-- Category badge -->
              <div
                v-if="selectedCategory"
                class="flex items-center justify-between rounded-lg bg-gray-50 px-3 py-3"
              >
                <span
                  class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium"
                  :class="categoryClass(selectedCategory.category)"
                >
                  {{ capitalize(selectedCategory.category) }}
                </span>

                <span class="text-sm font-bold text-gray-900">
                  {{ selectedCategory.points }} poin
                </span>
              </div>

              <!-- Date -->
              <div>
                <p class="text-xs font-medium uppercase text-gray-400">
                  Tanggal
                </p>

                <p class="mt-1 text-sm text-gray-700">
                  {{ formatDate(form.date) }}
                </p>
              </div>

              <!-- Status -->
              <div>
                <p class="text-xs font-medium uppercase text-gray-400">
                  Status
                </p>

                <span
                  class="mt-1 inline-flex rounded-full px-2.5 py-1 text-xs font-medium"
                  :class="statusClass(form.status)"
                >
                  {{ form.status === "aktif" ? "Aktif" : "Dibatalkan" }}
                </span>
              </div>
            </div>
          </div>

          <!-- Recorded By -->
          <div
            v-if="form.recordedBy"
            class="mt-4 rounded-xl border border-gray-200 bg-white p-5 shadow-sm"
          >
            <p class="text-xs font-medium uppercase text-gray-400">
              Dicatat oleh
            </p>

            <p class="mt-1 text-sm font-medium text-gray-900">
              {{ form.recordedBy }}
            </p>
          </div>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
import { computed, onMounted, reactive, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import { ArrowLeft, Ban, CircleAlert, Loader2, Save } from "lucide-vue-next";

import api from "../../../utils/api";

const route = useRoute();
const router = useRouter();

const loading = ref(true);
const submitting = ref(false);
const error = ref("");
const submitError = ref("");

const categories = ref([]);

const form = reactive({
  studentId: null,
  studentName: "",
  nis: "",
  categoryId: "",
  date: "",
  description: "",
  status: "aktif",
  handlingStatus: "",
  recordedBy: "",
});

const unwrapData = (response) => {
  const data = response?.data?.data ?? response?.data;

  if (Array.isArray(data)) {
    return data;
  }

  if (Array.isArray(data?.data)) {
    return data.data;
  }

  return data || null;
};

const normalizeCategory = (category) => ({
  id: category.id,

  name:
    category.nama_pelanggaran ||
    category.name ||
    category.nama ||
    category.nama_kategori ||
    "Kategori",

  category:
    category.kategori || category.category || category.tingkat || "ringan",

  points: Number(category.poin ?? category.points ?? 0),

  status: category.status,
});

const normalizeDate = (date) => {
  if (!date) {
    return "";
  }

  return String(date).slice(0, 10);
};

const loadCategories = async () => {
  const response = await api.get("/staff/violation-categories");

  categories.value = unwrapData(response)
    .map(normalizeCategory)
    .filter(
      (category) =>
        category.status === undefined ||
        category.status === true ||
        category.status === 1 ||
        category.status === "aktif",
    );
};

const loadViolation = async () => {
  const response = await api.get(`/staff/violations/${route.params.id}`);

  const violation = unwrapData(response);

  if (!violation) {
    throw new Error("Data pelanggaran tidak ditemukan.");
  }

  form.studentId = violation.student_id ?? violation.student?.id ?? null;

  form.studentName =
    violation.student?.user?.name ||
    violation.student?.name ||
    violation.student?.nama ||
    "Siswa";

  form.nis = violation.student?.nis || "-";

  form.categoryId = violation.category_id
    ? String(violation.category_id)
    : violation.category?.id
      ? String(violation.category.id)
      : "";

  form.date = normalizeDate(violation.tanggal_kejadian);

  form.description = violation.keterangan || "";

  form.status = violation.status || "aktif";

  form.handlingStatus = violation.status_penanganan || "";

  form.recordedBy = violation.staff?.user?.name || violation.staff?.name || "";
};

const selectedCategory = computed(() => {
  if (!form.categoryId) {
    return null;
  }

  return (
    categories.value.find(
      (category) => Number(category.id) === Number(form.categoryId),
    ) || null
  );
});

const submit = async () => {
  if (form.status === "dibatalkan") {
    return;
  }

  if (!form.categoryId || !form.date) {
    submitError.value = "Kategori dan tanggal kejadian wajib diisi.";

    return;
  }

  submitting.value = true;
  submitError.value = "";

  try {
    await api.put(`/staff/violations/${route.params.id}`, {
      category_id: Number(form.categoryId),
      tanggal_kejadian: form.date,
      keterangan: form.description.trim() || null,
    });

    window.alert("Pelanggaran berhasil diperbarui.");

    router.push(`/staff/pelanggaran/${route.params.id}`);
  } catch (err) {
    console.error("Gagal memperbarui pelanggaran:", err);

    submitError.value =
      err?.response?.data?.message || "Gagal memperbarui pelanggaran.";
  } finally {
    submitting.value = false;
  }
};

const goBack = () => {
  router.push(`/staff/pelanggaran/${route.params.id}`);
};

const formatDate = (date) => {
  if (!date) {
    return "-";
  }

  const parsedDate = new Date(date);

  if (Number.isNaN(parsedDate.getTime())) {
    return date;
  }

  return new Intl.DateTimeFormat("id-ID", {
    day: "2-digit",
    month: "long",
    year: "numeric",
  }).format(parsedDate);
};

const capitalize = (value) => {
  if (!value) {
    return "-";
  }

  return value.charAt(0).toUpperCase() + value.slice(1);
};

const categoryClass = (category) => {
  switch (category) {
    case "ringan":
      return "bg-green-50 text-green-700";

    case "sedang":
      return "bg-yellow-50 text-yellow-700";

    case "berat":
      return "bg-red-50 text-red-700";

    default:
      return "bg-gray-100 text-gray-700";
  }
};

const statusClass = (status) => {
  switch (status) {
    case "aktif":
      return "bg-green-50 text-green-700";

    case "dibatalkan":
      return "bg-red-50 text-red-700";

    default:
      return "bg-gray-100 text-gray-700";
  }
};

onMounted(async () => {
  loading.value = true;
  error.value = "";

  try {
    await Promise.all([loadCategories(), loadViolation()]);
  } catch (err) {
    console.error("Gagal memuat halaman edit pelanggaran:", err);

    error.value =
      err?.response?.data?.message ||
      err?.message ||
      "Gagal memuat data pelanggaran.";
  } finally {
    loading.value = false;
  }
});
</script>
