<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">Novo Orçamento</h1>

        <form @submit.prevent="salvar">

            <h3 class="font-bold mt-6 mb-2">Itens do Pedido</h3>

            <div>
                <input type="text" name="" placeholder="Local do evento"/>
                <input type="text" name="" placeholder="Data do evento"/>
            </div>

            <div class="mb-4">
                <label class="block font-bold">Cliente</label>
                <select v-model="form.cliente_id" class="border p-2 w-full">
                    <option value="">Selecione um cliente...</option>
                    <option v-for="cliente in clientes" :key="cliente.id" :value="cliente.id">
                        {{ cliente.nome }}
                    </option>
                </select>
            </div>

            <h3 class="font-bold mt-6 mb-2">Itens do Pedido</h3>

            <div v-for="(item, index) in form.itens" :key="index" class="flex gap-2 mb-2">
                <input v-model="item.descricao" placeholder="Descrição/Produto" class="border p-2 flex-1">
                <input v-model="item.quantidade" type="number" placeholder="Qtd" class="border p-2 w-20">
                <input v-model="item.preco" type="number" step="0.01" placeholder="Preço" class="border p-2 w-24">

                <button type="button" @click="removerItem(index)" class="text-red-500 font-bold">X</button>
            </div>

            <button type="button" @click="adicionarItem" class="bg-gray-200 px-4 py-2 rounded mt-2">
                + Adicionar Item
            </button>

            <div class="mt-6 border-t pt-4">
                <h2 class="text-xl font-bold text-right">Total: R$ {{ totalCalculado }}</h2>
            </div>

            <div class="mt-4">
                <input
                    type="checkbox"
                    id="exibir_unitario"
                    v-model="form.exibir_unitario"
                    class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                >
                <label for="exibir_unitario" class="text-sm font-medium text-gray-700 cursor-pointer">
                    Cliente pode ver orçamento unitário?
                </label>
            </div>

            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded mt-4 w-full" :disabled="form.processing">
                Finalizar Orçamento
            </button>
        </form>
    </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    clientes: Array,
    produtos: Array
});

const form = useForm({
    cliente_id: '',
    exibir_unitario: false,
    itens: [
        { descricao: '', quantidade: 1, preco: 0 } // Começa com um item vazio
    ]
});

// Funções para manipular a lista de itens dinamicamente
function adicionarItem() {
    form.itens.push({ descricao: '', quantidade: 1, preco: 0 });
}

function removerItem(index) {
    form.itens.splice(index, 1);
}

// Cálculo automático do total na tela
const totalCalculado = computed(() => {
    return form.itens.reduce((acc, item) => acc + (item.quantidade * item.preco), 0).toFixed(2);
});

function salvar() {
    form.post('/orcamentos');
}
</script>
