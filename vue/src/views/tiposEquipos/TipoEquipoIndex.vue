<template>
    <div>
        <main-content columnClass="col-12">
            <template v-slot:card-header-title>
                Tipos de equipos
            </template>

            <template v-slot:card-header-actions>
                <button class="btn btn-sm btn-info fw-bold" @click="buscarTipoEquipo()">
                    <i class="fas fa-search"></i>
                    Buscar
                </button>

                <button class="btn btn-sm btn-danger fw-bold" @click="limpiarFiltros()">
                    <i class="fas fa-times"></i>
                    Limpiar
                </button>

                <router-link :to="{ name: 'spa.tipoEquipo.register' }" class="btn btn-sm btn-success fw-bold">
                    <i class="fas fa-plus"></i>
                    Nuevo
                </router-link>
            </template>

            <template v-slot:card-body-main>
                <div class="row">
                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Nombre</label>
                        <input type="text" class="form-control" v-model="tipoEquipo.nombre"
                            @keyup.enter="buscarTipoEquipo()">
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">
                            Intervalo de mantenimiento
                        </label>
                        <input type="number" class="form-control" v-model="tipoEquipo.intervalo_mantenimiento"
                            @keyup.enter="buscarTipoEquipo()">
                    </div>

                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" id="estado" class="form-check-input" v-model="tipoEquipo.estado"
                                @change="buscarTipoEquipo()">

                            <label class="form-check-label" for="estado">
                                Incluir eliminados
                            </label>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-striped
                        table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Acciones</th>
                                <th>Código</th>
                                <th>Nombre</th>
                                <th>Intervalo de mantenimiento</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="tipo in tiposEquipos" :key="tipo.id">
                                <td>
                                    <row-actions :rowData="tipo" @rowItemActions="rowItemActions" :activeDelete="false">
                                        <button type="button" :title="tipo.isactive ? 'Desactivar' : 'Activar'"
                                            @click="eliminarTipoEquipo(tipo)" class="btn btn-sm me-1"
                                            :class="tipo.isactive ? 'bg-danger-subtle' : 'bg-primary-subtle'">
                                            <i class="fa-solid fa-trash-can"
                                                :class="tipo.isactive ? 'mdi-delete text-danger' : 'mdi-check-circle text-primary'"></i>
                                        </button>
                                    </row-actions>
                                </td>
                                <td>{{ tipo.id }}</td>
                                <td>{{ tipo.nombre }}</td>
                                <td>
                                    {{ tipo.intervalo_mantenimiento }} horas
                                </td>
                                <td>
                                    <span class="badge rounded-pill w-75 py-2"
                                        :class="tipo.isactive ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'">
                                        {{ tipo.statename }}
                                    </span>
                                </td>
                            </tr>

                            <tr v-if="tiposEquipos.length === 0">
                                <td colspan="5" class="text-center">
                                    No hay registros disponibles.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <pagination-links :pagination="pagination" @changePerPage="changePerPage">
                </pagination-links>
            </template>
        </main-content>
    </div>
</template>

<script>
import { tipoEquipoService } from '../../services/tipoEquipoService'
import MainContent from '../../utils/MainContent.vue'
import PaginationLinks from '../../utils/PaginationLinks.vue'
import RowActions from '../../utils/RowActions.vue'

export default {
    data() {
        return {
            tiposEquipos: [],
            tipoEquipo: {
                nombre: '',
                intervalo_mantenimiento: '',
                estado: false,
            },
            pagination: {},
            filters: {},
        }
    },

    methods: {
        async listarTiposEquipos() {
            showPreloader();
            let vm = this;
            try {
                const response = await tipoEquipoService.getAll(this.filters);

                this.tiposEquipos = response.data.data;
                this.pagination = response.data;
                delete vm.pagination.data;
                console.log(this.tiposEquipos);

            } catch (error) {
                console.log(error);
            } finally {
                hidePreloader()
            }
        },

        buscarTipoEquipo() {
            this.filters = {};
            this.filters.page = 1;

            if (this.tipoEquipo.nombre.length)
                this.filters.nombre = this.tipoEquipo.nombre;

            if (this.tipoEquipo.intervalo_mantenimiento?.length)
                this.filters.sku = this.tipoEquipo.intervalo_mantenimiento;

            if (this.tipoEquipo.estado)
                this.filters.estado = this.tipoEquipo.estado;

            this.listarTiposEquipos();
        },

        limpiarFiltros() {
            this.tipoEquipo.nombre = '',
                this.tipoEquipo.intervalo_mantenimiento = '',
                this.tipoEquipo.estado = false,
                this.buscarTipoEquipo();
        },

        changePerPage(event) {
            this.filters.page = event.page;
            this.filters.perpage = event.perpage;
            this.listarTiposEquipos();
        },

        rowItemActions(event) {
            switch (event.action) {
                case 'edit':
                    /* if (!event.data.isactive) {
                        warningMessage(appCannotDeleteMessage, appName);
                        break;
                    } */

                 this.$router.push({ name: 'spa.tipoEquipo.edit', params: { id: event.data.id } })
                break;
                case 'delete':
                    this.eliminarproducto(event.data);
                    break;
            }
        },

        async eliminarTipoEquipo(param) {

            if (!param.isactive) {
                warningMessage(window.appRecordIsDeletedMessage);
                return;
            }

            const result = await swalAlertConfirm(
                `¿Estás seguro de eliminar el tipo de equipo <strong>${param.nombre}</strong>?`,'Eliminar tipo de equipo');

            if (!result.isConfirmed) return;

            try {

                await tipoEquipoService.remove(param.id);

                successMessage('Tipo de equipo eliminado correctamente.');

                this.listarTiposEquipos();

            } catch (error) {

                errorMessage(
                    error.response?.data?.message || window.appErrorMessage
                );

            }
        },
    },

    created() {
        this.listarTiposEquipos()
    },

    components: {
        MainContent,
        PaginationLinks,
        RowActions,
    },
}
</script>
