<template>
  <div class="container p-4 card my-5">
    <h1>Iniciar sesión</h1>

    <form @submit.prevent="login">
      <div>
        <label for="email">Correo electrónico</label>
        <input class="form-control mb-3" id="email" v-model="form.email" type="email" name="email" required />
      </div>

      <div>
        <label for="password">Contraseña</label>
        <input class="form-control mb-3" id="password" v-model="form.password" type="password" name="password"
          required />
      </div>
      <div v-if="errorMessage" class="alert alert-danger py-2 small">
        {{ errorMessage }}
      </div>

      <button class="btn btn-primary rounded-3" :disabled="loading">
        {{ loading ? "Ingresando..." : "Iniciar sesión" }}
      </button>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { useRouter } from 'vue-router'

import authService from '../services/authService'
import { useAuthStore } from '../stores/auth'

const form = reactive({
  email: 'yhonatan@test.com',
  password: 'password123',
})

const loading = ref(false)

const authStore = useAuthStore();
const router = useRouter();
const errorMessage = ref('');

const login = async () => {
  loading.value = true

  try {

    const response = await authService.login(form)
    authStore.setSession(response.data.access_token, response.data.user,)
    await router.push({ name: 'dashboard' })

  } catch (error) {

    console.error('Error en el login:', error.response?.data?.message)
    if (error.response?.status == 401) {
      errorMessage.value = error.response?.data?.message;
    }

  } finally {
    loading.value = false
  }

}
</script>