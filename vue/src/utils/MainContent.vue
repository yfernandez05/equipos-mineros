
<template>
    <div class="row">
        <div :class="columnClass">
            <div class="card main-card">

                <!-- Header -->
                <div
                    class="card-header main-card-header d-flex justify-content-between align-items-center"
                    :class="headerBgClass"
                >
                    <h5 class="mb-0 card-header-title">
                        <slot name="card-header-title"></slot>
                    </h5>

                    <div class="d-flex flex-wrap align-items-center gap-2">
                        <slot name="card-header-actions"></slot>
                    </div>
                </div>

                <!-- Body -->
                <div class="card-body main-card-body">

                    <div class="card-content">
                        <slot name="card-body-main"></slot>
                    </div>

                    <div class="card-body-actions d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <slot name="card-body-actions"></slot>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        columnClass: {
            type: String,
            default: 'col-12'
        },
        headerBgClass: {
            type: String,
            default: 'bg-dark'
        }
    }
}
</script>

<style scoped>
.main-card {
    border: 1px solid #E4E9E6;
    border-radius: 9px;
    background-color: #FFFFFF;
    box-shadow: 0 2px 7px rgba(16, 45, 42, 0.04);
    overflow: hidden;
}

/* Encabezado */
.main-card-header {
    min-height: 50px;
    padding: 11px 18px;
    gap: 14px;
    border: none;
    border-radius: 0 !important;
}

.card-header-title {
    color: #FFFFFF;
    font-weight: 600;
    letter-spacing: .15px;
    line-height: 1.4;
}


.main-card-body {
    padding: 20px;
    background-color: #FFFFFF;
}

.card-content {
    width: 100%;
    min-width: 0;
}

.card-body-actions {
    padding-top: 15px;
}

@media (max-width: 767.98px) {
    .main-card-header {
        padding: 12px 14px;
    }

    .main-card-body {
        padding: 15px;
    }

    .card-body-actions {
        margin-top: 16px;
        padding-top: 13px;
    }
}

@media (max-width: 575.98px) {
    .main-card-body {
        padding: 13px;
    }

    .main-card-header {
        gap: 10px;
    }
}
</style>
