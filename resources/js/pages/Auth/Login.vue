<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { Input, PrimaryButton, SubmitButton } from '@netblink/vue-components';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Log in" />
    <div class="flex min-h-screen flex-col items-center bg-gray-100 pt-6 sm:justify-center sm:pt-0">
        <div>
            <a href="/"><img src="logo" alt="Directory App" class="w-full fill-current text-gray-500 sm:max-w-md" /></a>
        </div>

        <div class="mt-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg">
            <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <Input label="Email" v-bind:form="form" field="email" type="email" required autofocus autocomplete="username" />
                </div>

                <div class="mt-4">
                    <Input label="Password" v-bind:form="form" field="password" type="password" required />
                </div>

                <div class="flex flex-row items-center justify-between">
                    <div class="mt-4 block">
                        <label class="flex items-center">
                            <Input rightDescription="Remember me" :noLabel="true" v-bind:form="form" field="remember" type="checkbox" />
                        </label>
                    </div>
                    <!-- <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="ml-4 mt-2 rounded-md text-center text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Forgot your password?
                    </Link> -->
                </div>

                <div class="flex flex-col space-y-4">
                    <SubmitButton :form="form" class="w-full justify-center bg-blue-500 hover:bg-blue-600">Sign in</SubmitButton>
                </div>
            </form>
        </div>
    </div>
</template>
