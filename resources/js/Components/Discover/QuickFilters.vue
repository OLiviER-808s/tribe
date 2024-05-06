<script setup>
import { ref } from 'vue'

const selectedFilters = ref([])
</script>

<template>
    <div class="filter-container">
        <button class="filter" :class="{ 'selected-filter': selectedFilters.length < 1 }">
            All
        </button>

        <button v-for="topic in $page.props.profile.interests" 
        :key="topic.uuid"
        class="filter" 
        :class="{ 'selected-filter': selectedFilters.includes(topic.uuid) }">
            {{ topic.label }}
        </button>
    </div>
</template>

<style>
    .filter {
        @apply rounded-md duration-300 text-sm font-medium py-1 px-4 bg-neutral-400 text-nowrap;
    }
    .filter:hover {
        @apply bg-neutral-800 text-dropdown-text;
    }

    .selected-filter {
        @apply bg-neutral-800 text-dropdown-text;
    }

    .filter-container {
        @apply flex items-center gap-2 mb-4 pb-2 overflow-auto;
    }
    .filter-container::-webkit-scrollbar {
        height: 6px;
    }
</style>