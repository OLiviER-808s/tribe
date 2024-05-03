<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import Conversation from '@/Components/Display/Conversation.vue'
import { provide, ref } from 'vue'
import { useInfiniteScroll } from '@vueuse/core/index.cjs';
import axios from 'axios';
import Tag from '@/Components/Generic/Tag.vue';

const props = defineProps({
    conversations: Object
})

provide('headerTitle', 'Discover')

const conversationContainer = ref(null)
const loading = ref(false)

useInfiniteScroll(conversationContainer, async () => {
    if (loading.value || !props.conversations.meta.next_cursor) return

    loading.value = true
    const response = await axios.get(`${props.conversations.meta.path}?cursor=${props.conversations.meta.next_cursor}`)

    props.conversations.data = [...props.conversations.data, ...response.data.data]
    props.conversations.meta = response.data.meta
    loading.value = false
}, { distance: 10 })
</script>

<template>
    <AuthLayout>
        <div class="overflow-auto h-full">
            <section class="flex justify-center overflow-auto h-full" ref="conversationContainer">
                <div class="px-1 py-6 w-full max-w-2xl">
                    <div class="flex items-center gap-2 mb-6">
                        <Tag rounded="md" color="secondary-text/60" size="sm">test</Tag>
                    </div>

                    <div class="flex flex-col gap-4">
                        <div v-for="conversation in conversations.data" :key="conversation.uuid">
                            <Conversation :conversation="conversation" />
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AuthLayout>
</template>