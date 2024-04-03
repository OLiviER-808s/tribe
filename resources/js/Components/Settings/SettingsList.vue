<script setup>
import { computed } from 'vue';

const isActive = computed(() => false)
</script>

<template>
    <div class="flex flex-col h-full p-3">
        <div class="flex-grow h-0 overflow-auto">
            <a
                :href="href"
                @click="navigate"
                class="hover:bg-base rounded-md py-2 px-4 cursor-pointer text-lg block"
                :class="{ 'selected': isActive }"
            >
                Account
            </a>
        </div>
    </div>
</template>

<style>
.selected {
    @apply bg-base font-semibold;
}
.mobile {
    @apply text-xl font-medium;
}
</style>
