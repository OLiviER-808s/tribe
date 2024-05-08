<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import GlobalSearch from '@/Components/Dropdowns/GlobalSearch.vue'
import ConversationCard from '@/Components/Display/ConversationCard.vue'
import ProfileCard from '@/Components/Profile/ProfileCard.vue'
import { useInfiniteScroll } from '@vueuse/core'
import { ref } from 'vue'

const props = defineProps({
    results: Object,
    searchQuery: String
})

const resultsContainer = ref('')

useInfiniteScroll(resultsContainer, async () => {
    if (!props.results.next_page_url) return

    const response = await axios.get(props.results.next_page_url)

    props.results.data = [...props.results.data, ...response.data.results.data]
    props.results.next_page_url = response.data.results.next_page_url
}, { distance: 40 })
</script>

<template>
    <AuthLayout>
        <template #header-right-section>
            <GlobalSearch />
        </template>

        <div class="overflow-auto h-full">
            <section class="flex justify-center overflow-auto h-full" ref="resultsContainer">
                <div class="px-1 py-6 w-full max-w-2xl flex flex-col gap-4">

                    <div class="flex flex-col gap-4">
                        <div v-for="result in results.data" :key="result.uuid">
                            <ConversationCard v-if="result.resultType === 'conversation'" :conversation="result" />
                            <ProfileCard v-if="result.resultType === 'user'" :profile="result" />
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AuthLayout>
</template>