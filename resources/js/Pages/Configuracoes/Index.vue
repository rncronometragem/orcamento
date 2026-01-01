<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    user: Object
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    password: '',
    password_confirmation: ''
});

// Controle de visibilidade das senhas
const showPass = ref(false);

const submit = () => {
    form.post('/configuracoes', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('password', 'password_confirmation');
        }
    });
};
</script>

<template>
    <AppLayout>
        <template #header>Meu Perfil</template>

        <v-card class="mx-auto" max-width="900" elevation="2" rounded="lg">

            <v-card-item class="pa-6 bg-grey-lighten-5 border-b">
                <template v-slot:prepend>
                    <v-avatar color="blue-lighten-5" size="48" class="mr-4">
                        <span class="text-h5">👤</span>
                    </v-avatar>
                </template>
                <v-card-title class="font-weight-bold text-grey-darken-3">
                    Dados de Acesso
                </v-card-title>
                <v-card-subtitle>
                    Gerencie suas informações pessoais e segurança.
                </v-card-subtitle>
            </v-card-item>

            <v-form @submit.prevent="submit">
                <v-card-text class="pa-6">

                    <v-row>
                        <v-col cols="12" md="6">
                            <div class="text-subtitle-2 font-weight-bold mb-2 text-grey-darken-2">Nome Completo</div>
                            <v-text-field
                                v-model="form.name"
                                placeholder="Seu nome"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-account-outline"
                                :error-messages="form.errors.name"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <div class="text-subtitle-2 font-weight-bold mb-2 text-grey-darken-2">E-mail de Login</div>
                            <v-text-field
                                v-model="form.email"
                                type="email"
                                placeholder="seu@email.com"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-email-outline"
                                :error-messages="form.errors.email"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                    <v-divider class="my-6"></v-divider>

                    <h4 class="text-h6 font-weight-bold text-grey-darken-2 mb-4">
                        Alterar Senha <span class="text-caption text-grey font-weight-regular">(Opcional)</span>
                    </h4>

                    <v-row>
                        <v-col cols="12" md="6">
                            <div class="text-subtitle-2 font-weight-bold mb-2 text-grey-darken-2">Nova Senha</div>
                            <v-text-field
                                v-model="form.password"
                                :type="showPass ? 'text' : 'password'"
                                placeholder="Deixe em branco para manter a atual"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-lock-outline"
                                :append-inner-icon="showPass ? 'mdi-eye-off' : 'mdi-eye'"
                                @click:append-inner="showPass = !showPass"
                                :error-messages="form.errors.password"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <div class="text-subtitle-2 font-weight-bold mb-2 text-grey-darken-2">Confirmar Nova Senha</div>
                            <v-text-field
                                v-model="form.password_confirmation"
                                :type="showPass ? 'text' : 'password'"
                                placeholder="Repita a nova senha"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-lock-check-outline"
                            ></v-text-field>
                        </v-col>
                    </v-row>

                </v-card-text>

                <v-card-actions class="pa-6 pt-0 d-flex align-center justify-space-between">

                    <div style="min-height: 24px;">
                        <v-slide-y-transition>
                            <div v-if="form.recentlySuccessful" class="text-success font-weight-bold d-flex align-center">
                                <v-icon icon="mdi-check-circle" start class="mr-1"></v-icon>
                                Salvo com sucesso!
                            </div>
                        </v-slide-y-transition>
                    </div>

                    <v-btn
                        type="submit"
                        color="blue-darken-1"
                        variant="elevated"
                        size="large"
                        :loading="form.processing"
                        class="font-weight-bold text-white px-6"
                    >
                        Atualizar Perfil
                    </v-btn>
                </v-card-actions>
            </v-form>

        </v-card>
    </AppLayout>
</template>
