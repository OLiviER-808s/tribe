<script setup>
import { router, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

const getInitialSelectedTopics = () => {
    if (!page.url.includes('/discover?topics=')) return []

    const params = new URLSearchParams(window.location.search)
    const topics = params.get('topics').split(',')

    return page.props.profile.interests.filter(topic => {
        return topics.includes(topic.label)
    })
}

const page = usePage()
const selectedTopics = ref(getInitialSelectedTopics())

const select = (topic) => {
    if (!topic) {
        selectedTopics.value = []
        router.visit(route('discover'), {
            preserveScroll: true,
            preserveState: true
        })
        return
    }

    if (selectedTopics.value.find(({ uuid }) => uuid === topic.uuid)) {
        selectedTopics.value = selectedTopics.value.filter(({ uuid }) => uuid !== topic.uuid)
    } else {
        selectedTopics.value.push(topic)
    }

    router.visit(`/discover?topics=${selectedTopics.value.map(({ label }) => label).join(',')}`, {
        preserveScroll: true,
        preserveState: true
    })
}
</script>

<template>
    <div class="filter-container">
        <button @click="select(null)" class="filter" :class="{ 'selected-filter': selectedTopics.length < 1 }">
            All
        </button>

        <button v-for="topic in $page.props.profile.interests" 
        @click="select(topic)"
        :key="topic.uuid"
        class="filter" 
        :class="{ 'selected-filter': !!selectedTopics.find(({ uuid }) => uuid === topic.uuid) }">
            {{ topic.label }}
        </button>
    </div>
</template>

<style>
    .filter {
        @apply rounded-md duration-300 text-sm font-medium py-1 px-4 bg-quick-filter text-nowrap;
    }

    .selected-filter {
        @apply bg-quick-filter-selected text-dropdown-text;
    }

    .filter-container {
        @apply flex items-center gap-2 mb-4 pb-2 overflow-auto;
    }
    .filter-container::-webkit-scrollbar {
        height: 6px;
    }
</style>