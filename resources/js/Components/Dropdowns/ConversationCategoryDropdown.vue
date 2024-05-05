<script setup>
import DropdownMenu from 'v-dropdown-menu'
import Textbox from '@/Components/Generic/Textbox.vue'
import { ref, watch, computed } from 'vue';

const { categories, modelValue } = defineProps({
    categories: Array,
    modelValue: String,
    error: [String, Boolean],
})
const emit = defineEmits(['update:modelValue'])

const searchValue = ref(modelValue)

const filteredCategories = computed(() => categories.map(category => ({
    ...category,
    topics: category.topics.filter(topic => topic.label.toLowerCase().includes(searchValue.value.toLowerCase()) && topic.label.toLowerCase() !== searchValue.value.toLowerCase())
})))
const allTopics = computed(() => categories.flatMap(category => category.topics))

watch(searchValue, () => {
    const topic = allTopics.value.find(topic => topic.label.toLowerCase() === searchValue.value.toLowerCase())

    if (topic) {
        emit('update:modelValue', topic.uuid)
    } else {
        emit('update:modelValue', '')
    }
})
</script>

<template>
    <DropdownMenu :overlay="false" :dropup="false">
        <template #trigger>
            <Textbox 
            v-model="searchValue" 
            label="Category"
            placeholder="Pick a category..."
            :error="error"
            />
        </template>
        
        <template #body>
            <div class="overflow-auto max-h-64">
                <div v-for="category in filteredCategories" :key="category.uuid">
                    <h5 class="text-sm font-semibold text-dropdown-text p-2" v-if="category.topics.length > 0">{{ category.name }}</h5>

                    <div @click="searchValue = topic.label" v-for="topic in category.topics" :key="topic.uuid" class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md cursor-pointer">
                        {{ topic.label }}
                    </div>
                </div>
            </div>
        </template>
    </DropdownMenu>
</template>

<style>
.v-dropdown-menu__container {
    @apply !w-full rounded-md bg-dropdown text-dropdown-text !border-none;
}
</style>