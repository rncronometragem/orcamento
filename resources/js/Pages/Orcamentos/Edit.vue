<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Form from './components/Form.vue';

const props = defineProps({
    orcamento: Object,
    clientes: Array,
    produtos: Array
});
</script>

<template>
    <AppLayout>
        <template #header>{{ isEditing ? `Editar Orçamento #${orcamento.id}` : 'Novo Orçamento' }}</template>

        <Form
            :orcamento="orcamento"
            :clientes="clientes"
            :produtos="produtos"
            />
    </AppLayout>
</template>
