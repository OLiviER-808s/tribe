<script setup>
import DropdownMenu from 'v-dropdown-menu'
import Textbox from '@/Components/Generic/Textbox.vue'
import { ref, watch } from 'vue'
import { watchDebounced } from '@vueuse/core'
import axios from 'axios'
import { faLocationDot } from '@fortawesome/free-solid-svg-icons'

const props = defineProps({
    defaultSearchValue: String,
    modelValue: String,
})
const emit = defineEmits(['update:modelValue'])

const searchValue = ref(props.defaultSearchValue ?? '')
const suggestedCities = ref([])

watch(searchValue, () => emit('update:modelValue', searchValue.value))

watchDebounced(searchValue, async () => {
    if (searchValue.value) {
        const response = await axios.get(`/cities?search=${searchValue.value}`)
        suggestedCities.value = response.data?.cities
    }
}, { debounce: 500, maxWait: 1000 })
</script>

<template>
    <DropdownMenu :overlay="false" :dropup="false">
        <template #trigger>
            <Textbox 
            v-model="searchValue"
            :icon="faLocationDot"
            label="Location"
            placeholder="Select a city..."
            />
        </template>
        
        <template #body v-if="searchValue">
            <div class="overflow-auto max-h-64 flex flex-col">
                <button v-for="city in suggestedCities" :key="city.label" @click="searchValue = city.label" type="button" class="text-md py-2 px-6 hover:bg-dropdown-select rounded-md text-left">
                    {{ city.label }}
                </button>
            </div>
        </template>
    </DropdownMenu>
</template>

<style>
.v-dropdown-menu__container {
    @apply !w-full rounded-md bg-dropdown text-dropdown-text !border-none;
}
.v-dropdown-menu {
    @apply !w-full;
}
</style>