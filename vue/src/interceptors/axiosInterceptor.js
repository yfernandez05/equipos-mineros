import api from '../services/api';
import { useAuthStore } from '../stores/auth';

import router from '../router';

const setupAxiosInterceptors = () => {
    // Interceptor de solicitudes
    api.interceptors.request.use(
        (config) => { 

            const authStore = useAuthStore();

            if (authStore.token) {
                config.headers.Authorization = `Bearer ${authStore.token}`;
            }
            return config;
        },
        (error) => {
            return Promise.reject(error);
        },
    )

    // Interceptor de respuestas
    api.interceptors.response.use(
        (response) => {

            return response
        },
        async (error) => {

            if (error.response?.status === 401) {
                const authStore = useAuthStore()

                authStore.clearSession()

                if (router.currentRoute.value.name !== 'login') {
                    await router.push({ name: 'login' })
                }
            }

            return Promise.reject(error)
        },
    )
}

export default setupAxiosInterceptors