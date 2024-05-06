<script setup>
import DropdownMenu from 'v-dropdown-menu'
import Textbox from '@/Components/Generic/Textbox.vue'
import { ref, watch } from 'vue'
import { watchDebounced } from '@vueuse/core'
import axios from 'axios'
import { faSearch } from '@fortawesome/free-solid-svg-icons'

const props = defineProps({
    modelValue: String,
    error: [String, Boolean],
})
const emit = defineEmits(['update:modelValue'])

const focused = ref(false)
</script>

<template>
    <DropdownMenu :overlay="false" :dropup="false">
        <template #trigger>
            <Textbox 
            :icon="faSearch" 
            placeholder="Press / to search"
            :on-focus="() => focused = true"
            :on-blur="() => focused = false"
            :styles="focused ? 'w-96' : 'w-auto'"
            />
        </template>
        
        <template #body>
            <div class="overflow-auto max-h-64 flex flex-col">

            </div>
        </template>
    </DropdownMenu>
</template>

<style>
.v-dropdown-menu__container {
    @apply !w-full rounded-md bg-dropdown text-dropdown-text !border-none;
}
</style>