<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Table, Thead, Th, Td, Tbody } from '@netblink/vue-components';
import HeaderBar from '@/components/HeaderBar.vue';
import { Head, Link } from '@inertiajs/vue3';
import FormModal from './FormModal.vue';
import ImportModal from './ImportModal.vue';
import ScrapeReviewsModal from './ScrapeReviewsModal.vue';

defineProps({
    places: {
        type: Object,
        required: true,
    },
    imports: {
        type: Object,
        required: false,
    },
});

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <Head title="Places" />
    <HeaderBar>
        <ol class="inline-flex items-center">
            <Link :href="route('places.index')">Places</Link>
        </ol>

        <template #actions>
            <ImportModal />
            <FormModal />
        </template>
    </HeaderBar>

    <!-- <SearchForm /> -->
    <Table :pagination="places.meta" :total="places.meta.total">
        <Thead>
            <tr>
                <Th orderBy="id">#</Th>
                <Th orderBy="name">Name</Th>
                <Th>Reviews</Th>
                <Th class="!w-0"></Th>
            </tr>
        </Thead>
        <Tbody data="places">
            <tr v-for="place in places.data" :key="place.id">
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
                <Td>
                    <div class="flex items-center gap-1">
                        <FormModal :place="place" />
                        <ScrapeReviewsModal :place="place" />
                    </div>
                </Td>
            </tr>
        </Tbody>
    </Table>

    <hr class="my-10" />
    <p>Imports</p>

    <Table :pagination="imports.meta" :total="imports.meta.total">
        <Thead>
            <tr>
                <Th orderBy="id">#</Th>
                <Th orderBy="query">Query</Th>
                <Th>Status</Th>
                <Th>Created</Th>
            </tr>
        </Thead>
        <Tbody data="imports">
            <tr v-for="_import in imports.data" :key="_import.id">
                <Td>
                    {{ _import.id }}
                </Td>
                <Td>
                    {{ _import.query }}
                </Td>
                <Td>
                    {{ _import.status }}
                </Td>
                <Td>
                    {{ _import.created_at }}
                </Td>
            </tr>
        </Tbody>
    </Table>
</template>
