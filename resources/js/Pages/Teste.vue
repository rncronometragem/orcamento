<template>
    <h1 style="color: blue;">Funcionou no Laravel 10!</h1>
    <p>Mensagem do Back-end: {{ mensagem }}</p>
</template>

<script setup>
defineProps({ mensagem: String })
</script>
