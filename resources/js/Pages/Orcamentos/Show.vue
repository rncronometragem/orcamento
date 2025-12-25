<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    orcamento: Object,
});

const formatarMoeda = (val) => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val);

const formatarData = (data) => {
    if (!data) return '-';
    return new Date(data).toLocaleDateString('pt-BR');
};

const statusClasses = (status) => {
    const classes = {
        'pendente': 'bg-yellow-100 text-yellow-800 border-yellow-200',
        'aprovado': 'bg-green-100 text-green-800 border-green-200',
        'rejeitado': 'bg-red-100 text-red-800 border-red-200',
        'cancelado': 'bg-gray-100 text-gray-800 border-gray-200',
    };
    return classes[status] || 'bg-gray-100';
};
</script>

<template>
    <AppLayout title="Detalhes do Orçamento">
        <template #header>
            <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Orçamento #{{ orcamento.id }}
                </h2>
                <div class="flex gap-2">
                    <Link :href="route('orcamentos.index')" class="px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50">
                        Voltar
                    </Link>
                    <a :href="route('orcamentos.publico', orcamento.hash)" target="_blank" class="px-4 py-2 bg-indigo-50 border border-indigo-200 rounded-md font-semibold text-xs text-indigo-700 uppercase tracking-widest shadow-sm hover:bg-indigo-100 flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Link Público
                    </a>
                    <Link :href="route('orcamentos.edit', orcamento.id)" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        Editar
                    </Link>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">

                    <div class="p-6 md:p-8 border-b border-gray-200 bg-gray-50 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                        <div>
                            <span :class="statusClasses(orcamento.status)" class="px-3 py-1 rounded-full text-sm font-bold uppercase border">
                                {{ orcamento.status }}
                            </span>
                            <div class="mt-2 text-sm text-gray-500">
                                Criado em: {{ formatarData(orcamento.created_at) }}
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Valor Total</div>
                            <div class="text-3xl font-bold text-gray-800">{{ formatarMoeda(orcamento.valor_total) }}</div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 p-6 md:p-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Dados do Cliente</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Nome</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ orcamento.cliente.nome }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ orcamento.cliente.email || '-' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Telefone</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ orcamento.cliente.telefone || '-' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Detalhes do Evento</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Data do Evento</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ formatarData(orcamento.data_evento) }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Local</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ orcamento.local_evento || 'Não informado' }}</dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Validade da Proposta</dt>
                                    <dd class="mt-1 text-sm text-red-600">{{ formatarData(orcamento.data_expiracao) }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="px-6 md:px-8 pb-8">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Itens do Orçamento</h3>
                        <div class="border rounded-lg overflow-hidden">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Qtd</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Preço Unit.</th>
                                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Subtotal</th>
                                </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="item in orcamento.itens" :key="item.id">
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ item.nome }}</div>
                                        <div class="text-sm text-gray-500">{{ item.descricao }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center text-sm text-gray-500">
                                        {{ item.quantidade }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm text-gray-500">
                                        {{ formatarMoeda(item.preco_unitario) }}
                                    </td>
                                    <td class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                        {{ formatarMoeda(item.subtotal) }}
                                    </td>
                                </tr>
                                </tbody>
                                <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right text-sm font-bold text-gray-900">Total Geral</td>
                                    <td class="px-6 py-4 text-right text-sm font-bold text-gray-900">{{ formatarMoeda(orcamento.valor_total) }}</td>
                                </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>

                    <div class="px-6 md:px-8 pb-8" v-if="orcamento.observacoes">
                        <h3 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Observações Internas</h3>
                        <div class="bg-yellow-50 border border-yellow-100 rounded p-4 text-sm text-gray-700 whitespace-pre-wrap">
                            {{ orcamento.observacoes }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>
