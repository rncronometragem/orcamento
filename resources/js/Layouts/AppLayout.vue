<template>
    <v-app>
        <v-navigation-drawer
            v-model="drawer"
            color="blue-grey-darken-4"
            theme="dark"
            width="280"
        >
            <div class="d-flex align-center justify-center pa-4 border-b-sm border-opacity-25">
        <span class="text-h6 font-weight-bold">
          ORÇAMENTO<span class="text-blue-lighten-2">SYS</span>
        </span>
            </div>

            <v-list class="pa-4">

                <Link href="/dashboard" class="text-decoration-none text-white">
                    <v-list-item
                        rounded="lg"
                        class="mb-1"
                        :active="$page.url === '/dashboard'"
                        color="primary"
                    >
                        <template v-slot:prepend>
                            <span class="mr-3 text-h6">📊</span>
                        </template>
                        <v-list-item-title>Dashboard</v-list-item-title>
                    </v-list-item>
                </Link>

                <Link href="/orcamentos" class="text-decoration-none text-white">
                    <v-list-item
                        rounded="lg"
                        class="mb-1"
                        :active="$page.url.startsWith('/orcamentos')"
                        color="primary"
                    >
                        <template v-slot:prepend>
                            <span class="mr-3 text-h6">📝</span>
                        </template>
                        <v-list-item-title>Orçamentos</v-list-item-title>
                    </v-list-item>
                </Link>

                <Link href="/clientes" class="text-decoration-none text-white">
                    <v-list-item
                        rounded="lg"
                        class="mb-1"
                        :active="$page.url.startsWith('/clientes')"
                        color="primary"
                    >
                        <template v-slot:prepend>
                            <span class="mr-3 text-h6">👥</span>
                        </template>
                        <v-list-item-title>Clientes</v-list-item-title>
                    </v-list-item>
                </Link>

                <Link href="/produtos" class="text-decoration-none text-white">
                    <v-list-item
                        rounded="lg"
                        class="mb-1"
                        :active="$page.url.startsWith('/produtos')"
                        color="primary"
                    >
                        <template v-slot:prepend>
                            <span class="mr-3 text-h6">📦</span>
                        </template>
                        <v-list-item-title>Produtos</v-list-item-title>
                    </v-list-item>
                </Link>

                <v-divider class="my-4 border-opacity-25"></v-divider>

                <Link href="/empresa" class="text-decoration-none text-white">
                    <v-list-item
                        rounded="lg"
                        class="mb-1"
                        :active="$page.url.startsWith('/empresa')"
                        color="primary"
                    >
                        <template v-slot:prepend>
                            <span class="mr-3 text-h6">🏢</span>
                        </template>
                        <v-list-item-title>Minha Empresa</v-list-item-title>
                    </v-list-item>
                </Link>

                <Link href="/configuracoes" class="text-decoration-none text-white">
                    <v-list-item
                        rounded="lg"
                        class="mb-1"
                        :active="$page.url.startsWith('/configuracoes')"
                        color="primary"
                    >
                        <template v-slot:prepend>
                            <span class="mr-3 text-h6">⚙️</span>
                        </template>
                        <v-list-item-title>Configurações</v-list-item-title>
                    </v-list-item>
                </Link>

            </v-list>

            <template v-slot:append>
                <div class="pa-4 border-t-sm border-opacity-25">
                    <Link href="/logout" method="post" as="div" class="text-decoration-none cursor-pointer">
                        <v-list-item rounded="lg" color="red-lighten-2">
                            <template v-slot:prepend>
                                <span class="mr-3 text-h6">🚪</span>
                            </template>
                            <v-list-item-title class="text-red-lighten-2">Sair do Sistema</v-list-item-title>
                        </v-list-item>
                    </Link>
                </div>
            </template>
        </v-navigation-drawer>

        <v-app-bar elevation="1" color="white">
            <v-app-bar-nav-icon @click="drawer = !drawer" class="d-md-none"></v-app-bar-nav-icon>

            <v-toolbar-title class="font-weight-bold text-grey-darken-3">
                <slot name="header" />
            </v-toolbar-title>

            <template v-slot:append>
        <span class="text-caption text-grey-darken-1 mr-3 d-none d-sm-flex">
          Olá, {{ $page.props.auth.user.name }}
        </span>
                <v-avatar color="blue" size="32">
                    <span class="text-white font-weight-bold">A</span>
                </v-avatar>
            </template>
        </v-app-bar>

        <v-main class="bg-grey-lighten-4">
            <v-container fluid class="pa-6">
                <slot />
            </v-container>
        </v-main>
    </v-app>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import { useDisplay } from 'vuetify';

// Controla a abertura/fechamento do menu lateral
// O Vuetify gerencia isso automaticamente, mas precisamos da ref para o botão de toggle
const drawer = ref(null);
</script>

<style scoped>
/* Ajuste fino para garantir que o Link do Inertia não quebre o layout flex do Vuetify */
.cursor-pointer {
    cursor: pointer;
}
</style>
