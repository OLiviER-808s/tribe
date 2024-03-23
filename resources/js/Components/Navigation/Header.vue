<script setup>
import Card from '../Generic/Card.vue'

const { title } = defineProps({
    title: {
        type: String,
        default: 'Tribe'
    }
})
</script>

<template>
    <Card styles="w-full flex items-center gap-6 mb-1">
        <img src="/logo.svg" alt="logo" class="w-11" />

        <h1 class="font-bold text-3xl mb-1">{{ title }}</h1>
    </Card>
</template>