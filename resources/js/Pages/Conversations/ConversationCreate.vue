<script setup>
import Card from '@/Components/Generic/Card.vue'
import AuthLayout from '@/Layouts/AuthLayout.vue'
import Textbox from '@/Components/Generic/Textbox.vue'
import { ref } from 'vue'

const title = ref('')
</script>

<template>
    <AuthLayout>
        <section class="flex justify-center">
            <div class="px-1 py-6 w-full max-w-2xl">

                <Card>
                    <Textbox 
                    v-model="title" 
                    placeholder="Title" />
                </Card>
            </div>
        </section>
    </AuthLayout>
</template>