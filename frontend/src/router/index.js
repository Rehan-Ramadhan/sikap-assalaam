import { createRouter, createWebHistory } from 'vue-router'

import Login from '../views/Auth/Login.vue'

import StaffDashboard from '../views/Staff/Dashboard.vue'
import StaffProfile from '../views/Staff/Profile.vue'
import StudentIndex from '../views/Staff/Students/Index.vue'
import StudentCreate from '../views/Staff/Students/Create.vue'
import StudentShow from '../views/Staff/Students/Show.vue'
import StudentEdit from '../views/Staff/Students/Edit.vue'
import ViolationIndex from '../views/Staff/Violations/Index.vue'
import ViolationCreate from '../views/Staff/Violations/Create.vue'
import ViolationShow from '../views/Staff/Violations/Show.vue'
import ViolationEdit from '../views/Staff/Violations/Edit.vue'

import SiswaDashboard from '../views/Siswa/Dashboard.vue'
// import SiswaProfile from '../views/Siswa/Profile.vue'
// import SiswaViolations from '../views/Siswa/Violations.vue'
// import SiswaAchievements from '../views/Siswa/Achievements.vue'
// import SiswaInterventions from '../views/Siswa/Interventions.vue'
// import SiswaNotifications from '../views/Siswa/Notifications.vue'

import {
  getUser,
  isAuthenticated
} from '../utils/auth'
const routes = [

  // ========================================
  // PUBLIC
  // ========================================

  {
    path: '/',
    redirect: '/login'
  },

  {
    path: '/login',
    name: 'login',
    component: Login
  },


  // ========================================
  // STAFF
  // ========================================

  {
    path: '/staff',
    name: 'staff.dashboard',
    component: StaffDashboard,

    meta: {
      requiresAuth: true,
      role: 'staf'
    }
  },

  {
    path: '/staff/profile',
    name: 'staff.profile',
    component: StaffProfile,

    meta: {
      requiresAuth: true,
      role: 'staf'
    }
  },

  {
    path: '/staff/siswa',
    name: 'staff.students',
    component: StudentIndex,

    meta: {
      requiresAuth: true,
      role: 'staf'
    }
  },
  {
    path: '/staff/siswa/create',
    name: 'staff-siswa-create',
    component: StudentCreate,
    meta: {
      requiresAuth: true,
      role: 'staf'
    }
  },
  {
    path: '/staff/siswa/:id',
    name: 'staff.students.show',
    component: StudentShow,
    meta: {
      requiresAuth: true,
      role: 'staf'
    }
},
{
    path: '/staff/siswa/:id/edit',
    name: 'staff-siswa-edit',
    component: StudentEdit,
    meta: {
      requiresAuth: true,
      role: 'staf'
    }
  },

  {
    path: '/staff/pelanggaran',
    name: 'staff.pelanggaran',
    component: ViolationIndex,
    meta: {
      requiresAuth: true,
      role: 'staf'
    }
  },

  {
    path: '/staff/pelanggaran/create',
    name: 'staff.pelanggaran.create',
    component: ViolationCreate,
    meta: {
      requiresAuth: true,
      role: 'staf'
    }
  },

  {
    path: '/staff/pelanggaran/:id',
    name: 'staff.pelanggaran.show',
    component: ViolationShow,
    meta: {
      requiresAuth: true,
      role: 'staf'
    }
  },

  {
    path: '/staff/pelanggaran/:id/edit',
    name: 'staff.pelanggaran.edit',
    component: ViolationEdit,
    meta: {
      requiresAuth: true,
      role: 'staf'
    }
  },

  

  // ========================================
  // SISWA
  // ========================================

  {
    path: '/siswa',
    name: 'siswa.dashboard',
    component: SiswaDashboard,

    meta: {
      requiresAuth: true,
      role: 'siswa'
    }
  },

  // {
  //   path: '/siswa/profile',
  //   name: 'siswa.profile',
  //   component: SiswaProfile,

  //   meta: {
  //     requiresAuth: true,
  //     role: 'siswa'
  //   }
  // },

  // {
  //   path: '/siswa/pelanggaran',
  //   name: 'siswa.pelanggaran',
  //   component: SiswaViolations,

  //   meta: {
  //     requiresAuth: true,
  //     role: 'siswa'
  //   }
  // },

  // {
  //   path: '/siswa/prestasi',
  //   name: 'siswa.prestasi',
  //   component: SiswaAchievements,

  //   meta: {
  //     requiresAuth: true,
  //     role: 'siswa'
  //   }
  // },

  // {
  //   path: '/siswa/intervensi',
  //   name: 'siswa.intervensi',
  //   component: SiswaInterventions,

  //   meta: {
  //     requiresAuth: true,
  //     role: 'siswa'
  //   }
  // },

  // {
  //   path: '/siswa/notifikasi',
  //   name: 'siswa.notifikasi',
  //   component: SiswaNotifications,

  //   meta: {
  //     requiresAuth: true,
  //     role: 'siswa'
  //   }
  // }

]


// ========================================
// ROUTER
// ========================================

const router = createRouter({
  history: createWebHistory(),
  routes
})


// ========================================
// NAVIGATION GUARD
// ========================================

router.beforeEach((to) => {
  const authenticated = isAuthenticated()
  const user = getUser()

  // ======================================
  // HALAMAN MEMBUTUHKAN LOGIN
  // ======================================

  if (to.meta.requiresAuth) {
    if (!authenticated || !user) {
      return '/login'
    }

    // Role tidak sesuai
    if (to.meta.role && user.role !== to.meta.role) {
      if (user.role === 'staf') {
        return '/staff'
      }

      if (user.role === 'siswa') {
        return '/siswa'
      }

      return '/login'
    }
  }

  // ======================================
  // SUDAH LOGIN TAPI BUKA LOGIN
  // ======================================

  if (to.path === '/login' && authenticated && user) {
    if (user.role === 'staf') {
      return '/staff'
    }

    if (user.role === 'siswa') {
      return '/siswa'
    }
  }

  return true
})
export default router