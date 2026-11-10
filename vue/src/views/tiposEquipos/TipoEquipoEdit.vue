<template>
    <form-tipo-equipo cardTitle="Editar tipo de equipo" :tipoEquipo="tipoEquipo" @saveData="saveData" />
</template>

<script>
import tipoEquipoService from '../../services/tipoEquipoService';
import FormTipoEquipo from './Components/_FormTipoEquipo.vue';
import { getApiErrorMessage } from '../../utils/apiError';

export default {
    data() {
        return {
            tipoEquipo: {}
        };
    },

    created() {
        this.tipoEquipo.id = this.$route.params.id;

        if (isNaN(this.tipoEquipo.id) || !this.tipoEquipo.id) {
            this.backToList();
            return;
        }

        this.obtenerTipoEquipo(this.tipoEquipo.id);
    },

    methods: {
        async obtenerTipoEquipo(id) {
            try {
                showPreloader();

                const result = await tipoEquipoService.find(id);
                const tipoEquipo = result.data.data;

                if (!tipoEquipo) {
                    errorMessage(`No se encontró ningún tipo de equipo con el código ${id}.`);
                    this.backToList();
                    return;
                }

                if (!tipoEquipo.isactive) {
                    warningMessage(window.appCannotDeleteMessage);
                    this.backToList();
                    return;
                }

                this.tipoEquipo = tipoEquipo;

            } catch (error) {
                errorMessage(getApiErrorMessage(error));
                this.backToList();
            } finally {
                hidePreloader();
            }
        },

        async saveData(event) {
            try {

                showPreloader();
                await tipoEquipoService.update(this.tipoEquipo.id, event);
                successMessage('Tipo de equipo actualizado correctamente.');
                this.backToList();

            } catch (error) {
                errorMessage(getApiErrorMessage(error));
            } finally {
                hidePreloader();
            }
        },

        backToList() {
            this.$router.push({name: 'spa.tiposEquipo'});
        }
    },

    components: {
        FormTipoEquipo
    }
};
</script>
