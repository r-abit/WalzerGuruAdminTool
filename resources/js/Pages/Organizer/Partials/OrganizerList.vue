<script setup>

const props = defineProps({
    organizers: Object,
});

const goToHome = (organizer) => {
    document.getElementById('id').value = organizer.id;
    document.getElementById('name').value = organizer.name;
    document.getElementById('email').value = organizer.email;
    document.getElementById('website').value = organizer.website;
    document.getElementById('uid_number').value = organizer.uid_number;
    document.getElementById('street').value = organizer.street;
    document.getElementById('zip').value = organizer.zip;
    document.getElementById('city').value = organizer.city;
    document.getElementById('phone').value = organizer.phone;
    document.getElementById('description').value = organizer.description;
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
            </tr>
        </thead>
        <tbody>
            <tr v-for="organizer in organizers" @click="goToHome(organizer)" class="text-sm text-left border-b border-gray-200 cursor-pointer hover:bg-gray-100">
                <td class="px-3 py-3">
                    {{ organizer.name }}
                </td>
                <td class="px-3 py-3">
                    {{ organizer.street }}
                </td>
                <td class="px-3 py-3">
                    {{ organizer.zip }}
                </td>
                <td class="px-3 py-3">
                    {{ organizer.city }}
                </td>
                <td class="px-3 py-3">
                    {{ organizer.phone }}
                </td>
                <td class="px-3 py-3">
                    {{ organizer.email }}
                </td>
            </tr>

        </tbody>
    </table>
</template>
