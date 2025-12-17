<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce'; // Vamos usar para não buscar a cada letra digitada

// Recebendo os dados do Controller
const props = defineProps({
    clientes: Object,
    filters: Object
});

// Controle do campo de busca
const search = ref(props.filters.search || '');

// Observa o campo de busca e dispara a requisição ao Controller
// O debounce espera o usuário parar de digitar por 300ms antes de buscar
watch(search, debounce((value) => {
    router.get('/clientes', { search: value }, {
        preserveState: true, // Mantém a posição do scroll
        replace: true        // Não suja o histórico do navegador
    });
}, 300));

// Função auxiliar para deletar (opcional por enquanto)
function deletar(id) {
    if(confirm('Tem certeza que deseja excluir este cliente?')) {
        router.delete(`/clientes/${id}`);
    }
}
</script>

<template>
    <AppLayout>
        <template #header>
            Gerenciar Clientes
        </template>

        <div class="bg-white rounded-lg shadow overflow-hidden">

            <div class="p-4 border-b border-gray-200 flex flex-col md:flex-row justify-between items-center gap-4">

                <div class="relative w-full md:w-1/3">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Buscar por nome ou CPF/CNPJ..."
                        class="w-full pl-10 pr-4 py-2 border rounded-lg text-sm focus:outline-none focus:border-blue-500"
                    >
                    <span class="absolute left-3 top-2.5 text-gray-400">🔍</span>
                </div>

                <Link href="/clientes/novo" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-bold flex items-center">
                    <span class="mr-2">+</span> Novo Cliente
                </Link>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                    <tr class="bg-gray-50 text-gray-600 text-xs uppercase font-semibold">
                        <th class="p-4 border-b">Nome / Razão Social</th>
                        <th class="p-4 border-b">Documento</th>
                        <th class="p-4 border-b">Localização</th>
                        <th class="p-4 border-b">Contato Principal</th>
                        <th class="p-4 border-b text-center">Ações</th>
                    </tr>
                    </thead>
                    <tbody class="text-sm text-gray-700 divide-y divide-gray-100">

                    <tr v-if="clientes.data.length === 0">
                        <td colspan="5" class="p-8 text-center text-gray-500">
                            Nenhum cliente encontrado.
                        </td>
                    </tr>

                    <tr v-for="cliente in clientes.data" :key="cliente.id" class="hover:bg-gray-50 transition">

                        <td class="p-4 font-medium text-gray-900">
                            {{ cliente.nome }}
                            <div class="text-xs text-gray-400 mt-1">{{ cliente.tipo_pessoa === 'juridica' ? 'Empresa' : 'Pessoa Física' }}</div>
                        </td>

                        <td class="p-4">
                            {{ cliente.documento || '-' }}
                        </td>

                        <td class="p-4">
                            <div v-if="cliente.enderecos && cliente.enderecos.length > 0">
                                {{ cliente.enderecos[0].cidade }} / {{ cliente.enderecos[0].uf }}
                            </div>
                            <span v-else class="text-gray-400 italic">Sem endereço</span>
                        </td>

                        <td class="p-4">
                            <div v-if="cliente.contatos && cliente.contatos.length > 0">
                                <div class="font-medium">{{ cliente.contatos[0].celular }}</div>
                                <div class="text-xs text-gray-500">{{ cliente.contatos[0].email }}</div>
                            </div>
                            <span v-else class="text-gray-400 italic">Sem contato</span>
                        </td>

                        <td class="p-4 flex justify-center gap-2">
                            <Link :href="`/clientes/${cliente.id}/editar`" class="text-blue-600 hover:text-blue-800 bg-blue-50 hover:bg-blue-100 p-2 rounded">
                                ✏️
                            </Link>
                            <button @click="deletar(cliente.id)" class="text-red-600 hover:text-red-800 bg-red-50 hover:bg-red-100 p-2 rounded">
                                🗑️
                            </button>
                        </td>

                    </tr>
                    </tbody>
                </table>
            </div>

            <div v-if="clientes.data.length > 0" class="p-4 border-t border-gray-200 flex justify-center">
                <div class="flex gap-1">
                    <Link
                        v-for="(link, key) in clientes.links"
                        :key="key"
                        :href="link.url ?? '#'"
                        v-html="link.label"
                        class="px-3 py-1 border rounded text-sm"
                        :class="{
              'bg-blue-600 text-white border-blue-600': link.active,
              'text-gray-500 bg-white hover:bg-gray-50': !link.active,
              'opacity-50 cursor-not-allowed': !link.url
            }"
                    />
                </div>
            </div>

        </div>
    </AppLayout>
</template>
