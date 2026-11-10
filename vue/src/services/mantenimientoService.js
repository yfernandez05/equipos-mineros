
import api from './api'

export const mantenimientoService = {

    getAll(params) {
        return api.get('mantenimientos', { params })
    },

    getByEquipo(equipoId, params = {}) {
        return api.get(`mantenimientos/equipo/${equipoId}`, { params })
    },

    create(data) {
        return api.post('mantenimientos', data)
    },

}

export default mantenimientoService
