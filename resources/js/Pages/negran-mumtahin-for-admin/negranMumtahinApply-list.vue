<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import SplitButton from 'primevue/splitbutton';

import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Button from 'primevue/button';


// Data states
const applications = ref([]);
const applicationTypes = ref([]);
const loading = ref(true);
const error = ref(null);

// Filters
const filters = ref({
    global: null,
    application_type: null,
    status: null
});

// Sorting
const sortField = ref('id');
const sortOrder = ref(1); // 1 for ascending, -1 for descending

// Pagination
const currentPage = ref(1);
const perPage = ref(10);

// Page title
const pageTitle = "নেগরান/মুমতাহিন আবেদনসমূহ";

// Export options for PDF
const exportOptions = ref([
    {
        label: 'Excel ডাউনলোড',
        icon: 'ri-file-excel-line',
        command: () => exportExcel()
    }
]);

// Function to export PDF
function exportPDF() {
    console.log('Exporting PDF...');
    // Implement PDF export logic here
}

// Function to export Excel
function exportExcel() {
    console.log('Exporting Excel...');
    // Implement Excel export logic here
}

// Function to clear filters
function clearFilter() {
    filters.value = {
        global: null,
        application_type: null,
        status: null
    };
}

// Function to get severity for status tag
function getSeverity(status) {
    switch (status) {
        case 'পেন্ডিং':
            return 'warning';
        case 'অনুমোদিত':
            return 'success';
        case 'বাতিল':
            return 'danger';
        default:
            return 'info';
    }
}

// Function to get status color for Tailwind
function getStatusColor(status) {
    switch (status) {
        case 'পেন্ডিং':
            return 'bg-yellow-100 text-yellow-800';
        case 'অনুমোদিত':
            return 'bg-green-100 text-green-800';
        case 'বাতিল':
            return 'bg-red-100 text-red-800';
        default:
            return 'bg-blue-100 text-blue-800';
    }
}

// Function to get action items for split button
function getActionItems(data) {
    return [
        {
            label: 'বিস্তারিত দেখুন',
            icon: 'pi pi-eye',
            command: () => viewApplication(data.id)
        },
        {
            label: 'সম্পাদনা করুন',
            icon: 'pi pi-pencil',
            command: () => editApplication(data.id)
        },
        {
            label: 'বাতিল করুন',
            icon: 'pi pi-times',
            command: () => cancelApplication(data.id)
        }
    ];
}

// Function to submit to board
function submitToBoard(data) {
    console.log('Submitting to board:', data);
    // Implement submit to board logic here
}

// View application details
function viewApplication(id) {
    console.log('Viewing application:', id);
    window.location.href = `/negranMumtahinDetailes`;
}

// Edit application
function editApplication(id) {
    console.log('Editing application:', id);
    // Implement edit logic here
}

// Cancel application
function cancelApplication(id) {
    console.log('Cancelling application:', id);
    // Implement cancel logic here
}

// Sort function
function sort(field) {
    if (sortField.value === field) {
        sortOrder.value = sortOrder.value * -1;
    } else {
        sortField.value = field;
        sortOrder.value = 1;
    }
}

// Filtered and sorted applications
const filteredApplications = computed(() => {
    return applications.value
        .filter(app => {
            // Global search
            if (filters.value.global &&
                !(app.name?.toLowerCase().includes(filters.value.global.toLowerCase()) ||
                  app.mobile?.toLowerCase().includes(filters.value.global.toLowerCase()) ||
                  app.madrasha_name?.toLowerCase().includes(filters.value.global.toLowerCase()))) {
                return false;
            }

            // Application type filter
            if (filters.value.application_type && app.application_type !== filters.value.application_type) {
                return false;
            }

            // Status filter
            if (filters.value.status && app.status !== filters.value.status) {
                return false;
            }

            return true;
        })
        .sort((a, b) => {
            let valueA = a[sortField.value];
            let valueB = b[sortField.value];

            // Handle null values
            if (valueA === null) valueA = '';
            if (valueB === null) valueB = '';

            // Compare based on sort order
            if (typeof valueA === 'string') {
                return sortOrder.value * valueA.localeCompare(valueB);
            } else {
                return sortOrder.value * (valueA - valueB);
            }
        });
});

// Paginated applications
const paginatedApplications = computed(() => {
    const start = (currentPage.value - 1) * perPage.value;
    const end = start + perPage.value;
    return filteredApplications.value.slice(start, end);
});

// Total pages
const totalPages = computed(() => {
    return Math.ceil(filteredApplications.value.length / perPage.value);
});

// Pagination range
const paginationRange = computed(() => {
    const range = [];
    const maxPages = 5;

    if (totalPages.value <= maxPages) {
        for (let i = 1; i <= totalPages.value; i++) {
            range.push(i);
        }
    } else {
        let start = Math.max(1, currentPage.value - 2);
        let end = Math.min(totalPages.value, start + maxPages - 1);

        if (end - start < maxPages - 1) {
            start = Math.max(1, end - maxPages + 1);
        }

        for (let i = start; i <= end; i++) {
            range.push(i);
        }
    }

    return range;
});

// Get unique application types
// const uniqueApplicationTypes = computed(() => {
//     const types = applications.value.map(app => app.application_type);
//     return [...new Set(types)].filter(Boolean);
// });

// // Get unique statuses
// const uniqueStatuses = computed(() => {
//     const statuses = applications.value.map(app => app.status);
//     return [...new Set(statuses)].filter(Boolean);
// });

// Fetch data using Axios
async function fetchData() {
    try {
        loading.value = true;
        // Use the API endpoint that works for you
        const response = await axios.get('/api/negran-mumtahin/list');
        applications.value = response.data.applications;
        applicationTypes.value = response.data.applicationTypes;
        // Add status field if not present
        applications.value = applications.value.map(app => ({
            ...app,
            status: app.status || 'পেন্ডিং' // Default status if not provided
        }));
        console.log('Data fetched successfully:', response.data);
    } catch (err) {
        error.value = 'Failed to load data: ' + err.message;
        console.error('Error fetching data:', err);
    } finally {
        loading.value = false;
    }
}

// Initialize data
onMounted(() => {
    fetchData();
});




const uniqueApplicationTypes = computed(() => {
    const types = applications.value.map(app => app.application_type).filter(Boolean);
    const uniqueTypes = [...new Set(types)];
    return [
        { label: 'সকল ধরন', value: '' },
        ...uniqueTypes.map(type => ({ label: type, value: type }))
    ];
});

// Get unique statuses for dropdown
const uniqueStatuses = computed(() => {
    const statuses = applications.value.map(app => app.status).filter(Boolean);
    const uniqueStatuses = [...new Set(statuses)];
    return [
        { label: 'সকল অবস্থা', value: '' },
        ...uniqueStatuses.map(status => ({ label: status, value: status }))
    ];
});





</script>

<template>
    <Head :title="pageTitle" />
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-lg text-gray-800 leading-tight">
                {{ pageTitle }}
            </h2>
        </template>

        <div class="py-12">
            <div class=" sm:px-6 lg:px-8">
                <!-- Error message -->
                <div v-if="error" class="mb-4 p-4 bg-red-100 text-red-700 rounded-md shadow-sm">
                    {{ error }}
                </div>

                <!-- Header Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <h4 class="text-lg font-semibold text-gray-800">
                                মোট নেগরান মুমতাহিন আবেদন সংখ্যা: <span class="text-blue-600">{{ applications.length }}</span>
                            </h4>
                            <div class="flex gap-4">
                                <SplitButton
                                    label="PDF ডাউনলোড"
                                    icon="ri-file-pdf-line"
                                    @click="exportPDF"
                                    :model="exportOptions"
                                />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters Section -->
              <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
    <div class="p-6">
        <h4 class="text-lg font-medium text-gray-700 mb-4">ফিল্টার অপশন</h4>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Global Search -->
            <div>
                <label for="global-search" class="block text-sm font-medium text-gray-700 mb-1">সার্চ করুন</label>
                <span class="p-input-icon-left w-full">

                    <InputText
                        id="global-search"
                        v-model="filters.global"
                        placeholder="নাম, মোবাইল বা মাদ্রাসার নাম দিয়ে খুঁজুন..."
                        class="w-full"
                    />
                </span>
            </div>

            <!-- Application Type Filter -->
            <div>
                <label for="application-type" class="block text-sm font-medium text-gray-700 mb-1">আবেদনের ধরন</label>
                <Dropdown
                    id="application-type"
                    v-model="filters.application_type"
                    :options="uniqueApplicationTypes"
                    placeholder="সকল ধরন"
                    class="w-full"
                    optionValue="value"
                    optionLabel="label"
                />
            </div>

            <!-- Status Filter -->
            <div>
                <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-1">আবেদন অবস্থা</label>
                <Dropdown
                    id="status-filter"
                    v-model="filters.status"
                    :options="uniqueStatuses"
                    placeholder="সকল অবস্থা"
                    class="w-full"
                    optionValue="value"
                    optionLabel="label"
                />
            </div>
        </div>

        <!-- Reset Button -->
        <div class="mt-4 flex justify-end">
            <Button
                @click="clearFilter"
                icon="pi pi-filter-slash"
                label="ফিল্টার মুছুন"
                outlined
            />
        </div>
    </div>
</div>


                <!-- Table Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Loading indicator -->
                    <div v-if="loading" class="flex justify-center items-center p-8">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        <span>ডাটা লোড হচ্ছে...</span>
                    </div>

                    <!-- Empty state -->
                      <!-- Empty state -->
                    <div v-else-if="filteredApplications.length === 0" class="p-8 text-center text-gray-500">
                        কোন আবেদন পাওয়া যায়নি
                    </div>

                    <!-- Data table -->
                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-lg font-medium text-gray-500 uppercase tracking-wider">
                                        ক্রমিক নং
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-lg font-medium text-gray-500 uppercase tracking-wider">
                                        ছবি
                                    </th>
                                    <th scope="col" @click="sort('name')" class="px-6 py-3 text-left text-lg font-medium text-gray-500 uppercase tracking-wider cursor-pointer group">
                                        <div class="flex items-center">
                                            নাম
                                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path v-if="sortField === 'name' && sortOrder === 1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                <path v-else-if="sortField === 'name' && sortOrder === -1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th scope="col" @click="sort('mobile')" class="px-6 py-3 text-left text-lg font-medium text-gray-500 uppercase tracking-wider cursor-pointer group">
                                        <div class="flex items-center">
                                            মোবাইল নম্বর
                                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path v-if="sortField === 'mobile' && sortOrder === 1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                <path v-else-if="sortField === 'mobile' && sortOrder === -1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th scope="col" @click="sort('madrasha_name')" class="px-6 py-3 text-left text-lg font-medium text-gray-500 uppercase tracking-wider cursor-pointer group">
                                        <div class="flex items-center">
                                            মাদ্রাসার নাম
                                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path v-if="sortField === 'madrasha_name' && sortOrder === 1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                <path v-else-if="sortField === 'madrasha_name' && sortOrder === -1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th scope="col" @click="sort('birth_date')" class="px-6 py-3 text-left text-lg font-medium text-gray-500 uppercase tracking-wider cursor-pointer group">
                                        <div class="flex items-center">
                                            জন্ম-তারিখ
                                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path v-if="sortField === 'birth_date' && sortOrder === 1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                <path v-else-if="sortField === 'birth_date' && sortOrder === -1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th scope="col" @click="sort('application_type')" class="px-6 py-3 text-left text-lg font-medium text-gray-500 uppercase tracking-wider cursor-pointer group">
                                        <div class="flex items-center">
                                            আবেদনের ধরন
                                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path v-if="sortField === 'application_type' && sortOrder === 1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                <path v-else-if="sortField === 'application_type' && sortOrder === -1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th scope="col" @click="sort('status')" class="px-6 py-3 text-left text-lg font-medium text-gray-500 uppercase tracking-wider cursor-pointer group">
                                        <div class="flex items-center">
                                            আবেদন অবস্থা
                                            <svg xmlns="http://www.w3.org/2000/svg" class="ml-1 h-4 w-4 text-gray-400 group-hover:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path v-if="sortField === 'status' && sortOrder === 1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                                                <path v-else-if="sortField === 'status' && sortOrder === -1" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4" />
                                            </svg>
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-lg font-medium text-gray-500 uppercase tracking-wider">
                                        করণীয়
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(app, index) in paginatedApplications" :key="app.id" :class="index % 2 === 0 ? 'bg-white' : 'bg-gray-50'">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ (currentPage - 1) * perPage + index + 1 }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <img v-if="app.image" :src="`/storage/${app.image}`" alt="Profile Image" class="h-10 w-10 rounded-full object-cover">
                                            <div v-else class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500 text-lg">N/A</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ app.name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ app.mobile }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ app.madrasha_name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ app.birth_date }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-500">{{ app.application_type }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="['px-2 py-1 inline-flex text-lg leading-5 font-semibold rounded-full', getStatusColor(app.status)]">
                                            {{ app.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <SplitButton
                                            label="অনুমোদন"
                                            icon="pi pi-send-plane"
                                            severity="success"
                                            @click="submitToBoard(app)"
                                            :model="getActionItems(app)"
                                            class="p-button-sm "
                                        />
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div v-if="filteredApplications.length > 0" class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <button @click="currentPage > 1 ? currentPage-- : null" :disabled="currentPage === 1"
                                    class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    পূর্ববর্তী
                                </button>
                                <button @click="currentPage < totalPages ? currentPage++ : null" :disabled="currentPage === totalPages"
                                    class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    পরবর্তী
                                </button>
                            </div>
                            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-sm text-gray-700">
                                        মোট <span class="font-medium">{{ filteredApplications.length }}</span> টি এন্ট্রি থেকে
                                        <span class="font-medium">{{ (currentPage - 1) * perPage + 1 }}</span> -
                                        <span class="font-medium">{{ Math.min(currentPage * perPage, filteredApplications.length) }}</span> দেখানো হচ্ছে
                                    </p>
                                </div>
                                <div>
                                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                        <button @click="currentPage > 1 ? currentPage-- : null" :disabled="currentPage === 1"
                                            class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>

                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                                aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                        <button v-for="page in paginationRange" :key="page" @click="currentPage = page"
                                            :class="[
                                                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                                currentPage === page
                                                    ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                            ]">
                                            {{ page }}
                                        </button>
                                        <button @click="currentPage < totalPages ? currentPage++ : null" :disabled="currentPage === totalPages"
                                            class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                                aria-hidden="true">
                                                <path fill-rule="evenodd"
                                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </button>
                                    </nav>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
/* Custom styling for SplitButton */






</style>
