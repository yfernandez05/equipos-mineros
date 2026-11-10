<template>
    <div>
        <breadcrumb :breadcrumb="[{ label: 'Tipos de equipos', to: { name: 'spa.tiposEquipo' } },{ label: cardTitle }]" />

        <main-content columnClass="col-12 col-xl-10">

            <template v-slot:card-header-title>
                <span v-text="cardTitle"></span>
            </template>

            <template v-slot:card-body-main>
                <div class="row">

                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">
                            Nombre <small class="text-danger">(*)</small>
                        </label>

                        <input type="text" class="form-control" v-model.trim="tipoEquipo.nombre"
                            @keyup.enter="doSaveData" :class="{ 'is-invalid': errorExists('nombre') }"
                            placeholder="Ej. Camión" />

                        <small class="invalid-feedback" v-if="errorExists('nombre')">
                            {{ showError('nombre').errorDetail }}
                        </small>
                    </div>

                    <!-- Intervalo de mantenimiento -->
                    <div class="col-12 col-md-6 mb-3">
                        <label class="form-label">
                            Intervalo de mantenimiento (horas)
                            <small class="text-danger">(*)</small>
                        </label>

                        <input type="number" class="form-control" v-model.number="tipoEquipo.intervalo_mantenimiento"
                            @keyup.enter="doSaveData" :class="{
                                'is-invalid': errorExists('intervalo_mantenimiento')
                            }" min="1" step="1" placeholder="Ej. 250" />

                        <small class="invalid-feedback" v-if="errorExists('intervalo_mantenimiento')">
                            {{ showError('intervalo_mantenimiento').errorDetail }}
                        </small>

                        <small class="text-muted">
                            Horas de funcionamiento entre mantenimientos.
                        </small>
                    </div>

                </div>

                <hr class="mt-2">
            </template>

            <template v-slot:card-body-actions>

                <router-link :to="{ name: 'spa.tiposEquipo' }"
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
import MainContent from '../../../utils/MainContent.vue';
import Breadcrumb from '../../../utils/Breadcrumb.vue';

export default {
    props: {
        cardTitle: {
            type: String,
            default: 'Tipo de equipo'
        },

        tipoEquipo: {
            type: Object,
            default() {
                return {
                    nombre: '',
                    intervalo_mantenimiento: ''
                };
            }
        }
    },

    data() {
        return {
            errors: []
        };
    },

    methods: {

        doSaveData() {

            if (this.validateFields().length > 0) {
                return;
            }

            const tipoEquipoData = {
                nombre: this.tipoEquipo.nombre,
                intervalo_mantenimiento: this.tipoEquipo.intervalo_mantenimiento
            };

            this.$emit('saveData', tipoEquipoData);
        },

        validateFields() {

            this.errors = [];

            if (!this.tipoEquipo.nombre) {
                this.setError('nombre','El nombre del tipo de equipo es obligatorio.');
            }

            if (this.tipoEquipo.intervalo_mantenimiento === '' || this.tipoEquipo.intervalo_mantenimiento === null || this.tipoEquipo.intervalo_mantenimiento === undefined) {
                this.setError('intervalo_mantenimiento', 'El intervalo de mantenimiento es obligatorio.');
            } else if (!Number.isInteger(Number(this.tipoEquipo.intervalo_mantenimiento)) || Number(this.tipoEquipo.intervalo_mantenimiento) <= 0 ) {
                this.setError('intervalo_mantenimiento','El intervalo debe ser un número entero mayor que cero.');
            }

            return this.errors;
        },

        setError(keyModel, errorDetail) {
            this.errors.push({
                keyModel: keyModel,
                errorDetail: errorDetail
            });
        },

        errorExists(keyModel) {
            return this.errors.some(err => err.keyModel === keyModel);
        },

        showError(keyModel) {
            return this.errors.find(err => err.keyModel === keyModel);
        },

        resetForm() {
            this.tipoEquipo.nombre = '';
            this.tipoEquipo.intervalo_mantenimiento = '';
            this.errors = [];
        }
    },

    components: {
        MainContent,
        Breadcrumb
    }
};
</script>
