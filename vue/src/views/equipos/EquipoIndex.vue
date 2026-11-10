<template>
    <div>
        <main-content columnClass="col-12">
            <template v-slot:card-header-title>
                Equipos mineros
            </template>

            <template v-slot:card-header-actions>
                <button class="btn btn-sm btn-info fw-bold" @click="buscarEquipo()">
                    <i class="fas fa-search"></i> Buscar
                </button>

                <button class="btn btn-sm btn-danger fw-bold" @click="limpiarFiltros()">
                    <i class="fas fa-times"></i> Limpiar
                </button>

                <router-link :to="{ name: 'spa.equipo.register' }" class="btn btn-sm btn-success fw-bold">
                    <i class="fas fa-plus"></i> Nuevo
                </router-link>
            </template>

            <template v-slot:card-body-main>
                <div class="row">
                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Código</label>
                        <input type="text" class="form-control" v-model="equipo.codigo" @keyup.enter="buscarEquipo()">
                    </div>

                    <div class="col-12 col-md-4 mb-3">
                        <label class="form-label">Tipo de equipo</label>
                        <BaseSelect v-model="equipo.tipo_equipo_id" :options="tiposEquipos" key-property="id"
                            text-property="nombre" placeholder="Todos los tipos" />
                    </div>

                    <div class="col-12 mb-3">
                        <div class="form-check">
                            <input type="checkbox" id="estado" class="form-check-input" v-model="equipo.estado"
                                @change="buscarEquipo()">
                            <label class="form-check-label" for="estado">
                                Incluir equipos inactivos
                            </label>
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-sm table-striped table-hover table-bordered">
                        <thead>
                            <tr>
                                <th>Acciones</th>
                                <th>id</th>
                                <th>Código</th>
                                <th>Tipo de equipo</th>
                                <th>Horómetro total</th>
                                <th>Último mantenimiento</th>
                                <th>Horas trabajadas</th>
                                <th>Estado de mantenimiento</th>
                                <th>Estado</th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr v-for="item in equipos" :key="item.id">
                                <td class="align-middle text-nowrap">
                                    <row-actions :rowData="item" @rowItemActions="rowItemActions" :activeDelete="false">
                                        <button type="button" :title="item.isactive ? 'Desactivar' : 'Activar'"
                                            @click="eliminarEquipo(item)" class="btn btn-sm me-1"
                                            :class="item.isactive ? 'bg-danger-subtle' : 'bg-primary-subtle'">
                                            <i class="fa-solid fa-trash-can"
                                                :class="item.isactive ? 'mdi-delete text-danger' : 'mdi-check-circle text-primary'"></i>
                                        </button>
                                    </row-actions>
                                </td>

                                <td class="align-middle text-nowrap" v-text="item.id"></td>

                                <td class="align-middle text-nowrap" v-text="item.codigo"></td>

                                <td class="align-middle text-nowrap">{{ item.tipoequipo?.nombre || '-' }}</td>

                                <td class="align-middle text-nowrap">{{ item.horometro_actual }} horas</td>

                                <td class="align-middle text-nowrap">{{ item.horometro_ultimo_mantenimiento }} horas</td>

                                <td class="align-middle text-nowrap">
                                    {{ (Number(item.horometro_actual) -
                                        Number(item.horometro_ultimo_mantenimiento)).toFixed(2) }} horas
                                </td>

                                <td class="align-middle text-nowrap">
                                    <span class="badge rounded-pill py-2 px-4 text-uppercase" :class="item.estado_mantenimiento === 'Requiere mantenimiento'
                                        ? 'bg-danger-subtle text-danger' : 'bg-success-subtle text-success'">
                                        {{ item.estado_mantenimiento }}
                                    </span>
                                </td>

                                <td class="align-middle text-nowrap">
                                    <span class="badge rounded-pill py-2 px-4" :class="item.isactive
                                        ? 'bg-success-subtle text-success'
                                        : 'bg-danger-subtle text-danger'">
                                        {{ item.statename }}
                                    </span>
                                </td>
                            </tr>

                            <tr v-if="equipos.length === 0">
                                <td colspan="6" class="text-center">
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
import { equipoService } from '../../services/equipoService'
import { tipoEquipoService } from '../../services/tipoEquipoService'
import MainContent from '../../utils/MainContent.vue'
import PaginationLinks from '../../utils/PaginationLinks.vue'
import RowActions from '../../utils/RowActions.vue'
import BaseSelect from '../../components/common/BaseSelect.vue'

export default {
    data() {
        return {
            equipos: [],
            tiposEquipos: [],
            equipo: {
                codigo: '',
                tipo_equipo_id: '',
                estado: false,
            },
            pagination: {},
            filters: {},
        }
    },

    methods: {
        async listarEquipos() {
            showPreloader()

            try {
                const response = await equipoService.getAll(this.filters)

                this.equipos = response.data.data
                this.pagination = response.data
                delete this.pagination.data

            } catch (error) {
                console.log(error)
            } finally {
                hidePreloader()
            }
        },

        async listarTiposEquipos() {
            try {
                this.tiposEquipos = await tipoEquipoService.getSelect()
            } catch (error) {
                console.log(error)
            }
        },

        buscarEquipo() {
            this.filters = { page: 1 }

            if (this.equipo.codigo?.length)
                this.filters.codigo = this.equipo.codigo

            if (this.equipo.tipo_equipo_id)
                this.filters.tipo_equipo_id = this.equipo.tipo_equipo_id

            if (this.equipo.estado)
                this.filters.estado = this.equipo.estado

            this.listarEquipos()
        },

        limpiarFiltros() {
            this.equipo.codigo = ''
            this.equipo.tipo_equipo_id = ''
            this.equipo.estado = false

            this.buscarEquipo()
        },

        changePerPage(event) {
            this.filters.page = event.page
            this.filters.perpage = event.perpage
            this.listarEquipos()
        },

        rowItemActions(event) {
            switch (event.action) {
                case 'edit':
                    this.$router.push({
                        name: 'spa.equipo.edit',
                        params: { id: event.data.id }
                    })
                    break

                case 'delete':
                    this.eliminarEquipo(event.data)
                    break
            }
        },

        async eliminarEquipo(param) {
            if (!param.isactive) {
                warningMessage(window.appRecordIsDeletedMessage)
                return
            }

            const result = await swalAlertConfirm(
                `¿Estás seguro de eliminar el equipo <strong>${param.codigo}</strong>?`,
                'Eliminar equipo'
            )

            if (!result.isConfirmed) return

            try {
                await equipoService.remove(param.id)

                successMessage('Equipo eliminado correctamente.')
                this.listarEquipos()

            } catch (error) {
                errorMessage(
                    error.response?.data?.message || window.appErrorMessage
                )
            }
        },
    },

    created() {
        this.listarEquipos()
        this.listarTiposEquipos()
    },

    components: {
        MainContent,
        PaginationLinks,
        RowActions,
        BaseSelect,
    },
}
</script>