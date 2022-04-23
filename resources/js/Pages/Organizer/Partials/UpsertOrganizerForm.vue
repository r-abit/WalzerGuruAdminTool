<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';

const props = defineProps({
    id: String,
    name: String,
    email: String,
    website: String,
    uid_number: String,
    street: String,
    zip: String,
    city: String,
    phone: String,
    description: String,
});

const form = useForm({
    _method: 'POST',
    id: props.id,
    name: props.name,
    email: props.email,
    website: props.website,
    uid_number: props.uid_number,
    street: props.street,
    zip: props.zip,
    city: props.city,
    phone: props.phone,
    description: props.description,
});

const upsertOrganizerInformation = () => {
    form.post(route('organizers.upsert'), {
        errorBag: 'upsertOrganizerInformation',
        preserveScroll: true,
        // onSuccess: () => upsertOrganizerInformation(),
    });
};
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
                        required
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
                    <JetLabel for="description" value="Beschreibeung" class="text-left"/>
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

                    <JetButton @click="submit()" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Speichern
                    </JetButton>
                </div>
            </div>
        </form>
    </div>
</template>
