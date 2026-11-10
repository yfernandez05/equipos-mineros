
<template>
    <form-tipo-equipo cardTitle="Registrar tipo de equipo" @saveData="saveData" :tipoEquipo="tipoEquipo"/>
</template>

<script>
import tipoEquipoService from '../../services/tipoEquipoService';
import FormTipoEquipo from './Components/_FormTipoEquipo.vue';
import { getApiErrorMessage } from '../../utils/apiError';

export default {
    data() {
        return {
            tipoEquipo: {
                nombre: '',
                intervalo_mantenimiento: ''
            }
        };
    },

    methods: {

        async saveData(event) {
            showPreloader();
            try {
                await tipoEquipoService.create(event);
                window.successMessage('Tipo de equipo registrado correctamente.');
                await this.$router.push({ name: 'spa.tiposEquipo' });

            } catch (error) {
                window.errorMessage(getApiErrorMessage(error));
            } finally {
                hidePreloader();
            }
        }
    },

    components: {
        FormTipoEquipo
    }
};
</script>
