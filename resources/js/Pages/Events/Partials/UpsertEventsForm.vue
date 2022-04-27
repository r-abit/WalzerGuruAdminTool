<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import EventSelection from '@/Pages/Events/Partials/EventSelection.vue';

const props = defineProps({
    organizers: Object,
});

const form = useForm({
    id: null,
    organizer_id: null,
    name: null,
    participants: null,
    date: null,
    time: null,
    dresscode: null,
    street: null,
    zip: null,
    city: null,
    description: null,
});

const upsertEventInformation = () => {
    form.post(route('events.upsert'), {
        errorBag: 'upsertEventInformation',
        preserveScroll: true,
        onSuccess: () => clearFrom(),
    });
};

function clearFrom() {
    form.reset();
}

function updateFormValues() {
    form.id = document.getElementById('id').value;
    form.organizer_id = document.getElementById('organizer_id').value;
    form.name = document.getElementById('name').value;
    form.participants = document.getElementById('participants').value;
    form.date = document.getElementById('date').value;
    form.time = document.getElementById('time').value;
    form.dresscode = document.getElementById('dresscode').value;
    form.street = document.getElementById('street').value;
    form.zip = document.getElementById('zip').value;
    form.city = document.getElementById('city').value;
    form.description = document.getElementById('description').value;
}
</script>

<template>
    <div class="mt-5 md:mt-0 md:col-span-2">
        <form @submit.prevent="upsertEventInformation">
            <div class="grid grid-cols-8 gap-4 px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-md sm:rounded-md">
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

                <!-- Event name -->
                <div class="col-span-2">
                    <JetLabel for="name" value="Event-Titel" class="text-left"/>
                    <JetInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <JetInputError :message="form.errors.name" class="mt-2" />
                </div>

                <!-- Organizer selection -->
                <div class="col-span-2">
                    <JetLabel for="name" value="Veranstalter" class="text-left"/>
                    <EventSelection :organizers="$page.props.organizers" :organizer_id="form.organizer_id" />
                    <JetInputError :message="form.errors.name" class="mt-2" />
                </div>

                <!-- Number of participants -->
                <div class="col-span-2">
                    <JetLabel for="participants" value="Anzahl der Teilnehmer" class="text-left"/>
                    <JetInput
                        id="participants"
                        v-model="form.participants"
                        type="number"
                        class="mt-1 block w-full"
                        required
                    />
                    <JetInputError :message="form.errors.participants" class="mt-2" />
                </div>

                <!-- Dresscode -->
                <div class="col-span-2">
                    <JetLabel for="dresscode" value="Dresscode" class="text-left"/>
                    <JetInput
                        id="dresscode"
                        v-model="form.dresscode"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <JetInputError :message="form.errors.dresscode" class="mt-2" />
                </div>

                <!-- Date -->
                <div class="col-span-1">
                    <JetLabel for="date" value="Datum" class="text-left"/>
                    <JetInput
                        id="date"
                        v-model="form.date"
                        type="date"
                        class="mt-1 block w-full py-1"
                        required
                    />
                    <JetInputError :message="form.errors.date" class="mt-2" />
                </div>

                <!-- Time -->
                <div class="col-span-1">
                    <JetLabel for="time" value="Uhrzeit" class="text-left"/>
                    <JetInput
                        id="time"
                        v-model="form.time"
                        type="time"
                        class="mt-1 block w-full py-1"
                        required
                    />
                    <JetInputError :message="form.errors.time" class="mt-2" />
                </div>

                <!-- Street -->
                <div class="col-span-2">
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
                <div class="col-span-2">
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
                <div class="col-span-2">
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

                <!-- Description -->
                <div class="col-span-6">
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

                <div class="col-span-2 self-end mb-2">
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
