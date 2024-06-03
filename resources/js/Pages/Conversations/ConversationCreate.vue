<script setup>
import Card from '@/Components/Generic/Card.vue'
import AuthLayout from '@/Layouts/AuthLayout.vue'
import Textbox from '@/Components/Generic/Textbox.vue'
import Textarea from '@/Components/Generic/Textarea.vue'
import NumberInput from '@/Components/Generic/NumberInput.vue'
import Button from '@/Components/Generic/Button.vue'
import {Head, useForm} from '@inertiajs/vue3'
import ConversationTopicDropdown from '@/Components/Dropdowns/ConversationTopicDropdown.vue'

const form = useForm({
    title: '',
    description: '',
    limit: 4,
    topic: ''
})

const submit = () => form.post(route('conversation.store'))
</script>

<template>
    <AuthLayout>
        <Head title="New Conversation" />

        <section class="flex justify-center">
            <div class="px-1 py-6 w-full max-w-2xl">
                <Card styles="flex flex-col gap-4">
                    <Textbox
                    v-model="form.title"
                    label="Title"
                    placeholder="Write a title..."
                    :error="form.errors?.title"
                    />

                    <Textarea
                    v-model="form.description"
                    label="Description"
                    placeholder="Write a description..."
                    :error="form.errors?.description"
                    :maxlength="300"
                    />

                    <ConversationTopicDropdown v-model="form.topic" :error="form.errors?.topic" />

                    <NumberInput
                    v-model="form.limit"
                    label="Member Limit"
                    :min="2"
                    :max="10"
                    :error="form.errors?.limit"
                    />

                    <Button styles="w-full" :on-click="submit">Post</Button>
                </Card>
            </div>
        </section>
    </AuthLayout>
</template>
