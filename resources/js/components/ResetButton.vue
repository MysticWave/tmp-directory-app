<script setup>
import { SecondaryButton } from '@netblink/vue-components';
import { defineProps, reactive } from 'vue';

const props = defineProps({
    form: {
        type: Object,
        required: false,
        default: null,
    },
});

const isFormChanged = () => {
    return Object.keys(props.form.data()).some((key) => !!props.form[key]);
};
</script>

<template>
    <div v-if="isFormChanged()" class="mt-2 flex items-center max-sm:w-full max-sm:text-center">
        <SecondaryButton
            @click="form.reset()"
            type="button"
            class="rounded-md border border-gray-300 bg-white px-4 py-2 text-gray-500 hover:bg-gray-100 hover:text-gray-800 max-sm:w-full"
        >
            reset
        </SecondaryButton>
    </div>
</template>
