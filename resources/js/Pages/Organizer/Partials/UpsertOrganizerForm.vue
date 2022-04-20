<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/inertia-vue3';
import JetButton from '@/Jetstream/Button.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';

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
    logo: null,
    uidNumber: '',
});

const photoPreview = ref(null);
const photoInput = ref(null);

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

const selectNewPhoto = () => {
    photoInput.value.click();
};

const updatePhotoPreview = () => {
    const photo = photoInput.value.files[0];

    if (! photo) return;

    const reader = new FileReader();

    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };

    reader.readAsDataURL(photo);
};

const deletePhoto = () => {
    Inertia.delete(route('current-user-photo.destroy'), {
        preserveScroll: true,
        onSuccess: () => {
            photoPreview.value = null;
            clearPhotoFileInput();
        },
    });
};

const clearPhotoFileInput = () => {
    if (photoInput.value?.value) {
        photoInput.value.value = null;
    }
};
</script>

<script>
export default {
    data: function() {
        return {
            dancing_level: this.user.dancing_level,
        }
    },
}
</script>

<template>
    <div class="mt-5 md:mt-0 md:col-span-2">
        <form @submitted="insertOrganizerInformation">
            <div class="grid grid-cols-4 gap-4 px-4 py-3 bg-gray-50 text-right sm:px-6 shadow sm:rounded-md sm:rounded-md">

                <!-- Organizer name -->
                <div>
                    <JetLabel for="organizerName" value="Veranstalter" class="text-left"/>
                    <JetInput
                        id="organizerName"
                        v-model="form.organizerName"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <JetInputError :message="form.errors.organizerName" class="mt-2" />
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

                <!-- Description -->
                <div class="col-span-2">
                    <JetLabel for="description" value="Beschreibeung" class="text-left"/>
                    <textarea
                        id="description"
                        v-model="form.description"
                        type="textarea"
                        class="mt-1 w-full h-12 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm"
                        required
                    />
                    <JetInputError :message="form.errors.description" class="mt-2" />
                </div>

                <!-- uidNumber -->
                <div>
                    <JetLabel for="uidNumber" value="UID-Nummer" class="text-left"/>
                    <JetInput
                        id="uidNumber"
                        v-model="form.uidNumber"
                        type="text"
                        class="mt-1 block w-full justify-self-end"
                        required
                    />
                    <JetInputError :message="form.errors.uidNumber" class="mt-2" />
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

                <!-- Fax -->
                <div>
                    <JetLabel for="fax" value="Fax" class="text-left"/>
                    <JetInput
                        id="fax"
                        v-model="form.fax"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <JetInputError :message="form.errors.fax" class="mt-2" />
                </div>

                <!-- Fax -->
                <div class="col-span-2 self-end">
                    <JetActionMessage :on="form.recentlySuccessful" class="mr-3">
                        Gespeichert.
                    </JetActionMessage>

                    <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Speichern
                    </JetButton>
                </div>

            </div>
        </form>
    </div>
</template>
