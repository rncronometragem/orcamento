<template>
    <AppLayout>
        <template #header></template>

        <div class="d-flex justify-space-between align-center mb-8">
            <div>
                <h1 class="text-h4 font-weight-bold text-grey-darken-3">Painel de Controle</h1>
                <p class="text-subtitle-1 text-grey-darken-1">Bem-vindo ao sistema de orçamentos.</p>
            </div>
            <div class="text-caption text-grey-darken-1">
                {{ new Date().toLocaleDateString() }}
            </div>
        </div>

        <v-dialog v-model="isActive" max-width="800">
            <v-card v-if="novoOrcamento">
                <v-toolbar color="primary" title="Orçamento">
                    <v-spacer></v-spacer>

                    <v-btn icon="mdi-close" variant="text" @click="isActive = false"></v-btn>
                </v-toolbar>
                <v-card-text>
                    <Form
                        :modal="true"
                        :data="dataSelecionada"
                        :clientes="clientes"
                        :produtos="produtos"
                    />
                </v-card-text>
            </v-card>
            <v-card v-else-if="listaOrcamentos">
                <v-toolbar color="primary" title="Orçamento">
                    <v-spacer></v-spacer>

                    <v-btn icon="mdi-close" variant="text" @click="isActive = false"></v-btn>
                </v-toolbar>
                <v-card-text>
                    <Lista
                        :orcamentos="orcamentos"
                        :filters="{
                            'search': null
                        }"
                    />
                </v-card-text>
            </v-card>
        </v-dialog>

        <v-row>
            <v-col cols="12" :lg="visualizacaoSelecionada === 0 ? 8 : 12">

                <v-card class="pa-6 mb-8" elevation="2" rounded="lg">
                    <div class="d-flex align-center mb-4 gap-4">
                        <span class="text-body-1 font-weight-bold">Formato de visão:</span>
                        <div style="width: 200px">
                            <v-select
                                v-model="visualizacaoSelecionada"
                                :items="[
                                  { id: 0, text: 'Mês' },
                                  { id: 1, text: 'Semestre' },
                                  { id: 2, text: 'Ano' }
                                 ]"
                                item-title="text"
                                item-value="id"
                                density="compact"
                                variant="outlined"
                                hide-details
                            ></v-select>
                        </div>
                    </div>

                    <div class="w-100">
                        <Calendar
                            ref="calendar"
                            :initial-page="calendarPage"
                            expanded
                            borderless
                            transparent
                            locale="pt-BR"
                            :columns="visualizacaoCalendario[visualizacaoSelecionada].columns"
                            :rows="visualizacaoCalendario[visualizacaoSelecionada].rows"
                            :attributes="attributes"
                            @dayclick="onDayClick"
                        />
                    </div>
                    <v-divider class="my-4"></v-divider>

                    <div class="d-flex align-center px-2">

                        <v-row>
                            <v-col cols="2">
                                <span class="text-caption text-medium-emphasis mr-3 font-weight-bold">LEGENDA:</span>
                            </v-col>
                            <v-col cols="2">
                                <div class="d-flex align-center">
                                    <div class="rounded-circle bg-blue mr-2" style="width: 10px; height: 10px;"></div>
                                    <span class="text-caption text-high-emphasis">Dia Atual</span>
                                </div>
                            </v-col>
                            <v-col>
                                <div class="d-flex align-center">
                                    <div class="rounded-circle bg-green mr-2" style="width: 10px; height: 10px;"></div>
                                    <span class="text-caption text-high-emphasis">Eventos com orçamentos aprovados</span>
                                </div>
                            </v-col>
                            <v-col>
                                <div class="d-flex align-center">
                                    <div class="rounded-circle bg-yellow mr-2" style="width: 10px; height: 10px;"></div>
                                    <span class="text-caption text-high-emphasis">Eventos com orçamentos pendentes</span>
                                </div>
                            </v-col>
                        </v-row>


                    </div>
                </v-card>

                <v-card elevation="2" rounded="lg" class="overflow-hidden">
                    <v-card-title class="pa-4 border-b">
                        <h3 class="text-h6 font-weight-bold text-grey-darken-2">Últimos Orçamentos</h3>
                    </v-card-title>

                    <v-table>
                        <thead>
                        <tr class="bg-grey-lighten-4 text-uppercase text-caption text-grey-darken-1">
                            <th class="font-weight-bold">Cliente</th>
                            <th class="font-weight-bold">Data</th>
                            <th class="font-weight-bold">Valor</th>
                            <th class="font-weight-bold">Status</th>
                            <th class="text-right"></th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="orcamento in recentes" :key="orcamento.id" class="hover-bg">
                            <td class="font-weight-medium">{{ orcamento.cliente }}</td>
                            <td class="text-grey-darken-1">{{ orcamento.data }}</td>
                            <td class="text-green-darken-1 font-weight-bold">{{ orcamento.valor }}</td>
                            <td>
                                <v-chip
                                    :color="getStatusColor(orcamento.status)"
                                    size="small"
                                    label
                                    class="font-weight-bold"
                                >
                                    {{ orcamento.status }}
                                </v-chip>
                            </td>
                            <td class="text-right">
                                <Link :href="`/orcamentos/${orcamento.id}`" class="text-decoration-none text-blue">Ver</Link>
                            </td>
                        </tr>
                        </tbody>
                    </v-table>
                </v-card>
            </v-col>

            <v-col cols="12" :lg="visualizacaoSelecionada === 0 ? 4 : 12">
                <div class="d-flex flex-column gap-6">

                    <v-card class="pa-6" elevation="2" rounded="lg">
                        <h3 class="text-h6 font-weight-bold text-grey-darken-2 mb-4">Acesso Rápido</h3>

                        <v-row dense>
                            <v-col cols="6">
                                <Link href="/orcamentos/novo" class="text-decoration-none">
                                    <v-card color="blue-lighten-5" class="d-flex flex-column align-center justify-center pa-4 h-100 hover-card" flat>
                                        <span class="text-h5 mb-2 text-blue">➕</span>
                                        <span class="text-caption font-weight-bold text-blue">Novo Orçamento</span>
                                    </v-card>
                                </Link>
                            </v-col>

                            <v-col cols="6">
                                <Link href="/clientes/novo" class="text-decoration-none">
                                    <v-card color="green-lighten-5" class="d-flex flex-column align-center justify-center pa-4 h-100 hover-card" flat>
                                        <span class="text-h5 mb-2">👤</span>
                                        <span class="text-caption font-weight-bold text-green-darken-1">Novo Cliente</span>
                                    </v-card>
                                </Link>
                            </v-col>

                            <v-col cols="6">
                                <Link href="/produtos" class="text-decoration-none">
                                    <v-card color="purple-lighten-5" class="d-flex flex-column align-center justify-center pa-4 h-100 hover-card" flat>
                                        <span class="text-h5 mb-2">📦</span>
                                        <span class="text-caption font-weight-bold text-purple-darken-1">Produtos</span>
                                    </v-card>
                                </Link>
                            </v-col>

                            <v-col cols="6">
                                <Link href="/relatorios" class="text-decoration-none">
                                    <v-card color="orange-lighten-5" class="d-flex flex-column align-center justify-center pa-4 h-100 hover-card" flat>
                                        <span class="text-h5 mb-2">📄</span>
                                        <span class="text-caption font-weight-bold text-orange-darken-3">Relatórios</span>
                                    </v-card>
                                </Link>
                            </v-col>
                        </v-row>
                    </v-card>

                    <v-card color="indigo" class="pa-6 text-white" elevation="2" rounded="lg">
                        <h4 class="text-h6 font-weight-bold mb-2">Precisa de ajuda?</h4>
                        <p class="text-body-2 mb-4 opacity-90">Veja os tutoriais de como usar o novo sistema Laravel.</p>
                        <v-btn color="white" variant="flat" class="text-indigo font-weight-bold text-caption">Ver Tutoriais</v-btn>
                    </v-card>

                </div>
            </v-col>
        </v-row>

        <v-row class="mt-4">
            <v-col cols="12" md="4">
                <v-card class="pa-6 border-s-lg custom-border-blue d-flex align-center" elevation="2" rounded="lg">
                    <v-avatar color="blue-lighten-5" size="56" class="mr-4 text-blue">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </v-avatar>
                    <div>
                        <div class="text-caption text-grey-darken-1">Total de Clientes</div>
                        <div class="text-h5 font-weight-bold text-grey-darken-3">{{ kpis.clientes }}</div>
                    </div>
                </v-card>
            </v-col>

            <v-col cols="12" md="4">
                <v-card class="pa-6 border-s-lg custom-border-yellow d-flex align-center" elevation="2" rounded="lg">
                    <v-avatar color="yellow-lighten-5" size="56" class="mr-4 text-yellow-darken-3">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </v-avatar>
                    <div>
                        <div class="text-caption text-grey-darken-1">Orçamentos Abertos</div>
                        <div class="text-h5 font-weight-bold text-grey-darken-3">{{ kpis.orcamentos_abertos }}</div>
                    </div>
                </v-card>
            </v-col>

            <v-col cols="12" md="4">
                <v-card class="pa-6 border-s-lg custom-border-green d-flex align-center" elevation="2" rounded="lg">
                    <v-avatar color="green-lighten-5" size="56" class="mr-4 text-green">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width: 24px"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </v-avatar>
                    <div>
                        <div class="text-caption text-grey-darken-1">Faturamento (Mês)</div>
                        <div class="text-h5 font-weight-bold text-grey-darken-3">{{ kpis.faturamento }}</div>
                    </div>
                </v-card>
            </v-col>
        </v-row>
    </AppLayout>
</template>

<script setup>
import {ref, watch} from 'vue';
import {Link, router} from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { Calendar } from 'v-calendar';
import 'v-calendar/style.css';
import Form from "./Orcamentos/components/Form.vue";
import Lista from "./Orcamentos/components/Lista.vue";

const props = defineProps({
    kpis: Object,
    grafico: Object,
    recentes: Array,
    clientes: Array,
    produtos: Array,
    orcamentosAprovados: Array,
    orcamentosPendentes: Array,
});

let visualizacaoSelecionada = ref(0);
const dataSelecionada = ref(null);
const isActive = ref(false);
const today = ref('2025-12-31');
const calendar = ref(null);
const novoOrcamento = ref(false);
const listaOrcamentos = ref(false);
const orcamentos = ref([]);

const getStatusColor = (status) => {
    switch(status) {
        case 'Pendente': return 'warning';
        case 'Aprovado': return 'success';
        case 'Rejeitado': return 'error';
        default: return 'grey';
    }
};

const visualizacaoCalendario = {
    0: {rows: 1, columns: 1},
    1: {rows: 2, columns: 3},
    2: {rows: 4, columns: 3}
};

let calendarPage = ref(null);

watch(visualizacaoSelecionada, (novaVisao) => {
    const dataAtual = new Date();
    const anoAtual = dataAtual.getFullYear();
    const mesAtual = dataAtual.getMonth(); // 1 a 12

    if (novaVisao === 2) {
        calendar.value.move(new Date(anoAtual, 0));
    }
    else if (novaVisao === 1) {
        if (mesAtual <= 6) {
            calendar.value.move(new Date(anoAtual, 0));
        } else {
            calendar.value.move(new Date(anoAtual, 6));
        }
    }
    else {
        calendar.value.move(dataAtual);
    }
});

const attributes = [
    {
        key: 'today',
        highlight: {
            color: 'blue',
            fillMode: `light`,
        },
        dates: new Date(),
    },
    {
        key: 'pendentes',
        highlight: { color: 'yellow', fillMode: 'light' },
        dates: props.orcamentosPendentes.map((item) => new Date(item.data_evento)),
    },
    {
        key: 'concluidos',
        highlight: { color: 'green', fillMode: 'solid' },
        dates: props.orcamentosAprovados.map((item) => new Date(item.data_evento)),
    }
];

async function onDayClick (day) {
    const response = await axios.get(`/orcamentos/consulta-dia/${day.id}`);

    if (response.status === 200) {
        listaOrcamentos.value = true;
        orcamentos.value = response.data.orcamentos;
        novoOrcamento.value = false;
    } else {
        novoOrcamento.value = true;
        listaOrcamentos.value = false;
    }
    isActive.value = true;
    dataSelecionada.value = day.id;

};
</script>

<style scoped>
/* Classes auxiliares para recriar o hover suave */
.gap-4 { gap: 16px; }
.gap-6 { gap: 24px; }
.min-vh-100 { min-height: 100vh; }

/* Customização da borda lateral dos KPIs (Vuetify não tem border-left colorido por padrão nas props) */
.custom-border-blue { border-left: 4px solid #2196F3 !important; }
.custom-border-yellow { border-left: 4px solid #FFC107 !important; }
.custom-border-green { border-left: 4px solid #4CAF50 !important; }

/* Efeito Hover nos cards de acesso rápido */
.hover-card {
    transition: background-color 0.2s;
}
.hover-card:hover {
    filter: brightness(0.95);
}
</style>
