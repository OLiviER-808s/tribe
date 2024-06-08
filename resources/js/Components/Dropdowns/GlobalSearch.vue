<script setup>
import DropdownMenu from 'v-dropdown-menu'
import Textbox from '@/Components/Generic/Textbox.vue'
import { ref } from 'vue'
import { watchDebounced } from '@vueuse/core'
import axios from 'axios'
import { faSearch, faUsers } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import Avatar from '../Generic/Avatar.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
    defaultSearchValue: String
})

const focused = ref(false)
const searchValue = ref(props.defaultSearchValue ?? '')
const searchResults = ref(null)

const selectResult = (result = null) => {
    if (!searchValue || !focused) return

    switch (result?.resultType) {
        case 'search_result':
            router.visit(`/search?query=${result.term}`)
            break
        case 'user':
            router.visit(route('profile', { username: result.username }))
            break
        case 'conversation':
            router.visit(route('conversation', { uuid: result.uuid }))
            break
        default:
            router.visit(`/search?query=${searchValue.value}`)
            break
    }
}

watchDebounced(searchValue, async () => {
    const response = await axios.get(`/search?query=${searchValue.value}`)
    searchResults.value = response.data.results
}, { debounce: 500, maxWait: 1000 })
</script>

<template>
    <form @submit.prevent="selectResult">
        <DropdownMenu :overlay="false" :dropup="false" class="w-full">
            <template #trigger>
                <Textbox
                v-model="searchValue"
                :icon="faSearch"
                placeholder="Press / to search"
                :on-focus="() => focused = true"
                :on-blur="() => focused = false"
                :styles="focused || searchValue.length > 0 ? 'w-96' : 'w-auto'"
                />
            </template>

            <template #body>
                <div class="overflow-auto max-h-64 flex flex-col" v-if="searchValue.length > 0 && searchResults?.data.length > 0">
                    <button v-for="result in searchResults.data" @click="selectResult(result)" :key="result.uuid" type="button" class="text-md py-2 px-4 hover:bg-dropdown-select rounded-md text-left">
                        <div class="flex items-center gap-4" v-if="result.resultType === 'conversation'">
                            <FontAwesomeIcon :icon="faUsers" />
                            <div>
                                <p cla>{{ result.title }}</p>
                                <p class="text-sm">Conversation by @{{ result.created_by }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4" v-else-if="result.resultType === 'user'">
                            <Avatar :src="result.photo" styles="w-8" />
                            <div>
                                <p cla>{{ result.name }}</p>
                                <p class="text-sm">@{{ result.username }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-4" v-else-if="result.resultType === 'search_term'">
                            <FontAwesomeIcon :icon="faSearch" /> {{ result.term }}
                        </div>
                    </button>
                </div>
            </template>
        </DropdownMenu>
    </form>
</template>

<style>
.v-dropdown-menu__container {
    @apply w-full rounded-md bg-dropdown text-dropdown-text !border-none;
}
</style>
