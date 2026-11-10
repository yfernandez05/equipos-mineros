<template>
    <div class="pb-3 d-flex align-items-center flex-wrap">
        <!-- <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">{{ title }}</h4>
        </div> -->
        <nav aria-label="breadcrumb" style="--bs-breadcrumb-divider: '›';">
            <ol class="breadcrumb mb-0 align-items-center">
                <li v-for="(item, index) in breadcrumb" :key="index" class="breadcrumb-item"
                    :class="{ active: index === breadcrumb.length - 1 }" :aria-current="index === breadcrumb.length - 1 ? 'page' : undefined">
                    <span v-if="index === breadcrumb.length - 1" class="text-dark fw-semibold">
                        {{ item.label }}
                    </span>

                    <router-link v-else :to="item.to" class="text-decoration-none text-secondary">
                        {{ item.label }}
                    </router-link>
                </li>
            </ol>
        </nav>
    </div>
</template>

<script setup>
defineProps({
    title: String,
    breadcrumb: {
        type: Array,
        default: () => [],
    },
});
</script>
