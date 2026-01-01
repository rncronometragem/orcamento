<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { computed, watch } from 'vue';
import Form from './components/Form.vue';

const props = defineProps({
    orcamento: Object,
    clientes: Array,
    produtos: Array
});

// const props = defineProps({
//     orcamento: Object,
//     clientes: Array,
//     produtos: Array
// });
//
// const isEditing = computed(() => !!props.orcamento);
//
// const form = useForm({
//     _method: isEditing.value ? 'put' : 'post',
//     cliente_id: props.orcamento?.cliente_id || '',
//     status: props.orcamento?.status || 'pendente',
//     observacoes: props.orcamento?.observacoes || '',
//     // ADICIONADO: Inicializa com o valor do banco ou false se for novo
//     pode_ver_unitarios: props.orcamento?.pode_ver_unitarios ?? false,
//     itens: props.orcamento?.itens || [
//         { descricao: '', quantidade: 1, preco_unitario: 0, produto_temp: '' }
//     ],
//     data_evento: props.orcamento?.data_evento || '',
//     local_evento: props.orcamento?.local_evento || '',
// });
//
// // === LÓGICA DE ITENS ===
//
// const addItem = () => {
//     form.itens.push({ descricao: '', quantidade: 1, preco_unitario: 0, produto_temp: '' });
// };
//
// const removeItem = (index) => {
//     if (form.itens.length > 1) {
//         form.itens.splice(index, 1);
//     }
// };
//
// const aoSelecionarProduto = (index, event) => {
//     const produtoId = event.target.value;
//     if (!produtoId) return;
//
//     const prod = props.produtos.find(p => p.id == produtoId);
//     if (prod) {
//         form.itens[index].descricao = prod.nome;
//         form.itens[index].preco_unitario = parseFloat(prod.valor);
//         form.itens[index].produto_temp = '';
//     }
// };
//
// // === CÁLCULOS ===
//
// const totalGeral = computed(() => {
//     return form.itens.reduce((acc, item) => {
//         return acc + (item.quantidade * item.preco_unitario);
//     }, 0);
// });
//
// const formatarMoeda = (val) => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val);
//
// const submit = () => {
//     if (isEditing.value) {
//         form.post(`/orcamentos/${props.orcamento.id}`);
//     } else {
//         form.post('/orcamentos');
//     }
// };
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
