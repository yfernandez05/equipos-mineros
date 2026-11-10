import api from './api'

const authService = {
    login(credentials) {
        return api.post('/login', credentials)
    },

    register(data) {
        return api.post('/register', data)
    },

    getCurrentUser() {
        return api.get('/me')
    },

    logout() {
        return api.post('/logout')
    },
}

export default authService