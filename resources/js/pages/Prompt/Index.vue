<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Table, Thead, Th, Td, Tbody } from '@netblink/vue-components';
import HeaderBar from '@/components/HeaderBar.vue';
import { Head, Link } from '@inertiajs/vue3';
import FormModal from './FormModal.vue';

defineProps({
    prompts: {
        type: Object,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <Head title="Prompts" />
    <HeaderBar>
        <ol class="inline-flex items-center">
            <Link :href="route('prompts.index')">Prompts</Link>
        </ol>

        <template #actions>
            <FormModal />
        </template>
    </HeaderBar>

    <!-- <SearchForm /> -->
    <Table :links="prompts.links" :total="prompts.total">
        <Thead>
            <tr>
                <Th orderBy="id">#</Th>
                <Th orderBy="name">Name</Th>
                <Th class="!w-0"></Th>
            </tr>
        </Thead>
        <Tbody data="prompts">
            <tr v-for="prompt in prompts.data" :key="prompt.id">
                <Td>
                    {{ prompt.id }}
                </Td>
                <Td>
                    {{ prompt.name }}
                </Td>
                <Td>
                    <FormModal :prompt="prompt" />
                </Td>
            </tr>
        </Tbody>
    </Table>
</template>
