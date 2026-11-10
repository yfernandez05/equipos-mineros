<template>
    <div class="d-flex align-items-center">
        <button type="button" title="Ver Detalle" @click="onRowItemActions('show',rowData)" v-if="activeShow"
            class="btn btn-sm bg-info-subtle me-1 waves-light" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ver Detalle">
            <i class="fa-regular fa-eye fa-md text-info"></i>
        </button>

        <button type="button" title="Editar" @click="onRowItemActions('edit',rowData)" v-if="activeEdit"
            class="btn btn-sm bg-primary-subtle me-1 waves-light" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Editar">
            <i class="fa-solid fa-pencil fa-md text-primary"></i>
        </button>

        <button type="button" title="Eliminar" @click="onRowItemActions('delete',rowData)" v-if="activeDelete"
            class="btn btn-sm bg-danger-subtle waves-light" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Eliminar">
            <i class="fa-solid fa-trash-can fa-md text-danger"></i>
        </button>
        <slot></slot>
    </div>
</template>
<script>
    export default {

        props:{
            rowData: {
                type: Object,
                required: true
            },
            activeShow: {
                type: Boolean,
                default: false
            },
            activeDelete: {
                type: Boolean,
                default: true
            },
            activeEdit: {
                type: Boolean,
                default: true
            }
        },
        methods:{
            onRowItemActions(action, data){
                this.$emit('rowItemActions', {action:action, data:data})
            }
        }
    }

</script>
