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

      <input
        type="text"
        placeholder="Cari siswa, pelanggaran, prestasi..."
      />

      <span class="search-shortcut">
        /
      </span>
    </div>


    <!-- ACTION -->
    <div class="navbar-actions">

      <!-- NOTIFICATION -->
      <button
        class="notification-btn"
        title="Notifikasi"
      >
        <Bell :size="19" />

        <span class="notification-badge">
          8
        </span>
      </button>


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

        <ChevronDown
          class="user-chevron"
          :size="16"
        />

      </div>


      <!-- LOGOUT -->
      <button
        class="logout-btn"
        title="Logout"
        @click="$emit('logout')"
      >
        <LogOut :size="17" />
      </button>

    </div>

  </header>
</template>


<script setup>
import { computed } from 'vue'

import {
  Search,
  Bell,
  User,
  ChevronDown,
  LogOut
} from 'lucide-vue-next'

import {
  getUser,
  getStaffPosition
} from '../../utils/auth'

defineEmits(['logout'])

const user = getUser()
const jabatan = getStaffPosition()


const positionNames = {
  wali_kelas: 'Wali Kelas',
  bk: 'BK',
  kesiswaan: 'Kesiswaan',
  kepala_sekolah: 'Kepala Sekolah'
}


const positionLabel = computed(() => {
  if (user?.role === 'siswa') {
    return 'Siswa'
  }

  return positionNames[jabatan] || 'Staf'
})


const userName = computed(() => {
  if (user?.role === 'siswa') {
    return user?.name || user?.username || 'Siswa'
  }

  return user?.staff?.nama || user?.name || user?.username || 'Staf'
})


const dashboardTitle = computed(() => {
  if (user?.role === 'siswa') {
    return 'Dashboard Siswa'
  }

  return `Dashboard ${positionNames[jabatan] || 'Staf'}`
})
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

  box-shadow:
    0 0 0 3px rgba(37, 99, 235, 0.08);
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
   NOTIFICATION
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


/* AVATAR */

.user-avatar {
  width: 36px;
  height: 36px;

  display: flex;
  align-items: center;
  justify-content: center;

  flex-shrink: 0;

  border-radius: 10px;

  background: linear-gradient(
    135deg,
    #2563eb,
    #3b82f6
  );

  color: #ffffff;

  box-shadow:
    0 3px 8px rgba(37, 99, 235, 0.18);
}


/* USER INFO */

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
}
</style>