<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({ produtos: Object, filters: Object });

const search = ref(props.filters.search || '');
// Criamos uma ref para a página atual baseada no retorno do Laravel
const page = ref(props.produtos.current_page);

// Watch para a busca (mantido do original)
watch(search, debounce((val) => {
    router.get('/produtos', { search: val, page: 1 }, { preserveState: true, replace: true });
}, 300));

// Função para mudar de página usando o componente v-pagination
const onPageChange = (newPage) => {
    router.get('/produtos', { search: search.value, page: newPage }, { preserveState: true, replace: true });
};

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

        <v-card elevation="2" rounded="lg">

            <v-card-text class="d-flex flex-column flex-md-row justify-space-between align-center gap-4 pa-4">
                <div style="width: 100%; max-width: 400px;">
                    <v-text-field
                        v-model="search"
                        label="Buscar produto..."
                        prepend-inner-icon="mdi-magnify"
                        variant="outlined"
                        density="compact"
                        hide-details
                        bg-color="white"
                    ></v-text-field>
                </div>

                <Link href="/produtos/novo" class="text-decoration-none">
                    <v-btn color="blue" prepend-icon="mdi-plus" class="text-white font-weight-bold">
                        Novo Produto
                    </v-btn>
                </Link>
            </v-card-text>

            <v-divider></v-divider>

            <v-table hover>
                <thead>
                <tr class="bg-grey-lighten-4 text-uppercase text-caption text-grey-darken-1">
                    <th class="font-weight-bold py-3">Produto</th>
                    <th class="font-weight-bold py-3">Valor</th>
                    <th class="font-weight-bold py-3">Última Alteração</th>
                    <th class="font-weight-bold py-3 text-center">Ações</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="prod in produtos.data" :key="prod.id">
                    <td class="font-weight-bold text-grey-darken-3 py-4">
                        {{ prod.nome }}
                    </td>
                    <td class="text-green-darken-1 font-weight-bold py-4">
                        {{ formatarMoeda(prod.valor) }}
                    </td>
                    <td class="text-caption text-grey-darken-1 py-4">
                        {{ prod.usuario_log || '-' }}
                    </td>
                    <td class="text-center py-4">
                        <div class="d-flex justify-center gap-2">
                            <Link :href="`/produtos/${prod.id}/editar`" class="text-decoration-none">
                                <v-btn
                                    color="amber-lighten-4"
                                    variant="flat"
                                    size="small"
                                    class="text-amber-darken-4 font-weight-bold"
                                    elevation="0"
                                >
                                    Editar
                                </v-btn>
                            </Link>

                            <v-btn
                                color="red-lighten-4"
                                variant="flat"
                                size="small"
                                class="text-red-darken-4 font-weight-bold"
                                elevation="0"
                                @click="deletar(prod.id)"
                            >
                                Excluir
                            </v-btn>
                        </div>
                    </td>
                </tr>

                <tr v-if="produtos.data.length === 0">
                    <td colspan="4" class="text-center pa-8 text-grey">
                        <v-icon icon="mdi-package-variant" size="large" class="mb-2"></v-icon>
                        <div>Nenhum produto encontrado.</div>
                    </td>
                </tr>
                </tbody>
            </v-table>

            <v-divider></v-divider>

            <div v-if="produtos.last_page > 1" class="pa-4 d-flex justify-center">
                <v-pagination
                    v-model="page"
                    :length="produtos.last_page"
                    :total-visible="5"
                    color="blue"
                    density="comfortable"
                    @update:model-value="onPageChange"
                ></v-pagination>
            </div>

        </v-card>
    </AppLayout>
</template>

<style scoped>
/* Pequeno ajuste para garantir espaçamento entre botões na coluna de ações */
.gap-2 {
    gap: 8px;
}
.gap-4 {
    gap: 16px;
}
</style>
