<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import ConversationCard from '@/Components/Display/ConversationCard.vue'
import QuickFilters from '@/Components/Discover/QuickFilters.vue'
import GlobalSearch from '@/Components/Dropdowns/GlobalSearch.vue'
import { provide, ref } from 'vue'
import axios from 'axios'
import { useInfiniteScroll } from '@vueuse/core'
import {Head} from "@inertiajs/vue3";

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
        <Head title="Discover" />

        <template #header-right-section>
            <GlobalSearch />
        </template>

        <div class="overflow-auto h-full">
            <section class="flex justify-center overflow-auto h-full" ref="conversationContainer">
                <div class="px-1 py-6 w-full max-w-2xl">
                    <QuickFilters />

                    <div class="flex flex-col gap-4">
                        <div v-for="conversation in conversations.data" :key="conversation.uuid">
                            <ConversationCard :conversation="conversation" />
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AuthLayout>
</template>
