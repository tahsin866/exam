<template>
  <AuthenticatedLayout>
    <div class="py-6 px-4 md:px-6 lg:px-8 bg-gradient-to-br from-gray-50 to-emerald-50 min-h-screen">
      <!-- Header Dashboard Card -->
      <div class="mb-6 bg-white rounded-xl shadow-md overflow-hidden border border-emerald-100">
        <div class="px-6 py-5 bg-gradient-to-r from-emerald-600 to-teal-500 text-white">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <div class="bg-white/20 p-2.5 rounded-lg mr-4">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
              <div>
                <h1 class="text-xl font-bold leading-tight">পরীক্ষা ফি সেটাপ</h1>
                <p class="text-sm text-emerald-100">{{ formattedTitle }}</p>
              </div>
            </div>
            <div class="hidden md:flex items-center space-x-4">
              <div class="bg-white/10 px-3 py-1.5 rounded-lg text-sm">
                <span class="opacity-80">তারিখ:</span> {{ getCurrentDate() }}
              </div>
              <div class="bg-white/10 px-3 py-1.5 rounded-lg text-sm">
                <span class="opacity-80">সময়:</span> {{ getCurrentTime() }}
              </div>
            </div>
          </div>
        </div>

        <div class="p-5 bg-emerald-50/50 border-b border-emerald-100">
          <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex items-center gap-4">
              <div class="px-4 py-2 bg-white rounded-lg shadow-sm border border-emerald-100">
                <div class="text-xs text-gray-500">মোট মারহালা</div>
                <div class="text-lg font-bold text-gray-800">{{ rows.length }}</div>
              </div>
              <div class="px-4 py-2 bg-white rounded-lg shadow-sm border border-emerald-100">
                <div class="text-xs text-gray-500">কেন্দ্রীয় পরীক্ষা</div>
                <div class="text-lg font-bold text-gray-800">{{ examSetup?.exam_name || 'N/A' }}</div>
              </div>
              <div class="px-4 py-2 bg-white rounded-lg shadow-sm border border-emerald-100">
                <div class="text-xs text-gray-500">শিক্ষাবর্ষ</div>
                <div class="text-lg font-bold text-gray-800">{{ examSetup?.english_year || 'N/A' }} ইসাব্দ</div>
              </div>
            </div>

            <div class="flex flex-wrap gap-3">
              <button @click="downloadExcel"
                      class="flex items-center gap-2 px-4 py-2 bg-emerald-50 border border-emerald-200 rounded-lg text-emerald-700 hover:bg-emerald-100 transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                এক্সেল ডাউনলোড
              </button>
              <button @click="toggleExpand"
                      class="flex items-center gap-2 px-4 py-2 bg-blue-50 border border-blue-200 rounded-lg text-blue-700 hover:bg-blue-100 transition duration-150">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path v-if="expandAll" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                  <path v-else stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                {{ expandAll ? 'সব সংকুচিত করুন' : 'সব বিস্তৃত করুন' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Fee Setup Cards -->
      <div class="grid grid-cols-1 gap-6">
        <div v-for="(row, index) in rows" :key="index"
             class="bg-white rounded-xl shadow-md overflow-hidden border border-emerald-100 transition-all duration-300"
             :class="{'ring-2 ring-emerald-300': expandedCards[index]}">
          <!-- Card Header -->
          <div @click="toggleCard(index)"
               class="flex items-center justify-between px-6 py-4 cursor-pointer bg-gradient-to-r"
               :class="expandedCards[index] ? 'from-emerald-50 to-emerald-100 border-b border-emerald-200' : 'from-white to-gray-50'">
            <div class="flex items-center space-x-4">
              <div class="h-10 w-10 flex items-center justify-center rounded-full bg-emerald-100 text-emerald-700 font-bold text-lg">
                {{ index + 1 }}
              </div>
              <div>
                <h3 class="text-lg font-bold text-gray-800">{{ row.examName }}</h3>
                <p class="text-sm text-gray-500">পরীক্ষা ফি সেটআপ</p>
              </div>
            </div>
            <div class="flex items-center gap-3">
              <div v-if="isCardFilled(row)" class="px-3 py-1 rounded-full bg-emerald-100 text-emerald-800 text-xs">
                <span class="flex items-center gap-1">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  সম্পূর্ণ
                </span>
              </div>
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 transition-transform duration-300"
                   :class="{'rotate-180': expandedCards[index]}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
              </svg>
            </div>
          </div>

          <!-- Card Body (Collapsible) -->
          <div v-show="expandedCards[index]" class="p-6 bg-white">
            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-6">
              <div class="flex -mb-px">
                <button @click="activeTab[index] = 'regular'"
                        class="px-4 py-2 font-medium text-sm border-b-2 transition-colors duration-200 mr-4"
                        :class="activeTab[index] === 'regular' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700'">
                  নিয়মিত ফি
                </button>
                <button @click="activeTab[index] = 'late'"
                        class="px-4 py-2 font-medium text-sm border-b-2 transition-colors duration-200"
                        :class="activeTab[index] === 'late' ? 'border-emerald-500 text-emerald-600' : 'border-transparent text-gray-500 hover:text-gray-700'">
                  বিলম্ব ফি
                </button>
              </div>
            </div>

            <!-- Regular Fee Section -->
            <div v-if="activeTab[index] === 'regular'">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date Range -->
                <div class="bg-gray-50 p-4 rounded-lg">
                  <h4 class="text-sm font-medium text-gray-700 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    নিবন্ধন সময়সীমা
                  </h4>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-xs text-gray-500 mb-1">শুরুর তারিখ</label>
                      <div class="relative">
                        <input type="date" v-model="row.dateFrom1"
                              class="block w-full border border-gray-300 rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                      </div>
                    </div>

                    <div>
                      <label class="block text-xs text-gray-500 mb-1">শেষের তারিখ</label>
                      <div class="relative">
                        <input type="date" v-model="row.dateTo1"
                              class="block w-full border border-gray-300 rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500" />
                      </div>
                    </div>
                  </div>

                  <div class="text-xs text-gray-500 mt-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    নিবন্ধন কাল:
                    <span class="ml-1 font-medium text-emerald-700">
                      {{ calculateDateDifference(row.dateFrom1, row.dateTo1) }}
                    </span>
                  </div>
                </div>

                <!-- Fee Inputs -->
                <div class="bg-gray-50 p-4 rounded-lg">
                  <h4 class="text-sm font-medium text-gray-700 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-emerald-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    নিবন্ধন ফি
                  </h4>

                  <div class="space-y-4">
                    <div class="flex items-center">
                      <div class="w-36 text-sm text-gray-700">নিয়মিত ফি:</div>
                      <div class="flex-1">
                        <div class="relative rounded-md">
                          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">৳</span>
                          </div>
                          <input type="number" v-model="row.fee1"
                                class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="0.00" />
                        </div>
                      </div>
                    </div>

                    <div class="flex items-center">
                      <div class="w-36 text-sm text-gray-700">অনিয়মিত (যেমনি):</div>
                      <div class="flex-1">
                        <div class="relative rounded-md">
                          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">৳</span>
                          </div>
                          <input type="number" v-model="row.invest1Men"
                                class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="0.00" />
                        </div>
                      </div>
                    </div>

                    <div class="flex items-center">
                      <div class="w-36 text-sm text-gray-700">অনিয়মিত (মানোন্নয়ন):</div>
                      <div class="flex-1">
                        <div class="relative rounded-md">
                          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">৳</span>
                          </div>
                          <input type="number" v-model="row.invest1Madan"
                                class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="0.00" />
                        </div>
                      </div>
                    </div>

                    <div class="flex items-center">
                      <div class="w-36 text-sm text-gray-700">অনিয়মিত (অন্যান্য):</div>
                      <div class="flex-1">
                        <div class="relative rounded-md">
                          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">৳</span>
                          </div>
                          <input type="number" v-model="row.invest1Others"
                                class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500"
                                placeholder="0.00" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Late Fee Section -->
            <div v-if="activeTab[index] === 'late'">
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Date Range -->
                <div class="bg-gray-50 p-4 rounded-lg">
                  <h4 class="text-sm font-medium text-gray-700 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    বিলম্ব ফি সময়সীমা
                  </h4>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                      <label class="block text-xs text-gray-500 mb-1">শুরুর তারিখ</label>
                      <div class="relative">
                        <input type="date" v-model="row.dateFrom2"
                              class="block w-full border border-gray-300 rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500" />
                      </div>
                    </div>

                    <div>
                      <label class="block text-xs text-gray-500 mb-1">শেষের তারিখ</label>
                      <div class="relative">
                        <input type="date" v-model="row.dateTo2"
                              class="block w-full border border-gray-300 rounded-md py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500" />
                      </div>
                    </div>
                  </div>

                  <div class="text-xs text-gray-500 mt-3 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 mr-1 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    বিলম্ব কাল:
                    <span class="ml-1 font-medium text-amber-700">
                      {{ calculateDateDifference(row.dateFrom2, row.dateTo2) }}
                    </span>
                  </div>
                </div>

                <!-- Fee Inputs -->
                <div class="bg-gray-50 p-4 rounded-lg">
                  <h4 class="text-sm font-medium text-gray-700 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    বিলম্ব ফি
                  </h4>

                  <div class="space-y-4">
                    <div class="flex items-center">
                      <div class="w-36 text-sm text-gray-700">নিয়মিত ফি:</div>
                      <div class="flex-1">
                        <div class="relative rounded-md">
                          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">৳</span>
                          </div>
                          <input type="number" v-model="row.fee2"
                                class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                placeholder="0.00" />
                        </div>
                      </div>
                    </div>

                    <div class="flex items-center">
                      <div class="w-36 text-sm text-gray-700">অনিয়মিত (যেমনি):</div>
                      <div class="flex-1">
                        <div class="relative rounded-md">
                          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">৳</span>
                          </div>
                          <input type="number" v-model="row.invest2Men"
                                class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                placeholder="0.00" />
                        </div>
                      </div>
                    </div>

                    <div class="flex items-center">
                      <div class="w-36 text-sm text-gray-700">অনিয়মিত (মানোন্নয়ন):</div>
                      <div class="flex-1">
                        <div class="relative rounded-md">
                          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">৳</span>
                          </div>
                          <input type="number" v-model="row.invest2Madan"
                                class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                placeholder="0.00" />
                        </div>
                      </div>
                    </div>

                    <div class="flex items-center">
                      <div class="w-36 text-sm text-gray-700">অনিয়মিত (অন্যান্য):</div>
                      <div class="flex-1">
                        <div class="relative rounded-md">
                          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">৳</span>
                          </div>
                          <input type="number" v-model="row.invest2Others"
                                class="block w-full pl-8 pr-3 py-2 border border-gray-300 rounded-md text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                                placeholder="0.00" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Summary and Extra Actions -->
            <div class="mt-6 p-4 bg-gray-50 rounded-lg">
              <div class="flex flex-wrap items-center justify-between gap-4">
                <div>
                  <h4 class="text-sm font-medium text-gray-700 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                    </svg>
                    ফি সারসংক্ষেপ
                  </h4>
                  <div class="mt-2 grid grid-cols-2 gap-x-8 gap-y-1 text-sm">
                    <div class="text-gray-500">নিয়মিত ফি:</div>
                    <div class="text-gray-700 font-medium">৳ {{ formatAmount(row.fee1) }}</div>

                    <div class="text-gray-500">বিলম্ব ফি:</div>
                    <div class="text-gray-700 font-medium">৳ {{ formatAmount(row.fee2) }}</div>
                  </div>
                </div>

                <div class="flex gap-2">
                  <button @click="copyToAllCards(row)"
                          class="inline-flex items-center px-3 py-1.5 bg-blue-50 border border-blue-200 rounded-lg text-blue-700 hover:bg-blue-100 transition duration-150 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" />
                    </svg>
                    সব কার্ডে কপি করুন
                  </button>
                  <button @click="resetSingleCard(index)"
                          class="inline-flex items-center px-3 py-1.5 bg-red-50 border border-red-200 rounded-lg text-red-700 hover:bg-red-100 transition duration-150 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                    </svg>
                    রিসেট করুন
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Submit Button - Fixed at Bottom -->
      <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-4 flex justify-end shadow-lg z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex justify-between items-center">
          <div class="flex items-center">
            <div class="bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full text-sm flex items-center">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              সম্পূর্ণ করা হয়েছে:
              <span class="ml-1.5 font-bold">{{ filledCards() }} / {{ rows.length }}</span>
            </div>
          </div>

          <div class="flex gap-3">
            <button @click="resetForm"
                    class="inline-flex items-center px-4 py-2.5 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-150">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
              </svg>
              সব রিসেট করুন
            </button>
            <button @click="submit"
                    class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-emerald-600 to-teal-500 border border-transparent rounded-lg text-white hover:from-emerald-700 hover:to-teal-600 focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-150 shadow-md">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
              সংরক্ষণ করুন
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { ref, computed, reactive, onMounted } from 'vue';
import axios from 'axios';

// State
const examSetup = ref(null);
const rows = ref([]);
const expandedCards = ref({});
const activeTab = ref({});
const expandAll = ref(false);

// Format title
const formattedTitle = computed(() => {
  if (!examSetup.value) return '';
  return `${examSetup.value.exam_name} ${examSetup.value.arabic_year} হিজরি/${examSetup.value.bangla_year} বঙ্গাব্দ/${examSetup.value.english_year} ইসাব্দ`;
});

// Get current date in Bangla format
const getCurrentDate = () => {
  const date = new Date();
  return date.toLocaleDateString('bn-BD', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
};

// Get current time
const getCurrentTime = () => {
  const date = new Date();
  return date.toLocaleTimeString('bn-BD', {
    hour: '2-digit',
    minute: '2-digit'
  });
};

// Check if a card has data filled
const isCardFilled = (row) => {
  return row.dateFrom1 && row.dateTo1 && row.fee1 &&
         row.dateFrom2 && row.dateTo2 && row.fee2;
};

// Count filled cards
const filledCards = () => {
  return rows.value.filter(row => isCardFilled(row)).length;
};

// Format amount with Bangla numerals
const formatAmount = (amount) => {
  if (!amount) return '0';
  return new Intl.NumberFormat('bn-BD').format(amount);
};

// Calculate date difference
const calculateDateDifference = (start, end) => {
  if (!start || !end) return 'নির্ধারিত নয়';

  const startDate = new Date(start);
  const endDate = new Date(end);

  // Check if dates are valid
  if (isNaN(startDate.getTime()) || isNaN(endDate.getTime())) {
    return 'অবৈধ তারিখ';
  }

  // If end date is before start date
  if (endDate < startDate) {
    return 'অবৈধ সময়কাল';
  }

  // Calculate difference in days
  const diffTime = Math.abs(endDate - startDate);
  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  return `${diffDays} দিন`;
};

// Toggle card expansion
const toggleCard = (index) => {
  expandedCards.value = {
    ...expandedCards.value,
    [index]: !expandedCards.value[index]
  };

  // Initialize active tab if not already set
  if (!activeTab.value[index]) {
    activeTab.value[index] = 'regular';
  }
};

// Toggle all cards
const toggleExpand = () => {
  expandAll.value = !expandAll.value;

  const newState = {};
  rows.value.forEach((_, index) => {
    newState[index] = expandAll.value;
    if (expandAll.value && !activeTab.value[index]) {
      activeTab.value[index] = 'regular';
    }
  });

  expandedCards.value = newState;
};

// Copy one card's data to all cards
const copyToAllCards = (sourceRow) => {
  if (confirm('আপনি কি নিশ্চিত যে এই কার্ডের ডাটা সমস্ত কার্ডে কপি করতে চান?')) {
    rows.value = rows.value.map(row => {
      return {
        ...row,
        dateFrom1: sourceRow.dateFrom1,
        dateTo1: sourceRow.dateTo1,
        fee1: sourceRow.fee1,
        invest1Men: sourceRow.invest1Men,
        invest1Madan: sourceRow.invest1Madan,
        invest1Others: sourceRow.invest1Others,
        dateFrom2: sourceRow.dateFrom2,
        dateTo2: sourceRow.dateTo2,
        fee2: sourceRow.fee2,
        invest2Men: sourceRow.invest2Men,
        invest2Madan: sourceRow.invest2Madan,
        invest2Others: sourceRow.invest2Others
      };
    });
  }
};

// Reset a single card's data
const resetSingleCard = (index) => {
  if (confirm('আপনি কি নিশ্চিত যে এই কার্ডের ডাটা রিসেট করতে চান?')) {
    rows.value[index] = {
      ...rows.value[index],
      dateFrom1: null,
      dateTo1: null,
      fee1: null,
      invest1Men: null,
      invest1Madan: null,
      invest1Others: null,
      dateFrom2: null,
      dateTo2: null,
      fee2: null,
      invest2Men: null,
      invest2Madan: null,
      invest2Others: null
    };
  }
};

// Fetch exam setup data
const fetchExamSetup = async () => {
  try {
    const response = await axios.get('/api/exam-setups/latest');
    examSetup.value = response.data;
  } catch (error) {
    console.error('Error fetching exam setup:', error);
  }
};

// Load marhalas and create rows
const loadMarhalas = async () => {
  try {
    const response = await axios.get('/api/marhalas');
    rows.value = response.data.map(marhala => ({
      examName: marhala.marhala_name_bn,
      dateFrom1: null,
      dateTo1: null,
      fee1: null,
      invest1Men: null,
      invest1Madan: null,
      invest1Others: null,
      dateFrom2: null,
      dateTo2: null,
      fee2: null,
      invest2Men: null,
      invest2Madan: null,
      invest2Others: null
    }));

    // Initialize the first card as expanded
    if (rows.value.length > 0) {
      expandedCards.value = { 0: true };
      activeTab.value = { 0: 'regular' };
    }
  } catch (error) {
    console.error('Error loading marhalas:', error);
  }
};

// Reset form
const resetForm = () => {
  if (confirm('আপনি কি নিশ্চিত যে সমস্ত ডাটা রিসেট করতে চান?')) {
    loadMarhalas();
  }
};

// Excel download function
const downloadExcel = () => {
  alert('এক্সেল ডাউনলোড ফাংশন বাস্তবায়ন করা হবে।');
};

// Submit form
const submit = async () => {
  try {
    const feesData = rows.value.map(row => ({
      exam_setup_id: examSetup.value.id,
      exam_name: row.examName,
      reg_date_from: row.dateFrom1,
      reg_date_to: row.dateTo1,
      reg_regular_fee: row.fee1,
      reg_irregular_jemni: row.invest1Men,
      reg_irregular_manonnoyon: row.invest1Madan,
      reg_irregular_others: row.invest1Others,
      late_date_from: row.dateFrom2,
      late_date_to: row.dateTo2,
      late_regular_fee: row.fee2,
      late_irregular_jemni: row.invest2Men,
      late_irregular_manonnoyon: row.invest2Madan,
      late_irregular_others: row.invest2Others
    }));

    // Show loading indicator or toast notification here

    const response = await axios.post('/api/exam-fees', { fees: feesData });
    alert('পরীক্ষার ফি সফলভাবে সংরক্ষণ করা হয়েছে!');
  } catch (error) {
    console.error('Error submitting data:', error.response?.data);
    alert('ডাটা সংরক্ষণে সমস্যা হয়েছে!');
  }
};

onMounted(async () => {
  await fetchExamSetup();
  await loadMarhalas();
});
</script>

<style scoped>
/* Card transition effects */
.card-enter-active, .card-leave-active {
  transition: all 0.3s ease;
}

.card-enter-from, .card-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

/* Custom focus and hover effects */
input:focus, button:focus {
  outline: none;
  box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.2);
}

input[type="date"]::-webkit-calendar-picker-indicator {
  filter: invert(0.5);
  cursor: pointer;
  opacity: 0.6;
}

input[type="date"]::-webkit-calendar-picker-indicator:hover {
  opacity: 1;
}

/* Space at bottom to account for fixed submit button */
body {
  padding-bottom: 80px;
}

/* Fix PrimeVue components to match our design */
:deep(.p-component) {
  font-family: inherit;
}

:deep(.p-inputtext) {
  font-family: inherit;
}

:deep(.p-datatable-wrapper) {
  overflow: visible;
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 6px;
}

::-webkit-scrollbar-thumb {
  background: #c5c5c5;
  border-radius: 6px;
}

::-webkit-scrollbar-thumb:hover {
  background: #a0a0a0;
}
</style>
