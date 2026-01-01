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
    valor: props.produto?.valor || '',
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

        <div class="pa-6">
            <v-card class="mx-auto" max-width="800" elevation="2" rounded="lg">

                <v-form @submit.prevent="submit">
                    <v-card-text class="pa-6">

                        <div class="mb-4">
                            <v-text-field
                                v-model="form.nome"
                                label="Nome do Produto"
                                placeholder="Ex: Câmera de Segurança HD"
                                variant="outlined"
                                density="comfortable"
                                counter="200"
                                :error-messages="form.errors.nome"
                            ></v-text-field>
                        </div>

                        <div class="mb-2">
                            <v-text-field
                                v-model="form.valor"
                                label="Valor Unitário"
                                placeholder="0.00"
                                type="number"
                                step="0.01"
                                prefix="R$"
                                variant="outlined"
                                density="comfortable"
                                :error-messages="form.errors.valor"
                                hint="Deixe em branco se não tiver valor definido."
                                persistent-hint
                            ></v-text-field>
                        </div>

                    </v-card-text>

                    <v-divider></v-divider>

                    <v-card-actions class="pa-4 justify-end">
                        <Link href="/produtos" class="text-decoration-none mr-2">
                            <v-btn variant="text" color="grey-darken-1">
                                Cancelar
                            </v-btn>
                        </Link>

                        <v-btn
                            type="submit"
                            color="blue-darken-1"
                            variant="elevated"
                            :loading="form.processing"
                            class="font-weight-bold"
                        >
                            {{ form.processing ? 'Salvando...' : 'Salvar Produto' }}
                        </v-btn>
                    </v-card-actions>
                </v-form>

            </v-card>
        </div>
    </AppLayout>
</template>
