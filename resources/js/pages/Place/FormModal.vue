<script setup>
import { faPencil, faPlus, faSave } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useForm } from '@inertiajs/vue3';
import { Input, NewModal, PrimaryButton, SubmitButton } from '@netblink/vue-components';
import { ref } from 'vue';
import { PlaceType } from '@/Enum';

const props = defineProps({
    place: {
        type: Object,
    },
});

const isModalOpen = ref(false);
const handleSuccess = () => {
    isModalOpen.value = false;
};

const form = useForm({
    name: props.place?.name || '',
    type: props.place?.type || '',
    description: props.place?.description || '',
    address_line_1: props.place?.address_line_1 || '',
    address_line_2: props.place?.address_line_2 || '',
    city: props.place?.city || '',
    region: props.place?.region || '',
    postcode: props.place?.postcode || '',
    country: props.place?.country || '',
    latitude: props.place?.latitude || '',
    longitude: props.place?.longitude || '',
    phone: props.place?.phone || '',
    website: props.place?.website || '',
    email: props.place?.email || '',
    // opening_hours: props.place?.opening_hours || '',
    rating: props.place?.rating || 0.0,
    user_ratings_total: props.place?.user_ratings_total || 0,
    // tags: props.place?.tags || [],
    is_verified: props.place?.is_verified || false,
    source: props.place?.source || '',
});

const save = () => {
    if (props.place) {
        form.patch(route('places.update', props.place.id), {
            preserveScroll: true,
            onSuccess: () => handleSuccess(),
        });
        return;
    }

    form.post(route('places.store'), {
        preserveScroll: true,
        onSuccess: () => handleSuccess(),
    });
};
</script>

<template>
    <NewModal :title="place ? 'Edit Place' : 'Create Place'" v-model:open="isModalOpen">
        <template #trigger>
            <PrimaryButton>
                <FontAwesomeIcon :icon="faPencil" v-if="place" />
                <template v-else>
                    <FontAwesomeIcon :icon="faPlus" class="mr-2" />
                    Create Place
                </template>
            </PrimaryButton>
        </template>

        <form @submit.prevent="save" class="mt-6 grid grid-cols-2 gap-2" autocomplete="off">
            <Input label="Name" v-model:form="form" field="name" required autofocus />
            <Input label="Type" v-model:form="form" field="type" required type="select">
                <option v-for="_type in PlaceType" :key="_type" :value="_type">
                    {{ _type }}
                </option>
            </Input>
            <Input label="Description" class="col-span-2" v-model:form="form" field="description" type="textarea" />

            <Input label="Address Line 1" v-model:form="form" field="address_line_1" required />
            <Input label="Address Line 2" v-model:form="form" field="address_line_2" />

            <Input label="City" v-model:form="form" field="city" required />
            <Input label="Region" v-model:form="form" field="region" />
            <Input label="Postcode" v-model:form="form" field="postcode" />
            <Input label="Country" v-model:form="form" field="country" required />

            <hr class="col-span-2 my-6 border-gray-200" />
            <Input label="Latitude" v-model:form="form" field="latitude" type="number" step="any" required />
            <Input label="Longitude" v-model:form="form" field="longitude" type="number" step="any" required />

            <hr class="col-span-2 my-6 border-gray-200" />

            <Input label="Phone" v-model:form="form" field="phone" />
            <Input label="Website" v-model:form="form" field="website" />
            <Input label="Email" v-model:form="form" field="email" type="email" />
            <div></div>
            <!-- <Input label="Opening Hours" v-model:form="form" field="opening_hours" /> -->

            <hr class="col-span-2 my-6 border-gray-200" />

            <Input label="Rating" v-model:form="form" field="rating" type="number" step="any" min="0" max="5" />
            <Input label="User Ratings Total" v-model:form="form" field="user_ratings_total" type="number" />

            <!-- <Input label="Tags" v-model:form="form" field="tags" /> -->
            <Input rightDescription="Is Verified" class="col-span-2" v-model:form="form" field="is_verified" type="checkbox" required />
            <Input label="Source" class="col-span-2" v-model:form="form" field="source" required />

            <div class="flex items-center gap-4">
                <SubmitButton :form="form">
                    <FontAwesomeIcon :icon="faSave" class="mr-2" />
                    Save
                </SubmitButton>
            </div>
        </form>
    </NewModal>
</template>
