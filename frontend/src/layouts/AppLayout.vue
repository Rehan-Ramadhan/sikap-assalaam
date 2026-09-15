<template>
  <div class="layout">

    <AppSidebar :role="userRole" />

    <div class="content">

      <AppHeader @logout="handleLogout" />

      <main class="page-content">
        <slot />
      </main>

    </div>

  </div>
</template>


<script setup>
import { computed } from 'vue'
import { useRouter } from 'vue-router'

import AppSidebar from '../components/dashboard/AppSidebar.vue'
import AppHeader from '../components/dashboard/AppHeader.vue'

import { getUser, logout } from '../utils/auth'

const router = useRouter()

const user = getUser()

const userRole = computed(() => {
  return user?.role || ''
})

const handleLogout = () => {
  logout()
  router.push('/login')
}
</script>