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
                <Th orderBy="type">Type</Th>
                <Th>Status</Th>
                <Th>Created</Th>
                <Th orderBy="query">Query</Th>
            </tr>
        </Thead>
        <Tbody data="imports">
            <tr v-for="_import in imports.data" :key="_import.id">
                <Td>
                    <Link :href="route('place-imports.show', _import.id)" class="underline">
                        {{ _import.id }}
                    </Link>
                </Td>
                <Td>
                    {{ _import.type }}
                </Td>
                <Td>
                    {{ _import.status }}
                </Td>
                <Td>
                    {{ _import.created_at }}
                </Td>
                <Td>
                    {{ _import.query ?? _import.params }}
                </Td>
            </tr>
        </Tbody>
    </Table>
</template>
