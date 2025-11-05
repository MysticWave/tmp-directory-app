<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Table, Th, Td, Tbody, Thead } from '@netblink/vue-components';
import HeaderBar from '@/components/HeaderBar.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    placeImport: {
        type: Object,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <Head title="Place Import" />
    <HeaderBar>
        <ol class="inline-flex items-center">
            <Link :href="route('place-imports.index')">Place Imports</Link>
            <span class="mx-2">/</span>
            <span>{{ placeImport.data.id }}</span>
        </ol>
    </HeaderBar>

    <Table class="my-4">
        <Tbody>
            <tr>
                <Th class="w-40">ID</Th>
                <Td>{{ placeImport.data.id }}</Td>
            </tr>
            <tr>
                <Th>Type</Th>
                <Td>{{ placeImport.data.type }}</Td>
            </tr>
            <tr>
                <Th>Status</Th>
                <Td>{{ placeImport.data.status }}</Td>
            </tr>
            <tr>
                <Th>Created At</Th>
                <Td>{{ placeImport.data.created_at }}</Td>
            </tr>
            <tr>
                <Th>Query</Th>
                <Td v-if="placeImport.data.query">{{ placeImport.data.query }}</Td>
                <Td v-else>
                    <div v-for="(value, key) in placeImport.data.params" :key="key">
                        <strong>{{ key }}:</strong>
                        {{ value }}
                    </div>
                </Td>
            </tr>
        </Tbody>
    </Table>

    <Table>
        <Thead>
            <tr>
                <Th orderBy="id">Place ID</Th>
                <Th orderBy="name">Name</Th>
                <Th>Reviews</Th>
            </tr>
        </Thead>
        <Tbody>
            <tr v-if="!placeImport.data.places?.length">
                <Td colspan="3" class="text-center">No places found for this import.</Td>
            </tr>

            <tr v-for="place in placeImport.data.places" :key="place.id">
                <Td>
                    {{ place.id }}
                </Td>
                <Td>
                    {{ place.name }}
                </Td>
                <Td>
                    <Link :href="route('reviews.index', { place_id: place.id })" class="underline" v-if="place.reviews_count">
                        {{ place.reviews_count }}
                    </Link>
                    <span v-else>0</span>
                </Td>
            </tr>
        </Tbody>
    </Table>
</template>
