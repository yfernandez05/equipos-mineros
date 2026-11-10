
export function getApiErrorMessage(error) {
    const status = error.response?.status
    const data = error.response?.data

    if (!error.response) {
        return 'No se pudo conectar con el servidor.'
    }

    switch (status) {
        case 401:
            return 'Tu sesión ha expirado.'

        case 403:
            return 'No tienes permiso para realizar esta operación.'

        case 404:
            return 'No se encontró el recurso solicitado.'

        case 409:
            return data?.message || 'Existe un conflicto con los datos.'

        case 422:
            return data?.message || 'Revisa los datos ingresados.'

        case 503:
            return 'El servicio no está disponible temporalmente.'

        default:
            if (status >= 500) {
                return 'Ocurrió un error en el servidor.'
            }

            return data?.message || 'No se pudo completar la operación.'
    }
}

export function getApiValidationErrors(error) {
    if (error.response?.status === 422) {
        return error.response.data.errors || {}
    }

    return {}
}
