import { createRouter, createWebHistory } from "vue-router";

import Login from "../views/Auth/Login.vue";
import DashboardAdmin from "../views/Admin/Dashboard.vue";
import DashboardUser from "../views/User/Dashboard.vue";
import StudentIndex from "../views/Admin/Student/Index.vue";

const routes = [
  {
    path: "/",
    redirect: "/login",
  },
  {
    path: "/login",
    name: "login",
    component: Login,
  },
  {
    path: "/admin",
    name: "admin.dashboard",
    component: DashboardAdmin,
    meta: {
      requiresAuth: true,
      role: "staf",
    },
  },
  {
    path: "/admin/user",
    name: "admin.user",
    component: Datauser,
    meta: {
      requiresAuth: true,
      role: "staf",
    },
  },
  {
    path: "/user",
    name: "user.dashboard",
    component: StudentIndex,
    meta: {
      requiresAuth: true,
      role: "siswa",
    },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem("token");
  const userData = localStorage.getItem("user");

  let user = null;

  if (userData) {
    try {
      user = JSON.parse(userData);
    } catch (error) {
      console.error("Data user tidak valid:", error);
      localStorage.removeItem("user");
    }
  }

  if (to.meta.requiresAuth) {
    if (!token || !user) {
      next("/login");
      return;
    }

    if (to.meta.role && user.role !== to.meta.role) {
      if (user.role === "admin") {
        next("/admin");
      } else if (user.role === "siswa") {
        next("/siswa");
      } else {
        localStorage.removeItem("token");
        localStorage.removeItem("user");
        next("/login");
      }

      return;
    }
  }

  if (to.path === "/login" && token && user) {
    if (user.role === "admin") {
      next("/admin");
      return;
    }

    if (user.role === "siswa") {
      next("/siswa");
      return;
    }
  }

  next();
});

export default router;
