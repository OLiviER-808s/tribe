<script setup>
import EntryLayout from '@/Layouts/EntryLayout.vue'
import AvatarInput from '@/Components/Generic/AvatarInput.vue'
import Stepper from '@/Components/Generic/Stepper.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import { REGISTER_STEPS } from '@/lib/constants'
import Textarea from '@/Components/Generic/Textarea.vue'
import Button from '@/Components/Generic/Button.vue'
import UsernameInput from '@/Components/Auth/UsernameInput.vue'
import { ref } from 'vue'
import ClickableTag from '../../Components/Generic/ClickableTag.vue'

const { categories } = defineProps({
    categories: Array
})

const form = useForm({
    tags: []
})

const tagIsSelected = (tag) => {
    return form.tags.includes(tag)
}

const handleTagSelect = (tag) => {
    if (tagIsSelected(tag)) {
        form.tags = form.tags.filter(t => t !== tag)
    } else {
        form.tags.push(tag)
    }
}

const submit = () => {
    
}
</script>

<template>
    <EntryLayout>
        <div class="mt-4 mb-8">
            <Stepper :steps="REGISTER_STEPS" selected-step-value="interests" />
        </div>

        <div class="flex flex-col flex-1 h-0 overflow-auto px-2">
            <div class="flex-grow flex flex-col gap-4">
                <div v-for="category in categories" :key="category.uuid">
                    <h2 class="text-xl mb-2">{{ category.name }}</h2>

                    <div class="flex gap-y-4 gap-x-2 flex-wrap items-center">
                        <div v-for="tag in category.tags" :key="tag">
                            <ClickableTag :selected="tagIsSelected(tag)" :on-select="() => handleTagSelect(tag)">{{ tag }}</ClickableTag>
                        </div>
                    </div>
                </div>
            </div>

            <Button styles="w-full" type="submit">Complete</Button>
        </div>
    </EntryLayout>
</template>