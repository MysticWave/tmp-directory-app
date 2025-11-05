<script setup>
import { PlaceImportTaskType, PlaceImportType } from '@/Enum';
import { faSave, faUpload } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useForm } from '@inertiajs/vue3';
import { Input, NewModal, PrimaryButton, SubmitButton } from '@netblink/vue-components';
import { onMounted, ref } from 'vue';

const isModalOpen = ref(false);
const handleSuccess = () => {
    isModalOpen.value = false;
};

const form = useForm({
    query: '',
    type: PlaceImportType.PLACE,
    task_type: PlaceImportTaskType.URL,
    category: null,
    country: null,
    city: null,
});

const save = () => {
    console.log(form);
    form.post(route('place-imports.store'), {
        preserveScroll: true,
        onSuccess: () => handleSuccess(),
    });
};

const squidDetails = ref(null);
const getSquidDetails = () => {
    fetch(route('place-imports.squid-details'))
        .then((response) => response.json())
        .then((data) => {
            squidDetails.value = data;
        });
};

onMounted(getSquidDetails);
</script>

<template>
    <NewModal title="Import Place" v-model:open="isModalOpen">
        <template #trigger>
            <PrimaryButton>
                <FontAwesomeIcon :icon="faUpload" class="mr-2" />
                Import Place
            </PrimaryButton>
        </template>

        <form @submit.prevent="save" class="mt-6 space-y-6" autocomplete="off">
            <div class="flex w-full items-center">
                <button
                    type="button"
                    class="bg-primary/20 hover:bg-primary/50 disabled:bg-primary/70 w-full py-2 text-center disabled:pointer-events-none"
                    @click="form.task_type = PlaceImportTaskType.URL"
                    :disabled="form.task_type == PlaceImportTaskType.URL"
                >
                    URL
                </button>
                <button
                    type="button"
                    class="bg-primary/20 hover:bg-primary/50 disabled:bg-primary/70 w-full py-2 text-center disabled:pointer-events-none"
                    @click="form.task_type = PlaceImportTaskType.PARAMS"
                    :disabled="form.task_type == PlaceImportTaskType.PARAMS"
                >
                    Advanced
                </button>
            </div>

            <template v-if="form.task_type == PlaceImportTaskType.URL">
                <Input
                    label="Google Maps Link"
                    v-model:form="form"
                    field="query"
                    required
                    autofocus
                    placeholder="https://www.google.com/maps/place/..."
                />
            </template>

            <template v-else>
                <p v-if="squidDetails">Max Results: {{ squidDetails.results }}</p>
                <Input label="Category" v-model:form="form" field="category" required autofocus placeholder="e.g., restaurant, cafe, museum" />
                <Input label="Country" v-model:form="form" field="country" required placeholder="e.g., United States, Canada, United Kingdom" />
                <Input label="City" v-model:form="form" field="city" required placeholder="e.g., New York, London, Paris" />
            </template>
        </form>

        <template #footer>
            <div class="flex items-center gap-4">
                <SubmitButton :form="form" @click="save">
                    <FontAwesomeIcon :icon="faSave" class="mr-2" />
                    Import
                </SubmitButton>
            </div>
        </template>
    </NewModal>
</template>
