<template>
  <div class="layout">

    <Sidebar :role="role"/>

    <div class="content">

      <Navbar @logout="logout"/>

      <main class="page-content">
        <slot></slot>
      </main>

    </div>

  </div>
</template>

<script setup>
import Navbar from './Navbar.vue'
import Sidebar from './Sidebar.vue'

import { useRouter } from 'vue-router'

const props = defineProps({
  role: String
})

const router = useRouter()

const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')

  router.push('/login')
}
</script>