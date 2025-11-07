<script setup>
import { PromptType } from '@/Enum';
import { faSave } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { useForm } from '@inertiajs/vue3';
import { Input, SubmitButton } from '@netblink/vue-components';

const props = defineProps({
    prompt: {
        type: Object,
    },
});

const emit = defineEmits(['success']);
const form = useForm({
    name: props.prompt?.name || '',
    description: props.prompt?.description || null,
    type: props.prompt?.type || PromptType.AGGREGATE,
    prompt_text: props.prompt?.prompt_text || '',
});

const save = () => {
    if (props.prompt) {
        form.patch(route('prompts.update', props.prompt.id), {
            preserveScroll: true,
            onSuccess: () => emit('success'),
        });
        return;
    }

    form.post(route('prompts.store'), {
        preserveScroll: true,
        onError: () => form.reset('password'),
        onSuccess: () => emit('success'),
    });
};
</script>

<template>
    <form @submit.prevent="save" class="mt-6 space-y-6" autocomplete="off">
        <Input label="Name" v-model:form="form" field="name" required autofocus />
        <Input label="Description" v-model:form="form" field="description" type="textarea" />
        <Input label="Type" type="select" v-model:form="form" field="type" required>
            <option v-for="type in Object.values(PromptType)" :key="type" :value="type">
                {{ type.charAt(0).toUpperCase() + type.slice(1).toLowerCase() }}
            </option>
        </Input>
        <Input label="Prompt Text" type="textarea" v-model:form="form" field="prompt_text" required />

        <div class="flex items-center gap-4">
            <SubmitButton :form="form">
                <FontAwesomeIcon :icon="faSave" class="mr-2" />
                Save
            </SubmitButton>
        </div>
    </form>
</template>
