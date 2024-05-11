<script setup>
import EntryLayout from '@/Layouts/EntryLayout.vue'
import Stepper from '@/Components/Generic/Stepper.vue'
import { useForm } from '@inertiajs/vue3'
import { REGISTER_STEPS } from '@/lib/constants'
import Button from '@/Components/Generic/Button.vue'
import ClickableTag from '../../Components/Generic/ClickableTag.vue'
import TopicSearch from '@/Components/Dropdowns/TopicSearch.vue'
import { computed, ref } from 'vue'
import { insertToIndex } from '@/lib/utils'

const props = defineProps({
    categories: Array
})

const categoriesState = ref(props.categories)

const form = useForm({
    next_route: 'discover',
    interests: []
})

const allTopics = computed(() => categoriesState.value.flatMap(category => category.topics))

const tagIsSelected = (topic) => form.interests.includes(topic.uuid)

const handleTagSelect = (topic) => {
    if (tagIsSelected(topic)) {
        form.interests = form.interests.filter(uuid => uuid !== topic.uuid)
    } else {
        form.interests.push(topic.uuid)
    }
}

const selectTagFromSearch = (topic) => {
    form.interests.push(topic.uuid)

    const categoryIdx = categoriesState.value.findIndex(({ uuid }) => uuid === topic.category.uuid)
    const parentIdx = categoriesState.value[categoryIdx].topics.findIndex(({ uuid }) => uuid === topic.parent.uuid)

    insertToIndex(categoriesState.value[categoryIdx].topics, parentIdx + 1, topic)
}

const submit = () => form.patch(route('profile.interests.update'))
</script>

<template>
    <EntryLayout>
        <div class="mt-4 mb-4">
            <Stepper :steps="REGISTER_STEPS" selected-step-value="interests" />
        </div>

        <div class="mb-4 w-full">
            <TopicSearch :filtered-out-topics="allTopics" @on-topic-select="selectTagFromSearch" />
        </div>

        <div class="flex flex-col flex-1 h-0 overflow-auto px-2 mb-2">
            <div class="flex-grow flex flex-col gap-4">
                <div v-for="category in categoriesState" :key="category.uuid">
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
        </div>

        <p v-if="form.errors?.interests" class="text-error text-center mb-2">{{ form.errors.interests }}</p>

        <Button styles="w-full" :on-click="submit">Complete</Button>
    </EntryLayout>
</template>