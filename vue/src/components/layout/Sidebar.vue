<template>
  <aside class="sidebar" :class="{ 'sidebar-closed': !sidebarOpen }">

    <!-- Navegación -->
    <nav class="sidebar-navigation">

      <!-- General -->
      <div class="sidebar-section">
        <span class="section-title">GENERAL</span>

        <RouterLink :to="{ name: 'dashboard' }" class="sidebar-link">
          <i class="fas fa-chart-pie"></i>
          <span>Dashboard</span>
        </RouterLink>
      </div>

      <!-- Gestión de activos -->
      <div class="sidebar-section">
        <span class="section-title">GESTIÓN DE ACTIVOS</span>

        <RouterLink :to="{ name: 'spa.equipo' }" class="sidebar-link">
          <i class="fas fa-truck"></i>
          <span>Equipos</span>
        </RouterLink>

        <RouterLink :to="{ name: 'spa.tiposEquipo' }" class="sidebar-link">
          <i class="fas fa-layer-group"></i>
          <span>Tipos de equipos</span>
        </RouterLink>

        <RouterLink :to="{ name: 'spa.mantenimiento' }" class="sidebar-link">
          <i class="fas fa-tools"></i>
          <span>Mantenimientos</span>
        </RouterLink>
      </div>

    </nav>
  </aside>
</template>

<script setup>
defineProps({
  sidebarOpen: {
    type: Boolean,
    default: true
  }
})
</script>
