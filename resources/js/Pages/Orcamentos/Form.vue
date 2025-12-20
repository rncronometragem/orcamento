<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { computed, watch } from 'vue';

const props = defineProps({
    orcamento: Object,
    clientes: Array,
    produtos: Array
});

const isEditing = computed(() => !!props.orcamento);

const form = useForm({
    _method: isEditing.value ? 'put' : 'post',
    cliente_id: props.orcamento?.cliente_id || '',
    status: props.orcamento?.status || 'pendente',
    observacoes: props.orcamento?.observacoes || '',
    // ADICIONADO: Inicializa com o valor do banco ou false se for novo
    pode_ver_unitarios: props.orcamento?.pode_ver_unitarios ?? false,
    itens: props.orcamento?.itens || [
        { descricao: '', quantidade: 1, preco_unitario: 0, produto_temp: '' }
    ]
});

// === LÓGICA DE ITENS ===

const addItem = () => {
    form.itens.push({ descricao: '', quantidade: 1, preco_unitario: 0, produto_temp: '' });
};

const removeItem = (index) => {
    if (form.itens.length > 1) {
        form.itens.splice(index, 1);
    }
};

const aoSelecionarProduto = (index, event) => {
    const produtoId = event.target.value;
    if (!produtoId) return;

    console.log(produtoId);

    const prod = props.produtos.find(p => p.id === produtoId);
    console.log(prod);
    if (prod) {
        form.itens[index].descricao = prod.nome;
        form.itens[index].preco_unitario = parseFloat(prod.valor);
        form.itens[index].produto_temp = '';
    }
};

// === CÁLCULOS ===

const totalGeral = computed(() => {
    return form.itens.reduce((acc, item) => {
        return acc + (item.quantidade * item.preco_unitario);
    }, 0);
});

const formatarMoeda = (val) => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val);

const submit = () => {
    if (isEditing.value) {
        form.post(`/orcamentos/${props.orcamento.id}`);
    } else {
        form.post('/orcamentos');
    }
};
</script>

<template>
    <AppLayout>
        <template #header>{{ isEditing ? `Editar Orçamento #${orcamento.id}` : 'Novo Orçamento' }}</template>

        <form @submit.prevent="submit" class="max-w-6xl mx-auto py-6 space-y-6">

            <div class="bg-white p-6 rounded-lg shadow grid grid-cols-1 md:grid-cols-3 gap-6">

                <div class="md:col-span-2">
                    <label class="block font-bold text-gray-700 mb-2">Cliente</label>
                    <select v-model="form.cliente_id" class="w-full border rounded p-2" required>
                        <option value="" disabled>Selecione um cliente...</option>
                        <option v-for="cli in clientes" :key="cli.id" :value="cli.id">{{ cli.nome }}</option>
                    </select>
                    <div v-if="form.errors.cliente_id" class="text-red-500 text-sm">{{ form.errors.cliente_id }}</div>
                </div>

                <div>
                    <label class="block font-bold text-gray-700 mb-2">Status</label>
                    <select v-model="form.status" class="w-full border rounded p-2 bg-gray-50">
                        <option value="pendente">Pendente</option>
                        <option value="aprovado">Aprovado</option>
                        <option value="rejeitado">Rejeitado</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>

                <div class="md:col-span-3">
                    <label class="block font-bold text-gray-700 mb-2">Observações</label>
                    <textarea v-model="form.observacoes" class="w-full border rounded p-2" rows="2" placeholder="Detalhes de pagamento, validade, etc..."></textarea>
                </div>
            </div>

            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex justify-between items-center mb-4 border-b pb-2">
                    <h3 class="text-lg font-bold text-gray-700">Itens do Orçamento</h3>
                    <button type="button" @click="addItem" class="bg-blue-100 text-blue-700 px-3 py-1 rounded font-bold hover:bg-blue-200">+ Adicionar Item</button>
                </div>

                <div v-if="form.errors.itens" class="text-red-500 mb-4">{{ form.errors.itens }}</div>

                <div class="hidden md:grid grid-cols-12 gap-4 text-sm font-bold text-gray-500 mb-2 px-2">
                    <div class="col-span-4">Descrição / Produto</div>
                    <div class="col-span-2 text-center">Qtd</div>
                    <div class="col-span-2 text-right">Preço Un.</div>
                    <div class="col-span-3 text-right">Subtotal</div>
                    <div class="col-span-1"></div>
                </div>

                <div v-for="(item, index) in form.itens" :key="index" class="grid grid-cols-1 md:grid-cols-12 gap-4 items-center mb-4 border-b pb-4 md:border-0 md:pb-0 bg-gray-50 md:bg-white p-4 md:p-0 rounded">

                    <div class="col-span-4 flex flex-col gap-1">
                        <div class="md:hidden font-bold text-xs text-gray-400">PRODUTO/SERVIÇO</div>
                        <select v-model="item.produto_temp" @change="aoSelecionarProduto(index, $event)" class="text-xs border rounded p-1 text-gray-500 mb-1">
                            <option value="">Copiar de um produto...</option>
                            <option v-for="p in produtos" :key="p.id" :value="p.id">{{ p.nome }}</option>
                        </select>
                        <input v-model="item.descricao" type="text" placeholder="Descrição do item" class="w-full border rounded p-2" required>
                        <div v-if="form.errors[`itens.${index}.descricao`]" class="text-red-500 text-xs">{{ form.errors[`itens.${index}.descricao`] }}</div>
                    </div>

                    <div class="col-span-2">
                        <div class="md:hidden font-bold text-xs text-gray-400">QTD</div>
                        <input v-model="item.quantidade" type="number" min="1" class="w-full border rounded p-2 text-center" required>
                    </div>

                    <div class="col-span-2">
                        <div class="md:hidden font-bold text-xs text-gray-400">PREÇO</div>
                        <input v-model="item.preco_unitario" type="number" step="0.01" min="0" class="w-full border rounded p-2 text-right" required>
                    </div>

                    <div class="col-span-3 text-right font-bold text-gray-700">
                        <div class="md:hidden font-bold text-xs text-gray-400 text-left">SUBTOTAL</div>
                        {{ formatarMoeda(item.quantidade * item.preco_unitario) }}
                    </div>

                    <div class="col-span-1 text-center">
                        <button type="button" @click="removeItem(index)" class="text-red-500 hover:bg-red-50 p-2 rounded">🗑️</button>
                    </div>
                </div>

                <div class="flex flex-col md:flex-row justify-between items-end md:items-center mt-6 pt-4 border-t border-gray-200">

                    <div class="flex items-center gap-2 mb-4 md:mb-0">
                        <input
                            id="pode_ver_unitarios"
                            type="checkbox"
                            v-model="form.pode_ver_unitarios"
                            class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        >
                        <label for="pode_ver_unitarios" class="text-sm font-medium text-gray-700 cursor-pointer select-none">
                            Cliente pode ver orçamento unitário?
                        </label>
                    </div>

                    <div class="text-right">
                        <span class="text-gray-500 text-lg mr-4">Total Geral:</span>
                        <span class="text-3xl font-bold text-blue-700">{{ formatarMoeda(totalGeral) }}</span>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-4">
                <Link href="/orcamentos" class="bg-gray-200 text-gray-700 px-6 py-3 rounded font-bold hover:bg-gray-300">Cancelar</Link>
                <button type="submit" :disabled="form.processing" class="bg-blue-600 text-white px-6 py-3 rounded font-bold hover:bg-blue-700 shadow-lg">
                    {{ form.processing ? 'Salvando...' : (isEditing ? 'Atualizar Orçamento' : 'Finalizar Orçamento') }}
                </button>
            </div>

        </form>
    </AppLayout>
</template>
