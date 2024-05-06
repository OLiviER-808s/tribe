<script setup>
import Sidebar from '@/Components/Navigation/Sidebar.vue'
import Header from '@/Components/Navigation/Header.vue'
import GlobalLayout from './GlobalLayout.vue'
import MobileHeader from '@/Components/Navigation/MobileHeader.vue'
import MobileFooter from '@/Components/Navigation/MobileFooter.vue'
import { useIsHandheld } from '@/Composables/useIsHandheld'

const props = defineProps({
	showMobileHeader: {
		type: Boolean,
		default: true
	},
	showMobileFooter: {
		type: Boolean,
		default: true
	}
})

const { isHandheld } = useIsHandheld()
</script>

<template>
    <GlobalLayout>
		<div class="h-full flex flex-col" v-if="isHandheld">
			<MobileHeader v-if="showMobileHeader" />

			<div class="flex-1 flex flex-col">
				<div class="flex-grow h-0 overflow-auto" id="scrollable-content">
					<slot />
				</div>
			</div>

			<MobileFooter v-if="showMobileFooter" />
		</div>
		<div class="h-full flex flex-col p-1" v-else>
			<Header>
				<template #right-section>
					<slot name="header-right-section" />
				</template>
			</Header>

			<div class="flex-1 flex gap-1">
				<Sidebar />

				<slot name="additional-sidebar" />
		
				<div class="flex-1 flex flex-col">
					<div class="flex-grow h-0 overflow-auto" id="scrollable-content">
						<slot />
					</div>
				</div>

				<slot name="right-sidebar" />
			</div>
		</div>
	</GlobalLayout>
</template>