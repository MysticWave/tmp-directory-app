<script setup>
import HeaderBar from '@/components/HeaderBar.vue';
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <Head title="Home" />
    <HeaderBar>
        <ol class="inline-flex items-center">
            <li><Link :href="route('home')">Home</Link></li>
        </ol>
    </HeaderBar>

    <div class="p-4">Hello</div>
</template>
