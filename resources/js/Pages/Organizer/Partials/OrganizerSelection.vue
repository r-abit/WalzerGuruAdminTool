<script setup>
import {onMounted} from "vue"

const props = defineProps({
    user: Object,
    organizers: Object,
    organizer_id: String,
});

onMounted(() => {
    if (props.user.role === 'organizer'){
        document.getElementById('organizer_id').value = props.user.organizer_id;
        document.getElementById('organizer_id').disabled = true;
    }
})
</script>

<template>
    <div class="flex justify-center mt-1">
        <div class="xl:w-96">
            <select class="form-select appearance-none block w-full px-3 py-2 text-base font-normal text-gray-700 bg-white bg-clip-padding bg-no-repeat border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md transition ease-in-out shadow-sm m-0"
                    aria-label="Default select example" v-model="organizer_id" id="organizer_id">
                <option v-for="(organizer, index) in organizers" :key="index" :value="organizer.id" >
                    {{organizer.name}}
                </option>
            </select>
        </div>
    </div>
</template>
