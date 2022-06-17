<script setup>
import { Link } from '@inertiajs/inertia-vue3'
import axios from 'axios'
import { Inertia } from '@inertiajs/inertia'

const props = defineProps({
    user: Object,
    list: Object,
});

const like = (user_id, user_liked) => {

    Inertia.get('/dancing/like',
        {
            partner_id: user_id,
            partner_liked: user_liked
        },
        {
            preserveState: true,
            preserveScroll: true
        });
}

// Reference: https://thewebdev.info/2021/03/20/how-to-calculate-the-age-given-the-birth-date-in-yyyy-mm-dd-format-with-javascript/
const calculateAge = (birthday) => {
    const ageDifMs = Date.now() - new Date(birthday).getTime();
    const ageDate = new Date(ageDifMs);
    return Math.abs(ageDate.getUTCFullYear() - 1970);
}
</script>

<template>
    <table class="mt-5 md:mt-0 md:col-span-2 px-4 py-3 bg-gray-50 sm:px-6 shadow sm:rounded-md sm:rounded-md leading-normal">
        <thead>
            <tr class="border-b-0 text-left border-gray-300 bg-gray-200">
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    User
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Tanzlevel
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-center text-gray-600 uppercase tracking-wider">
                    Größe
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-center text-gray-600 uppercase tracking-wider">
                    Alter
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-center text-gray-600 uppercase tracking-wider">
                    Wunschpartner
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="user in props.list"
                class="text-sm text-left border-b border-gray-200 hover:bg-gray-100"
                :class="[props.clickable ? 'cursor-pointer' : 'cursor-default']"
            >
                <td class="px-3 py-3">
                    {{ user.username }}
                </td>
                <td class="px-3 py-3">
                    {{ user.dancing_level }}
                </td>
                <td class="px-3 py-3 text-center">
                    {{ user.height }}
                </td>
                <td class="px-3 py-3 text-center">
                    {{ calculateAge(user.birthday) }}
                </td>
                <td class="px-3 py-3 flex justify-center items-center">
                    <svg
                        @click="like(user.id, user.liked)"
                        :class="user.liked  ? 'fill-red-500 hover:fill-red-200' : 'fill-red-200 hover:fill-red-500'"
                        class="h-6 w-6 cursor-pointer"
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                    >
                        <path
                            d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
                        />
                    </svg>
                </td>
            </tr>

        </tbody>
    </table>
</template>
