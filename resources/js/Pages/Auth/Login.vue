<script setup>
import { useForm, Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    email: '',
    password: ''
});

// Controle de visibilidade da senha
const visible = ref(false);

const submit = () => {
    form.post('/login');
};
</script>

<template>
    <Head title="Login" />

    <v-app>
        <v-main class="bg-grey-lighten-4">
            <v-container class="fill-height" fluid>
                <v-row align="center" justify="center">
                    <v-col cols="12" sm="8" md="5" lg="4">

                        <v-card class="elevation-4 rounded-lg px-4 py-6">

                            <div class="text-center mb-6">
                                <h1 class="text-h4 font-weight-bold text-grey-darken-3">
                                    Orçamento<span class="text-blue-darken-1">Sys</span>
                                </h1>
                                <p class="text-body-2 text-grey mt-2">Entre para acessar o sistema</p>
                            </div>

                            <v-form @submit.prevent="submit">
                                <v-card-text>
                                    <div class="mb-2">
                                        <div class="text-subtitle-2 font-weight-bold mb-1 text-grey-darken-2">E-mail</div>
                                        <v-text-field
                                            v-model="form.email"
                                            placeholder="admin@admin.com"
                                            prepend-inner-icon="mdi-email-outline"
                                            variant="outlined"
                                            density="comfortable"
                                            color="blue"
                                            :error-messages="form.errors.email"
                                            autofocus
                                        ></v-text-field>
                                    </div>

                                    <div class="mb-4">
                                        <div class="text-subtitle-2 font-weight-bold mb-1 text-grey-darken-2">Senha</div>
                                        <v-text-field
                                            v-model="form.password"
                                            placeholder="********"
                                            :type="visible ? 'text' : 'password'"
                                            prepend-inner-icon="mdi-lock-outline"
                                            :append-inner-icon="visible ? 'mdi-eye-off' : 'mdi-eye'"
                                            @click:append-inner="visible = !visible"
                                            variant="outlined"
                                            density="comfortable"
                                            color="blue"
                                        ></v-text-field>
                                    </div>

                                    <v-btn
                                        type="submit"
                                        block
                                        color="blue-darken-1"
                                        size="large"
                                        class="font-weight-bold text-white mt-2"
                                        :loading="form.processing"
                                        elevation="2"
                                    >
                                        {{ form.processing ? 'Entrando...' : 'Entrar' }}
                                    </v-btn>
                                </v-card-text>
                            </v-form>

                            <div class="text-center mt-4 text-caption text-grey-lighten-1">
                                &copy; {{ new Date().getFullYear() }} Sistema de Orçamentos
                            </div>

                        </v-card>
                    </v-col>
                </v-row>
            </v-container>
        </v-main>
    </v-app>
</template>
