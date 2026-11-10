
import api from './api'

export const tipoEquipoService = {

    getAll(params) {
        return api.get('/tipos-equipos', { params })
    },

    getSelect(params = {}) {
        return api.get('/tipos-equipos/select', { params })
            .then(response => response.data.data)
    },

    find(id) {
        return api.get(`/tipos-equipos/${id}`)
    },

    create(data) {
        return api.post('/tipos-equipos', data)
    },

    update(id, data) {
        return api.put(`/tipos-equipos/${id}`, data)
    },

    remove(id) {
        return api.delete(`/tipos-equipos/${id}`)
    },

}

export default tipoEquipoService
