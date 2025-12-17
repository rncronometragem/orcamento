<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';

// Recebe o objeto cliente completo do Controller
const props = defineProps({
    cliente: Object
});

// Inicializa o formulário com os dados existentes
const form = useForm({
    _method: 'PUT', // Truque do Laravel para update via form
    nome: props.cliente.nome,
    documento: props.cliente.documento,
    tipo_pessoa: props.cliente.tipo_pessoa,

    // Se não tiver endereços (erro de dados antigos), inicia array vazio
    enderecos: props.cliente.enderecos.length ? props.cliente.enderecos : [
        { cep: '', logradouro: '', numero: '', bairro: '', cidade: '', uf: '' }
    ],

    contatos: props.cliente.contatos.length ? props.cliente.contatos : [
        { departamento: '', email: '', celular: '' }
    ]
});

// Funções de manipulação (iguais ao Novo.vue)
function adicionarEndereco() {
    form.enderecos.push({ cep: '', logradouro: '', numero: '', bairro: '', cidade: '', uf: '' });
}

function removerEndereco(index) {
    if (form.enderecos.length > 1) {
        form.enderecos.splice(index, 1);
    } else {
        alert('É necessário ter pelo menos um endereço.');
    }
}

function adicionarContato() {
    form.contatos.push({ departamento: '', email: '', celular: '' });
}

function removerContato(index) {
    form.contatos.splice(index, 1);
}

// Envio para a rota UPDATE
function atualizar() {
    form.post(`/clientes/${props.cliente.id}`);
}
</script>

<template>
    <AppLayout>
        <template #header>
            Editar Cliente: <span class="text-blue-600">{{ form.nome }}</span>
        </template>

        <div class="max-w-7xl mx-auto py-6">
            <form @submit.prevent="atualizar">

                <div class="bg-white p-6 rounded-lg shadow mb-6 border-l-4 border-blue-500">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-bold text-gray-700">Dados Básicos</h2>
                        <span class="text-xs text-gray-400">ID: {{ cliente.id }}</span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tipo</label>
                            <select v-model="form.tipo_pessoa" class="w-full border rounded p-2">
                                <option value="fisica">Pessoa Física</option>
                                <option value="juridica">Pessoa Jurídica</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Documento</label>
                            <input v-model="form.documento" type="text" class="w-full border rounded p-2">
                        </div>
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nome / Razão Social</label>
                            <input v-model="form.nome" type="text" class="w-full border rounded p-2">
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-bold text-gray-700">Endereços</h2>
                        <button type="button" @click="adicionarEndereco" class="text-sm bg-blue-50 text-blue-600 px-3 py-1 rounded hover:bg-blue-100 font-bold">
                            + Adicionar
                        </button>
                    </div>

                    <div v-for="(endereco, index) in form.enderecos" :key="index" class="mb-6 p-4 bg-gray-50 border rounded-lg relative">
                        <button type="button" @click="removerEndereco(index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500">✖</button>

                        <input type="hidden" v-model="endereco.id">

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                            <div class="md:col-span-1">
                                <input v-model="endereco.cep" placeholder="CEP" class="w-full border p-2 rounded text-sm">
                            </div>
                            <div class="md:col-span-3">
                                <input v-model="endereco.logradouro" placeholder="Logradouro" class="w-full border p-2 rounded text-sm">
                            </div>
                            <div class="md:col-span-1">
                                <input v-model="endereco.numero" placeholder="Nº" class="w-full border p-2 rounded text-sm">
                            </div>
                            <div class="md:col-span-1">
                                <input v-model="endereco.bairro" placeholder="Bairro" class="w-full border p-2 rounded text-sm">
                            </div>
                            <div class="md:col-span-4">
                                <input v-model="endereco.cidade" placeholder="Cidade" class="w-full border p-2 rounded text-sm">
                            </div>
                            <div class="md:col-span-2">
                                <input v-model="endereco.uf" placeholder="UF" maxlength="2" class="w-full border p-2 rounded text-sm uppercase">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-bold text-gray-700">Contatos</h2>
                        <button type="button" @click="adicionarContato" class="text-sm bg-green-50 text-green-600 px-3 py-1 rounded hover:bg-green-100 font-bold">
                            + Adicionar
                        </button>
                    </div>

                    <div v-for="(contato, index) in form.contatos" :key="index" class="flex gap-4 mb-2 items-start">
                        <input type="hidden" v-model="contato.id">

                        <div class="flex-1">
                            <input v-model="contato.departamento" placeholder="Departamento" class="w-full border p-2 rounded text-sm">
                        </div>
                        <div class="flex-1">
                            <input v-model="contato.email" type="email" placeholder="Email" class="w-full border p-2 rounded text-sm">
                        </div>
                        <div class="flex-1">
                            <input v-model="contato.celular" placeholder="Celular" class="w-full border p-2 rounded text-sm">
                        </div>
                        <button type="button" @click="removerContato(index)" class="text-red-500 font-bold p-2">🗑️</button>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <Link href="/clientes" class="text-gray-600 hover:underline">Cancelar</Link>
                    <button type="submit" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded shadow-lg">
                        {{ form.processing ? 'Salvando...' : 'Salvar Alterações' }}
                    </button>
                </div>

            </form>
        </div>
    </AppLayout>
</template>
