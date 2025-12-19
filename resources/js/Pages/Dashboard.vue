<template>
    <AppLayout>
        <template #header>
        </template>
        <div class="p-6 bg-gray-100 min-h-screen">

            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Painel de Controle</h1>
                    <p class="text-gray-500">Bem-vindo ao sistema de orçamentos.</p>
                </div>
                <div class="text-sm text-gray-400">
                    {{ new Date().toLocaleDateString() }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-blue-500 flex items-center">
                    <div class="p-3 rounded-full bg-blue-100 text-blue-500 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Total de Clientes</p>
                        <p class="text-2xl font-bold text-gray-800">{{ kpis.clientes }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-yellow-500 flex items-center">
                    <div class="p-3 rounded-full bg-yellow-100 text-yellow-500 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Orçamentos Abertos</p>
                        <p class="text-2xl font-bold text-gray-800">{{ kpis.orcamentos_abertos }}</p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow border-l-4 border-green-500 flex items-center">
                    <div class="p-3 rounded-full bg-green-100 text-green-500 mr-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div>
                        <p class="text-gray-500 text-sm">Faturamento (Mês)</p>
                        <p class="text-2xl font-bold text-gray-800">{{ kpis.faturamento }}</p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <div class="lg:col-span-2 space-y-8">

                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="font-bold text-gray-700 mb-4">Desempenho Semestral</h3>
                        <div class="h-64">
                            <Bar :data="chartData" :options="chartOptions" />
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow overflow-hidden">
                        <div class="p-4 border-b">
                            <h3 class="font-bold text-gray-700">Últimos Orçamentos</h3>
                        </div>
                        <table class="w-full text-left">
                            <thead>
                            <tr class="bg-gray-50 text-gray-600 text-sm uppercase">
                                <th class="p-4">Cliente</th>
                                <th class="p-4">Data</th>
                                <th class="p-4">Valor</th>
                                <th class="p-4">Status</th>
                                <th class="p-4"></th>
                            </tr>
                            </thead>
                            <tbody class="divide-y">
                            <tr v-for="orcamento in recentes" :key="orcamento.id" class="hover:bg-gray-50">
                                <td class="p-4 font-medium">{{ orcamento.cliente }}</td>
                                <td class="p-4 text-gray-500">{{ orcamento.data }}</td>
                                <td class="p-4 text-green-600 font-bold">{{ orcamento.valor }}</td>
                                <td class="p-4">
                  <span :class="{
                    'bg-yellow-100 text-yellow-800': orcamento.status === 'Pendente',
                    'bg-green-100 text-green-800': orcamento.status === 'Aprovado',
                    'bg-red-100 text-red-800': orcamento.status === 'Rejeitado'
                  }" class="px-2 py-1 rounded text-xs font-bold">
                    {{ orcamento.status }}
                  </span>
                                </td>
                                <td class="p-4 text-right">
                                    <Link :href="`/orcamentos/${orcamento.id}`" class="text-blue-600 hover:underline">Ver</Link>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="space-y-6">

                    <div class="bg-white p-6 rounded-lg shadow">
                        <h3 class="font-bold text-gray-700 mb-4">Acesso Rápido</h3>
                        <div class="grid grid-cols-2 gap-4">

                            <Link href="/orcamentos/novo" class="flex flex-col items-center justify-center p-4 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition">
                                <span class="text-2xl mb-2">+</span>
                                <span class="font-bold text-sm">Novo Orçamento</span>
                            </Link>

                            <Link href="/clientes/novo" class="flex flex-col items-center justify-center p-4 bg-green-50 text-green-600 rounded-lg hover:bg-green-100 transition">
                                <span class="text-2xl mb-2">👤</span>
                                <span class="font-bold text-sm">Novo Cliente</span>
                            </Link>

                            <Link href="/produtos" class="flex flex-col items-center justify-center p-4 bg-purple-50 text-purple-600 rounded-lg hover:bg-purple-100 transition">
                                <span class="text-2xl mb-2">📦</span>
                                <span class="font-bold text-sm">Produtos</span>
                            </Link>

                            <Link href="/relatorios" class="flex flex-col items-center justify-center p-4 bg-orange-50 text-orange-600 rounded-lg hover:bg-orange-100 transition">
                                <span class="text-2xl mb-2">📄</span>
                                <span class="font-bold text-sm">Relatórios</span>
                            </Link>

                        </div>
                    </div>

                    <div class="bg-indigo-600 p-6 rounded-lg shadow text-white">
                        <h4 class="font-bold text-lg mb-2">Precisa de ajuda?</h4>
                        <p class="text-sm opacity-90 mb-4">Veja os tutoriais de como usar o novo sistema Laravel.</p>
                        <button class="bg-white text-indigo-600 px-4 py-2 rounded text-sm font-bold">Ver Tutoriais</button>
                    </div>

                </div>

            </div>
        </div>
    </AppLayout>
</template>

<script setup>
import {Link} from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale
} from 'chart.js';
import {Bar} from 'vue-chartjs';

// Registra os componentes do Chart.js
ChartJS.register(CategoryScale, LinearScale, BarElement, Title, Tooltip, Legend);

const props = defineProps({
    kpis: Object,
    grafico: Object,
    recentes: Array
});

// Configuração dos Dados do Gráfico
const chartData = {
    labels: props.grafico.labels, // Ex: ['2023-10', '2023-11']
    datasets: [
        {
            label: 'Vendas Aprovadas (R$)',
            backgroundColor: '#3B82F6', // Azul Tailwind
            data: props.grafico.dados
        }
    ]
}

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false
}
</script>
