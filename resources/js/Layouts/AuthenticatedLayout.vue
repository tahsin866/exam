<template>
  <div style="font-family: 'SolaimanLipi', sans-serif;" class="flex min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Sidebar -->
    <aside
      :class="[
        'fixed inset-y-0 left-0 z-40 w-64 transform transition-transform duration-300 bg-white dark:bg-gray-800 border-r border-gray-200 dark:border-gray-700 shadow-sm',
        showSidebar ? 'translate-x-0' : '-translate-x-full',
        'lg:translate-x-0'
      ]"
    >
      <!-- Header -->
      <div class="h-16 flex items-center justify-center border-b border-gray-200 dark:border-gray-700">
        <div class="flex items-center gap-2 text-gray-800 dark:text-gray-200 font-semibold">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          এক্সাম মেনেজমেন্ট সিস্টেম
        </div>
      </div>

      <nav class="mt-2 overflow-y-auto max-h-[calc(100vh-4rem)]">
        <!-- Dashboard -->
        <Link
          :href="route('dashboard')"
          :class="[
            'px-4 py-2.5 flex items-center gap-2 transition-colors',
            isExactRouteActive('dashboard')
              ? 'bg-indigo-50 text-indigo-600 font-medium border-l-4 border-indigo-600 dark:bg-gray-700 dark:text-indigo-300 dark:border-indigo-400'
              : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
          ]"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
          </svg>
          <span>ড্যাশবোর্ড</span>
        </Link>

        <!-- Application Section -->
        <div class="mt-4 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">আবেদন ও নিবন্ধন</div>
        
        <!-- তাকমিল -->
        <div>
          <button
            @click="toggleDropdown('takmil')"
            :class="[
              'w-full px-4 py-2.5 flex justify-between items-center transition-colors',
              activeDropdown === 'takmil' || isRouteActive('Markaz') || isRouteActive('Takmil')
                ? 'bg-indigo-50 text-indigo-600 font-medium border-l-4 border-indigo-600 dark:bg-gray-700 dark:text-indigo-300 dark:border-indigo-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
            ]"
          >
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
              </svg>
              আবেদন সংক্রান্ত
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'transform rotate-90': activeDropdown === 'takmil'}" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <div v-if="activeDropdown === 'takmil'" class="bg-gray-50 dark:bg-gray-900 py-1 border-l border-gray-200 dark:border-gray-700">
            <Link
              :href="route('Markaz.makaj-apply')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('Markaz.makaj-apply')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              মারকায আবেদন
            </Link>
            <Link
              v-if="!$page.props.auth.user.markaz_serial"
              :href="route('Markaz.markaz_change_table')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('Markaz.markaz_change_table')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              মারকায পরিবর্তন
            </Link>
            <Link
              :href="route('Markaz.subjectSettings_for_madrasha')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('Markaz.subjectSettings_for_madrasha')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              বিষয় সেটাপ
            </Link>
            <Link
              :href="route('Markaz.marhala_change_table')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('Markaz.marhala_change_table')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              মারহালা পরিবর্তন
            </Link>
            <Link
              href="route('Takmil.cirtificateProvide')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('Takmil.cirtificateProvide')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              মন্জুরী পত্র আবেদন
            </Link>
          </div>
        </div>

        <!-- নিবন্ধন সংক্রান্ত -->
        <div>
          <button
            @click="toggleDropdown('orders')"
            :class="[
              'w-full px-4 py-2.5 flex justify-between items-center transition-colors',
              activeDropdown === 'orders' || isRouteActive('students_registration')
                ? 'bg-indigo-50 text-indigo-600 font-medium border-l-4 border-indigo-600 dark:bg-gray-700 dark:text-indigo-300 dark:border-indigo-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
            ]"
          >
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
              </svg>
              নিবন্ধন সংক্রান্ত
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'transform rotate-90': activeDropdown === 'orders'}" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <div v-if="activeDropdown === 'orders'" class="bg-gray-50 dark:bg-gray-900 py-1 border-l border-gray-200 dark:border-gray-700">
            <Link
              :href="route('students_registration.student_registration')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('students_registration.student_registration')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              পরীক্ষার্থী নিবন্ধন
            </Link>
            <Link
              :href="route('students_registration.stuedent_reg_list')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('students_registration.stuedent_reg_list')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              নিবন্ধন তালিকা
            </Link>
            <Link
              :href="route('students_registration.student_reg_card')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('students_registration.student_reg_card')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              নিবন্ধন পত্র
            </Link>
            <Link
              :href="route('students_registration.stue_reg_draft_soft_delete')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('students_registration.stue_reg_draft_soft_delete')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              ড্রাফ্ট/সফ্ট ডিলিট
            </Link>
            <Link
              :href="route('students_registration.stu_reg_payment')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('students_registration.stu_reg_payment')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              পেমেন্ট
            </Link> 
          </div>
        </div>

        <!-- অন্তর্ভুক্তি সংক্রান্ত -->
        <div>
          <button
            @click="toggleDropdown('sanawia')"
            :class="[
              'w-full px-4 py-2.5 flex justify-between items-center transition-colors',
              activeDropdown === 'sanawia' || isRouteActive('OntorVukti')
                ? 'bg-indigo-50 text-indigo-600 font-medium border-l-4 border-indigo-600 dark:bg-gray-700 dark:text-indigo-300 dark:border-indigo-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
            ]"
          >
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
              </svg>
              অন্তর্ভুক্তি সংক্রান্ত
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'transform rotate-90': activeDropdown === 'sanawia'}" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <div v-if="activeDropdown === 'sanawia'" class="bg-gray-50 dark:bg-gray-900 py-1 border-l border-gray-200 dark:border-gray-700">
            <Link
              :href="route('OntorVukti.ontorvukti_table')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('OntorVukti.ontorvukti_table')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              অন্তর্ভূক্তি তালিকা
            </Link>
            <Link
              :href="route('OntorVukti.softDelete_draft_list')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('OntorVukti.softDelete_draft_list')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              ড্রাফ্ট তালিকা
            </Link>
            <Link
              :href="route('OntorVukti.stu_admit_card')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('OntorVukti.stu_admit_card')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              প্রবেশপত্র
            </Link>
            <Link
              :href="route('OntorVukti.stu_payment')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('OntorVukti.stu_payment')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              পেমেন্ট
            </Link>
          </div>
        </div>

        <!-- Management Section -->
        <div class="mt-4 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">ম্যানেজমেন্ট</div>

        <!-- নেগরান/মুমতাহিন -->
        <div>
          <button
            @click="toggleDropdown('mutawassita')"
            :class="[
              'w-full px-4 py-2.5 flex justify-between items-center transition-colors',
              activeDropdown === 'mutawassita' || isRouteActive('Negran_Mumtahin')
                ? 'bg-indigo-50 text-indigo-600 font-medium border-l-4 border-indigo-600 dark:bg-gray-700 dark:text-indigo-300 dark:border-indigo-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
            ]"
          >
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
              </svg>
              নেগরান/মুমতাহিন
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'transform rotate-90': activeDropdown === 'mutawassita'}" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <div v-if="activeDropdown === 'mutawassita'" class="bg-gray-50 dark:bg-gray-900 py-1 border-l border-gray-200 dark:border-gray-700">
            <Link
              :href="route('Negran_Mumtahin.negran_mumtahin')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('Negran_Mumtahin.negran_mumtahin')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              নেগ:/মুম: আবেদন
            </Link>
            <Link
              :href="route('Negran_Mumtahin.mumtahin_list')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('Negran_Mumtahin.mumtahin_list')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              মুমতাহিন তালিকা
            </Link>
            <Link
              :href="route('Negran_Mumtahin.negran_apoint_list')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('Negran_Mumtahin.negran_apoint_list')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              নিয়োগপত্র
            </Link>
          </div>
        </div>

        <!-- মারকায সংক্রান্ত -->
        <div v-if="$page.props.auth.user.markaz_serial">
          <button
            @click="toggleDropdown('ibtedaia')"
            :class="[
              'w-full px-4 py-2.5 flex justify-between items-center transition-colors',
              activeDropdown === 'ibtedaia' || isRouteActive('About_markaj')
                ? 'bg-indigo-50 text-indigo-600 font-medium border-l-4 border-indigo-600 dark:bg-gray-700 dark:text-indigo-300 dark:border-indigo-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
            ]"
          >
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
              </svg>
              মারকায সংক্রান্ত
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'transform rotate-90': activeDropdown === 'ibtedaia'}" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <div v-if="activeDropdown === 'ibtedaia'" class="bg-gray-50 dark:bg-gray-900 py-1 border-l border-gray-200 dark:border-gray-700">
            <Link
              :href="route('About_markaj.madrasha_list')"
              :class="[
                'pl-10 pr-4 py-2 flex transition-colors',
                isExactRouteActive('About_markaj.madrasha_list')
                  ? 'text-indigo-600 dark:text-indigo-300 font-medium'
                  : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
              ]"
            >
              মাদরাসার তালিকা
            </Link>
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              নেগরান তালিকা
            </Link>
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              প্রশ্নপত্র প্রিন্ট
            </Link>
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              ভাউচার তৈরি
            </Link>
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              ছাত্রদের হাজিরা
            </Link>
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              নেগরানদের হাজিরা
            </Link>
          </div>
        </div>

        <!-- Reports Section -->
        <div class="mt-4 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">রিপোর্টস ও ডকুমেন্টস</div>

        <!-- রিপোর্টস -->
        <div>
          <button
            @click="toggleDropdown('HifzulQuran')"
            :class="[
              'w-full px-4 py-2.5 flex justify-between items-center transition-colors',
              activeDropdown === 'HifzulQuran'
                ? 'bg-indigo-50 text-indigo-600 font-medium border-l-4 border-indigo-600 dark:bg-gray-700 dark:text-indigo-300 dark:border-indigo-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
            ]"
          >
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
              </svg>
              রিপোর্টস
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'transform rotate-90': activeDropdown === 'HifzulQuran'}" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <div v-if="activeDropdown === 'HifzulQuran'" class="bg-gray-50 dark:bg-gray-900 py-1 border-l border-gray-200 dark:border-gray-700">
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              নিবন্ধন রিপোর্ট
            </Link>
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              অন্তর্ভূক্তি রিপোর্ট
            </Link>
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              নেগ:/মুম রিপোর্ট
            </Link>
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              হাজিরা রিপোর্ট
            </Link>
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              পেমেন্ট রিপোর্ট
            </Link>
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              নিয়োগ রিপোর্ট
            </Link>
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              মাদরাসা রিপোর্ট
            </Link>
          </div>
        </div>

        <!-- প্রয়োজনীয় ডকুমেন্টস -->
        <div>
          <button
            @click="toggleDropdown('Qirat')"
            :class="[
              'w-full px-4 py-2.5 flex justify-between items-center transition-colors',
              activeDropdown === 'Qirat'
                ? 'bg-indigo-50 text-indigo-600 font-medium border-l-4 border-indigo-600 dark:bg-gray-700 dark:text-indigo-300 dark:border-indigo-400'
                : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
            ]"
          >
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
              </svg>
              প্রয়োজনীয় ডকুমেন্টস
            </div>
            <svg xmlns="http://www.w3.org/2000/svg" :class="{'transform rotate-90': activeDropdown === 'Qirat'}" class="w-4 h-4 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
            </svg>
          </button>
          <div v-if="activeDropdown === 'Qirat'" class="bg-gray-50 dark:bg-gray-900 py-1 border-l border-gray-200 dark:border-gray-700">
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              সকল ছাত্র
            </Link>
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              আবেদন তালিকা
            </Link>
            <Link
              href="#"
              class="pl-10 pr-4 py-2 flex text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700"
            >
              সনদ কর্যক্রম
            </Link>
          </div>
        </div>

        <!-- Communication Section -->
        <div class="mt-4 px-4 text-xs font-medium text-gray-500 uppercase tracking-wider">যোগাযোগ</div>

        <!-- মেসেজিং -->
        <Link
          :href="route('others.massaging')"
          :class="[
            'px-4 py-2.5 flex items-center gap-2 transition-colors',
            isExactRouteActive('others.massaging')
              ? 'bg-indigo-50 text-indigo-600 font-medium border-l-4 border-indigo-600 dark:bg-gray-700 dark:text-indigo-300 dark:border-indigo-400'
              : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
          ]"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
          মেসেজিং
        </Link>

        <!-- নোটিস -->
        <Link
          :href="route('others.notice')"
          :class="[
            'px-4 py-2.5 flex items-center gap-2 transition-colors',
            isExactRouteActive('others.notice')
              ? 'bg-indigo-50 text-indigo-600 font-medium border-l-4 border-indigo-600 dark:bg-gray-700 dark:text-indigo-300 dark:border-indigo-400'
              : 'text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-700'
          ]"
        >
          <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
          </svg>
          নোটিস
        </Link>
      </nav>
    </aside>

    <!-- Main Content -->
    <div class="flex-1 ml-0 lg:ml-64 transition-all duration-300">
      <!-- Top Navigation Bar -->
      <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700 fixed right-0 left-0 z-20 lg:left-64 shadow-sm">
        <div class="px-4 sm:px-6 lg:px-8">
          <div class="flex justify-between h-16">
            <!-- Mobile Menu Toggle -->
            <div class="flex items-center lg:hidden">
              <button
                @click="showSidebar = !showSidebar"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-indigo-600 hover:bg-gray-100 focus:outline-none"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
              </button>
            </div>

            <!-- School/Institute Info -->
            <div class="flex items-center justify-center flex-grow">
              <div class="text-center">
                <h1 class="text-sm sm:text-lg font-medium text-gray-800 dark:text-gray-200">
                  {{ page.props.auth.user.madrasha_name }} {{ page.props.auth.user.thana }} ({{ page.props.auth.user.custom_code }})
                </h1>
                <div v-if="!error && Object.keys(centers).length > 0" class="flex flex-wrap justify-center gap-1 mt-1">
                  <span
                    v-for="(centerName, type) in centers"
                    :key="type"
                    class="text-xs bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300 px-2 py-0.5 rounded"
                  >
                    {{ centerName }}
                  </span>
                </div>
              </div>
            </div>

            <!-- User Account -->
              <Dropdown align="right" width="48">
                    <template #trigger>
                        <span class="inline-flex rounded-md">
                            <button
                                style="font-family: 'Merriweather', 'SolaimanLipi', sans-serif;"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                            >
                                <!-- Mobile view - only icon -->
                                <div class="sm:hidden">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <!-- Desktop view - full content -->
                                <div class="hidden sm:flex flex-col items-start mr-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="hidden sm:flex flex-col items-start">
                                    <span class="font-medium text-sm">{{ $page.props.auth.user.name }}</span>
                                    <span class="bg-gray-100 text-gray-800 px-2 py-0.5 rounded-full text-xs mt-0.5">
                                        {{ $page.props.auth.user.admin_Designation }}
                                    </span>
                                </div>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1 sm:ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                        </span>
                    </template>
                    <template #content>
                        <DropdownLink
                            :href="route('profile.edit')"
                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out"
                        >
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Profile
                            </div>
                        </DropdownLink>
                        <DropdownLink
                            :href="route('logout')"
                            method="post"
                            as="button"
                            class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out"
                        >
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                Log Out
                            </div>
                        </DropdownLink>
                    </template>
                </Dropdown>
          </div>
        </div>
      </nav>

      <!-- Main Content Area -->
      <main class="pt-16 p-4">
        <slot />
      </main>
    </div>

    <!-- Mobile sidebar overlay -->
    <div
      v-if="showSidebar"
      @click="showSidebar = false"
      class="fixed inset-0 z-30 bg-gray-600 bg-opacity-75 lg:hidden transition-opacity duration-300"
    ></div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

// State management
const showSidebar = ref(false);
const activeDropdown = ref(null);
const page = usePage();
const centers = ref([]);
const error = ref(null);

// Toggle dropdown sections
const toggleDropdown = (name) => {
  if (activeDropdown.value === name) {
    activeDropdown.value = null;
  } else {
    activeDropdown.value = name;
  }
};

// Route active state checks
const currentRoute = computed(() => route().current());

const isRouteActive = (routeName) => {
  return currentRoute.value.startsWith(routeName.split('.')[0]);
};

const isExactRouteActive = (routeName) => {
  return currentRoute.value === routeName;
};

// Fetch center data
const fetchCenters = async () => {
  try {
    const response = await fetch('/api/user-centers');
    if (!response.ok) {
      throw new Error('Failed to fetch centers');
    }
    const data = await response.json();
    centers.value = data.centers || {};
    error.value = data.error || null;
  } catch (err) {
    console.error('Error fetching centers:', err);
    error.value = err.message;
  }
};

// Auto-open dropdown based on current route
watch(currentRoute, (newRoute) => {
  if (newRoute.startsWith('Markaz') || newRoute.startsWith('Takmil')) {
    activeDropdown.value = 'takmil';
  } else if (newRoute.startsWith('students_registration')) {
    activeDropdown.value = 'orders';
  } else if (newRoute.startsWith('OntorVukti')) {
    activeDropdown.value = 'sanawia';
  } else if (newRoute.startsWith('Negran_Mumtahin')) {
    activeDropdown.value = 'mutawassita';
  } else if (newRoute.startsWith('About_markaj')) {
    activeDropdown.value = 'ibtedaia';
  } else if (newRoute.includes('report')) {
    activeDropdown.value = 'HifzulQuran';
  } else if (newRoute.includes('document')) {
    activeDropdown.value = 'Qirat';
  }
}, { immediate: true });

onMounted(() => {
  fetchCenters();
});
</script>