<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm, Link } from '@inertiajs/vue3';
import { vMaska } from 'maska/vue';

const form = useForm({
    nome: '',
    documento: '',
    tipo_pessoa: 'juridica',

    enderecos: [
        { cep: '', logradouro: '', numero: '', bairro: '', cidade: '', uf: '' }
    ],

    contatos: [
        { departamento: '', email: '', celular: '' }
    ]
});

// Opções de máscara
const options = {
    fisica: { mask: '###.###.###-##' },
    juridica: { mask: '##.###.###/####-##' }
};

const optionCelular = {
    mask: '(##) # ####-####'
};

function adicionarEndereco() {
    form.enderecos.push({ cep: '', logradouro: '', numero: '', bairro: '', cidade: '', uf: '' });
}

function removerEndereco(index) {
    if (form.enderecos.length > 1) {
        form.enderecos.splice(index, 1);
    } else {
        // Snackbars são melhores, mas alert serve por enquanto
        alert('É necessário ter pelo menos um endereço.');
    }
}

function adicionarContato() {
    form.contatos.push({ departamento: '', email: '', celular: '' });
}

function removerContato(index) {
    form.contatos.splice(index, 1);
}

function salvar() {
    form.post('/clientes');
}
</script>

<template>
    <AppLayout>
        <template #header>
            Novo Cliente
        </template>

        <v-form @submit.prevent="salvar">

            <v-card class="mb-6" elevation="2" rounded="lg">
                <v-card-title class="pa-4 border-b text-subtitle-1 font-weight-bold text-grey-darken-2">
                    Dados Básicos
                </v-card-title>

                <v-card-text class="pa-6">
                    <v-row>
                        <v-col cols="12" md="4">
                            <label class="text-caption font-weight-bold text-grey-darken-1 mb-2 d-block">Tipo de Pessoa</label>
                            <v-btn-toggle
                                v-model="form.tipo_pessoa"
                                color="blue-darken-2"
                                mandatory
                                divided
                                variant="outlined"
                                class="w-100"
                            >
                                <v-btn value="fisica" class="flex-grow-1">Pessoa Física</v-btn>
                                <v-btn value="juridica" class="flex-grow-1">Pessoa Jurídica</v-btn>
                            </v-btn-toggle>
                        </v-col>

                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="form.documento"
                                v-maska="options[form.tipo_pessoa]"
                                :label="form.tipo_pessoa === 'fisica' ? 'CPF' : 'CNPJ'"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-card-account-details-outline"
                                :error-messages="form.errors.documento"
                                class="mt-md-6"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="form.nome"
                                :label="form.tipo_pessoa === 'fisica' ? 'Nome Completo' : 'Razão Social'"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-account-outline"
                                :error-messages="form.errors.nome"
                                class="mt-md-6"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>

            <v-card class="mb-6" elevation="2" rounded="lg">
                <div class="d-flex justify-space-between align-center pa-4 border-b">
                    <h2 class="text-subtitle-1 font-weight-bold text-grey-darken-2">Endereços</h2>
                    <v-btn
                        color="blue-lighten-5"
                        class="text-blue-darken-3 font-weight-bold"
                        prepend-icon="mdi-map-marker-plus"
                        elevation="0"
                        size="small"
                        @click="adicionarEndereco"
                    >
                        Adicionar Endereço
                    </v-btn>
                </div>

                <v-card-text class="pa-6">
                    <div v-if="form.errors.enderecos" class="text-red text-caption mb-2">
                        {{ form.errors.enderecos }}
                    </div>

                    <div
                        v-for="(endereco, index) in form.enderecos"
                        :key="index"
                        class="mb-4 pa-4 bg-grey-lighten-5 rounded border position-relative"
                    >
                        <v-btn
                            icon="mdi-close"
                            variant="text"
                            color="grey"
                            size="small"
                            class="position-absolute top-0 right-0 ma-2"
                            @click="removerEndereco(index)"
                            v-tooltip="'Remover este endereço'"
                        ></v-btn>

                        <div class="text-overline text-grey mb-2">Endereço #{{ index + 1 }}</div>

                        <v-row dense>
                            <v-col cols="12" md="2">
                                <v-text-field
                                    v-model="endereco.cep"
                                    label="CEP"
                                    v-maska="'#####-###'"
                                    variant="outlined"
                                    density="compact"
                                    bg-color="white"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="endereco.logradouro"
                                    label="Logradouro (Rua, Av...)"
                                    variant="outlined"
                                    density="compact"
                                    bg-color="white"
                                    :error-messages="form.errors[`enderecos.${index}.logradouro`]"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="6" md="2">
                                <v-text-field
                                    v-model="endereco.numero"
                                    label="Número"
                                    variant="outlined"
                                    density="compact"
                                    bg-color="white"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="6" md="2">
                                <v-text-field
                                    v-model="endereco.bairro"
                                    label="Bairro"
                                    variant="outlined"
                                    density="compact"
                                    bg-color="white"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="8">
                                <v-text-field
                                    v-model="endereco.cidade"
                                    label="Cidade"
                                    variant="outlined"
                                    density="compact"
                                    bg-color="white"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field
                                    v-model="endereco.uf"
                                    label="UF"
                                    variant="outlined"
                                    density="compact"
                                    bg-color="white"
                                    maxlength="2"
                                    class="text-uppercase"
                                    :error-messages="form.errors[`enderecos.${index}.uf`]"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </div>
                </v-card-text>
            </v-card>

            <v-card class="mb-6" elevation="2" rounded="lg">
                <div class="d-flex justify-space-between align-center pa-4 border-b">
                    <h2 class="text-subtitle-1 font-weight-bold text-grey-darken-2">Contatos</h2>
                    <v-btn
                        color="green-lighten-5"
                        class="text-green-darken-3 font-weight-bold"
                        prepend-icon="mdi-account-plus"
                        elevation="0"
                        size="small"
                        @click="adicionarContato"
                    >
                        Adicionar Contato
                    </v-btn>
                </div>

                <v-card-text class="pa-6">
                    <v-row v-for="(contato, index) in form.contatos" :key="index" align="start" dense class="mb-2">
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="contato.departamento"
                                label="Departamento"
                                placeholder="Ex: Financeiro"
                                variant="outlined"
                                density="compact"
                                hide-details="auto"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="12" md="4">
                            <v-text-field
                                v-model="contato.email"
                                label="Email"
                                type="email"
                                variant="outlined"
                                density="compact"
                                hide-details="auto"
                                prepend-inner-icon="mdi-email-outline"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="10" md="3">
                            <v-text-field
                                v-model="contato.celular"
                                label="Celular / WhatsApp"
                                v-maska="optionCelular"
                                variant="outlined"
                                density="compact"
                                hide-details="auto"
                                prepend-inner-icon="mdi-whatsapp"
                                :error-messages="form.errors[`contatos.${index}.celular`]"
                            ></v-text-field>
                        </v-col>
                        <v-col cols="2" md="1" class="d-flex justify-center">
                            <v-btn
                                icon="mdi-delete"
                                color="red-lighten-4"
                                class="text-red-darken-3"
                                size="small"
                                elevation="0"
                                @click="removerContato(index)"
                            ></v-btn>
                        </v-col>
                    </v-row>

                    <div v-if="form.contatos.length === 0" class="text-center pa-4 text-grey">
                        <v-icon icon="mdi-account-off-outline" class="mb-2"></v-icon>
                        <p>Nenhum contato adicionado.</p>
                    </div>
                </v-card-text>
            </v-card>

            <div class="d-flex justify-end gap-4 pb-6">
                <Link href="/clientes" class="text-decoration-none mr-4">
                    <v-btn variant="tonal" size="large" color="grey-darken-1">Cancelar</v-btn>
                </Link>

                <v-btn
                    type="submit"
                    color="blue-darken-1"
                    size="large"
                    elevation="2"
                    :loading="form.processing"
                >
                    Salvar Cliente
                </v-btn>
            </div>

        </v-form>
    </AppLayout>
</template>

<style scoped>
/* Vuetify lida bem com margens, mas classes utilitárias ajudam no ajuste fino */
.w-100 { width: 100%; }
.flex-grow-1 { flex-grow: 1; }
.gap-4 { gap: 16px; }
.position-relative { position: relative; }
.position-absolute { position: absolute; }
</style>
