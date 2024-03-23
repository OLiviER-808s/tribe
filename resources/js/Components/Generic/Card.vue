<script setup lang="ts">
    const { styles } = defineProps({
        styles: {
            type: String,
            default: ''
        }
    })
</script>

<template>
    <div :class="`${styles} p-4 bg-card border border-border dark:border-none rounded-md shadow-sm`">
        <slot />
    </div>
</template>