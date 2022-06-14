<script setup>
import { Link } from '@inertiajs/inertia-vue3'

const props = defineProps({
    user: Object,
});
const users = ["a", "b"];
</script>

<template>
    <table class="mt-5 md:mt-0 md:col-span-2 px-4 py-3 bg-gray-50 sm:px-6 shadow sm:rounded-md sm:rounded-md min-w-full leading-normal">
        <thead>
            <tr class="border-b-0 text-left border-gray-300 bg-gray-200">
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    User
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Tanzlevel
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Größe
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Alter
                </th>
                <th class="px-3 py-3 text-xs font-semibold text-center text-gray-600 uppercase tracking-wider">
                    Status
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="user in $page.props.liked_users"
                class="text-sm text-left border-b border-gray-200 hover:bg-gray-100"
                :class="[props.clickable ? 'cursor-pointer' : 'cursor-default']"
            >
                <td class="px-3 py-3">
                    {{ }}
                </td>
                <td class="px-3 py-3">
                    🤦‍
                </td>
                <td class="px-3 py-3">
                    🐰
                </td>
                <td class="px-3 py-3">
                    💩
                </td>
                <td class="px-3 py-3">
                    😊
                </td>
                <td v-if="props.deletable" class="px-3 py-3 flex justify-center items-center cursor-default">
                    🤷‍
                </td>
            </tr>

        </tbody>
    </table>
</template>
