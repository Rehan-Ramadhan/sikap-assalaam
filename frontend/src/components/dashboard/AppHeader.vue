<template>
  <header class="navbar">
    <!-- BRAND / TITLE -->
    <div class="navbar-title">
      <div class="title-main">
        <h2>{{ dashboardTitle }}</h2>
      </div>

      <span>SIKAP Assalaam</span>
    </div>

    <!-- SEARCH -->
    <div class="navbar-search">
      <Search :size="18" />

      <input type="text" placeholder="Cari siswa, pelanggaran, prestasi..." />

      <span class="search-shortcut"> / </span>
    </div>

    <!-- ACTION -->
    <div class="navbar-actions">
      <!-- NOTIFICATION -->
      <div class="notification-wrapper">
        <button
          class="notification-btn"
          title="Notifikasi"
          type="button"
          @click="toggleNotifications"
        >
          <Bell :size="19" />

          <span v-if="unreadCount > 0" class="notification-badge">
            {{ unreadCount > 99 ? "99+" : unreadCount }}
          </span>
        </button>

        <!-- NOTIFICATION DROPDOWN -->
        <Transition name="notification-dropdown">
          <div v-if="showNotifications" class="notification-dropdown">
            <!-- HEADER -->
            <div class="notification-header">
              <div>
                <h3>Notifikasi</h3>

                <span v-if="unreadCount > 0">
                  {{ unreadCount }} belum dibaca
                </span>

                <span v-else> Semua sudah dibaca </span>
              </div>

              <button
                v-if="unreadCount > 0"
                type="button"
                class="read-all-btn"
                :disabled="markingAllRead"
                @click="markAllAsRead"
              >
                {{ markingAllRead ? "..." : "Baca semua" }}
              </button>
            </div>

            <!-- LOADING -->
            <div v-if="loadingNotifications" class="notification-state">
              <div class="loading-spinner"></div>

              <span>Memuat notifikasi...</span>
            </div>

            <!-- ERROR -->
            <div
              v-else-if="notificationError"
              class="notification-state error-state"
            >
              <Bell :size="20" />

              <span>{{ notificationError }}</span>

              <button type="button" @click="fetchNotifications">
                Coba lagi
              </button>
            </div>

            <!-- EMPTY -->
            <div
              v-else-if="notifications.length === 0"
              class="notification-state"
            >
              <div class="empty-notification-icon">
                <Bell :size="20" />
              </div>

              <strong>Tidak ada notifikasi</strong>

              <span> Belum ada notifikasi untuk kamu. </span>
            </div>

            <!-- LIST -->
            <div v-else class="notification-list">
              <button
                v-for="notification in notifications"
                :key="notification.id"
                type="button"
                class="notification-item"
                :class="{
                  unread: !notification.is_read,
                }"
                @click="handleNotificationClick(notification)"
              >
                <!-- ICON -->
                <div
                  class="notification-icon"
                  :class="getNotificationIconClass(notification)"
                >
                  <component
                    :is="getNotificationIcon(notification)"
                    :size="16"
                  />
                </div>

                <!-- CONTENT -->
                <div class="notification-content">
                  <div class="notification-item-header">
                    <strong>
                      {{ getNotificationTitle(notification) }}
                    </strong>

                    <span
                      v-if="!notification.is_read"
                      class="unread-dot"
                    ></span>
                  </div>

                  <p>
                    {{ getNotificationMessage(notification) }}
                  </p>

                  <small>
                    {{ formatNotificationTime(notification.created_at) }}
                  </small>
                </div>
              </button>
            </div>

            <!-- FOOTER -->
            <div v-if="notifications.length > 0" class="notification-footer">
              <span> Menampilkan notifikasi terbaru </span>
            </div>
          </div>
        </Transition>
      </div>

      <!-- DIVIDER -->
      <div class="navbar-divider"></div>

      <!-- USER -->
      <div class="navbar-user">
        <div class="user-avatar">
          <User :size="18" />
        </div>

        <div class="user-info">
          <strong>{{ userName }}</strong>
          <span>{{ positionLabel }}</span>
        </div>

        <ChevronDown class="user-chevron" :size="16" />
      </div>

      <!-- LOGOUT -->
      <button
        class="logout-btn"
        title="Logout"
        type="button"
        @click="$emit('logout')"
      >
        <LogOut :size="17" />
      </button>
    </div>
  </header>
</template>

<script setup>
import { computed, onMounted, onBeforeUnmount, ref } from "vue";

import {
  Search,
  Bell,
  User,
  ChevronDown,
  LogOut,
  AlertTriangle,
  Trophy,
  ClipboardList,
  Info,
} from "lucide-vue-next";

import { getUser, getStaffPosition } from "../../utils/auth";

import api from "../../utils/api";

defineEmits(["logout"]);

/*
|--------------------------------------------------------------------------
| USER
|--------------------------------------------------------------------------
*/

const user = getUser();
const jabatan = getStaffPosition();

const positionNames = {
  wali_kelas: "Wali Kelas",
  bk: "BK",
  kesiswaan: "Kesiswaan",
  kepala_sekolah: "Kepala Sekolah",
};

/*
|--------------------------------------------------------------------------
| USER INFORMATION
|--------------------------------------------------------------------------
*/

const positionLabel = computed(() => {
  if (user?.role === "student") {
    return "Siswa";
  }

  return positionNames[jabatan] || "Staf";
});

const userName = computed(() => {
  if (user?.role === "student") {
    return user?.name || user?.username || "Siswa";
  }

  return user?.staff?.nama || user?.name || user?.username || "Staf";
});

const dashboardTitle = computed(() => {
  if (user?.role === "student") {
    return "Dashboard Siswa";
  }

  return `Dashboard ${positionNames[jabatan] || "Staf"}`;
});

/*
|--------------------------------------------------------------------------
| NOTIFICATIONS
|--------------------------------------------------------------------------
*/

const notifications = ref([]);

const loadingNotifications = ref(false);

const notificationError = ref("");

const showNotifications = ref(false);

const markingAllRead = ref(false);

const unreadCount = computed(() => {
  return notifications.value.filter((notification) => !notification.is_read)
    .length;
});

/*
|--------------------------------------------------------------------------
| FETCH NOTIFICATIONS
|--------------------------------------------------------------------------
*/

const fetchNotifications = async () => {
  loadingNotifications.value = true;
  notificationError.value = "";

  try {
    const response = await api.get("/notifications", {
      params: {
        per_page: 10,
      },
    });

    /*
     * Laravel paginator:
     *
     * response.data
     * └── data
     *     └── data = array notification
     */
    notifications.value = response?.data?.data?.data || [];
  } catch (error) {
    console.error("Gagal mengambil notifikasi:", error);

    notificationError.value =
      error?.response?.data?.message || "Gagal memuat notifikasi.";
  } finally {
    loadingNotifications.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| TOGGLE NOTIFICATION
|--------------------------------------------------------------------------
*/

const toggleNotifications = async () => {
  showNotifications.value = !showNotifications.value;

  if (showNotifications.value && notifications.value.length === 0) {
    await fetchNotifications();
  }
};

/*
|--------------------------------------------------------------------------
| MARK ONE AS READ
|--------------------------------------------------------------------------
*/

const markAsRead = async (notification) => {
  if (notification.is_read) {
    return;
  }

  try {
    await api.patch(`/notifications/${notification.id}/read`);

    notification.is_read = true;
    notification.read_at = new Date().toISOString();
  } catch (error) {
    console.error("Gagal menandai notifikasi:", error);
  }
};

/*
|--------------------------------------------------------------------------
| HANDLE NOTIFICATION CLICK
|--------------------------------------------------------------------------
*/

const handleNotificationClick = async (notification) => {
  await markAsRead(notification);
};

/*
|--------------------------------------------------------------------------
| MARK ALL AS READ
|--------------------------------------------------------------------------
*/

const markAllAsRead = async () => {
  if (unreadCount.value === 0) {
    return;
  }

  markingAllRead.value = true;

  try {
    await api.patch("/notifications/read-all");

    notifications.value.forEach((notification) => {
      notification.is_read = true;
      notification.read_at = new Date().toISOString();
    });
  } catch (error) {
    console.error("Gagal menandai semua notifikasi:", error);
  } finally {
    markingAllRead.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| NOTIFICATION TITLE
|--------------------------------------------------------------------------
*/

const getNotificationTitle = (notification) => {
  if (notification.title) {
    return notification.title;
  }

  if (notification.data?.title) {
    return notification.data.title;
  }

  if (notification.type) {
    const type = notification.type.split("\\").pop();

    return type.replace(/([A-Z])/g, " $1").trim();
  }

  return "Notifikasi";
};

/*
|--------------------------------------------------------------------------
| NOTIFICATION MESSAGE
|--------------------------------------------------------------------------
*/

const getNotificationMessage = (notification) => {
  if (notification.message) {
    return notification.message;
  }

  if (notification.data?.message) {
    return notification.data.message;
  }

  if (notification.data?.body) {
    return notification.data.body;
  }

  if (notification.body) {
    return notification.body;
  }

  return "Ada informasi baru untuk kamu.";
};

/*
|--------------------------------------------------------------------------
| NOTIFICATION ICON
|--------------------------------------------------------------------------
*/

const getNotificationIcon = (notification) => {
  const title = (
    getNotificationTitle(notification) +
    " " +
    getNotificationMessage(notification)
  ).toLowerCase();

  if (title.includes("pelanggaran") || title.includes("violation")) {
    return AlertTriangle;
  }

  if (title.includes("prestasi") || title.includes("achievement")) {
    return Trophy;
  }

  if (title.includes("penanganan") || title.includes("intervention")) {
    return ClipboardList;
  }

  return Info;
};

/*
|--------------------------------------------------------------------------
| NOTIFICATION ICON CLASS
|--------------------------------------------------------------------------
*/

const getNotificationIconClass = (notification) => {
  const title = (
    getNotificationTitle(notification) +
    " " +
    getNotificationMessage(notification)
  ).toLowerCase();

  if (title.includes("pelanggaran") || title.includes("violation")) {
    return "icon-danger";
  }

  if (title.includes("prestasi") || title.includes("achievement")) {
    return "icon-success";
  }

  if (title.includes("penanganan") || title.includes("intervention")) {
    return "icon-warning";
  }

  return "icon-info";
};

/*
|--------------------------------------------------------------------------
| FORMAT TIME
|--------------------------------------------------------------------------
*/

const formatNotificationTime = (date) => {
  if (!date) {
    return "";
  }

  const notificationDate = new Date(date);

  if (Number.isNaN(notificationDate.getTime())) {
    return "";
  }

  const now = new Date();

  const difference = now.getTime() - notificationDate.getTime();

  const seconds = Math.floor(difference / 1000);

  const minutes = Math.floor(seconds / 60);

  const hours = Math.floor(minutes / 60);

  const days = Math.floor(hours / 24);

  if (seconds < 60) {
    return "Baru saja";
  }

  if (minutes < 60) {
    return `${minutes} menit yang lalu`;
  }

  if (hours < 24) {
    return `${hours} jam yang lalu`;
  }

  if (days < 7) {
    return `${days} hari yang lalu`;
  }

  return notificationDate.toLocaleDateString("id-ID", {
    day: "numeric",
    month: "short",
    year: "numeric",
  });
};

/*
|--------------------------------------------------------------------------
| CLICK OUTSIDE
|--------------------------------------------------------------------------
*/

const handleClickOutside = (event) => {
  const notificationWrapper = document.querySelector(".notification-wrapper");

  if (notificationWrapper && !notificationWrapper.contains(event.target)) {
    showNotifications.value = false;
  }
};

/*
|--------------------------------------------------------------------------
| LIFECYCLE
|--------------------------------------------------------------------------
*/

onMounted(() => {
  fetchNotifications();

  document.addEventListener("click", handleClickOutside);
});

onBeforeUnmount(() => {
  document.removeEventListener("click", handleClickOutside);
});
</script>

<style scoped>
/* ========================================
   NAVBAR
======================================== */

.navbar {
  min-height: 76px;
  height: 76px;

  display: flex;
  align-items: center;

  padding: 0 28px;

  background: rgba(255, 255, 255, 0.96);

  border-bottom: 1px solid #e8edf5;

  position: sticky;
  top: 0;
  z-index: 50;

  gap: 28px;
}

/* ========================================
   TITLE
======================================== */

.navbar-title {
  min-width: 205px;

  display: flex;
  flex-direction: column;

  justify-content: center;
}

.title-main {
  display: flex;
  align-items: center;
}

.navbar-title h2 {
  margin: 0;

  font-size: 18px;
  line-height: 1.3;

  font-weight: 700;

  color: #172033;

  letter-spacing: -0.2px;
}

.navbar-title span {
  margin-top: 3px;

  font-size: 12px;

  color: #8a94a6;

  font-weight: 500;
}

/* ========================================
   SEARCH
======================================== */

.navbar-search {
  height: 42px;

  flex: 1;

  max-width: 470px;

  display: flex;
  align-items: center;

  padding: 0 12px;

  background: #f7f9fc;

  border: 1px solid #e8edf5;

  border-radius: 11px;

  color: #94a0b2;

  transition:
    border-color 0.2s ease,
    background 0.2s ease,
    box-shadow 0.2s ease;
}

.navbar-search:focus-within {
  background: #ffffff;

  border-color: #b9d2ff;

  box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.08);
}

.navbar-search input {
  flex: 1;

  width: 100%;

  border: none;
  outline: none;

  background: transparent;

  margin-left: 9px;

  font-size: 13px;

  color: #334155;
}

.navbar-search input::placeholder {
  color: #a0a9b8;
}

.search-shortcut {
  width: 22px;
  height: 22px;

  display: flex;
  align-items: center;
  justify-content: center;

  border: 1px solid #dce3ed;

  border-radius: 6px;

  background: #ffffff;

  color: #94a0b2;

  font-size: 12px;

  font-weight: 600;
}

/* ========================================
   ACTION
======================================== */

.navbar-actions {
  margin-left: auto;

  display: flex;
  align-items: center;

  gap: 12px;
}

/* ========================================
   NOTIFICATION WRAPPER
======================================== */

.notification-wrapper {
  position: relative;
}

/* ========================================
   NOTIFICATION BUTTON
======================================== */

.notification-btn {
  position: relative;

  width: 40px;
  height: 40px;

  display: flex;
  align-items: center;
  justify-content: center;

  border: 1px solid #e8edf5;

  border-radius: 10px;

  background: #ffffff;

  color: #526176;

  cursor: pointer;

  transition:
    background 0.2s ease,
    color 0.2s ease,
    border-color 0.2s ease;
}

.notification-btn:hover {
  background: #f5f8ff;

  color: #2563eb;

  border-color: #d7e4ff;
}

/* ========================================
   NOTIFICATION BADGE
======================================== */

.notification-badge {
  position: absolute;

  top: -4px;
  right: -4px;

  min-width: 17px;
  height: 17px;

  padding: 0 4px;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 999px;

  background: #2563eb;

  border: 2px solid #ffffff;

  color: #ffffff;

  font-size: 9px;

  font-weight: 700;
}

/* ========================================
   NOTIFICATION DROPDOWN
======================================== */

.notification-dropdown {
  position: absolute;

  top: calc(100% + 12px);
  right: 0;

  width: 370px;
  max-width: calc(100vw - 32px);

  overflow: hidden;

  background: #ffffff;

  border: 1px solid #e5eaf2;

  border-radius: 14px;

  box-shadow:
    0 18px 45px rgba(15, 23, 42, 0.12),
    0 4px 12px rgba(15, 23, 42, 0.06);
}

/* ========================================
   NOTIFICATION HEADER
======================================== */

.notification-header {
  min-height: 68px;

  display: flex;
  align-items: center;
  justify-content: space-between;

  padding: 14px 16px;

  border-bottom: 1px solid #edf1f6;
}

.notification-header h3 {
  margin: 0;

  color: #172033;

  font-size: 14px;

  font-weight: 700;
}

.notification-header span {
  display: block;

  margin-top: 3px;

  color: #8a94a6;

  font-size: 11px;
}

.read-all-btn {
  border: none;

  background: transparent;

  color: #2563eb;

  font-size: 11px;

  font-weight: 600;

  cursor: pointer;

  padding: 6px 4px;

  transition: color 0.2s ease;
}

.read-all-btn:hover {
  color: #1d4ed8;
}

.read-all-btn:disabled {
  opacity: 0.5;

  cursor: not-allowed;
}

/* ========================================
   NOTIFICATION LIST
======================================== */

.notification-list {
  max-height: 390px;

  overflow-y: auto;
}

.notification-list::-webkit-scrollbar {
  width: 5px;
}

.notification-list::-webkit-scrollbar-track {
  background: transparent;
}

.notification-list::-webkit-scrollbar-thumb {
  background: #d8dee8;

  border-radius: 999px;
}

/* ========================================
   NOTIFICATION ITEM
======================================== */

.notification-item {
  width: 100%;

  display: flex;
  align-items: flex-start;

  gap: 11px;

  padding: 13px 16px;

  border: none;
  border-bottom: 1px solid #f0f3f7;

  background: #ffffff;

  text-align: left;

  cursor: pointer;

  transition: background 0.2s ease;
}

.notification-item:last-child {
  border-bottom: none;
}

.notification-item:hover {
  background: #f8faff;
}

.notification-item.unread {
  background: #f7faff;
}

.notification-item.unread:hover {
  background: #f1f6ff;
}

/* ========================================
   NOTIFICATION ICON
======================================== */

.notification-icon {
  width: 34px;
  height: 34px;

  flex-shrink: 0;

  display: flex;
  align-items: center;
  justify-content: center;

  border-radius: 9px;
}

.icon-danger {
  background: #fff1f2;

  color: #e11d48;
}

.icon-success {
  background: #ecfdf5;

  color: #059669;
}

.icon-warning {
  background: #fffbeb;

  color: #d97706;
}

.icon-info {
  background: #eff6ff;

  color: #2563eb;
}

/* ========================================
   NOTIFICATION CONTENT
======================================== */

.notification-content {
  flex: 1;

  min-width: 0;
}

.notification-item-header {
  display: flex;
  align-items: center;

  gap: 7px;
}

.notification-item-header strong {
  overflow: hidden;

  text-overflow: ellipsis;
  white-space: nowrap;

  color: #253047;

  font-size: 12px;

  font-weight: 650;
}

.notification-content p {
  margin: 4px 0 0;

  display: -webkit-box;

  overflow: hidden;

  -webkit-box-orient: vertical;
  -webkit-line-clamp: 2;
  line-clamp: 2;

  color: #64748b;

  font-size: 11px;

  line-height: 1.5;
}

.notification-content small {
  display: block;

  margin-top: 5px;

  color: #9aa4b3;

  font-size: 10px;
}

/* ========================================
   UNREAD DOT
======================================== */

.unread-dot {
  width: 6px;
  height: 6px;

  flex-shrink: 0;

  border-radius: 999px;

  background: #2563eb;
}

/* ========================================
   NOTIFICATION STATE
======================================== */

.notification-state {
  min-height: 180px;

  display: flex;
  flex-direction: column;

  align-items: center;
  justify-content: center;

  gap: 7px;

  padding: 24px;

  color: #94a0b2;

  text-align: center;

  font-size: 11px;
}

.notification-state strong {
  color: #475569;

  font-size: 12px;
}

.notification-state button {
  margin-top: 5px;

  padding: 5px 9px;

  border: 1px solid #dbe3ef;

  border-radius: 7px;

  background: #ffffff;

  color: #2563eb;

  font-size: 10px;

  font-weight: 600;

  cursor: pointer;
}

.error-state {
  color: #dc2626;
}

.empty-notification-icon {
  width: 40px;
  height: 40px;

  display: flex;
  align-items: center;
  justify-content: center;

  margin-bottom: 3px;

  border-radius: 10px;

  background: #f5f7fa;

  color: #94a0b2;
}

/* ========================================
   LOADING
======================================== */

.loading-spinner {
  width: 22px;
  height: 22px;

  border: 2px solid #e5eaf2;

  border-top-color: #2563eb;

  border-radius: 50%;

  animation: notification-spin 0.7s linear infinite;
}

@keyframes notification-spin {
  to {
    transform: rotate(360deg);
  }
}

/* ========================================
   NOTIFICATION FOOTER
======================================== */

.notification-footer {
  padding: 10px 16px;

  border-top: 1px solid #edf1f6;

  background: #fafbfc;

  color: #a0a9b8;

  font-size: 10px;

  text-align: center;
}

/* ========================================
   DROPDOWN TRANSITION
======================================== */

.notification-dropdown-enter-active,
.notification-dropdown-leave-active {
  transition:
    opacity 0.18s ease,
    transform 0.18s ease;
}

.notification-dropdown-enter-from,
.notification-dropdown-leave-to {
  opacity: 0;

  transform: translateY(-6px) scale(0.98);
}

/* ========================================
   DIVIDER
======================================== */

.navbar-divider {
  width: 1px;
  height: 30px;

  background: #e8edf5;

  margin: 0 3px;
}

/* ========================================
   USER
======================================== */

.navbar-user {
  display: flex;
  align-items: center;

  gap: 10px;

  padding: 5px 8px 5px 5px;

  border-radius: 11px;

  cursor: default;

  transition: background 0.2s ease;
}

.navbar-user:hover {
  background: #f7f9fc;
}

/* ========================================
   AVATAR
======================================== */

.user-avatar {
  width: 36px;
  height: 36px;

  display: flex;
  align-items: center;
  justify-content: center;

  flex-shrink: 0;

  border-radius: 10px;

  background: linear-gradient(135deg, #2563eb, #3b82f6);

  color: #ffffff;

  box-shadow: 0 3px 8px rgba(37, 99, 235, 0.18);
}

/* ========================================
   USER INFO
======================================== */

.user-info {
  display: flex;
  flex-direction: column;

  min-width: 105px;
  max-width: 150px;
}

.user-info strong {
  overflow: hidden;

  text-overflow: ellipsis;
  white-space: nowrap;

  color: #1e293b;

  font-size: 13px;

  font-weight: 650;
}

.user-info span {
  margin-top: 2px;

  color: #8a94a6;

  font-size: 11px;

  font-weight: 500;
}

.user-chevron {
  color: #94a0b2;
}

/* ========================================
   LOGOUT
======================================== */

.logout-btn {
  width: 40px;
  height: 40px;

  display: flex;
  align-items: center;
  justify-content: center;

  border: 1px solid #e8edf5;

  border-radius: 10px;

  background: #ffffff;

  color: #64748b;

  cursor: pointer;

  transition:
    background 0.2s ease,
    color 0.2s ease,
    border-color 0.2s ease;
}

.logout-btn:hover {
  background: #fff5f5;

  color: #dc2626;

  border-color: #fecaca;
}

/* ========================================
   RESPONSIVE
======================================== */

@media (max-width: 1000px) {
  .navbar {
    padding: 0 20px;

    gap: 18px;
  }

  .navbar-title {
    min-width: 170px;
  }

  .navbar-search {
    max-width: 350px;
  }

  .user-info {
    display: none;
  }
}

@media (max-width: 760px) {
  .navbar {
    padding: 0 16px;
  }

  .navbar-title {
    min-width: auto;
  }

  .navbar-title h2 {
    font-size: 16px;
  }

  .navbar-title span {
    display: none;
  }

  .navbar-search {
    max-width: none;
  }

  .search-shortcut {
    display: none;
  }

  .navbar-divider {
    display: none;
  }

  .user-chevron {
    display: none;
  }

  .notification-dropdown {
    right: -55px;
  }
}

@media (max-width: 560px) {
  .navbar {
    gap: 10px;
  }

  .navbar-title {
    display: none;
  }

  .navbar-search {
    flex: 1;
  }

  .notification-btn,
  .logout-btn {
    width: 38px;
    height: 38px;
  }

  .navbar-actions {
    gap: 7px;
  }

  .notification-dropdown {
    position: fixed;

    top: 72px;
    left: 16px;
    right: 16px;

    width: auto;
    max-width: none;
  }
}
</style>
