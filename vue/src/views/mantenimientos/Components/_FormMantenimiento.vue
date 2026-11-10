<template>
    <div>
        <breadcrumb :breadcrumb="[{ label: 'Mantenimientos', to: { name: 'spa.mantenimiento' } },{ label: cardTitle }]" />

        <main-content columnClass="col-12 col-xl-10">
            <template v-slot:card-header-title>
                <span v-text="cardTitle"></span>
            </template>

            <template v-slot:card-body-main>
                <div class="row">

                    <!-- Equipo -->
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">
                            Equipo <small class="text-danger">(*)</small>
                        </label>

                        <BaseSelect v-model="mantenimiento.equipo_id" :options="equipos" key-property="id"
                            text-property="descripcion" placeholder="Seleccione un equipo" @select="seleccionarEquipo"
                            :invalid="errorExists('equipo_id')" />

                        <small class="text-danger" v-if="errorExists('equipo_id')">
                            {{ showError('equipo_id').errorDetail }}
                        </small>
                    </div>

                    <!-- Horómetro -->
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">
                            Horómetro del mantenimiento
                            <small class="text-danger">(*)</small>
                        </label>

                        <input disabled type="number" class="form-control" v-model.number="mantenimiento.horometro"
                            @keyup.enter="doSaveData" :class="{ 'is-invalid': errorExists('horometro') }" min="0"
                            step="0.01" placeholder="Seleccione un equipo" />

                        <small class="invalid-feedback" v-if="errorExists('horometro')">
                            {{ showError('horometro').errorDetail }}
                        </small>
                        <!-- <small class="text-muted">
                            Ingresa el horómetro registrado al momento del mantenimiento.
                        </small> -->
                    </div>

                    <!-- Fecha y hora -->
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">
                            Fecha y hora del mantenimiento
                            <small class="text-danger">(*)</small>
                        </label>

                        <DatePicker v-model:value="mantenimiento.fecha_mantenimiento" type="datetime"
                            format="DD-MM-YYYY HH:mm" value-type="YYYY-MM-DD HH:mm:ss" lang="es" confirm
                            :editable="false" input-class="form-control" placeholder="Seleccione fecha y hora"
                            class="w-100" />

                        <small class="text-danger" v-if="errorExists('fecha_mantenimiento')">
                            {{ showError('fecha_mantenimiento').errorDetail }}
                        </small>
                    </div>

                    <!-- Descripción -->
                    <div class="col-12 mb-3">
                        <label class="form-label">
                            Descripción
                        </label>

                        <textarea class="form-control" v-model.trim="mantenimiento.descripcion"
                            :class="{ 'is-invalid': errorExists('descripcion') }" rows="3" maxlength="500"
                            placeholder="Describe el mantenimiento realizado..."></textarea>

                        <small class="invalid-feedback" v-if="errorExists('descripcion')">
                            {{ showError('descripcion').errorDetail }}
                        </small>

                        <small class="text-muted">
                            Máximo 500 caracteres.
                        </small>
                    </div>

                </div>

                <hr class="mt-2">
            </template>

            <template v-slot:card-body-actions>
                <router-link :to="{ name: 'spa.mantenimiento' }"
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
import { equipoService } from '../../../services/equipoService';
import MainContent from '../../../utils/MainContent.vue';
import Breadcrumb from '../../../utils/Breadcrumb.vue';
import BaseSelect from '../../../components/common/BaseSelect.vue';
import DatePicker from 'vue-datepicker-next';
import 'vue-datepicker-next/index.css';
import 'vue-datepicker-next/locale/es';
import { getApiErrorMessage } from '../../../utils/apiError';
export default {
    props: {
        cardTitle: {
            type: String,
            default: 'Mantenimiento',
        },

        mantenimiento: {
            type: Object,
            default() {
                return {
                    equipo_id: '',
                    horometro: '',
                    fecha_mantenimiento: '',
                    descripcion: '',
                }
            },
        },
    },

    data() {
        return {
            errors: [],
            equipos: [],
        }
    },

    methods: {

        async listarEquipos() {
            try {
                const response = await equipoService.getAll({ perpage: 100 });

                this.equipos = response.data.data
                    .filter(equipo => equipo.isactive)
                    .map(equipo => ({
                        ...equipo,
                        descripcion: `${equipo.codigo} - ${equipo.tipoequipo?.nombre || equipo.tipo_equipo?.nombre || 'Sin tipo'}`
                    }));

            } catch (error) {
                errorMessage(getApiErrorMessage(error));
            }
        },

        doSaveData() {
            if (this.validateFields().length > 0) {
                return;
            }

            const mantenimientoData = {
                equipo_id: this.mantenimiento.equipo_id,
                horometro: this.mantenimiento.horometro,
                fecha_mantenimiento: this.mantenimiento.fecha_mantenimiento,
                descripcion: this.mantenimiento.descripcion || null,
            };

            this.$emit('saveData', mantenimientoData);
        },

        seleccionarEquipo(equipo) {
            this.mantenimiento.horometro = equipo ? equipo.horometro_actual : '';
        },

        validateFields() {
            this.errors = [];

            if (!this.mantenimiento.equipo_id) {
                this.setError('equipo_id', 'Debes seleccionar un equipo.');
            }

            if (
                this.mantenimiento.horometro === '' ||
                this.mantenimiento.horometro === null ||
                this.mantenimiento.horometro === undefined
            ) {
                this.setError('horometro', 'El horómetro es obligatorio.');

            } else if (
                !Number.isFinite(Number(this.mantenimiento.horometro)) ||
                Number(this.mantenimiento.horometro) < 0
            ) {
                this.setError('horometro', 'El horómetro debe ser un número mayor o igual a cero.');
            }

            if (!this.mantenimiento.fecha_mantenimiento) {
                this.setError('fecha_mantenimiento', 'Debes seleccionar la fecha del mantenimiento.');
            }

            if (
                this.mantenimiento.descripcion &&
                this.mantenimiento.descripcion.length > 500
            ) {
                this.setError('descripcion', 'La descripción no puede superar los 500 caracteres.');
            }

            return this.errors;
        },

        setError(keyModel, errorDetail) {
            this.errors.push({
                keyModel,
                errorDetail,
            });
        },

        errorExists(keyModel) {
            return this.errors.some(err => err.keyModel === keyModel);
        },

        showError(keyModel) {
            return this.errors.find(err => err.keyModel === keyModel);
        },

        resetForm() {
            this.mantenimiento.equipo_id = '';
            this.mantenimiento.horometro = '';
            this.mantenimiento.fecha_mantenimiento = '';
            this.mantenimiento.descripcion = '';

            this.errors = [];
        },
    },

    async created() {
        this.listarEquipos();
    },

    components: {
        MainContent,
        Breadcrumb,
        BaseSelect,
        DatePicker,
    },
}
</script>
