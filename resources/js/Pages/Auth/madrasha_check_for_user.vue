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
    <Head title="madrasha_check_for_user" />
    <div class="min-h-screen flex items-center justify-center bg-gray-100 py-4 sm:pt-0">
        <div class="w-full max-w-md">
            <div class="bg-white p-6 rounded-lg shadow-md">
                <div class="text-center text-xl font-bold text-gray-800 mb-6">
                    মাদরাসা তথ্য অনুসন্ধান
                </div>

                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="ElhaqNo" class="block text-sm font-medium text-gray-700 mb-1">ইলহাক নম্বর</label>
                        <input
                            id="ElhaqNo"
                            v-model="form.ElhaqNo"
                            type="text"
                            placeholder="ইলহাক নম্বর লিখুন"
                            class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required
                            autofocus
                        />
                        <div v-if="form.errors.ElhaqNo" class="text-sm text-red-600 mt-1">{{ form.errors.ElhaqNo }}</div>
                    </div>

                    <div class="mb-4">
                        <label for="Mobile" class="block text-sm font-medium text-gray-700 mb-1">মোবাইল নম্বর</label>
                        <input
                            id="Mobile"
                            v-model="form.Mobile"
                            type="text"
                            placeholder="মোবাইল নম্বর লিখুন"
                            class="w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required
                        />
                        <div v-if="form.errors.Mobile" class="text-sm text-red-600 mt-1">{{ form.errors.Mobile }}</div>
                    </div>

                    <div class="flex justify-end mt-6">
                        <button
                            type="submit"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
                            :disabled="form.processing"
                        >
                            <span v-if="form.processing" class="mr-2">
                                <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            অনুসন্ধান করুন
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style scoped>
* {
    font-family: 'SolaimanLipi', Arial, sans-serif;
}
</style>
