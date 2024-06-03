<script setup lang="ts">
import EntryLayout from "@/Layouts/EntryLayout.vue";
import {useIsHandheld} from "@/Composables/useIsHandheld";
import Card from "@/Components/Generic/Card.vue";
import ForgotPasswordForm from "@/Components/Forms/ForgotPasswordForm.vue";

defineProps<{
    status?: string;
}>()

const { isHandheld } = useIsHandheld()
</script>

<template>
    <EntryLayout title="Forgot Password">
        <section class="flex justify-center flex-1 overflow-auto h-0">
            <div class="max-w-sm flex-grow py-6">
                <Card v-if="isHandheld">
                    <ForgotPasswordForm :status="status" />
                </Card>
                <ForgotPasswordForm v-else :status="status" />
            </div>
        </section>
    </EntryLayout>
</template>
