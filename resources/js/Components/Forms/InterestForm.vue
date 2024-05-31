<script setup>
import { useForm } from '@inertiajs/vue3'
import Button from '@/Components/Generic/Button.vue'
import ClickableTag from '../../Components/Generic/ClickableTag.vue'
import TopicSearch from '@/Components/Dropdowns/TopicSearch.vue'
import { computed, ref } from 'vue'
import { insertToIndex } from '@/lib/utils'
import axios from 'axios'

const props = defineProps({
    defaultCategories: Array,
    nextRoute: String,
    defaultSelectedInterests: {
        type: Array,
        default: []
    },
    onSubmitted: {
        type: Function,
        default: () => null
    }
})

const categories = ref(props.defaultCategories)
const uncategorizedTopics = ref([])

const form = useForm({
    next_route: props.nextRoute,
    interests: props.defaultSelectedInterests
})

const allTopics = computed(() => categories.value.flatMap(category => category.topics))

const tagIsSelected = (topic) => form.interests.includes(topic.uuid)

const handleTagSelect = async (topic, idx, categoryIdx) => {
    if (tagIsSelected(topic)) {
        form.interests = form.interests.filter(uuid => uuid !== topic.uuid)
    } else {
        form.interests.push(topic.uuid)

        const response = await axios.get(route('topic.children', { uuid: topic.uuid }))
        const childTopics = response.data.topics.filter(child => !allTopics.value.find(({ uuid }) => uuid === child.uuid)) ?? []

        insertToIndex(categories.value[categoryIdx].topics, idx + 1, ...childTopics)
    }
}

const selectTagFromSearch = (topic) => {
    form.interests.push(topic.uuid)

    if (allTopics.value.some(({ uuid }) => topic.uuid === uuid)) {
        const categoryIdx = categories.value.findIndex(({ uuid }) => uuid === topic.category.uuid)
        const parentIdx = categories.value[categoryIdx].topics.findIndex(({ uuid }) => uuid === topic.parent.uuid)

        insertToIndex(categories.value[categoryIdx].topics, parentIdx + 1, topic)
    } else {
        uncategorizedTopics.value.push(topic)
    }
}

const submit = () => form.patch(route('profile.interests.update'), {
    onSuccess: () => props.onSubmitted()
})
</script>

<template>
    <div v-bind="$attrs" class="mb-4 w-full">
        <TopicSearch :filtered-out-topics="allTopics" @on-topic-select="selectTagFromSearch" />
    </div>

    <div v-bind="$attrs" class="flex flex-col flex-1 h-0 overflow-auto px-2 mb-2">
        <div class="flex-grow flex flex-col gap-4">
            <div v-if="uncategorizedTopics.length > 0">
                <h2 class="text-xl mb-2">Uncategorized</h2>

                <div class="flex gap-y-4 gap-x-2 flex-wrap items-center">
                    <div v-for="(topic, topicIdx) in uncategorizedTopics" :key="topic.uuid">
                        <ClickableTag :selected="tagIsSelected(topic)" :on-select="() => handleTagSelect(topic, topicIdx, categoryIdx)">
                            {{ topic.label }}
                        </ClickableTag>
                    </div>
                </div>
            </div>

            <div v-for="(category, categoryIdx) in categories" :key="category.uuid">
                <h2 class="text-xl mb-2">{{ category.name }}</h2>

                <div class="flex gap-y-4 gap-x-2 flex-wrap items-center">
                    <div v-for="(topic, topicIdx) in category.topics" :key="topic.uuid">
                        <ClickableTag :selected="tagIsSelected(topic)" :on-select="() => handleTagSelect(topic, topicIdx, categoryIdx)">
                            {{ topic.label }}
                        </ClickableTag>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div v-bind="$attrs">
        <p v-if="form.errors?.interests" class="text-error text-center mb-2">{{ form.errors.interests }}</p>

        <Button styles="w-full" :on-click="submit">Complete</Button>
    </div>
</template>
