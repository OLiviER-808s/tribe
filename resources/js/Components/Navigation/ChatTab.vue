<script setup>
const { chat } = defineProps({
    chat: Object
})
</script>

<template>
    <div class="flex-1 overflow-hidden">
		<div class="flex justify-between items-center gap-2">
			<h4 class="whitespace-nowrap flex-1 overflow-hidden w-4/5">
				{{ chat.name }}
			</h4>
		</div>
	</div>
</template>