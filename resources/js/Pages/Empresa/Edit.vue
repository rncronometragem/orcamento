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

    _method: 'POST'
});

const logoPreview = ref(props.empresa?.logo_url || null);

// Vuetify retorna um array de arquivos no v-file-input, pegamos o primeiro
const handleLogoChange = (files) => {
    const file = files[0]; // Vuetify 3 retorna array
    if (file) {
        form.logo = file;
        logoPreview.value = URL.createObjectURL(file);
    }
};

const submit = () => {
    form.post(route('empresa.update'), {
        forceFormData: true,
        onSuccess: () => {
            // Feedback opcional (Snackbars são comuns no Vuetify)
        },
    });
};
</script>

<template>
    <AppLayout>
        <Head title="Configuração da Empresa" />

        <v-form @submit.prevent="submit">
            <v-card elevation="2" rounded="lg">

                <v-card-title class="pa-6 border-b">
                    <h2 class="text-h5 font-weight-bold text-grey-darken-3">Dados da Empresa</h2>
                </v-card-title>

                <v-card-text class="pa-6">

                    <div class="mb-8">
                        <h3 class="text-h6 font-weight-medium text-grey-darken-2 mb-4 d-flex align-center">
                            <v-icon icon="mdi-palette" start color="blue"></v-icon>
                            Identidade Visual
                        </h3>

                        <v-card variant="outlined" color="grey-lighten-2" class="pa-4 bg-grey-lighten-5">
                            <v-row align="center">
                                <v-col cols="12" md="4" class="text-center">
                                    <v-card
                                        class="mx-auto mb-4 d-flex align-center justify-center bg-white border-dashed"
                                        width="150"
                                        height="150"
                                        elevation="0"
                                        border
                                    >
                                        <v-img
                                            v-if="logoPreview"
                                            :src="logoPreview"
                                            max-height="120"
                                            contain
                                        ></v-img>
                                        <span v-else class="text-caption text-grey">Sem Logo</span>
                                    </v-card>

                                    <v-file-input
                                        label="Alterar Logo"
                                        accept="image/*"
                                        variant="outlined"
                                        density="compact"
                                        prepend-icon="mdi-camera"
                                        hide-details
                                        @update:model-value="handleLogoChange"
                                    ></v-file-input>
                                </v-col>

                                <v-col cols="12" md="8">
                                    <v-row>
                                        <v-col cols="12" sm="6">
                                            <label class="text-caption font-weight-bold text-grey-darken-1">Cor da Barra (Título)</label>
                                            <div class="d-flex align-center mt-2">
                                                <v-text-field
                                                    v-model="form.cor_barra"
                                                    variant="outlined"
                                                    density="compact"
                                                    hide-details
                                                    readonly
                                                >
                                                    <template v-slot:append-inner>
                                                        <input
                                                            type="color"
                                                            v-model="form.cor_barra"
                                                            class="color-input-swatch cursor-pointer"
                                                        />
                                                    </template>
                                                </v-text-field>
                                            </div>
                                        </v-col>

                                        <v-col cols="12" sm="6">
                                            <label class="text-caption font-weight-bold text-grey-darken-1">Cor do Texto (Título)</label>
                                            <div class="d-flex align-center mt-2">
                                                <v-text-field
                                                    v-model="form.cor_texto"
                                                    variant="outlined"
                                                    density="compact"
                                                    hide-details
                                                    readonly
                                                >
                                                    <template v-slot:append-inner>
                                                        <input
                                                            type="color"
                                                            v-model="form.cor_texto"
                                                            class="color-input-swatch cursor-pointer"
                                                        />
                                                    </template>
                                                </v-text-field>
                                            </div>
                                        </v-col>
                                    </v-row>
                                </v-col>
                            </v-row>
                        </v-card>
                    </div>

                    <v-divider class="my-6"></v-divider>

                    <div class="mb-6">
                        <h3 class="text-h6 font-weight-medium text-grey-darken-2 mb-4 d-flex align-center">
                            <v-icon icon="mdi-domain" start color="blue"></v-icon>
                            Dados Cadastrais
                        </h3>

                        <v-row dense>
                            <v-col cols="12">
                                <v-text-field
                                    v-model="form.nome"
                                    label="Razão Social / Nome"
                                    variant="outlined"
                                    density="comfortable"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field
                                    v-model="form.cnpj"
                                    label="CNPJ"
                                    variant="outlined"
                                    density="comfortable"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field
                                    v-model="form.insc_estadual"
                                    label="Insc. Estadual"
                                    variant="outlined"
                                    density="comfortable"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field
                                    v-model="form.site"
                                    label="Website"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-web"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </div>

                    <v-divider class="my-6"></v-divider>

                    <div class="mb-6">
                        <h3 class="text-h6 font-weight-medium text-grey-darken-2 mb-4 d-flex align-center">
                            <v-icon icon="mdi-card-account-phone" start color="blue"></v-icon>
                            Contato
                        </h3>

                        <v-row dense>
                            <v-col cols="12" md="4">
                                <v-text-field
                                    v-model="form.email"
                                    label="Email"
                                    type="email"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-email"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field
                                    v-model="form.telefone"
                                    label="Telefone Fixo"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-phone"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field
                                    v-model="form.celular"
                                    label="Celular / WhatsApp"
                                    variant="outlined"
                                    density="comfortable"
                                    prepend-inner-icon="mdi-whatsapp"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </div>

                    <v-divider class="my-6"></v-divider>

                    <div class="mb-6">
                        <h3 class="text-h6 font-weight-medium text-grey-darken-2 mb-4 d-flex align-center">
                            <v-icon icon="mdi-map-marker" start color="blue"></v-icon>
                            Endereço
                        </h3>

                        <v-row dense>
                            <v-col cols="12" md="3">
                                <v-text-field
                                    v-model="form.cep"
                                    label="CEP"
                                    variant="outlined"
                                    density="comfortable"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="6">
                                <v-text-field
                                    v-model="form.rua"
                                    label="Rua / Logradouro"
                                    variant="outlined"
                                    density="comfortable"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-text-field
                                    v-model="form.numero"
                                    label="Número"
                                    variant="outlined"
                                    density="comfortable"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field
                                    v-model="form.bairro"
                                    label="Bairro"
                                    variant="outlined"
                                    density="comfortable"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="4">
                                <v-text-field
                                    v-model="form.complemento"
                                    label="Complemento"
                                    variant="outlined"
                                    density="comfortable"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="3">
                                <v-text-field
                                    v-model="form.cidade"
                                    label="Cidade"
                                    variant="outlined"
                                    density="comfortable"
                                ></v-text-field>
                            </v-col>
                            <v-col cols="12" md="1">
                                <v-text-field
                                    v-model="form.uf"
                                    label="UF"
                                    variant="outlined"
                                    density="comfortable"
                                    maxlength="2"
                                ></v-text-field>
                            </v-col>
                        </v-row>
                    </div>

                </v-card-text>

                <v-card-actions class="pa-6 border-t bg-grey-lighten-5 justify-end">
                    <v-btn
                        type="submit"
                        color="blue-darken-1"
                        variant="elevated"
                        size="large"
                        :loading="form.processing"
                        class="font-weight-bold px-6"
                    >
                        Salvar Configurações
                    </v-btn>
                </v-card-actions>

            </v-card>
        </v-form>
    </AppLayout>
</template>

<style scoped>
/* Estilo para tornar o card de preview da logo "dashed" */
.border-dashed {
    border-style: dashed !important;
    border-width: 2px !important;
    border-color: #e0e0e0 !important;
}

/* Hack para mostrar o seletor de cor bonito dentro do input do Vuetify */
.color-input-swatch {
    width: 30px;
    height: 30px;
    border: none;
    padding: 0;
    background: transparent;
}
</style>
