<script setup>
import { GravatarImg } from '@netblink/vue-components';

const props = defineProps({
    user: Object,
    size: { type: Number, default: 80 },
});
</script>
<template>
    <img v-if="user.avatar" class="size-full" :src="user.avatar" :alt="user.full_name" />
    <GravatarImg class="size-full" v-else :email="user.email" :size="size" />
</template>
