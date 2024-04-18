<script setup>
import { faSearch } from '@fortawesome/free-solid-svg-icons'
import IconButton from '../Generic/IconButton.vue'
</script>

<template>
    <header class="flex justify-between p-4 bg-card shadow-sm">
        <img src="/logo.svg" alt="logo" class="w-10" />
        <IconButton variant="subtle" color="base" size="xl" :icon="faSearch" />
    </header>
</template>