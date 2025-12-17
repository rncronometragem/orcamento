<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({ produto: Object });

const isEditing = computed(() => !!props.produto);
const titulo = computed(() => isEditing.value ? 'Editar Produto' : 'Novo Produto');

const form = useForm({
    _method: isEditing.value ? 'put' : 'post',
    nome: props.produto?.nome || '',
    valor: props.produto?.valor || '', // Agora mapeia para 'valor'
    // usuario_log não precisa estar aqui, o backend preenche sozinho
});

const submit = () => {
    if (isEditing.value) {
        form.post(`/produtos/${props.produto.id}`);
    } else {
        form.post('/produtos');
    }
};
</script>

<template>
    <AppLayout>
        <template #header>{{ titulo }}</template>

        <div class="max-w-3xl mx-auto py-6">
            <div class="bg-white p-8 rounded-lg shadow">

                <form @submit.prevent="submit">

                    <div class="mb-4">
                        <label class="block text-gray-700 font-bold mb-2">Nome do Produto</label>
                        <input v-model="form.nome" type="text" class="w-full border rounded p-2" placeholder="Ex: Câmera de Segurança HD" maxlength="200" required>
                        <div v-if="form.errors.nome" class="text-red-500 text-sm mt-1">{{ form.errors.nome }}</div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-bold mb-2">Valor Unitário (R$)</label>
                        <input v-model="form.valor" type="number" step="0.01" class="w-full border rounded p-2" placeholder="0.00">
                        <div v-if="form.errors.valor" class="text-red-500 text-sm mt-1">{{ form.errors.valor }}</div>
                        <p class="text-xs text-gray-400 mt-1">Deixe em branco se não tiver valor definido.</p>
                    </div>

                    <div class="flex items-center justify-end gap-3">
                        <Link href="/produtos" class="text-gray-600 hover:underline">Cancelar</Link>

                        <button type="submit" :disabled="form.processing"
                                class="bg-blue-600 text-white font-bold py-2 px-6 rounded hover:bg-blue-700 transition">
                            {{ form.processing ? 'Salvando...' : 'Salvar Produto' }}
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </AppLayout>
</template>
