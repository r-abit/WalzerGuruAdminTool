<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import {onMounted} from "vue";

const props = defineProps({
    user: Object,
    events: Object,
    organizers: Object,
});

const updateForm = (event) => {
    if (props.user.role === 'user') {
        document.getElementById('eventList').value = event.id;
        return;
    }

    let time = event.date.split(' ')[1].split(':');
    document.getElementById('id').value = event.id;
    document.getElementById('organizer_id').value = event.organizer_id;
    document.getElementById('name').value = event.name;
    document.getElementById('participants').value = event.participants;
    document.getElementById('date').value = event.date.split(' ')[0];
    document.getElementById('time').value = time[0] + ':' + time[1];
    document.getElementById('dresscode').value = event.dresscode;
    document.getElementById('street').value = event.street;
    document.getElementById('zip').value = event.zip;
    document.getElementById('city').value = event.city;
    document.getElementById('description').value = event.description;
}

const getOrganizer = (org_id) => {
    var x;
    props.organizers.some(
        organizer => {
            if (organizer.id === org_id) x = organizer.name;
        }
    );
    return x;
}

onMounted(() => {
    if (props.user.role === 'user') {
        document.querySelector('#eventList').options[0].selected = true;
    }
})
</script>

<template>
    <table class="mt-5 md:mt-0 md:col-span-2 px-4 py-3 bg-gray-50 sm:px-6 shadow sm:rounded-md sm:rounded-md min-w-full leading-normal">
        <thead>
            <tr class="border-b-0 text-left border-gray-300 bg-gray-200">
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Event
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Veranstalter
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Teilnehmer
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Dresscode
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Datum und Uhrzeit
                </th>
                <th v-if="$page.props.user.role !== 'user'" class="px-3 py-3 text-xs font-semibold text-center text-gray-600 uppercase tracking-wider">
                    Löschen
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="event in events" class="text-sm text-left border-b border-gray-200 hover:bg-gray-100">
                <td class="px-3 py-3 cursor-pointer" @click="updateForm(event)">
                    {{ event.name }}
                </td>
                <td class="px-3 py-3 cursor-pointer" @click="updateForm(event)">
                    {{ getOrganizer(event.organizer_id) }}
                </td>
                <td class="px-3 py-3 cursor-pointer" @click="updateForm(event)">
                    {{ event.participants }}
                </td>
                <td class="px-3 py-3 cursor-pointer" @click="updateForm(event)">
                    {{ event.dresscode }}
                </td>
                <td class="px-3 py-3 cursor-pointer" @click="updateForm(event)">
                    {{ event.date }}
                </td>
                <td v-if="$page.props.user.role !== 'user'" class="px-3 py-3 flex justify-center items-center ">
                    <Link href="/events" method="delete" as="button" preserve-scroll :data="{ id: event.id }">
                        <svg
                             xmlns="http://www.w3.org/2000/svg"
                             class="p-1 rounded-full h-7 w-7 hover:bg-red-300 cursor-pointer"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor"
                             stroke-width="2">
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                    </Link>
                </td>
            </tr>

        </tbody>
    </table>
</template>
