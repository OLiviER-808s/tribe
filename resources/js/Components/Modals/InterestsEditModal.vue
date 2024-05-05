<script setup>
import { VueFinalModal } from 'vue-final-modal'
import Card from '../Generic/Card.vue'
import ClickableTag from '../Generic/ClickableTag.vue'
import { useForm, usePage } from '@inertiajs/vue3'
import Button from '../Generic/Button.vue'
import { ref } from 'vue'

const { categories } = defineProps({
    categories: Array
})
const emit = defineEmits(['close'])

const profile = usePage().props.profile

const errors = ref({})

const form = useForm({
    next_route: 'settings.profile',
    interests: profile.interests.map(topic => topic.uuid)
})

const tagIsSelected = (topic) => {
    return form.interests.includes(topic.uuid)
}

const handleTagSelect = (topic) => {
    if (tagIsSelected(topic)) {
        form.interests = form.interests.filter(uuid => uuid !== topic.uuid)
    } else {
        form.interests.push(topic.uuid)
    }
}

const submit = () => {
    form.patch(route('profile.interests.update'), {
        onError: (errs) => {
            errors.value = errs
        },
        onSuccess: () => {
            emit('close')
        }
    })
}
</script>

<template>
    <VueFinalModal overlay-transition="vfm-fade" content-transition="vfm-fade" class="flex justify-center items-center" content-class="w-full max-w-xl p-2">
        <Card styles="w-full">
            <div>
                <div class="flex flex-col gap-4">
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

                <p v-if="errors?.interests" class="text-error text-center my-2">{{ errors.interests }}</p>

                <Button styles="w-full mt-2" :on-click="submit">Confirm</Button>
            </div>
        </Card>
    </VueFinalModal>
</template>