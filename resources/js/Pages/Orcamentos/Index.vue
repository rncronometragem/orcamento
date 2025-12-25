<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';
import { EyeIcon } from "@heroicons/vue/24/solid";

const props = defineProps({ orcamentos: Object, filters: Object });
const search = ref(props.filters.search || '');

watch(search, debounce((val) => {
    router.get('/orcamentos', { search: val }, { preserveState: true, replace: true });
}, 300));

const formatarMoeda = (val) => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val);

const statusClasses = (status) => {
    const classes = {
        'pendente': 'bg-yellow-100 text-yellow-800',
        'aprovado': 'bg-green-100 text-green-800',
        'rejeitado': 'bg-red-100 text-red-800',
        'cancelado': 'bg-gray-100 text-gray-800',
    };
    return classes[status] || 'bg-gray-100';
};
</script>

<template>
    <AppLayout>
        <template #header>Orçamentos</template>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b flex flex-col md:flex-row justify-between items-center gap-4">
                <input v-model="search" type="text" placeholder="Buscar por cliente ou ID..." class="border rounded p-2 w-full md:w-1/3">
                <Link href="/orcamentos/novo" class="bg-blue-600 text-white px-4 py-2 rounded font-bold hover:bg-blue-700">
                    + Novo Orçamento
                </Link>
            </div>

            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="p-4 text-center">Cliente</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Total</th>
                    <th class="p-4 text-center">Data do Evento</th>
                    <th class="p-4 text-center">Local do Evento</th>
                    <th class="p-4 text-center">Ações</th>
                </tr>
                </thead>
                <tbody class="divide-y">
                <tr v-for="orc in orcamentos.data" :key="orc.id" class="hover:bg-gray-50">
                    <td class="p-4 text-center">{{ orc.cliente.nome }}</td>
                    <td class="p-4 text-center">
                        <span :class="statusClasses(orc.status)" class="px-2 py-1 rounded text-xs font-bold uppercase">
                            {{ orc.status }}
                        </span>
                    </td>
                    <td class="p-4 text-center font-bold text-gray-700">{{ formatarMoeda(orc.valor_total) }}</td>
                    <td class="p-4 text-center">
                        <label v-if="orc.data_evento">
                            {{ new Date(orc.data_evento).toLocaleDateString() }}
                        </label>
                        <label v-else>
                            -
                        </label>
                    </td>
                    <td class="p-4 text-center">
                        {{ orc.local_evento ? orc.local_evento : '-'}}
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <Link :href="`/orcamentos/${orc.id}`" class="text-blue-600 hover:underline">
                            Ver
                        </Link>
                        <Link :href="`/orcamentos/${orc.id}/editar`" class="text-blue-600 hover:underline">Editar</Link>
                        <Link :href="`/proposta/${orc.hash}`" class="text-blue-600 hover:underline">Compartilhar</Link>
                    </td>
                </tr>
                </tbody>
            </table>

            <div v-if="orcamentos.links.length > 3" class="p-4 flex justify-center gap-1">
                <Link v-for="(link, k) in orcamentos.links" :key="k" :href="link.url ?? '#'" v-html="link.label"
                      class="px-3 py-1 border rounded text-sm"
                      :class="link.active ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-100'"
                />
            </div>
        </div>
    </AppLayout>
</template>
