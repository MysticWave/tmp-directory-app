<script setup>
import { faSave, faWrench } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useForm } from '@inertiajs/vue3';
import { NewModal, PrimaryButton, SubmitButton } from '@netblink/vue-components';
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
});
const save = () => {
    form.places_ids = collect(props.places).pluck('id').all();

    form.post(route('places.scrape-reviews'), {
        preserveScroll: true,
        onSuccess: () => handleSuccess(),
    });
};

const hasPlaceWithReviews = computed(() => {
    return props.places.some((place) => place.reviews_count > 0);
});
</script>

<template>
    <NewModal title="Scrape Reviews" v-model:open="isModalOpen">
        <template #trigger>
            <PrimaryButton :disabled="!places.length">
                <FontAwesomeIcon :icon="faWrench" class="mr-2" />
                Scrape Reviews ({{ places.length }})
            </PrimaryButton>
        </template>

        <template v-if="hasPlaceWithReviews">
            <div class="bg-warning-100 text-warning flex flex-col px-4 py-2">
                <p>Some of the places already have reviews.</p>
                <p>Do you want to continue?</p>
            </div>
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
