<script setup>
const { message } = defineProps({
    message: Object
})
</script>

<template>
    <div class="flex flex-row-reverse py-1">
        <div class="max-w-md w-auto">
            <div class="flex flex-row-reverse">
				<div class="bg-primary/30 rounded-lg p-1 rounded-br-none flex gap-1">
					<div class="p-1">
						{{ message.content }}
					</div>
	
					<div class="text-xs text-secondary-text flex flex-col justify-end">
                        10:08
                    </div>			
				</div>
			</div>
        </div>
    </div>
</template>