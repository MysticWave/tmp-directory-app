<script setup>
import { faRobot, faSave, faWrench } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useForm } from '@inertiajs/vue3';
import { NewModal, PrimaryButton, Select2ajax, SubmitButton } from '@netblink/vue-components';
import { computed, ref } from 'vue';
import { collect } from '@mysticwave/collect-me';

const props = defineProps({
    places: {
        type: Object,
    },
});

const isModalOpen = ref(false);
const handleSuccess = () => {
    isModalOpen.value = false;
};

const form = useForm({
    places_ids: [],
    prompt_id: null,
});
const save = () => {
    form.places_ids = collect(props.places).pluck('id').all();

    form.post(route('places.process-reviews'), {
        preserveScroll: true,
        onSuccess: () => handleSuccess(),
    });
};

const hasPlaceWithoutReviews = computed(() => {
    return props.places.some((place) => !place.reviews_count);
});
</script>

<template>
    <NewModal title="Process Reviews" v-model:open="isModalOpen">
        <template #trigger>
            <PrimaryButton :disabled="!places.length">
                <FontAwesomeIcon :icon="faRobot" class="mr-2" />
                Process Reviews ({{ places.length }})
            </PrimaryButton>
        </template>

        <Select2ajax
            id="prompt_id"
            label="Select Prompt"
            placeholder="Select Prompt"
            v-model:form="form"
            field="prompt_id"
            :url="route('prompts.find')"
        />

        <template v-if="hasPlaceWithoutReviews">
            <div class="bg-warning-100 text-warning mt-5 flex flex-col px-4 py-2">
                <p>Some of the places do not have reviews.</p>
                <p>Those places won't be processed.</p>
            </div>
        </template>

        <template #footer>
            <div class="flex items-center gap-4">
                <SubmitButton :form="form" @click="save" :disabled="!form.prompt_id">
                    <FontAwesomeIcon :icon="faRobot" class="mr-2" />
                    Process Reviews
                </SubmitButton>
            </div>
        </template>
    </NewModal>
</template>
