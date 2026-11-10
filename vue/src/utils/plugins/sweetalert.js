import Swal from 'sweetalert2';

// Configuración general
const optionsAlert = {
    title: window.appName,
    customClass: {
        confirmButton: 'btn btn-primary mx-1',
        cancelButton: 'btn btn-secondary mx-1',
        denyButton: 'btn btn-danger mx-1'
    },
    buttonsStyling: false,
    reverseButtons: true
};

// Confirmar una acción
const swalAlertConfirm = (message, title = window.appName) => {
    return Swal.fire({
        ...optionsAlert,
        title,
        html: message,
        showCancelButton: true,
        confirmButtonText: 'Si Confirmar',
        cancelButtonText: 'Cancelar'
    });
};

// Mostrar información
const swalAlertInfo = (message, title = window.appName) => {
    return Swal.fire({
        ...optionsAlert,
        title,
        text: message,
        icon: 'info',
        confirmButtonText: 'Entendido',
        showCancelButton: false
    });
};

// Mostrar éxito
const swalAlertSuccess = (message, title = window.appName) => {
    return Swal.fire({
        ...optionsAlert,
        title,
        text: message,
        icon: 'success',
        confirmButtonText: 'Aceptar',
        showCancelButton: false
    });
};

// Mostrar advertencia
const swalAlertWarning = (message, title = window.appName) => {
    return Swal.fire({
        ...optionsAlert,
        title,
        text: message,
        icon: 'warning',
        confirmButtonText: 'Entendido',
        showCancelButton: false
    });
};

// Mostrar error
const swalAlertError = (message, title = window.appName) => {
    return Swal.fire({
        ...optionsAlert,
        title,
        text: message,
        icon: 'error',
        confirmButtonText: 'Aceptar',
        showCancelButton: false
    });
};

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
    background: '#FFFFFF',
    color: '#263D35',
    customClass: {
        popup: 'toast-mining',
        title: 'toast-mining-title',
        timerProgressBar: 'toast-mining-progress'
    }
});

const successMessage = (message) => {
    Toast.fire({
        icon: 'success',
        title: message,
        iconColor: '#4B8B62'
    });
};

const errorMessage = (message) => {
    Toast.fire({
        icon: 'error',
        title: message,
        iconColor: '#C04C4C'
    });
};

const warningMessage = (message) => {
    Toast.fire({
        icon: 'warning',
        title: message,
        iconColor: '#D69A35'
    });
};

window.swalAlertConfirm = swalAlertConfirm;
window.swalAlertInfo = swalAlertInfo;
window.swalAlertSuccess = swalAlertSuccess;
window.swalAlertWarning = swalAlertWarning;
window.swalAlertError = swalAlertError;

window.successMessage = successMessage;
window.errorMessage = errorMessage;
window.warningMessage = warningMessage;

// Exportar funciones
export {
    swalAlertConfirm,
    swalAlertInfo,
    swalAlertSuccess,
    swalAlertWarning,
    swalAlertError,
    successMessage,
    errorMessage,
    warningMessage
};

