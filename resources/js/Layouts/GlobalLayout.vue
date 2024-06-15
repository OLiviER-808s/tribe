<script setup lang="ts">
import { useDarkMode } from "@/Composables/useDarkMode"
import { useFlashMessages } from "@/Composables/useFlashMessages"
import FlashMessage from "@/Components/Generic/FlashMessage.vue"

useDarkMode()
const flashMessages = useFlashMessages()
</script>

<template>
    <div class="w-full h-full relative">
        <div class="absolute top-0 right-0 z-50 p-2 flex flex-col gap-2 overflow-hidden">
            <div v-for="message in flashMessages" :key="message.uuid">
                <FlashMessage :content="message.content" :color="message.color" />
            </div>
        </div>

        <slot />
    </div>
</template>
