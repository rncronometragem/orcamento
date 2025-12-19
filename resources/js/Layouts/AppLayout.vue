<template>
    <div class="flex h-screen bg-gray-100">

        <aside class="w-64 bg-slate-800 text-white flex flex-col hidden md:flex">

            <div class="h-16 flex items-center justify-center border-b border-slate-700 font-bold text-xl tracking-wider">
                ORÇAMENTO<span class="text-blue-400">SYS</span>
            </div>

            <nav class="flex-1 px-4 py-6 space-y-2">

                <Link href="/dashboard" :class="{'bg-slate-700 text-white': $page.url === '/dashboard'}" class="flex items-center px-4 py-3 rounded hover:bg-slate-700 transition text-slate-300 hover:text-white">
                    <span class="mr-3 text-lg">📊</span>
                    Dashboard
                </Link>

                <Link href="/orcamentos" :class="{'bg-slate-700 text-white': $page.url.startsWith('/orcamentos')}" class="flex items-center px-4 py-3 rounded hover:bg-slate-700 transition text-slate-300 hover:text-white">
                    <span class="mr-3 text-lg">📝</span>
                    Orçamentos
                </Link>

                <Link href="/clientes" :class="{'bg-slate-700 text-white': $page.url.startsWith('/clientes')}" class="flex items-center px-4 py-3 rounded hover:bg-slate-700 transition text-slate-300 hover:text-white">
                    <span class="mr-3 text-lg">👥</span>
                    Clientes
                </Link>

                <Link href="/produtos" :class="{'bg-slate-700 text-white': $page.url.startsWith('/produtos')}" class="flex items-center px-4 py-3 rounded hover:bg-slate-700 transition text-slate-300 hover:text-white">
                    <span class="mr-3 text-lg">📦</span>
                    Produtos
                </Link>

                <div class="border-t border-slate-700 my-4"></div>

                <Link href="/empresa" :class="{'bg-slate-700 text-white': $page.url.startsWith('/empresa')}" class="flex items-center px-4 py-3 rounded hover:bg-slate-700 transition text-slate-300 hover:text-white">
                    <span class="mr-3 text-lg">🏢</span>
                    Minha Empresa
                </Link>

                <Link href="/configuracoes" class="flex items-center px-4 py-3 rounded hover:bg-slate-700 transition text-slate-300 hover:text-white">
                    <span class="mr-3 text-lg">⚙️</span>
                    Configurações
                </Link>

            </nav>

            <div class="p-4 border-t border-slate-700">
                <Link href="/logout" method="post" as="button" class="flex items-center w-full px-4 py-2 text-red-400 hover:bg-slate-700 rounded transition">
                    <span class="mr-3">🚪</span>
                    Sair do Sistema
                </Link>
            </div>

        </aside>

        <div class="flex-1 flex flex-col overflow-hidden">

            <header class="h-16 bg-white shadow flex items-center justify-between px-6">
                <button class="md:hidden text-gray-600 text-2xl">☰</button> <div class="font-bold text-gray-700">
                <slot name="header" />
            </div>

                <div class="flex items-center">
                    <span class="text-sm text-gray-600 mr-2">Olá, {{ $page.props.auth.user.name }}</span>
                    <div class="h-8 w-8 rounded-full bg-blue-500 text-white flex items-center justify-center font-bold">A</div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-100 p-6">
                <slot />
            </main>

        </div>

    </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';

</script>
