<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';

const form = useForm({
    nome: '',
    documento: '',
    tipo_pessoa: 'juridica', // Valor padrão

    // Lista inicial com 1 endereço vazio para o usuário preencher
    enderecos: [
        { cep: '', logradouro: '', numero: '', bairro: '', cidade: '', uf: '' }
    ],

    // Lista inicial com 1 contato vazio
    contatos: [
        { departamento: '', email: '', celular: '' }
    ]
});

// === FUNÇÕES DE ENDEREÇO ===
function adicionarEndereco() {
    form.enderecos.push({ cep: '', logradouro: '', numero: '', bairro: '', cidade: '', uf: '' });
}

function removerEndereco(index) {
    // Impede remover se só tiver 1 sobrando
    if (form.enderecos.length > 1) {
        form.enderecos.splice(index, 1);
    } else {
        alert('É necessário ter pelo menos um endereço.');
    }
}

// === FUNÇÕES DE CONTATO ===
function adicionarContato() {
    form.contatos.push({ departamento: '', email: '', celular: '' });
}

function removerContato(index) {
    form.contatos.splice(index, 1);
}

// === ENVIO ===
function salvar() {
    form.post('/clientes', {
        onSuccess: () => {
            // O redirecionamento acontece no controller,
            // mas aqui poderíamos limpar o form se quiséssemos continuar na página
        }
    });
}
</script>

<template>
    <AppLayout>
        <template #header>
            Novo Cliente
        </template>

        <div class="max-w-7xl mx-auto py-6">
            <form @submit.prevent="salvar">

                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-bold text-gray-700">Dados Básicos</h2>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="md:col-span-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Tipo</label>
                            <select v-model="form.tipo_pessoa" class="w-full border rounded p-2">
                                <option value="fisica">Pessoa Física</option>
                                <option value="juridica">Pessoa Jurídica</option>
                            </select>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Documento (CPF/CNPJ)</label>
                            <input v-model="form.documento" type="text" class="w-full border rounded p-2" placeholder="Digite apenas números">
                            <div v-if="form.errors.documento" class="text-red-500 text-xs mt-1">{{ form.errors.documento }}</div>
                        </div>

                        <div class="md:col-span-1">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nome / Razão Social</label>
                            <input v-model="form.nome" type="text" class="w-full border rounded p-2" required>
                            <div v-if="form.errors.nome" class="text-red-500 text-xs mt-1">{{ form.errors.nome }}</div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-bold text-gray-700">Endereços</h2>
                        <button type="button" @click="adicionarEndereco" class="text-sm bg-blue-50 text-blue-600 px-3 py-1 rounded hover:bg-blue-100 font-bold">
                            + Adicionar Outro Endereço
                        </button>
                    </div>

                    <div v-if="form.errors.enderecos" class="text-red-500 text-sm mb-2">{{ form.errors.enderecos }}</div>

                    <div v-for="(endereco, index) in form.enderecos" :key="index" class="mb-6 p-4 bg-gray-50 border rounded-lg relative">

                        <button type="button" @click="removerEndereco(index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500" title="Remover este endereço">
                            ✖
                        </button>

                        <h3 class="text-xs font-bold text-gray-400 uppercase mb-2">Endereço #{{ index + 1 }}</h3>

                        <div class="grid grid-cols-1 md:grid-cols-6 gap-4">
                            <div class="md:col-span-1">
                                <input v-model="endereco.cep" placeholder="CEP" class="w-full border p-2 rounded text-sm">
                            </div>
                            <div class="md:col-span-3">
                                <input v-model="endereco.logradouro" placeholder="Logradouro (Rua, Av...)" class="w-full border p-2 rounded text-sm" required>
                                <div v-if="form.errors[`enderecos.${index}.logradouro`]" class="text-red-500 text-xs">{{ form.errors[`enderecos.${index}.logradouro`] }}</div>
                            </div>
                            <div class="md:col-span-1">
                                <input v-model="endereco.numero" placeholder="Número" class="w-full border p-2 rounded text-sm">
                            </div>
                            <div class="md:col-span-1">
                                <input v-model="endereco.bairro" placeholder="Bairro" class="w-full border p-2 rounded text-sm">
                            </div>
                            <div class="md:col-span-4">
                                <input v-model="endereco.cidade" placeholder="Cidade" class="w-full border p-2 rounded text-sm" required>
                            </div>
                            <div class="md:col-span-2">
                                <input v-model="endereco.uf" placeholder="UF" maxlength="2" class="w-full border p-2 rounded text-sm uppercase" required>
                                <div v-if="form.errors[`enderecos.${index}.uf`]" class="text-red-500 text-xs">{{ form.errors[`enderecos.${index}.uf`] }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-lg shadow mb-6">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h2 class="text-lg font-bold text-gray-700">Contatos</h2>
                        <button type="button" @click="adicionarContato" class="text-sm bg-green-50 text-green-600 px-3 py-1 rounded hover:bg-green-100 font-bold">
                            + Adicionar Outro Contato
                        </button>
                    </div>

                    <div v-for="(contato, index) in form.contatos" :key="index" class="flex gap-4 mb-2 items-start">
                        <div class="flex-1">
                            <input v-model="contato.departamento" placeholder="Depto (Ex: Financeiro)" class="w-full border p-2 rounded text-sm">
                        </div>
                        <div class="flex-1">
                            <input v-model="contato.email" type="email" placeholder="Email" class="w-full border p-2 rounded text-sm">
                        </div>
                        <div class="flex-1">
                            <input v-model="contato.celular" placeholder="Celular / WhatsApp" class="w-full border p-2 rounded text-sm" required>
                            <div v-if="form.errors[`contatos.${index}.celular`]" class="text-red-500 text-xs">{{ form.errors[`contatos.${index}.celular`] }}</div>
                        </div>
                        <button type="button" @click="removerContato(index)" class="text-red-500 font-bold p-2 hover:bg-red-50 rounded">
                            🗑️
                        </button>
                    </div>

                    <div v-if="form.contatos.length === 0" class="text-gray-500 italic text-sm text-center py-4">
                        Nenhum contato adicionado.
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4">
                    <Link href="/clientes" class="text-gray-600 hover:underline">Cancelar</Link>

                    <button type="submit" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded shadow-lg transition transform hover:scale-105">
                        {{ form.processing ? 'Salvando...' : 'Salvar Cliente' }}
                    </button>
                </div>

            </form>
        </div>
    </AppLayout>
</template>
