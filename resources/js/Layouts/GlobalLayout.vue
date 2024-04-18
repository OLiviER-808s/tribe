<script setup>
import { usePage } from '@inertiajs/vue3'
import { watch } from 'vue';
import { onMounted } from 'vue'

const page = usePage()

onMounted(() => {
    if (page.props.theme === 'dark') {
        document.getElementsByTagName('body')[0].className += ' dark'
    }
})

watch(page, (newPage) => {
    if (newPage.props.theme === 'dark') {
        document.getElementsByTagName('body')[0].className += ' dark'
    } else {
        const className = document.getElementsByTagName('body')[0].className

        document.getElementsByTagName('body')[0].className = className.replace(' dark', '')
    }
}, { deep: true })
</script>

<template>
    <slot />
</template>