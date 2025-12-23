<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';
import { Ziggy } from '@/ziggy';

const props = defineProps({
    orcamento: Object,
    empresa: Object
});

// Form para as ações de Aprovar/Rejeitar
const form = useForm({
    status: '',
    observacao: ''
});

const enviarResposta = (status) => {
    if (!confirm(`Tem certeza que deseja ${status === 'aprovado' ? 'APROVAR' : 'REJEITAR'} este orçamento?`)) return;

    form.status = status;
    form.post(route('orcamentos.responder', props.orcamento.hash));
};

const formatMoney = (value) => {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
};

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('pt-BR');
};

const totalGeral = computed(() => {
    return props.orcamento.itens.reduce((acc, item) => acc + (item.quantidade * item.preco_unitario), 0);
});
</script>

<template>
    <Head title="Orçamento Detalhado" />

    <div class="min-h-screen bg-gray-100 py-8 px-4 sm:px-6">
        <div class="max-w-4xl mx-auto bg-white shadow-xl rounded-lg overflow-hidden border border-gray-200">

            <div class="bg-slate-800 text-white p-8 flex justify-between items-start">
                <div>
                    <h1 class="text-3xl font-bold">ORÇAMENTO</h1>
                    <p class="text-slate-400 mt-1">#{{ orcamento.id.toString().padStart(6, '0') }}</p>
                    <div class="mt-4">
                        <span
                            class="px-3 py-1 rounded-full text-sm font-bold uppercase tracking-wider"
                            :class="{
                                'bg-yellow-500 text-yellow-900': orcamento.status === 'pendente',
                                'bg-green-500 text-green-900': orcamento.status === 'aprovado',
                                'bg-red-500 text-red-900': orcamento.status === 'rejeitado',
                            }"
                        >
                            {{ orcamento.status }}
                        </span>
                    </div>
                </div>
                <div class="text-right" v-if="empresa">
                    <div class="h-16 w-16 bg-slate-600 rounded mb-2 ml-auto flex items-center justify-center">
                        <img :src="'/storage/' + empresa.logo ">
                    </div>
                    <h2 class="font-semibold text-lg">{{ empresa.nome }}</h2>
                    <p class="text-sm text-slate-300">{{ empresa.email }}</p>
                    <p class="text-sm text-slate-300">{{ empresa.telefone }}</p>
                </div>
            </div>

            <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8 border-b">
                <div>
                    <h3 class="text-gray-500 uppercase text-xs font-bold tracking-wider mb-2">Cliente</h3>
                    <p class="text-lg font-bold text-gray-800">{{ orcamento.cliente.nome }}</p>
                    <p class="text-gray-600">{{ orcamento.cliente.email }}</p>
                    <p class="text-gray-600" v-if="orcamento.cliente.documento">CPF/CNPJ: {{ orcamento.cliente.documento }}</p>
                </div>
                <div class="md:text-right">
                    <div class="mb-2">
                        <span class="text-gray-500 uppercase text-xs font-bold tracking-wider">Data de Emissão:</span>
                        <span class="ml-2 font-medium">{{ formatDate(orcamento.created_at) }}</span>
                    </div>
                    <div >
                        <span class="text-gray-500 uppercase text-xs font-bold tracking-wider">Validade:</span>
                        <span v-if="orcamento.data_expiracao" class="ml-2 font-medium text-red-600">{{ formatDate(orcamento.data_expiracao) }}</span>
                        <span v-else> Sem prazo</span>
                    </div>
                </div>
            </div>

            <div class="p-8" v-if="orcamento.pode_ver_unitarios">
                <h3 class="text-gray-500 uppercase text-xs font-bold tracking-wider mb-4">Itens / Serviços</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                        <tr class="border-b-2 border-slate-200">
                            <th class="py-3 text-sm text-gray-600">Descrição</th>
                            <th class="py-3 text-sm text-gray-600 text-center">Qtd.</th>
                            <th class="py-3 text-sm text-gray-600 text-right">Preço Unit.</th>
                            <th class="py-3 text-sm text-gray-600 text-right">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in orcamento.itens" :key="item.id" class="border-b border-gray-100 last:border-0">
                            <td class="py-4 pr-4">
                                <p class="font-medium text-gray-800">{{ item.nome }}</p>
                                <p class="text-sm text-gray-500">{{ item.descricao }}</p>
                            </td>
                            <td class="py-4 text-center">{{ item.quantidade }}</td>
                            <td class="py-4 text-right">{{ formatMoney(item.preco_unitario) }}</td>
                            <td class="py-4 text-right font-medium">{{ formatMoney(item.subtotal) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="bg-gray-50 p-8 flex flex-col md:flex-row justify-end items-end gap-2 border-t">
                <div class="w-full md:w-1/2 space-y-3">
                    <div class="flex justify-between text-gray-600">
                        <span>Subtotal</span>
                        <span>{{ formatMoney(totalGeral) }}</span>
                    </div>
                    <div class="flex justify-between text-gray-600" v-if="orcamento.desconto > 0">
                        <span>Desconto</span>
                        <span class="text-green-600">- {{ formatMoney(orcamento.desconto) }}</span>
                    </div>
                    <div class="flex justify-between text-2xl font-bold text-slate-800 pt-4 border-t border-gray-300">
                        <span>Total</span>
                        <span>{{ formatMoney(totalGeral - (orcamento.desconto || 0)) }}</span>
                    </div>
                </div>
            </div>

            <div v-if="orcamento.status === 'pendente'" class="p-8 bg-slate-50 border-t border-slate-200 flex flex-col sm:flex-row gap-4 justify-end">
                <button
                    @click="enviarResposta('rejeitado')"
                    :disabled="form.processing"
                    class="px-6 py-3 bg-white border border-red-300 text-red-600 font-semibold rounded hover:bg-red-50 transition shadow-sm w-full sm:w-auto"
                >
                    Recusar Proposta
                </button>
                <button
                    @click="enviarResposta('aprovado')"
                    :disabled="form.processing"
                    class="px-6 py-3 bg-green-600 text-white font-bold rounded hover:bg-green-700 transition shadow-lg w-full sm:w-auto flex items-center justify-center gap-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    Aprovar Orçamento
                </button>
            </div>

            <div v-if="orcamento.status === 'aprovado'" class="p-8 bg-green-50 text-center border-t border-green-100">
                <p class="text-green-800 font-medium text-lg">Este orçamento foi aprovado em {{ formatDate(orcamento.data_resposta) }}.</p>
                <p class="text-green-600 text-sm mt-1">Entraremos em contato em breve para iniciar o serviço.</p>
            </div>

        </div>

        <div class="max-w-4xl mx-auto text-center mt-8 text-gray-400 text-sm">
            &copy; {{ new Date().getFullYear() }} {{ (empresa) ? empresa.nome : "OrçamentoSYS" }}. Todos os direitos reservados.
        </div>
    </div>
</template>
