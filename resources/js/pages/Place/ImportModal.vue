<script setup>
import { faSave, faUpload } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useForm } from '@inertiajs/vue3';
import { Input, NewModal, PrimaryButton, SubmitButton } from '@netblink/vue-components';
import { ref } from 'vue';

const props = defineProps({
    squids: {
        type: Array,
    },
});

const isModalOpen = ref(false);
const handleSuccess = () => {
    isModalOpen.value = false;
};

const form = useForm({
    query: '',
    squid_id: null,
});

const save = () => {
    form.post(route('places.import'), {
        preserveScroll: true,
        onSuccess: () => handleSuccess(),
    });
};
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
            <Input
                label="Google Maps Link"
                v-model:form="form"
                field="query"
                required
                autofocus
                placeholder="https://www.google.com/maps/place/..."
            />
            <Input label="Squid" v-model:form="form" field="squid_id" required type="select">
                <option v-for="squid in props.squids" :key="squid.id" :value="squid.id">
                    {{ squid.name }}
                </option>
            </Input>

            <div class="flex items-center gap-4">
                <SubmitButton :form="form">
                    <FontAwesomeIcon :icon="faSave" class="mr-2" />
                    Import
                </SubmitButton>
            </div>
        </form>
    </NewModal>
</template>
