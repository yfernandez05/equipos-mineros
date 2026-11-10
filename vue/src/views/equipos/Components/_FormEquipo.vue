<template>
    <div>
        <breadcrumb :breadcrumb="[
            { label: 'Equipos mineros', to: { name: 'spa.equipo' } },
            { label: cardTitle }
        ]" />

        <main-content columnClass="col-12 col-xl-10">
            <template v-slot:card-header-title>
                <span v-text="cardTitle"></span>
            </template>

            <template v-slot:card-body-main>
                <div class="row">

                    <!-- Código -->
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">
                            Código <small class="text-danger">(*)</small>
                        </label>

                        <input type="text" class="form-control" v-model.trim="equipo.codigo" @keyup.enter="doSaveData"
                            :class="{ 'is-invalid': errorExists('codigo') }" placeholder="Ej. EQ-001" />

                        <small class="invalid-feedback" v-if="errorExists('codigo')">
                            {{ showError('codigo').errorDetail }}
                        </small>
                    </div>

                    <!-- Tipo de equipo -->
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">
                            Tipo de equipo <small class="text-danger">(*)</small>
                        </label>

                        <BaseSelect v-model="equipo.tipo_equipo_id" :options="tiposEquipos" key-property="id"
                            text-property="nombre" placeholder="Seleccione un tipo de equipo"
                            :invalid="errorExists('tipo_equipo_id')" />

                        <small class="text-danger" v-if="errorExists('tipo_equipo_id')">
                            {{ showError('tipo_equipo_id').errorDetail }}
                        </small>
                    </div>

                    <!-- Horómetro -->
                    <div v-if="!isEdit" class="col-12 col-md-6 mb-3">
                        <label class="form-label">
                            Horómetro (horas) <small class="text-danger">(*)</small>
                        </label>

                        <input type="number" class="form-control" v-model.number="equipo.horometro_actual"
                            @keyup.enter="doSaveData" :class="{ 'is-invalid': errorExists('horometro_actual') }" min="0"
                            step="0.01" placeholder="Ej. 1250" />

                        <small class="invalid-feedback" v-if="errorExists('horometro_actual')">
                            {{ showError('horometro_actual').errorDetail }}
                        </small>

                        <small class="text-muted">
                            Ingresa las horas actuales de funcionamiento del equipo.
                        </small>
                    </div>

                    <!-- Control de horas: solamente al editar -->

                    <!-- Control de horas: solamente al editar -->
                    <div v-else class="col-12 mt-3">
                        <hr>

                        <h6 class="fw-bold mb-3">
                            <i class="bi bi-speedometer2 me-2"></i>
                            Control de horas trabajadas
                        </h6>

                        <!-- Información actual del equipo -->
                        <div class="row g-2 mb-3">

                            <div class="col-12 col-md-4">
                                <div class="bg-light border rounded p-3">
                                    <small class="text-muted">Horómetro total</small>
                                    <h5 class="mb-0">
                                        {{ Number(equipo.horometro_actual || 0).toFixed(2) }} hrs
                                    </h5>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="bg-light border rounded p-3">
                                    <small class="text-muted">Uso desde mantenimiento</small>
                                    <h5 class="mb-0">
                                        {{ equipo.horas_desde_mantenimiento }} /
                                        {{ equipo.tipoequipo?.intervalo_mantenimiento }} hrs
                                    </h5>
                                </div>
                            </div>

                            <div class="col-12 col-md-4">
                                <div class="bg-light border rounded p-3">
                                    <small class="text-muted">Horas restantes</small>
                                    <h5 class="mb-0 text-info">
                                        {{ equipo.horas_restantes }} hrs
                                    </h5>
                                </div>
                            </div>

                        </div>

                        <!-- Registrar nuevas horas -->
                        <div class="row g-3 align-items-end bg-light border rounded p-3">

                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">
                                    Nuevas horas trabajadas  <small class="text-danger">(*)</small>
                                </label>

                                <div class="input-group">
                                    <input type="number" class="form-control" v-model.number="horasNuevas" min="0"
                                        step="0.01" placeholder="Ingrese horas">
                                    <span class="input-group-text">hrs</span>
                                </div>
                            </div>

                            <!-- Resultado proyectado -->
                            <div class="col-12 col-md-6">
                                <label class="form-label fw-semibold">
                                    Nuevo horómetro total
                                </label>

                                <h4 class="mb-2">
                                    {{ (
                                        Number(equipo.horometro_actual || 0) +
                                        Number(horasNuevas || 0)
                                    ).toFixed(2) }} hrs
                                </h4>

                                <span class="badge" :class="(Number(equipo.horas_restantes) - Number(horasNuevas || 0)) <= 0 ? 'bg-danger' : 'bg-success'">
                                    {{(Number(equipo.horas_restantes) - Number(horasNuevas || 0)) <= 0 ? 'Requiere mantenimiento' : 'Sigue operativo' }} </span>
                            </div>
                        </div>
                    </div>

                </div>

                <hr class="mt-2">
            </template>

            <template v-slot:card-body-actions>
                <router-link :to="{ name: 'spa.equipo' }"
                    class="btn btn-info me-2 d-flex flex-wrap align-items-center align-content-center">
                    <i class="fas fa-reply me-1"></i>
                    <span class="button-text">Atrás</span>
                </router-link>

                <div class="d-flex">
                    <button type="button"
                        class="btn btn-success me-2 d-flex flex-wrap align-items-center align-content-center"
                        @click="doSaveData">
                        <i class="fa fa-save me-1"></i>
                        Guardar
                    </button>

                    <button type="reset"
                        class="btn btn-outline-secondary d-flex flex-wrap align-items-center align-content-center"
                        @click="resetForm">
                        <i class="fa fa-window-close me-1"></i>
                        <span class="button-text">Cancelar</span>
                    </button>
                </div>
            </template>
        </main-content>
    </div>
</template>

<script>
import { tipoEquipoService } from '../../../services/tipoEquipoService';
import MainContent from '../../../utils/MainContent.vue';
import Breadcrumb from '../../../utils/Breadcrumb.vue';
import BaseSelect from '../../../components/common/BaseSelect.vue';
import { getApiErrorMessage } from '../../../utils/apiError';

export default {
    props: {
        cardTitle: {
            type: String,
            default: 'Equipo',
        },

        equipo: {
            type: Object,
            default() {
                return {
                    codigo: '',
                    tipo_equipo_id: '',
                    horometro_actual: '',
                }
            },
        },

        isEdit: {
            type: Boolean,
            default: false,
        },

    },

    data() {
        return {
            errors: [],
            tiposEquipos: [],
            horasNuevas: '',
        }
    },

    methods: {

        async listarTiposEquipos() {
            try {
                this.tiposEquipos = await tipoEquipoService.getSelect()
            } catch (error) {
                errorMessage(getApiErrorMessage(error))
            }
        },
        doSaveData() {
            if (this.validateFields().length > 0) {
                return
            }

            const equipoData = {
                codigo: this.equipo.codigo,
                tipo_equipo_id: this.equipo.tipo_equipo_id,
                horometro_actual: this.isEdit ? Number(this.equipo.horometro_actual) + Number(this.horasNuevas || 0) : this.equipo.horometro_actual,
            }

            this.$emit('saveData', equipoData)
        },

        validateFields() {
            this.errors = []

            if (!this.equipo.codigo) {
                this.setError('codigo', 'El código del equipo es obligatorio.')
            }

            if (!this.equipo.tipo_equipo_id) {
                this.setError('tipo_equipo_id', 'Debes seleccionar un tipo de equipo.')
            }

            if (this.equipo.horometro_actual === '' || this.equipo.horometro_actual === null || this.equipo.horometro_actual === undefined) {
                this.setError('horometro_actual', 'El horómetro es obligatorio.')
            } else if (!Number.isFinite(Number(this.equipo.horometro_actual)) || Number(this.equipo.horometro_actual) < 0) {
                this.setError('horometro_actual', 'El horómetro debe ser un número mayor o igual a cero.')
            }

            return this.errors
        },

        setError(keyModel, errorDetail) {
            this.errors.push({
                keyModel,
                errorDetail,
            })
        },

        errorExists(keyModel) {
            return this.errors.some(err => err.keyModel === keyModel)
        },

        showError(keyModel) {
            return this.errors.find(err => err.keyModel === keyModel)
        },

        resetForm() {
            this.equipo.codigo = ''
            this.equipo.tipo_equipo_id = ''
            this.equipo.horometro_actual = ''
            this.errors = []
        },
    },

    async created() {
        this.listarTiposEquipos();
    },

    computed: {
        nuevoHorometro() {
            const actual = Number(this.equipo.horometro_actual || 0)
            const nuevas = Number(this.horasNuevas || 0)

            return actual + nuevas
        },
    },

    components: {
        MainContent,
        Breadcrumb,
        BaseSelect,
    },
}
</script>