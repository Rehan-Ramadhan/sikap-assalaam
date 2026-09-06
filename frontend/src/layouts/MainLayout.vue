<template>
  <div class="layout">
    <Sidebar />

    <div class="layout-content">
      <Navbar />

      <main class="page-content">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import Sidebar from '../components/Sidebar.vue'
import Navbar from '../components/Navbar.vue'
</script>