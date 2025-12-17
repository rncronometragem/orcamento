<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({ produtos: Object, filters: Object });
const search = ref(props.filters.search || '');

watch(search, debounce((val) => {
    router.get('/produtos', { search: val }, { preserveState: true, replace: true });
}, 300));

function deletar(id) {
    if (confirm('Deseja realmente excluir este produto?')) {
        router.delete(`/produtos/${id}`);
    }
}

const formatarMoeda = (valor) => {
    if (!valor) return 'R$ 0,00';
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(valor);
}
</script>

<template>
    <AppLayout>
        <template #header>Gerenciar Produtos</template>

        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="p-4 border-b flex flex-col md:flex-row justify-between items-center gap-4">
                <input v-model="search" type="text" placeholder="Buscar produto..." class="border rounded p-2 w-full md:w-1/3">
                <Link href="/produtos/novo" class="bg-blue-600 text-white px-4 py-2 rounded font-bold hover:bg-blue-700">
                    + Novo Produto
                </Link>
            </div>

            <table class="w-full text-left">
                <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
                <tr>
                    <th class="p-4">Produto</th>
                    <th class="p-4">Valor</th>
                    <th class="p-4">Última Alteração</th> <th class="p-4 text-center">Ações</th>
                </tr>
                </thead>
                <tbody class="divide-y">
                <tr v-for="prod in produtos.data" :key="prod.id" class="hover:bg-gray-50">
                    <td class="p-4 font-bold text-gray-700">
                        {{ prod.nome }}
                    </td>
                    <td class="p-4 text-green-600 font-bold">
                        {{ formatarMoeda(prod.valor) }}
                    </td>
                    <td class="p-4 text-xs text-gray-500">
                        {{ prod.usuario_log || '-' }}
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <Link :href="`/produtos/${prod.id}/editar`" class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded text-sm hover:bg-yellow-200">
                            Editar
                        </Link>
                        <button @click="deletar(prod.id)" class="bg-red-100 text-red-700 px-3 py-1 rounded text-sm hover:bg-red-200">
                            Excluir
                        </button>
                    </td>
                </tr>
                <tr v-if="produtos.data.length === 0">
                    <td colspan="4" class="p-6 text-center text-gray-500">Nenhum produto encontrado.</td>
                </tr>
                </tbody>
            </table>

            <div v-if="produtos.links.length > 3" class="p-4 flex justify-center gap-1">
                <Link v-for="(link, k) in produtos.links" :key="k" :href="link.url ?? '#'" v-html="link.label"
                      class="px-3 py-1 border rounded text-sm"
                      :class="link.active ? 'bg-blue-600 text-white' : 'bg-white hover:bg-gray-100'"
                />
            </div>
        </div>
    </AppLayout>
</template>
