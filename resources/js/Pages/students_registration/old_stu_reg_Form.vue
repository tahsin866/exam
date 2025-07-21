<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref, onMounted, computed, watch } from 'vue';
import axios from 'axios';

// Define all reactive variables
const examName = ref('');
const marhalaName = ref('');
const students = ref([]);
const years = ref([]);
const loading = ref(false);
const currentMarhalaId = ref(null);
const showStudentDetails = ref(null);

// Search filters
const selectedMarhala = ref('');
const selectedYear = ref('');
const rollNumber = ref('');
const registrationNumber = ref('');

// Add error message handling
const errorMessage = ref('');
const showError = ref(false);
const searchPerformed = ref(false);

// Animation states
const isSearching = ref(false);
const fadeIn = ref(false);

// Computed property for filtered marhalas
const availableMarhalas = computed(() => {
    const allMarhalas = [
        { id: '2', name: 'ফযীলত' },
        { id: '3', name: 'সানাবিয়া উলইয়া' },
        { id: '4', name: 'সানাবিয়া' },
        { id: '5', name: 'মুতাওয়াসসিতাহ' },
        { id: '6', name: 'ইবতিদাইয়্যাহ' },
        { id: '7', name: 'হিফযুল কুরআন' },
        { id: '8', name: 'ইলমুত তাজবীদ ওয়াল ক্বিরাআত' }
    ];

    if (currentMarhalaId.value === '9') {
        return allMarhalas.filter(marhala => ['2', '3'].includes(marhala.id));
    }
    if (currentMarhalaId.value === '10') {
        return allMarhalas.filter(marhala => ['4', '3'].includes(marhala.id));
    }
    if (currentMarhalaId.value === '11') {
        return allMarhalas.filter(marhala => ['5', '4'].includes(marhala.id));
    }
    if (currentMarhalaId.value === '12') {
        return allMarhalas.filter(marhala => ['6', '5'].includes(marhala.id));
    }
    if (currentMarhalaId.value === '14') {
        return allMarhalas.filter(marhala => ['6', '7'].includes(marhala.id));
    }
    return allMarhalas;
});

// Only one onMounted hook
onMounted(async () => {
    const marhalaId = route().params.marhalaId;
    currentMarhalaId.value = marhalaId;
    try {
        const response = await axios.get(`/api/student-registration/${marhalaId}`);
        examName.value = response.data.examName;
        marhalaName.value = response.data.marhalaName;
    } catch (error) {
        console.error("Error fetching marhala info:", error);
    }
    // Fetch years from students table
    fetchYears();

    // Show animation
    setTimeout(() => {
        fadeIn.value = true;
    }, 100);
});

const fetchYears = async () => {
    try {
        const response = await axios.get('/api/student-years');
        years.value = response.data;
    } catch (error) {
        console.error('Error fetching years:', error);
    }
};

// Function to show error message
const showErrorMessage = (message) => {
    errorMessage.value = message;
    showError.value = true;

    // Auto-hide the error message after 5 seconds
    setTimeout(() => {
        showError.value = false;
    }, 5000);
};

const searchStudents = async () => {
    loading.value = true;
    isSearching.value = true;
    searchPerformed.value = true;

    // Clear any previous error messages
    showError.value = false;

    try {
        const response = await axios.get('/api/search-students', {
            params: {
                marhala: selectedMarhala.value,
                year: selectedYear.value,
                roll: rollNumber.value,
                registration: registrationNumber.value
            },
            headers: {
                'marhalaId': currentMarhalaId.value
            }
        });

        students.value = response.data;

    } catch (error) {
        if (error.response) {
            if (error.response.status === 404) {
                students.value = [];
                showErrorMessage('রেজাল্ট পাওয়া যায়নি');
            } else if (error.response.status === 403) {
                students.value = [];
                showErrorMessage(error.response.data.error || 'অনুমতি নেই');
            } else if (error.response.status === 401) {
                students.value = [];
                showErrorMessage(error.response.data.error || 'অনুগ্রহ করে লগইন করুন');
            } else {
                students.value = [];
                showErrorMessage(error.response.data.error || 'একটি ত্রুটি ঘটেছে');
            }
        } else {
            console.error('Error searching students:', error);
            showErrorMessage('সার্ভারে সমস্যা হয়েছে, অনুগ্রহ করে আবার চেষ্টা করুন');
        }
    } finally {
        setTimeout(() => {
            loading.value = false;
            isSearching.value = false;
        }, 600);
    }
};

const resetSearch = () => {
    selectedMarhala.value = '';
    selectedYear.value = '';
    rollNumber.value = '';
    registrationNumber.value = '';
    students.value = [];
    showError.value = false;
    searchPerformed.value = false;
    showStudentDetails.value = null;
};

// Base64 এনকোডিং ব্যবহার করে URL তৈরি
const getEditUrl = (student) => {
    if (!student) return '#';
    // Roll এবং reg_id কে একটি স্ট্রিং-এ যোগ করে Base64 এনকোড করুন
    const encodedData = btoa(`${student.Roll}:${student.reg_id}:${student.CID}`);
    return route('students_registration.old_stu_reg_edit', {
        data: encodedData
    });
};

// Get current date and time
const getCurrentDate = () => {
    const date = new Date('2025-07-18 00:32:51');
    return new Intl.DateTimeFormat('bn-BD', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
    }).format(date);
};

const toggleStudentDetails = (student) => {
    if (showStudentDetails.value === student.id) {
        showStudentDetails.value = null;
    } else {
        showStudentDetails.value = student.id;
    }
};

// Get badge color based on student type
const getStudentTypeBadge = (type) => {
    if (type?.trim() === 'নিয়মিত') {
        return 'bg-emerald-100 text-emerald-800';
    } else if (type?.includes('অনিয়মিত যেমনী')) {
        return 'bg-amber-100 text-amber-800';
    } else {
        return 'bg-red-100 text-red-800';
    }
};

// Get badge color based on division/result
const getDivisionBadge = (division) => {
    if (!division) return '';

    if (division === 'রাসিব') {
        return 'bg-red-100 text-red-800';
    } else if (division.includes('মমতাজ')) {
        return 'bg-emerald-100 text-emerald-800';
    } else if (division.includes('জায়্যিদ')) {
        return 'bg-blue-100 text-blue-800';
    } else {
        return 'bg-purple-100 text-purple-800';
    }
};

// Check if any field has value
const hasSearchCriteria = computed(() => {
    return selectedMarhala.value || selectedYear.value || rollNumber.value || registrationNumber.value;
});

// Watch for changes in search fields
watch([selectedMarhala, selectedYear, rollNumber, registrationNumber], () => {
    if (searchPerformed.value && hasSearchCriteria.value) {
        searchStudents();
    }
});
</script>

<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 py-8">
      <div class=" mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div
          class="bg-gradient-to-r from-blue-700 to-indigo-800 rounded-xl shadow-xl mb-6 overflow-hidden transition-all duration-500"
          :class="{'opacity-100 transform translate-y-0': fadeIn, 'opacity-0 transform -translate-y-4': !fadeIn}"
        >
          <div class="p-6 md:p-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
              <div class="flex items-center mb-4 md:mb-0">
                <div class="p-3 bg-white/20 rounded-xl mr-4">
                  <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14zm-4 6v-7.5l4-2.222"></path>
                  </svg>
                </div>
                <div>
                  <h1 class="text-2xl md:text-3xl font-bold text-white">
                    {{ examName }}: {{ marhalaName }}
                  </h1>
                  <p class="text-blue-100 text-sm md:text-base">পুরাতন শিক্ষার্থী নিবন্ধন সিস্টেম</p>
                  <p class="text-blue-100 text-xs mt-1">{{ getCurrentDate() }} • tahsin866</p>
                </div>
              </div>

              <div class="flex flex-wrap gap-3">
                <Link
                  :href="route('students_registration.new_stu_reg_Form', { marhalaId: currentMarhalaId })"
                  class="inline-flex items-center px-4 py-2 bg-white bg-opacity-10 hover:bg-opacity-20 border border-white border-opacity-20 rounded-lg text-sm font-medium text-white transition-all duration-200 hover:shadow-md"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M8 9a3 3 0 100-6 3 3 0 000 6zM8 11a6 6 0 016 6H2a6 6 0 016-6zM16 7a1 1 0 10-2 0v1h-1a1 1 0 100 2h1v1a1 1 0 102 0v-1h1a1 1 0 100-2h-1V7z" />
                  </svg>
                  নতুন ছাত্র নিবন্ধন
                </Link>
                <Link
                  :href="route('students_registration.student_reg_table')"
                  class="inline-flex items-center px-4 py-2 bg-white bg-opacity-10 hover:bg-opacity-20 border border-white border-opacity-20 rounded-lg text-sm font-medium text-white transition-all duration-200 hover:shadow-md"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M3 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm0 4a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1z" clip-rule="evenodd" />
                  </svg>
                  নিবন্ধন তালিকা
                </Link>
              </div>
            </div>
          </div>
        </div>

        <!-- Error Message Alert -->
        <div
          v-if="showError"
          class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm transition-all duration-300 animate-pulse"
        >
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-6 w-6 text-red-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
              </svg>
            </div>
            <div class="ml-3">
              <h3 class="text-sm font-medium text-red-800">ত্রুটি!</h3>
              <div class="mt-1 text-sm text-red-700">{{ errorMessage }}</div>
            </div>
            <div class="ml-auto pl-3">
              <div class="-mx-1.5 -my-1.5">
                <button @click="showError = false" class="inline-flex rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-red-500">
                  <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                  </svg>
                </button>
              </div>
            </div>
          </div>
        </div>

        <!-- Search Card -->
        <div
          class="bg-white rounded-xl shadow-lg overflow-hidden mb-6 transition-all duration-500"
          :class="{'opacity-100 transform translate-y-0': fadeIn, 'opacity-0 transform -translate-y-4': !fadeIn}"
        >
          <!-- Header -->
          <div class="bg-gradient-to-r from-slate-800 to-gray-900 px-6 py-4">
            <div class="flex items-center">
              <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
              </svg>
              <h3 class="ml-2 text-lg font-medium text-white">শিক্ষার্থী অনুসন্ধান</h3>
            </div>
          </div>

          <!-- Search form -->
          <div class="p-6">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
              <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 flex items-center">
                  <svg class="h-4 w-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  মারহালা
                </label>
                <div class="relative rounded-md shadow-sm">
                  <select
                    v-model="selectedMarhala"
                    class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg transition-colors sm:text-sm"
                  >
                    <option value="">মারহালা নির্বাচন করুন</option>
                    <option
                      v-for="marhala in availableMarhalas"
                      :key="marhala.id"
                      :value="marhala.id"
                    >
                      {{ marhala.name }}
                    </option>
                  </select>
                  <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </div>
                </div>
              </div>

              <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 flex items-center">
                  <svg class="h-4 w-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                  </svg>
                  পাশের সন
                </label>
                <div class="relative rounded-md shadow-sm">
                  <select
                    v-model="selectedYear"
                    class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg transition-colors sm:text-sm"
                  >
                    <option value="">পাশের সন নির্বাচন করুন</option>
                    <option v-for="year in years" :key="year" :value="year">{{ year }}</option>
                  </select>
                  <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </div>
                </div>
              </div>

              <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 flex items-center">
                  <svg class="h-4 w-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                  </svg>
                  রোল নম্বর
                </label>
                <div class="relative rounded-md shadow-sm">
                  <input
                    v-model="rollNumber"
                    type="text"
                    class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg transition-colors sm:text-sm"
                    placeholder="রোল নম্বর লিখুন"
                  >
                  <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                  </div>
                </div>
              </div>

              <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 flex items-center">
                  <svg class="h-4 w-4 text-gray-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                  </svg>
                  রেজিস্ট্রেশন নম্বর
                </label>
                <div class="relative rounded-md shadow-sm">
                  <input
                    v-model="registrationNumber"
                    type="text"
                    class="block w-full pl-3 pr-10 py-3 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 rounded-lg transition-colors sm:text-sm"
                    placeholder="রেজিস্ট্রেশন নম্বর লিখুন"
                  >
                  <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                    </svg>
                  </div>
                </div>
              </div>
            </div>

            <div class="flex justify-center md:justify-end gap-3 mt-6">
              <button
                @click="resetSearch"
                class="inline-flex items-center px-4 py-2 bg-gray-200 hover:bg-gray-300 border border-transparent rounded-lg text-sm font-medium text-gray-700 transition-all duration-200"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                </svg>
                সব রিসেট
              </button>
              <button
                @click="searchStudents"
                class="inline-flex items-center px-6 py-2 bg-indigo-600 hover:bg-indigo-700 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm transition-all duration-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                :disabled="loading"
              >
                <svg v-if="loading" class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                </svg>
                {{ loading ? 'অনুসন্ধান করা হচ্ছে...' : 'অনুসন্ধান করুন' }}
              </button>
            </div>
          </div>
        </div>

        <!-- Results Section - Full Width Student Cards -->
        <div
          v-if="students.length > 0"
          class="transition-all duration-500"
          :class="{'opacity-100 transform translate-y-0': !isSearching, 'opacity-0 transform translate-y-4': isSearching}"
        >
          <!-- Student Cards - Full Width -->
          <div class="space-y-6">
            <div
              v-for="student in students"
              :key="student.id"
              class="bg-white rounded-xl shadow-md overflow-hidden hover:shadow-lg transition-all duration-200"
            >
              <!-- Card Header -->
              <div class="bg-gradient-to-r from-slate-700 to-slate-800 px-6 py-4 flex justify-between items-center">
                <div class="flex items-center">
                  <div class="bg-white/20 p-2 rounded-full">
                    <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                  </div>
                  <h4 class="ml-3 text-lg font-medium text-white truncate">{{ student.Name }}</h4>
                </div>
                <span
                  class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  :class="getStudentTypeBadge(student.student_type)"
                >
                  {{ student.student_type }}
                </span>
              </div>

              <!-- Card Content -->
              <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                  <!-- Student Basic Info -->
                  <div class="space-y-4">
                    <h5 class="text-sm font-semibold text-gray-900 uppercase tracking-wider flex items-center">
                      <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                      </svg>
                      শিক্ষার্থীর মৌলিক তথ্য
                    </h5>

                    <dl class="space-y-2">
                      <div class="flex">
                        <dt class="w-24 flex-shrink-0 text-sm font-medium text-gray-500">পিতা:</dt>
                        <dd class="text-sm text-gray-900">{{ student.Father }}</dd>
                      </div>
                      <div class="flex">
                        <dt class="w-24 flex-shrink-0 text-sm font-medium text-gray-500">মাতা:</dt>
                        <dd class="text-sm text-gray-900">{{ student.Mother || 'N/A' }}</dd>
                      </div>
                      <div class="flex">
                        <dt class="w-24 flex-shrink-0 text-sm font-medium text-gray-500">জন্ম-তারিখ:</dt>
                        <dd class="text-sm text-gray-900">{{ student.DateofBirth || 'N/A' }}</dd>
                      </div>
                    </dl>
                  </div>

                  <!-- Academic Info -->
                  <div class="space-y-4">
                    <h5 class="text-sm font-semibold text-gray-900 uppercase tracking-wider flex items-center">
                      <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M12 14l9-5-9-5-9 5 9 5z" />
                        <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                      </svg>
                      একাডেমিক তথ্য
                    </h5>

                    <dl class="space-y-2">
                      <div class="flex">
                        <dt class="w-24 flex-shrink-0 text-sm font-medium text-gray-500">ক্লাস:</dt>
                        <dd class="text-sm text-gray-900">{{ student.Class }}</dd>
                      </div>
                      <div class="flex">
                        <dt class="w-24 flex-shrink-0 text-sm font-medium text-gray-500">নিবন্ধন নং:</dt>
                        <dd class="text-sm text-gray-900">{{ student.Roll }}</dd>
                      </div>
                      <div class="flex">
                        <dt class="w-24 flex-shrink-0 text-sm font-medium text-gray-500">রেজিঃ নং:</dt>
                        <dd class="text-sm text-gray-900">{{ student.reg_id }}</dd>
                      </div>
                      <div class="flex">
                        <dt class="w-24 flex-shrink-0 text-sm font-medium text-gray-500">ফলাফল:</dt>
                        <dd class="text-sm">
                          <span
                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                            :class="getDivisionBadge(student.Division)"
                          >
                            {{ student.Division || 'N/A' }}
                          </span>
                        </dd>
                      </div>
                    </dl>
                  </div>

                  <!-- Institution Info -->
                  <div class="space-y-4">
                    <h5 class="text-sm font-semibold text-gray-900 uppercase tracking-wider flex items-center">
                      <svg class="h-5 w-5 text-indigo-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                      </svg>
                      প্রতিষ্ঠানের তথ্য
                    </h5>

                    <dl class="space-y-2">
                      <div class="flex items-start">
                        <dt class="w-24 flex-shrink-0 text-sm font-medium text-gray-500">মাদরাসা:</dt>
                        <dd class="text-sm text-gray-900">{{ student.Madrasha || 'N/A' }}</dd>
                      </div>
                      <div class="flex items-start">
                        <dt class="w-24 flex-shrink-0 text-sm font-medium text-gray-500">মারকায:</dt>
                        <dd class="text-sm text-gray-900">{{ student.Markaj || 'N/A' }}</dd>
                      </div>
                    </dl>

                    <!-- Action Button -->
                    <div class="pt-4">
                      <Link
                        :href="getEditUrl(student)"
                        class="w-full inline-flex items-center justify-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-lg text-sm font-medium text-white shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                          <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                        </svg>
                        পরীক্ষার ফরম পূরণ করুন
                      </Link>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Loading Indicator -->
        <div v-else-if="loading" class="bg-white rounded-xl shadow-md p-8 text-center mt-6">
          <svg class="animate-spin h-12 w-12 text-indigo-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
          <p class="mt-4 text-lg font-medium text-gray-700">ডাটা লোড হচ্ছে...</p>
          <p class="mt-2 text-sm text-gray-500">অনুগ্রহ করে অপেক্ষা করুন</p>
        </div>

        <!-- Empty Search Results -->
        <div v-else-if="searchPerformed && hasSearchCriteria" class="bg-white rounded-xl shadow-md p-8 text-center mt-6">
          <div class="mx-auto h-24 w-24 flex items-center justify-center rounded-full bg-yellow-100 mb-4">
            <svg class="h-16 w-16 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900">কোন ডাটা পাওয়া যায়নি</h3>
          <p class="mt-2 text-sm text-gray-500">আপনার অনুসন্ধান অনুযায়ী কোন ফলাফল পাওয়া যায়নি।</p>
          <p class="mt-1 text-sm text-gray-500">অন্য কোন ফিল্টার দিয়ে চেষ্টা করুন অথবা সব ফিল্টার মুছে ফেলুন।</p>
          <button
            @click="resetSearch"
            class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200"
          >
            <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
              <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
            </svg>
            ফিল্টার রিসেট করুন
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<style scoped>
/* Additional styling */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* Smooth hover transitions */
.hover-scale {
  transition: transform 0.2s ease;
}

.hover-scale:hover {
  transform: scale(1.02);
}

/* Pulse animation for notification badges */
@keyframes pulse {
  0%, 100% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
}
</style>
