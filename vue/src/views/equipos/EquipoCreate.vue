<template>
    <form-equipo cardTitle="Registrar equipo" :equipo="equipo" @saveData="saveData" />
</template>

<script>
import equipoService from '../../services/equipoService';
import FormEquipo from './Components/_FormEquipo.vue';
import { getApiErrorMessage } from '../../utils/apiError';

export default {
    data() {
        return {
            equipo: {
                codigo: '',
                tipo_equipo_id: '',
                horometro_actual: '0',
            },
        }
    },

    methods: {        
        async saveData(event) {
            showPreloader()
            console.log('save data', event);

            try {
                await equipoService.create(event)

                successMessage('Equipo registrado correctamente.')
                await this.$router.push({ name: 'spa.equipo' })

            } catch (error) {
                errorMessage(getApiErrorMessage(error))
            } finally {
                hidePreloader()
            }
        },
    },

    components: {
        FormEquipo,
    },
}
</script>