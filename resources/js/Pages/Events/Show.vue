<script setup>
import EventRegistration from '@/Pages/Fellow/Partials/EventRegistration.vue'
import UpsertEventsForm from "./Partials/UpsertEventsForm";
import AppLayout from '@/Layouts/AppLayout.vue';
import EventsList from "./Partials/EventsList";
import {onMounted} from "vue";

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
                Events
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <UpsertEventsForm v-if="$page.props.user.role !== 'user'"
                              :organizers="$page.props.organizers"
                              class="mb-5" />
            <EventRegistration v-else
                               class="mb-5" />

            <EventsList v-if="$page.props.user.role === 'organizer'"
                        :user="$page.props.user"
                        :events="$page.props.events.filter(i => i.organizer_id === props.user.organizer_id)"
                        :organizers="$page.props.organizers" />
            <EventsList v-else
                        :user="$page.props.user"
                        :events="$page.props.events"
                        :organizers="$page.props.organizers" />
        </div>

    </AppLayout>
</template>
