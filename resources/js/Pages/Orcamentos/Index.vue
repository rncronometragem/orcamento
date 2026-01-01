<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import Lista from "./components/Lista.vue";

const props = defineProps({ orcamentos: Object, filters: Object });


</script>

<template>
    <AppLayout>
        <template #header>Orçamentos por Cliente</template>

        <Lista
            :orcamentos="orcamentos"
            :filters="filters"
        />
    </AppLayout>
</template>
