<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import debounce from 'lodash/debounce';

const props = defineProps({
    clientes: Object,
    filters: Object
});

const search = ref(props.filters.search || '');
// Ref para controlar a página atual na paginação do Vuetify
const page = ref(props.clientes.current_page);

watch(search, debounce((value) => {
    // Ao buscar, voltamos para a página 1
    router.get('/clientes', { search: value, page: 1 }, {
        preserveState: true,
        replace: true
    });
}, 300));

const onPageChange = (newPage) => {
    router.get('/clientes', { search: search.value, page: newPage }, {
        preserveState: true,
        replace: true
    });
};

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

        <v-card elevation="2" rounded="lg">

            <v-card-text class="d-flex flex-column flex-md-row justify-space-between align-center gap-4 pa-4">
                <div style="width: 100%; max-width: 400px;">
                    <v-text-field
                        v-model="search"
                        label="Buscar por nome ou CPF/CNPJ..."
                        prepend-inner-icon="mdi-magnify"
                        variant="outlined"
                        density="compact"
                        hide-details
                        bg-color="white"
                    ></v-text-field>
                </div>

                <Link href="/clientes/novo" class="text-decoration-none">
                    <v-btn color="blue" prepend-icon="mdi-plus" class="text-white font-weight-bold">
                        Novo Cliente
                    </v-btn>
                </Link>
            </v-card-text>

            <v-divider></v-divider>

            <v-table hover>
                <thead>
                <tr class="bg-grey-lighten-4 text-uppercase text-caption text-grey-darken-1">
                    <th class="font-weight-bold py-3">Nome / Razão Social</th>
                    <th class="font-weight-bold py-3">Documento</th>
                    <th class="font-weight-bold py-3">Localização</th>
                    <th class="font-weight-bold py-3">Contato Principal</th>
                    <th class="font-weight-bold py-3 text-center">Ações</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="cliente in clientes.data" :key="cliente.id">
                    <td class="py-4">
                        <div class="font-weight-bold text-grey-darken-3">
                            {{ cliente.nome }}
                        </div>
                        <div class="mt-1">
                            <v-chip
                                size="x-small"
                                :color="cliente.tipo_pessoa === 'juridica' ? 'indigo' : 'teal'"
                                variant="tonal"
                                label
                            >
                                {{ cliente.tipo_pessoa === 'juridica' ? 'Empresa' : 'Pessoa Física' }}
                            </v-chip>
                        </div>
                    </td>

                    <td class="text-body-2 text-grey-darken-2 py-4">
                        {{ cliente.documento || '-' }}
                    </td>

                    <td class="py-4">
                        <div v-if="cliente.enderecos && cliente.enderecos.length > 0" class="text-body-2">
                            {{ cliente.enderecos[0].cidade }} / {{ cliente.enderecos[0].uf }}
                        </div>
                        <span v-else class="text-caption text-grey font-italic">
                                Sem endereço
                            </span>
                    </td>

                    <td class="py-4">
                        <div v-if="cliente.contatos && cliente.contatos.length > 0">
                            <div class="font-weight-medium text-body-2">
                                {{ cliente.contatos[0].celular }}
                            </div>
                            <div class="text-caption text-grey">
                                {{ cliente.contatos[0].email }}
                            </div>
                        </div>
                        <span v-else class="text-caption text-grey font-italic">
                                Sem contato
                            </span>
                    </td>

                    <td class="text-center py-4">
                        <div class="d-flex justify-center gap-2">
                            <Link :href="`/clientes/${cliente.id}/editar`" class="text-decoration-none">
                                <v-btn
                                    icon="mdi-pencil"
                                    color="blue-lighten-4"
                                    variant="flat"
                                    size="x-small"
                                    class="text-blue-darken-4"
                                    elevation="0"
                                ></v-btn>
                            </Link>

                            <v-btn
                                icon="mdi-delete"
                                color="red-lighten-4"
                                variant="flat"
                                size="x-small"
                                class="text-red-darken-4"
                                elevation="0"
                                @click="deletar(cliente.id)"
                            ></v-btn>
                        </div>
                    </td>
                </tr>

                <tr v-if="clientes.data.length === 0">
                    <td colspan="5" class="text-center pa-8 text-grey">
                        <v-icon icon="mdi-account-off" size="large" class="mb-2"></v-icon>
                        <div>Nenhum cliente encontrado.</div>
                    </td>
                </tr>
                </tbody>
            </v-table>

            <v-divider></v-divider>

            <div v-if="clientes.last_page > 1" class="pa-4 d-flex justify-center">
                <v-pagination
                    v-model="page"
                    :length="clientes.last_page"
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
.gap-4 { gap: 16px; }
.gap-2 { gap: 8px; }
</style>
