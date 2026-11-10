import { createRouter, createWebHistory } from 'vue-router';
import { useAuthStore } from '../stores/auth'
import LoginView from '../views/LoginView.vue';
import MainLayout from '../layouts/MainLayout.vue'

import dashboardRoutes from './modules/dashboardRoutes';

import tipoEquipoRoutes from './modules/tipoEquipoRoutes';
import equipoRoutes from './modules/equipoRoutes';
import mantenimientoRoutes from './modules/mantenimientoRoutes';


const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),

  routes: [
    {
      path: '/',
      redirect: '/login',
    },
    {
      path: '/login',
      name: 'login',
      component: LoginView,
    },
    {
      path: '/',
      component: MainLayout,
      meta: {
        requiresAuth: true,
      },
      children: [
        ...dashboardRoutes,    
        ...dashboardRoutes,
        ...tipoEquipoRoutes,
        ...equipoRoutes,
        ...mantenimientoRoutes,    
      ],
    },
  ],
})

router.beforeEach(async (to) => {
  const authStore = useAuthStore()

  if (authStore.token && !authStore.user) {
    await authStore.restoreSession()
  }

  if (to.meta.requiresAuth && !authStore.isAuthenticated) {
    return { name: 'login' }
  }

  if (to.name === 'login' && authStore.isAuthenticated) {
    return { name: 'dashboard' }
  }

  return true
})

export default router