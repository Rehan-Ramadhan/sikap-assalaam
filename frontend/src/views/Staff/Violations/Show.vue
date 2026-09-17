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

      <div
        class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between"
      >
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Detail Pelanggaran</h1>

          <p class="mt-1 text-sm text-gray-500">
            Informasi lengkap mengenai pelanggaran siswa.
          </p>
        </div>

        <div class="flex items-center gap-2">
          <button
            v-if="violation?.status !== 'dibatalkan'"
            type="button"
            class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
            @click="goToEdit"
          >
            <Pencil class="h-4 w-4" />
            Edit
          </button>

          <button
            v-if="violation?.status !== 'dibatalkan'"
            type="button"
            class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-700"
            @click="openCancelModal"
          >
            <Ban class="h-4 w-4" />
            Batalkan
          </button>
        </div>
      </div>
    </div>

    <!-- Loading -->
    <div
      v-if="loading"
      class="flex min-h-[350px] items-center justify-center rounded-xl border border-gray-200 bg-white"
    >
      <div class="flex items-center gap-2 text-sm text-gray-500">
        <Loader2 class="h-5 w-5 animate-spin" />
        Memuat detail pelanggaran...
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
          <p class="text-sm font-semibold text-red-800">Gagal memuat data</p>

          <p class="mt-1 text-sm text-red-700">
            {{ error }}
          </p>
        </div>
      </div>
    </div>

    <!-- Content -->
    <template v-else-if="violation">
      <!-- Cancelled Alert -->
      <div
        v-if="violation.status === 'dibatalkan'"
        class="rounded-xl border border-red-200 bg-red-50 px-5 py-4"
      >
        <div class="flex items-start gap-3">
          <Ban class="mt-0.5 h-5 w-5 shrink-0 text-red-600" />

          <div class="min-w-0">
            <p class="text-sm font-semibold text-red-800">
              Pelanggaran dibatalkan
            </p>

            <p class="mt-1 text-sm text-red-700">
              Pelanggaran ini sudah dibatalkan dan tidak dapat diubah.
            </p>

            <p v-if="violation.cancelReason" class="mt-2 text-sm text-red-700">
              <span class="font-medium">Alasan:</span>
              {{ violation.cancelReason }}
            </p>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <!-- Main -->
        <div class="space-y-6 lg:col-span-2">
          <!-- Student -->
          <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-5 py-4">
              <h2 class="font-semibold text-gray-900">Informasi Siswa</h2>
            </div>

            <div class="p-5">
              <div class="flex items-center gap-4">
                <div
                  class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600"
                >
                  <User class="h-6 w-6" />
                </div>

                <div>
                  <p class="text-base font-semibold text-gray-900">
                    {{ violation.studentName }}
                  </p>

                  <p class="mt-1 text-sm text-gray-500">
                    NIS: {{ violation.nis }}
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Violation Detail -->
          <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-5 py-4">
              <h2 class="font-semibold text-gray-900">Informasi Pelanggaran</h2>
            </div>

            <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2">
              <!-- Violation -->
              <div class="sm:col-span-2">
                <p class="text-xs font-medium uppercase text-gray-400">
                  Jenis Pelanggaran
                </p>

                <p class="mt-1 text-base font-semibold text-gray-900">
                  {{ violation.violationName }}
                </p>
              </div>

              <!-- Category -->
              <div>
                <p class="text-xs font-medium uppercase text-gray-400">
                  Kategori
                </p>

                <span
                  class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-medium"
                  :class="categoryClass(violation.category)"
                >
                  {{ capitalize(violation.category) }}
                </span>
              </div>

              <!-- Points -->
              <div>
                <p class="text-xs font-medium uppercase text-gray-400">Poin</p>

                <p class="mt-1 text-xl font-bold text-gray-900">
                  {{ violation.points }}
                  <span class="text-sm font-normal text-gray-500"> poin </span>
                </p>
              </div>

              <!-- Date -->
              <div>
                <p class="text-xs font-medium uppercase text-gray-400">
                  Tanggal Kejadian
                </p>

                <p class="mt-1 text-sm text-gray-700">
                  {{ formatDate(violation.date) }}
                </p>
              </div>

              <!-- Status -->
              <div>
                <p class="text-xs font-medium uppercase text-gray-400">
                  Status
                </p>

                <span
                  class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-medium"
                  :class="statusClass(violation.status)"
                >
                  {{ formatStatus(violation.status) }}
                </span>
              </div>

              <!-- Description -->
              <div class="sm:col-span-2">
                <p class="text-xs font-medium uppercase text-gray-400">
                  Keterangan
                </p>

                <div class="mt-2 rounded-lg bg-gray-50 px-4 py-3">
                  <p
                    v-if="violation.description"
                    class="whitespace-pre-line text-sm leading-6 text-gray-700"
                  >
                    {{ violation.description }}
                  </p>

                  <p v-else class="text-sm italic text-gray-400">
                    Tidak ada keterangan.
                  </p>
                </div>
              </div>
            </div>
          </div>

          <!-- Handling -->
          <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-5 py-4">
              <h2 class="font-semibold text-gray-900">Penanganan</h2>
            </div>

            <div class="p-5">
              <div
                v-if="violation.handlingStatus"
                class="flex items-start gap-4"
              >
                <div
                  class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600"
                >
                  <ClipboardCheck class="h-5 w-5" />
                </div>

                <div>
                  <p class="text-xs font-medium uppercase text-gray-400">
                    Status Penanganan
                  </p>

                  <p class="mt-1 text-sm font-semibold text-gray-900">
                    {{ formatHandlingStatus(violation.handlingStatus) }}
                  </p>
                </div>
              </div>

              <div
                v-else
                class="flex items-center gap-3 rounded-lg bg-gray-50 px-4 py-3"
              >
                <ClipboardCheck class="h-5 w-5 text-gray-400" />

                <p class="text-sm text-gray-500">
                  Belum ada informasi penanganan.
                </p>
              </div>
            </div>
          </div>

          <!-- Cancellation -->
          <div
            v-if="violation.status === 'dibatalkan'"
            class="rounded-xl border border-red-200 bg-white shadow-sm"
          >
            <div class="border-b border-red-100 px-5 py-4">
              <h2 class="font-semibold text-gray-900">Informasi Pembatalan</h2>
            </div>

            <div class="grid grid-cols-1 gap-5 p-5 sm:grid-cols-2">
              <div class="sm:col-span-2">
                <p class="text-xs font-medium uppercase text-gray-400">
                  Alasan Pembatalan
                </p>

                <p
                  class="mt-2 whitespace-pre-line rounded-lg bg-red-50 px-4 py-3 text-sm leading-6 text-red-700"
                >
                  {{ violation.cancelReason || "-" }}
                </p>
              </div>

              <div>
                <p class="text-xs font-medium uppercase text-gray-400">
                  Dibatalkan Oleh
                </p>

                <p class="mt-1 text-sm text-gray-700">
                  {{ violation.cancelledBy || "-" }}
                </p>
              </div>

              <div>
                <p class="text-xs font-medium uppercase text-gray-400">
                  Waktu Pembatalan
                </p>

                <p class="mt-1 text-sm text-gray-700">
                  {{ formatDateTime(violation.cancelledAt) }}
                </p>
              </div>
            </div>
          </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
          <!-- Status -->
          <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-5 py-4">
              <h2 class="font-semibold text-gray-900">Status</h2>
            </div>

            <div class="space-y-4 p-5">
              <div>
                <p class="text-xs font-medium uppercase text-gray-400">
                  Status Pelanggaran
                </p>

                <span
                  class="mt-2 inline-flex rounded-full px-2.5 py-1 text-xs font-medium"
                  :class="statusClass(violation.status)"
                >
                  {{ formatStatus(violation.status) }}
                </span>
              </div>

              <div>
                <p class="text-xs font-medium uppercase text-gray-400">
                  Status Penanganan
                </p>

                <p class="mt-1 text-sm font-medium text-gray-900">
                  {{
                    violation.handlingStatus
                      ? formatHandlingStatus(violation.handlingStatus)
                      : "Belum ada"
                  }}
                </p>
              </div>
            </div>
          </div>

          <!-- Recorded Information -->
          <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="border-b border-gray-200 px-5 py-4">
              <h2 class="font-semibold text-gray-900">Informasi Pencatatan</h2>
            </div>

            <div class="space-y-4 p-5">
              <div>
                <p class="text-xs font-medium uppercase text-gray-400">
                  Dicatat Oleh
                </p>

                <p class="mt-1 text-sm font-medium text-gray-900">
                  {{ violation.recordedBy || "-" }}
                </p>
              </div>

              <div>
                <p class="text-xs font-medium uppercase text-gray-400">
                  Dibuat Pada
                </p>

                <p class="mt-1 text-sm text-gray-700">
                  {{ formatDateTime(violation.createdAt) }}
                </p>
              </div>

              <div v-if="violation.updatedAt">
                <p class="text-xs font-medium uppercase text-gray-400">
                  Terakhir Diperbarui
                </p>

                <p class="mt-1 text-sm text-gray-700">
                  {{ formatDateTime(violation.updatedAt) }}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </template>

    <!-- Cancel Modal -->
    <div
      v-if="cancelModal.open"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
      @click.self="closeCancelModal"
    >
      <div class="w-full max-w-md rounded-xl bg-white shadow-xl">
        <div
          class="flex items-start justify-between border-b border-gray-200 px-5 py-4"
        >
          <div>
            <h2 class="text-lg font-semibold text-gray-900">
              Batalkan Pelanggaran
            </h2>

            <p class="mt-1 text-sm text-gray-500">
              Masukkan alasan pembatalan pelanggaran.
            </p>
          </div>

          <button
            type="button"
            class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
            :disabled="cancelModal.loading"
            @click="closeCancelModal"
          >
            <X class="h-5 w-5" />
          </button>
        </div>

        <div class="space-y-4 p-5">
          <div class="rounded-lg bg-gray-50 p-4">
            <p class="text-sm font-medium text-gray-900">
              {{ violation?.violationName }}
            </p>

            <p class="mt-1 text-xs text-gray-500">
              {{ violation?.studentName }}
              ·
              {{ violation?.nis }}
            </p>
          </div>

          <div>
            <label
              for="cancelReason"
              class="mb-2 block text-sm font-medium text-gray-700"
            >
              Alasan Pembatalan
            </label>

            <textarea
              id="cancelReason"
              v-model="cancelModal.reason"
              rows="4"
              maxlength="1000"
              placeholder="Masukkan alasan pembatalan..."
              class="w-full resize-none rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-100"
            />

            <p class="mt-1 text-right text-xs text-gray-400">
              {{ cancelModal.reason.length }}/1000
            </p>
          </div>

          <div
            v-if="cancelModal.error"
            class="rounded-lg border border-red-200 bg-red-50 px-4 py-3"
          >
            <p class="text-sm text-red-700">
              {{ cancelModal.error }}
            </p>
          </div>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-200 px-5 py-4">
          <button
            type="button"
            class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50 disabled:opacity-50"
            :disabled="cancelModal.loading"
            @click="closeCancelModal"
          >
            Batal
          </button>

          <button
            type="button"
            class="inline-flex items-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60"
            :disabled="cancelModal.loading || !cancelModal.reason.trim()"
            @click="cancelViolation"
          >
            <Loader2 v-if="cancelModal.loading" class="h-4 w-4 animate-spin" />

            <Ban v-else class="h-4 w-4" />

            {{
              cancelModal.loading ? "Membatalkan..." : "Batalkan Pelanggaran"
            }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from "vue";
import { useRoute, useRouter } from "vue-router";
import {
  ArrowLeft,
  Ban,
  CircleAlert,
  ClipboardCheck,
  Loader2,
  Pencil,
  User,
  X,
} from "lucide-vue-next";

import api from "../../../utils/api";

const route = useRoute();
const router = useRouter();

const loading = ref(true);
const error = ref("");
const violation = ref(null);

const cancelModal = reactive({
  open: false,
  loading: false,
  reason: "",
  error: "",
});

const unwrapData = (response) => {
  const data = response?.data?.data ?? response?.data;

  if (Array.isArray(data)) {
    return data[0] ?? null;
  }

  if (Array.isArray(data?.data)) {
    return data.data[0] ?? null;
  }

  return data || null;
};

const normalizeViolation = (data) => ({
  id: data.id,

  studentName:
    data.student?.user?.name ||
    data.student?.name ||
    data.student?.nama ||
    "Siswa",

  nis: data.student?.nis || "-",

  violationName:
    data.category?.nama_pelanggaran ||
    data.category?.name ||
    data.category?.nama ||
    data.category?.nama_kategori ||
    "Pelanggaran",

  category:
    data.category?.kategori ||
    data.category?.category ||
    data.category?.tingkat ||
    "ringan",

  points: Number(data.poin_tercatat ?? data.category?.poin ?? data.points ?? 0),

  date: data.tanggal_kejadian || data.date || null,

  description: data.keterangan || data.description || "",

  status: data.status || "aktif",

  handlingStatus: data.status_penanganan || data.handling_status || null,

  recordedBy: data.staff?.user?.name || data.staff?.name || "-",

  createdAt: data.created_at || data.createdAt || null,

  updatedAt: data.updated_at || data.updatedAt || null,

  cancelReason: data.cancel_reason || data.cancelReason || null,

  cancelledBy:
    data.cancelled_by?.name ||
    data.cancelledBy?.name ||
    data.cancelledBy?.user?.name ||
    null,

  cancelledAt: data.cancelled_at || data.cancelledAt || null,
});

const fetchViolation = async () => {
  loading.value = true;
  error.value = "";

  try {
    const response = await api.get(`/staff/violations/${route.params.id}`);

    const data = unwrapData(response);

    if (!data) {
      throw new Error("Data pelanggaran tidak ditemukan.");
    }

    violation.value = normalizeViolation(data);
  } catch (err) {
    console.error("Gagal mengambil detail pelanggaran:", err);

    error.value =
      err?.response?.data?.message ||
      err?.message ||
      "Gagal mengambil detail pelanggaran.";
  } finally {
    loading.value = false;
  }
};

const goBack = () => {
  router.push("/staff/pelanggaran");
};

const goToEdit = () => {
  router.push(`/staff/pelanggaran/${route.params.id}/edit`);
};

const openCancelModal = () => {
  cancelModal.open = true;
  cancelModal.loading = false;
  cancelModal.reason = "";
  cancelModal.error = "";
};

const closeCancelModal = () => {
  if (cancelModal.loading) {
    return;
  }

  cancelModal.open = false;
  cancelModal.reason = "";
  cancelModal.error = "";
};

const cancelViolation = async () => {
  if (!violation.value) {
    return;
  }

  if (!cancelModal.reason.trim()) {
    cancelModal.error = "Alasan pembatalan wajib diisi.";

    return;
  }

  cancelModal.loading = true;
  cancelModal.error = "";

  try {
    const response = await api.post(
      `/staff/violations/${violation.value.id}/cancel`,
      {
        cancel_reason: cancelModal.reason.trim(),
      },
    );

    const data = unwrapData(response);

    if (data) {
      violation.value = normalizeViolation(data);
    } else {
      await fetchViolation();
    }

    closeCancelModal();

    window.alert("Pelanggaran berhasil dibatalkan.");
  } catch (err) {
    console.error("Gagal membatalkan pelanggaran:", err);

    cancelModal.error =
      err?.response?.data?.message || "Gagal membatalkan pelanggaran.";
  } finally {
    cancelModal.loading = false;
  }
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

const formatDateTime = (date) => {
  if (!date) {
    return "-";
  }

  const parsedDate = new Date(date);

  if (Number.isNaN(parsedDate.getTime())) {
    return date;
  }

  return new Intl.DateTimeFormat("id-ID", {
    day: "2-digit",
    month: "short",
    year: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  }).format(parsedDate);
};

const capitalize = (value) => {
  if (!value) {
    return "-";
  }

  return value.charAt(0).toUpperCase() + value.slice(1);
};

const formatStatus = (status) => {
  const labels = {
    aktif: "Aktif",
    dibatalkan: "Dibatalkan",
  };

  return (
    labels[status] || capitalize(String(status || "").replaceAll("_", " "))
  );
};

const formatHandlingStatus = (status) => {
  if (!status) {
    return "-";
  }

  const labels = {
    belum_ditangani: "Belum ditangani",
    ringan: "Tahap ringan",
    sedang: "Tahap sedang",
    berat: "Tahap berat",
  };

  return labels[status] || capitalize(String(status).replaceAll("_", " "));
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

onMounted(() => {
  fetchViolation();
});
</script>
