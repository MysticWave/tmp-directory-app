<script setup>
import PromptForm from '@/components/forms/PromptForm.vue';
import { faPencil } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { NewModal, PrimaryButton } from '@netblink/vue-components';
import { ref } from 'vue';

const props = defineProps({
    prompt: {
        type: Object,
    },
});

const isModalOpen = ref(false);
const handleSuccess = () => {
    isModalOpen.value = false;
};
</script>

<template>
    <NewModal :title="prompt ? 'Edit Prompt' : 'Create Prompt'" v-model:open="isModalOpen">
        <template #trigger>
            <PrimaryButton>
                <FontAwesomeIcon :icon="faPencil" v-if="prompt" />
                <template v-else>
                    <FontAwesomeIcon :icon="faPlus" class="mr-2" />
                    Create Prompt
                </template>
            </PrimaryButton>
        </template>

        <PromptForm :prompt="props.prompt" @success="handleSuccess" />
    </NewModal>
</template>
