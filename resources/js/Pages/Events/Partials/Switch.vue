<!--
Reference (08.05.2021): https://thomaslombart.com/how-to-build-reusable-and-accessible-switch-vue
-->

<script setup>
const props = defineProps({
    checked: {
        type: Boolean,
        required: true,
    },
    inheritAttrs: false,
});
</script>

<template>
    <label class="container w-16">
        <input
            v-bind="$attrs"
            class="input"
            type="checkbox"
            :checked="checked"
            @change="$emit('update:checked', $event.target.checked)"
        />
        <span class="switch"></span>
    </label>
</template>

<style scoped>
    .container {
        cursor: pointer;
        display: flex;
        align-items: center;
    }

    /* Visually hide the checkbox input */
    .input {
        position: absolute;
        width: 1px;
        height: 1px;
        padding: 0;
        margin: -1px;
        overflow: hidden;
        clip: rect(0, 0, 0, 0);
        white-space: nowrap;
        border-width: 0;
    }
    .switch {
        --switch-container-width: 50px;
        --switch-size: calc(var(--switch-container-width) / 2);

        /* Vertically center the inner circle */
        display: flex;
        align-items: center;
        position: relative;
        height: var(--switch-size);
        flex-basis: var(--switch-container-width);

        /* Make the container element rounded */
        border-radius: var(--switch-size);
        background-color: #e2e8f0;
    }

    .switch::before {
        content: "";
        position: absolute;

        /* Move a little bit the inner circle to the right */
        left: 1px;
        height: calc(var(--switch-size) - 4px);
        width: calc(var(--switch-size) - 4px);

        /* Make the inner circle fully rounded */
        border-radius: 9999px;
        background-color: white;
    }

    .input:checked + .switch {
        /* Teal background */
        background-color: #4fd1c5;
    }

    .input:checked + .switch::before {
        border-color: #4fd1c5;

        /* Move the inner circle to the right */
        transform: translateX(
            calc(var(--switch-container-width) - var(--switch-size))
        );
    }
</style>
