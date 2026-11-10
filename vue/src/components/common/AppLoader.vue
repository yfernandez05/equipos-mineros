<template>
    <div class="preloader">
        <div class="loader-spinner"></div>
        <span>Cargando...</span>
    </div>
</template>

<style scoped>
.preloader {
    position: fixed;
    inset: 0;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    background: rgba(255, 255, 255, 0.75);
    backdrop-filter: blur(2px);
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
    transition: opacity 0.2s ease, visibility 0.2s ease;
}

.preloader.show {
    opacity: 1;
    visibility: visible;
    pointer-events: auto;
}

.loader-spinner {
    width: 40px;
    height: 40px;
    border: 4px solid #e5e7eb;
    border-top-color: #198754;
    border-radius: 50%;
    animation: spin 0.7s linear infinite;
}

@keyframes spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
