<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    login: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);
const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-950">
        <Head title="Login to WEMS" />
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm rounded-lg px-8 py-10">
                <!-- Logo -->
                <div class="flex justify-center mb-8">
                    <svg class="w-12 h-12 text-emerald-600" fill="none" viewBox="0 0 48 48">
                        <rect width="48" height="48" rx="8" fill="currentColor" fill-opacity="0.08"/>
                        <path d="M24 6L31 20H40L24 42L8 20H17L24 6Z" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" fill="white"/>
                    </svg>
                </div>
                <!-- Header -->
                <div class="mb-7 text-center">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">লগইন</h2>
                    <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">ইমেইল বা মোবাইল নম্বর দিয়ে প্রবেশ করুন</p>
                </div>
                <!-- Status -->
                <div v-if="status" class="mb-5 px-4 py-3 rounded border border-emerald-200 text-emerald-800 bg-emerald-50 dark:bg-emerald-900/30 dark:text-emerald-300 text-sm">
                    {{ status }}
                </div>
                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-5">
                    <div>
                        <label for="login" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ইমেইল বা মোবাইল নম্বর</label>
                        <input
                            id="login"
                            type="text"
                            v-model="form.login"
                            autocomplete="username"
                            required
                            autofocus
                            class="block w-full px-4 py-2 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
                            placeholder="example@email.com বা ০১XXXXXXXXX"
                        />
                        <div v-if="form.errors.login" class="text-xs text-red-600 dark:text-red-400 mt-1">{{ form.errors.login }}</div>
                    </div>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">পাসওয়ার্ড</label>
                        <div class="relative">
                            <input
                                id="password"
                                :type="showPassword ? 'text' : 'password'"
                                v-model="form.password"
                                autocomplete="current-password"
                                required
                                class="block w-full px-4 py-2 pr-11 rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition"
                                placeholder="আপনার পাসওয়ার্ড লিখুন"
                            />
                            <button type="button"
                                @click="togglePassword"
                                class="absolute inset-y-0 right-0 flex items-center px-3 text-gray-400 hover:text-emerald-600 dark:hover:text-emerald-400 focus:outline-none"
                                tabindex="-1"
                                aria-label="Show/hide password"
                            >
                                <svg v-if="!showPassword" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.522 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg v-else class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.967 9.967 0 012.293-3.95M6.21 6.21A9.956 9.956 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.977 9.977 0 01-4.293 5.95M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3l18 18"/>
                                </svg>
                            </button>
                        </div>
                        <div v-if="form.errors.password" class="text-xs text-red-600 dark:text-red-400 mt-1">{{ form.errors.password }}</div>
                    </div>
                    <div class="flex items-center justify-between">
                        <label class="flex items-center space-x-2">
                            <input
                                type="checkbox"
                                v-model="form.remember"
                                class="h-4 w-4 border-gray-300 dark:border-gray-700 text-emerald-600 focus:ring-emerald-400 transition rounded"
                            />
                            <span class="text-sm text-gray-600 dark:text-gray-400">মনে রাখুন</span>
                        </label>
                        <Link
                            v-if="canResetPassword"
                            href="route('password.request')"
                            class="text-sm text-emerald-600 hover:underline dark:text-emerald-400"
                        >
                            পাসওয়ার্ড ভুলে গেছেন?
                        </Link>
                    </div>
                    <button
                        type="submit"
                        class="w-full flex justify-center items-center py-2 px-4 rounded bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-base transition focus:outline-none focus:ring-2 focus:ring-emerald-400 focus:ring-offset-2"
                        :disabled="form.processing"
                        :class="{ 'opacity-70 cursor-not-allowed': form.processing }"
                    >
                        <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>প্রবেশ করুন</span>
                    </button>
                </form>
                <div class="mt-7 text-center text-sm text-gray-500 dark:text-gray-400">
                    <span>মাদরাসা একাউন্ট নেই?</span>
                    <Link
                        :href="route('madrasha_check_for_user')"
                        class="ml-1 font-semibold text-emerald-600 hover:underline dark:text-emerald-400"
                    >
                        মাদরাসা নিবন্ধন করুন
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>