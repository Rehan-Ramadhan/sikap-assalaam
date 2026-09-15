<template>
  <header class="navbar">

    <!-- JUDUL -->
    <div class="navbar-title">
      <h2>{{ dashboardTitle }}</h2>
      <span>SIKAP Assalaam</span>
    </div>


    <!-- SEARCH -->
    <div class="navbar-search">
      <Search :size="18" />

      <input
        type="text"
        placeholder="Cari siswa, pelanggaran, prestasi..."
      />
    </div>


    <!-- ACTION -->
    <div class="navbar-actions">

      <!-- NOTIFICATION -->
      <button class="notification-btn">
        <Bell :size="19" />

        <span class="notification-badge">
          8
        </span>
      </button>


      <!-- USER -->
      <div class="navbar-user">

        <div class="user-avatar">
          <User :size="19" />
        </div>

        <div class="user-info">
          <strong>{{ userName }}</strong>
          <span>{{ positionLabel }}</span>
        </div>

        <ChevronDown :size="17" />

      </div>


      <!-- LOGOUT -->
      <button
        class="logout-btn"
        @click="$emit('logout')"
      >
        <LogOut :size="17" />
        <span>Logout</span>
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

import { getUser, getStaffPosition } from '../../utils/auth'

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