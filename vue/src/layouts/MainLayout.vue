
<template>
    <div class="main-layout">

        <!-- Barra superior -->
        <Header @toggle-sidebar="toggleSidebar" />

        <!-- Cuerpo de la aplicación -->
        <div class="layout-body">

            <!-- Menú lateral -->
            <Sidebar :sidebarOpen="sidebarOpen" />

            <!-- Contenido principal -->
            <main class="main-content">
                <div class="content-wrapper">
                    <RouterView />
                </div>
            </main>

        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'

import Header from '../components/layout/Header.vue'
import Sidebar from '../components/layout/Sidebar.vue'

// Controla la visibilidad del menú lateral
const sidebarOpen = ref(true)

// Abre o cierra el menú
const toggleSidebar = () => {
    sidebarOpen.value = !sidebarOpen.value
}
</script>
