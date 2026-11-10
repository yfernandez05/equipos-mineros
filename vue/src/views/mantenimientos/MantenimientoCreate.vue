
<template>
    <form-mantenimiento
        cardTitle="Registrar mantenimiento"
        :mantenimiento="mantenimiento"
        @saveData="saveData"
    />
</template>

<script>
import mantenimientoService from '../../services/mantenimientoService';
import FormMantenimiento from './Components/_FormMantenimiento.vue';
import { getApiErrorMessage } from '../../utils/apiError';
import dayjs from 'dayjs';

export default {
    data() {
        return {
            mantenimiento: {
                equipo_id: '',
                horometro: '',
                fecha_mantenimiento: dayjs().format('YYYY-MM-DD HH:mm:ss'),
                descripcion: '',
            },
        }
    },

    methods: {

        async saveData(event) {
            showPreloader();

            console.log('save data', event);

            try {
                await mantenimientoService.create(event);

                successMessage('Mantenimiento registrado correctamente.');

                await this.$router.push({ name: 'spa.mantenimiento' });

            } catch (error) {
                errorMessage(getApiErrorMessage(error));

            } finally {
                hidePreloader();
            }
        },
    },

    components: {
        FormMantenimiento,
    },
}
</script>
