<script setup lang="ts">
import Card from '@/Components/Generic/Card.vue'
import AuthLayout from '@/Layouts/AuthLayout.vue'
import Textbox from '@/Components/Generic/Textbox.vue'
import { ref } from 'vue'
import Textarea from '@/Components/Generic/Textarea.vue'
import NumberInput from '@/Components/Generic/NumberInput.vue'
import Button from '@/Components/Generic/Button.vue'
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    title: '',
    description: '',
    limit: 2
})
const errors = ref<any>({})

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

                    <NumberInput 
                    v-model="form.limit"
                    :default-value="form.limit"
                    label="Member Limit"
                    :min="1"
                    :max="10"
                    :error="errors?.limit"
                    />

                    <Button styles="w-full" :on-click="submit">Post</Button>
                </Card>
            </div>
        </section>
    </AuthLayout>
</template>