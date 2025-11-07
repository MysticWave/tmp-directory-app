<script setup>
import HeaderBar from '@/components/HeaderBar.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';
import FormModal from './FormModal.vue';
import { Input, Table, Tbody, Td, Th, Thead } from '@netblink/vue-components';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faComment, faRobot, faStar } from '@fortawesome/free-solid-svg-icons';
import { ref } from 'vue';
import Markdown from '@/components/Markdown.vue';

const props = defineProps({
    place: {
        type: Object,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

const showRawOutput = ref(false);
</script>

<template>
    <Head title="Places" />
    <HeaderBar>
        <ol class="inline-flex items-center">
            <Link :href="route('places.index')">Places</Link>
            <span class="mx-2">/</span>
            <span>Place {{ place.data.id }}</span>
        </ol>

        <template #actions>
            <FormModal :place="place.data" />
        </template>
    </HeaderBar>

    <Table class="my-4">
        <Tbody>
            <tr>
                <Th class="w-40">ID</Th>
                <Td>{{ place.data.id }}</Td>
            </tr>
            <tr>
                <Th>Name</Th>
                <Td>{{ place.data.name }}</Td>
            </tr>
            <tr>
                <Th>Description</Th>
                <Td>{{ place.data.description }}</Td>
            </tr>
            <tr>
                <Th>Type</Th>
                <Td>{{ place.data.type }}</Td>
            </tr>
            <tr>
                <Th>Created At</Th>
                <Td>{{ place.data.created_at }}</Td>
            </tr>
            <tr>
                <Th>Address</Th>
                <Td>
                    {{ place.data.address_line_1 }}
                </Td>
            </tr>
            <tr>
                <Th>Lat / Lon</Th>
                <Td>{{ place.data.latitude }}, {{ place.data.longitude }}</Td>
            </tr>
            <tr>
                <Th>Phone</Th>
                <Td>
                    {{ place.data.phone }}
                </Td>
            </tr>
            <tr>
                <Th>Email</Th>
                <Td>
                    {{ place.data.email }}
                </Td>
            </tr>
            <tr>
                <Th>Website</Th>
                <Td>
                    <a :href="place.data.website" v-if="place.data.website" target="_blank" class="underline" rel="noopener noreferrer">
                        {{ place.data.website }}
                    </a>
                    <span v-else>-</span>
                </Td>
            </tr>
            <tr>
                <Th>Rating</Th>
                <Td>
                    <FontAwesomeIcon :icon="faStar" class="text-yellow-400" />
                    {{ place.data.rating }} ({{ place.data.user_ratings_total }} reviews)
                </Td>
            </tr>
            <tr>
                <Th>Verified</Th>
                <Td>
                    <span v-if="place.data.verified" class="font-semibold text-green-600">Yes</span>
                    <span v-else class="font-semibold text-red-600">No</span>
                </Td>
            </tr>
            <tr>
                <Th>Source</Th>
                <Td>
                    {{ place.data.source }}
                </Td>
            </tr>
        </Tbody>
    </Table>

    <div class="my-4 flex w-full items-center gap-2">
        <FontAwesomeIcon :icon="faRobot" />
        <span class="shrink-0">AI Processes</span>
        <hr class="w-full border-gray-400" />
    </div>

    <Input type="checkbox" rightDescription="Display Raw Output" v-model="showRawOutput" />

    <Table>
        <Thead>
            <tr>
                <Th>ID</Th>
                <Th>Status</Th>
                <Th>Output</Th>
            </tr>
        </Thead>
        <Tbody>
            <tr v-if="!place.data.ai_outputs?.length">
                <Td colspan="999" class="text-center">No AI processes found.</Td>
            </tr>

            <tr v-for="output in place.data.ai_outputs" :key="output.id">
                <Td>
                    {{ output.id }}
                </Td>
                <Td>
                    {{ output.status }}
                </Td>
                <Td>
                    <span v-if="showRawOutput">{{ output.output }}</span>
                    <Markdown v-else :source="output.output"></Markdown>
                </Td>
            </tr>
        </Tbody>
    </Table>

    <div class="my-4 flex w-full items-center gap-2">
        <FontAwesomeIcon :icon="faComment" />
        <span class="shrink-0">Reviews</span>
        <hr class="w-full border-gray-400" />
    </div>

    <Table>
        <Thead>
            <tr>
                <Th>Text</Th>
                <Th orderBy="rating">Rating</Th>
                <Th orderBy="likes_count">Likes</Th>
                <Th orderBy="reviews_count">Reviews</Th>
                <Th>Pictures</Th>
            </tr>
        </Thead>
        <Tbody>
            <tr v-for="review in place.data.reviews" :key="review.id">
                <Td>{{ review.text ?? review.original_text }}</Td>
                <Td>
                    <div class="flex items-center">
                        <FontAwesomeIcon :icon="faStar" class="text-yellow-400" v-for="n in review.rating" :key="n" />
                        <span class="ml-2">({{ review.rating }})</span>
                    </div>
                </Td>
                <Td>
                    {{ review.likes_count ?? 0 }}
                    <FontAwesomeIcon :icon="faThumbsUp" />
                </Td>
                <Td>
                    {{ review.reviews_count ?? 0 }}
                    <FontAwesomeIcon :icon="faEye" />
                </Td>
                <Td>
                    <div class="flex items-center gap-1">
                        <template v-for="(picture, index) in review.pictures" :key="index">
                            <ImagePreview :src="picture" alt="Review Picture" class="h-12 w-12 rounded object-cover" />
                        </template>
                    </div>
                </Td>
            </tr>
        </Tbody>
    </Table>
</template>
