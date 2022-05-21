<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import { bus } from "../../../bus";

const props = defineProps({
    organizers: Object,
});

const updateForm = (organizer) => {
    bus.emit('organizer.form', {
        organizer: {
            ...organizer,
        }
    })
}
</script>


<template>
    <table class="mt-5 md:mt-0 md:col-span-2 px-4 py-3 bg-gray-50 sm:px-6 shadow sm:rounded-md sm:rounded-md min-w-full leading-normal">
        <thead>
            <tr class="border-b-0 text-left border-gray-300 bg-gray-200">
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Veranstalter
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Straße mit Hausnummer
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Postleitzahl
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Ort
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Tel.
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    E-Mail
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-center text-gray-600 uppercase tracking-wider">
                    Löschen
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="organizer in organizers" class="text-sm text-left border-b border-gray-200 hover:bg-gray-100">
                <td class="px-3 py-3 cursor-pointer" @click="updateForm(organizer)">
                    {{ organizer.name }}
                </td>
                <td class="px-3 py-3 cursor-pointer" @click="updateForm(organizer)">
                    {{ organizer.street }}
                </td>
                <td class="px-3 py-3 cursor-pointer" @click="updateForm(organizer)">
                    {{ organizer.zip }}
                </td>
                <td class="px-3 py-3 cursor-pointer" @click="updateForm(organizer)">
                    {{ organizer.city }}
                </td>
                <td class="px-3 py-3 cursor-pointer" @click="updateForm(organizer)">
                    {{ organizer.phone }}
                </td>
                <td class="px-3 py-3 cursor-pointer" @click="updateForm(organizer)">
                    {{ organizer.email }}
                </td>
                <td class="px-3 py-3 flex justify-center items-center ">
                    <Link href="/organizers" method="delete" as="button" preserve-scroll :data="{ id: organizer.id }">
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
