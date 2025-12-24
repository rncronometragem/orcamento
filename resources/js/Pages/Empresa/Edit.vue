<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { ref } from 'vue';
import AppLayout from "@/Layouts/AppLayout.vue";
import { route } from 'ziggy-js';

const props = defineProps({
    empresa: Object
});

// Inicializa o form com os campos da migration
const form = useForm({
    nome: props.empresa?.nome || '',
    cnpj: props.empresa?.cnpj || '',
    insc_estadual: props.empresa?.insc_estadual || '',

    // Endereço
    cep: props.empresa?.cep || '',
    rua: props.empresa?.rua || '',
    numero: props.empresa?.numero || '',
    complemento: props.empresa?.complemento || '',
    bairro: props.empresa?.bairro || '',
    cidade: props.empresa?.cidade || '',
    uf: props.empresa?.uf || '',

    // Contato
    email: props.empresa?.email || '',
    telefone: props.empresa?.telefone || '',
    celular: props.empresa?.celular || '',
    site: props.empresa?.site || '',

    // Visual
    cor_barra: props.empresa?.cor_barra || '#0275d8',
    cor_texto: props.empresa?.cor_texto || '#f7f7f7',
    logo: null,

    _method: 'POST' // Usamos POST no form geral, mas a lógica simula update
});

const logoPreview = ref(props.empresa?.logo_url || null);

const handleLogoChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('empresa.update'), {
        forceFormData: true, // Obrigatório para upload de arquivos
        onSuccess: () => alert('Empresa atualizada com sucesso!'),
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Configuração da Empresa" />

        <div class="py-12 bg-gray-100 min-h-screen">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">

                        <h2 class="text-2xl font-bold mb-6 text-gray-800">Dados da Empresa</h2>

                        <form @submit.prevent="submit" class="space-y-8">

                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Identidade Visual (Orçamento)</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                                    <div class="col-span-1 flex flex-col items-center justify-center">
                                        <div class="w-32 h-32 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center overflow-hidden bg-white mb-2">
                                            <img v-if="logoPreview" :src="logoPreview" class="w-full h-full object-contain" />
                                            <span v-else class="text-gray-400 text-sm">Sem Logo</span>
                                        </div>
                                        <input type="file" @change="handleLogoChange" class="text-sm text-gray-500 w-full max-w-xs" />
                                    </div>

                                    <div class="col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Cor da Barra (Título)</label>
                                            <div class="flex items-center gap-2 mt-1">
                                                <input v-model="form.cor_barra" type="color" class="h-10 w-16 p-1 border rounded" />
                                                <span class="text-sm text-gray-500">{{ form.cor_barra }}</span>
                                            </div>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700">Cor do Texto (Título)</label>
                                            <div class="flex items-center gap-2 mt-1">
                                                <input v-model="form.cor_texto" type="color" class="h-10 w-16 p-1 border rounded" />
                                                <span class="text-sm text-gray-500">{{ form.cor_texto }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Dados Cadastrais</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div class="md:col-span-3">
                                        <label class="block text-sm font-medium text-gray-700">Razão Social / Nome</label>
                                        <input v-model="form.nome" type="text" class="mt-1 block w-full rounded-md border shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">CNPJ</label>
                                        <input v-model="form.cnpj" type="text" class="mt-1 block w-full rounded-md border shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Insc. Estadual</label>
                                        <input v-model="form.insc_estadual" type="text" class="mt-1 block w-full rounded-md border shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Website</label>
                                        <input v-model="form.site" type="text" class="mt-1 block w-full rounded-md border shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2" />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Contato</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Email</label>
                                        <input v-model="form.email" type="email" class="mt-1 block w-full rounded-md border shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Telefone Fixo</label>
                                        <input v-model="form.telefone" type="text" class="p-2 mt-1 block w-full rounded-md border shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Celular / WhatsApp</label>
                                        <input v-model="form.celular" type="text" class="mt-1 block w-full rounded-md border shadow-sm focus:border-blue-500 focus:ring-blue-500 p-2" />
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Endereço</h3>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">CEP</label>
                                        <input v-model="form.cep" type="text" class="p-2 mt-1 block w-full rounded-md border shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700">Rua / Logradouro</label>
                                        <input v-model="form.rua" type="text" class="p-2 mt-1 block w-full rounded-md border shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Número</label>
                                        <input v-model="form.numero" type="text" class="p-2 mt-1 block w-full rounded-md border shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Bairro</label>
                                        <input v-model="form.bairro" type="text" class="p-2 mt-1 block w-full rounded-md border shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Complemento</label>
                                        <input v-model="form.complemento" type="text" class="p-2 mt-1 block w-full rounded-md border shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Cidade</label>
                                        <input v-model="form.cidade" type="text" class="p-2 mt-1 block w-full rounded-md border shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">UF</label>
                                        <input v-model="form.uf" type="text" class="p-2 mt-1 block w-full rounded-md border shadow-sm focus:border-blue-500 focus:ring-blue-500" maxLength="2" />
                                    </div>
                                </div>
                            </div>

                            <div class="pt-4 border-t">
                                <button type="submit" :disabled="form.processing" class="w-full md:w-auto px-6 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition">
                                    Salvar Configurações
                                </button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
