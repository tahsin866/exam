<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    ElhaqNo: '',
    Mobile: '',
});

const submit = () => {
    form.post(route('madrasha.check'), {
        onFinish: () => form.reset('password', 'password_confirmation')
    });
};
</script>

<template>
    <div
        style="font-family: 'SolaimanLipi', sans-serif;"
        class="min-h-screen flex items-center justify-center bg-gray-50 dark:bg-gray-950 py-4"
    >
        <Head title="মাদরাসা তথ্য অনুসন্ধান" />
        <div class="w-full max-w-md">
            <div class="bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800 shadow-sm rounded-lg px-8 py-10">
                <!-- Header -->
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white mb-2 tracking-tight">
                        মাদরাসা তথ্য অনুসন্ধান
                    </h1>
                    <p class="text-gray-500 dark:text-gray-400 text-sm">
                        ইলহাক নম্বর ও মোবাইল নম্বর প্রদান করুন
                    </p>
                </div>
                <!-- Form -->
                <form @submit.prevent="submit" class="space-y-6">
                    <!-- ElhaqNo -->
                    <div>
                        <label for="ElhaqNo" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">ইলহাক নম্বর</label>
                        <input
                            id="ElhaqNo"
                            v-model="form.ElhaqNo"
                            type="text"
                            placeholder="ইলহাক নম্বর লিখুন"
                            class="w-full rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition px-4 py-3"
                            required
                            autofocus
                        />
                        <div v-if="form.errors.ElhaqNo" class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ form.errors.ElhaqNo }}
                        </div>
                    </div>
                    <!-- Mobile -->
                    <div>
                        <label for="Mobile" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">মোবাইল নম্বর</label>
                        <input
                            id="Mobile"
                            v-model="form.Mobile"
                            type="text"
                            placeholder="মোবাইল নম্বর লিখুন"
                            class="w-full rounded border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 placeholder-gray-400 dark:placeholder-gray-500 focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 transition px-4 py-3"
                            required
                        />
                        <div v-if="form.errors.Mobile" class="text-xs text-red-600 dark:text-red-400 mt-1 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                            {{ form.errors.Mobile }}
                        </div>
                    </div>
                    <!-- Submit Button -->
                    <div class="flex justify-end mt-4">
                        <button
                            type="submit"
                            class="inline-flex items-center px-6 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded font-semibold text-base shadow-sm transition focus:outline-none focus:ring-2 focus:ring-emerald-400 disabled:opacity-70"
                            :disabled="form.processing"
                        >
                            <svg v-if="form.processing" class="animate-spin h-5 w-5 text-white mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            অনুসন্ধান করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>