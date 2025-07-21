<template>
  <AuthenticatedLayout>
    <div class="py-8 px-6 bg-gradient-to-br from-gray-50 to-gray-100 min-h-screen">
      <div class=" mx-auto">
        <!-- Header Section with Stats -->
        <div class="mb-8 flex flex-col md:flex-row justify-between gap-6">
          <div class="flex items-center">
            <div class="mr-5 h-14 w-14 flex items-center justify-center rounded-xl bg-emerald-600 text-white shadow-lg shadow-emerald-200">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
            </div>
            <div>
              <h1 class="text-3xl font-bold text-gray-800 mb-1">সকল মারকাযের তালিকা</h1>
              <p class="text-gray-600">সকল মারকায এবং তাদের সম্পর্কিত তথ্য</p>
            </div>
          </div>

          <div class="grid grid-cols-2 sm:grid-cols-2 gap-4">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex items-center hover:shadow-md transition-shadow duration-300">
              <div class="p-3 rounded-lg bg-blue-50 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <div>
                <h2 class="text-gray-500 text-sm">মোট মারকায</h2>
                <p class="text-2xl font-bold text-gray-800">{{ applications.data?.length || 0 }}</p>
              </div>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4 flex items-center hover:shadow-md transition-shadow duration-300">
              <div class="p-3 rounded-lg bg-emerald-50 mr-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
              </div>
              <div>
                <h2 class="text-gray-500 text-sm">সক্রিয় মারকায</h2>
                <p class="text-2xl font-bold text-gray-800">{{ getActiveCount() }}</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Search & Filter Panel -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6 transition-shadow duration-300 hover:shadow-md">
          <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 p-4 flex justify-between items-center">
            <h2 class="text-white text-lg font-semibold flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
              </svg>
              অনুসন্ধান ফিল্টার
            </h2>
            <div class="flex gap-2">
              <button @click="resetFilters"
                      class="text-white hover:text-emerald-200 transition-colors duration-200 flex items-center gap-1 px-3 py-1.5 rounded-md hover:bg-emerald-800/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                </svg>
                রিসেট
              </button>
              <button @click="exportData"
                      class="text-white hover:text-emerald-200 transition-colors duration-200 flex items-center gap-1 px-3 py-1.5 rounded-md hover:bg-emerald-800/30">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                এক্সপোর্ট
              </button>
            </div>
          </div>

          <div class="p-5">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              <!-- Name Search -->
              <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                  </svg>
                  মারকাযের নাম
                </label>
                <div class="relative">
                  <input
                    type="text"
                    v-model="searchFilters.madrasahName"
                    placeholder="মারকাযের নাম লিখুন..."
                    class="w-full pl-10 pr-3 py-2.5 bg-gray-50 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200"
                  />
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                  </div>
                </div>
              </div>

              <!-- Type -->
              <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                  </svg>
                  মাদ্রাসার ধরন
                </label>
                <div class="relative">
                  <select
                    v-model="searchFilters.type"
                    class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-300 rounded-md appearance-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200"
                  >
                    <option value="">সকল</option>
                    <option value="ছাত্র">ছাত্র</option>
                    <option value="ছাত্রী">ছাত্রী</option>
                  </select>
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </div>
                  <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </div>
                </div>
              </div>

              <!-- Level -->
              <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path d="M12 14l9-5-9-5-9 5 9 5z" />
                    <path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                  </svg>
                  মাদরাসার স্তর
                </label>
                <div class="relative">
                  <select
                    v-model="searchFilters.level"
                    class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-300 rounded-md appearance-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200"
                  >
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
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                  </div>
                  <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </div>
                </div>
              </div>

              <!-- Markaz Type -->
              <div class="space-y-2">
                <label class="text-sm font-medium text-gray-700 flex items-center">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                  </svg>
                  মারকাযের ধরন
                </label>
                <div class="relative">
                  <select
                    v-model="searchFilters.status"
                    class="w-full pl-10 pr-10 py-2.5 bg-gray-50 border border-gray-300 rounded-md appearance-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-colors duration-200"
                  >
                    <option value="">সকল</option>
                    <option value="darsiyat">দরসিয়াত</option>
                    <option value="hifzul_quran">হিফজুল কুরআন</option>
                    <option value="ilmul_qiraat">ইলমুল কিরাআত</option>
                    <option value="all">সকল মারকাজ</option>
                  </select>
                  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                    </svg>
                  </div>
                  <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                  </div>
                </div>
              </div>
            </div>

            <!-- Active Filters -->
            <div v-if="hasActiveFilters" class="mt-4 flex flex-wrap gap-2 items-center">
              <span class="text-sm font-medium text-gray-700">সক্রিয় ফিল্টারসমূহ:</span>
              <div v-if="searchFilters.madrasahName" class="px-3 py-1 rounded-full bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm flex items-center">
                নাম: {{ searchFilters.madrasahName }}
                <button @click="searchFilters.madrasahName = ''" class="ml-1.5 focus:outline-none hover:text-emerald-800">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div v-if="searchFilters.type" class="px-3 py-1 rounded-full bg-blue-50 border border-blue-200 text-blue-700 text-sm flex items-center">
                মাদ্রাসার ধরন: {{ searchFilters.type }}
                <button @click="searchFilters.type = ''" class="ml-1.5 focus:outline-none hover:text-blue-800">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div v-if="searchFilters.level" class="px-3 py-1 rounded-full bg-purple-50 border border-purple-200 text-purple-700 text-sm flex items-center">
                মাদরাসার স্তর: {{ getLevelName(searchFilters.level) }}
                <button @click="searchFilters.level = ''" class="ml-1.5 focus:outline-none hover:text-purple-800">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
              <div v-if="searchFilters.status" class="px-3 py-1 rounded-full bg-amber-50 border border-amber-200 text-amber-700 text-sm flex items-center">
                মারকাযের ধরন: {{ getMarkazTypeName(searchFilters.status) }}
                <button @click="searchFilters.status = ''" class="ml-1.5 focus:outline-none hover:text-amber-800">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Search Button -->
            <div class="mt-5 flex justify-end">
              <button
                @click="search"
                class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-md shadow-sm hover:shadow transition-all duration-200 flex items-center gap-2"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                অনুসন্ধান করুন
              </button>
            </div>
          </div>
        </div>

        <!-- DataTable -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden transition-shadow duration-300 hover:shadow-md">
          <div class="bg-gradient-to-r from-emerald-600 to-emerald-700 p-4 flex justify-between items-center">
            <h2 class="text-white text-lg font-semibold flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              মারকাযের তালিকা
            </h2>
            <div class="text-white flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8h2a2 2 0 012 2v6a2 2 0 01-2 2h-2v4l-4-4H9a1.994 1.994 0 01-1.414-.586m0 0L11 14h4a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2v4l.586-.586z" />
              </svg>
              মোট উপাত্ত: <span class="font-semibold">{{ applications.data?.length || 0 }}</span>
            </div>
          </div>

          <div class="p-0">
            <DataTable
              :value="applications.data"
              v-model:filters="filters"
              filterDisplay="menu"
              :loading="loading"
              dataKey="id"
              paginator
              :rows="perPage"
              :rowsPerPageOptions="[10, 20, 50, 100]"
              paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
              currentPageReportTemplate="দেখানো হচ্ছে {first} থেকে {last} মোট {totalRecords} এর মধ্যে"
              responsiveLayout="stack"
              breakpoint="960px"
              class="p-datatable-sm p-datatable-gridlines"
              :scrollable="true"
              scrollHeight="flex"
              :resizableColumns="true"
              columnResizeMode="fit"
              showGridlines
              stripedRows
              :globalFilterFields="['name', 'Elhaq_no', 'MType', 'markaz_serial', 'id', 'mobile_no', 'division_name', 'district_name', 'thana_name']"
            >
              <template #header>
                <div class="flex justify-between items-center flex-wrap p-2">
                  <div class="flex flex-wrap items-center gap-3">
                    <span class="p-input-icon-left w-64">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                      </svg>
                      <InputText v-model="filters['global'].value" placeholder="সার্চ করুন..." class="w-full pl-9 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                    </span>

                    <div class="flex items-center">
                      <button @click="showAllColumns = !showAllColumns"
                              class="flex items-center bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-md text-sm transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                        </svg>
                        {{ showAllColumns ? 'কলাম লুকান' : 'সব কলাম দেখুন' }}
                      </button>
                    </div>
                  </div>

                  <div class="flex items-center text-gray-600 text-sm">
                    <span v-if="loading" class="flex items-center">
                      <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-emerald-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                      </svg>
                      লোড হচ্ছে...
                    </span>
                    <span v-else>
                      সর্বশেষ আপডেট: {{ getCurrentDateTime() }}
                    </span>
                  </div>
                </div>
              </template>

              <Column field="id" header="ক্রমিক" sortable style="min-width: 5rem; max-width: 8rem;">
                <template #body="{ index }">
                  <div class="font-medium text-gray-900">{{ index + 1 }}</div>
                </template>
              </Column>

              <Column field="name" header="মারকাযের নাম" sortable style="min-width: 14rem">
                <template #filter="{ filterModel, filterCallback }">
                  <InputText v-model="filterModel.value" @input="filterCallback()" placeholder="সার্চ করুন..." class="w-full p-2" />
                </template>
                <template #body="{ data }">
                  <Link :href="route('markaz_for_admin.madrasha_list_underMarkaz', data.id)"
                    class="text-blue-600 hover:text-blue-800 font-medium hover:underline flex items-center"
                    title="বিস্তারিত দেখুন"
                  >
                    <span>{{ data.name }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                    </svg>
                  </Link>
                </template>
              </Column>

              <Column field="Elhaq_no" header="এলহাক নং" sortable style="min-width: 10rem" :visible="showAllColumns">
                <template #filter="{ filterModel, filterCallback }">
                  <InputText v-model="filterModel.value" @input="filterCallback()" placeholder="সার্চ করুন..." class="w-full p-2" />
                </template>
                <template #body="{ data }">
                  <span class="px-2.5 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-medium">
                    {{ data.Elhaq_no || 'N/A' }}
                  </span>
                </template>
              </Column>

              <Column field="MType" header="মাদ্রাসার ধরন" sortable style="min-width: 10rem" :visible="showAllColumns">
                <template #filter="{ filterModel, filterCallback }">
                  <InputText v-model="filterModel.value" @input="filterCallback()" placeholder="সার্চ করুন..." class="w-full p-2" />
                </template>
                <template #body="{ data }">
                  <span :class="[
                    'px-2.5 py-1 rounded-full text-xs font-medium',
                    data.MType === 'ছাত্র' ? 'bg-blue-50 text-blue-700' : 'bg-pink-50 text-pink-700'
                  ]">
                    {{ data.MType || 'N/A' }}
                  </span>
                </template>
              </Column>

              <Column field="markaz_serial" header="মারকাজ সিরিয়াল" sortable style="min-width: 10rem" :visible="showAllColumns">
                <template #filter="{ filterModel, filterCallback }">
                  <InputText v-model="filterModel.value" @input="filterCallback()" placeholder="সার্চ করুন..." class="w-full p-2" />
                </template>
              </Column>

              <Column field="mobile_no" header="মোবাইল নম্বর" sortable style="min-width: 10rem">
                <template #filter="{ filterModel, filterCallback }">
                  <InputText v-model="filterModel.value" @input="filterCallback()" placeholder="সার্চ করুন..." class="w-full p-2" />
                </template>
                <template #body="{ data }">
                  <a :href="`tel:${data.mobile_no}`" class="text-gray-700 hover:text-blue-600 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                    </svg>
                    {{ data.mobile_no || 'N/A' }}
                  </a>
                </template>
              </Column>

              <Column field="division_name" header="বিভাগ" sortable style="min-width: 10rem" :visible="showAllColumns">
                <template #filter="{ filterModel }">
                  <Dropdown
                    v-model="filterModel.value"
                    :options="divisions"
                    optionLabel="Division"
                    optionValue="Division"
                    placeholder="বিভাগ নির্বাচন করুন"
                    class="w-full"
                    @change="handleTableDivisionChange($event, filterModel)"
                  />
                </template>
              </Column>

              <Column field="district_name" header="জেলা" sortable style="min-width: 10rem" :visible="showAllColumns">
                <template #filter="{ filterModel }">
                  <Dropdown
                    v-model="filterModel.value"
                    :options="tableFilterDistricts"
                    optionLabel="District"
                    optionValue="District"
                    placeholder="জেলা নির্বাচন করুন"
                    class="w-full"
                    @change="handleTableDistrictChange($event, filterModel)"
                  />
                </template>
              </Column>

              <Column field="thana_name" header="থানা/উপজেলা" sortable style="min-width: 10rem" :visible="showAllColumns">
                <template #filter="{ filterModel }">
                  <Dropdown
                    v-model="filterModel.value"
                    :options="tableFilterThanas"
                    optionLabel="Thana"
                    optionValue="Thana"
                    placeholder="থানা নির্বাচন করুন"
                    class="w-full"
                  />
                </template>
              </Column>

              <Column header="করনীয়" style="min-width: 10rem; text-align: center;">
                <template #body="{ data }">
                  <div class="flex justify-center gap-3">
                    <button @click="viewApplication(data)"
                      class="p-2 bg-blue-50 hover:bg-blue-100 text-blue-600 hover:text-blue-800 rounded-lg transition-colors duration-200"
                      title="বিস্তারিত দেখুন"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                      </svg>
                    </button>

                    <button @click="editApplication(data)"
                      class="p-2 bg-amber-50 hover:bg-amber-100 text-amber-600 hover:text-amber-800 rounded-lg transition-colors duration-200"
                      title="সম্পাদনা করুন"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                      </svg>
                    </button>

                    <button @click="sendMessage(data)"
                      class="p-2 bg-green-50 hover:bg-green-100 text-green-600 hover:text-green-800 rounded-lg transition-colors duration-200"
                      title="বার্তা পাঠান"
                    >
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                    </button>
                  </div>
                </template>
              </Column>

              <template #empty>
                <div class="flex flex-col items-center justify-center py-10">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                  </svg>
                  <h3 class="mt-3 text-lg font-medium text-gray-900">কোন তথ্য পাওয়া যায়নি</h3>
                  <p class="mt-1 text-gray-500">আপনার অনুসন্ধান অনুযায়ী কোন মারকায পাওয়া যায়নি।</p>
                </div>
              </template>

              <template #loading>
                <div class="flex justify-center items-center py-10">
                  <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-emerald-500"></div>
                </div>
              </template>
            </DataTable>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { ref, reactive, onMounted, computed } from 'vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';

// Define FilterMatchMode constants
const FilterMatchMode = {
  STARTS_WITH: 'startswith',
  CONTAINS: 'contains',
  EQUALS: 'equals',
  DATE_IS: 'dateIs'
};

// Props
const props = defineProps({
  initialApplications: Object,
  initialDivisions: Array,
  initialDistricts: Array,
  initialThanas: Array,
});

// State Management
const applications = ref(props.initialApplications || {
  data: [],
  current_page: 1,
  last_page: 1,
  total: 0,
  from: 0,
  to: 0
});
const loading = ref(false);
const perPage = ref(10);
const divisions = ref(props.initialDivisions || []);
const districts = ref(props.initialDistricts || []);
const thanas = ref(props.initialThanas || []);
const showAllColumns = ref(true);

// For table column filters
const tableFilterDistricts = ref([]);
const tableFilterThanas = ref([]);
const selectedTableDivision = ref(null);
const selectedTableDistrict = ref(null);

// Filters
const filters = ref({
  global: { value: null, matchMode: FilterMatchMode.CONTAINS },
  name: { value: null, matchMode: FilterMatchMode.CONTAINS },
  Elhaq_no: { value: null, matchMode: FilterMatchMode.CONTAINS },
  MType: { value: null, matchMode: FilterMatchMode.CONTAINS },
  markaz_serial: { value: null, matchMode: FilterMatchMode.CONTAINS },
  id: { value: null, matchMode: FilterMatchMode.CONTAINS },
  mobile_no: { value: null, matchMode: FilterMatchMode.CONTAINS },
  division_name: { value: null, matchMode: FilterMatchMode.EQUALS },
  district_name: { value: null, matchMode: FilterMatchMode.EQUALS },
  thana_name: { value: null, matchMode: FilterMatchMode.EQUALS },
});

// Search filters for the top filter section
const searchFilters = reactive({
  madrasahName: '',
  type: '',
  level: '',
  status: '',
  division: '',
  district: '',
  thana: ''
});

// Check if any filters are active
const hasActiveFilters = computed(() => {
  return Object.values(searchFilters).some(value => value !== '');
});

// Get count of active markaz
const getActiveCount = () => {
  return applications.value?.data?.length || 0;
};

// Get current date time
const getCurrentDateTime = () => {
  return new Date().toLocaleString('bn-BD', {
    day: '2-digit',
    month: '2-digit',
    year: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  });
};

// Format level name
const getLevelName = (level) => {
  const levelMap = {
    'takmil': 'তাকমিল',
    'fazilat': 'ফযিলত',
    'sanabiya_uliya': 'সানাবিয়া উলইয়া',
    'sanabiya': 'সানাবিয়া',
    'mutawassita': 'মুতাওয়াসসিতা',
    'ibtedaiya': 'ইবতেদাইয়া',
    'hifzul_quran': 'হিফজুল কোরআন',
    'ilmul_qiraat': 'ইলমুল কিরাআত'
  };
  return levelMap[level] || level;
};

// Format markaz type name
const getMarkazTypeName = (type) => {
  const typeMap = {
    'darsiyat': 'দরসিয়াত',
    'hifzul_quran': 'হিফজুল কুরআন',
    'ilmul_qiraat': 'ইলমুল কিরাআত',
    'all': 'সকল মারকাজ'
  };
  return typeMap[type] || type;
};

// API Functions
const loadDivisions = async () => {
  try {
    const response = await axios.get('/api/divisions');
    divisions.value = response.data;
  } catch (error) {
    console.error('Error loading divisions:', error);
  }
};

const handleDivisionChange = async () => {
  searchFilters.district = '';
  searchFilters.thana = '';
  districts.value = [];
  thanas.value = [];
  if (searchFilters.division) {
    try {
      const response = await axios.get(`/api/districts/${searchFilters.division}`);
      districts.value = response.data;
    } catch (error) {
      console.error('Error loading districts:', error);
    }
  }
};

const handleDistrictChange = async () => {
  searchFilters.thana = '';
  thanas.value = [];
  if (searchFilters.district) {
    try {
      const response = await axios.get(`/api/thanas/${searchFilters.district}`);
      thanas.value = response.data;
    } catch (error) {
      console.error('Error loading thanas:', error);
    }
  }
};

// Table column filter handlers
const handleTableDivisionChange = async (event, filterModel) => {
  selectedTableDivision.value = event.value;
  tableFilterDistricts.value = [];
  tableFilterThanas.value = [];

  if (event.value) {
    try {
      // Find division ID from name
      const division = divisions.value.find(d => d.Division === event.value);
      if (division) {
        const response = await axios.get(`/api/districts/${division.id}`);
        tableFilterDistricts.value = response.data;
      }
    } catch (error) {
      console.error('Error loading districts for table filter:', error);
    }
  }

  filterModel.value = event.value;
};

const handleTableDistrictChange = async (event, filterModel) => {
  selectedTableDistrict.value = event.value;
  tableFilterThanas.value = [];

  if (event.value) {
    try {
      // Find district ID from name
      const district = tableFilterDistricts.value.find(d => d.District === event.value);
      if (district) {
        const response = await axios.get(`/api/thanas/${district.DesID}`);
        tableFilterThanas.value = response.data;
      }
    } catch (error) {
      console.error('Error loading thanas for table filter:', error);
    }
  }

  filterModel.value = event.value;
};

const fetchMadrasaData = async () => {
  loading.value = true;
  try {
    const response = await axios.get('/api/markaz-list', {
      params: {
        page: applications.value.current_page,
        per_page: perPage.value,
        ...searchFilters
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
  loading.value = true;
  // Trim search term
  if (searchFilters.madrasahName) {
    searchFilters.madrasahName = searchFilters.madrasahName.trim();
  }

  axios.get('/api/madrashas/filter', {
    params: searchFilters
  })
  .then(response => {
    const data = response.data;
    // Data mapping and suggestion logic
    let formattedData = data;

    // Sort suggestions if search term exists
    if (searchFilters.madrasahName && formattedData.length > 0) {
      const searchTerm = searchFilters.madrasahName.toLowerCase();

      formattedData.sort((a, b) => {
        const aName = ((a.name || a.MName) || '').toLowerCase();
        const bName = ((b.name || b.MName) || '').toLowerCase();

        // Exact match first
        if (aName === searchTerm && bName !== searchTerm) return -1;
        if (bName === searchTerm && aName !== searchTerm) return 1;

        // Starts with match second priority
        if (aName.startsWith(searchTerm) && !bName.startsWith(searchTerm)) return -1;
        if (bName.startsWith(searchTerm) && !aName.startsWith(searchTerm)) return 1;

        // Contains match third priority
        if (aName.includes(searchTerm) && !bName.includes(searchTerm)) return -1;
        if (bName.includes(searchTerm) && !aName.includes(searchTerm)) return 1;

        // Alphabetical sort otherwise
        return aName.localeCompare(bName);
      });
    }

    // Data mapping - ensure correct field names
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

    applications.value = {
      data: formattedData,
      total: formattedData.length
    };
  })
  .catch(error => {
    console.error('Error filtering madrasas:', error);
  })
  .finally(() => {
    loading.value = false;
  });
};

const resetFilters = () => {
  Object.keys(searchFilters).forEach(key => {
    searchFilters[key] = '';
  });

  // Reset DataTable filters
  filters.value = {
    global: { value: null, matchMode: FilterMatchMode.CONTAINS },
    name: { value: null, matchMode: FilterMatchMode.CONTAINS },
    Elhaq_no: { value: null, matchMode: FilterMatchMode.CONTAINS },
    MType: { value: null, matchMode: FilterMatchMode.CONTAINS },
    markaz_serial: { value: null, matchMode: FilterMatchMode.CONTAINS },
    id: { value: null, matchMode: FilterMatchMode.CONTAINS },
    mobile_no: { value: null, matchMode: FilterMatchMode.CONTAINS },
    division_name: { value: null, matchMode: FilterMatchMode.EQUALS },
    district_name: { value: null, matchMode: FilterMatchMode.EQUALS },
    thana_name: { value: null, matchMode: FilterMatchMode.EQUALS },
  };

  districts.value = [];
  thanas.value = [];
  tableFilterDistricts.value = [];
  tableFilterThanas.value = [];
  selectedTableDivision.value = null;
  selectedTableDistrict.value = null;

  search();
};

const exportData = async () => {
  try {
    const response = await axios.get('/api/export-madrasa', {
      params: searchFilters,
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
  }
};

const viewApplication = async (application) => {
  try {
    await axios.get(`/api/madrasahs/${application.id}`);
  } catch (error) {
    console.log('Error viewing application:', error);
  }
};

const editApplication = async (application) => {
  try {
    await axios.get(`/api/madrasahs/${application.id}/edit`);
  } catch (error) {
    console.log('Error editing application:', error);
  }
};

const sendMessage = async (application) => {
  alert(`${application.name} কে বার্তা পাঠানো হচ্ছে...`);
  // Implement your message sending functionality here
};

onMounted(() => {
  loadDivisions();
  fetchMadrasaData();
});
</script>

<style scoped>
/* DataTable styling */
:deep(.p-datatable) {
  font-family: 'Merriweather', 'SolaimanLipi', sans-serif;
}

:deep(.p-datatable .p-datatable-header) {
  background: #ffffff;
  border: none;
  padding: 1rem;
}

:deep(.p-datatable .p-datatable-thead > tr > th) {
  background: #f8f9fa;
  color: #4b5563;
  font-weight: 600;
  padding: 0.75rem 1rem;
  border-width: 1px;
  border-color: #e5e7eb;
}

:deep(.p-datatable .p-datatable-thead > tr > th:hover) {
  background: #f3f4f6;
}

:deep(.p-datatable .p-datatable-tbody > tr) {
  background: #ffffff;
  color: #374151;
  transition: all 0.2s ease;
}

:deep(.p-datatable .p-datatable-tbody > tr:hover) {
  background: #f9fafb;
}

:deep(.p-datatable .p-datatable-tbody > tr:nth-child(even)) {
  background: #f9fafb;
}

:deep(.p-datatable .p-datatable-tbody > tr:nth-child(even):hover) {
  background: #f3f4f6;
}

:deep(.p-datatable .p-datatable-tbody > tr > td) {
  padding: 0.75rem 1rem;
  border-width: 1px;
  border-color: #e5e7eb;
}

:deep(.p-paginator) {
  padding: 1rem;
  background: #ffffff;
  border: 1px solid #e5e7eb;
}

:deep(.p-paginator .p-paginator-current) {
  margin-left: auto;
  color: #4b5563;
}

:deep(.p-paginator .p-paginator-page.p-highlight) {
  background: #059669;
  border-color: #059669;
  color: #ffffff;
}

:deep(.p-dropdown) {
  width: 100%;
}

:deep(.p-column-filter) {
  width: 100%;
}

:deep(.p-inputtext:enabled:focus) {
  box-shadow: 0 0 0 2px #d1fae5;
  border-color: #059669;
}

:deep(.p-dropdown:not(.p-disabled).p-focus) {
  box-shadow: 0 0 0 2px #d1fae5;
  border-color: #059669;
}

/* Animations */
@keyframes pulse-gentle {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.8; }
}

.animate-pulse-gentle {
  animation: pulse-gentle 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>
