<script setup>
import { ref, onMounted, Transition } from 'vue';
import { Dropdown, DropdownLink } from '@netblink/vue-components';
import Sidenav from '@/components/Sidenav.vue';
import Toast from '@/components/Toast.vue';
// import Logo from '@/components/Logo.vue';
import Avatar from '@/components/Avatar.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { auth } from '@/Helpers';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCamera, faRightFromBracket } from '@fortawesome/free-solid-svg-icons';

const isMenuOpen = ref(false);
const closeMenu = () => {
    isMenuOpen.value = false;
};

const avatarForm = useForm({
    avatar: null,
});

const updateAvatar = (event) => {
    avatarForm.avatar = event.target.files[0];
    avatarForm.post(route('profile.upload-avatar'), {
        preserveScroll: true,
        onSuccess: () => {
            avatarForm.reset();
        },
    });
};

const disableHide = (event) => {
    event.stopPropagation();
};

const stopImpersonateClicked = ref(false);
</script>

<template>
    <div class="top-0 z-50 h-14 w-full bg-white dark:bg-gray-800" :class="auth().is_impersonating ? '!h-24' : ''"></div>
    <nav class="fixed top-0 z-20 w-full border-b border-gray-200 bg-white dark:border-gray-700 dark:bg-gray-800">
        <div class="bg-primary-500 flex h-10 w-full items-center justify-between px-4 text-white" v-if="auth().is_impersonating">
            <div></div>
            <div>You are logged as: {{ auth().full_name }}</div>
            <div @click="stopImpersonateClicked = true">
                <Link class="!text-white" :href="route('impersonate.leave')" :class="stopImpersonateClicked ? 'pointer-events-none' : ''">
                    Stop impersonating
                </Link>
            </div>
        </div>
        <div class="px-3 py-2 lg:px-5 lg:pl-3">
            <div class="flex items-center justify-between">
                <div class="flex min-w-0 items-center justify-start max-lg:min-w-[140px]">
                    <button
                        aria-controls="logo-sidebar"
                        @click="isMenuOpen = !isMenuOpen"
                        type="button"
                        class="inline-flex items-center rounded-lg p-2 text-sm text-gray-500 hover:bg-gray-100 focus:ring-2 focus:ring-gray-200 focus:outline-none lg:hidden dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                    >
                        <span class="sr-only">Open sidebar</span>
                        <svg class="h-6 w-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path
                                clip-rule="evenodd"
                                fill-rule="evenodd"
                                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"
                            ></path>
                        </svg>
                    </button>
                    <!-- <Logo /> -->
                </div>
                <div class="flex w-fit items-center justify-end gap-4 md:w-fit">
                    <Dropdown align="right" width="w-72">
                        <template #trigger>
                            <div class="size-10 cursor-pointer overflow-hidden rounded-full">
                                <Avatar :user="$page.props.auth.user" :size="40" />
                            </div>
                        </template>
                        <template #content>
                            <div class="flex flex-col items-center justify-center p-4 pb-2" @click="disableHide">
                                <div class="group relative size-20 overflow-hidden rounded-full">
                                    <Avatar :user="$page.props.auth.user" :size="80" />
                                    <label
                                        class="absolute top-0 right-0 flex size-full cursor-pointer items-center justify-center bg-black/50 opacity-0 duration-150 group-hover:opacity-100"
                                    >
                                        <FontAwesomeIcon :icon="faCamera" class="text-2xl text-white" />
                                        <input type="file" @change="updateAvatar" class="hidden" accept="image/*" />
                                    </label>
                                </div>

                                <div class="mt-4 text-center font-bold">
                                    {{ $page.props.auth.user.full_name }}
                                </div>
                                <div class="text-center text-xs text-gray-500">
                                    {{ $page.props.auth.user.email }}
                                </div>
                            </div>
                            <hr />
                            <!-- <DropdownLink :href="route('profile.edit')" as="button">
                                <FontAwesomeIcon :icon="faEdit" class="mr-2" />
                                Profile
                            </DropdownLink> -->
                            <DropdownLink :href="route('logout')" method="post" as="button">
                                <FontAwesomeIcon :icon="faRightFromBracket" class="mr-2" />
                                Log Out
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </div>
        </div>
    </nav>
    <Sidenav :isMenuOpen="isMenuOpen" @closeMenu="closeMenu" :class="auth().is_impersonating ? '!mt-10' : ''" />

    <div class="min-h-[calc(100vh-65px)] bg-white p-4 lg:mt-0 lg:ml-64">
        <Toast variant="warning" :clearProps="true" v-if="$page.props.flash.alert" :body="$page.props.flash.alert" />
        <Toast variant="error" :clearProps="true" v-if="$page.props.flash.error" :body="$page.props.flash.error" />
        <Toast variant="success" :clearProps="true" v-if="$page.props.flash.success" :body="$page.props.flash.success" />
        <Toast variant="warning" :clearProps="true" v-if="$page.props.flash.warning" :body="$page.props.flash.warning" />

        <Transition name="fade" mode="out-in" appear>
            <div :key="$page.component + $page.url.split('?')[0]">
                <slot />
            </div>
        </Transition>
    </div>
</template>
