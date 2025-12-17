<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    user: Object
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: ''
});

const submit = () => {
    form.post('/configuracoes', {
        preserveScroll: true,
        onSuccess: () => form.reset('password', 'password_confirmation') // Limpa a senha após salvar
    });
};
</script>

<template>
    <AppLayout>
        <template #header>Meu Perfil</template>

        <div class="max-w-4xl mx-auto py-6">

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex items-center gap-3">
                    <div class="bg-blue-100 text-blue-600 p-2 rounded-full">
                        👤
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Dados de Acesso</h3>
                        <p class="text-sm text-gray-500">Gerencie suas informações pessoais e segurança.</p>
                    </div>
                </div>

                <form @submit.prevent="submit" class="p-6">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-2">Nome Completo</label>
                            <input v-model="form.name" type="text" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500" required>
                            <div v-if="form.errors.name" class="text-red-500 text-xs mt-1">{{ form.errors.name }}</div>
                        </div>

                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-2">E-mail de Login</label>
                            <input v-model="form.email" type="email" class="w-full border border-gray-300 rounded-lg p-2.5 focus:ring-blue-500 focus:border-blue-500" required>
                            <div v-if="form.errors.email" class="text-red-500 text-xs mt-1">{{ form.errors.email }}</div>
                        </div>
                    </div>

                    <div class="border-t border-gray-100 my-6"></div>

                    <h4 class="font-bold text-gray-700 mb-4">Alterar Senha (Opcional)</h4>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-2">Nova Senha</label>
                            <input v-model="form.password" type="password" placeholder="Deixe em branco para manter a atual" class="w-full border border-gray-300 rounded-lg p-2.5">
                            <div v-if="form.errors.password" class="text-red-500 text-xs mt-1">{{ form.errors.password }}</div>
                        </div>

                        <div>
                            <label class="block font-bold text-sm text-gray-700 mb-2">Confirmar Nova Senha</label>
                            <input v-model="form.password_confirmation" type="password" placeholder="Repita a nova senha" class="w-full border border-gray-300 rounded-lg p-2.5">
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-4">

                        <div class="text-sm">
                        <span v-if="form.recentlySuccessful" class="text-green-600 font-bold flex items-center gap-1">
                            ✅ Salvo com sucesso!
                        </span>
                        </div>

                        <button type="submit" :disabled="form.processing" class="bg-blue-600 text-white font-bold py-2.5 px-6 rounded-lg hover:bg-blue-700 transition shadow-md disabled:opacity-50">
                            {{ form.processing ? 'Salvando...' : 'Atualizar Perfil' }}
                        </button>
                    </div>

                </form>
            </div>

        </div>
    </AppLayout>
</template>
