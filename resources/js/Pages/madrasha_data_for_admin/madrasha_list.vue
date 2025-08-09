<template>
    <AuthenticatedLayout>
        <div class="py-6 bg-gray-100 ">
            <div class=" mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header with Breadcrumb and Metadata -->
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 pb-4 border-b border-gray-200">
                    <div>
                        <div class="flex items-center text-sm text-gray-500 mb-1">
                            <span>মাদরাসা ম্যানেজমেন্ট</span>
                            <svg class="h-4 w-4 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                            <span class="font-medium text-gray-700">মাদরাসা তালিকা</span>
                        </div>
                        <h1 class="text-2xl font-semibold text-gray-800">সকল মাদরাসার তালিকা</h1>
                    </div>
                    
                    <div class="mt-2 md:mt-0 flex items-center space-x-2 text-sm text-gray-500">
                        <div class="bg-white py-1 px-3 rounded-md border border-gray-200 shadow-sm">
                            <span class="font-medium">{{ getCurrentUser }}</span>
                        </div>
                        <div class="bg-white py-1 px-3 rounded-md border border-gray-200 shadow-sm">
                            <span>{{ getCurrentDateTime }}</span>
                        </div>
                    </div>
                </div>

                <!-- Action bar -->
                <div class="mb-6 flex flex-wrap items-center justify-between gap-4 bg-white p-4 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex flex-grow items-center">
                        <div class="w-full max-w-lg">
                            <div class="relative">
                                <input 
                                    v-model="filters.madrasahName" 
                                    type="search" 
                                    class="w-full pl-10 pr-4 py-2.5 text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-2 focus:ring-blue-600/50 focus:border-blue-600"
                                    placeholder="মাদরাসার নাম বা কোড দিয়ে অনুসন্ধান করুন"
                                    @keyup.enter="search"
                                >
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <svg class="w-4 h-4 text-gray-500" aria-hidden="true" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                        <button @click="search" class="ml-3 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-2">
                            অনুসন্ধান
                        </button>
                    </div>
                    
                    <div class="flex space-x-2">
                        <button @click="resetFilters" 
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            ফিল্টার রিসেট
                        </button>
                        
                        <button @click="exportData" 
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            এক্সপোর্ট
                        </button>
                        
                        <button type="button" @click="toggleFilterPanel" 
                            class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                            </svg>
                            উন্নত ফিল্টার
                        </button>
                    </div>
                </div>

                <!-- Advanced Filter Panel (toggle visibility) -->
                <div v-show="showFilterPanel" class="mb-6 bg-white rounded-lg shadow-sm border border-gray-200 transition-all duration-300 ease-in-out">
                    <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-lg font-medium text-gray-800">উন্নত ফিল্টার</h2>
                        <button @click="toggleFilterPanel" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-6">
                                <!-- মাদ্রাসার ধরন -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">মাদ্রাসার ধরন</label>
                                    <select v-model="filters.type"
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">সকল</option>
                                        <option value="ছাত্র">ছাত্র</option>
                                        <option value="ছাত্রী">ছাত্রী</option>
                                    </select>
                                </div>

                                <!-- মাদরাসার স্তর -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">মাদরাসার স্তর</label>
                                    <select v-model="filters.level" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">সকল</option>
                                        <option value="takmil">তাকমিল</option>
                                        <option value="fazilat">ফযিলত</option>
                                        <option value="sanabiya_uliya">সানাবিয়া উলইয়া</option>
                                        <option value="sanabiya">সানাবিয়া</option>
                                        <option value="mutawassita">মুতাওয়াসসিতা</option>
                                        <option value="ibtedaiya">ইবতেদাইয়া</option>
                                        <option value="hifzul_quran">হিফজুল কোরআন</option>
                                        <option value="ilmul_qiraat">ইলমুল কিরাআত</option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- মারকাযের ধরন নির্বাচন -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">মারকাযের ধরন</label>
                                    <select v-model="filters.status" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">সকল</option>
                                        <option value="darsiyat">দরসিয়াত</option>
                                        <option value="hifzul_quran">হিফজুল কুরআন</option>
                                        <option value="ilmul_qiraat">ইলমুল কিরাআত</option>
                                        <option value="all">সকল মারকাজ</option>
                                    </select>
                                </div>

                                <!-- বিভাগ -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">বিভাগ</label>
                                    <select v-model="filters.division" @change="handleDivisionChange" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">সকল</option>
                                        <option v-for="division in divisions" :key="division.id" :value="division.id">
                                            {{ division.Division }}
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <div class="space-y-6">
                                <!-- জেলা -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">জেলা</label>
                                    <select v-model="filters.district" @change="handleDistrictChange" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                        :disabled="!filters.division">
                                        <option value="">সকল</option>
                                        <option v-for="district in districts" :key="district.DesID" :value="district.DesID">
                                            {{ district.District }}
                                        </option>
                                    </select>
                                </div>

                                <!-- থানা/উপজেলা -->
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">থানা/উপজেলা</label>
                                    <select v-model="filters.thana" 
                                        class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                                        :disabled="!filters.district">
                                        <option value="">সকল</option>
                                        <option v-for="thana in thanas" :key="thana.Thana_ID" :value="thana.Thana_ID">
                                            {{ thana.Thana }}
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 flex justify-end">
                            <button @click="search"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                                ফলাফল খুঁজুন
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Data Table Card -->
                <div class="bg-white shadow-md rounded-lg border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex flex-wrap items-center justify-between">
                            <div class="flex items-center">
                                <h3 class="text-lg font-medium text-gray-900">মাদরাসা তালিকা</h3>
                                <span class="ml-2 px-2.5 py-0.5 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                                    মোট {{ applications.total || 0 }}
                                </span>
                            </div>

                            <div class="flex items-center gap-2 text-sm text-gray-600">
                                <span>দেখাচ্ছে {{ applications.from || 0 }}-{{ applications.to || 0 }} (মোট {{ applications.total || 0 }})</span>
                            </div>
                        </div>
                    </div>

                    <!-- Loading State -->
                    <div v-if="loading" class="flex justify-center items-center py-24">
                        <div class="flex flex-col items-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
                            <p class="mt-3 text-gray-600">অনুগ্রহ করে অপেক্ষা করুন...</p>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-else-if="!applications.data || applications.data.length === 0" class="py-24">
                        <div class="flex flex-col items-center">
                            <div class="rounded-full bg-gray-100 p-3">
                                <svg class="h-10 w-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-sm font-medium text-gray-900">কোন তথ্য পাওয়া যায়নি</h3>
                            <p class="mt-1 text-sm text-gray-500">এই ফিল্টারের জন্য কোন মাদরাসার তথ্য উপলব্ধ নেই</p>
                            <div class="mt-6">
                                <button
                                    type="button"
                                    class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                    @click="resetFilters"
                                >
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd" />
                                    </svg>
                                    ফিল্টার রিসেট করুন
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Data Table -->
                    <div v-else class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th v-for="header in tableHeaders" :key="header.id"
                                        scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        {{ header.label }}
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="(application, index) in applications.data" :key="application.id"
                                    class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ ((applications.current_page - 1) * perPage) + index + 1 }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ application.name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ application.Elhaq_no }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            {{ application.MType }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ application.markaz_serial }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ application.id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ application.mobile_no }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ application.division_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ application.district_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ application.thana_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <button @click="viewApplication(application)"
                                                class="text-blue-600 hover:text-blue-900 p-1 rounded-md hover:bg-blue-50 transition-colors"
                                                title="মাদরাসা দেখুন">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                </svg>
                                            </button>
                                            <button @click="editApplication(application)"
                                                class="text-indigo-600 hover:text-indigo-900 p-1 rounded-md hover:bg-indigo-50 transition-colors"
                                                title="যোগাযোগ করুন">
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                        <div class="flex items-center justify-between flex-wrap">
                            <div class="flex items-center">
                                <label for="perPage" class="sr-only">প্রতি পেজে</label>
                                <select id="perPage" v-model="perPage" @change="handlePerPageChange"
                                    class="mr-2 rounded-md border-gray-300 py-1.5 text-base sm:text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="50">50</option>
                                    <option value="100">100</option>
                                </select>
                                <p class="text-sm text-gray-700">
                                    <span>প্রতি পেজে</span>
                                </p>
                            </div>
                            
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <button @click="handlePageChange(1)" 
                                    :disabled="applications.current_page === 1"
                                    :class="[applications.current_page === 1 ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-50']"
                                    class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="sr-only">First</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                
                                <button @click="handlePageChange(applications.current_page - 1)" 
                                    :disabled="applications.current_page === 1"
                                    :class="[applications.current_page === 1 ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-50']"
                                    class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                
                                <button class="relative inline-flex items-center px-3 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                                    <input v-model="jumpToPage" @keyup.enter="handleJumpToPage" 
                                        class="w-10 text-center border-none focus:ring-0 p-0" 
                                        type="number" min="1" :max="applications.last_page" />
                                    <span class="mx-1">/</span>
                                    <span>{{ applications.last_page }}</span>
                                </button>
                                
                                <button @click="handlePageChange(applications.current_page + 1)" 
                                    :disabled="applications.current_page === applications.last_page"
                                    :class="[applications.current_page === applications.last_page ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-50']"
                                    class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                
                                <button @click="handlePageChange(applications.last_page)" 
                                    :disabled="applications.current_page === applications.last_page"
                                    :class="[applications.current_page === applications.last_page ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-50']"
                                    class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
                                    <span class="sr-only">Last</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414zm6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';

// State Management
const applications = ref({
    data: [],
    current_page: 1,
    last_page: 1,
    total: 0,
    from: 0,
    to: 0
});

const loading = ref(false);
const perPage = ref(10);
const jumpToPage = ref('');
const divisions = ref([]);
const districts = ref([]);
const thanas = ref([]);
const showFilterPanel = ref(false);

// Current user and datetime
const getCurrentUser = "tahsin866";
const getCurrentDateTime = "2025-08-09 04:54:32";

// Filters
const filters = ref({
    madrasahName: '',
    type: '',
    level: '',
    status: '',
    division: '',
    district: '',
    thana: ''
});

// Table Headers
const tableHeaders = [
    { id: 1, label: "ক্রমিক" },
    { id: 2, label: "মাদরাসার নাম" },
    { id: 3, label: "এলহাক নম্বর" },
    { id: 4, label: "ধরন" },
    { id: 5, label: "মারকাজ সিরিয়াল" },
    { id: 6, label: "আইডি" },
    { id: 7, label: "মোবাইল নম্বর" },
    { id: 8, label: "বিভাগ" },
    { id: 9, label: "জেলা" },
    { id: 10, label: "থানা" },
    { id: 11, label: "অ্যাকশন" }
];

// Toggle filter panel
const toggleFilterPanel = () => {
    showFilterPanel.value = !showFilterPanel.value;
};

// API Functions
const loadDivisions = async () => {
    try {
        loading.value = true;
        const response = await axios.get('/api/divisions');
        divisions.value = response.data;
    } catch (error) {
        console.error('Error loading divisions:', error);
    } finally {
        loading.value = false;
    }
};

const handleDivisionChange = async () => {
    filters.value.district = '';
    filters.value.thana = '';
    districts.value = [];
    thanas.value = [];

    if (filters.value.division) {
        try {
            loading.value = true;
            const response = await axios.get(`/api/districts/${filters.value.division}`);
            districts.value = response.data;
        } catch (error) {
            console.error('Error loading districts:', error);
        } finally {
            loading.value = false;
        }
    }
};

const handleDistrictChange = async () => {
    filters.value.thana = '';
    thanas.value = [];

    if (filters.value.district) {
        try {
            loading.value = true;
            const response = await axios.get(`/api/thanas/${filters.value.district}`);
            thanas.value = response.data;
        } catch (error) {
            console.error('Error loading thanas:', error);
        } finally {
            loading.value = false;
        }
    }
};

const fetchMadrasaData = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/api/markaz-list', {
            params: {
                page: applications.value.current_page,
                per_page: perPage.value,
                ...filters.value
            }
        });
        applications.value = response.data;
    } catch (error) {
        console.error('Error fetching data:', error);
    } finally {
        loading.value = false;
    }
};

// Search with filters
const search = () => {
    applications.value.current_page = 1;
    loading.value = true;

    if (filters.value.madrasahName) {
        filters.value.madrasahName = filters.value.madrasahName.trim();
    }

    axios.get('/api/madrashas/filter', {
        params: filters.value
    })
    .then(response => {
        const data = response.data;
        let formattedData = data;

        if (filters.value.madrasahName && formattedData.length > 0) {
            const searchTerm = filters.value.madrasahName.toLowerCase();
            formattedData.sort((a, b) => {
                const aName = ((a.name || a.MName) || '').toLowerCase();
                const bName = ((b.name || b.MName) || '').toLowerCase();

                if (aName === searchTerm && bName !== searchTerm) return -1;
                if (bName === searchTerm && aName !== searchTerm) return 1;
                if (aName.startsWith(searchTerm) && !bName.startsWith(searchTerm)) return -1;
                if (bName.startsWith(searchTerm) && !aName.startsWith(searchTerm)) return 1;
                if (aName.includes(searchTerm) && !bName.includes(searchTerm)) return -1;
                if (bName.includes(searchTerm) && !aName.includes(searchTerm)) return 1;
                return aName.localeCompare(bName);
            });
        }

        formattedData = formattedData.map(item => {
            return {
                id: item.id,
                name: item.name || item.MName,
                Elhaq_no: item.Elhaq_no || item.ElhaqNo,
                MType: item.type || item.MType,
                markaz_serial: item.markaz_serial || item.CenterSL,
                mobile_no: item.mobile_no || item.Mobile || item.MobileNo,
                division_name: item.division_name,
                district_name: item.district_name,
                thana_name: item.thana_name,
            };
        });

        const totalItems = formattedData.length;
        const startIndex = 0;
        const endIndex = Math.min(perPage.value, totalItems);

        applications.value = {
            data: formattedData.slice(startIndex, endIndex),
            current_page: 1,
            last_page: Math.ceil(totalItems / perPage.value) || 1,
            total: totalItems,
            from: totalItems > 0 ? 1 : 0,
            to: endIndex
        };
    })
    .catch(error => {
        console.error('Error filtering madrasas:', error);
    })
    .finally(() => {
        loading.value = false;
    });
};

// Event Handlers
const handlePageChange = async (page) => {
    if (page < 1 || page > applications.value.last_page || page === applications.value.current_page) {
        return;
    }
    
    applications.value.current_page = page;
    await fetchMadrasaData();
};

const handlePerPageChange = () => {
    applications.value.current_page = 1;
    fetchMadrasaData();
};

const handleJumpToPage = () => {
    const pageNumber = parseInt(jumpToPage.value);
    if (pageNumber && pageNumber >= 1 && pageNumber <= applications.value.last_page) {
        handlePageChange(pageNumber);
        jumpToPage.value = '';
    }
};

const resetFilters = () => {
    Object.keys(filters.value).forEach(key => {
        filters.value[key] = '';
    });
    districts.value = [];
    thanas.value = [];
    search();
};

const exportData = async () => {
    try {
        loading.value = true;
        const response = await axios.get('/api/export-madrasa', {
            params: filters.value,
            responseType: 'blob'
        });
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'madrasa-list.xlsx');
        document.body.appendChild(link);
        link.click();
        link.remove();
    } catch (error) {
        console.error('Error exporting data:', error);
    } finally {
        loading.value = false;
    }
};

const viewApplication = async (application) => {
    try {
        loading.value = true;
        await axios.get(`/api/madrasahs/${application.id}`);
    } catch (error) {
        console.log('Error viewing application:', error);
    } finally {
        loading.value = false;
    }
};

const editApplication = async (application) => {
    try {
        loading.value = true;
        await axios.get(`/api/madrasahs/${application.id}/edit`);
    } catch (error) {
        console.log('Error editing application:', error);
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    loadDivisions();
    fetchMadrasaData();
});
</script>