<script setup>
import UserForm from '@/components/forms/UserForm.vue';
import { faPencil } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { NewModal, PrimaryButton } from '@netblink/vue-components';
import { ref } from 'vue';

const props = defineProps({
    user: {
        type: Object,
    },
});

const isModalOpen = ref(false);
const handleSuccess = () => {
    isModalOpen.value = false;
};
</script>

<template>
    <NewModal :title="user ? 'Edit User' : 'Create User'" v-model:open="isModalOpen">
        <template #trigger>
            <PrimaryButton>
                <FontAwesomeIcon :icon="faPencil" v-if="user" />
                <template v-else>
                    <FontAwesomeIcon :icon="faPlus" class="mr-2" />
                    Create User
                </template>
            </PrimaryButton>
        </template>

        <UserForm :user="props.user" @success="handleSuccess" />
    </NewModal>
</template>
