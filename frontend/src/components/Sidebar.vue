<template>
  <aside class="sidebar">

    <!-- LOGO -->
    <div class="sidebar-brand">
      <div class="brand-icon">
        <GraduationCap :size="27" />
      </div>

      <div class="brand-text">
        <strong>SIKAP</strong>
        <span>ASSALAAM</span>
      </div>
    </div>


    <!-- MENU UTAMA -->
    <div class="sidebar-section">

      <div class="sidebar-label">
        MENU UTAMA
      </div>


      <!-- KESISWAAN -->
      <template v-if="role === 'kesiswaan'">

        <router-link
          to="/kesiswaan"
          class="sidebar-menu"
          exact-active-class="active"
        >
          <LayoutDashboard :size="19" />
          <span>Dashboard</span>
        </router-link>


        <router-link
          to="/kesiswaan/siswa"
          class="sidebar-menu"
          active-class="active"
        >
          <Users :size="19" />
          <span>Data Siswa</span>
        </router-link>


        <router-link
          to="/kesiswaan/pelanggaran"
          class="sidebar-menu"
          active-class="active"
        >
          <TriangleAlert :size="19" />
          <span>Pelanggaran</span>
        </router-link>


        <router-link
          to="/kesiswaan/prestasi"
          class="sidebar-menu"
          active-class="active"
        >
          <Trophy :size="19" />
          <span>Prestasi</span>
        </router-link>


        <router-link
          to="/kesiswaan/penanganan"
          class="sidebar-menu"
          active-class="active"
        >
          <ClipboardList :size="19" />
          <span>Penanganan</span>
        </router-link>


        <router-link
          to="/kesiswaan/laporan"
          class="sidebar-menu"
          active-class="active"
        >
          <ChartNoAxesCombined :size="19" />
          <span>Laporan</span>
        </router-link>

      </template>


      <!-- SISWA -->
      <template v-else>

        <router-link
          to="/siswa"
          class="sidebar-menu"
          exact-active-class="active"
        >
          <LayoutDashboard :size="19" />
          <span>Dashboard</span>
        </router-link>


        <router-link
          to="/siswa/profil"
          class="sidebar-menu"
          active-class="active"
        >
          <User :size="19" />
          <span>Profil</span>
        </router-link>


        <router-link
          to="/siswa/riwayat"
          class="sidebar-menu"
          active-class="active"
        >
          <History :size="19" />
          <span>Riwayat</span>
        </router-link>


        <router-link
          to="/siswa/notifikasi"
          class="sidebar-menu"
          active-class="active"
        >
          <Bell :size="19" />
          <span>Notifikasi</span>

          <span class="menu-badge">
            3
          </span>
        </router-link>

      </template>

    </div>
   
    <!-- FOOTER -->
    <div class="sidebar-footer">

      <div class="school-info">
        <div class="school-icon">
          <School :size="19" />
        </div>

        <div>
          <strong>Assalaam</strong>
          <span>Sistem Informasi</span>
        </div>
      </div>

    </div>

  </aside>
</template>


<script setup>
import {
  GraduationCap,
  LayoutDashboard,
  Users,
  TriangleAlert,
  Trophy,
  ClipboardList,
  ChartNoAxesCombined,
  User,
  History,
  Bell,
  School
} from 'lucide-vue-next'

defineProps({
  role: {
    type: String,
    default: ''
  }
})
</script>