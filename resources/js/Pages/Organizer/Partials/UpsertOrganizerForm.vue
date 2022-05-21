<script setup>
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import { useForm } from '@inertiajs/inertia-vue3';
import { onMounted, onUnmounted } from 'vue';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetLabel from '@/Jetstream/Label.vue';
import { bus } from "../../../bus";

const props = defineProps({
});

const form = useForm({
    id: null,
    name: null,
    email: null,
    website: null,
    uid_number: null,
    street: null,
    zip: null,
    city: null,
    phone: null,
    description: null,
});

const upsertOrganizerInformation = () => {
    form.post(route('organizers.upsert'), {
        errorBag: 'upsertOrganizerInformation',
        preserveScroll: true,
        preserveState: false,
        onSuccess: () => clearFrom(),
    });
};

function clearFrom() {
    form.defaults({});
    form.reset();
}

function updateFormValues() {
    bus.emit('organizer.form', {
        organizer: {
            ...organizer,
        }
    })
}

onMounted(() => {
    bus.on('organizer.form',({organizer}) => {
        form.defaults(organizer);
        form.reset();
    })
})

onUnmounted(() => {
    bus.off('organizer.form')
})
</script>

<template>
    <div class="mt-5 md:mt-0 md:col-span-2">
        <form @submit.prevent="upsertOrganizerInformation">
            <div class="grid grid-cols-4 gap-4 px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-md sm:rounded-md">
                <!-- Id (hidden but needed to update existing organizers -->
                <div class="hidden">
                    <JetLabel for="id" value="Id" class="text-left"/>
                    <JetInput
                        id="id"
                        v-model="form.id"
                        type="text"
                        class="mt-1 block w-full"
                    />
                    <JetInputError :message="form.errors.id" class="mt-2" />
                </div>

                <!-- Organizer name -->
                <div>
                    <JetLabel for="name" value="Veranstalter" class="text-left"/>
                    <JetInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <JetInputError :message="form.errors.name" class="mt-2" />
                </div>

                <!-- Email -->
                <div>
                    <JetLabel for="email" value="E-Mail" class="text-left"/>
                    <JetInput
                        id="email"
                        v-model="form.email"
                        type="email"
                        class="mt-1 block w-full"
                        required
                    />
                    <JetInputError :message="form.errors.email" class="mt-2" />
                </div>

                <!-- Website -->
                <div class="">
                    <JetLabel for="website" value="Website" class="text-left"/>
                    <JetInput
                        id="website"
                        v-model="form.website"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <JetInputError :message="form.errors.website" class="mt-2" />
                </div>

                <!-- uidNumber -->
                <div>
                    <JetLabel for="uid_number" value="UID-Nummer" class="text-left"/>
                    <JetInput
                        id="uid_number"
                        v-model="form.uid_number"
                        type="text"
                        class="mt-1 block w-full justify-self-end"
                        required
                    />
                    <JetInputError :message="form.errors.uid_number" class="mt-2" />
                </div>

                <!-- Street -->
                <div>
                    <JetLabel for="street" value="Straße mit Hausnummer" class="text-left"/>
                    <JetInput
                        id="street"
                        v-model="form.street"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <JetInputError :message="form.errors.street" class="mt-2" />
                </div>

                <!-- Zip -->
                <div>
                    <JetLabel for="zip" value="Postleitzahl" class="text-left"/>
                    <JetInput
                        id="zip"
                        v-model="form.zip"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <JetInputError :message="form.errors.zip" class="mt-2" />
                </div>

                <!-- City -->
                <div>
                    <JetLabel for="city" value="Ort" class="text-left"/>
                    <JetInput
                        id="city"
                        v-model="form.city"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <JetInputError :message="form.errors.city" class="mt-2" />
                </div>

                <!-- Phone -->
                <div>
                    <JetLabel for="phone" value="Tel." class="text-left"/>
                    <JetInput
                        id="phone"
                        v-model="form.phone"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <JetInputError :message="form.errors.phone" class="mt-2" />
                </div>

                <!-- Description -->
                <div class="col-span-3">
                    <JetLabel for="description" value="Beschreibung" class="text-left"/>
                    <textarea
                        id="description"
                        v-model="form.description"
                        type="textarea"
                        class="mt-1 w-full h-15 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                        required
                    />
                    <JetInputError :message="form.errors.description" class="mt-2" />
                </div>

                <div class="col-span-1 self-end mb-2">
                    <JetActionMessage :on="form.recentlySuccessful" class="mr-3">
                        Gespeichert.
                    </JetActionMessage>
                    <JetButton @click="updateFormValues">
                        <span v-if="form.processing"  class="flex items-center">
                            <svg class="animate-spin h-4 w-4 mr-3" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            in Bearbeitung
                        </span>
                        <span v-else>Speichern</span>
                    </JetButton>

                </div>
            </div>
        </form>
    </div>
</template>
