<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Table, Thead, Th, Td, Tbody } from '@netblink/vue-components';
import HeaderBar from '@/components/HeaderBar.vue';
import { Head, Link } from '@inertiajs/vue3';
import FormModal from './FormModal.vue';
import ImportModal from './ImportModal.vue';

defineProps({
    places: {
        type: Object,
        required: true,
    },
    squids: {
        type: Array,
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
            <ImportModal :squids="squids" />
            <FormModal />
        </template>
    </HeaderBar>

    <!-- <SearchForm /> -->
    <Table :links="places.links" :total="places.total">
        <Thead>
            <tr>
                <Th orderBy="id">#</Th>
                <Th orderBy="name">Name</Th>
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
                    <FormModal :place="place" />
                </Td>
            </tr>
        </Tbody>
    </Table>
</template>
