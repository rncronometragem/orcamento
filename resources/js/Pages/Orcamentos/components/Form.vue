<script setup>
import { useForm, Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    orcamento: Object,
    clientes: Array,
    produtos: Array,
    modal: Boolean || undefined,
    data: String || undefined,
});

const isEditing = computed(() => !!props.orcamento);

const form = useForm({
    _method: isEditing.value ? 'put' : 'post',
    cliente_id: props.orcamento?.cliente_id || null, // null funciona melhor com v-autocomplete
    status: props.orcamento?.status || 'pendente',
    observacoes: props.orcamento?.observacoes || '',
    pode_ver_unitarios: props.orcamento?.pode_ver_unitarios ?? false,
    itens: props.orcamento?.itens || [
        { descricao: '', quantidade: 1, preco_unitario: 0, produto_temp: null }
    ],
    // Garante que o input date receba YYYY-MM-DD
    data_evento: (props.data) ? props.data : props.orcamento?.data_evento?.split('T')[0] || '',
    local_evento: props.orcamento?.local_evento || '',
    quantidade_atletas: props.orcamento?.quantidade_atletas || '',
});

// === LÓGICA DE ITENS ===

const addItem = () => {
    form.itens.push({ descricao: '', quantidade: 1, preco_unitario: 0, produto_temp: null });
};

const removeItem = (index) => {
    if (form.itens.length > 1) {
        form.itens.splice(index, 1);
    }
};

// Modifiquei levemente para funcionar com o v-autocomplete (retorna o valor direto)
const aoSelecionarProduto = (index, produtoId) => {
    if (!produtoId) return;

    const prod = props.produtos.find(p => p.id === produtoId);
    if (prod) {
        form.itens[index].descricao = prod.nome;
        form.itens[index].preco_unitario = parseFloat(prod.valor);
        form.itens[index].produto_temp = null; // Reseta o select auxiliar
    }
};

const totalGeral = computed(() => {
    return form.itens.reduce((acc, item) => {
        return acc + (item.quantidade * item.preco_unitario);
    }, 0);
});

const formatarMoeda = (val) => new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL' }).format(val);

const submit = () => {
    const options = {
        preserveScroll: true,
        onSuccess: () => {
            if (props.modal) {
                // Lógica para fechar modal se necessário
            }
        }
    };

    if (isEditing.value) {
        form.post(`/orcamentos/${props.orcamento.id}`, options);
    } else {
        form.post('/orcamentos', options);
    }
};

const rules = {
    minimo: value => {
        return value > 0;

    }
}
</script>

<template>
    <v-form @submit.prevent="submit">
        <v-container fluid class="pa-0">

            <v-card class="mb-6" elevation="2" rounded="lg">
                <v-card-title class="text-subtitle-1 font-weight-bold text-grey-darken-2 pa-4 border-b">
                    Dados do Evento
                </v-card-title>
                <v-card-text class="pa-4">
                    <v-row>
                        <v-col cols="12" md="3">
                            <v-text-field
                                v-model="form.data_evento"
                                label="Data do Evento"
                                type="date"
                                variant="outlined"
                                density="comfortable"
                                :error-messages="form.errors.data_evento"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="6">
                            <v-text-field
                                v-model="form.local_evento"
                                label="Local do Evento"
                                placeholder="Ex: Ginásio Municipal"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-map-marker"
                            ></v-text-field>
                        </v-col>

                        <v-col cols="12" md="3">
                            <v-text-field
                                v-model="form.quantidade_atletas"
                                label="Qtd. Atletas"
                                type="number"
                                min="1"
                                variant="outlined"
                                density="comfortable"
                                prepend-inner-icon="mdi-account-group"
                            ></v-text-field>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>

            <v-card class="mb-6" elevation="2" rounded="lg">
                <v-card-text class="pa-4">
                    <v-row>
                        <v-col cols="12" md="8">
                            <v-autocomplete
                                v-model="form.cliente_id"
                                :items="clientes"
                                item-title="nome"
                                item-value="id"
                                label="Cliente"
                                placeholder="Selecione ou pesquise..."
                                variant="outlined"
                                density="comfortable"
                                :error-messages="form.errors.cliente_id"
                                prepend-inner-icon="mdi-account"
                            ></v-autocomplete>
                        </v-col>

                        <v-col cols="12" md="4">
                            <v-select
                                v-model="form.status"
                                :items="[
                                    { title: 'Pendente', value: 'pendente' },
                                    { title: 'Aprovado', value: 'aprovado' },
                                    { title: 'Rejeitado', value: 'rejeitado' },
                                    { title: 'Cancelado', value: 'cancelado' }
                                ]"
                                label="Status"
                                variant="outlined"
                                density="comfortable"
                            ></v-select>
                        </v-col>

                        <v-col cols="12">
                            <v-textarea
                                v-model="form.observacoes"
                                label="Observações"
                                placeholder="Detalhes de pagamento, validade, etc..."
                                rows="2"
                                variant="outlined"
                                density="comfortable"
                                auto-grow
                            ></v-textarea>
                        </v-col>
                    </v-row>
                </v-card-text>
            </v-card>

            <v-card class="mb-6" elevation="2" rounded="lg">
                <div class="d-flex justify-space-between align-center pa-4 border-b">
                    <h3 class="text-h6 font-weight-bold text-grey-darken-2">Itens do Orçamento</h3>
                    <v-btn
                        color="blue-lighten-5"
                        class="text-blue-darken-3 font-weight-bold"
                        prepend-icon="mdi-plus"
                        elevation="0"
                        @click="addItem"
                    >
                        Adicionar Item
                    </v-btn>
                </div>

                <v-card-text class="pa-4">
                    <div v-if="form.errors.itens" class="text-red mb-4 text-caption">
                        {{ form.errors.itens }}
                    </div>

                    <div
                        v-for="(item, index) in form.itens"
                        :key="index"
                        class="mb-4 pa-4 bg-grey-lighten-5 rounded border"
                    >
                        <v-row dense align="center">
                            <v-col cols="12" md="5">
                                <v-autocomplete
                                    v-model="item.produto_temp"
                                    :items="produtos"
                                    item-title="nome"
                                    item-value="id"
                                    label="Copiar de um produto..."
                                    variant="underlined"
                                    density="compact"
                                    class="mb-2"
                                    hide-details
                                    @update:model-value="(val) => aoSelecionarProduto(index, val)"
                                ></v-autocomplete>

                                <v-text-field
                                    v-model="item.descricao"
                                    label="Descrição do Item"
                                    variant="outlined"
                                    density="compact"
                                    hide-details="auto"
                                    bg-color="white"
                                    :error-messages="form.errors[`itens.${index}.descricao`]"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="6" md="2">
                                <v-text-field
                                    v-model="item.quantidade"
                                    type="number"
                                    label="Qtd"
                                    :rules="rules.minimo"
                                    min="1"
                                    variant="outlined"
                                    density="compact"
                                    hide-details
                                    bg-color="white"
                                    class="mt-md-6"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="6" md="2" v-if="form.pode_ver_unitarios">
                                <v-text-field
                                    v-model="item.preco_unitario"
                                    type="number"
                                    label="Preço Un."
                                    step="0.01"
                                    prefix="R$"
                                    variant="outlined"
                                    density="compact"
                                    hide-details
                                    bg-color="white"
                                    class="mt-md-6"
                                ></v-text-field>
                            </v-col>

                            <v-col cols="12" md="3" class="d-flex align-center justify-end mt-2 mt-md-6">
                                <div class="text-right mr-4">
                                    <div class="text-caption text-grey">Subtotal</div>
                                    <div class="font-weight-bold text-grey-darken-3">
                                        {{ formatarMoeda(item.quantidade * item.preco_unitario) }}
                                    </div>
                                </div>
                                <v-btn
                                    icon="mdi-delete"
                                    color="red-lighten-4"
                                    class="text-red-darken-3"
                                    size="small"
                                    elevation="0"
                                    @click="removeItem(index)"
                                ></v-btn>
                            </v-col>
                        </v-row>
                    </div>
                </v-card-text>

                <v-divider></v-divider>
                <div class="pa-4 bg-grey-lighten-4">
                    <v-row align="center" justify="space-between">
                        <v-col cols="12" md="6">
                            <v-checkbox
                                v-model="form.pode_ver_unitarios"
                                label="Cliente pode ver valor unitário?"
                                color="blue"
                                hide-details
                                density="compact"
                            ></v-checkbox>
                        </v-col>
                        <v-col cols="12" md="6" class="text-right">
                            <span class="text-h6 text-grey mr-3">Total Geral:</span>
                            <span class="text-h4 font-weight-bold text-blue-darken-2">
                                {{ formatarMoeda(totalGeral) }}
                            </span>
                        </v-col>
                    </v-row>
                </div>
            </v-card>

            <div class="d-flex text-center gap-4 pb-6">
                <Link v-if="!modal" href="/orcamentos" class="text-decoration-none mr-4">
                    <v-btn variant="tonal" size="large" color="grey-darken-1">Cancelar</v-btn>
                </Link>

                <v-btn
                    :style="(modal) ? `width: 100%;` : null"
                    type="submit"
                    color="blue-darken-1"
                    size="large"
                    elevation="2"
                    :loading="form.processing"
                >
                    {{ isEditing ? 'Atualizar Orçamento' : 'Finalizar Orçamento' }}
                </v-btn>
            </div>

        </v-container>
    </v-form>
</template>

<style scoped>
/* Pequeno ajuste para classes de margem que o Vuetify às vezes precisa de ajuda */
.gap-4 { gap: 16px; }
</style>
