<script setup>
import { router } from '@inertiajs/vue3';
import { ref } from 'vue';
import { faBuilding, faComment, faRobot, faUsers } from '@fortawesome/free-solid-svg-icons';
import MenuItem from './MenuItem.vue';

const currentRoute = ref(route().current());
router.on('navigate', () => {
    currentRoute.value = route().current();
});

const isCurrent = (name, matchFullPath = false) => {
    if (matchFullPath) {
        return currentRoute.value === name;
    }
    return currentRoute.value.startsWith(name);
};

const props = defineProps({
    isMenuOpen: Boolean,
});

const emit = defineEmits(['closeMenu']);
const closeMenu = () => {
    emit('closeMenu');
};
</script>

<template>
    <div
        class="fixed top-12 left-0 z-10 h-full bg-white transition-all duration-150 sm:top-0"
        :class="{
            'max-lg:-left-64': !isMenuOpen,
        }"
    >
        <aside
            id="logo-sidebar"
            class="z-40 block h-screen w-64 border-b border-gray-200 bg-white pt-2 lg:fixed lg:border-r lg:border-b-0 dark:border-gray-700 dark:bg-gray-800"
            aria-label="Sidebar"
        >
            <div
                class="bg-opacity-25 fixed h-full w-[200%] bg-gray-500 transition-all lg:hidden"
                type="button"
                :class="{
                    '!bg-opacity-0 pointer-events-none': !isMenuOpen,
                }"
                @click="closeMenu"
            ></div>
            <div
                class="relative z-50 h-full w-full overflow-x-hidden overflow-y-auto bg-white px-3 !pt-2 pb-4 min-[640px]:pt-[7rem] lg:pt-[7rem]"
                :class="{ 'relative min-[640px]:!pt-[4rem] lg:!pt-[4rem]': !$page.props.is_impersonating }"
            >
                <ul class="space-y-2">
                    <MenuItem @click="closeMenu" url="users.index" :icon="faUsers" :isCurrent="isCurrent('users')">Users</MenuItem>
                    <MenuItem @click="closeMenu" url="places.index" :icon="faBuilding" :isCurrent="isCurrent('places')">Places</MenuItem>
                    <MenuItem @click="closeMenu" url="place-imports.index" :icon="faBuilding" :isCurrent="isCurrent('place-imports')">
                        Place Imports
                    </MenuItem>
                    <MenuItem @click="closeMenu" url="reviews.index" :icon="faComment" :isCurrent="isCurrent('reviews')">Reviews</MenuItem>
                    <MenuItem @click="closeMenu" url="prompts.index" :icon="faComment" :isCurrent="isCurrent('prompts')">Prompts</MenuItem>
                    <MenuItem @click="closeMenu" url="ai-outputs.index" :icon="faRobot" :isCurrent="isCurrent('ai-outputs')">AI Outputs</MenuItem>

                    <li class="h-8"></li>
                </ul>
            </div>
        </aside>
    </div>
</template>
