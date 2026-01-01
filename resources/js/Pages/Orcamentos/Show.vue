<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    orcamento: Object,
});

const formatarMoeda = (val) => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val);

const formatarData = (data) => {
    if (!data) return '-';
    return new Date(data).toLocaleDateString('pt-BR');
};

// Mapeamento de cores para o Vuetify
const getStatusColor = (status) => {
    const map = {
        'pendente': 'warning',
        'aprovado': 'success',
        'rejeitado': 'error',
        'cancelado': 'grey',
    };
    return map[status] || 'grey';
};
</script>

<template>
    <AppLayout title="Detalhes do Orçamento">
        <template #header>
            <div class="d-flex flex-column flex-md-row justify-space-between align-center">
                <div class="v-row py-1">
                    <div class="v-col-2">
                        <h2 class="text-h5 font-weight-bold text-grey-darken-3">
                            Orçamento #{{ orcamento.id }}
                        </h2>
                    </div>
                    <div class="v-col-2 text-center">
                        <Link :href="route('orcamentos.index')" class="text-decoration-none">
                            <v-btn
                                variant="outlined"
                                color="grey-darken-1"
                                prepend-icon="mdi-arrow-left"
                                class="text-none font-weight-bold"
                            >
                                Voltar
                            </v-btn>
                        </Link>
                    </div>
                    <div class="v-col-2">
                        <v-btn
                            :href="route('orcamentos.publico', orcamento.hash)"
                            target="_blank"
                            color="indigo-lighten-5"
                            class="text-indigo-darken-2 font-weight-bold text-none"
                            elevation="0"
                            prepend-icon="mdi-open-in-new"
                            border
                        >
                            Link Público
                        </v-btn>
                    </div>
                    <div class="v-col-2 text-center">
                        <Link :href="route('orcamentos.edit', orcamento.id)" class="text-decoration-none">
                            <v-btn
                                color="blue-darken-1"
                                class="text-white font-weight-bold text-none"
                                prepend-icon="mdi-pencil"
                                elevation="1"
                            >
                                Editar
                            </v-btn>
                        </Link>
                    </div>
                </div>
            </div>
        </template>

        <v-card elevation="2" rounded="lg">

            <div class="d-flex flex-column flex-md-row justify-space-between align-start align-md-center pa-6 bg-grey-lighten-5 border-b">
                <div>
                    <v-chip
                        :color="getStatusColor(orcamento.status)"
                        label
                        class="font-weight-bold text-uppercase mb-2"
                        variant="tonal"
                    >
                        {{ orcamento.status }}
                    </v-chip>
                    <div class="text-caption text-grey-darken-1">
                        <v-icon icon="mdi-calendar" size="x-small" class="mr-1"></v-icon>
                        Criado em: {{ formatarData(orcamento.created_at) }}
                    </div>
                </div>

                <div class="text-md-right mt-4 mt-md-0">
                    <div class="text-caption text-grey-darken-1 font-weight-bold text-uppercase">Valor Total</div>
                    <div class="text-h4 font-weight-bold text-grey-darken-3">
                        {{ formatarMoeda(orcamento.valor_total) }}
                    </div>
                </div>
            </div>

            <v-card-text class="pa-6">
                <v-row>
                    <v-col cols="12" md="6">
                        <div class="mb-4 pb-2 border-b">
                            <h3 class="text-h6 font-weight-medium text-grey-darken-3">Dados do Cliente</h3>
                        </div>

                        <v-list density="compact" class="pa-0">
                            <v-list-item class="px-0">
                                <template v-slot:subtitle>Nome</template>
                                <template v-slot:title>
                                    <span class="text-body-1 font-weight-medium text-grey-darken-3">{{ orcamento.cliente.nome }}</span>
                                </template>
                            </v-list-item>

                            <v-list-item class="px-0">
                                <template v-slot:subtitle>Email</template>
                                <template v-slot:title>
                                    <span class="text-body-2">{{ orcamento.cliente.email || '-' }}</span>
                                </template>
                            </v-list-item>

                            <v-list-item class="px-0">
                                <template v-slot:subtitle>Telefone</template>
                                <template v-slot:title>
                                    <span class="text-body-2">{{ orcamento.cliente.telefone || '-' }}</span>
                                </template>
                            </v-list-item>
                        </v-list>
                    </v-col>

                    <v-col cols="12" md="6">
                        <div class="mb-4 pb-2 border-b">
                            <h3 class="text-h6 font-weight-medium text-grey-darken-3">Detalhes do Evento</h3>
                        </div>

                        <v-list density="compact" class="pa-0">
                            <v-list-item class="px-0">
                                <template v-slot:subtitle>Data do Evento</template>
                                <template v-slot:title>
                                    <span class="text-body-1 font-weight-medium text-grey-darken-3">
                                        {{ formatarData(orcamento.data_evento) }}
                                    </span>
                                </template>
                            </v-list-item>

                            <v-list-item class="px-0">
                                <template v-slot:subtitle>Local</template>
                                <template v-slot:title>
                                    <span class="text-body-2">{{ orcamento.local_evento || 'Não informado' }}</span>
                                </template>
                            </v-list-item>

                            <v-list-item class="px-0">
                                <template v-slot:subtitle>Validade da Proposta</template>
                                <template v-slot:title>
                                    <span class="text-body-2 text-red-darken-1 font-weight-bold">
                                        {{ formatarData(orcamento.data_expiracao) }}
                                    </span>
                                </template>
                            </v-list-item>
                        </v-list>
                    </v-col>
                </v-row>
            </v-card-text>

            <v-divider></v-divider>

            <div class="pa-6">
                <h3 class="text-h6 font-weight-medium text-grey-darken-3 mb-4">Itens do Orçamento</h3>

                <v-card variant="outlined" class="overflow-hidden">
                    <v-table>
                        <thead class="bg-grey-lighten-4">
                        <tr>
                            <th class="text-left font-weight-bold text-uppercase text-caption text-grey-darken-1">Descrição</th>
                            <th class="text-center font-weight-bold text-uppercase text-caption text-grey-darken-1">Qtd</th>
                            <th class="text-right font-weight-bold text-uppercase text-caption text-grey-darken-1">Preço Unit.</th>
                            <th class="text-right font-weight-bold text-uppercase text-caption text-grey-darken-1">Subtotal</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="item in orcamento.itens" :key="item.id">
                            <td class="py-3">
                                <div class="font-weight-medium text-body-2">{{ item.nome }}</div>
                                <div class="text-caption text-grey">{{ item.descricao }}</div>
                            </td>
                            <td class="text-center text-body-2 text-grey-darken-1">{{ item.quantidade }}</td>
                            <td class="text-right text-body-2 text-grey-darken-1">{{ formatarMoeda(item.preco_unitario) }}</td>
                            <td class="text-right font-weight-bold text-body-2">{{ formatarMoeda(item.subtotal) }}</td>
                        </tr>
                        </tbody>
                        <tfoot class="bg-grey-lighten-5">
                        <tr>
                            <td colspan="3" class="text-right font-weight-bold text-body-1">Total Geral</td>
                            <td class="text-right font-weight-bold text-h6 text-grey-darken-3">{{ formatarMoeda(orcamento.valor_total) }}</td>
                        </tr>
                        </tfoot>
                    </v-table>
                </v-card>
            </div>

            <div v-if="orcamento.observacoes" class="pa-6 pt-0">
                <div class="text-caption font-weight-bold text-grey-darken-1 text-uppercase mb-2">Observações Internas</div>
                <v-sheet
                    color="amber-lighten-5"
                    class="pa-4 text-body-2 text-grey-darken-3 border rounded border-amber-lighten-4"
                    style="white-space: pre-wrap;"
                >
                    {{ orcamento.observacoes }}
                </v-sheet>
            </div>

        </v-card>
    </AppLayout>
</template>

<style scoped>
.gap-2 { gap: 8px; }
/* Borda manual para o sheet de observações parecer um alert customizado */
.border-amber-lighten-4 {
    border-color: #FFECB3 !important;
}
</style>
