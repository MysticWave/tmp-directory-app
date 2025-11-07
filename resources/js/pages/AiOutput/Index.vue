<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Table, Thead, Th, Td, Tbody } from '@netblink/vue-components';
import HeaderBar from '@/components/HeaderBar.vue';
import { Head, Link } from '@inertiajs/vue3';

const props = defineProps({
    ai_outputs: {
        type: Object,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});
</script>

<template>
    <Head title="AI Outputs" />
    <HeaderBar>
        <ol class="inline-flex items-center">
            <Link :href="route('ai-outputs.index')">AI Outputs</Link>
        </ol>
    </HeaderBar>

    <Table :pagination="ai_outputs.meta" :total="ai_outputs.meta.total">
        <Thead>
            <tr>
                <Th>ID</Th>
                <Th>Related To</Th>
                <Th>Status</Th>
                <Th>Output</Th>
            </tr>
        </Thead>
        <Tbody>
            <tr v-if="!ai_outputs.data.length">
                <Td colspan="999" class="text-center">No AI processes found.</Td>
            </tr>

            <tr v-for="output in ai_outputs.data" :key="output.id">
                <Td>
                    {{ output.id }}
                </Td>
                <Td>
                    <Link :href="output.outputable_href" v-if="output.outputable_href" class="underline">
                        [{{ output.type }}] {{ output.outputable_name }}
                    </Link>
                    <span v-else>[{{ output.type }}] {{ output.outputable_name }}</span>
                </Td>
                <Td>
                    {{ output.status }}
                </Td>
                <Td>
                    {{ output.output }}
                </Td>
            </tr>
        </Tbody>
    </Table>
</template>
