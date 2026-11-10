<template>
    <div>
        <main-content columnClass="col-12">
            <template v-slot:card-header-title>
                Historial de mantenimientos
            </template>

            <template v-slot:card-header-actions>
                <button class="btn btn-sm btn-info fw-bold" @click="buscarMantenimiento()">
                    <i class="fas fa-search"></i> Buscar
                </button>

                <button class="btn btn-sm btn-danger fw-bold" @click="limpiarFiltros()">
                    <i class="fas fa-times"></i> Limpiar
                </button>

                <router-link
                    :to="{ name: 'spa.mantenimiento.register' }"
                    class="btn btn-sm btn-success fw-bold"
                >
                    <i class="fas fa-plus"></i> Registrar mantenimiento
                </router-link>
            </template>

            <template v-slot:card-body-main>
                <div class="row">
                    <!-- Filtro por código -->
                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Código del equipo</label>

                        <input
                            type="text"
                            class="form-control"
                            v-model="filtro.codigo"
                            @keyup.enter="buscarMantenimiento()"
                            placeholder="Ej. EQ-001"
                        >
                    </div>
                </div>

                <!-- Tabla -->
                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th class="align-middle text-nowrap">ID</th>
                                <th class="align-middle text-nowrap">Código del equipo</th>
                                <th class="align-middle text-nowrap">Tipo de equipo</th>
                                <th class="align-middle text-nowrap">Horómetro</th>
                                <th class="align-middle text-nowrap">Descripción</th>
                                <th class="align-middle text-nowrap">Fecha de registro</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="item in mantenimientos" :key="item.id">
                                <td v-text="item.id"></td>

                                <td class="align-middle text-nowrap">
                                    {{ item.equipo?.codigo || '-' }}
                                </td>

                                <td class="align-middle text-nowrap">
                                    {{ item.equipo?.tipo_equipo?.nombre || '-' }}
                                </td>

                                <td class="align-middle text-nowrap">
                                    {{ item.horometro }} horas
                                </td>

                                <td class="align-middle text-nowrap" v-text="item.descripcion">
                                </td>

                                <td class="align-middle text-nowrap" v-text="item.fecha_mantenimiento_formateada">
                                </td>
                            </tr>

                            <tr v-if="mantenimientos.length === 0">
                                <td colspan="6" class="text-center">
                                    No hay mantenimientos registrados.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <pagination-links
                    :pagination="pagination"
                    @changePerPage="changePerPage"
                />
            </template>
        </main-content>
    </div>
</template>

<script>
import mantenimientoService from '../../services/mantenimientoService'

import MainContent from '../../utils/MainContent.vue'
import PaginationLinks from '../../utils/PaginationLinks.vue'

import { getApiErrorMessage } from '../../utils/apiError'

export default {
    data() {
        return {
            mantenimientos: [],

            filtro: {
                codigo: '',
            },

            pagination: {},
            filters: {},
        }
    },

    methods: {
        async listarMantenimientos() {
            showPreloader()

            try {
                const response = await mantenimientoService.getAll(this.filters)

                this.mantenimientos = response.data.data

                this.pagination = response.data
                delete this.pagination.data

            } catch (error) {
                errorMessage(getApiErrorMessage(error))
            } finally {
                hidePreloader()
            }
        },

        buscarMantenimiento() {
            this.filters = {
                page: 1,
            }

            if (this.filtro.codigo?.length) {
                this.filters.codigo = this.filtro.codigo
            }

            this.listarMantenimientos()
        },

        limpiarFiltros() {
            this.filtro.codigo = ''

            this.buscarMantenimiento()
        },

        changePerPage(event) {
            this.filters.page = event.page
            this.filters.perpage = event.perpage

            this.listarMantenimientos()
        },
    },

    created() {
        this.listarMantenimientos()
    },

    components: {
        MainContent,
        PaginationLinks,
    },
}
</script>