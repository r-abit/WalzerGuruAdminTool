<script setup>
import EventsList from '@/Pages/Events/Partials/EventsList.vue'
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    previous_events: Object,
    upcoming_events: Object,
    organizers: Object,
    events: Object,
    user: Object,
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
            <p class="text-sm font-semibold text-gray-600 uppercase pl-2 pb-2">Aktuelle Anmeldungen</p>
            <EventsList :user="$page.props.user"
                        :events="$page.props.previous_events"
                        :organizers="$page.props.organizers"
                        :deletable="true"
                        class="mb-5" />

            <p class="text-sm font-semibold text-gray-600 uppercase pl-2 pb-2">Vergangene Events</p>
            <EventsList :user="$page.props.user"
                        :events="$page.props.upcoming_events"
                        :organizers="$page.props.organizers"
                        :deletable="false" />
        </div>

    </AppLayout>
</template>
