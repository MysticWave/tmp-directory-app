<script setup>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { Link } from '@inertiajs/vue3';
import { computed } from 'vue';

const props = defineProps({
    url: String,
    icon: Object,
    isCurrent: Boolean,
});

const classes = computed(() =>
    props.isCurrent
        ? 'focusable flex items-center p-2 text-base font-normal bg-primary-300  rounded-lg !text-white'
        : 'flex items-center p-2 text-base font-normal bg-primary-100 !text-primary rounded-lg hover:bg-primary-300 hover:!text-white'
);
</script>

<template>
    <li>
        <Link
            prefetch
            :href="route(url)"
            class="relative transition-colors duration-150"
            :class="classes"
            data-te-ripple-init
            data-te-ripple-color="light"
        >
            <FontAwesomeIcon v-bind:icon="icon" class="absolute" />
            <span class="ml-7 flex-1 whitespace-nowrap">
                <slot />
            </span>
        </Link>
    </li>
</template>
