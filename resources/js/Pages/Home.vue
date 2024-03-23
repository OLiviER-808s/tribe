<script setup>
import Card from '../Components/Generic/Card.vue'
import Button from '../Components/Generic/Button.vue'
import IconButton from '../Components/Generic/IconButton.vue'
import { faHouse } from '@fortawesome/free-solid-svg-icons';
</script>

<template>
    <div class="p-6">
        <Card>
            test
            <Button variant="light" color="secondary">
                click me
            </Button>
            <IconButton :icon="faHouse" size="lg" />
        </Card>
    </div>
</template>