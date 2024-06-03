<script setup lang="ts">
import GuestLayout from '@/Layouts/GuestLayout.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head, useForm } from '@inertiajs/vue3';
import Card from "@/Components/Generic/Card.vue";
import Textbox from "@/Components/Generic/Textbox.vue";
import Button from "@/Components/Generic/Button.vue";
import GlobalLayout from "@/Layouts/GlobalLayout.vue";

const props = defineProps<{
    email: string;
    token: string;
}>();

const form = useForm({
    token: props.token,
    email: props.email,
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.store'), {
        onFinish: () => {
            form.reset('password', 'password_confirmation');
        },
    });
};
</script>

<template>
    <GlobalLayout>
        <Head title="Reset Password" />

        <div class="h-full p-1 flex flex-col items-center">
            <div class="flex justify-center my-2">
                <img src="/logo.svg" alt="logo" class="w-10" />
            </div>
            <h2 class="text-center text-2xl font-bold mb-6">Reset Password</h2>

            <Card styles="max-w-xl w-full">
                <form class="flex flex-col gap-4" @submit.prevent="submit">
                    <Textbox
                        v-model="form.password"
                        :error="form.errors.password"
                        type="password"
                        placeholder="Your new password..."
                        label="Password"
                    />

                    <Textbox
                        v-model="form.password_confirmation"
                        :error="form.errors.password_confirmation"
                        type="password"
                        placeholder="Your new password..."
                        label="Confirm password"
                    />

                    <Button type="submit">Confirm</Button>
                </form>
            </Card>
        </div>
    </GlobalLayout>
</template>
