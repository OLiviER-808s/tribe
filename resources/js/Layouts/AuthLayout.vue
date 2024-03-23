<template>
    <div class="h-full flex flex-col p-1">
        <div class="flex-1 flex gap-1">
			<slot name="additional-sidebar" />
	
			<div class="flex-1 flex flex-col">
				<div class="flex-grow h-0 overflow-auto" id="scrollable-content">
					<slot />
				</div>
			</div>

			<slot name="right-sidebar" />
		</div>
    </div>
</template>