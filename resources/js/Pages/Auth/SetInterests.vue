<script setup>
import EntryLayout from '@/Layouts/EntryLayout.vue'
import Stepper from '@/Components/Generic/Stepper.vue'
import { useForm } from '@inertiajs/vue3'
import { REGISTER_STEPS } from '@/lib/constants'
import Button from '@/Components/Generic/Button.vue'
import ClickableTag from '../../Components/Generic/ClickableTag.vue'
import { ref } from 'vue'

const { categories } = defineProps({
    categories: Array
})

const errors = ref({})

const form = useForm({
    next_route: 'discover',
    interests: []
})

const tagIsSelected = (topic) => {
    return form.interests.includes(topic.uuid)
}

const handleTagSelect = (topic) => {
    if (tagIsSelected(topic)) {
        form.interests = form.interests.filter(t => t !== topic.uuid)
    } else {
        form.interests.push(topic.uuid)
    }
}

const submit = () => {
    form.patch(route('profile.interests.update'), {
        onError: (errs) => {
            errors.value = errs
        }
    })
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
                        <div v-for="topic in category.topics" :key="topic.uuid">
                            <ClickableTag :selected="tagIsSelected(topic)" :on-select="() => handleTagSelect(topic)">
                                {{ topic.label }}
                            </ClickableTag>
                        </div>
                    </div>
                </div>
            </div>

            <p v-if="errors?.interests" class="text-error text-center mb-2">{{ errors.interests }}</p>

            <Button styles="w-full" :on-click="submit">Complete</Button>
        </div>
    </EntryLayout>
</template>