<script setup>
import { ref, watch } from 'vue';
import {Link, router} from "@inertiajs/vue3";
import debounce from 'lodash/debounce';

const props = defineProps({ orcamentos: Object, filters: Object });

// Controle de qual painel está aberto (opcional)
const panel = ref([]);

const formatarMoeda = (val) => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val);

// Mapeamento de Cores para o Vuetify
const getStatusColor = (status) => {
    if (!status) return 'grey';
    const s = status.toLowerCase();
    const map = {
        'pendente': 'warning',
        'aprovado': 'success',
        'rejeitado': 'error',
        'cancelado': 'grey-darken-1',
    };
    return map[s] || 'grey';
};

const search = ref(props.filters.search || '');

watch(search, debounce((val) => {
    router.get('/orcamentos', { search: val }, { preserveState: true, replace: true });
}, 300));

</script>

<template>
    <div>
        <v-card elevation="2" rounded="lg" class="mb-6">
            <v-card-text class="d-flex flex-column flex-md-row justify-space-between align-center gap-4 pa-4">
                <div style="width: 100%; max-width: 400px;">
                    <v-text-field
                        v-model="search"
                        label="Buscar por cliente ou ID..."
                        prepend-inner-icon="mdi-magnify"
                        variant="outlined"
                        density="compact"
                        hide-details
                        bg-color="white"
                    ></v-text-field>
                </div>

                <Link href="/orcamentos/novo" class="text-decoration-none">
                    <v-btn color="blue" prepend-icon="mdi-plus" class="text-white font-weight-bold">
                        Novo Orçamento
                    </v-btn>
                </Link>
            </v-card-text>
        </v-card>

        <v-expansion-panels v-model="panel" variant="popout" class="pa-0">

            <v-expansion-panel
                v-for="(item, id) in orcamentos"
                :key="id"
                elevation="1"
            >
                <v-expansion-panel-title>
                    <div class="d-flex align-center">
                        <v-avatar color="blue-lighten-5" size="32" class="mr-3">
                            <span class="text-blue font-weight-bold">{{ item.nome_cliente.charAt(0) }}</span>
                        </v-avatar>
                        <span class="text-subtitle-1 font-weight-bold text-grey-darken-3">
                            {{ item.nome_cliente }}
                        </span>
                        <v-chip size="x-small" class="ml-3" variant="outlined">
                            {{ item.items.length }} orçamentos
                        </v-chip>
                    </div>
                </v-expansion-panel-title>

                <v-expansion-panel-text>
                    <v-table density="comfortable">
                        <thead>
                        <tr class="text-uppercase text-caption text-grey-darken-1">
                            <th class="text-center font-weight-bold">Status</th>
                            <th class="text-center font-weight-bold">Total</th>
                            <th class="text-center font-weight-bold">Data do Evento</th>
                            <th class="text-center font-weight-bold">Local</th>
                            <th class="text-center font-weight-bold">Ações</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="orc in item.items" :key="orc.id">
                            <td class="text-center">
                                <v-chip
                                    :color="getStatusColor(orc.status)"
                                    size="small"
                                    label
                                    class="text-uppercase font-weight-bold"
                                >
                                    {{ orc.status }}
                                </v-chip>
                            </td>

                            <td class="text-center font-weight-bold text-grey-darken-3">
                                {{ formatarMoeda(orc.valor_total) }}
                            </td>

                            <td class="text-center text-body-2">
                                {{ orc.data_evento ? new Date(orc.data_evento).toLocaleDateString() : '-' }}
                            </td>

                            <td class="text-center text-body-2 text-grey-darken-1">
                                {{ orc.local_evento || '-' }}
                            </td>

                            <td class="text-center">
                                <div class="d-flex justify-center gap-2">

                                    <Link :href="`/orcamentos/${orc.id}`">
                                        <v-btn icon="mdi-eye" size="x-small" variant="text" color="blue" v-tooltip="'Ver Detalhes'"></v-btn>
                                    </Link>

                                    <Link :href="`/orcamentos/${orc.id}/editar`">
                                        <v-btn icon="mdi-pencil" size="x-small" variant="text" color="amber-darken-2" v-tooltip="'Editar'"></v-btn>
                                    </Link>

                                    <Link :href="`/proposta/${orc.hash}`" target="_blank">
                                        <v-btn icon="mdi-share-variant" size="x-small" variant="text" color="teal" v-tooltip="'Link da Proposta'"></v-btn>
                                    </Link>

                                </div>
                            </td>
                        </tr>
                        </tbody>
                    </v-table>
                </v-expansion-panel-text>
            </v-expansion-panel>

        </v-expansion-panels>

        <div v-if="Object.keys(orcamentos).length === 0" class="text-center mt-10 text-grey">
            <v-icon icon="mdi-file-document-off" size="50" class="mb-2"></v-icon>
            <p>Nenhum orçamento encontrado.</p>
        </div>
    </div>
</template>
<style scoped>
.gap-4 { gap: 16px; }
.gap-2 { gap: 8px; }
</style>
