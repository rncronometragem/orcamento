<script setup>
import { useForm, Head } from '@inertiajs/vue3';

const form = useForm({
    email: '',
    password: ''
});

const submit = () => {
    form.post('/login');
};
</script>

<template>
    <Head title="Login" />

    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">

            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Orçamentoteste<span class="text-blue-600">Sys</span></h1>
                <p class="text-gray-500 text-sm mt-2">Entre para acessar o sistema</p>
            </div>

            <form @submit.prevent="submit">

                <div class="mb-4">
                    <label class="block text-gray-700 font-bold mb-2" for="email">E-mail</label>
                    <input
                        v-model="form.email"
                        id="email"
                        type="email"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                        placeholder="admin@admin.com"
                        required
                        autofocus
                    >
                    <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                        {{ form.errors.email }}
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 font-bold mb-2" for="password">Senha</label>
                    <input
                        v-model="form.password"
                        id="password"
                        type="password"
                        class="w-full border rounded px-3 py-2 focus:outline-none focus:border-blue-500"
                        placeholder="********"
                        required
                    >
                </div>

                <button
                    type="submit"
                    :disabled="form.processing"
                    class="w-full bg-blue-600 text-white font-bold py-3 rounded hover:bg-blue-700 transition duration-200"
                >
                    {{ form.processing ? 'Entrando...' : 'Entrar' }}
                </button>

            </form>

            <div class="mt-6 text-center text-xs text-gray-400">
                &copy; {{ new Date().getFullYear() }} Sistema de Orçamentos
            </div>

        </div>
    </div>
</template>
