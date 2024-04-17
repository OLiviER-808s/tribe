<script setup>
import Card from '../Components/Generic/Card.vue'
import GlobalLayout from './GlobalLayout.vue'

const { title } = defineProps({
    title: {
        type: String,
        default: 'Welcome to Tribe'
    }
})
</script>

<template>
    <GlobalLayout>
        <div class="flex h-full w-full p-1 gap-1">
            <div class="flex-grow">
                <slot name="ad" />
            </div>

            <Card styles="w-2/5 max-w-xl h-full flex flex-col" padding="p-2">
                <div class="flex justify-center my-2">
                    <img src="/logo.svg" alt="logo" class="w-10" />
                </div>
                <h2 class="text-center text-2xl font-bold">{{ title }}</h2>

                <slot />
            </Card>
        </div>
    </GlobalLayout>
</template>