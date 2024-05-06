<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import Conversation from '@/Components/Display/Conversation.vue'
import QuickFilters from '@/Components/Discover/QuickFilters.vue'
import { provide, ref } from 'vue'
import { useInfiniteScroll } from '@vueuse/core/index.cjs'
import axios from 'axios'
import Textbox from '@/Components/Generic/Textbox.vue'
import { faSearch } from '@fortawesome/free-solid-svg-icons'

const props = defineProps({
    conversations: Object
})

provide('headerTitle', 'Discover')

const conversationContainer = ref(null)

useInfiniteScroll(conversationContainer, async () => {
    if (!props.conversations.links.next) return

    const response = await axios.get(props.conversations.links.next)

    props.conversations.data = [...props.conversations.data, ...response.data.data]
    props.conversations.meta = response.data.meta
    props.conversations.links = response.data.links
}, { distance: 20 })
</script>

<template>
    <AuthLayout>
        <template #header-right-section>
            <Textbox :icon="faSearch" placeholder="Press / to search" />
        </template>

        <div class="overflow-auto h-full">
            <section class="flex justify-center overflow-auto h-full" ref="conversationContainer">
                <div class="px-1 py-6 w-full max-w-2xl">
                    <QuickFilters />

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