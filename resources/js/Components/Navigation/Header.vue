<script setup>
import { inject } from 'vue'
import Card from '../Generic/Card.vue'

const headerTitle = inject('headerTitle')
</script>

<template>
    <Card styles="w-full flex items-center justify-between mb-1">
        <div class="flex items-center gap-6">
            <img src="/logo.svg" alt="logo" class="w-11" />
            <h1 class="font-bold text-3xl mb-1">{{ headerTitle ?? 'Tribe' }}</h1>
        </div>

        <slot name="right-section" />
    </Card>
</template>