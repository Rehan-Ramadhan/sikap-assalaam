import { createRouter, createWebHistory } from 'vue-router'

import Login from '../views/Auth/Login.vue'
import DashboardKesiswaan from '../views/Kesiswaan/Dashboard.vue'
import DashboardSiswa from '../views/Siswa/Dashboard.vue'
import DataSiswa from '../views/Kesiswaan/DataSiswa.vue'

const routes = [
  {
    path: '/',
    redirect: '/login'
  },
  {
    path: '/login',
    name: 'login',
    component: Login
  },
  {
    path: '/kesiswaan',
    name: 'kesiswaan.dashboard',
    component: DashboardKesiswaan,
    meta: {
      requiresAuth: true,
      role: 'kesiswaan'
    }
  },
  {
    path: '/kesiswaan/siswa',
    name: 'kesiswaan.siswa',
    component: DataSiswa,
    meta: {
      requiresAuth: true,
      role: 'kesiswaan'
    }
  },
  {
    path: '/siswa',
    name: 'siswa.dashboard',
    component: DashboardSiswa,
    meta: {
      requiresAuth: true,
      role: 'siswa'
    }
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  const userData = localStorage.getItem('user')

  let user = null

  if (userData) {
    try {
      user = JSON.parse(userData)
    } catch (error) {
      console.error('Data user tidak valid:', error)
      localStorage.removeItem('user')
    }
  }

  // Jika halaman membutuhkan login
  if (to.meta.requiresAuth) {
    // Belum login
    if (!token || !user) {
      next('/login')
      return
    }

    // Jika role tidak sesuai
    if (to.meta.role && user.role !== to.meta.role) {
      if (user.role === 'kesiswaan') {
        next('/kesiswaan')
      } else if (user.role === 'siswa') {
        next('/siswa')
      } else {
        localStorage.removeItem('token')
        localStorage.removeItem('user')
        next('/login')
      }

      return
    }
  }

  // Jika sudah login lalu membuka halaman login
  if (to.path === '/login' && token && user) {
    if (user.role === 'kesiswaan') {
      next('/kesiswaan')
      return
    }

    if (user.role === 'siswa') {
      next('/siswa')
      return
    }
  }

  next()
})

export default router