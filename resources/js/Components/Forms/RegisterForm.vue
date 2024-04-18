<script setup>
import { useForm } from '@inertiajs/vue3'
import Button from '../../Components/Generic/Button.vue'
import Textbox from '../../Components/Generic/Textbox.vue'
import { ref } from 'vue'

const form = useForm({
    name: '',
    email: '',
    password: '',
})

const errors = ref({})

const submit = () => {
    form.post(route('register'), {
        onFinish: () => {
            form.reset('password')
        },
        onError: (errs) => {
            errors.value = errs
        }
    })
}
</script>

<template>
    <form class="flex flex-col gap-4" @submit.prevent="submit">
        <Textbox 
        v-model="form.name"
        name="name"
        label="Full Name"
        placeholder="Your name..."
        :error="errors.name"
        />

        <Textbox 
        v-model="form.email"
        name="email"
        label="Email"
        placeholder="Your email..."
        :error="errors.email"
        />

        <Textbox 
        v-model="form.password"
        name="password"
        label="Password"
        type="password"
        placeholder="Your password..."
        :error="errors.password"
        />

        <Button type="submit">Continue</Button>
    </form>
</template>