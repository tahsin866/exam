<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import dashboard_card from '../admin_dashboard/dashboard_card.vue';
import notice_bord_and_upcomming_exam from '../admin_dashboard/notice_bord_and_upcomming_exam.vue';
import registrasion_update_dashboard from '../admin_dashboard/registrasion_update_dashboard.vue';
import exam_analytics from '../admin_dashboard/exam_analytics.vue';
import center_management from '../admin_dashboard/center_management.vue';

// Recent activities
const activities = ref([
    {
        id: 1,
        type: 'exam_created',
        description: 'New exam schedule published for Computer Science',
        time: '2 hours ago',
        icon: '🆕'
    },
    // Add more activities...
]);

const selectedDaura = ref('1446'); // Default value
const madrasaCode = ref('');

// Current date/time and user
const currentDateTime = '2025-08-07 06:57:43';
const currentUser = 'tahsin866';

const search = () => {
  // Perform search logic here using selectedDaura and madrasaCode
  console.log('Searching for:', selectedDaura.value, madrasaCode.value);
  // You would typically make an API call or filter data here.
};
</script>

<template>
    <Head title="WEMS" />

    <AuthenticatedLayout>
        <div class="py-6 bg-gray-50">
            <div class=" mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header/Search Banner -->
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
                    <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                        <div class="flex flex-col md:flex-row justify-between items-center">
                            <div class="flex items-center mb-4 md:mb-0">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="h-12 w-12 bg-indigo-100 rounded-lg flex items-center justify-center">
                                        <svg class="h-7 w-7 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M12 14l9-5-9-5-9 5 9 5z"/>
                                            <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/>
                                        </svg>
                                    </div>
                                </div>
                                <div>
                                    <h1 class="text-lg md:text-xl font-semibold text-gray-900">৪৮ তম কেন্দ্রীয় পরীক্ষা- ১৪৪৬ হিজরি / ২০২৫ ঈসাব্দ</h1>
                                    <p class="mt-1 text-sm text-gray-500">{{ currentDateTime }} • {{ currentUser }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <div class="text-sm text-gray-600 mb-4 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            আপনার অধিনস্ত ১৫ টি মাদরাসার উপাত্ত
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="col-span-1">
                                <label for="daura-select" class="block text-sm font-medium text-gray-700 mb-1">দাওরায়ে হাদীস সিলেক্ট করুন</label>
                                <div class="relative">
                                    <select
                                        id="daura-select"
                                        v-model="selectedDaura"
                                        class="block w-full bg-white border border-gray-300 rounded-md py-2 pl-3 pr-10 text-gray-700 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    >
                                        <option value="1446">দাওরায়ে হাদীস ১৪৪৬</option>
                                        <option value="1445">দাওরায়ে হাদীস ১৪৪৫</option>
                                    </select>
                                    <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                     
                                    </div>
                                </div>
                            </div>

                            <div class="col-span-1">
                                <label for="madrasa-code" class="block text-sm font-medium text-gray-700 mb-1">মাদরাসা কোড</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                        </svg>
                                    </div>
                                    <input
                                        id="madrasa-code"
                                        type="text"
                                        v-model="madrasaCode"
                                        placeholder="মাদরাসা কোড (ইংরেজিতে)"
                                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md text-gray-700 placeholder-gray-400 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 text-sm"
                                    >
                                </div>
                            </div>

                            <div class="col-span-1 flex items-end">
                                <button
                                    @click="search"
                                    class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-md inline-flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-150"
                                >
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    অনুসন্ধান
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="mb-6">
                    <dashboard_card />
                </div>

                <!-- Notice Board and Upcoming Exams -->
                <div class="mb-6">
                    <notice_bord_and_upcomming_exam />
                </div>

                <!-- Registration Updates -->
                <div class="mb-6">
                    <registrasion_update_dashboard />
                </div>

                <!-- Exam Analytics -->
                <div class="mb-6">
                    <exam_analytics />
                </div>

                <!-- Center Management -->
                <div class="mb-6">
                    <center_management />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>