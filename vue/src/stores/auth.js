import { defineStore } from 'pinia'
import authService from '../services/authService'

export const useAuthStore = defineStore('auth', {
    state: () => ({
        token: sessionStorage.getItem('access_token'),
        user: null,
        isAuthenticated: !!sessionStorage.getItem('access_token'),
    }),

    actions: {
        setSession(token, user) {
            this.token = token;
            this.user = user;
            this.isAuthenticated = true;

            sessionStorage.setItem('access_token', token);
        },

        clearSession() {
            this.token = null;
            this.user = null;
            this.isAuthenticated = false;

            sessionStorage.removeItem('access_token');
        },

        async restoreSession() {
            if (!this.token) {
                return false;
            }

            try {
                const response = await authService.getCurrentUser();

                this.user = response.data.user;
                this.isAuthenticated = true;

                return true;
            } catch (error) {
                this.clearSession();
                return false;
            }
        },
    },
})