<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { bus } from "../../../bus";

const props = defineProps({
    clickable: Boolean,
    deletable: Boolean,
    organizers: Object,
    events: Object,
    user: Object,
});

const getOrganizer = (org_id) => {
    var x;
    props.organizers.some(
        organizer => {
            if (organizer.id === org_id) x = organizer.name;
        }
    );
    return x;
}

// Reference: https://thewebdev.info/2021/03/20/how-to-calculate-the-age-given-the-birth-date-in-yyyy-mm-dd-format-with-javascript/
const calculateAge = (birthday) => {
    const ageDifMs = Date.now() - new Date(birthday).getTime();
    const ageDate = new Date(ageDifMs);
    return Math.abs(ageDate.getUTCFullYear() - 1970);
}
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
                <th v-if="props.deletable && props.user.role !== 'user'" class="px-3 py-3 text-xs font-semibold text-center text-gray-600 uppercase tracking-wider">
                    Löschen
                </th>
                <th v-else-if="props.deletable && props.user.role === 'user'" class="px-3 py-3 text-xs font-semibold text-center text-gray-600 uppercase tracking-wider">
                    Abmelden
                </th>
            </tr>
        </thead>
        <tbody>
        <template v-if="props.user.role !=='user'">
            <tr v-for="event in events"
                class="text-sm text-left border-b border-gray-200 hover:bg-gray-100"
                :class="[props.clickable ? 'cursor-pointer' : 'cursor-default']"
            >
                <td class="px-3 py-3">
                    {{ event.event.name }}
                </td>
                <td class="px-3 py-3">
                    {{ getOrganizer(event.event.organizer_id) }}
                </td>

                <td class="px-3 py-3">
                    {{ event.event.participants }}
                </td>
                <td class="px-3 py-3">
                    {{ event.event.dresscode }}
                </td>
                <td class="px-3 py-3">
                    {{ event.event.date }}
                </td>
                <td v-if="props.deletable" class="px-3 py-3 flex justify-center items-center cursor-default">
                    <Link :href='(props.user.role === "user") ? "/dashboard" :  "/events"'
                          method="delete"
                          as="button"
                          preserve-scroll
                          :data="{ id: event.event.id, user:props.user }">
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
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                        </svg>
                    </Link>
                </td>
            </tr>
        </template>

        <template v-else v-for="event in events">
            <tr
                class="text-sm text-left border-t border-gray-200"
                :class="props.clickable ? 'cursor-pointer' : 'cursor-default'"
            >
                <td class="px-3" :class="event.hasOwnProperty('partner') ? 'pt-3' : 'py-3'">
                    {{ event.event.name }}
                </td>
                <td class="px-3" :class="event.hasOwnProperty('partner') ? 'pt-3' : 'py-3'">
                    {{ getOrganizer(event.event.organizer_id) }}
                </td>
                <td class="px-3" :class="event.hasOwnProperty('partner') ? 'pt-3' : 'py-3'">
                    {{ event.event.participants }}
                </td>
                <td class="px-3" :class="event.hasOwnProperty('partner') ? 'pt-3' : 'py-3'">
                    {{ event.event.dresscode }}
                </td>
                <td class="px-3" :class="event.hasOwnProperty('partner') ? 'pt-3' : 'py-3'">
                    {{ event.event.date }}
                </td>
                <td v-if="props.deletable" class="px-3 flex justify-center items-center cursor-default">
                    <Link :href='(props.user.role === "user") ? "/dashboard" :  "/events"'
                          method="delete"
                          as="button"
                          preserve-scroll
                          :data="{ id: event.event.id, user:props.user }">
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
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"
                                />
                        </svg>
                    </Link>
                </td>
            </tr>
            <tr
                v-if="event.hasOwnProperty('partner')"
                class="text-sm text-left -mt-5"
                :class="[props.clickable ? 'cursor-pointer' : 'cursor-default']"
            >
                <th colspan="6" class="columns-7 px-3 pb-3 font-normal">
                    <p><span class="font-bold">User: </span>{{ event.partner.username }}</p>
                    <p><span class="font-bold">Alter: </span>{{ calculateAge(event.partner.birthday) }}</p>
                    <p><span class="font-bold">Größe: </span>{{ event.partner.height }}</p>
                    <p><span class="font-bold">Tanzlevel: </span>{{ event.partner.dancing_level }}</p>
                </th>
            </tr>
        </template>

        </tbody>
    </table>
</template>
