<script setup>
import EventsList from '@/Pages/Events/Partials/EventsList.vue'
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    user: Object,
    events: Object,
    organizers: Object,
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
            <EventsList :user="$page.props.user"
                        :events="$page.props.events"
                        :organizers="$page.props.organizers"
                        :deletable="true" />
        </div>

    </AppLayout>
</template>
