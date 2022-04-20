<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import JetSectionBorder from '@/Jetstream/SectionBorder.vue';
import UpsertOrganizerForm from '@/Pages/Organizer/Partials/UpsertOrganizerForm.vue';
import OrganizerList from '@/Pages/Organizer/Partials/OrganizerList.vue';

defineProps({
    confirmsTwoFactorAuthentication: Boolean,
    sessions: Array,
});
</script>

<template>
    <AppLayout title="Veranstalter">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Veranstalter
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">

            <UpsertOrganizerForm :user="$page.props.user" :dancingLevels="$page.props.dancingLevels" />

            <JetSectionBorder />

            <OrganizerList :user="$page.props.user" :dancingLevels="$page.props.dancingLevels" />

        </div>

    </AppLayout>
</template>
