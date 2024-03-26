<script setup lang="ts">
    const { styles } = defineProps({
        styles: {
            type: String,
            default: ''
        },
        padding: {
            type: String,
            default: 'p-4'
        }
    })
</script>

<template>
    <div :class="`${styles} ${padding} bg-card border border-border dark:border-none rounded-md shadow-sm`">
        <slot />
    </div>
</template>