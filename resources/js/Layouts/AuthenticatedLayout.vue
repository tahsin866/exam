<template>
    <div style="font-family: 'Merriweather','SolaimanLipi',sans-serif;" class="flex min-h-screen bg-gray-100 dark:bg-gray-900">
      <!-- Sidebar -->
  <aside
  :class="[
    'fixed inset-y-0 left-0 z-50 w-64 transform transition-transform duration-300 ease-in-out bg-gray-800 text-white lg:translate-x-0 shadow-xl',
    showSidebar ? 'translate-x-0' : '-translate-x-full'
  ]"
>
  <!-- Header -->
  <div class="h-16 flex text-xl items-center justify-center bg-gray-900 text-white font-medium shadow-md">
    <div class="flex items-center gap-2">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
      </svg>
      এক্সাম মেনেজমেন্ট সিস্টেম
    </div>
  </div>

  <nav style="font-family: 'Merriweather','SolaimanLipi',sans-serif;" class="mt-4 space-y-1 overflow-y-auto max-h-[calc(100vh-4rem)] scrollbar-hidden">
    <!-- Dashboard -->
    <Link
      :href="route('dashboard')"
      :class="[
        'text-xl px-4 py-2 flex items-center gap-2 transition-colors duration-200',
        isExactRouteActive('dashboard')
          ? 'bg-gray-900 text-white font-medium border-l-4 border-indigo-400'
          : 'text-gray-300 hover:bg-gray-700 hover:text-white'
      ]"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
      </svg>
      ড্যাশবোর্ড
    </Link>

    <!-- তাকমিল -->
    <div>
      <button
        @click="toggleDropdown('takmil')"
        :class="[
          'w-full text-xl flex justify-between items-center px-4 py-2 transition-colors duration-200',
          activeDropdown === 'takmil' || isRouteActive('Markaz') || isRouteActive('Takmil')
            ? 'bg-gray-900 text-white font-medium border-l-4 border-indigo-400'
            : 'text-gray-300 hover:bg-gray-700 hover:text-white'
        ]"
      >
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
          আবেদন সংক্রান্ত
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': activeDropdown === 'takmil'}" class="w-4 h-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
      <div v-if="activeDropdown === 'takmil'" class="pl-6 bg-gray-700 py-1 space-y-1 transition-all duration-300">
        <Link
          :href="route('Markaz.makaj-apply')"
          :class="[
            'px-4 py-2 text-xl flex items-center gap-2 transition-colors duration-200 rounded-sm',
            isExactRouteActive('Markaz.makaj-apply')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          মারকায আবেদন
        </Link>
        <Link
          v-if="!$page.props.auth.user.markaz_serial"
          :href="route('Markaz.markaz_change_table')"
          :class="[
            'block px-4 py-2 text-xl transition-colors duration-200 rounded-sm',
            isExactRouteActive('Markaz.markaz_change_table')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          মারকায পরিবর্তন
        </Link>
        <Link
          :href="route('Markaz.subjectSettings_for_madrasha')"
          :class="[
            'block px-4 py-2 text-xl transition-colors duration-200 rounded-sm',
            isExactRouteActive('Markaz.subjectSettings_for_madrasha')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          বিষয় সেটাপ
        </Link>
        <Link
          :href="route('Markaz.marhala_change_table')"
          :class="[
            'block px-4 py-2 text-xl transition-colors duration-200 rounded-sm',
            isExactRouteActive('Markaz.marhala_change_table')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          মারহালা পরিবর্তন
        </Link>
        <Link
          href="route('Takmil.cirtificateProvide')"
          :class="[
            'block px-4 py-2 text-xl transition-colors duration-200 rounded-sm',
            isExactRouteActive('Takmil.cirtificateProvide')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
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
          'w-full text-xl flex justify-between items-center px-4 py-2 transition-colors duration-200',
          activeDropdown === 'orders' || isRouteActive('students_registration')
            ? 'bg-gray-900 text-white font-medium border-l-4 border-indigo-400'
            : 'text-gray-300 hover:bg-gray-700 hover:text-white'
        ]"
      >
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
          </svg>
          নিবন্ধন সংক্রান্ত
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': activeDropdown === 'orders'}" class="w-4 h-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
      <div v-if="activeDropdown === 'orders'" class="pl-6 bg-gray-700 py-1 space-y-1 transition-all duration-300">
        <Link
          :href="route('students_registration.student_registration')"
          :class="[
            'px-4 py-2 text-xl flex items-center gap-2 transition-colors duration-200 rounded-sm',
            isExactRouteActive('students_registration.student_registration')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          পরীক্ষার্থী নিবন্ধন
        </Link>
        <Link
          :href="route('students_registration.stuedent_reg_list')"
          :class="[
            'block text-xl px-4 py-2 transition-colors duration-200 rounded-sm',
            isExactRouteActive('students_registration.stuedent_reg_list')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          নিবন্ধন তালিকা
        </Link>
        <Link
          :href="route('students_registration.student_reg_card')"
          :class="[
            'block px-4 text-xl py-2 transition-colors duration-200 rounded-sm',
            isExactRouteActive('students_registration.student_reg_card')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          নিবন্ধন পত্র
        </Link>
        <Link
          :href="route('students_registration.stue_reg_draft_soft_delete')"
          :class="[
            'block px-4 text-xl py-2 transition-colors duration-200 rounded-sm',
            isExactRouteActive('students_registration.stue_reg_draft_soft_delete')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          ড্রাফ্ট/সফ্ট ডিলিট
        </Link>
        <Link
          :href="route('students_registration.stu_reg_payment')"
          :class="[
            'block px-4 text-xl py-2 transition-colors duration-200 rounded-sm',
            isExactRouteActive('students_registration.stu_reg_payment')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
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
          'w-full text-xl flex justify-between items-center px-4 py-2 transition-colors duration-200',
          activeDropdown === 'sanawia' || isRouteActive('OntorVukti')
            ? 'bg-gray-900 text-white font-medium border-l-4 border-indigo-400'
            : 'text-gray-300 hover:bg-gray-700 hover:text-white'
        ]"
      >
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
          </svg>
          অন্তর্ভুক্তি সংক্রান্ত
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': activeDropdown === 'sanawia'}" class="w-4 h-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
            <div v-if="activeDropdown === 'sanawia'" class="pl-6 bg-gray-700 py-1 space-y-1 transition-all duration-300">
        <Link
          :href="route('OntorVukti.ontorvukti_table')"
          :class="[
            'block text-xl px-4 py-2 transition-colors duration-200 rounded-sm',
            isExactRouteActive('OntorVukti.ontorvukti_table')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          অন্তর্ভূূূূূূূূূূক্তি তালিকা
        </Link>
        <Link
          :href="route('OntorVukti.softDelete_draft_list')"
          :class="[
            'block px-4 text-xl py-2 transition-colors duration-200 rounded-sm',
            isExactRouteActive('OntorVukti.softDelete_draft_list')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          ড্রাফ্ট তালিকা
        </Link>
        <Link
          :href="route('OntorVukti.stu_admit_card')"
          :class="[
            'block px-4 text-xl py-2 transition-colors duration-200 rounded-sm',
            isExactRouteActive('OntorVukti.stu_admit_card')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          প্রবেশপত্র
        </Link>
        <Link
          :href="route('OntorVukti.stu_payment')"
          :class="[
            'block px-4 text-xl py-2 transition-colors duration-200 rounded-sm',
            isExactRouteActive('OntorVukti.stu_payment')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          পেমেন্ট
        </Link>
      </div>
    </div>

    <!-- নেগরান/মুমতাহিন -->
    <div>
      <button
        @click="toggleDropdown('mutawassita')"
        :class="[
          'w-full text-xl flex justify-between items-center px-4 py-2 transition-colors duration-200',
          activeDropdown === 'mutawassita' || isRouteActive('Negran_Mumtahin')
            ? 'bg-gray-900 text-white font-medium border-l-4 border-indigo-400'
            : 'text-gray-300 hover:bg-gray-700 hover:text-white'
        ]"
      >
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
          নেগরান/মুমতাহিন
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': activeDropdown === 'mutawassita'}" class="w-4 h-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
      <div v-if="activeDropdown === 'mutawassita'" class="pl-6 bg-gray-700 py-1 space-y-1 transition-all duration-300">
        <Link
          :href="route('Negran_Mumtahin.negran_mumtahin')"
          :class="[
            'block text-xl px-4 py-2 transition-colors duration-200 rounded-sm',
            isExactRouteActive('Negran_Mumtahin.negran_mumtahin')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          নেগ:/মুম: আবেদন
        </Link>
        <Link
          :href="route('Negran_Mumtahin.mumtahin_list')"
          :class="[
            'block px-4 py-2 text-xl transition-colors duration-200 rounded-sm',
            isExactRouteActive('Negran_Mumtahin.mumtahin_list')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          মুমতাহিন তালিকা
        </Link>
        <Link
          :href="route('Negran_Mumtahin.negran_apoint_list')"
          :class="[
            'block px-4 py-2 text-xl transition-colors duration-200 rounded-sm',
            isExactRouteActive('Negran_Mumtahin.negran_apoint_list')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
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
          'w-full text-xl flex justify-between items-center px-4 py-2 transition-colors duration-200',
          activeDropdown === 'ibtedaia' || isRouteActive('About_markaj')
            ? 'bg-gray-900 text-white font-medium border-l-4 border-indigo-400'
            : 'text-gray-300 hover:bg-gray-700 hover:text-white'
        ]"
      >
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          মারকায সংক্রান্ত
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': activeDropdown === 'ibtedaia'}" class="w-4 h-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
      <div v-if="activeDropdown === 'ibtedaia'" class="pl-6 bg-gray-700 py-1 space-y-1 transition-all duration-300">
        <Link
          href="route('About_markaj.madrasha_list')"
          :class="[
            'block text-xl px-4 py-2 transition-colors duration-200 rounded-sm',
            isExactRouteActive('About_markaj.madrasha_list')
              ? 'bg-gray-600 text-white'
              : 'text-gray-300 hover:bg-gray-600 hover:text-white'
          ]"
        >
          মাদরাসার তালিকা
        </Link>
        <Link
          href=""
          class="block px-4 py-2 text-gray-300 hover:bg-gray-600 hover:text-white text-xl transition-colors duration-200 rounded-sm"
        >
          নেগরান তালিকা
        </Link>
        <Link
          href="#"
          class="block px-4 py-2 text-gray-300 hover:bg-gray-600 hover:text-white text-xl transition-colors duration-200 rounded-sm"
        >
          প্রশ্নপত্র প্রিন্ট
        </Link>
        <Link
          href="#"
          class="block px-4 py-2 text-gray-300 hover:bg-gray-600 hover:text-white text-xl transition-colors duration-200 rounded-sm"
        >
          ভাউচার তৈরি
        </Link>
        <Link
          href="#"
          class="block px-4 py-2 text-gray-300 hover:bg-gray-600 hover:text-white text-xl transition-colors duration-200 rounded-sm"
        >
          ছাত্রদের হাজিরা
        </Link>
        <Link
          href="#"
          class="block px-4 py-2 text-gray-300 hover:bg-gray-600 hover:text-white text-xl transition-colors duration-200 rounded-sm"
        >
          নেগরানদের হাজিরা
        </Link>
      </div>
    </div>

    <!-- রিপোর্টস -->
    <div>
      <button
        @click="toggleDropdown('HifzulQuran')"
        :class="[
          'w-full text-xl flex justify-between items-center px-4 py-2 transition-colors duration-200',
          activeDropdown === 'HifzulQuran'
            ? 'bg-gray-900 text-white font-medium border-l-4 border-indigo-400'
            : 'text-gray-300 hover:bg-gray-700 hover:text-white'
        ]"
      >
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
          </svg>
          রিপোর্টস
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': activeDropdown === 'HifzulQuran'}" class="w-4 h-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
      <div v-if="activeDropdown === 'HifzulQuran'" class="pl-6 bg-gray-700 py-1 space-y-1 transition-all duration-300">
        <Link
          href="route('Fajilat.sanawaia')"
          class="block px-4 py-2 text-xl text-gray-300 hover:bg-gray-600 hover:text-white transition-colors duration-200 rounded-sm"
        >
          নিবন্ধন রিপোর্ট
        </Link>
        <Link
          href=""
          class="block px-4 py-2 text-gray-300 hover:bg-gray-600 hover:text-white text-xl transition-colors duration-200 rounded-sm"
        >
          অন্তর্ভূক্তি রিপোর্ট
        </Link>
        <Link
          href=""
          class="block px-4 py-2 text-gray-300 hover:bg-gray-600 hover:text-white text-xl transition-colors duration-200 rounded-sm"
        >
          নেগ:/মুম রিপোর্ট
        </Link>
        <Link
          href="#"
          class="block px-4 py-2 text-gray-300 hover:bg-gray-600 hover:text-white text-xl transition-colors duration-200 rounded-sm"
        >
          হাজিরা রিপোর্ট
        </Link>
        <Link
          href="#"
          class="block px-4 py-2 text-gray-300 hover:bg-gray-600 hover:text-white text-xl transition-colors duration-200 rounded-sm"
        >
          পেমেন্ট রিপোর্ট
        </Link>
        <Link
          href="#"
          class="block px-4 py-2 text-gray-300 hover:bg-gray-600 hover:text-white text-xl transition-colors duration-200 rounded-sm"
        >
          নিয়োগ রিপোর্ট
        </Link>
        <Link
          href="#"
          class="block px-4 py-2 text-gray-300 hover:bg-gray-600 hover:text-white text-xl transition-colors duration-200 rounded-sm"
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
          'w-full text-xl flex justify-between items-center px-4 py-2 transition-colors duration-200',
          activeDropdown === 'Qirat'
            ? 'bg-gray-900 text-white font-medium border-l-4 border-indigo-400'
            : 'text-gray-300 hover:bg-gray-700 hover:text-white'
        ]"
      >
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
          প্রয়োজনীয় ডকুমেন্টস
        </div>
        <svg xmlns="http://www.w3.org/2000/svg" :class="{'rotate-90': activeDropdown === 'Qirat'}" class="w-4 h-4 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
      </button>
      <div v-if="activeDropdown === 'Qirat'" class="pl-6 bg-gray-700 py-1 space-y-1 transition-all duration-300">
        <Link
          href="route('fazilat.sanawaia')"
          class="block px-4 py-2 text-gray-300 hover:bg-gray-600 hover:text-white text-xl transition-colors duration-200 rounded-sm"
        >
          সকল ছাত্র
        </Link>
        <Link
          href=""
          class="block px-4 py-2 text-gray-300 hover:bg-gray-600 hover:text-white text-xl transition-colors duration-200 rounded-sm"
        >
          আবেদন তালিকা
        </Link>
        <Link
          href="#"
          class="block px-4 py-2 text-gray-300 hover:bg-gray-600 hover:text-white text-xl transition-colors duration-200 rounded-sm"
        >
          সনদ কর্যক্রম
        </Link>
      </div>
    </div>

    <!-- মেসেজিং -->
    <Link
      :href="route('others.massaging')"
      :class="[
        'px-4 text-xl py-2 flex items-center gap-2 transition-colors duration-200',
        isExactRouteActive('others.massaging')
          ? 'bg-gray-900 text-white font-medium border-l-4 border-indigo-400'
          : 'text-gray-300 hover:bg-gray-700 hover:text-white'
      ]"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
      </svg>
      মেসেজিং
    </Link>

    <!-- নোটিস -->
    <Link
      :href="route('others.notice')"
      :class="[
        'px-4 text-xl py-2 flex items-center gap-2 transition-colors duration-200',
        isExactRouteActive('others.notice')
          ? 'bg-gray-900 text-white font-medium border-l-4 border-indigo-400'
          : 'text-gray-300 hover:bg-gray-700 hover:text-white'
      ]"
    >
      <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
      </svg>
      নোটিস
    </Link>
  </nav>
</aside>



    <!-- Main Content -->
    <div
      :class="[
        'flex-1',
        'transition-all duration-300 ease-in-out',
        'md:ml-64',  // Always maintain margin on desktop
        'w-full',    // Ensure full width
        'overflow-x-hidden' // Prevent horizontal scrolling
      ]"
    >
      <!-- Top Navigation Bar -->
<nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700 fixed right-0 left-0 z-10 md:left-64 shadow-sm">
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 md:h-28">
            <!-- Mobile menu toggle -->
            <div class="flex items-center md:hidden">
                <button
                    @click="showSidebar = !showSidebar"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>

            <!-- Logo and Center Info -->
            <div class="flex items-center justify-center flex-grow flex-col py-2">
                <h1 class="text-sm sm:text-lg md:text-xl font-medium text-center mb-1 sm:mb-2 truncate max-w-[200px] sm:max-w-full text-gray-800 dark:text-gray-200">
                    {{ page.props.auth.user.madrasha_name }} {{ page.props.auth.user.thana }} ({{ page.props.auth.user.custom_code }})
                </h1>
                <!-- Centers displayed below madrasha name -->
                <div v-if="!error && Object.keys(centers).length > 0" class="flex flex-wrap justify-center gap-1 sm:gap-2 mb-1 sm:mb-2">
                    <span
                        v-for="(centerName, type) in centers"
                        :key="type"
                        class="text-xs sm:text-sm md:text-base bg-gray-100 text-gray-800 px-2 py-0.5 sm:px-3 sm:py-1 rounded-full truncate max-w-[100px] sm:max-w-full"
                    >
                        {{ centerName }}
                    </span>
                </div>
            </div>

            <!-- Notification and User Dropdown -->
            <div class="flex items-center space-x-1 sm:space-x-4">
                <!-- Prayer Times Button -->
                <button class="hidden xs:inline-flex relative text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 p-1 sm:p-2 rounded-md transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </button>

                <!-- Notification Icon -->
                <button
                    @click="openNotifications"
                    class="relative text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 p-1 sm:p-2 rounded-md transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                    <span class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[16px] sm:min-w-[18px] h-4 sm:h-4 px-1 text-[10px] sm:text-xs font-bold text-white bg-red-600 rounded-full">
                        3
                    </span>
                </button>

                <!-- Message Icon -->
                <button
                    @click="openMessages"
                    class="hidden xs:inline-flex relative text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 p-1 sm:p-2 rounded-md transition"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[16px] sm:min-w-[18px] h-4 sm:h-4 px-1 text-[10px] sm:text-xs font-bold text-white bg-red-600 rounded-full">
                        5
                    </span>
                </button>

                <!-- User Dropdown -->
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
    </div>
</nav>



      <!-- Main Content Area -->
      <main class="flex-1 pt-16 mt-10 p-4">
        <slot />
      </main>
    </div>
  </div>

  <!-- Overlay for mobile sidebar -->
  <div
    v-show="showSidebar"
    @click="showSidebar = false"
    class="fixed inset-0 z-40 bg-black bg-opacity-50 lg:hidden transition-opacity duration-300"
  ></div>

  <!-- Notification Panel (can be implemented as needed) -->
  <div
    v-if="showNotifications"
    class="fixed right-2 top-16 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl z-50 border border-emerald-100 dark:border-emerald-700 overflow-hidden transition-all duration-300 transform origin-top-right"
  >
    <div class="p-4 bg-emerald-50 dark:bg-emerald-900 border-b border-emerald-100 dark:border-emerald-700">
      <h3 class="text-lg font-semibold text-emerald-800 dark:text-emerald-200">নোটিফিকেশন</h3>
    </div>
    <div class="max-h-96 overflow-y-auto p-2">
      <!-- Sample notifications -->
      <div class="p-3 hover:bg-emerald-50 dark:hover:bg-emerald-900 rounded-lg mb-2 border border-emerald-100 dark:border-emerald-800">
        <div class="flex items-start">
          <div class="flex-shrink-0 bg-emerald-100 dark:bg-emerald-800 p-2 rounded-full">
            <i class="fas fa-bell text-emerald-600 dark:text-emerald-400"></i>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">নতুন নোটিশ</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">১০ মিনিট আগে</p>
          </div>
        </div>
      </div>
      <div class="p-3 hover:bg-emerald-50 dark:hover:bg-emerald-900 rounded-lg mb-2 border border-emerald-100 dark:border-emerald-800">
        <div class="flex items-start">
          <div class="flex-shrink-0 bg-emerald-100 dark:bg-emerald-800 p-2 rounded-full">
            <i class="fas fa-file-alt text-emerald-600 dark:text-emerald-400"></i>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">আবেদন অনুমোদিত হয়েছে</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">১ ঘন্টা আগে</p>
          </div>
        </div>
      </div>
    </div>
    <div class="p-2 border-t border-emerald-100 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900">
      <button class="w-full py-2 text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 font-medium">
        সব দেখুন
      </button>
    </div>
  </div>

  <!-- Messages Panel (can be implemented as needed) -->
  <div
    v-if="showMessages"
    class="fixed right-2 top-16 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-xl z-50 border border-emerald-100 dark:border-emerald-700 overflow-hidden transition-all duration-300 transform origin-top-right"
  >
    <div class="p-4 bg-emerald-50 dark:bg-emerald-900 border-b border-emerald-100 dark:border-emerald-700">
      <h3 class="text-lg font-semibold text-emerald-800 dark:text-emerald-200">মেসেজ</h3>
    </div>
    <div class="max-h-96 overflow-y-auto p-2">
      <!-- Sample messages -->
      <div class="p-3 hover:bg-emerald-50 dark:hover:bg-emerald-900 rounded-lg mb-2 border border-emerald-100 dark:border-emerald-800">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <div class="w-10 h-10 rounded-full bg-emerald-200 flex items-center justify-center text-emerald-700">
              <i class="fas fa-user"></i>
            </div>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">আব্দুল্লাহ</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">আসসালামু আলাইকুম, কেমন আছেন?</p>
            <p class="text-xs text-gray-400 mt-1">৩০ মিনিট আগে</p>
          </div>
        </div>
      </div>
      <div class="p-3 hover:bg-emerald-50 dark:hover:bg-emerald-900 rounded-lg mb-2 border border-emerald-100 dark:border-emerald-800">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <div class="w-10 h-10 rounded-full bg-emerald-200 flex items-center justify-center text-emerald-700">
              <i class="fas fa-user"></i>
            </div>
          </div>
          <div class="ml-3">
            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">মোহাম্মদ</p>
            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">আপনার ডকুমেন্ট পাঠিয়েছি, দেখবেন</p>
            <p class="text-xs text-gray-400 mt-1">২ ঘন্টা আগে</p>
          </div>
        </div>
      </div>
    </div>
    <div class="p-2 border-t border-emerald-100 dark:border-emerald-700 bg-emerald-50 dark:bg-emerald-900">
      <button class="w-full py-2 text-sm text-emerald-600 dark:text-emerald-400 hover:text-emerald-800 dark:hover:text-emerald-300 font-medium">
        সব মেসেজ দেখুন
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { Link, usePage, router } from '@inertiajs/vue3';
import axios from 'axios';
import 'primeicons/primeicons.css'

// Sidebar state
const showSidebar = ref(false);

// Security check for madrasa layout
const page = usePage();
const auth = computed(() => page.props.auth);

// Security check with watcher for auth data (temporarily disabled for debugging)
// Will re-enable after confirming basic functionality works
/*
const hasCheckedSecurity = ref(false);

watch(auth, (newAuth) => {
    // Only run security check once and if auth data is available
    if (newAuth && typeof newAuth === 'object' && !hasCheckedSecurity.value) {
        hasCheckedSecurity.value = true;
        
        // Check if user is logged in and is madrasa user
        if (!newAuth.user) {
            // Not logged in - redirect to login (but not if already on login page)
            if (!window.location.pathname.includes('/login')) {
                router.visit('/login');
            }
            return;
        }
        
        if (newAuth.user && newAuth.userType && !newAuth.isMadrasa) {
            // Not madrasa user - log security violation and redirect to appropriate dashboard
            console.error('Security Violation: Non-madrasa user attempted to access madrasa layout', {
                user: newAuth.user,
                userType: newAuth.userType,
                timestamp: new Date().toISOString()
            });
            
            // Redirect based on user type (but not if already on correct page)
            if (newAuth.isAdmin && !window.location.pathname.includes('/admin')) {
                router.visit('/admin/dashboard');
            } else if (!newAuth.isAdmin && !window.location.pathname.includes('/login')) {
                router.visit('/login');
            }
            return;
        }
    }
}, { immediate: true });
*/

// Dropdown states with single active management
const activeDropdown = ref(null);

// Function to toggle dropdown - closes others when one is opened
const toggleDropdown = (name) => {
  if (activeDropdown.value === name) {
    activeDropdown.value = null;
  } else {
    activeDropdown.value = name;
  }
};

// Notification & message states
const showNotifications = ref(false);
const showMessages = ref(false);

// Toggle sidebar for mobile
const toggleSidebar = () => {
  showSidebar.value = !showSidebar.value;
};

// Notification handlers
const openNotifications = () => {
  showNotifications.value = !showNotifications.value;
  showMessages.value = false;
};

const openMessages = () => {
  showMessages.value = !showMessages.value;
  showNotifications.value = false;
};

const mobileMenuOpen = ref(false);
const toggleMobileMenu = () => {
  mobileMenuOpen.value = !mobileMenuOpen.value;
};

// const page = usePage(); // Already declared above
const centers = ref([]);
const error = ref(null);

// Get current route for active menu highlighting
const currentRoute = computed(() => route().current());

// Check if a route is active or its child routes are active
const isRouteActive = (routeName) => {
  return currentRoute.value.startsWith(routeName.split('.')[0]);
};

// Check if a specific route is exactly active
const isExactRouteActive = (routeName) => {
  return currentRoute.value === routeName;
};

const fetchCenters = async () => {
  try {
    const response = await fetch('/api/user-centers');
    if (!response.ok) {
      throw new Error('Failed to fetch centers');
    }
    const data = await response.json();
    centers.value = data.centers || [];
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

<style scoped>
/* Custom scrollbar for sidebar */
.sidebar-scrollbar::-webkit-scrollbar {
  width: 4px;
}

.sidebar-scrollbar::-webkit-scrollbar-track {
  background: #004d40;
}

.sidebar-scrollbar::-webkit-scrollbar-thumb {
  background-color: #00796b;
  border-radius: 4px;
}

.sidebar-scrollbar::-webkit-scrollbar-thumb:hover {
  background-color: #00897b;
}

/* Transition for sidebar */
.sidebar-enter-active,
.sidebar-leave-active {
  transition: transform 0.3s ease;
}

.sidebar-enter-from,
.sidebar-leave-to {
  transform: translateX(-100%);
}

/* Ensure proper font rendering for Bangla text */
* {
  font-family: 'SolaimanLipi', sans-serif;
}
</style>
