<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import EventRegistration from '@/Pages/Fellow/Partials/EventRegistration.vue'

const props = defineProps({
});

</script>

<template>
    <AppLayout title="Veranstalter">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tanzpartner
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <EventRegistration />
        </div>

    </AppLayout>
</template>
