<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Table, Thead, Th, Td, Tbody, Input } from '@netblink/vue-components';
import HeaderBar from '@/components/HeaderBar.vue';
import { Head, Link } from '@inertiajs/vue3';
import FormModal from './FormModal.vue';
import ImportModal from './ImportModal.vue';
import ScrapeReviewsModal from './ScrapeReviewsModal.vue';
import { computed } from 'vue';
import SearchForm from './SearchForm.vue';
import ProcessReviewsModal from './ProcessReviewsModal.vue';

const props = defineProps({
    places: {
        type: Object,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

const selectedAll = computed({
    get() {
        return props.places.data.every((place) => place.selected);
    },
    set(value) {},
});
const selectAll = (e) => {
    props.places.data.forEach((place) => {
        place.selected = e.target.checked;
    });
};

const getSelectedPlaces = () => {
    return props.places.data.filter((place) => place.selected);
};
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
            <ProcessReviewsModal :places="getSelectedPlaces()" />
            <ScrapeReviewsModal :places="getSelectedPlaces()" />
        </template>
    </HeaderBar>

    <SearchForm />
    <Table :pagination="places.meta" :total="places.meta.total">
        <Thead>
            <tr>
                <Th class="!w-0"><Input type="checkbox" class="!mb-0" v-model="selectedAll" @change="selectAll" rightDescription="" /></Th>
                <Th orderBy="id">#</Th>
                <Th orderBy="name">Name</Th>
                <Th orderBy="import_id">Import</Th>
                <Th>Reviews</Th>
                <Th class="!w-0"></Th>
            </tr>
        </Thead>
        <Tbody data="places">
            <tr v-for="place in places.data" :key="place.id">
                <Td>
                    <Input type="checkbox" class="!mb-0" v-model="place.selected" rightDescription="" />
                </Td>
                <Td>
                    <Link :href="route('places.show', place.id)" class="underline">
                        {{ place.id }}
                    </Link>
                </Td>
                <Td>
                    {{ place.name }}
                </Td>
                <Td>
                    <Link :href="route('place-imports.show', place.import_id)" class="underline" v-if="place.import_id">
                        {{ place.import_id }}
                    </Link>
                    <span v-else>-</span>
                </Td>
                <Td>
                    <Link :href="route('reviews.index', { place_id: place.id })" class="underline" v-if="place.reviews_count">
                        {{ place.reviews_count }}
                    </Link>
                    <span v-else>0</span>
                </Td>
                <Td>
                    <FormModal :place="place" />
                </Td>
            </tr>
        </Tbody>
    </Table>
</template>
