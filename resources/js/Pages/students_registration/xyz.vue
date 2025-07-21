<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class=" sm:px-6 lg:px-8">
                 <div class="mb-6">
                    <ul class="flex border-b">
                        <li class="mr-1 -mb-px" :class="{ 'border-b-2 border-indigo-500': currentStep === 1 }">
                            <a @click="currentStep = 1" class="bg-white inline-block py-2 px-4 font-medium cursor-pointer"
                               :class="currentStep === 1 ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-800'">
                                ব্যক্তিগত তথ্য
                            </a>
                        </li>
                        <li class="mr-1" :class="{ 'border-b-2 border-indigo-500': currentStep === 2 }">
                            <a @click="currentStep = 2" class="bg-white inline-block py-2 px-4 font-medium cursor-pointer"
                               :class="currentStep === 2 ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-800'">
                                প্রয়োজনীয় তথ্য ও ঠিকানা
                            </a>
                        </li>
                        <li class="mr-1" :class="{ 'border-b-2 border-indigo-500': currentStep === 3 }">
                            <a @click="currentStep = 3" class="bg-white inline-block py-2 px-4 font-medium cursor-pointer"
                               :class="currentStep === 3 ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-800'">
                                সংযুক্তি
                            </a>
                        </li>
                    </ul>
                </div>


                <div class="p-6">
                    <form @submit.prevent="submitStudentInfo" class="space-y-8">
                        <!-- Personal Information Section -->
                        <div class="bg-white border border-emerald-100 rounded-sm shadow">
                            <div
                                class="flex bg-gradient-to-r rounded-t-md from-emerald-800 gap-3 items-center px-6 py-3 to-emerald-600">
                                <i class="text-2xl text-white fa-user-circle fas"></i>
                                <h5 class="text-white text-xl">ব্যক্তিগত তথ্য</h5>
                            </div>

                            <div class="p-6">
                                <!-- Name Fields -->
                                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
                                    <div class="relative">
                                        <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">নাম
                                            (বাংলা)</label>
                                        <div class="relative">
                                            <span
                                                class="flex absolute inset-y-0 items-center left-0 pl-3 pointer-events-none">
                                                <i class="text-emerald-600 fa-user fas"></i>
                                            </span>
                                            <input v-model="studentInfoForm.name_bn" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-gray-700 w-full cursor-not-allowed pl-10 pr-3 py-2">
                                        </div>
                                        <p class="text-gray-500 text-xs italic mt-1">* এই ফিল্ডটি পরিবর্তন করা যাবে না
                                        </p>
                                    </div>

                                    <div class="relative">
                                        <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">নাম
                                            (ইংরেজি)</label>
                                        <div class="relative">
                                            <span
                                                class="flex absolute inset-y-0 items-center left-0 pl-3 pointer-events-none">
                                                <i class="text-emerald-600 fa-user fas"></i>
                                            </span>
                                            <input v-model="studentInfoForm.name_en" type="text"
                                                class="border border-emerald-200 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-emerald-500 pl-10 pr-3 py-2">
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">নাম
                                            (আরবি)</label>
                                        <div class="relative">
                                            <span
                                                class="flex absolute inset-y-0 items-center left-0 pl-3 pointer-events-none">
                                                <i class="text-emerald-600 fa-user fas"></i>
                                            </span>
                                            <input v-model="studentInfoForm.name_ar" type="text"
                                                class="border border-emerald-200 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-emerald-500 pl-10 pr-3 py-2"
                                                dir="rtl">
                                        </div>
                                    </div>
                                </div>

                                <!-- Father's Name Fields -->
                                <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
                                    <div class="relative">
                                        <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">পিতার
                                            নাম (বাংলা)</label>
                                        <div class="relative">
                                            <span
                                                class="flex absolute inset-y-0 items-center left-0 pl-3 pointer-events-none">
                                                <i class="text-emerald-600 fa-male fas"></i>
                                            </span>
                                            <input v-model="studentInfoForm.father_name_bn" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-gray-700 w-full cursor-not-allowed pl-10 pr-3 py-2">
                                        </div>
                                        <p class="text-gray-500 text-xs italic mt-1">* এই ফিল্ডটি পরিবর্তন করা যাবে না
                                        </p>
                                    </div>

                                    <div class="relative">
                                        <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">পিতার
                                            নাম (ইংরেজি)</label>
                                        <div class="relative">
                                            <span
                                                class="flex absolute inset-y-0 items-center left-0 pl-3 pointer-events-none">
                                                <i class="text-emerald-600 fa-male fas"></i>
                                            </span>
                                            <input v-model="studentInfoForm.father_name_en" type="text"
                                                class="border border-emerald-200 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-emerald-500 pl-10 pr-3 py-2">
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">পিতার
                                            নাম (আরবি)</label>
                                        <div class="relative">
                                            <span
                                                class="flex absolute inset-y-0 items-center left-0 pl-3 pointer-events-none">
                                                <i class="text-emerald-600 fa-male fas"></i>
                                            </span>
                                            <input v-model="studentInfoForm.father_name_ar" type="text" required
                                                class="border border-emerald-200 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-emerald-500 pl-10 pr-3 py-2"
                                                dir="rtl">
                                        </div>
                                    </div>
                                </div>

                                <!-- Mother's Name Fields -->
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                    <div class="relative">
                                        <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">মাতার
                                            নাম (বাংলা)</label>
                                        <div class="relative">
                                            <span
                                                class="flex absolute inset-y-0 items-center left-0 pl-3 pointer-events-none">
                                                <i class="text-emerald-600 fa-female fas"></i>
                                            </span>
                                            <input v-model="studentInfoForm.mother_name_bn" type="text"
                                                class="border border-emerald-200 rounded-md text-gray-700 w-full pl-10 pr-3 py-2">
                                        </div>


                                    </div>

                                    <div class="relative">
                                        <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">মাতার
                                            নাম (ইংরেজি)</label>
                                        <div class="relative">
                                            <span
                                                class="flex absolute inset-y-0 items-center left-0 pl-3 pointer-events-none">
                                                <i class="text-emerald-600 fa-female fas"></i>
                                            </span>
                                            <input v-model="studentInfoForm.mother_name_en" type="text"
                                                class="border border-emerald-200 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-emerald-500 pl-10 pr-3 py-2">
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <label class="text-gray-700 text-lg block font-arabic font-medium mb-1">মাতার
                                            নাম (আরবি)</label>
                                        <div class="relative">
                                            <span
                                                class="flex absolute inset-y-0 items-center left-0 pl-3 pointer-events-none">
                                                <i class="text-emerald-600 fa-female fas"></i>
                                            </span>
                                            <input v-model="studentInfoForm.mother_name_ar" type="text"
                                                class="border border-emerald-200 rounded-md w-full focus:outline-none focus:ring-2 focus:ring-emerald-500 pl-10 pr-3 py-2"
                                                dir="rtl">
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            <i class="text-emerald-600 fa-id-card fas"></i>
                                            জন্ম-নিবন্ধন নম্বর
                                        </label>
                                        <div class="relative">
                                            <input v-model="studentInfoForm.BRN_no" type="text"
                                                class="border border-emerald-200 rounded-md text-emerald-800 w-full pl-4 pr-3 py-2">
                                        </div>
                                    </div>


                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            <i class="text-emerald-600 fa-id-card fas"></i>
                                            জাতীয় পরীচয়পত্র
                                        </label>
                                        <div class="relative">
                                            <input v-model="studentInfoForm.NID_no" type="text"
                                                class="border border-emerald-200 rounded-md text-emerald-800 w-full pl-4 pr-3 py-2">
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            <i class="text-emerald-600 fa-id-card fas"></i>
                                            অভিবাকের ফোন নম্বর
                                        </label>
                                        <div class="relative">
                                            <input v-model="studentInfoForm.mobile_no" type="text"
                                                class="border border-emerald-200 rounded-md text-emerald-800 w-full pl-4 pr-3 py-2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Student Information Section -->
                        <div class="bg-white border border-emerald-100 rounded-lg shadow">
                            <div
                                class="bg-gradient-to-r rounded-t-md from-emerald-800 overflow-hidden px-6 py-3 relative to-emerald-600">
                                <div class="bg-pattern absolute inset-0 opacity-10"></div>
                                <div class="flex gap-3 items-center relative z-10">
                                    <i class="text-2xl text-white fa-graduation-cap fas"></i>
                                    <h5 class="text-white text-xl font-arabic">বিগত পরীক্ষার তথ্য</h5>
                                </div>
                            </div>

                            <div class="bg-opacity-5 bg-white p-6">
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                    <!-- Madrasha -->
                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            <i class="text-emerald-600 fa-school fas"></i>
                                            মাদরাসা
                                        </label>
                                        <div class="relative">
                                            <input v-model="pastExamForm.Madrasha" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
                                        </div>
                                    </div>

                                    <!-- Markaj -->
                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            <i class="text-emerald-600 fa-school fas"></i>
                                            মারকায
                                        </label>
                                        <div class="relative">
                                            <input v-model="pastExamForm.Markaj" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
                                        </div>
                                    </div>

                                    <!-- Class -->
                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            <i class="text-emerald-600 fa-layer-group fas"></i>
                                            শ্রেণী
                                        </label>
                                        <div class="relative">
                                            <input v-model="pastExamForm.Class" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
                                        </div>
                                    </div>

                                    <!-- Roll Number -->
                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            <i class="text-emerald-600 fa-id-card fas"></i>
                                            রোল নম্বর
                                        </label>
                                        <div class="relative">
                                            <input v-model="pastExamForm.Roll" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
                                        </div>
                                    </div>

                                    <!-- Registration ID -->
                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            <i class="text-emerald-600 fa-id-card fas"></i>
                                            রেজিস্ট্রেশন আইডি
                                        </label>
                                        <div class="relative">
                                            <input v-model="pastExamForm.reg_id" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
                                        </div>
                                    </div>

                                    <!-- Date of Birth -->
                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            <i class="text-emerald-600 fa-calendar-alt fas"></i>
                                            জন্ম-তারিখ
                                        </label>
                                        <div class="relative">
                                            <input v-model="pastExamForm.DateofBirth" type="date" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
                                        </div>
                                    </div>

                                    <!-- Result -->
                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            <i class="text-emerald-600 fa-calendar-alt fas"></i>
                                            ফলাফল
                                        </label>
                                        <div class="relative">
                                            <input v-model="pastExamForm.Division" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
                                        </div>
                                    </div>


                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">

                                            পরীক্ষার্থীর ধরন
                                        </label>
                                        <div class="relative">
                                            <input v-model="currentExamForm.student_type" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
                                        </div>
                                    </div>

                                    <div class="relative"
                                        v-if="currentExamForm.student_type === 'অনিয়মিত যেমনী' || currentExamForm.student_type === 'অনিয়মিত অন্যান্য'">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            অনিয়মিত হলে পরীক্ষা দিতে হবে এমন কিতাবের নাম
                                        </label>
                                        <div class="relative">
                                            <input v-model="currentExamForm.irregular_subjects" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>




                        <!-- <div class="bg-white border border-emerald-100 rounded-lg shadow">
                            <div
                                class="bg-gradient-to-r rounded-t-md from-emerald-800 overflow-hidden px-6 py-3 relative to-emerald-600">
                                <div class="bg-[url( absolute inset-0 opacity-10"></div>
                                <div class="flex gap-3 items-center relative z-10">
                                    <i class="text-2xl text-white fa-graduation-cap fas"></i>
                                    <h5 class="text-white text-xl font-arabic">বর্তমান পরীক্ষার তথ্য</h5>
                                </div>
                            </div>

                            <div class="bg-opacity-5 p-6">
                                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            <i class="text-emerald-600 fa-school fas"></i>
                                            মাদরাসা
                                        </label>
                                        <div class="relative">
                                            <input v-model="currentExamForm.Madrasha" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            <i class="text-emerald-600 fa-school fas"></i>
                                            মারকায
                                        </label>
                                        <div class="relative">
                                            <input v-model="currentExamForm.Markaj" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
                                        </div>
                                    </div>


                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            <i class="text-emerald-600 fa-layer-group fas"></i>
                                            শ্রেণী
                                        </label>
                                        <div class="relative">
                                            <input v-model="currentExamForm.Class" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
                                        </div>
                                    </div>

                                    <div class="relative">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">

                                            পরীক্ষার্থীর ধরন
                                        </label>
                                        <div class="relative">
                                            <input v-model="currentExamForm.student_type" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
                                        </div>
                                    </div>




                                    <div class="relative"
                                        v-if="currentExamForm.student_type === 'অনিয়মিত যেমনী' || currentExamForm.student_type === 'অনিয়মিত অন্যান্য'">
                                        <label
                                            class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                            অনিয়মিত হলে পরীক্ষা দিতে হবে এমন কিতাবের নাম
                                        </label>
                                        <div class="relative">
                                            <input v-model="currentExamForm.irregular_subjects" type="text" disabled
                                                class="bg-emerald-50 border border-emerald-200 rounded-md text-emerald-800 w-full cursor-not-allowed pl-4 pr-3 py-2">
                                        </div>
                                    </div>


                                </div>


                            </div>
                        </div> -->


                        <!-- Address Section -->
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                            <!-- Present Address -->
                            <div class="bg-white border border-emerald-100 rounded-md shadow">
                                <div
                                    class="bg-gradient-to-r rounded-t-md from-emerald-800 overflow-hidden px-6 py-3 relative to-emerald-600">
                                    <div class="bg-pattern absolute inset-0 opacity-10"></div>
                                    <div class="flex gap-3 items-center relative z-10">
                                        <i class="text-2xl text-white fa-map-marker-alt fas"></i>
                                        <h5 class="text-white text-xl font-arabic">বর্তমান ঠিকানা</h5>
                                    </div>
                                </div>

                                <div class="bg-opacity-5 bg-white p-6">
                                    <div class="grid grid-cols-1 gap-4">
                                        <div class="relative">
                                            <label
                                                class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                                বিভাগ
                                            </label>
                                            <div class="relative">
                                                <select v-model="presentFilters.division"
                                                    @change="presentHandleDivisionChange"
                                                    class="bg-white border border-gray-200 rounded-sm w-full block focus:ring-[#2d6a4f] focus:ring-2 px-4 py-2">
                                                    <option value="">সকল</option>
                                                    <option v-for="division in divisions" :key="division.id"
                                                        :value="division.id">
                                                        {{ division.Division }}
                                                    </option>
                                                </select>
                                                <div
                                                    class="flex absolute inset-y-0 items-center pointer-events-none px-2 right-0">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="relative">
                                            <label
                                                class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                                জেলা
                                            </label>
                                            <div class="relative">
                                                <select v-model="presentFilters.district"
                                                    @change="presentHandleDistrictChange"
                                                    class="bg-white border border-gray-200 rounded-sm w-full block focus:ring-[#2d6a4f] focus:ring-2 px-4 py-2">
                                                    <option value="">সকল</option>
                                                    <option v-for="district in presentDistricts" :key="district.DesID"
                                                        :value="district.DesID">
                                                        {{ district.District }}
                                                    </option>
                                                </select>
                                                <div
                                                    class="flex absolute inset-y-0 items-center pointer-events-none px-2 right-0">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="relative">
                                            <label
                                                class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                                থানা/উপজেলা
                                            </label>
                                            <div class="relative">
                                                <select v-model="presentFilters.Thana"
                                                    class="block w-full px-4 py-2 bg-white border border-gray-200 rounded-sm focus:ring-2 focus:ring-[#2d6a4f]">
                                                    <option value="">সকল</option>
                                                    <option v-for="Thana in presentThanas" :key="Thana.Thana_ID"
                                                        :value="Thana.Thana_ID">
                                                        {{ Thana.Thana }}
                                                    </option>
                                                </select>


                                                <div
                                                    class="flex absolute inset-y-0 items-center pointer-events-none px-2 right-0">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Permanent Address -->
                            <div class="bg-white border border-emerald-100 rounded-md shadow">
                                <div
                                    class="bg-gradient-to-r rounded-t-md from-emerald-800 overflow-hidden px-6 py-3 relative to-emerald-600">
                                    <div class="bg-pattern absolute inset-0 opacity-10"></div>
                                    <div class="flex gap-3 items-center relative z-10">
                                        <i class="text-2xl text-white fa-home fas"></i>
                                        <h5 class="text-white text-xl font-arabic">স্থায়ী ঠিকানা</h5>
                                    </div>
                                </div>

                                <div class="bg-opacity-5 bg-white p-6">
                                    <div class="grid grid-cols-1 gap-4">
                                        <div class="relative">
                                            <label
                                                class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                                বিভাগ
                                            </label>
                                            <div class="relative">
                                                <select v-model="permanentFilters.division"
                                                    @change="handleDivisionChange"
                                                    class="bg-white border border-gray-200 rounded-sm w-full block focus:ring-[#2d6a4f] focus:ring-2 px-4 py-2">
                                                    <option value="">সকল</option>
                                                    <option v-for="division in divisions" :key="division.id"
                                                        :value="division.id">
                                                        {{ division.Division }}
                                                    </option>
                                                </select>
                                                <div
                                                    class="flex absolute inset-y-0 items-center pointer-events-none px-2 right-0">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="relative">
                                            <label
                                                class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                                জেলা
                                            </label>
                                            <div class="relative">
                                                <select v-model="permanentFilters.district"
                                                    @change="handleDistrictChange"
                                                    class="bg-white border border-gray-200 rounded-sm w-full block focus:ring-[#2d6a4f] focus:ring-2 px-4 py-2">
                                                    <option value="">সকল</option>
                                                    <option v-for="district in districts" :key="district.DesID"
                                                        :value="district.DesID">
                                                        {{ district.District }}
                                                    </option>
                                                </select>
                                                <div
                                                    class="flex absolute inset-y-0 items-center pointer-events-none px-2 right-0">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="relative">
                                            <label
                                                class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">
                                                থানা/উপজেলা
                                            </label>


                                            <select v-model="permanentFilters.Thana"
                                                class="block w-full px-4 py-2 bg-white border border-gray-200 rounded-sm focus:ring-2 focus:ring-[#2d6a4f]">
                                                <option value="">সকল</option>
                                                <option v-for="Thana in thanas" :key="Thana.Thana_ID"
                                                    :value="Thana.Thana_ID">
                                                    {{ Thana.Thana }}
                                                </option>
                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Attachment Section -->
                        <div class="bg-white border border-emerald-100 rounded-md shadow">
                            <div class="bg-white border border-emerald-200 p-6 rounded-md shadow-md">
                                <h3 class="text-emerald-800 text-xl arabic-font font-bold mb-6">সংযুক্তি</h3>

                                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                    <!-- Student Photo -->
                                    <div class="space-y-3">
                                        <label class="text-emerald-700 text-lg arabic-font block font-medium">
                                            ছাত্রের ছবি (পাসপোর্ট সাইজ এবং নীল ব্যাকগ্রাইন্ড হতে হবে)
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="flex bg-emerald-50 border-2 border-dashed border-emerald-300 justify-between p-4 rounded-md duration-200 hover:border-emerald-500 items-center transition-colors">
                                                <span class="text-emerald-600 arabic-font">
                                                    {{ studentPhoto ? studentPhoto.name : 'ফাইল আপলোড করুন' }}
                                                </span>
                                                <div v-if="studentPhotoPreview"
                                                    class="flex items-center pointer-events-auto relative space-x-2 z-10">
                                                    <a :href="studentPhotoPreview" target="_blank"
                                                        class="text-emerald-600 hover:text-emerald-800">প্রিভিউ</a>
                                                    <button @click.stop="removeFile('studentPhoto')"
                                                        class="text-red-600 hover:text-red-800">মুছুন</button>
                                                </div>
                                            </div>
                                            <input type="file" @change="handleFileUpload($event, 'studentPhoto')"
                                                class="h-full w-full absolute cursor-pointer inset-0 opacity-0 z-0"
                                                accept="image/*,.pdf,.doc,.docx">
                                        </div>
                                    </div>

                                    <!-- Birth Certificate/NID -->
                                    <div class="space-y-3">
                                        <label class="text-emerald-700 text-lg arabic-font block font-medium">
                                            জন্ম নিবন্ধন/এন আইডি সংযুক্তি করুন
                                        </label>
                                        <div class="relative">
                                            <div
                                                class="flex bg-emerald-50 border-2 border-dashed border-emerald-300 justify-between p-4 rounded-md duration-200 hover:border-emerald-500 items-center transition-colors">
                                                <span class="text-emerald-600 arabic-font">
                                                    {{ nidAttachment ? nidAttachment.name : 'ফাইল আপলোড করুন' }}
                                                </span>
                                                <div v-if="nidAttachmentPreview"
                                                    class="flex items-center pointer-events-auto relative space-x-2 z-10">
                                                    <a :href="nidAttachmentPreview" target="_blank"
                                                        class="text-emerald-600 hover:text-emerald-800">প্রিভিউ</a>
                                                    <button @click.stop="removeFile('nidAttachment')"
                                                        class="text-red-600 hover:text-red-800">মুছুন</button>
                                                </div>
                                            </div>
                                            <input type="file" @change="handleFileUpload($event, 'nidAttachment')"
                                                class="h-full w-full absolute cursor-pointer inset-0 opacity-0 z-0"
                                                accept="image/*,.pdf,.doc,.docx">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-4">
                            <button type="button" @click="goBack"
                                class="flex bg-gray-200 rounded-md text-gray-700 font-arabic gap-2 hover:bg-gray-300 items-center px-6 py-1.5 transition-colors">
                                <i class="fa-times fas"></i> বাতিল করুন
                            </button>

                            <!-- <button type="submit"
                                class="flex bg-emerald-600 rounded-md text-white gap-2 hover:bg-emerald-700 items-center px-6 py-1.5 transition-colors"
                                :disabled="pastExamForm.processing">
                                <i class="fa-save fas"></i>
                                <span v-if="pastExamForm.processing">সংরক্ষণ হচ্ছে...</span>
                                <span v-else>সংরক্ষণ করুন</span>
                            </button> -->

                            <div class="mt-6">
                                <button @click.prevent="submitStudentInfo"
                                    class="bg-emerald-600 rounded-md shadow-md text-white font-bold hover:bg-emerald-700 px-6 py-2">
                                    <i class="fa-save fas mr-2"></i> সংরক্ষণ করুন
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>

</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, reactive, watch } from 'vue';
import { Link, useForm } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";
// Student data with initial values
const props = defineProps({
    roll: String,
    reg_id: String,
    CID: String,
    modelValue: Object
});

const toaster = createToaster({
  position: "top-right",
  duration: 3000 // 3 সেকেন্ড দেখাবে
});


const examName = ref('');
const marhalaName = ref('');
const currentMarhalaId = ref(null);

const student = ref(null);
const loading = ref(true);
const error = ref(null);

// Past exam form
const pastExamForm = useForm({
    Name: '',
    Father: '',
    Mother: '',
    DateofBirth: '',
    Roll: '',
    reg_id: '',
    CID: '',
    Madrasha: '',
    Markaj: '',
    Class: '',
    Division: ''


});

// Current exam form
const currentExamForm = useForm({
    Madrasha: '',
    Markaj: '',
    Class: '',
    student_type: '',
    marhalaId: '',
    irregular_subjects: ''
});




const studentInfoForm = useForm({
    // student_id: '',      // Will be populated from pastExamForm.id
    name_bn: '',         // Will be populated from pastExamForm.Name
    name_en: '',         // New field for English name
    name_ar: '',         // New field for Arabic name
    father_name_bn: '',  // Will be populated from pastExamForm.Father
    father_name_en: '',  // New field for father's English name
    father_name_ar: '',  // New field for father's Arabic name
    mother_name_bn: '',  // Will be populated from pastExamForm.Mother
    mother_name_en: '',  // New field for mother's English name
    mother_name_ar: '',  // New field for mother's Arabic name
    BRN_no: '', // New field for birth certificate
    NID_no: '',
    past_Roll: '',            // New field for national ID
    past_reg_id: '',
    madrasha_name: '',
    markaz_name: '',
    passing_year: '',
    class: '',
    Division: '',
    Date_of_birth: '',
    current_madrasha: '',
    current_markaz: '',
    student_type: '',
    current_class: '',
    exam_books_name: '',
    mobile_no: '',
    user_name: '',
    user_id: '',
    markaz_id: '',
    NID_attach: '',
    marhala_id: '',


    present_division_name: '',
    presernt_DID: '',
    present_district_name: '',
    present_desId: '',
    present_thana_name: '',
    present_TID: '',

    // New fields for permanent address
    parmanent_division_name: '',
    parmanent_DID: '',
    parmanent_district_name: '',
    parmanent_desId: '',
    parmanent_thana_name: '',
    parmanent_TID: '',
    student_image: '',
});







onMounted(async () => {
    try {
        // Get marhalaId from localStorage or another source


        const response = await axios.get('/api/get-student-for-edit', {
            params: {
                roll: props.roll,
                reg_id: props.reg_id,
                CID: props.CID,
                 marhalaId: localStorage.getItem('marhalaId') || ''
            }
        });

        student.value = response.data;

        // Populate the past exam form
        if (response.data.pastExam) {
            pastExamForm.Name = response.data.pastExam.Name || '';
            pastExamForm.Father = response.data.pastExam.Father || '';
            pastExamForm.Mother = response.data.pastExam.Mother || '';
            pastExamForm.DateofBirth = response.data.pastExam.DateofBirth || '';
            pastExamForm.Roll = response.data.pastExam.Roll || '';
            pastExamForm.reg_id = response.data.pastExam.reg_id || '';
            pastExamForm.Madrasha = response.data.pastExam.Madrasha || '';
            pastExamForm.Markaj = response.data.pastExam.Markaj || '';
            pastExamForm.Class = response.data.pastExam.Class || '';
            pastExamForm.Division = response.data.pastExam.Division || '';
            // studentInfoForm.student_id = response.data.pastExam.id;
            studentInfoForm.name_bn = response.data.pastExam.Name;
            studentInfoForm.father_name_bn = response.data.pastExam.Father;
            studentInfoForm.mother_name_bn = response.data.pastExam.Mother;
            studentInfoForm.past_Roll = response.data.pastExam.Roll;
            studentInfoForm.past_reg_id = response.data.pastExam.reg_id;
            studentInfoForm.madrasha_name = response.data.pastExam.Madrasha;
            studentInfoForm.markaz_name = response.data.pastExam.Markaj;
               studentInfoForm.passing_year = response.data.pastExam.years;
            studentInfoForm.class = response.data.pastExam.Class;
            studentInfoForm.Division = response.data.pastExam.Division;
            studentInfoForm.Date_of_birth = response.data.pastExam.DateofBirth;

        }

        // Populate the current exam form
   // Populate the current exam form
if (response.data.currentExam) {
    currentExamForm.Madrasha = response.data.currentExam.Madrasha || '';
    currentExamForm.Markaj = response.data.currentExam.Markaj || '';
    currentExamForm.Class = response.data.currentExam.Class || '';

    // এখানে student_type সেট করা হচ্ছে
    currentExamForm.student_type = response.data.currentExam.student_type || '';

    currentExamForm.marhalaId = response.data.currentExam.marhalaId || '';
    currentExamForm.irregular_subjects = response.data.currentExam.irregular_subjects || '';
    studentInfoForm.current_madrasha = response.data.currentExam.Madrasha
    studentInfoForm.current_markaz = response.data.currentExam.Markaj
    studentInfoForm.student_type = response.data.currentExam.student_type
    // studentInfoForm.current_class = response.data.currentExam.Class
    studentInfoForm.exam_books_name = response.data.currentExam.irregular_subjects
}




        // Populate address fields if they exist in the response
        if (response.data.studentInfo) {
            studentInfoForm.present_division_name = response.data.studentInfo.present_division_name || '';
            studentInfoForm.presernt_DID = response.data.studentInfo.presernt_DID || '';
            studentInfoForm.present_district_name = response.data.studentInfo.present_district_name || '';
            studentInfoForm.present_desId = response.data.studentInfo.present_des_id || '';
            studentInfoForm.present_thana_name = response.data.studentInfo.present_thana_name || '';
            studentInfoForm.present_TID = response.data.studentInfo.present_TID || '';

            studentInfoForm.parmanent_division_name = response.data.studentInfo.parmanent_division_name || '';
            studentInfoForm.parmanent_DID = response.data.studentInfo.parmanent_DID || '';
            studentInfoForm.parmanent_district_name = response.data.studentInfo.parmanent_district_name || '';
            studentInfoForm.parmanent_desId = response.data.studentInfo.parmanent_desId || '';
            studentInfoForm.parmanent_thana_name = response.data.studentInfo.parmanent_thana_name || '';
            studentInfoForm.parmanent_TID = response.data.studentInfo.parmanent_TID || '';
        }




        loading.value = false;
    } catch (err) {
        error.value = 'ছাত্রের তথ্য লোড করতে সমস্যা হয়েছে';
        loading.value = false;
        console.error('Error fetching student data:', err);
    }



});



// In the submitStudentInfo function, the marhalaId is not being properly set
const submitStudentInfo = () => {
    // আগে ঠিকানার ডাটা আপডেট করুন
    updateFormData();

    // মারহালা আইডি সেট করুন
    const marhalaId = props.CID || localStorage.getItem('marhalaId');
    studentInfoForm.marhala_id = marhalaId;

    // ফাইল যোগ করুন
    if (studentPhoto.value) {
        studentInfoForm.student_image = studentPhoto.value;
    }
    if (nidAttachment.value) {
        studentInfoForm.NID_attach = nidAttachment.value;
    }

    // ফর্ম সাবমিট করুন
    studentInfoForm.post(`/api/save-student-info/${marhalaId}`, {
        forceFormData: true,
        onSuccess: (page) => {
        // page.props.flash.success থেকে মেসেজ নিন
        toaster.success(page.props.flash?.success || 'ছাত্রের তথ্য সফলভাবে সংরক্ষণ করা হয়েছে');
    },
    onError: (errors) => {
        toaster.error('ছাত্রের তথ্য সংরক্ষণ করতে সমস্যা হয়েছে');
        console.error('Error saving student information:', errors);
    },
    preserveScroll: true
    });
};








// Attachment files
const studentPhoto = ref(null);
const studentPhotoPreview = ref(null);
const nidAttachment = ref(null);
const nidAttachmentPreview = ref(null);

// ফাইল আপলোড হ্যান্ডলার
const handleFileUpload = (event, type) => {
    const file = event.target.files[0];
    if (!file) return;

    // ফাইল টাইপ অনুযায়ী সেট করুন
    if (type === 'studentPhoto') {
        studentPhoto.value = file;
        createFilePreview(file, 'studentPhoto');
    } else if (type === 'nidAttachment') {
        nidAttachment.value = file;
        createFilePreview(file, 'nidAttachment');
    }
};

// ফাইল প্রিভিউ তৈরি করুন
const createFilePreview = (file, type) => {
    const reader = new FileReader();
    reader.onload = (e) => {
        if (type === 'studentPhoto') {
            studentPhotoPreview.value = e.target.result;
        } else if (type === 'nidAttachment') {
            nidAttachmentPreview.value = e.target.result;
        }
    };
    reader.readAsDataURL(file);
};

// ফাইল রিমুভ করুন
const removeFile = (type) => {
    if (type === 'studentPhoto') {
        studentPhoto.value = null;
        studentPhotoPreview.value = null;
    } else if (type === 'nidAttachment') {
        nidAttachment.value = null;
        nidAttachmentPreview.value = null;
    }
};

// Go back to previous page without using router
const goBack = () => {
    window.history.back();
};










// বিভাগ/ থানা/ জেলা

// const props = defineProps({
//   modelValue: Object
// });

const emit = defineEmits(['update:modelValue']);

const divisions = ref([]);
const presentDistricts = ref([]);
const presentThanas = ref([]);
const districts = ref([]);
const thanas = ref([]);

// Separate refs for present and permanent address data
const presentFilters = ref({
    division: '',
    district: '',
    Thana: '' // 'T' uppercase করে দিন
});

const permanentFilters = ref({
    division: '',
    district: '',
    Thana: '' // 'T' uppercase করে দিন
});

// Initialize filters from props if they exist
const initializeFilters = () => {
    if (props.modelValue && props.modelValue.presernt_DID) {
        presentFilters.value.division = props.modelValue.presernt_DID;
    }
    if (props.modelValue && props.modelValue.present_des_id) {
        presentFilters.value.district = props.modelValue.present_des_id;
    }
    if (props.modelValue && props.modelValue.present_TID) {
        presentFilters.value.Thana = props.modelValue.present_TID;
    }

    if (props.modelValue && props.modelValue.parmanent_DID) {
        permanentFilters.value.division = props.modelValue.parmanent_DID;
    }
    if (props.modelValue && props.modelValue.parmanent_desId) {
        permanentFilters.value.district = props.modelValue.parmanent_desId;
    }
    if (props.modelValue && props.modelValue.parmanent_TID) {
        permanentFilters.value.Thana = props.modelValue.parmanent_TID;
    }
};

// Watch for changes in props.modelValue to initialize filters
watch(() => props.modelValue, (newValue) => {
    if (newValue) {
        initializeFilters();
    }
}, { immediate: true });

// Watch for changes in presentFilters and update the form
watch(presentFilters, async (newValues) => {
    if (!props.modelValue) return;

    const updatedValue = { ...props.modelValue };

    // Update division
    if (newValues.division) {
        const selectedDivision = divisions.value.find(d => d.id == newValues.division);
        if (selectedDivision) {
            updatedValue.present_division_name = selectedDivision.Division_U;
            updatedValue.presernt_DID = selectedDivision.id;
        }
    }

    // Update district
    if (newValues.district) {
        const selectedDistrict = presentDistricts.value.find(d => d.DesID == newValues.district);
        if (selectedDistrict) {
            updatedValue.present_district_name = selectedDistrict.District_U;
            updatedValue.present_des_id = selectedDistrict.DesID;
        }
    }

    // Update thana
    if (newValues.Thana) {
        const selectedThana = presentThanas.value.find(t => t.Thana_ID == newValues.Thana);
        if (selectedThana) {
            updatedValue.present_thana_name = selectedThana.Thana_U;
            updatedValue.present_TID = selectedThana.Thana_ID;
        }
    }

    emit('update:modelValue', updatedValue);
}, { deep: true });

// Watch for changes in permanentFilters and update the form
watch(permanentFilters, async (newValues) => {
    if (!props.modelValue) return;

    const updatedValue = { ...props.modelValue };

    // Update division
    if (newValues.division) {
        const selectedDivision = divisions.value.find(d => d.id == newValues.division);
        if (selectedDivision) {
            updatedValue.parmanent_division_name = selectedDivision.Division_U;
            updatedValue.parmanent_DID = selectedDivision.id;
        }
    }

    // Update district
    if (newValues.district) {
        const selectedDistrict = districts.value.find(d => d.DesID == newValues.district);
        if (selectedDistrict) {
            updatedValue.parmanent_district_name = selectedDistrict.District_U;
            updatedValue.parmanent_desId = selectedDistrict.DesID;
        }
    }

    // Update thana
    if (newValues.Thana) {
        const selectedThana = thanas.value.find(t => t.Thana_ID == newValues.Thana);
        if (selectedThana) {
            updatedValue.parmanent_thana_name = selectedThana.Thana_U;
            updatedValue.parmanent_TID = selectedThana.Thana_ID;
        }
    }

    emit('update:modelValue', updatedValue);
}, { deep: true });

onMounted(async () => {
    await loadDivisions();
    initializeFilters();

    // If we have saved values, load the related districts and thanas
    if (presentFilters.value.division) {
        await presentHandleDivisionChange();

        if (presentFilters.value.district) {
            await presentHandleDistrictChange();
        }
    }

    if (permanentFilters.value.division) {
        await handleDivisionChange();

        if (permanentFilters.value.district) {
            await handleDistrictChange();
        }
    }


    const marhalaId = route().params.marhalaId;
    currentMarhalaId.value = marhalaId;

    try {
        const response = await axios.get(`/api/student-registration_old/${marhalaId}`);
        examName.value = response.data.examName;
        marhalaName.value = response.data.marhalaName;

        // এই লাইন যোগ করুন - studentInfoForm এ মারহালার নাম সেট করুন
        studentInfoForm.current_class = response.data.marhalaName;
        studentInfoForm.marhala_id = marhalaId;

    } catch (error) {
        console.error("Error fetching marhala info:", error);
    }


});







const loadDivisions = async () => {
    try {
        const response = await axios.get('/api/divisions');
        divisions.value = response.data;
        return true;
    } catch (error) {
        console.error('Error loading divisions:', error);
        return false;
    }
};

// Present address handlers
const presentHandleDivisionChange = async () => {
    presentFilters.value.district = '';
    presentFilters.value.Thana = '';
    presentDistricts.value = [];
    presentThanas.value = [];

    if (!presentFilters.value.division) {
        return;
    }

    try {
        const response = await axios.get(`/api/districts/${presentFilters.value.division}`);
        presentDistricts.value = response.data;
    } catch (error) {
        console.error('Error loading districts:', error);
    }
};

const presentHandleDistrictChange = async () => {
    presentFilters.value.Thana = '';
    presentThanas.value = [];

    if (!presentFilters.value.district) {
        return;
    }

    try {
        const response = await axios.get(`/api/thanas/${presentFilters.value.district}`);
        presentThanas.value = response.data;
    } catch (error) {
        console.error('Error loading thanas:', error);
    }
};

// Permanent address handlers
const handleDivisionChange = async () => {
    permanentFilters.value.district = '';
    permanentFilters.value.Thana = '';
    districts.value = [];
    thanas.value = [];

    if (!permanentFilters.value.division) {
        return;
    }

    try {
        const response = await axios.get(`/api/districts/${permanentFilters.value.division}`);
        districts.value = response.data;
    } catch (error) {
        console.error('Error loading districts:', error);
    }
};

const handleDistrictChange = async () => {
    permanentFilters.value.Thana = '';
    thanas.value = [];

    if (!permanentFilters.value.district) {
        return;
    }

    try {
        const response = await axios.get(`/api/thanas/${permanentFilters.value.district}`);
        thanas.value = response.data;
    } catch (error) {
        console.error('Error loading thanas:', error);
    }
};







const updateFormData = () => {
    // বর্তমান ঠিকানার ডাটা আপডেট
    if (presentFilters.value.division) {
        const selectedDivision = divisions.value.find(d => d.id == presentFilters.value.division);
        if (selectedDivision) {
            studentInfoForm.present_division_name = selectedDivision.Division;
            studentInfoForm.presernt_DID = selectedDivision.id;
        }
    }

    if (presentFilters.value.district) {
        const selectedDistrict = presentDistricts.value.find(d => d.DesID == presentFilters.value.district);
        if (selectedDistrict) {
            studentInfoForm.present_district_name = selectedDistrict.District;
            studentInfoForm.present_desId = selectedDistrict.DesID;
        }
    }

    if (presentFilters.value.Thana) {
        const selectedThana = presentThanas.value.find(t => t.Thana_ID == presentFilters.value.Thana);
        if (selectedThana) {
            studentInfoForm.present_thana_name = selectedThana.Thana;
            studentInfoForm.present_TID = selectedThana.Thana_ID;
        }
    }

    // স্থায়ী ঠিকানার ডাটা আপডেট
    if (permanentFilters.value.division) {
        const selectedDivision = divisions.value.find(d => d.id == permanentFilters.value.division);
        if (selectedDivision) {
            studentInfoForm.parmanent_division_name = selectedDivision.Division;
            studentInfoForm.parmanent_DID = selectedDivision.id;
        }
    }

    if (permanentFilters.value.district) {
        const selectedDistrict = districts.value.find(d => d.DesID == permanentFilters.value.district);
        if (selectedDistrict) {
            studentInfoForm.parmanent_district_name = selectedDistrict.District;
            studentInfoForm.parmanent_desId = selectedDistrict.DesID;
        }
    }

    if (permanentFilters.value.Thana) {
        const selectedThana = thanas.value.find(t => t.Thana_ID == permanentFilters.value.Thana);
        if (selectedThana) {
            studentInfoForm.parmanent_thana_name = selectedThana.Thana;
            studentInfoForm.parmanent_TID = selectedThana.Thana_ID;
        }
    }
}




</script>


<style scoped>

</style>
