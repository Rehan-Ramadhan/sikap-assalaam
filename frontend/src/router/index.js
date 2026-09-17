import { createRouter, createWebHistory } from "vue-router";

import Login from "../views/Auth/Login.vue";
import LandingPage from "../views/Public/LandingPage.vue";
import ReportIndex from "../views/Reports/Index.vue";

// staf
import StaffDashboard from "../views/Staff/Dashboard.vue";
import StaffProfile from "../views/Staff/Profile.vue";

import StudentIndex from "../views/Staff/Students/Index.vue";
import StudentCreate from "../views/Staff/Students/Create.vue";
import StudentShow from "../views/Staff/Students/Show.vue";
import StudentEdit from "../views/Staff/Students/Edit.vue";

import ViolationIndex from "../views/Staff/Violations/Index.vue";
import ViolationCreate from "../views/Staff/Violations/Create.vue";
import ViolationShow from "../views/Staff/Violations/Show.vue";
import ViolationEdit from "../views/Staff/Violations/Edit.vue";

import ViolationCategoryIndex from "../views/Staff/Violations/Categories/Index.vue";
import ViolationCategoryCreate from "../views/Staff/Violations/Categories/Create.vue";
import ViolationCategoryShow from "../views/Staff/Violations/Categories/Show.vue";
import ViolationCategoryEdit from "../views/Staff/Violations/Categories/Edit.vue";

import AchievementIndex from "../views/Staff/Achievements/Index.vue";
import AchievementCreate from "../views/Staff/Achievements/Create.vue";
import AchievementShow from "../views/Staff/Achievements/Show.vue";
import AchievementEdit from "../views/Staff/Achievements/Edit.vue";

import AchievementCategoryIndex from "../views/Staff/Achievements/Categories/Index.vue";
import AchievementCategoryCreate from "../views/Staff/Achievements/Categories/Create.vue";
import AchievementCategoryShow from "../views/Staff/Achievements/Categories/Show.vue";
import AchievementCategoryEdit from "../views/Staff/Achievements/Categories/Edit.vue";

import InterventionIndex from "../views/Staff/Intervention/Index.vue";
import InterventionShow from "../views/Staff/Intervention/Show.vue";
import InterventionEdit from "../views/Staff/Intervention/Edit.vue";

// siswa
import SiswaDashboard from "../views/Siswa/Dashboard.vue";

import { getUser, isAuthenticated } from "../utils/auth";

const routes = [
  // public
  {
    path: "/",
    name: "landing",
    component: LandingPage,
  },
  {
    path: "/login",
    name: "login",
    component: Login,
  },

  // staf
  {
    path: "/staf",
    name: "staf.dashboard",
    component: StaffDashboard,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/profil",
    name: "staf.profil",
    component: StaffProfile,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },

  // siswa
  {
    path: "/staf/siswa",
    name: "staf.siswa",
    component: StudentIndex,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/siswa/create",
    name: "staf.siswa.create",
    component: StudentCreate,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/siswa/:id",
    name: "staf.siswa.show",
    component: StudentShow,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/siswa/:id/edit",
    name: "staf.siswa.edit",
    component: StudentEdit,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },

  // pelanggaran
  {
    path: "/staf/pelanggaran",
    name: "staf.pelanggaran",
    component: ViolationIndex,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/pelanggaran/create",
    name: "staf.pelanggaran.create",
    component: ViolationCreate,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/pelanggaran/:id",
    name: "staf.pelanggaran.show",
    component: ViolationShow,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/pelanggaran/:id/edit",
    name: "staf.pelanggaran.edit",
    component: ViolationEdit,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },

    // kategori pelanggaran
  {
    path: "/staf/pelanggaran/kategori",
    name: "staf.pelanggaran.kategori",
    component: ViolationCategoryIndex,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/pelanggaran/kategori/create",
    name: "staf.pelanggaran.kategori.create",
    component: ViolationCategoryCreate,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/pelanggaran/kategori/:id",
    name: "staf.pelanggaran.kategori.show",
    component: ViolationCategoryShow,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/pelanggaran/kategori/:id/edit",
    name: "staf.pelanggaran.kategori.edit",
    component: ViolationCategoryEdit,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },

  // prestasi
  {
    path: "/staf/prestasi",
    name: "staf.prestasi",
    component: AchievementIndex,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/prestasi/create",
    name: "staf.prestasi.create",
    component: AchievementCreate,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/prestasi/:id",
    name: "staf.prestasi.show",
    component: AchievementShow,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/prestasi/:id/edit",
    name: "staf.prestasi.edit",
    component: AchievementEdit,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },

  // kategori prestasi
  {
    path: "/staf/prestasi/kategori",
    name: "staf.prestasi.kategori",
    component: AchievementCategoryIndex,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/prestasi/kategori/create",
    name: "staf.prestasi.kategori.create",
    component: AchievementCategoryCreate,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/prestasi/kategori/:id",
    name: "staf.prestasi.kategori.show",
    component: AchievementCategoryShow,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/prestasi/kategori/:id/edit",
    name: "staf.prestasi.kategori.edit",
    component: AchievementCategoryEdit,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },

  // penanganan
  {
    path: "/staf/penanganan",
    name: "staf.penanganan",
    component: InterventionIndex,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/penanganan/:id",
    name: "staf.penanganan.show",
    component: InterventionShow,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },
  {
    path: "/staf/penanganan/:id/edit",
    name: "staf.penanganan.edit",
    component: InterventionEdit,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },

  // laporan
  {
    path: "/staf/laporan",
    name: "staf.laporan",
    component: ReportIndex,
    meta: {
      requiresAuth: true,
      role: "staff",
    },
  },

  // siswa
  {
    path: "/siswa",
    name: "siswa.dashboard",
    component: SiswaDashboard,
    meta: {
      requiresAuth: true,
      role: "student",
    },
  },
  {
    path: "/siswa/laporan",
    name: "siswa.laporan",
    component: ReportIndex,
    meta: {
      requiresAuth: true,
      role: "student",
    },
  },
];

// router
const router = createRouter({
  history: createWebHistory(),
  routes,
});

// guard
router.beforeEach((to) => {
  const authenticated = isAuthenticated();
  const user = getUser();

  if (to.meta.requiresAuth) {
    if (!authenticated || !user) {
      return "/login";
    }

    if (to.meta.role && user.role !== to.meta.role) {
      if (user.role === "staff") {
        return "/staf";
      }

      if (user.role === "student") {
        return "/siswa";
      }

      return "/login";
    }
  }

  if (to.path === "/login" && authenticated && user) {
    if (user.role === "staff") {
      return "/staf";
    }

    if (user.role === "student") {
      return "/siswa";
    }
  }

  return true;
});

export default router;
