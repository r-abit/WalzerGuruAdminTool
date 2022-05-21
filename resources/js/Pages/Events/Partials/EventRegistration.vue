<script setup>
import EventSelection from '@/Pages/Events/Partials/EventSelection.vue'
import Switch from '@/Pages/Events/Partials/Switch.vue'
import JetButton from '@/Jetstream/Button.vue'
import {onBeforeMount, reactive} from 'vue'
import { Inertia } from '@inertiajs/inertia'
import Draggable from "vuedraggable"

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
            'event_id': parseInt(event_id),
            'age': props.ageImportant,
            'height': props.heightImportant,
            'level': props.levelImportant,
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
    if (props.user.height === null
        || props.user.birthday === null
        || props.user.dancing_level === null
        || props.user.previous_dancer === null
    ) {
        console.log('_--------------------___');
        console.log(vars.accessible);
        vars.accessible = false;
        console.log(vars.accessible);
        console.log('_--------------------___');
    }
    console.log(props.user.birthday);
    console.log(props.user.height);
    console.log(props.user.dancing_level);
    console.log(props.user.previous_dancer);
})

let users = reactive([
                {
                    "name": "John",
                    "id": 1
                },
                {
                    "name": "Joao",
                    "id": 2
                },
                {
                    "name": "Jean",
                    "id": 3
                },
                {
                    "name": "Gerard",
                    "id": 4
                }
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
            <span>Bitte geben Sie Ihre Präferenzen an.</span>
            <div class="grid grid-cols-3 gap-4 pt-2 items-center">



                <Draggable v-if="users" tag="ul" class="w-full max-w-md" v-model="users" item-key="id" :animation="200">
                    <template #item="user">
                        <div
                            class="p-4 mb-3 flex justify-between items-center bg-white shadow rounded-lg cursor-move"
                            :class="{ 'not-draggable': !enabled }">
                            {{user}}
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
                    <JetButton v-else class="bg-gray-700 active:bg-gray-700 hover:bg-gray-700 hover:cursor-default" >
                        <span>Bitte Profile ergänzen!</span>
                    </JetButton>
                </div>
            </div>
        </div>
    </div>
</template>
