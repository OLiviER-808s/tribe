<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import Conversation from '@/Components/Display/Conversation.vue'

const { conversations } = defineProps({
    conversations: Array
})
</script>

<template>
    <AuthLayout>
        <div class="overflow-auto h-full" id="scrollable-content">
            <section class="flex justify-center overflow-auto h-full">
                <div class="px-1 py-6 w-full max-w-2xl">
                    <div class="flex flex-col gap-4">
                        <div v-for="conversation in conversations" :key="conversation.uuid">
                            <Conversation :conversation="conversation" />
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AuthLayout>
</template>