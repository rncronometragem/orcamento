<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { route } from 'ziggy-js';

const props = defineProps({
    orcamento: Object,
    empresa: Object
});

const form = useForm({
    status: '',
    observacao: ''
});

const enviarResposta = (status) => {
    const action = status === 'aprovado' ? 'APROVAR' : 'REJEITAR';
    if (!confirm(`Tem certeza que deseja ${action} este orçamento?`)) return;

    form.status = status;
    form.post(route('orcamentos.responder', props.orcamento.hash));
};

const formatMoney = (value) => {
    return new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(value);
};

const formatDate = (date) => {
    if (!date) return '-';
    return new Date(date).toLocaleDateString('pt-BR');
};

const totalGeral = computed(() => {
    return props.orcamento.itens.reduce((acc, item) => acc + (item.quantidade * item.preco_unitario), 0);
});

const getStatusColor = (status) => {
    const map = {
        'pendente': 'warning',
        'aprovado': 'success',
        'rejeitado': 'error'
    };
    return map[status] || 'grey';
};
</script>

<template>
    <Head title="Orçamento Detalhado" />

    <v-app class="bg-grey-lighten-4">
        <v-main>
            <v-container class="py-10">

                <v-card class="mx-auto" max-width="900" elevation="4" rounded="lg">

                    <v-sheet color="blue-grey-darken-4" class="pa-8">
                        <v-row align="start">
                            <v-col cols="12" md="8">
                                <h1 class="text-h4 font-weight-bold text-white mb-1">ORÇAMENTO</h1>
                                <p class="text-subtitle-1 text-grey-lighten-1 mb-4">
                                    #{{ orcamento.id.toString().padStart(6, '0') }}
                                </p>

                                <v-chip
                                    :color="getStatusColor(orcamento.status)"
                                    variant="elevated"
                                    class="font-weight-bold text-uppercase"
                                    label
                                >
                                    {{ orcamento.status }}
                                </v-chip>
                            </v-col>

                            <v-col cols="12" md="4" class="text-md-right" v-if="empresa">
                                <div class="d-flex flex-column align-md-end">
                                    <v-avatar
                                        rounded="lg"
                                        color="blue-grey-lighten-4"
                                        size="80"
                                        class="mb-3"
                                    >
                                        <v-img v-if="empresa.logo" :src="'/storage/' + empresa.logo" cover></v-img>
                                        <v-icon v-else icon="mdi-domain" size="large" color="grey-darken-2"></v-icon>
                                    </v-avatar>

                                    <h2 class="text-h6 font-weight-bold text-white">{{ empresa.nome }}</h2>
                                    <div class="text-body-2 text-grey-lighten-2">{{ empresa.email }}</div>
                                    <div class="text-body-2 text-grey-lighten-2">{{ empresa.telefone }}</div>
                                </div>
                            </v-col>
                        </v-row>
                    </v-sheet>

                    <div class="pa-8">
                        <v-row>
                            <v-col cols="12" md="6">
                                <div class="text-caption font-weight-bold text-grey-darken-1 text-uppercase mb-1">Cliente</div>
                                <div class="text-h6 font-weight-bold text-grey-darken-3 mb-1">
                                    {{ orcamento.cliente.nome }}
                                </div>
                                <div class="text-body-2 text-grey-darken-1">{{ orcamento.cliente.email }}</div>
                                <div class="text-body-2 text-grey-darken-1" v-if="orcamento.cliente.documento">
                                    CPF/CNPJ: {{ orcamento.cliente.documento }}
                                </div>
                            </v-col>

                            <v-col cols="12" md="6" class="text-md-right">
                                <div class="mb-3">
                                    <span class="text-caption font-weight-bold text-grey-darken-1 text-uppercase mr-2">Data de Emissão:</span>
                                    <span class="text-body-1 font-weight-medium">{{ formatDate(orcamento.created_at) }}</span>
                                </div>
                                <div>
                                    <span class="text-caption font-weight-bold text-grey-darken-1 text-uppercase mr-2">Validade:</span>
                                    <span
                                        class="text-body-1 font-weight-medium"
                                        :class="orcamento.data_expiracao ? 'text-red-darken-1' : ''"
                                    >
                                        {{ orcamento.data_expiracao ? formatDate(orcamento.data_expiracao) : 'Sem prazo' }}
                                    </span>
                                </div>
                            </v-col>
                        </v-row>
                    </div>

                    <v-divider></v-divider>

                    <div class="pa-8" v-if="orcamento.pode_ver_unitarios">
                        <div class="text-caption font-weight-bold text-grey-darken-1 text-uppercase mb-4">Itens / Serviços</div>

                        <v-table density="comfortable">
                            <thead>
                            <tr>
                                <th class="text-left font-weight-bold text-grey-darken-2">Descrição</th>
                                <th class="text-center font-weight-bold text-grey-darken-2">Qtd.</th>
                                <th class="text-right font-weight-bold text-grey-darken-2">Preço Unit.</th>
                                <th class="text-right font-weight-bold text-grey-darken-2">Total</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="item in orcamento.itens" :key="item.id">
                                <td class="py-4">
                                    <div class="font-weight-medium text-grey-darken-3">{{ item.nome }}</div>
                                    <div class="text-caption text-grey-darken-1">{{ item.descricao }}</div>
                                </td>
                                <td class="text-center">{{ item.quantidade }}</td>
                                <td class="text-right">{{ formatMoney(item.preco_unitario) }}</td>
                                <td class="text-right font-weight-medium">{{ formatMoney(item.subtotal) }}</td>
                            </tr>
                            </tbody>
                        </v-table>
                    </div>

                    <div class="bg-grey-lighten-5 pa-8">
                        <v-row justify="end">
                            <v-col cols="12" md="6" lg="5">
                                <div class="d-flex justify-space-between mb-2 text-grey-darken-2">
                                    <span>Subtotal</span>
                                    <span>{{ formatMoney(totalGeral) }}</span>
                                </div>

                                <div class="d-flex justify-space-between mb-4 text-green-darken-2" v-if="orcamento.desconto > 0">
                                    <span>Desconto</span>
                                    <span>- {{ formatMoney(orcamento.desconto) }}</span>
                                </div>

                                <v-divider class="mb-4"></v-divider>

                                <div class="d-flex justify-space-between align-center">
                                    <span class="text-h6 font-weight-regular text-grey-darken-3">Total</span>
                                    <span class="text-h4 font-weight-bold text-blue-grey-darken-4">
                                        {{ formatMoney(totalGeral - (orcamento.desconto || 0)) }}
                                    </span>
                                </div>
                            </v-col>
                        </v-row>
                    </div>

                    <div v-if="orcamento.status === 'pendente'" class="pa-8 border-t">
                        <v-row justify="end" dense>
                            <v-col cols="12" sm="auto">
                                <v-btn
                                    color="red"
                                    variant="outlined"
                                    size="large"
                                    block
                                    :loading="form.processing"
                                    @click="enviarResposta('rejeitado')"
                                >
                                    Recusar Proposta
                                </v-btn>
                            </v-col>
                            <v-col cols="12" sm="auto">
                                <v-btn
                                    color="green-darken-1"
                                    variant="elevated"
                                    size="large"
                                    block
                                    prepend-icon="mdi-check"
                                    class="font-weight-bold text-white"
                                    :loading="form.processing"
                                    @click="enviarResposta('aprovado')"
                                >
                                    Aprovar Orçamento
                                </v-btn>
                            </v-col>
                        </v-row>
                    </div>

                    <div v-if="orcamento.status === 'aprovado'" class="pa-8 bg-green-lighten-5 text-center border-t">
                        <v-icon icon="mdi-check-circle" color="green" size="48" class="mb-2"></v-icon>
                        <div class="text-h6 text-green-darken-3 font-weight-medium">
                            Este orçamento foi aprovado em {{ formatDate(orcamento.data_resposta) }}.
                        </div>
                        <div class="text-body-2 text-green-darken-2 mt-1">
                            Entraremos em contato em breve para iniciar o serviço.
                        </div>
                    </div>

                </v-card>

                <div class="text-center mt-8 text-caption text-grey">
                    &copy; {{ new Date().getFullYear() }} {{ empresa ? empresa.nome : "OrçamentoSYS" }}. Todos os direitos reservados.
                </div>

            </v-container>
        </v-main>
    </v-app>
</template>
