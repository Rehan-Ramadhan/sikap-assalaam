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


      <!-- ================================ -->
      <!-- STAFF -->
      <!-- ================================ -->

      <template v-if="role === 'staf'">

        <!-- Dashboard -->
        <router-link
          to="/staff"
          class="sidebar-menu"
          exact-active-class="active"
        >
          <LayoutDashboard :size="19" />
          <span>Dashboard</span>
        </router-link>


        <!-- Data Siswa -->
        <router-link
          to="/staff/siswa"
          class="sidebar-menu"
          active-class="active"
        >
          <Users :size="19" />
          <span>Data Siswa</span>
        </router-link>


        <!-- Pelanggaran -->
        <router-link
          to="/staff/pelanggaran"
          class="sidebar-menu"
          active-class="active"
        >
          <TriangleAlert :size="19" />
          <span>Pelanggaran</span>
        </router-link>


        <!-- Prestasi -->
        <router-link
          to="/staff/prestasi"
          class="sidebar-menu"
          active-class="active"
        >
          <Trophy :size="19" />
          <span>Prestasi</span>
        </router-link>


        <!-- Penanganan -->
        <router-link
          to="/staff/penanganan"
          class="sidebar-menu"
          active-class="active"
        >
          <ClipboardList :size="19" />
          <span>Penanganan</span>
        </router-link>


        <!-- Laporan -->
        <router-link
          to="/staff/laporan"
          class="sidebar-menu"
          active-class="active"
        >
          <ChartNoAxesCombined :size="19" />
          <span>Laporan</span>
        </router-link>

      </template>


      <!-- ================================ -->
      <!-- SISWA -->
      <!-- ================================ -->

      <template v-else-if="role === 'siswa'">

        <!-- Dashboard -->
        <router-link
          to="/siswa"
          class="sidebar-menu"
          exact-active-class="active"
        >
          <LayoutDashboard :size="19" />
          <span>Dashboard</span>
        </router-link>


        <!-- Profil -->
        <router-link
          to="/siswa/profile"
          class="sidebar-menu"
          active-class="active"
        >
          <User :size="19" />
          <span>Profil</span>
        </router-link>


        <!-- Pelanggaran -->
        <router-link
          to="/siswa/pelanggaran"
          class="sidebar-menu"
          active-class="active"
        >
          <TriangleAlert :size="19" />
          <span>Pelanggaran</span>
        </router-link>


        <!-- Prestasi -->
        <router-link
          to="/siswa/prestasi"
          class="sidebar-menu"
          active-class="active"
        >
          <Trophy :size="19" />
          <span>Prestasi</span>
        </router-link>


        <!-- Intervensi -->
        <router-link
          to="/siswa/intervensi"
          class="sidebar-menu"
          active-class="active"
        >
          <ClipboardList :size="19" />
          <span>Intervensi</span>
        </router-link>


        <!-- Notifikasi -->
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

