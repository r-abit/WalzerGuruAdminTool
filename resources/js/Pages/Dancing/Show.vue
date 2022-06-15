<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DancedUsers from '@/Pages/Dancing/Partials/DancedUsers.vue'

const props = defineProps({
    previous_events: Object,
    upcoming_events: Object,
    organizers: Object,
    events: Object,
    user: Object,
});

</script>

<template>
    <AppLayout title="Veranstalter">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tanzpartner
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-sm font-semibold text-gray-600 uppercase pl-2 pb-2">Gefallene Tänzer</p>
            <DancedUsers :user="$page.props.user" :list="$page.props.liked_users" />
            <p class="text-sm font-semibold text-gray-600 uppercase pl-2 pb-2 mt-5">Bereits getanzt mit</p>
            <DancedUsers :user="$page.props.user" :list="$page.props.all_users" />
        </div>

    </AppLayout>
</template>
