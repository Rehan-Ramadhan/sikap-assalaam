<template>
  <header class="navbar">

    <!-- JUDUL -->
    <div class="navbar-title">
      <h2>Dashboard Kesiswaan</h2>
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
          <strong>Admin Kesiswaan</strong>
          <span>Kesiswaan</span>
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
import {
  Search,
  Bell,
  User,
  ChevronDown,
  LogOut
} from 'lucide-vue-next'

defineEmits(['logout'])
</script>