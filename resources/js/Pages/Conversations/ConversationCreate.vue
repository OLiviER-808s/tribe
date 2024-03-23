<script setup lang="ts">
import Card from '@/Components/Generic/Card.vue'
import AuthLayout from '@/Layouts/AuthLayout.vue'
import Textbox from '@/Components/Generic/Textbox.vue'
import { ref } from 'vue'
import Textarea from '@/Components/Generic/Textarea.vue'
import NumberInput from '@/Components/Generic/NumberInput.vue'
import Button from '@/Components/Generic/Button.vue'
import { router } from '@inertiajs/vue3'

const title = ref('')
const description = ref('')
const limit = ref(2)

const submit = async () => {
    await router.post(route('conversation.store'), { title, description, limit })
}
</script>

<template>
    <AuthLayout>
        <section class="flex justify-center">
            <div class="px-1 py-6 w-full max-w-2xl">
                <Card styles="flex flex-col gap-4">
                    <Textbox 
                    v-model="title" 
                    label="Title"
                    placeholder="Write a title..."
                    />

                    <Textarea 
                    v-model="description"
                    label="Description"
                    placeholder="Write a description..."
                    />

                    <NumberInput 
                    v-model="limit"
                    label="Member Limit"
                    :min="1"
                    :max="10"
                    />

                    <Button styles="w-full" :on-click="submit">Post</Button>
                </Card>
            </div>
        </section>
    </AuthLayout>
</template>