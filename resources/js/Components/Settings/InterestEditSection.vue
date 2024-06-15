<script setup>
import Button from '@/Components/Generic/Button.vue'
import Tag from '@/Components/Generic/Tag.vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {faPencil, faXmark} from '@fortawesome/free-solid-svg-icons'
import {inject, ref} from "vue"
import Card from "@/Components/Generic/Card.vue"
import InterestForm from "@/Components/Forms/InterestForm.vue"

const props = defineProps({
    categories: Array
})

const editing = ref(false)
const { addFlashMessage } = inject('flashMessages')

const interestsUpdated = () => {
    addFlashMessage({
        content: 'Interests updated successfully.',
    })
    editing.value = false
}
</script>

<template>
    <section>
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-medium">Interests</h2>

            <Button v-if="editing" color="base" variant="light" :on-click="() => editing = false">
                <FontAwesomeIcon :icon="faXmark" /> Cancel
            </Button>
            <Button v-else :on-click="() => editing = true">
                <FontAwesomeIcon :icon="faPencil" /> Edit
            </Button>
        </div>

        <Card v-if="editing" styles="w-full flex flex-col max-h-[1200px]">
            <InterestForm
                next-route="settings.profile"
                :on-success="interestsUpdated"
                :default-categories="categories"
                :default-selected-interests="$page.props.profile.interests.map(topic => topic.uuid)" />
        </Card>
        <div v-else class="flex gap-2 items-center flex-wrap">
            <div v-for="interest in $page.props.profile.interests" :key="interest.uuid">
                <Tag>{{ interest.label }}</Tag>
            </div>
        </div>
    </section>
</template>
