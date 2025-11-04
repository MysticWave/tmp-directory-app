<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Table, Thead, Th, Td, Tbody } from '@netblink/vue-components';
import HeaderBar from '@/components/HeaderBar.vue';
import { Head, Link } from '@inertiajs/vue3';
import ImportModal from '../Place/ImportModal.vue';

const props = defineProps({
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
    <Head title="Place Imports" />
    <HeaderBar>
        <ol class="inline-flex items-center">
            <Link :href="route('place-imports.index')">Place Imports</Link>
        </ol>

        <template #actions>
            <ImportModal />
        </template>
    </HeaderBar>

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
