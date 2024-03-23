<script setup>
import Card from '../Components/Generic/Card.vue'
import Button from '../Components/Generic/Button.vue'
import IconButton from '../Components/Generic/IconButton.vue'
import { faHouse } from '@fortawesome/free-solid-svg-icons';
import AuthLayout from '@/Layouts/AuthLayout.vue';
</script>

<template>
    <AuthLayout>
        <Card>
            test
            <Button variant="light" color="secondary">
                click me
            </Button>
            <IconButton :icon="faHouse" size="lg" />
        </Card>
    </AuthLayout>
</template>