<template>
    <header class="header-modern">
        <nav class="navbar navbar-expand px-4">

            <!-- Lado izquierdo -->
            <div class="d-flex align-items-center">
                <button type="button" class="btn sidebar-toggle me-3" @click="toggleSidebar"
                    aria-label="Mostrar u ocultar menú">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="header-brand d-flex align-items-center">

                    <div class="brand-text">
                        <span class="brand-title">MINING ASSETS</span>
                    </div>
                </div>
            </div>

            <!-- Lado derecho: usuario -->
            <div class="dropdown ms-auto">
                <button type="button" class="btn user-dropdown d-flex align-items-center" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <div class="user-avatar me-2">
                        <i class="fas fa-user"></i>
                    </div>

                    <div class="user-info text-start d-none d-sm-flex flex-column">
                        <span class="user-name">
                            {{ authStore.user?.name || 'Usuario' }}
                        </span>
                        <small class="user-role">Administrador</small>
                    </div>

                    <i class="fas fa-chevron-down dropdown-arrow ms-3"></i>
                </button>

                <ul class="dropdown-menu dropdown-menu-end user-menu shadow">
                    <li>
                        <div class="user-menu-header">
                            <div class="user-avatar user-avatar-large">
                                <i class="fas fa-user"></i>
                            </div>

                            <div class="mt-2">
                                <h6 class="mb-1">
                                    {{ authStore.user?.name || 'Usuario' }}
                                </h6>
                                <small>
                                    {{ authStore.user?.email || '' }}
                                </small>
                            </div>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider">
                    </li>

                    <li>
                        <button type="button" class="dropdown-item logout-option" @click="logout">
                            <i class="fas fa-sign-out-alt me-2"></i>
                            Cerrar sesión
                        </button>
                    </li>
                </ul>
            </div>

        </nav>
    </header>
</template>

<script setup>
import { useRouter } from 'vue-router'
import authService from '../../services/authService'
import { useAuthStore } from '../../stores/auth'

const authStore = useAuthStore()
const router = useRouter()

const emit = defineEmits(['toggle-sidebar'])

const toggleSidebar = () => {
    emit('toggle-sidebar')
}

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

