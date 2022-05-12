<script setup>
import EventRegistration from './Partials/EventRegistration.vue'
import UpsertEventsForm from "./Partials/UpsertEventsForm";
import AppLayout from '@/Layouts/AppLayout.vue';
import EventsList from "./Partials/EventsList";
import {onMounted} from "vue";

const props = defineProps({
    user: Object,
    events: Object,
    organizers: Object,
});

onMounted(() => {
    if (props.user.role === 'user') {
        document.querySelector('#eventList').options[0].selected = true;
    }
})
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

            <EventsList
                        :user="$page.props.user"
                        :events="$page.props.events"
                        :clickable="true"
                        :deletable="$page.props.user.role !== 'user'"
                        :organizers="$page.props.organizers" />
        </div>

    </AppLayout>
</template>

<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
  <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
</svg>
