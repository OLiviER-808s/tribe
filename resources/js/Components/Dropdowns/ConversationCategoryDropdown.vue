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
    tags: category.tags.filter(tag => tag.toLowerCase().includes(searchValue.value.toLowerCase()) && tag.toLowerCase() !== searchValue.value.toLowerCase())
})))
const allTags = computed(() => categories.flatMap(category => category.tags).map(tag => tag.toLowerCase()))

watch(searchValue, () => {
    if (allTags.value.includes(searchValue.value.toLowerCase())) {
        console.log('test')
        emit('update:modelValue', searchValue.value)
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
                    <h5 class="text-sm font-semibold text-dropdown-text p-2" v-if="category.tags.length > 0">{{ category.name }}</h5>

                    <div @click="searchValue = tag" v-for="tag in category.tags" :key="tag" class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md cursor-pointer">
                        {{ tag }}
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