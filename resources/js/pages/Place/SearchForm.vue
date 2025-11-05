<script setup>
import ResetButton from '@/components/ResetButton.vue';
import { watchForm } from '@/Helpers';
import { useForm } from '@inertiajs/vue3';
import { Input, Select2ajax } from '@netblink/vue-components';

const form = useForm({
    term: route().params.term ?? null,
    city: route().params.city ?? null,
    has_reviews: route().params.has_reviews ?? '',
});

watchForm(form);
</script>

<template>
    <div class="mt-4 flex">
        <form @submit.prevent class="flex w-full flex-col sm:flex-row sm:flex-wrap sm:gap-2">
            <Input v-model:form="form" field="term" type="search" label="Name" />
            <Select2ajax id="city" class="min-w-40" v-model:form="form" field="city" :url="route('places.get-cities')" />
            <Input class="sm:w-32" v-model:form="form" field="has_reviews" type="select" label="Has Reviews">
                <option value="">All</option>
                <option value="true">Yes</option>
                <option value="false">No</option>
            </Input>
            <ResetButton :form="form" />
        </form>
    </div>
</template>
