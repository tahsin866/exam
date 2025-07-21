<script setup>
import { ref } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

// Professional color palette
const sidebarBg = 'bg-[#18222e]';
const sidebarText = 'text-[#e0e6ed]';
const sidebarHover = 'hover:bg-[#223049] hover:text-emerald-400';
const sidebarSelected = 'bg-emerald-50/10 text-emerald-400';
const sidebarDropdownBg = 'bg-[#1d2737]';

// Sidebar state
const page = usePage();

const hasPermission = (permission) => {
    const user = page.props.auth.user;
    if (!user) return false;

    // Super admin has all permissions
    if (user.roles && user.roles.some(role => role.name === 'super-admin')) return true;

    // Get all permissions from user (direct + role permissions)
    const allPermissions = [];

    // Add direct permissions
    if (user.permissions) {
        allPermissions.push(...user.permissions.map(p => p.name));
    }

    // Add role permissions
    if (user.roles) {
        user.roles.forEach(role => {
            if (role.permissions) {
                allPermissions.push(...role.permissions.map(p => p.name));
            }
        });
    }

    // Check if user has this permission
    return allPermissions.includes(permission);
};

const isSuperAdmin = () => {
    const user = page.props.auth.user;
    return user && user.roles && user.roles.some(role => role.name === 'super-admin');
};

const dropdownPermissions = {
    setup: ['setup.marhala', 'setup.subject', 'setup.central_exam', 'setup.user', 'setup.instruction'],
    bill: ['bill.negran', 'bill.mumtahin', 'bill.markaz_admin', 'bill.zonal'],
    madrasa: ['madrasa.list', 'madrasa.misc', 'madrasa.admin', 'madrasa.draft_soft_delete', 'madrasa.payment'],
    markaz: ['markaz.application_list', 'markaz.proposed', 'markaz.list', 'markaz.change_application', 'markaz.admin', 'markaz.admin_training', 'markaz.group'],
    examRoutine: ['exam_routine.group', 'exam_routine.routine', 'exam_routine.oral_exam_mumtahin'],
    registration: ['registration.list', 'registration.payment', 'registration.card_create'],
    inclusion: ['inclusion.list', 'inclusion.roll_generate', 'inclusion.admit_card_create', 'inclusion.payment', 'inclusion.cancelled_list'],
    khataLoose: ['khata_loose.setup', 'khata_loose.generate', 'khata_loose.account', 'khata_loose.others'],
    negran: ['negran.application_list', 'negran.proposed_list', 'negran.pending_list', 'negran.cancelled_list', 'negran.mumtahin_list', 'negran.report', 'negran.allowance'],
    mumtahin: ['mumtahin.application_list', 'mumtahin.proposed_list', 'mumtahin.inspector_setup', 'mumtahin.list', 'mumtahin.darsiyat', 'mumtahin.hifz_selection', 'mumtahin.qirat_selection', 'mumtahin.training'],
    zone: ['zone.setup', 'zone.selection', 'zone.sub_zone_setup', 'zone.markaz_setup'],
    attendance: ['attendance.examinee', 'attendance.negran'],
    result: ['result.condition', 'result.merit_condition', 'result.inspection_formula', 'result.darsiyat_result', 'result.oral_result', 'result.correction', 'result.review', 'result.review_correction'],
};

const hasDropdownPermission = (dropdownKey) => {
    if (isSuperAdmin()) return true;
    const permissions = dropdownPermissions[dropdownKey];
    if (!permissions) return false;
    return permissions.some(permission => hasPermission(permission));
};

const dropdownKeys = [
    'setup', 'bill', 'madrasa', 'markaz', 'examRoutine', 'registration', 'inclusion',
    'khataLoose', 'negran', 'mumtahin', 'zone', 'attendance', 'result',
];
const dropdownOpen = ref({
    setup: false, bill: false, madrasa: false, markaz: false, examRoutine: false,
    registration: false, inclusion: false, khataLoose: false, negran: false,
    mumtahin: false, zone: false, attendance: false, result: false,
});
const selectedItem = ref('');
const itemToDropdown = {
    marhala: 'setup',
    subject: 'setup',
    central_exam: 'setup',
    user_setup: 'setup',
    instruction: 'setup',

    negran_bill: 'bill',
    mumtahin_bill: 'bill',
    markaz_admin_bill: 'bill',
    zonal_bill: 'bill',

    madrasa_list: 'madrasa',
    madrasa_misc: 'madrasa',
    madrasa_admin: 'madrasa',
    draft_soft_delete: 'madrasa',
    madrasa_payment: 'madrasa',

    markaz_application_list: 'markaz',
    proposed_markaz: 'markaz',
    markaz_list: 'markaz',
    markaz_change_application: 'markaz',
    markaz_admin: 'markaz',
    markaz_admin_training: 'markaz',
    markaz_group: 'markaz',
    answer_sheet_group: 'markaz',
    mumtahin_group: 'markaz',

    exam_routine_group: 'examRoutine',
    exam_routine: 'examRoutine',
    oral_exam_mumtahin: 'examRoutine',

    madrasa_list_reg: 'registration',
    registration_list: 'registration',
    cancelled_registration_list: 'registration',
    payment_list: 'registration',
    board_return_list: 'registration',
    registration_card_create: 'registration',

    inclusion_list: 'inclusion',
    roll_generate: 'inclusion',
    admit_card_create: 'inclusion',
    inclusion_payment: 'inclusion',
    cancelled_inclusion_list: 'inclusion',

    khata_loose_setup: 'khataLoose',
    khata_loose_generate: 'khataLoose',
    khata_loose_account: 'khataLoose',
    khata_loose_others: 'khataLoose',

    negran_application_list: 'negran',
    negran_proposed_list: 'negran',
    negran_pending_list: 'negran',
    negran_cancelled_list: 'negran',
    mumtahin_list_negran: 'negran',
    negran_report: 'negran',
    negran_allowance: 'negran',

    mumtahin_application_list: 'mumtahin',
    mumtahin_proposed_list: 'mumtahin',
    inspector_setup: 'mumtahin',
    mumtahin_list_mumtahin: 'mumtahin',
    darsiyat_mumtahin: 'mumtahin',
    hifz_mumtahin_selection: 'mumtahin',
    qirat_mumtahin_selection: 'mumtahin',
    mumtahin_training: 'mumtahin',

    zone_setup: 'zone',
    zonal_selection: 'zone',
    sub_zone_setup: 'zone',
    zone_wise_markaz_setup: 'zone',

    examinee_attendance: 'attendance',
    negran_attendance: 'attendance',

    result_condition: 'result',
    merit_condition: 'result',
    inspection_formula: 'result',
    darsiyat_result: 'result',
    oral_result: 'result',
    result_correction: 'result',
    result_review: 'result',
    review_result_correction: 'result',
};
function toggleDropdown(key) {
    if (dropdownOpen.value[key]) {
        // If selectedItem belongs to this dropdown, keep open
        if (itemToDropdown[selectedItem.value] === key) return;
        dropdownOpen.value[key] = false;
    } else {
        dropdownKeys.forEach(k => dropdownOpen.value[k] = false);
        dropdownOpen.value[key] = true;
    }
}
function closeDropdowns() {
    dropdownKeys.forEach(k => dropdownOpen.value[k] = false);
}
function setSelected(item, parentDropdown = null) {
    selectedItem.value = item;
    if (parentDropdown) {
        dropdownKeys.forEach(k => dropdownOpen.value[k] = (k === parentDropdown));
    }
}
</script>

<template>
    <aside
      :class="[
        'fixed inset-y-0 left-0 z-40 w-64 transition-transform duration-300 ease-in-out',
        sidebarBg, sidebarText, 'lg:translate-x-0 border-none shadow-none'
      ]"
    >
        <div class="flex bg-gradient-to-r from-emerald-600/80 to-emerald-800/80 h-16 justify-center text-lg font-semibold items-center shadow-sm border-b border-emerald-900/30">
            <div class="flex gap-2 items-center">
                <svg class="h-8 w-8 text-emerald-300" viewBox="0 0 24 24" fill="none">
                    <path d="M12 2L3 9V20C3 20.5304 3.21071 21.0391 3.58579 21.4142C3.96086 21.7893 4.46957 22 5 22H19C19.5304 22 20.0391 21.7893 20.4142 21.4142C20.7893 21.0391 21 20.5304 21 20V9L12 2Z"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9 22V12H15V22"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                <span class="font-bold text-emerald-50 tracking-wide">এক্সাম মেনেজমেন্ট সিস্টেম</span>
            </div>
        </div>
        <nav class="h-[calc(100vh-4rem)] overflow-y-auto custom-scrollbar px-0 py-2">
            <!-- ড্যাশবোর্ড -->
            <Link
                :href="route('admin.dashboard')"
                class="flex items-center gap-3 px-5 py-2 rounded transition-all cursor-pointer text-base font-medium"
                :class="[
                  selectedItem === 'dashboard' ? sidebarSelected + ' border-l-4 border-emerald-400' : sidebarHover + ' border-l-4 border-transparent'
                ]"
                @click="setSelected('dashboard', null)"
            >
                <i class="pi pi-home text-lg" />
                <span>ই এম ড্যাশবোর্ড</span>
            </Link>

            <!-- সেটাপ সংক্রান্ত -->
            <div v-if="hasDropdownPermission('setup')">
                <div
                    class="flex items-center justify-between px-5 py-2 rounded cursor-pointer select-none transition-all font-medium"
                    :class="[
                      dropdownOpen.setup ? sidebarSelected + ' border-l-4 border-emerald-400' : sidebarHover + ' border-l-4 border-transparent'
                    ]"
                    @click="toggleDropdown('setup')"
                >
                    <div class="flex items-center gap-3">
                        <i class="pi pi-cog text-lg" />
                        <span>সেটাপ সংক্রান্ত</span>
                    </div>
                    <i class="pi pi-angle-down transition-transform duration-200" :class="{'rotate-180': dropdownOpen.setup}"/>
                </div>
                <transition name="sidebar-dropdown">
                    <div v-if="dropdownOpen.setup" :class="sidebarDropdownBg + ' rounded-md ml-3 my-1 py-1'">
                        <Link v-if="hasPermission('setup.marhala') || isSuperAdmin()" :href="route('Mrahala_for_Admin.marhala_details_list')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all"
                              :class="selectedItem === 'marhala' ? sidebarSelected : sidebarHover"
                              @click="setSelected('marhala', 'setup')">
                            <i class="pi pi-list text-base"/>
                            <span>মারহালা সেটাপ</span>
                        </Link>
                        <Link v-if="hasPermission('setup.subject') || isSuperAdmin()" :href="route('subjects_for_Admin.subject_search_wizard')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all"
                              :class="selectedItem === 'subject' ? sidebarSelected : sidebarHover"
                              @click="setSelected('subject', 'setup')">
                            <i class="pi pi-book text-base"/>
                            <span>বিষয় সেটাপ</span>
                        </Link>
                        <Link v-if="hasPermission('setup.central_exam') || isSuperAdmin()" :href="route('central_Exam_setup.central_exam_mng')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all"
                              :class="selectedItem === 'central_exam' ? sidebarSelected : sidebarHover"
                              @click="setSelected('central_exam', 'setup')">
                            <i class="pi pi-file text-base"/>
                            <span>কেন্দ্রীয় পরীক্ষা সেটাপ</span>
                        </Link>
                        <Link v-if="hasPermission('setup.user') || isSuperAdmin()" :href="route('admin.users.index')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all"
                              :class="selectedItem === 'user_setup' ? sidebarSelected : sidebarHover"
                              @click="setSelected('user_setup', 'setup')">
                            <i class="pi pi-users text-base"/>
                            <span>ব্যবহারকারী সেটাপ</span>
                        </Link>
                        <Link v-if="hasPermission('setup.instruction') || isSuperAdmin()" href="route('Takmil.cirtificateProvide')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all"
                              :class="selectedItem === 'instruction' ? sidebarSelected : sidebarHover"
                              @click="setSelected('instruction', 'setup')">
                            <i class="pi pi-info-circle text-base"/>
                            <span>নির্দেশনা</span>
                        </Link>
                    </div>
                </transition>
            </div>

            <!-- ভাতা ও বিল -->
            <div v-if="hasDropdownPermission('bill')">
                <div class="flex items-center justify-between px-5 py-2 rounded cursor-pointer select-none transition-all font-medium"
                     :class="[dropdownOpen.bill ? sidebarSelected + ' border-l-4 border-emerald-400' : sidebarHover + ' border-l-4 border-transparent']"
                     @click="toggleDropdown('bill')">
                    <div class="flex items-center gap-3">
                        <i class="pi pi-money-bill text-lg" />
                        <span>ভাতা ও বিল</span>
                    </div>
                    <i class="pi pi-angle-down transition-transform duration-200" :class="{'rotate-180': dropdownOpen.bill}"/>
                </div>
                <transition name="sidebar-dropdown">
                    <div v-if="dropdownOpen.bill" :class="sidebarDropdownBg + ' rounded-md ml-3 my-1 py-1'">
                        <Link v-if="hasPermission('bill.negran') || isSuperAdmin()" :href="route('Bill_setup_admin.negran_bill_setup')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all"
                              :class="selectedItem === 'negran_bill' ? sidebarSelected : sidebarHover"
                              @click="setSelected('negran_bill', 'bill')">
                            <i class="pi pi-user text-base"/>
                            <span>নেগরান ভাতা</span>
                        </Link>
                        <Link v-if="hasPermission('bill.mumtahin') || isSuperAdmin()" :href="route('Bill_setup_admin.mumtahin_bill')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all"
                              :class="selectedItem === 'mumtahin_bill' ? sidebarSelected : sidebarHover"
                              @click="setSelected('mumtahin_bill', 'bill')">
                            <i class="pi pi-users text-base"/>
                            <span>মুমতাহিন ভাতা</span>
                        </Link>
                        <Link v-if="hasPermission('bill.markaz_admin') || isSuperAdmin()" :href="route('Bill_setup_admin.markaz_admin_bill')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all"
                              :class="selectedItem === 'markaz_admin_bill' ? sidebarSelected : sidebarHover"
                              @click="setSelected('markaz_admin_bill', 'bill')">
                            <i class="pi pi-users text-base"/>
                            <span>মারকায এডমিন ভাতা</span>
                        </Link>
                        <Link v-if="hasPermission('bill.zonal') || isSuperAdmin()" href="route('Bill_setup_admin.zonal_bill')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all"
                              :class="selectedItem === 'zonal_bill' ? sidebarSelected : sidebarHover"
                              @click="setSelected('zonal_bill', 'bill')">
                            <i class="pi pi-users text-base"/>
                            <span>জোনাল ভাতা</span>
                        </Link>
                    </div>
                </transition>
            </div>

            <!-- মাদরাসা সংক্রান্ত -->
            <div v-if="hasDropdownPermission('madrasa')">
                <div class="flex items-center justify-between px-5 py-2 rounded cursor-pointer select-none transition-all font-medium"
                     :class="[dropdownOpen.madrasa ? sidebarSelected + ' border-l-4 border-emerald-400' : sidebarHover + ' border-l-4 border-transparent']"
                     @click="toggleDropdown('madrasa')">
                    <div class="flex items-center gap-3">
                        <i class="pi pi-building text-lg" />
                        <span>মাদরাসা সংক্রান্ত</span>
                    </div>
                    <i class="pi pi-angle-down transition-transform duration-200" :class="{'rotate-180': dropdownOpen.madrasa}"/>
                </div>
                <transition name="sidebar-dropdown">
                    <div v-if="dropdownOpen.madrasa" :class="sidebarDropdownBg + ' rounded-md ml-3 my-1 py-1'">
                        <Link v-if="hasPermission('madrasa.list') || isSuperAdmin()" :href="route('madrasha_data_for_admin.madrasha_list')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'madrasa_list' ? sidebarSelected : sidebarHover" @click="setSelected('madrasa_list', 'madrasa')">
                            <i class="pi pi-list text-base"/>
                            <span>মাদরাসা তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('madrasa.misc') || isSuperAdmin()" :href="route('students_registration.stuedent_reg_list')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'madrasa_misc' ? sidebarSelected : sidebarHover" @click="setSelected('madrasa_misc', 'madrasa')">
                            <i class="pi pi-inbox text-base"/>
                            <span>বিবিধ</span>
                        </Link>
                        <Link v-if="hasPermission('madrasa.admin') || isSuperAdmin()" :href="route('students_registration.student_reg_card')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'madrasa_admin' ? sidebarSelected : sidebarHover" @click="setSelected('madrasa_admin', 'madrasa')">
                            <i class="pi pi-id-card text-base"/>
                            <span>মাদরাসা এডমিন</span>
                        </Link>
                        <Link v-if="hasPermission('madrasa.draft_soft_delete') || isSuperAdmin()" :href="route('students_registration.stue_reg_draft_soft_delete')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'draft_soft_delete' ? sidebarSelected : sidebarHover" @click="setSelected('draft_soft_delete', 'madrasa')">
                            <i class="pi pi-trash text-base"/>
                            <span>ড্রাফ্ট/সফ্ট ডিলিট</span>
                        </Link>
                        <Link v-if="hasPermission('madrasa.payment') || isSuperAdmin()" :href="route('students_registration.stu_reg_payment')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'madrasa_payment' ? sidebarSelected : sidebarHover" @click="setSelected('madrasa_payment', 'madrasa')">
                            <i class="pi pi-credit-card text-base"/>
                            <span>পেমেন্ট</span>
                        </Link>
                    </div>
                </transition>
            </div>

            <!-- মারকায সংক্রান্ত -->
            <div v-if="hasDropdownPermission('markaz')">
                <div class="flex items-center justify-between px-5 py-2 rounded cursor-pointer select-none transition-all font-medium"
                     :class="[dropdownOpen.markaz ? sidebarSelected + ' border-l-4 border-emerald-400' : sidebarHover + ' border-l-4 border-transparent']"
                     @click="toggleDropdown('markaz')">
                    <div class="flex items-center gap-3">
                        <i class="pi pi-building text-lg" />
                        <span>মারকায সংক্রান্ত</span>
                    </div>
                    <i class="pi pi-angle-down transition-transform duration-200" :class="{'rotate-180': dropdownOpen.markaz}"/>
                </div>
                <transition name="sidebar-dropdown">
                    <div v-if="dropdownOpen.markaz" :class="sidebarDropdownBg + ' rounded-md ml-3 my-1 py-1'">
                        <Link v-if="hasPermission('markaz.application_list') || isSuperAdmin()" :href="route('markaz_for_admin.markaz_setup')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'markaz_application_list' ? sidebarSelected : sidebarHover" @click="setSelected('markaz_application_list', 'markaz')">
                            <i class="pi pi-list text-base"/>
                            <span>মারকায আবেদন তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('markaz.proposed') || isSuperAdmin()" :href="route('OntorVukti.softDelete_draft_list')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'proposed_markaz' ? sidebarSelected : sidebarHover" @click="setSelected('proposed_markaz', 'markaz')">
                            <i class="pi pi-angle-double-right text-base"/>
                            <span>প্রস্তাবিত মারকায</span>
                        </Link>
                        <Link v-if="hasPermission('markaz.list') || isSuperAdmin()" :href="route('markaz_for_admin.all_markaz_list')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'markaz_list' ? sidebarSelected : sidebarHover" @click="setSelected('markaz_list', 'markaz')">
                            <i class="pi pi-list text-base"/>
                            <span>মারকায তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('markaz.change_application') || isSuperAdmin()" :href="route('markaz_for_admin.markaz_change_apply_list')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'markaz_change_application' ? sidebarSelected : sidebarHover" @click="setSelected('markaz_change_application', 'markaz')">
                            <i class="pi pi-refresh text-base"/>
                            <span>মারকায পরিবর্তনের আবেদন</span>
                        </Link>
                        <Link v-if="hasPermission('markaz.admin') || isSuperAdmin()" :href="route('OntorVukti.stu_payment')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'markaz_admin' ? sidebarSelected : sidebarHover" @click="setSelected('markaz_admin', 'markaz')">
                            <i class="pi pi-users text-base"/>
                            <span>মারকায এডমিন</span>
                        </Link>
                        <Link v-if="hasPermission('markaz.admin_training') || isSuperAdmin()" :href="route('OntorVukti.stu_payment')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'markaz_admin_training' ? sidebarSelected : sidebarHover" @click="setSelected('markaz_admin_training', 'markaz')">
                            <i class="pi pi-users text-base"/>
                            <span>মারকায এডমিন ট্রেনিং</span>
                        </Link>
                        <Link v-if="hasPermission('markaz.group') || isSuperAdmin()" :href="route('OntorVukti.stu_payment')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'markaz_group' ? sidebarSelected : sidebarHover" @click="setSelected('markaz_group', 'markaz')">
                            <i class="pi pi-users text-base"/>
                            <span>মারকায গ্রুপ</span>
                        </Link>
                    </div>
                </transition>
            </div>

            <!-- পরীক্ষার রুটিন -->
            <div v-if="hasDropdownPermission('examRoutine')">
                <div class="flex items-center justify-between px-5 py-2 rounded cursor-pointer select-none transition-all font-medium"
                     :class="[dropdownOpen.examRoutine ? sidebarSelected + ' border-l-4 border-emerald-400' : sidebarHover + ' border-l-4 border-transparent']"
                     @click="toggleDropdown('examRoutine')">
                    <div class="flex items-center gap-3">
                        <i class="pi pi-calendar text-lg" />
                        <span>পরীক্ষার রুটিন</span>
                    </div>
                    <i class="pi pi-angle-down transition-transform duration-200" :class="{'rotate-180': dropdownOpen.examRoutine}"/>
                </div>
                <transition name="sidebar-dropdown">
                    <div v-if="dropdownOpen.examRoutine" :class="sidebarDropdownBg + ' rounded-md ml-3 my-1 py-1'">
                        <Link v-if="hasPermission('exam_routine.group') || isSuperAdmin()" :href="route('Negran_Mumtahin.negran_mumtahin')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'exam_routine_group' ? sidebarSelected : sidebarHover" @click="setSelected('exam_routine_group', 'examRoutine')">
                            <i class="pi pi-users text-base"/>
                            <span>পরীক্ষার রুটিন গ্রুপ</span>
                        </Link>
                        <Link v-if="hasPermission('exam_routine.routine') || isSuperAdmin()" :href="route('Negran_Mumtahin.mumtahin_list')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'exam_routine' ? sidebarSelected : sidebarHover" @click="setSelected('exam_routine', 'examRoutine')">
                            <i class="pi pi-calendar text-base"/>
                            <span>পরীক্ষার রুটিন</span>
                        </Link>
                        <Link v-if="hasPermission('exam_routine.oral_exam_mumtahin') || isSuperAdmin()" :href="route('Negran_Mumtahin.negran_apoint_list')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'oral_exam_mumtahin' ? sidebarSelected : sidebarHover" @click="setSelected('oral_exam_mumtahin', 'examRoutine')">
                            <i class="pi pi-user-edit text-base"/>
                            <span>মৌখিক পরীক্ষার মুমতাহিন</span>
                        </Link>
                    </div>
                </transition>
            </div>

            <!-- নিবন্ধন সংক্রান্ত -->
            <div v-if="hasDropdownPermission('registration')">
                <div class="flex items-center justify-between px-5 py-2 rounded cursor-pointer select-none transition-all font-medium"
                     :class="[dropdownOpen.registration ? sidebarSelected + ' border-l-4 border-emerald-400' : sidebarHover + ' border-l-4 border-transparent']"
                     @click="toggleDropdown('registration')">
                    <div class="flex items-center gap-3">
                        <i class="pi pi-user-plus text-lg" />
                        <span>নিবন্ধন সংক্রান্ত</span>
                    </div>
                    <i class="pi pi-angle-down transition-transform duration-200" :class="{'rotate-180': dropdownOpen.registration}"/>
                </div>
                <transition name="sidebar-dropdown">
                    <div v-if="dropdownOpen.registration" :class="sidebarDropdownBg + ' rounded-md ml-3 my-1 py-1'">
                        <Link v-if="hasPermission('registration.list') || isSuperAdmin()" href="route('About_markaj.madrasha_list')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'madrasa_list_reg' ? sidebarSelected : sidebarHover" @click="setSelected('madrasa_list_reg', 'registration')">
                            <i class="pi pi-list text-base"/>
                            <span>মাদরাসার তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('registration.list') || isSuperAdmin()" :href="route('nibondon_for_admin.nibondon_Markaz_list')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'registration_list' ? sidebarSelected : sidebarHover" @click="setSelected('registration_list', 'registration')">
                            <i class="pi pi-list text-base"/>
                            <span>নিবন্ধন তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('registration.list') || isSuperAdmin()" :href="route('nibondon_for_admin.abandon_stu_list')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'cancelled_registration_list' ? sidebarSelected : sidebarHover" @click="setSelected('cancelled_registration_list', 'registration')">
                            <i class="pi pi-ban text-base"/>
                            <span>বাতিলকৃত নিবন্ধন তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('registration.payment') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'payment_list' ? sidebarSelected : sidebarHover" @click="setSelected('payment_list', 'registration')">
                            <i class="pi pi-credit-card text-base"/>
                            <span>পেমেন্ট তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('registration.list') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'board_return_list' ? sidebarSelected : sidebarHover" @click="setSelected('board_return_list', 'registration')">
                            <i class="pi pi-refresh text-base"/>
                            <span>বোর্ড ফেরত তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('registration.card_create') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'registration_card_create' ? sidebarSelected : sidebarHover" @click="setSelected('registration_card_create', 'registration')">
                            <i class="pi pi-id-card text-base"/>
                            <span>নিবন্ধন পত্র তৈরি</span>
                        </Link>
                    </div>
                </transition>
            </div>

            <!-- অন্তর্ভুক্তি সংক্রান্ত -->
            <div v-if="hasDropdownPermission('inclusion')">
                <div class="flex items-center justify-between px-5 py-2 rounded cursor-pointer select-none transition-all font-medium"
                     :class="[dropdownOpen.inclusion ? sidebarSelected + ' border-l-4 border-emerald-400' : sidebarHover + ' border-l-4 border-transparent']"
                     @click="toggleDropdown('inclusion')">
                    <div class="flex items-center gap-3">
                        <i class="pi pi-user-edit text-lg" />
                        <span>অন্তর্ভুক্তি সংক্রান্ত</span>
                    </div>
                    <i class="pi pi-angle-down transition-transform duration-200" :class="{'rotate-180': dropdownOpen.inclusion}"/>
                </div>
                <transition name="sidebar-dropdown">
                    <div v-if="dropdownOpen.inclusion" :class="sidebarDropdownBg + ' rounded-md ml-3 my-1 py-1'">
                        <Link v-if="hasPermission('inclusion.list') || isSuperAdmin()" href="route('Fajilat.sanawaia')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'inclusion_list' ? sidebarSelected : sidebarHover" @click="setSelected('inclusion_list', 'inclusion')">
                            <i class="pi pi-list text-base"/>
                            <span>অন্তর্ভুক্তি তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('inclusion.roll_generate') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'roll_generate' ? sidebarSelected : sidebarHover" @click="setSelected('roll_generate', 'inclusion')">
                            <i class="pi pi-refresh text-base"/>
                            <span>রোল জেনারেট</span>
                        </Link>
                        <Link v-if="hasPermission('inclusion.admit_card_create') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'admit_card_create' ? sidebarSelected : sidebarHover" @click="setSelected('admit_card_create', 'inclusion')">
                            <i class="pi pi-id-card text-base"/>
                            <span>প্রবেশপত্র তৈরি</span>
                        </Link>
                        <Link v-if="hasPermission('inclusion.payment') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'inclusion_payment' ? sidebarSelected : sidebarHover" @click="setSelected('inclusion_payment', 'inclusion')">
                            <i class="pi pi-credit-card text-base"/>
                            <span>পেমেন্ট</span>
                        </Link>
                        <Link v-if="hasPermission('inclusion.cancelled_list') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'cancelled_inclusion_list' ? sidebarSelected : sidebarHover" @click="setSelected('cancelled_inclusion_list', 'inclusion')">
                            <i class="pi pi-ban text-base"/>
                            <span>বাতিলকৃত নিবন্ধন তালিকা</span>
                        </Link>
                    </div>
                </transition>
            </div>

            <!-- খাতা ও লুজ -->
            <div v-if="hasDropdownPermission('khataLoose')">
                <div class="flex items-center justify-between px-5 py-2 rounded cursor-pointer select-none transition-all font-medium"
                     :class="[dropdownOpen.khataLoose ? sidebarSelected + ' border-l-4 border-emerald-400' : sidebarHover + ' border-l-4 border-transparent']"
                     @click="toggleDropdown('khataLoose')">
                    <div class="flex items-center gap-3">
                        <i class="pi pi-file text-lg" />
                        <span>খাতা ও লুজ</span>
                    </div>
                    <i class="pi pi-angle-down transition-transform duration-200" :class="{'rotate-180': dropdownOpen.khataLoose}"/>
                </div>
                <transition name="sidebar-dropdown">
                    <div v-if="dropdownOpen.khataLoose" :class="sidebarDropdownBg + ' rounded-md ml-3 my-1 py-1'">
                        <Link v-if="hasPermission('khata_loose.setup') || isSuperAdmin()" href="route('fazilat.sanawaia')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'khata_loose_setup' ? sidebarSelected : sidebarHover" @click="setSelected('khata_loose_setup', 'khataLoose')">
                            <i class="pi pi-file-edit text-base"/>
                            <span>খাতা ও লুজ সেটাপ</span>
                        </Link>
                        <Link v-if="hasPermission('khata_loose.generate') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'khata_loose_generate' ? sidebarSelected : sidebarHover" @click="setSelected('khata_loose_generate', 'khataLoose')">
                            <i class="pi pi-refresh text-base"/>
                            <span>খাতা ও লুজ জেনারেট</span>
                        </Link>
                        <Link v-if="hasPermission('khata_loose.account') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'khata_loose_account' ? sidebarSelected : sidebarHover" @click="setSelected('khata_loose_account', 'khataLoose')">
                            <i class="pi pi-calculator text-base"/>
                            <span>খাত ও লুজের হিসাব</span>
                        </Link>
                        <Link v-if="hasPermission('khata_loose.others') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'khata_loose_others' ? sidebarSelected : sidebarHover" @click="setSelected('khata_loose_others', 'khataLoose')">
                            <i class="pi pi-ellipsis-h text-base"/>
                            <span>অন্যান্য</span>
                        </Link>
                    </div>
                </transition>
            </div>

            <!-- নেগরান সংক্রান্ত -->
            <div v-if="hasDropdownPermission('negran')">
                <div class="flex items-center justify-between px-5 py-2 rounded cursor-pointer select-none transition-all font-medium"
                     :class="[dropdownOpen.negran ? sidebarSelected + ' border-l-4 border-emerald-400' : sidebarHover + ' border-l-4 border-transparent']"
                     @click="toggleDropdown('negran')">
                    <div class="flex items-center gap-3">
                        <i class="pi pi-users text-lg" />
                        <span>নেগরান সংক্রান্ত</span>
                    </div>
                    <i class="pi pi-angle-down transition-transform duration-200" :class="{'rotate-180': dropdownOpen.negran}"/>
                </div>
                <transition name="sidebar-dropdown">
                    <div v-if="dropdownOpen.negran" :class="sidebarDropdownBg + ' rounded-md ml-3 my-1 py-1'">
                        <Link v-if="hasPermission('negran.application_list') || isSuperAdmin()" href="route('fazilat.sanawaia')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'negran_application_list' ? sidebarSelected : sidebarHover" @click="setSelected('negran_application_list', 'negran')">
                            <i class="pi pi-list text-base"/>
                            <span>আবেদন তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('negran.proposed_list') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'negran_proposed_list' ? sidebarSelected : sidebarHover" @click="setSelected('negran_proposed_list', 'negran')">
                            <i class="pi pi-angle-double-right text-base"/>
                            <span>প্রস্তাবিত তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('negran.pending_list') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'negran_pending_list' ? sidebarSelected : sidebarHover" @click="setSelected('negran_pending_list', 'negran')">
                            <i class="pi pi-clock text-base"/>
                            <span>অপেক্ষমান তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('negran.cancelled_list') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'negran_cancelled_list' ? sidebarSelected : sidebarHover" @click="setSelected('negran_cancelled_list', 'negran')">
                            <i class="pi pi-ban text-base"/>
                            <span>বাতিল তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('negran.mumtahin_list') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'mumtahin_list_negran' ? sidebarSelected : sidebarHover" @click="setSelected('mumtahin_list_negran', 'negran')">
                            <i class="pi pi-users text-base"/>
                            <span>মুমতাহিন তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('negran.report') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'negran_report' ? sidebarSelected : sidebarHover" @click="setSelected('negran_report', 'negran')">
                            <i class="pi pi-chart-line text-base"/>
                            <span>নেগরান রিপোর্ট</span>
                        </Link>
                        <Link v-if="hasPermission('negran.allowance') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'negran_allowance' ? sidebarSelected : sidebarHover" @click="setSelected('negran_allowance', 'negran')">
                            <i class="pi pi-money-bill text-base"/>
                            <span>নেগরান ভাতা</span>
                        </Link>
                    </div>
                </transition>
            </div>

            <!-- মুমতাহিন সংক্রান্ত -->
            <div v-if="hasDropdownPermission('mumtahin')">
                <div class="flex items-center justify-between px-5 py-2 rounded cursor-pointer select-none transition-all font-medium"
                     :class="[dropdownOpen.mumtahin ? sidebarSelected + ' border-l-4 border-emerald-400' : sidebarHover + ' border-l-4 border-transparent']"
                     @click="toggleDropdown('mumtahin')">
                    <div class="flex items-center gap-3">
                        <i class="pi pi-user-edit text-lg" />
                        <span>মুমতাহিন সংক্রান্ত</span>
                    </div>
                    <i class="pi pi-angle-down transition-transform duration-200" :class="{'rotate-180': dropdownOpen.mumtahin}"/>
                </div>
                <transition name="sidebar-dropdown">
                    <div v-if="dropdownOpen.mumtahin" :class="sidebarDropdownBg + ' rounded-md ml-3 my-1 py-1'">
                        <Link v-if="hasPermission('mumtahin.application_list') || isSuperAdmin()" href="route('fazilat.sanawaia')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'mumtahin_application_list' ? sidebarSelected : sidebarHover" @click="setSelected('mumtahin_application_list', 'mumtahin')">
                            <i class="pi pi-list text-base"/>
                            <span>আবেদন তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('mumtahin.proposed_list') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'mumtahin_proposed_list' ? sidebarSelected : sidebarHover" @click="setSelected('mumtahin_proposed_list', 'mumtahin')">
                            <i class="pi pi-angle-double-right text-base"/>
                            <span>প্রস্তাবিত তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('mumtahin.inspector_setup') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'inspector_setup' ? sidebarSelected : sidebarHover" @click="setSelected('inspector_setup', 'mumtahin')">
                            <i class="pi pi-cog text-base"/>
                            <span>পরিদর্শক সেটাপ</span>
                        </Link>
                        <Link v-if="hasPermission('mumtahin.list') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'mumtahin_list_mumtahin' ? sidebarSelected : sidebarHover" @click="setSelected('mumtahin_list_mumtahin', 'mumtahin')">
                            <i class="pi pi-users text-base"/>
                            <span>মুমতাহিন তালিকা</span>
                        </Link>
                        <Link v-if="hasPermission('mumtahin.darsiyat') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'darsiyat_mumtahin' ? sidebarSelected : sidebarHover" @click="setSelected('darsiyat_mumtahin', 'mumtahin')">
                            <i class="pi pi-book text-base"/>
                            <span>দারসিয়াত মুমতাহিন</span>
                        </Link>
                        <Link v-if="hasPermission('mumtahin.hifz_selection') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'hifz_mumtahin_selection' ? sidebarSelected : sidebarHover" @click="setSelected('hifz_mumtahin_selection', 'mumtahin')">
                            <i class="pi pi-star text-base"/>
                            <span>হিফজ মুমতাহিন নির্বাচন</span>
                        </Link>
                        <Link v-if="hasPermission('mumtahin.qirat_selection') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'qirat_mumtahin_selection' ? sidebarSelected : sidebarHover" @click="setSelected('qirat_mumtahin_selection', 'mumtahin')">
                            <i class="pi pi-star text-base"/>
                            <span>কিরাত মুমতাহিন নির্বাচন</span>
                        </Link>
                        <Link v-if="hasPermission('mumtahin.training') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'mumtahin_training' ? sidebarSelected : sidebarHover" @click="setSelected('mumtahin_training', 'mumtahin')">
                            <i class="pi pi-book text-base"/>
                            <span>মুমতাহিন ট্রেনিং</span>
                        </Link>
                    </div>
                </transition>
            </div>

            <!-- জোন সংক্রান্ত -->
            <div v-if="hasDropdownPermission('zone')">
                <div class="flex items-center justify-between px-5 py-2 rounded cursor-pointer select-none transition-all font-medium"
                     :class="[dropdownOpen.zone ? sidebarSelected + ' border-l-4 border-emerald-400' : sidebarHover + ' border-l-4 border-transparent']"
                     @click="toggleDropdown('zone')">
                    <div class="flex items-center gap-3">
                        <i class="pi pi-map-marker text-lg" />
                        <span>জোন সংক্রান্ত</span>
                    </div>
                    <i class="pi pi-angle-down transition-transform duration-200" :class="{'rotate-180': dropdownOpen.zone}"/>
                </div>
                <transition name="sidebar-dropdown">
                    <div v-if="dropdownOpen.zone" :class="sidebarDropdownBg + ' rounded-md ml-3 my-1 py-1'">
                        <Link v-if="hasPermission('zone.setup') || isSuperAdmin()" href="route('fazilat.sanawaia')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'zone_setup' ? sidebarSelected : sidebarHover" @click="setSelected('zone_setup', 'zone')">
                            <i class="pi pi-map-marker text-base"/>
                            <span>জোন সেটাপ</span>
                        </Link>
                        <Link v-if="hasPermission('zone.selection') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'zonal_selection' ? sidebarSelected : sidebarHover" @click="setSelected('zonal_selection', 'zone')">
                            <i class="pi pi-users text-base"/>
                            <span>জোনাল নির্বাচন</span>
                        </Link>
                        <Link v-if="hasPermission('zone.sub_zone_setup') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'sub_zone_setup' ? sidebarSelected : sidebarHover" @click="setSelected('sub_zone_setup', 'zone')">
                            <i class="pi pi-map-marker text-base"/>
                            <span>সাব-জোন সেটাপ</span>
                        </Link>
                        <Link v-if="hasPermission('zone.markaz_setup') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'zone_wise_markaz_setup' ? sidebarSelected : sidebarHover" @click="setSelected('zone_wise_markaz_setup', 'zone')">
                            <i class="pi pi-list text-base"/>
                            <span>জোনওয়ারী মারকায সেটাপ</span>
                        </Link>
                    </div>
                </transition>
            </div>

            <!-- হাজিরা সংক্রান্ত -->
            <div v-if="hasDropdownPermission('attendance')">
                <div class="flex items-center justify-between px-5 py-2 rounded cursor-pointer select-none transition-all font-medium"
                     :class="[dropdownOpen.attendance ? sidebarSelected + ' border-l-4 border-emerald-400' : sidebarHover + ' border-l-4 border-transparent']"
                     @click="toggleDropdown('attendance')">
                    <div class="flex items-center gap-3">
                        <i class="pi pi-check-square text-lg" />
                        <span>হাজিরা সংক্রান্ত</span>
                    </div>
                    <i class="pi pi-angle-down transition-transform duration-200" :class="{'rotate-180': dropdownOpen.attendance}"/>
                </div>
                <transition name="sidebar-dropdown">
                    <div v-if="dropdownOpen.attendance" :class="sidebarDropdownBg + ' rounded-md ml-3 my-1 py-1'">
                        <Link v-if="hasPermission('attendance.examinee') || isSuperAdmin()" href="route('fazilat.sanawaia')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'examinee_attendance' ? sidebarSelected : sidebarHover" @click="setSelected('examinee_attendance', 'attendance')">
                            <i class="pi pi-user text-base"/>
                            <span>পরীক্ষার্থী হাজিরা</span>
                        </Link>
                        <Link v-if="hasPermission('attendance.negran') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'negran_attendance' ? sidebarSelected : sidebarHover" @click="setSelected('negran_attendance', 'attendance')">
                            <i class="pi pi-users text-base"/>
                            <span>নেগরান হাজিরা</span>
                        </Link>
                    </div>
                </transition>
            </div>

            <!-- ফলাফল সংক্রান্ত -->
            <div v-if="hasDropdownPermission('result')">
                <div class="flex items-center justify-between px-5 py-2 rounded cursor-pointer select-none transition-all font-medium"
                     :class="[dropdownOpen.result ? sidebarSelected + ' border-l-4 border-emerald-400' : sidebarHover + ' border-l-4 border-transparent']"
                     @click="toggleDropdown('result')">
                    <div class="flex items-center gap-3">
                        <i class="pi pi-chart-bar text-lg" />
                        <span>ফলাফল সংক্রান্ত</span>
                    </div>
                    <i class="pi pi-angle-down transition-transform duration-200" :class="{'rotate-180': dropdownOpen.result}"/>
                </div>
                <transition name="sidebar-dropdown">
                    <div v-if="dropdownOpen.result" :class="sidebarDropdownBg + ' rounded-md ml-3 my-1 py-1'">
                        <Link v-if="hasPermission('result.condition') || isSuperAdmin()" href="route('fazilat.sanawaia')" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'result_condition' ? sidebarSelected : sidebarHover" @click="setSelected('result_condition', 'result')">
                            <i class="pi pi-cog text-base"/>
                            <span>ফলাফল কন্ডিশন</span>
                        </Link>
                        <Link v-if="hasPermission('result.merit_condition') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'merit_condition' ? sidebarSelected : sidebarHover" @click="setSelected('merit_condition', 'result')">
                            <i class="pi pi-star text-base"/>
                            <span>মেধা কন্ডিশন</span>
                        </Link>
                        <Link v-if="hasPermission('result.inspection_formula') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'inspection_formula' ? sidebarSelected : sidebarHover" @click="setSelected('inspection_formula', 'result')">
                            <i class="pi pi-calculator text-base"/>
                            <span>পরিদর্শন ফর্মুলা</span>
                        </Link>
                        <Link v-if="hasPermission('result.darsiyat_result') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'darsiyat_result' ? sidebarSelected : sidebarHover" @click="setSelected('darsiyat_result', 'result')">
                            <i class="pi pi-book text-base"/>
                            <span>দারসিয়াত ফলাফল</span>
                        </Link>
                        <Link v-if="hasPermission('result.oral_result') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'oral_result' ? sidebarSelected : sidebarHover" @click="setSelected('oral_result', 'result')">
                            <i class="pi pi-user text-base"/>
                            <span>মৌখিক ফলাফল</span>
                        </Link>
                        <Link v-if="hasPermission('result.correction') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'result_correction' ? sidebarSelected : sidebarHover" @click="setSelected('result_correction', 'result')">
                            <i class="pi pi-pencil text-base"/>
                            <span>ফলাফল সংশোধন</span>
                        </Link>
                        <Link v-if="hasPermission('result.review') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'result_review' ? sidebarSelected : sidebarHover" @click="setSelected('result_review', 'result')">
                            <i class="pi pi-search text-base"/>
                            <span>ফলাফল পুনঃনিরীক্ষণ</span>
                        </Link>
                        <Link v-if="hasPermission('result.review_correction') || isSuperAdmin()" href="#" class="flex items-center gap-2 px-5 py-2 rounded text-base cursor-pointer transition-all" :class="selectedItem === 'review_result_correction' ? sidebarSelected : sidebarHover" @click="setSelected('review_result_correction', 'result')">
                            <i class="pi pi-pencil text-base"/>
                            <span>পুনঃনিরীক্ষণ ফলাফল সংশোধন</span>
                        </Link>
                    </div>
                </transition>
            </div>

            <!-- Messaging -->
            <Link v-if="isSuperAdmin() || hasPermission('messaging')" :href="route('others.massaging')"
                class="flex items-center gap-3 px-5 py-2 rounded transition-all cursor-pointer text-base font-medium"
                :class="selectedItem === 'messaging'
                    ? sidebarSelected + ' border-l-4 border-emerald-400'
                    : sidebarHover + ' border-l-4 border-transparent'"
                @click="setSelected('messaging', null)"
            >
                <i class="pi pi-envelope text-lg"/>
                <span>মেসেজিং</span>
            </Link>
            <Link v-if="isSuperAdmin() || hasPermission('notice')" :href="route('others.notice')"
                class="flex items-center gap-3 px-5 py-2 rounded transition-all cursor-pointer text-base font-medium"
                :class="selectedItem === 'notice'
                    ? sidebarSelected + ' border-l-4 border-emerald-400'
                    : sidebarHover + ' border-l-4 border-transparent'"
                @click="setSelected('notice', null)"
            >
                <i class="pi pi-bell text-lg"/>
                <span>নোটিস</span>
            </Link>

            <!-- Role Management -->
            <Link v-if="isSuperAdmin() || hasPermission('role.manage')" :href="route('admin.roles.index')"
                class="flex items-center gap-3 px-5 py-2 rounded transition-all cursor-pointer text-base font-medium"
                :class="selectedItem === 'role_management'
                    ? sidebarSelected + ' border-l-4 border-emerald-400'
                    : sidebarHover + ' border-l-4 border-transparent'"
                @click="setSelected('role_management', null)"
            >
                <i class="pi pi-users text-lg"/>
                <span>রোল ম্যানেজমেন্ট</span>
            </Link>
        </nav>
    </aside>
</template>

<style scoped>
.custom-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: #3ba97b #18222e;
  max-height: 100%;
}
.custom-scrollbar::-webkit-scrollbar {
  width: 7px;
}
.custom-scrollbar::-webkit-scrollbar-thumb {
  background: #3ba97b;
  border-radius: 8px;
}
.custom-scrollbar::-webkit-scrollbar-track {
  background: #18222e;
  border-radius: 8px;
}
.sidebar-dropdown-enter-active, .sidebar-dropdown-leave-active {
  transition: all 0.18s cubic-bezier(.4,0,.2,1);
}
.sidebar-dropdown-enter-from,
.sidebar-dropdown-leave-to {
  opacity: 0;
  max-height: 0;
  transform: translateY(-5px);
}
.sidebar-dropdown-enter-to,
.sidebar-dropdown-leave-from {
  opacity: 1;
  max-height: 500px;
  transform: translateY(0);
}
</style>


