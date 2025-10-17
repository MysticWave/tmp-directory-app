<script setup>
import { faSave } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useForm } from '@inertiajs/vue3';
import { Input, SubmitButton } from '@netblink/vue-components';

const props = defineProps({
    user: {
        type: Object,
    },
});

const emit = defineEmits(['success']);
const form = useForm({
    name: props.user?.name || '',
    email: props.user?.email || '',
    password: undefined,
});

const save = () => {
    if (props.user) {
        form.patch(route('users.update', props.user.id), {
            preserveScroll: true,
            onSuccess: () => emit('success'),
        });
        return;
    }

    form.post(route('users.store'), {
        preserveScroll: true,
        onError: () => form.reset('password'),
        onSuccess: () => emit('success'),
    });
};
</script>

<template>
    <form @submit.prevent="save" class="mt-6 space-y-6" autocomplete="off">
        <Input label="Name" v-model:form="form" field="name" required autofocus />
        <Input label="Email" v-model:form="form" field="email" type="text" required autocomplete="off" />
        <Input label="Password" v-if="!props.user" v-model:form="form" field="password" type="password" required autocomplete="off" />

        <div class="flex items-center gap-4">
            <SubmitButton :form="form">
                <FontAwesomeIcon :icon="faSave" class="mr-2" />
                Save
            </SubmitButton>
        </div>
    </form>
</template>
