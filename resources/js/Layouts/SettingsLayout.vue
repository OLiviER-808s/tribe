<script setup>
import Card from '@/Components/Generic/Card.vue'
import SettingsList from '@/Components/Settings/SettingsList.vue'
import AuthLayout from './AuthLayout.vue'
import { provide, computed } from 'vue'
import IconButton from '@/Components/Generic/IconButton.vue'
import { faArrowLeft } from '@fortawesome/free-solid-svg-icons'
import {Head, usePage} from '@inertiajs/vue3'
import { useIsHandheld } from '@/Composables/useIsHandheld'

provide('headerTitle', 'Settings')

const page = usePage()
const isIndexPage = computed(() => page.url === '/settings')

const { isHandheld } = useIsHandheld()
</script>

<template>
    <AuthLayout :show-mobile-header="isIndexPage">
        <Head title="Settings" />

        <template #additional-sidebar>
            <Card styles="w-72 h-full" padding="p-0">
                <SettingsList />
            </Card>
        </template>

        <div v-if="!isIndexPage && isHandheld" class="pt-4 px-4 flex items-center gap-2">
			<IconButton variant="subtle" color="base" :icon="faArrowLeft" size="lg" :href="route('settings')" />

            <slot name="title" />
		</div>

        <slot />
    </AuthLayout>
</template>

