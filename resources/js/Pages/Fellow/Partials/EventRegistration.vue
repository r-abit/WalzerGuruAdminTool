<script setup>
import EventSelection from '@/Pages/Fellow/Partials/EventSelection.vue';
import Switch from '@/Pages/Fellow/Partials/Switch.vue';
import JetButton from '@/Jetstream/Button.vue';

const props = defineProps({
    user: Object,
    events: Object,
    selectedEvent: Number,
    ageImportant: Boolean,
    heightImportant: Boolean,
    levelImportant: Boolean,
});

function registerEvent() {
    console.log('event ID: ' + document.querySelector('#eventList').options[document.querySelector('#eventList').selectedIndex].value);
    console.log('ageImportant: ' + props.ageImportant);
    console.log('heightImportant: ' + props.heightImportant);
    console.log('levelImportant: ' + props.levelImportant);
}
</script>

<template>
    <div class="px-4 py-3 bg-gray-50 sm:px-6 shadow sm:rounded-md sm:rounded-md">
        <EventSelection :events="$page.props.events" :selectedEvent="$page.props.selectedEvent" />
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
