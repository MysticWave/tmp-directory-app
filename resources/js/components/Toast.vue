<script setup>
import { Toast } from '@/Helpers';
import { usePage } from '@inertiajs/vue3';
import { onMounted } from 'vue';

const props = defineProps({
    variant: { type: String, default: 'success' },
    class: { type: String, default: '' },
    body: { type: String, default: '' },
    clearProps: { type: Boolean, default: false },
});
const page = usePage();

onMounted(() => {
    if (props.body === '') return;
    Toast.fire({
        icon: props.variant,
        html: props.body,
        didClose: () => {
            if (props.clearProps) {
                page.props.flash[props.variant] = null;
            }
        },
    });
});
</script>
<template></template>
