<template>
  <div>
    <h1>Dashboard</h1>

    <p>Usuario autenticado correctamente.</p>

    <button class="btn btn-danger rounded" type="button" @click="logout">
      Cerrar sesión
    </button>
  </div>
</template>

<script setup>
import { useRouter } from 'vue-router'

import authService from '../services/authService'
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()
const router = useRouter()

const logout = async () => {
try {
    await authService.logout()
  } catch (error) {
    console.error('Error al cerrar sesión:', error)
  } finally {
    authStore.clearSession()
    await router.push({ name: 'login' })

  }
}
</script>