<script setup>
import AuthLayout from '@/Layouts/AuthLayout.vue'
import GlobalSearch from '@/Components/Dropdowns/GlobalSearch.vue'
import ConversationCard from '@/Components/Display/ConversationCard.vue'
import ProfileCard from '@/Components/Profile/ProfileCard.vue'

const props = defineProps({
    results: Object,
    searchQuery: String
})


</script>

<template>
    <AuthLayout>
        <template #header-right-section>
            <GlobalSearch />
        </template>

        <div class="overflow-auto h-full">
            <section class="flex justify-center overflow-auto h-full" ref="conversationContainer">
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