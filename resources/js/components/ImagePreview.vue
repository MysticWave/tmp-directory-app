<script setup>
import { PlaceImportType } from '@/Enum';
import { faSave, faUpload } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useForm } from '@inertiajs/vue3';
import { Input, NewModal, PrimaryButton, SubmitButton } from '@netblink/vue-components';
import { ref } from 'vue';

const props = defineProps({
    src: {
        type: String,
        required: true,
    },
    alt: {
        type: String,
        default: '',
    },
    class: {
        type: String,
        default: '',
    },
});
</script>

<template>
    <NewModal class="max-h-[95vh] !p-0">
        <template #trigger>
            <img v-bind="props" class="cursor-pointer" />
        </template>

        <img :src="props.src" :alt="props.alt" class="h-auto w-full" />
    </NewModal>
</template>
