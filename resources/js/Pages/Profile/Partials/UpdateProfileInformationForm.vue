<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm } from '@inertiajs/inertia-vue3';
import JetButton from '@/Jetstream/Button.vue';
import JetFormSection from '@/Jetstream/FormSection.vue';
import JetInput from '@/Jetstream/Input.vue';
import JetInputError from '@/Jetstream/InputError.vue';
import JetLabel from '@/Jetstream/Label.vue';
import JetActionMessage from '@/Jetstream/ActionMessage.vue';
import JetSecondaryButton from '@/Jetstream/SecondaryButton.vue';
import JetDropdown from '@/Jetstream/Dropdown.vue';

const props = defineProps({
    user: Object,

    dancingLevels: Object,
});

const form = useForm({
    _method: 'PUT',
    username: props.user.username,
    firstname: props.user.firstname,
    lastname: props.user.lastname,
    height: props.user.height,
    birthday: props.user.birthday,
    dancing_level: props.user.dancing_level,
    email: props.user.email,
    photo: null,
});

const photoPreview = ref(null);
const photoInput = ref(null);

const updateProfileInformation = () => {
    if (photoInput.value) {
        form.photo = photoInput.value.files[0];
    }

    form.post(route('user-profile-information.update'), {
        errorBag: 'updateProfileInformation',
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
    <JetFormSection @submitted="updateProfileInformation">
        <template #title>
            Profile Information
        </template>

        <template #description>
            Update your account's profile information and email address.
        </template>

        <template #form>
            <!-- Profile Photo -->
            <div v-if="$page.props.jetstream.managesProfilePhotos" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input
                    ref="photoInput"
                    type="file"
                    class="hidden"
                    @change="updatePhotoPreview"
                >

                <JetLabel for="photo" value="Photo" />

                <!-- Current Profile Photo -->
                <div v-show="! photoPreview" class="mt-2">
                    <img :src="user.profile_photo_url" :alt="user.name" class="rounded-full h-20 w-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div v-show="photoPreview" class="mt-2">
                    <span
                        class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                        :style="'background-image: url(\'' + photoPreview + '\');'"
                    />
                </div>

                <JetSecondaryButton class="mt-2 mr-2" type="button" @click.prevent="selectNewPhoto">
                    Select A New Photo
                </JetSecondaryButton>

                <JetSecondaryButton
                    v-if="user.profile_photo_path"
                    type="button"
                    class="mt-2"
                    @click.prevent="deletePhoto"
                >
                    Remove Photo
                </JetSecondaryButton>

                <JetInputError :message="form.errors.photo" class="mt-2" />
            </div>

            <!-- Firstname -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="firstname" value="Firstname" />
                <JetInput
                    id="firstname"
                    v-model="form.firstname"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <JetInputError :message="form.errors.firstname" class="mt-2" />
            </div>

            <!-- Lastname -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="lastname" value="Lastname" />
                <JetInput
                    id="lastname"
                    v-model="form.lastname"
                    type="text"
                    class="mt-1 block w-full"
                    required
                />
                <JetInputError :message="form.errors.lastname" class="mt-2" />
            </div>

            <!-- Birthday -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="birthday" value="Birthday" />
                <JetInput
                    id="birthday"
                    v-model="form.birthday"
                    type="date"
                    class="mt-1 block w-full"
                    required
                />
                <JetInputError :message="form.errors.birthday" class="mt-2" />
            </div>

            <!-- Height -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="height" value="Height (in cm)" />
                <JetInput
                    id="height"
                    v-model="form.height"
                    type="number"
                    class="mt-1 block w-full"
                    required
                />
                <JetInputError :message="form.errors.height" class="mt-2" />
            </div>

            <!-- Dancing level -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="dancing_level" value="Dancing level" />
                <select class="w-full border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" v-model="form.dancing_level">
                    <option v-for="(dancingLevel, index) in dancingLevels" :key="index" :value="index">
                        {{dancingLevel}}
                    </option>
                </select>
            </div>

            <!-- Username -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="username" value="Username" />
                <JetInput
                    id="username"
                    v-model="form.username"
                    type="text"
                    class="mt-1 block w-full bg-gray-200/50"
                    disabled
                />
                <JetInputError :message="form.errors.username" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="col-span-6 sm:col-span-4">
                <JetLabel for="email" value="Email" />
                <JetInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                />
                <JetInputError :message="form.errors.email" class="mt-2" />
            </div>
        </template>

        <template #actions>
            <JetActionMessage :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </JetActionMessage>

            <JetButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </JetButton>
        </template>
    </JetFormSection>
</template>
