<script setup>
defineProps({
    events: Object,
    event_id: String,
    selectedEvent: Number,
});

</script>

<template>
    <div class="flex mt-1">
        <div class="xl:w-96">
            <select class="form-select appearance-none block w-full px-3 py-2 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md transition ease-in-out shadow-sm m-0"
                    aria-label="Default select example"
                    id="eventList">
                <option :value="null" disabled>Liste der Events</option>
                <option v-for="(event, index) in events" :key="index" :value="event.id">
                    {{event.name}}
                </option>
            </select>
        </div>
    </div>
</template>
