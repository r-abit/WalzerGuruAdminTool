<script setup>
import EventSelection from '@/Pages/Events/Partials/EventSelection.vue'
import Switch from '@/Pages/Events/Partials/Switch.vue'
import JetButton from '@/Jetstream/Button.vue'
import { Inertia } from '@inertiajs/inertia'
import {onBeforeMount, ref} from 'vue'
import Draggable from 'vuedraggable'

const props = defineProps({
    user: Object,
    events: Object,
    error: String,
    success: String,
    selectedEvent: Number,
    ageImportant: Boolean,
    heightImportant: Boolean,
    levelImportant: Boolean,
    previousDancers: Boolean,
});

function registerEvent() {
    let event_id = document.querySelector('#eventList').options[document.querySelector('#eventList').selectedIndex].value;

    Inertia.visit('/event/participate', {
        method: 'post',
        data: {
            'event_id': ~~event_id,
            'priorities': users.value,
            'previous_dancer': props.previousDancers,
        },
        replace: true,
        preserveState: false,
    })
}

var vars = {
    accessible: true,
}

onBeforeMount(() =>{
    if (!props.user.height || !props.user.birthday || !props.user.dancing_level || !props.user.gender) {
        vars.accessible = false;
    }
})

const users = ref([
                {"Alter": 'age'},
                {"Größe": 'height'},
                {"Tanzlevel": 'level'},
            ]);

const onEdit = (user) => {
            alert(`Editing ${user.name}`);
            }
const onDelete = (user) => {
            alert(`Deleting ${user.name}`);
        }


</script>

<template>
    <div class="px-4 py-3 bg-gray-50 sm:px-6 shadow sm:rounded-md sm:rounded-md">
        <EventSelection :events="$page.props.events" :selectedEvent="$page.props.selectedEvent" />
        <span v-if="$page.props.error !== ''" class="font-semibold">{{ $page.props.error }}</span>
        <span v-if="$page.props.success !== ''" class="font-semibold">{{ $page.props.success }}</span>
        <div class="pt-5">
            <span class="font-semibold">Bitte sortieren Sie absteigend Ihre Präferenzen
                <span class="font-normal">(Liste verschiebbar)</span>
            </span>
            <div class="grid grid-cols-3 gap-4 pt-2 items-center">

                <Draggable v-if="users" tag="ul" class="min-w-min ml-4 px-2 w-52 bg-gray-100 rounded-lg" v-model="users" item-key="id" :animation="200">
                    <template #item="user">
                        <div
                            class="p-4 my-2 flex justify-between text-center bg-white shadow rounded-lg cursor-move"
                            :key="user.element.id">
                            <span class="w-full">
                            {{Object.keys(user.element)[0]}}
                            </span>
                        </div>
                    </template>
                </Draggable>

                <span class="col-start-1 col-end-2 font-semibold">Frühere Tanzpartner bevorzugen:</span>
                <div class="col-start-2 col-end-3">
                    <Switch v-model:checked="previousDancers"/>
                </div>

                <div class="col-start-3 col-end-4 justify-self-end">
                    <JetButton v-if="vars.accessible" @click="registerEvent">
                        <span>Speichern</span>
                    </JetButton>
                    <JetButton v-else class="bg-red-400 active:bg-red-400 hover:bg-red-400 hover:cursor-default" >
                        <span>Bitte Profile ergänzen!</span>
                    </JetButton>
                </div>
            </div>
        </div>
    </div>
</template>
