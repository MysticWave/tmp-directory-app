<script setup>
import AppLayout from '@/layouts/AppLayout.vue';
import { Table, Thead, Th, Td, Tbody, Input } from '@netblink/vue-components';
import HeaderBar from '@/components/HeaderBar.vue';
import { Head, Link } from '@inertiajs/vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faEye, faStar, faThumbsUp } from '@fortawesome/free-solid-svg-icons';
import { ref } from 'vue';
import ImagePreview from '@/components/ImagePreview.vue';

defineProps({
    reviews: {
        type: Object,
        required: true,
    },
});

defineOptions({
    layout: AppLayout,
});

const showOriginalText = ref(false);
</script>

<template>
    <Head title="Reviews" />
    <HeaderBar>
        <ol class="inline-flex items-center">
            <Link :href="route('reviews.index')">Reviews</Link>
        </ol>
    </HeaderBar>

    <Input type="checkbox" rightDescription="Show Original Text" v-model="showOriginalText" class="mt-4 !mb-0 w-max" />

    <!-- <SearchForm /> -->
    <Table :pagination="reviews.meta" :total="reviews.meta.total">
        <Thead>
            <tr>
                <Th orderBy="place_id">Place Name</Th>
                <Th>Text</Th>
                <Th orderBy="rating">Rating</Th>
                <Th orderBy="likes_count">Likes</Th>
                <Th orderBy="reviews_count">Reviews</Th>
                <Th>Pictures</Th>
            </tr>
        </Thead>
        <Tbody data="reviews">
            <tr v-for="review in reviews.data" :key="review.id">
                <Td>
                    {{ review.place_name }}
                </Td>
                <Td>{{ showOriginalText ? review.original_text : review.text }}</Td>
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
