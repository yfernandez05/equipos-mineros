import api from './api'

export const healthService = {
    check() {
        return api.get('/health')
    }
}