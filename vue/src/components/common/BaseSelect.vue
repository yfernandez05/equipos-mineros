<template>
    <div @mousedown.capture="handleClearMouseDown" :class="{ 'select-invalid': invalid }">
        <Multiselect v-model="selectedValue" mode="single" :options="formattedOptions" value-prop="value" label="label"
            :searchable="true" :can-clear="clearable" :disabled="disabled" :placeholder="placeholder"
            :no-options-text="'No hay opciones disponibles'" :no-results-text="'No se encontraron resultados'"
            @select="onSelect" @clear="$emit('select', null)" />
    </div>
</template>

<script>
import Multiselect from '@vueform/multiselect'
import '@vueform/multiselect/themes/default.css'

export default {
    name: 'BaseSelect',

    components: {
        Multiselect,
    },

    props: {
        modelValue: {
            type: [String, Number],
            default: '',
        },

        options: {
            type: Array,
            default: () => [],
        },

        keyProperty: {
            type: String,
            default: 'id',
        },

        textProperty: {
            type: String,
            default: 'name',
        },

        placeholder: {
            type: String,
            default: 'Seleccione una opción',
        },

        clearable: {
            type: Boolean,
            default: true,
        },

        disabled: {
            type: Boolean,
            default: false,
        },
        invalid: {
            type: Boolean,
            default: false,
        },
    },

    emits: ['update:modelValue', 'select'],

    computed: {
        formattedOptions() {
            return this.options.map(option => ({
                ...option,
                value: option[this.keyProperty],
                label: option[this.textProperty],
            }))
        },

        selectedValue: {
            get() {
                return this.modelValue || null
            },
            set(value) {
                this.$emit('update:modelValue', value ?? '')
            },
        },
    },

    methods: {
        handleClearMouseDown(event) {
            if (event.target.closest('.multiselect-clear')) {
                event.preventDefault()
                document.activeElement?.blur()
            }
        },

        onSelect(value) {
            const selectedOption = this.formattedOptions.find(option => String(option.value) === String(value),)

            if (selectedOption) {
                this.$emit('select', selectedOption)
            }
        },

        onClear() {
            this.$emit('update:modelValue', '')
            this.$emit('select', null)
        },
    },
}
</script>
