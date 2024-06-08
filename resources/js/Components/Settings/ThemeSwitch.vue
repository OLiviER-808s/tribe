<script setup>
import { faMoon, faSun } from '@fortawesome/free-solid-svg-icons'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { router } from '@inertiajs/vue3'
import { inject } from "vue"

const theme = inject('theme')

const toggle = () => {
    router.patch(route('settings.theme', {
        theme: theme.value === 'light' ? 'dark' : 'light'
    }))
}
</script>

<template>
    <button @click="toggle" class="hover:bg-base rounded-md flex gap-2 p-2 items-center">
        <!-- <Fa icon={theme === 'light' ? faMoon : faSun} /> -->
        <FontAwesomeIcon :icon="theme === 'light' ? faMoon : faSun" />
        <p>Toggle Theme</p>
    </button>
</template>
