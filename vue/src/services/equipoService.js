
import api from './api'

export const equipoService = {

    getAll(params) {
        return api.get('/equipos', { params })
    },

    find(id) {
        return api.get(`/equipos/${id}`)
    },

    create(data) {
        return api.post('/equipos', data)
    },

    update(id, data) {
        return api.put(`/equipos/${id}`, data)
    },

    remove(id) {
        return api.delete(`/equipos/${id}`)
    },

}

export default equipoService
