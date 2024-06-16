<script setup>
const props = defineProps({
    label: String
})
</script>

<template>
    <div class="flex items-center justify-center">
        <div>
            <div class="spinner"></div>

            <p v-if="label" class="text-center font-medium mt-2">{{ label }}</p>
        </div>
    </div>
</template>

<style>
.spinner {
    @apply border-4 border-base border-t-primary;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
