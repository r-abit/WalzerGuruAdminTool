<script setup>
import EventSelection from '@/Pages/Events/Partials/EventSelection.vue'
import Switch from '@/Pages/Events/Partials/Switch.vue'
import JetButton from '@/Jetstream/Button.vue'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    user: Object,
    events: Object,
    error: String,
    success: String,
    selectedEvent: Number,
    ageImportant: Boolean,
    heightImportant: Boolean,
    levelImportant: Boolean,
});

function registerEvent() {
    let event_id = document.querySelector('#eventList').options[document.querySelector('#eventList').selectedIndex].value;
    Inertia.post(route('event.participation'), {
        'event_id': parseInt(event_id),
        'age': props.ageImportant,
        'height': props.heightImportant,
        'level': props.levelImportant,
    });
}
</script>

<template>
    <div class="px-4 py-3 bg-gray-50 sm:px-6 shadow sm:rounded-md sm:rounded-md">
        <EventSelection :events="$page.props.events" :selectedEvent="$page.props.selectedEvent" />
        <span v-if="$page.props.error !== ''" class="font-semibold">{{ $page.props.error }}</span>
        <span v-if="$page.props.success !== ''" class="font-semibold">{{ $page.props.success }}</span>
        <div class="pt-5">
            <span>Bitte geben Sie Ihre Präferenzen an.</span>
            <div class="grid grid-cols-3 gap-4 pt-2 items-center">
                <span class="col-start-1 col-end-2 font-semibold">Alter:</span>
                <div class="col-start-2 col-end-3">
                    <Switch v-model:checked="ageImportant"/>
                </div>

                <span class="col-start-1 col-end-2 font-semibold">Größe:</span>
                <div class="col-start-2 col-end-3">
                    <Switch v-model:checked="heightImportant"/>
                </div>

                <span class="col-start-1 col-end-2 font-semibold">Tanzlevel:</span>
                <div class="col-start-2 col-end-3">
                    <Switch v-model:checked="levelImportant"/>
                </div>
                <div class="col-start-3 col-end-4 justify-self-end">
                    <JetButton @click="registerEvent">
                        <span>Speichern</span>
                    </JetButton>
                </div>
            </div>
        </div>
    </div>
</template>
