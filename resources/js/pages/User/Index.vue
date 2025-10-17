<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Table, Thead, Th, Td, Tbody } from '@netblink/vue-components';
import HeaderBar from '@/components/HeaderBar.vue';
import { Head, Link } from '@inertiajs/vue3';
import FormModal from './FormModal.vue';

defineProps({
    users: {
        type: Object,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <Head title="Users" />
    <HeaderBar>
        <ol class="inline-flex items-center">
            <Link :href="route('users.index')">Users</Link>
        </ol>

        <template #actions>
            <FormModal />
        </template>
    </HeaderBar>

    <!-- <SearchForm /> -->
    <Table :links="users.links" :total="users.total">
        <Thead>
            <tr>
                <Th orderBy="id">#</Th>
                <Th orderBy="first_name">Name</Th>
                <Th class="!w-0"></Th>
            </tr>
        </Thead>
        <Tbody data="users">
            <tr v-for="user in users.data" :key="user.id">
                <Td>
                    {{ user.id }}
                </Td>
                <Td>
                    {{ user.name }}
                </Td>
                <Td>
                    <FormModal :user="user" />
                </Td>
            </tr>
        </Tbody>
    </Table>
</template>
