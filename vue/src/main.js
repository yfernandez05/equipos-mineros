import 'bootstrap/dist/css/bootstrap.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';
import './utils/loader';
import './utils/appConfig.js';
import './utils/plugins/sweetalert.js';

import './assets/styles/variables.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'

import setupAxiosInterceptors from './interceptors/axiosInterceptor';
import { useAuthStore } from './stores/auth';
import { healthService } from './services/healthService'

const app = createApp(App);

const pinia = createPinia();

app.use(pinia);
app.use(router);

setupAxiosInterceptors();

app.mount('#app');

showPreloader()

const authStore = useAuthStore(pinia)

try {
    await authStore.restoreSession()
    healthService.check()
    .then(response => {
        console.log('Conexión exitosa:', response.data)
    })
    .catch(error => {
        console.error('Error de conexión:', error)
    })
} finally {
    hidePreloader()
}

