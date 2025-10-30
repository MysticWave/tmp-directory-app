<script setup>
import { faSave, faWrench } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useForm } from '@inertiajs/vue3';
import { NewModal, PrimaryButton, SubmitButton } from '@netblink/vue-components';
import { ref } from 'vue';

const props = defineProps({
    place: {
        type: Object,
    },
});

const isModalOpen = ref(false);
const handleSuccess = () => {
    isModalOpen.value = false;
};

const form = useForm({});
const save = () => {
    form.post(route('places.scrape-reviews', props.place.id), {
        preserveScroll: true,
        onSuccess: () => handleSuccess(),
    });
};
</script>

<template>
    <NewModal title="Scrape Reviews" v-model:open="isModalOpen">
        <template #trigger>
            <PrimaryButton>
                <FontAwesomeIcon :icon="faWrench" />
            </PrimaryButton>
        </template>

        <template #footer>
            <div class="flex items-center gap-4">
                <SubmitButton :form="form" @click="save">
                    <FontAwesomeIcon :icon="faSave" class="mr-2" />
                    Scrape Reviews
                </SubmitButton>
            </div>
        </template>
    </NewModal>
</template>
