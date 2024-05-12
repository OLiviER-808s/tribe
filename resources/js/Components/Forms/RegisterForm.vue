<script setup>
import { useForm } from '@inertiajs/vue3'
import Button from '../../Components/Generic/Button.vue'
import Textbox from '../../Components/Generic/Textbox.vue'

const form = useForm({
    name: '',
    email: '',
    password: '',
})

const submit = () => form.post(route('register'))
</script>

<template>
    <form class="flex flex-col gap-4" @submit.prevent="submit">
        <Textbox 
        v-model="form.name"
        name="name"
        placeholder="Full Name"
        :error="form.errors.name"
        />

        <Textbox 
        v-model="form.email"
        name="email"
        placeholder="Email"
        :error="form.errors.email"
        />

        <Textbox 
        v-model="form.password"
        name="password"
        type="password"
        placeholder="Password"
        :error="form.errors.password"
        />

        <Button type="submit">Continue</Button>
    </form>
</template>