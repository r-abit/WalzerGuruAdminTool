<script setup>
import { useForm } from '@inertiajs/inertia-vue3';

const props = defineProps({
    user: Object,
});

const form = useForm({
    _method: 'PUT',
    organizerName: '',
    description: '',
    website: '',
    street: '',
    zip: '',
    city: '',
    phone: '',
    fax: '',
    uidNumber: '',
});

const insertOrganizerInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('organizers.insert'), {
        errorBag: 'insertOrganizerInformation',
        preserveScroll: true,
        onSuccess: () => clearPhotoFileInput(),
    });
};
</script>


<template>
    <table class="mt-5 md:mt-0 md:col-span-2 px-4 py-3 bg-gray-50 sm:px-6 shadow sm:rounded-md sm:rounded-md min-w-full leading-normal">
        <thead>
            <tr class="border-b-2 text-left border-gray-200 bg-gray-100">
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
            </tr>
        </thead>
        <tbody>
            <tr class="text-sm text-left border-b border-gray-200">
                <td class="px-3 py-3">
                    Peter Parker
                </td>
                <td class="px-3 py-3">
                    Upsipuhpsidupsi 21
                </td>
                <td class="px-3 py-3">
                    1234
                </td>
                <td class="px-3 py-3">
                    New-York
                </td>
                <td class="px-3 py-3">
                    +123456789
                </td>
            </tr>

        </tbody>
    </table>
</template>
