<script setup>
import Sidebar from '@/Components/Navigation/Sidebar.vue'
import Header from '@/Components/Navigation/Header.vue'
import GlobalLayout from './GlobalLayout.vue'
</script>

<template>
    <GlobalLayout>
		<div class="h-full flex flex-col p-1">
			<Header />

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