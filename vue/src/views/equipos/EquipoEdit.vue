<template>
    <form-equipo cardTitle="Editar equipo" :equipo="equipo" @saveData="saveData" :isEdit="true" />
</template>

<script>
import equipoService from '../../services/equipoService'
import FormEquipo from './Components/_FormEquipo.vue';
import { getApiErrorMessage } from '../../utils/apiError';

export default {
    data() {
        return {
            equipo: {},
        }
    },

    async created() {
        this.equipo.id = this.$route.params.id

        if (isNaN(this.equipo.id) || !this.equipo.id) {
            this.backToList()
            return
        }

        await this.obtenerEquipo(this.equipo.id)
    },

    methods: {

        async obtenerEquipo(id) {
            showPreloader()

            try {
                const result = await equipoService.find(id)
                const equipo = result.data.data
                console.log(equipo);

                if (!equipo) {
                    errorMessage(`No se encontró ningún equipo con el código ${id}.`)
                    this.backToList()
                    return
                }

                if (!equipo.isactive) {
                    warningMessage(appCannotDeleteMessage)
                    this.backToList()
                    return
                }

                this.equipo = equipo;

            } catch (error) {
                errorMessage(getApiErrorMessage(error))
                this.backToList()
            } finally {
                hidePreloader()
            }
        },

        async saveData(event) {
            showPreloader()

            try {
                await equipoService.update(this.equipo.id, event)

                successMessage('Equipo actualizado correctamente.')
                this.backToList()

            } catch (error) {
                errorMessage(getApiErrorMessage(error))
            } finally {
                hidePreloader()
            }
        },

        backToList() {
            this.$router.push({ name: 'spa.equipo' })
        },
    },

    components: {
        FormEquipo,
    },
}
</script>