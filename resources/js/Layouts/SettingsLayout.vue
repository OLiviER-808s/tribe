<script setup>
import Card from '@/Components/Generic/Card.vue'
import SettingsList from '@/Components/Settings/SettingsList.vue'
import AuthLayout from './AuthLayout.vue'
</script>

<template>
    <AuthLayout>
        <template #additional-sidebar>
            <Card styles="w-72 h-full" padding="p-0">
                <SettingsList />
            </Card>
        </template>

        <slot />
    </AuthLayout>
</template>

