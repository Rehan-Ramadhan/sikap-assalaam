<template>
  <div class="space-y-6">
    <!-- Header -->
    <div
      class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between"
    >
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Pelanggaran</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola data pelanggaran siswa.</p>
      </div>

      <button
        type="button"
        class="inline-flex items-center justify-center gap-2 rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-blue-700"
        @click="goToCreate"
      >
        <Plus class="h-4 w-4" />
        Tambah Pelanggaran
      </button>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
      <div
        v-for="stat in stats"
        :key="stat.label"
        class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm"
      >
        <div class="flex items-start justify-between">
          <div>
            <p class="text-sm text-gray-500">{{ stat.label }}</p>
            <p class="mt-2 text-2xl font-bold text-gray-900">
              {{ stat.value }}
            </p>
          </div>

          <div
            class="flex h-10 w-10 items-center justify-center rounded-lg"
            :class="stat.iconClass"
          >
            <component :is="stat.icon" class="h-5 w-5" />
          </div>
        </div>
      </div>
    </div>

    <!-- Filters -->
    <div class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
      <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
        <!-- Search -->
        <div class="lg:col-span-2">
          <label class="mb-2 block text-sm font-medium text-gray-700">
            Cari
          </label>

          <div class="relative">
            <Search
              class="absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-gray-400"
            />

            <input
              v-model="filters.search"
              type="text"
              placeholder="Cari nama siswa, NIS, atau pelanggaran..."
              class="w-full rounded-lg border border-gray-300 py-2.5 pl-10 pr-4 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
              @keyup.enter="fetchViolations(1)"
            />
          </div>
        </div>

        <!-- Category -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">
            Kategori
          </label>

          <select
            v-model="filters.categoryId"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            @change="fetchViolations(1)"
          >
            <option value="">Semua kategori</option>

            <option
              v-for="category in categories"
              :key="category.id"
              :value="category.id"
            >
              {{ category.name }}
            </option>
          </select>
        </div>

        <!-- Status -->
        <div>
          <label class="mb-2 block text-sm font-medium text-gray-700">
            Status
          </label>

          <select
            v-model="filters.status"
            class="w-full rounded-lg border border-gray-300 bg-white px-3 py-2.5 text-sm outline-none transition focus:border-blue-500 focus:ring-2 focus:ring-blue-100"
            @change="fetchViolations(1)"
          >
            <option value="">Semua status</option>
            <option value="aktif">Aktif</option>
            <option value="dibatalkan">Dibatalkan</option>
          </select>
        </div>
      </div>

      <div class="mt-4 flex justify-end">
        <button
          type="button"
          class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
          @click="resetFilters"
        >
          <RotateCcw class="h-4 w-4" />
          Reset
        </button>
      </div>
    </div>

    <!-- Error -->
    <div
      v-if="error"
      class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700"
    >
      {{ error }}
    </div>

    <!-- Table -->
    <div
      class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm"
    >
      <div class="overflow-x-auto">
        <table class="w-full min-w-[900px] text-left">
          <thead class="border-b border-gray-200 bg-gray-50">
            <tr>
              <th
                class="px-5 py-4 text-xs font-semibold uppercase text-gray-500"
              >
                Siswa
              </th>

              <th
                class="px-5 py-4 text-xs font-semibold uppercase text-gray-500"
              >
                Pelanggaran
              </th>

              <th
                class="px-5 py-4 text-xs font-semibold uppercase text-gray-500"
              >
                Kategori
              </th>

              <th
                class="px-5 py-4 text-xs font-semibold uppercase text-gray-500"
              >
                Poin
              </th>

              <th
                class="px-5 py-4 text-xs font-semibold uppercase text-gray-500"
              >
                Tanggal
              </th>

              <th
                class="px-5 py-4 text-xs font-semibold uppercase text-gray-500"
              >
                Status
              </th>

              <th
                class="px-5 py-4 text-xs font-semibold uppercase text-gray-500"
              >
                Aksi
              </th>
            </tr>
          </thead>

          <tbody class="divide-y divide-gray-100">
            <!-- Loading -->
            <tr v-if="loading">
              <td colspan="7" class="px-5 py-12 text-center">
                <div
                  class="flex items-center justify-center gap-2 text-sm text-gray-500"
                >
                  <Loader2 class="h-5 w-5 animate-spin" />
                  Memuat data...
                </div>
              </td>
            </tr>

            <!-- Empty -->
            <tr v-else-if="violations.length === 0">
              <td colspan="7" class="px-5 py-12 text-center">
                <div class="flex flex-col items-center">
                  <FileWarning class="h-10 w-10 text-gray-300" />

                  <p class="mt-3 text-sm font-medium text-gray-700">
                    Tidak ada data pelanggaran
                  </p>

                  <p class="mt-1 text-sm text-gray-500">
                    Belum ada data yang sesuai dengan filter.
                  </p>
                </div>
              </td>
            </tr>

            <!-- Data -->
            <tr
              v-for="violation in violations"
              v-else
              :key="violation.id"
              class="transition hover:bg-gray-50"
            >
              <!-- Student -->
              <td class="px-5 py-4">
                <div>
                  <p class="text-sm font-medium text-gray-900">
                    {{ violation.studentName }}
                  </p>

                  <p class="mt-1 text-xs text-gray-500">
                    NIS: {{ violation.nis }}
                  </p>
                </div>
              </td>

              <!-- Violation -->
              <td class="px-5 py-4">
                <p class="max-w-[240px] text-sm font-medium text-gray-900">
                  {{ violation.violationName }}
                </p>

                <p
                  v-if="violation.description"
                  class="mt-1 max-w-[240px] truncate text-xs text-gray-500"
                >
                  {{ violation.description }}
                </p>
              </td>

              <!-- Category -->
              <td class="px-5 py-4">
                <span
                  class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium"
                  :class="categoryClass(violation.category)"
                >
                  {{ capitalize(violation.category) }}
                </span>
              </td>

              <!-- Points -->
              <td class="px-5 py-4">
                <span class="text-sm font-semibold text-gray-900">
                  {{ violation.points }}
                </span>
              </td>

              <!-- Date -->
              <td class="px-5 py-4">
                <span class="text-sm text-gray-600">
                  {{ formatDate(violation.date) }}
                </span>
              </td>

              <!-- Status -->
              <td class="px-5 py-4">
                <div class="flex flex-col gap-1">
                  <span
                    class="inline-flex w-fit rounded-full px-2.5 py-1 text-xs font-medium"
                    :class="statusClass(violation.status)"
                  >
                    {{ violation.status === "aktif" ? "Aktif" : "Dibatalkan" }}
                  </span>

                  <span
                    v-if="violation.handlingStatus"
                    class="text-xs text-gray-500"
                  >
                    {{ formatHandlingStatus(violation.handlingStatus) }}
                  </span>
                </div>
              </td>

              <!-- Actions -->
              <td class="px-5 py-4">
                <div class="flex items-center gap-1">
                  <button
                    type="button"
                    title="Lihat detail"
                    class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-blue-600"
                    @click="goToShow(violation.id)"
                  >
                    <Eye class="h-4 w-4" />
                  </button>

                  <button
                    v-if="violation.status !== 'dibatalkan'"
                    type="button"
                    title="Edit"
                    class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-amber-600"
                    @click="goToEdit(violation.id)"
                  >
                    <Pencil class="h-4 w-4" />
                  </button>

                  <button
                    v-if="violation.status !== 'dibatalkan'"
                    type="button"
                    title="Batalkan"
                    class="rounded-lg p-2 text-gray-500 transition hover:bg-gray-100 hover:text-red-600"
                    @click="openCancelModal(violation)"
                  >
                    <Ban class="h-4 w-4" />
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Pagination -->
      <div
        v-if="pagination.lastPage > 1"
        class="flex flex-col gap-3 border-t border-gray-200 px-5 py-4 sm:flex-row sm:items-center sm:justify-between"
      >
        <p class="text-sm text-gray-500">
          Menampilkan
          <span class="font-medium text-gray-700">
            {{ pagination.from || 0 }}
          </span>
          -
          <span class="font-medium text-gray-700">
            {{ pagination.to || 0 }}
          </span>
          dari
          <span class="font-medium text-gray-700">
            {{ pagination.total }}
          </span>
          data
        </p>

        <div class="flex items-center gap-1">
          <button
            type="button"
            :disabled="pagination.currentPage <= 1"
            class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40"
            @click="changePage(pagination.currentPage - 1)"
          >
            <ChevronLeft class="h-4 w-4" />
          </button>

          <button
            v-for="page in visiblePages"
            :key="page"
            type="button"
            class="min-w-9 rounded-lg border px-3 py-2 text-sm transition"
            :class="
              page === pagination.currentPage
                ? 'border-blue-600 bg-blue-600 text-white'
                : 'border-gray-300 text-gray-600 hover:bg-gray-50'
            "
            @click="changePage(page)"
          >
            {{ page }}
          </button>

          <button
            type="button"
            :disabled="pagination.currentPage >= pagination.lastPage"
            class="rounded-lg border border-gray-300 px-3 py-2 text-sm text-gray-600 transition hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40"
            @click="changePage(pagination.currentPage + 1)"
          >
            <ChevronRight class="h-4 w-4" />
          </button>
        </div>
      </div>
    </div>

    <!-- Cancel Modal -->
    <div
      v-if="cancelModal.open"
      class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
      @click.self="closeCancelModal"
    >
      <div class="w-full max-w-md rounded-xl bg-white shadow-xl">
        <div class="border-b border-gray-200 px-5 py-4">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-lg font-semibold text-gray-900">
                Batalkan Pelanggaran
              </h2>

              <p class="mt-1 text-sm text-gray-500">
                Data yang sudah dibatalkan tidak dapat diubah kembali.
              </p>
            </div>

            <button
              type="button"
              class="rounded-lg p-2 text-gray-400 transition hover:bg-gray-100 hover:text-gray-600"
              @click="closeCancelModal"
            >
              <X class="h-5 w-5" />
            </button>
          </div>
        </div>

        <div class="space-y-4 px-5 py-5">
          <div v-if="cancelModal.violation" class="rounded-lg bg-gray-50 p-4">
            <p class="text-sm font-medium text-gray-900">
              {{ cancelModal.violation.violationName }}
            </p>

            <p class="mt-1 text-xs text-gray-500">
              {{ cancelModal.violation.studentName }}
              ·
              {{ cancelModal.violation.nis }}
            </p>
          </div>

          <div>
            <label class="mb-2 block text-sm font-medium text-gray-700">
              Alasan pembatalan
            </label>

            <textarea
              v-model="cancelModal.reason"
              rows="4"
              placeholder="Masukkan alasan pembatalan..."
              class="w-full resize-none rounded-lg border border-gray-300 px-3 py-2.5 text-sm outline-none transition focus:border-red-500 focus:ring-2 focus:ring-red-100"
            />
          </div>

          <p v-if="cancelModal.error" class="text-sm text-red-600">
            {{ cancelModal.error }}
          </p>
        </div>

        <div class="flex justify-end gap-3 border-t border-gray-200 px-5 py-4">
          <button
            type="button"
            class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 transition hover:bg-gray-50"
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
import { computed, onMounted, reactive, ref } from "vue";
import { useRouter } from "vue-router";
import {
  Ban,
  ChevronLeft,
  ChevronRight,
  Eye,
  FileWarning,
  Loader2,
  Pencil,
  Plus,
  RotateCcw,
  Search,
  X,
} from "lucide-vue-next";

import api from "../../../utils/api";

const router = useRouter();

const violations = ref([]);
const categories = ref([]);

const loading = ref(false);
const error = ref("");

const filters = reactive({
  search: "",
  categoryId: "",
  status: "",
});

const pagination = reactive({
  currentPage: 1,
  lastPage: 1,
  total: 0,
  from: 0,
  to: 0,
  perPage: 15,
});

const cancelModal = reactive({
  open: false,
  loading: false,
  error: "",
  reason: "",
  violation: null,
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

const unwrapPaginator = (response) => {
  const data = response?.data?.data ?? response?.data;

  if (Array.isArray(data)) {
    return {
      data,
      currentPage: 1,
      lastPage: 1,
      total: data.length,
      from: data.length ? 1 : 0,
      to: data.length,
      perPage: data.length || 15,
    };
  }

  return {
    data: Array.isArray(data?.data) ? data.data : [],
    currentPage: Number(data?.current_page ?? 1),
    lastPage: Number(data?.last_page ?? 1),
    total: Number(data?.total ?? 0),
    from: Number(data?.from ?? 0),
    to: Number(data?.to ?? 0),
    perPage: Number(data?.per_page ?? 15),
  };
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

const normalizeViolation = (violation) => ({
  id: violation.id,

  studentName:
    violation.student?.user?.name ||
    violation.student?.name ||
    violation.student?.nama ||
    "Siswa",

  nis: violation.student?.nis || "-",

  violationName:
    violation.category?.nama_pelanggaran ||
    violation.category?.name ||
    violation.category?.nama ||
    violation.category?.nama_kategori ||
    "Pelanggaran",

  category:
    violation.category?.kategori ||
    violation.category?.category ||
    violation.category?.tingkat ||
    "ringan",

  points: Number(
    violation.poin_tercatat ??
      violation.category?.poin ??
      violation.points ??
      0,
  ),

  date: violation.tanggal_kejadian || violation.date || null,

  description: violation.keterangan || violation.description || "",

  status: violation.status || "aktif",

  handlingStatus:
    violation.status_penanganan || violation.handling_status || null,

  recordedBy: violation.staff?.user?.name || violation.staff?.name || "-",

  createdAt: violation.created_at || violation.createdAt || null,

  cancelReason: violation.cancel_reason || violation.cancelReason || null,
});

const fetchCategories = async () => {
  try {
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
  } catch (err) {
    console.error("Gagal mengambil kategori pelanggaran:", err);
  }
};

const fetchViolations = async (page = 1) => {
  loading.value = true;
  error.value = "";

  try {
    const params = {
      page,
      per_page: pagination.perPage,
    };

    if (filters.categoryId) {
      params.category_id = filters.categoryId;
    }

    if (filters.status) {
      params.status = filters.status;
    }

    const response = await api.get("/staff/violations", {
      params,
    });

    const result = unwrapPaginator(response);

    let data = result.data.map(normalizeViolation);

    /*
     * Backend belum menyediakan parameter search.
     * Karena itu pencarian nama siswa / NIS / nama pelanggaran
     * dilakukan terhadap data halaman yang sedang diterima.
     */
    if (filters.search.trim()) {
      const keyword = filters.search.trim().toLowerCase();

      data = data.filter((violation) => {
        return [
          violation.studentName,
          violation.nis,
          violation.violationName,
          violation.description,
        ].some((value) =>
          String(value || "")
            .toLowerCase()
            .includes(keyword),
        );
      });
    }

    violations.value = data;

    pagination.currentPage = result.currentPage;
    pagination.lastPage = result.lastPage;
    pagination.total = result.total;
    pagination.from = result.from;
    pagination.to = result.to;
    pagination.perPage = result.perPage || pagination.perPage;
  } catch (err) {
    console.error("Gagal mengambil data pelanggaran:", err);

    error.value =
      err?.response?.data?.message || "Gagal mengambil data pelanggaran.";
  } finally {
    loading.value = false;
  }
};

const resetFilters = () => {
  filters.search = "";
  filters.categoryId = "";
  filters.status = "";

  fetchViolations(1);
};

const changePage = (page) => {
  if (
    page < 1 ||
    page > pagination.lastPage ||
    page === pagination.currentPage
  ) {
    return;
  }

  fetchViolations(page);
};

const visiblePages = computed(() => {
  const current = pagination.currentPage;
  const last = pagination.lastPage;

  if (last <= 5) {
    return Array.from({ length: last }, (_, index) => index + 1);
  }

  if (current <= 3) {
    return [1, 2, 3, 4, 5];
  }

  if (current >= last - 2) {
    return [last - 4, last - 3, last - 2, last - 1, last];
  }

  return [current - 2, current - 1, current, current + 1, current + 2];
});

const stats = computed(() => {
  const total = pagination.total;

  /*
   * Karena endpoint index sudah menggunakan pagination,
   * jumlah aktif/dibatalkan di sini dihitung dari data halaman saat ini.
   */
  const active = violations.value.filter(
    (violation) => violation.status === "aktif",
  ).length;

  const cancelled = violations.value.filter(
    (violation) => violation.status === "dibatalkan",
  ).length;

  const points = violations.value.reduce(
    (totalPoints, violation) => totalPoints + violation.points,
    0,
  );

  return [
    {
      label: "Total Data",
      value: total,
      icon: FileWarning,
      iconClass: "bg-blue-50 text-blue-600",
    },
    {
      label: "Aktif",
      value: active,
      icon: FileWarning,
      iconClass: "bg-amber-50 text-amber-600",
    },
    {
      label: "Dibatalkan",
      value: cancelled,
      icon: Ban,
      iconClass: "bg-red-50 text-red-600",
    },
    {
      label: "Poin Halaman",
      value: points,
      icon: FileWarning,
      iconClass: "bg-purple-50 text-purple-600",
    },
  ];
});

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
    month: "short",
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

const formatHandlingStatus = (status) => {
  if (!status) {
    return "";
  }

  const labels = {
    belum_ditangani: "Belum ditangani",
    ringan: "Tahap ringan",
    sedang: "Tahap sedang",
    berat: "Tahap berat",
  };

  return labels[status] || capitalize(status.replaceAll("_", " "));
};

const goToCreate = () => {
  router.push("/staff/pelanggaran/create");
};

const goToShow = (id) => {
  router.push(`/staff/pelanggaran/${id}`);
};

const goToEdit = (id) => {
  router.push(`/staff/pelanggaran/${id}/edit`);
};

const openCancelModal = (violation) => {
  cancelModal.open = true;
  cancelModal.loading = false;
  cancelModal.error = "";
  cancelModal.reason = "";
  cancelModal.violation = violation;
};

const closeCancelModal = () => {
  if (cancelModal.loading) {
    return;
  }

  cancelModal.open = false;
  cancelModal.error = "";
  cancelModal.reason = "";
  cancelModal.violation = null;
};

const cancelViolation = async () => {
  if (!cancelModal.violation || !cancelModal.reason.trim()) {
    return;
  }

  cancelModal.loading = true;
  cancelModal.error = "";

  try {
    await api.post(`/staff/violations/${cancelModal.violation.id}/cancel`, {
      cancel_reason: cancelModal.reason.trim(),
    });

    closeCancelModal();

    await fetchViolations(pagination.currentPage);
  } catch (err) {
    console.error("Gagal membatalkan pelanggaran:", err);

    cancelModal.error =
      err?.response?.data?.message || "Gagal membatalkan pelanggaran.";
  } finally {
    cancelModal.loading = false;
  }
};

onMounted(async () => {
  await Promise.all([fetchCategories(), fetchViolations(1)]);
});
</script>
