<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl rounded-xl">
          <!-- Header with Stats -->
          <div class="bg-gradient-to-r from-indigo-50 via-white to-indigo-50 border-b border-gray-200 px-6 py-5">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
              <div class="flex items-center">
                <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center text-indigo-700 mr-3">
                  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                  </svg>
                </div>
                <div>
                  <h2 class="text-2xl font-bold text-gray-900">বিষয় তালিকা</h2>
                  <p class="text-gray-500 mt-1">সকল বিষয়ের তথ্য এবং ব্যবস্থাপনা</p>
                </div>
              </div>

              <div class="flex flex-wrap gap-3">
                <div class="text-center px-4 py-2 bg-indigo-50 rounded-lg shadow-sm">
                  <div class="text-xl font-bold text-indigo-600">{{ filteredSubjects.length }}</div>
                  <div class="text-xs text-indigo-700">মোট বিষয়</div>
                </div>

                <div class="text-center px-4 py-2 bg-green-50 rounded-lg shadow-sm">
                  <div class="text-xl font-bold text-green-600">{{ getActiveCount() }}</div>
                  <div class="text-xs text-green-700">সক্রিয় বিষয়</div>
                </div>

                <Link
                  :href="route('subjects_for_Admin.subject_setup_list')"
                  class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 border border-transparent rounded-lg text-white shadow-sm hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all"
                >
                  <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  নতুন বিষয় সেটাপ
                </Link>
              </div>
            </div>
          </div>

          <div class="p-6">
            <!-- Search and Filters Card -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden mb-6">
              <div class="px-5 py-4 bg-gradient-to-r from-gray-50 to-white border-b border-gray-200 flex items-center">
                <svg class="w-5 h-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                </svg>
                <h3 class="text-base font-medium text-gray-900">অনুসন্ধান এবং ফিল্টার</h3>
              </div>

              <div class="p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-5">
                  <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">অনুসন্ধান</label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                      </div>
                      <input
                        type="text"
                        v-model="searchTerm"
                        placeholder="বিষয় / কোড লিখুন"
                        class="pl-10 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3"
                      />
                    </div>
                  </div>

                  <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">মারকাযের ধরন</label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                      </div>
                      <select
                        v-model="markazType"
                        class="pl-10 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 appearance-none bg-white"
                      >
                        <option value="">নির্বাচন করুন</option>
                        <option value="দরসিয়াত">দরসিয়াত</option>
                        <option value="হিফজুল কোরআন">হিফজুল কোরআন</option>
                        <option value="কিরাআত">কিরাআত</option>
                      </select>
                      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </div>
                    </div>
                  </div>

                  <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">মারহালা</label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                      </div>
                      <select
                        v-model="marhalaType"
                        class="pl-10 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 appearance-none bg-white"
                      >
                        <option value="">নির্বাচন করুন</option>
                        <option v-for="type in marhalaTypes" :key="type" :value="type">{{ type }}</option>
                      </select>
                      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </div>
                    </div>
                  </div>

                  <div class="space-y-2">
                    <label class="block text-sm font-medium text-gray-700">স্ট্যাটাস</label>
                    <div class="relative">
                      <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                      </div>
                      <select
                        v-model="status"
                        class="pl-10 block w-full border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 py-2 px-3 appearance-none bg-white"
                      >
                        <option value="">নির্বাচন করুন</option>
                        <option value="active">সক্রিয়</option>
                        <option value="inactive">নিষ্ক্রিয়</option>
                      </select>
                      <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                      </div>
                    </div>
                  </div>

                  <div class="flex items-end space-x-2">
                    <button
                      @click="resetFilters"
                      class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                    >
                      <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                      </svg>
                      রিসেট
                    </button>
                    <button
                      @click="fetchData"
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                    >
                      <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                      </svg>
                      অনুসন্ধান করুন
                    </button>
                  </div>
                </div>

                <!-- Applied Filters -->
                <div v-if="hasActiveFilters" class="mt-4 flex flex-wrap gap-2">
                  <div class="text-sm font-medium text-gray-700 flex items-center">
                    <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
                    </svg>
                    সক্রিয় ফিল্টার:
                  </div>
                  <div v-if="searchTerm" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                    অনুসন্ধান: "{{ searchTerm }}"
                    <button @click="searchTerm = ''" class="ml-1 text-indigo-600 hover:text-indigo-800">
                      <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                  <div v-if="markazType" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    মারকাযের ধরন: {{ markazType }}
                    <button @click="markazType = ''" class="ml-1 text-blue-600 hover:text-blue-800">
                      <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                  <div v-if="marhalaType" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                    মারহালা: {{ marhalaType }}
                    <button @click="marhalaType = ''" class="ml-1 text-green-600 hover:text-green-800">
                      <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                  <div v-if="status" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                    স্ট্যাটাস: {{ status === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                    <button @click="status = ''" class="ml-1 text-purple-600 hover:text-purple-800">
                      <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                      </svg>
                    </button>
                  </div>
                </div>
              </div>
            </div>

            <!-- Results Count -->
            <div v-if="filteredSubjects.length > 0" class="flex justify-between items-center mb-4">
              <div class="text-sm text-gray-600">
                {{ filteredSubjects.length }} টি বিষয় পাওয়া গেছে
              </div>
              <div class="flex items-center space-x-2 text-sm text-gray-600">
                <span>সাজানঃ</span>
                <select
                  v-model="sortOption"
                  class="border border-gray-300 rounded px-2 py-1 text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                  @change="handleSortChange"
                >
                  <option value="code-asc">কোড (A-Z)</option>
                  <option value="code-desc">কোড (Z-A)</option>
                  <option value="Subject_Names-asc">বিষয় (A-Z)</option>
                  <option value="Subject_Names-desc">বিষয় (Z-A)</option>
                </select>
              </div>
            </div>

            <!-- Table -->
            <div class="bg-white overflow-hidden border border-gray-200 rounded-xl shadow-sm">
              <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                          @click="sortBy('code')">
                        <div class="flex items-center">
                          <span>কোড</span>
                          <span class="ml-1" v-if="sortField === 'code'">
                            <svg v-if="sortDirection === 'asc'" class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                            <svg v-else class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                          </span>
                        </div>
                      </th>
                      <th scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                          @click="sortBy('Subject_Names')">
                        <div class="flex items-center">
                          <span>বিষয়</span>
                          <span class="ml-1" v-if="sortField === 'Subject_Names'">
                            <svg v-if="sortDirection === 'asc'" class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                            <svg v-else class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                          </span>
                        </div>
                      </th>
                      <th scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                          @click="sortBy('Marhala_type')">
                        <div class="flex items-center">
                          <span>মারহালা</span>
                          <span class="ml-1" v-if="sortField === 'Marhala_type'">
                            <svg v-if="sortDirection === 'asc'" class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                            <svg v-else class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                          </span>
                        </div>
                      </th>
                      <th scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                          @click="sortBy('student_type')">
                        <div class="flex items-center">
                          <span>বালক/বালিকা</span>
                          <span class="ml-1" v-if="sortField === 'student_type'">
                            <svg v-if="sortDirection === 'asc'" class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                            <svg v-else class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                          </span>
                        </div>
                      </th>
                      <th scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                          @click="sortBy('markaz_type')">
                        <div class="flex items-center">
                          <span>মারকাযের ধরন</span>
                          <span class="ml-1" v-if="sortField === 'markaz_type'">
                            <svg v-if="sortDirection === 'asc'" class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                            <svg v-else class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                          </span>
                        </div>
                      </th>
                      <th scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                          @click="sortBy('total_marks')">
                        <div class="flex items-center">
                          <span>মোট নাম্বার</span>
                          <span class="ml-1" v-if="sortField === 'total_marks'">
                            <svg v-if="sortDirection === 'asc'" class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                            <svg v-else class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                          </span>
                        </div>
                      </th>
                      <th scope="col"
                          class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100 transition-colors"
                          @click="sortBy('status')">
                        <div class="flex items-center">
                          <span>স্ট্যাটাস</span>
                          <span class="ml-1" v-if="sortField === 'status'">
                            <svg v-if="sortDirection === 'asc'" class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                            </svg>
                            <svg v-else class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                          </span>
                        </div>
                      </th>
                      <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                        কর্মসূচী
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="subject in sortedSubjects" :key="subject.id" class="hover:bg-gray-50 transition-colors">
                      <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ subject.code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <div class="font-medium text-gray-900">{{ subject.Subject_Names }}</div>
                        <div class="text-xs text-gray-500">{{ subject.subject_type }}</div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                          {{ subject.Marhala_type }}
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="flex items-center">
                          <svg v-if="subject.student_type === 'ছাত্র'" class="w-4 h-4 mr-1 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                          </svg>
                          <svg v-else-if="subject.student_type === 'ছাত্রী'" class="w-4 h-4 mr-1 text-pink-600" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                          </svg>
                          <svg v-else class="w-4 h-4 mr-1 text-purple-600" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z" />
                          </svg>
                          {{ subject.student_type }}
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ subject.markaz_type }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-center">
                        <div class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                          {{ subject.total_marks }}
                          <span class="ml-1 text-xs text-gray-500">({{ subject.pass_marks }})</span>
                        </div>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <span
                          :class="[
                            'inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium',
                            subject.status === 'active'
                              ? 'bg-green-100 text-green-800'
                              : 'bg-red-100 text-red-800'
                          ]"
                        >
                          <span class="w-1.5 h-1.5 rounded-full mr-1.5"
                                :class="subject.status === 'active' ? 'bg-green-500' : 'bg-red-500'"></span>
                          {{ subject.status === 'active' ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-center space-x-2">
                          <Link
                            :href="route('subjects_for_Admin.subject_setings_edit', { marhala: subject.id })"
                            class="inline-flex items-center p-1.5 border border-yellow-300 rounded-md bg-yellow-50 text-yellow-600 hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition-colors"
                            title="সংশোধন করুন"
                          >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                          </Link>
                          <button
                            @click="viewSubjectDetails(subject)"
                            class="inline-flex items-center p-1.5 border border-blue-300 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                            title="বিস্তারিত দেখুন"
                          >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                          </button>
                        </div>
                      </td>
                    </tr>

                    <!-- Empty state when no subjects are available -->
                    <tr v-if="sortedSubjects.length === 0">
                      <td colspan="11" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                          <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                          </svg>
                          <h3 class="mt-2 text-lg font-medium text-gray-900">কোন বিষয় পাওয়া যায়নি</h3>
                          <p class="mt-1 text-sm text-gray-500">আপনার অনুসন্ধানের সাথে মিলে এমন কোন বিষয় নেই।</p>
                          <div class="mt-6">
                            <button
                              @click="resetFilters"
                              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                            >
                              <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                              </svg>
                              ফিল্টার রিসেট করুন
                            </button>
                          </div>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Pagination -->
            <div v-if="sortedSubjects.length > 0" class="mt-5 flex flex-col sm:flex-row items-center justify-between gap-4 bg-white px-4 py-3 border border-gray-200 rounded-lg shadow-sm">
              <div class="flex-1 flex justify-between sm:hidden">
                <button @click="prevPage" :disabled="currentPage === 1"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                  পূর্ববর্তী
                </button>
                <button @click="nextPage" :disabled="currentPage === totalPages"
                        class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">
                  পরবর্তী
                </button>
              </div>

              <div class="text-sm text-gray-700">
                মোট <span class="font-medium">{{ filteredSubjects.length }}</span> টি রেজাল্ট থেকে
                <span class="font-medium">{{ startIndex + 1 }}</span> থেকে
                <span class="font-medium">{{ endIndex }}</span> পর্যন্ত দেখানো হচ্ছে
              </div>

              <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                  <button @click="firstPage" :disabled="currentPage === 1"
                          class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span class="sr-only">প্রথম</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                  </button>

                  <button @click="prevPage" :disabled="currentPage === 1"
                          class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span class="sr-only">পূর্ববর্তী</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                  </button>

                  <!-- Page numbers -->
                  <template v-for="page in displayedPages" :key="page">
                    <button
                      v-if="page !== '...'"
                      @click="goToPage(page)"
                      :class="[
                        'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                        currentPage === page
                          ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600'
                          : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                        'transition-colors'
                      ]"
                    >
                      {{ page }}
                    </button>
                    <span
                      v-else
                      class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                    >
                      ...
                    </span>
                  </template>

                  <button @click="nextPage" :disabled="currentPage === totalPages"
                          class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span class="sr-only">পরবর্তী</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                  </button>

                  <button @click="lastPage" :disabled="currentPage === totalPages"
                          class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors">
                    <span class="sr-only">শেষ</span>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </nav>
              </div>
            </div>

            <!-- Subject Details Modal (can be implemented) -->

          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { ref, onMounted, watch, computed } from 'vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';

// Data
const subjects = ref([]);
const searchTerm = ref('');
const markazType = ref('');
const marhalaType = ref('');
const status = ref('');
const marhalaTypes = ref([]);

// Sorting
const sortField = ref('code');
const sortDirection = ref('asc');
const sortOption = ref('code-asc');

// Pagination
const currentPage = ref(1);
const rowsPerPage = ref(10);
const selectedSubject = ref(null);

// Check if any filters are active
const hasActiveFilters = computed(() => {
  return searchTerm.value || markazType.value || marhalaType.value || status.value;
});

// Fetch data from API
const fetchData = async () => {
  try {
    const response = await axios.get('/api/subjects', {
      params: {
        search_term: searchTerm.value,
        markaz_type: markazType.value,
        marhala_type: marhalaType.value,
        status: status.value
      }
    });
    subjects.value = response.data.subjects;
    marhalaTypes.value = response.data.marhalaTypes;
    currentPage.value = 1; // Reset to first page when filters change
  } catch (error) {
    console.error('Error fetching data:', error);
  }
};

// Reset all filters
const resetFilters = () => {
  searchTerm.value = '';
  markazType.value = '';
  marhalaType.value = '';
  status.value = '';
  fetchData();
};

// Sorting function
const sortBy = (field) => {
  if (sortField.value === field) {
    sortDirection.value = sortDirection.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortField.value = field;
    sortDirection.value = 'asc';
  }

  // Update sortOption to match
  sortOption.value = `${field}-${sortDirection.value}`;
};

// Handle sort dropdown change
const handleSortChange = () => {
  const [field, direction] = sortOption.value.split('-');
  sortField.value = field;
  sortDirection.value = direction;
};

// Get count of active subjects
const getActiveCount = () => {
  return subjects.value.filter(subject => subject.status === 'active').length;
};

// View subject details (placeholder function)
const viewSubjectDetails = (subject) => {
  selectedSubject.value = subject;
  alert(`বিষয় বিস্তারিত: ${subject.Subject_Names}`);
  // Implement a modal or navigation to detailed view
};

// Computed properties for filtering and sorting
const filteredSubjects = computed(() => {
  return subjects.value;
});

const sortedSubjects = computed(() => {
  const sorted = [...filteredSubjects.value].sort((a, b) => {
    const fieldA = a[sortField.value];
    const fieldB = b[sortField.value];

    if (fieldA < fieldB) return sortDirection.value === 'asc' ? -1 : 1;
    if (fieldA > fieldB) return sortDirection.value === 'asc' ? 1 : -1;
    return 0;
  });

  // Apply pagination
  const start = (currentPage.value - 1) * rowsPerPage.value;
  const end = start + rowsPerPage.value;
  return sorted.slice(start, end);
});

// Pagination computed properties
const totalPages = computed(() => {
  return Math.ceil(filteredSubjects.value.length / rowsPerPage.value);
});

const startIndex = computed(() => {
  return (currentPage.value - 1) * rowsPerPage.value;
});

const endIndex = computed(() => {
  const end = startIndex.value + rowsPerPage.value;
  return end > filteredSubjects.value.length ? filteredSubjects.value.length : end;
});

const displayedPages = computed(() => {
  const pages = [];
  const maxVisiblePages = 5;

  if (totalPages.value <= maxVisiblePages) {
    // Show all pages if total pages are less than max visible
    for (let i = 1; i <= totalPages.value; i++) {
      pages.push(i);
    }
  } else {
    // Always include first page, last page, current page, and pages around current
    const leftBound = Math.max(1, currentPage.value - 1);
    const rightBound = Math.min(totalPages.value, currentPage.value + 1);

    if (leftBound > 1) pages.push(1);
    if (leftBound > 2) pages.push('...');

    for (let i = leftBound; i <= rightBound; i++) {
      pages.push(i);
    }

    if (rightBound < totalPages.value - 1) pages.push('...');
    if (rightBound < totalPages.value) pages.push(totalPages.value);
  }

  return pages;
});

// Pagination methods
const goToPage = (page) => {
  if (typeof page === 'number') {
    currentPage.value = page;
  }
};

const prevPage = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};

const nextPage = () => {
  if (currentPage.value < totalPages.value) {
    currentPage.value++;
  }
};

const firstPage = () => {
  currentPage.value = 1;
};

const lastPage = () => {
  currentPage.value = totalPages.value;
};

// Watch for changes in filter values
watch([searchTerm, markazType, marhalaType, status], () => {
  fetchData();
});

// Initialize data on component mount
onMounted(() => {
  fetchData();
});
</script>

<style scoped>
/* Button and input transitions */
button, a, input, select {
  transition: all 0.2s ease;
}

/* Improved table hover effect */
tbody tr {
  transition: background-color 0.2s ease;
}

/* Custom scrollbar for better UX */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: #c5c5c5;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a0a0a0;
}
</style>
