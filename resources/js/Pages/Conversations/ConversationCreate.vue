<script setup>
import Card from '@/Components/Generic/Card.vue'
import AuthLayout from '@/Layouts/AuthLayout.vue'
import Textbox from '@/Components/Generic/Textbox.vue'
import { ref } from 'vue'
import Textarea from '@/Components/Generic/Textarea.vue'
import NumberInput from '@/Components/Generic/NumberInput.vue'
import Button from '@/Components/Generic/Button.vue'
import { useForm } from '@inertiajs/vue3'
import ConversationCategoryDropdown from '@/Components/Dropdowns/ConversationCategoryDropdown.vue'

const { categories } = defineProps({
    categories: Array
})

const form = useForm({
    title: '',
    description: '',
    limit: 4,
    category: ''
})
const errors = ref({})

const submit = () => {
    form.post(route('conversation.store'), {
        preserveScroll: true,
        onError: (errs) => errors.value = errs
    })
}
</script>

<template>
    <AuthLayout>
        <section class="flex justify-center">
            <div class="px-1 py-6 w-full max-w-2xl">
                <Card styles="flex flex-col gap-4">
                    <Textbox 
                    v-model="form.title" 
                    label="Title"
                    placeholder="Write a title..."
                    :error="errors?.title"
                    />

                    <Textarea 
                    v-model="form.description"
                    label="Description"
                    placeholder="Write a description..."
                    :error="errors?.description"
                    />

                    <ConversationCategoryDropdown v-model="form.category" :categories="categories" :error="errors?.category" />

                    <NumberInput 
                    v-model="form.limit"
                    label="Member Limit"
                    :min="2"
                    :max="10"
                    :error="errors?.limit"
                    />

                    <Button styles="w-full" :on-click="submit">Post</Button>
                </Card>
            </div>
        </section>
    </AuthLayout>
</template>