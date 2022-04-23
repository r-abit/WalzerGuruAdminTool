<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import JetSectionBorder from '@/Jetstream/SectionBorder.vue';
import OrganizerList from '@/Pages/Organizer/Partials/OrganizerList.vue';
import UpsertOrganizerForm from '@/Pages/Organizer/Partials/UpsertOrganizerForm.vue';

defineProps({
    user: Object,
    organizer: Object,
    organizers: Object,
});
</script>

<script>
export default {
    data() {
        return {
            organizer: {
                id: Number,
                name: String,
                email: String,
                website: String,
                uid_number: String,
                street: String,
                zip: String,
                city: String,
                phone: String,
                description: String,
            }
        }
    }
}
</script>

<template>
    <AppLayout title="Veranstalter">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Veranstalter
            </h2>
        </template>

        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <UpsertOrganizerForm :user="$page.props.user" :organizer="$page.props.organizer" />

            <JetSectionBorder />

            <OrganizerList :user="$page.props.user" :organizers="$page.props.organizers" />
        </div>

    </AppLayout>
</template>
