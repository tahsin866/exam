<template>
    <AuthenticatedLayout>
        <div class="container-fluid mx-auto py-8 px-4">
            <div class="bg-white rounded-sm shadow-xl overflow-hidden">
                <!-- Header -->
                <div class="bg-olive-700 px-6 py-4 flex justify-between items-center rounded-t-xl">
                    <h3 class="text-black text-xl font-bold">নেগরান/মুমতাহিন এডিট</h3>
                    <div class="flex items-center"></div>
                </div>
                <!-- Form Body -->
                <div class="p-6">
                    <div v-if="loading" class="text-gray-500 text-center py-8">তথ্য লোড হচ্ছে...</div>
                    <form v-else @submit.prevent="updateTeacher">
                        <!-- Profile Section -->
                        <div class="text-center mt-4">
                            <img :src="form.profile_image || 'img/islamic.png'" alt="User profile picture"
                                class="w-24 h-24 rounded-full mx-auto mb-3 object-cover">
                            <h3 class="text-xl font-semibold">{{ form.name }}</h3>
                            <input type="file" id="profileImageInput" class="hidden"
                                @change="handleProfileImageChange" />
                            <div class="flex justify-center items-center gap-2 mt-3 mb-6">
                                <label for="profileImageInput"
                                    class="px-4 py-2 border border-blue-500 text-blue-500 rounded-md text-sm flex items-center hover:bg-blue-50 transition cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                                    </svg>
                                    প্রোফাইল ছবি নির্বাচন করুন
                                </label>
                            </div>
                        </div>

                        <!-- Application Type -->
                        <div class="mb-8">
                            <div class="md:w-1/3">
                                <div class="space-y-2">
                                    <label for="applicationType" class="block text-emerald-800 font-semibold">আবেদনের ধরন</label>
                                    <Dropdown id="applicationType" v-model="form.application_type"
                                        :options="applicationTypes"
                                        placeholder="আবেদনের ধরন নির্বাচন করুন"
                                        class="w-full" />
                                </div>
                            </div>
                        </div>

                        <!-- Personal Information -->
                        <Card class="mb-8">
                            <template #header>
                                <div class="flex items-center text-lg font-semibold text-emerald-800 mt-3 mx-5">
                                    <i class="pi pi-user mr-2 text-emerald-600"></i>
                                    ব্যক্তিগত তথ্য
                                </div>
                            </template>
                            <template #content>
                                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                                    <div class="field">
                                        <label for="name" class="block text-emerald-800">নাম</label>
                                        <InputText id="name" v-model="form.name"
                                            class="w-full"
                                            placeholder="আপনার নাম লিখুন" />
                                    </div>
                                    <div class="field">
                                        <label for="mobile" class="block text-emerald-800">মোবাইল নম্বর</label>
                                        <InputText id="mobile" v-model="form.mobile"
                                            class="w-full"
                                            placeholder="মোবাইল নম্বর লিখুন" />
                                    </div>
                                    <div class="field">
                                        <label for="birthDate" class="block text-emerald-800">জন্ম-তারিখ</label>
                                        <Calendar id="birthDate" v-model="form.birth_date"
                                            dateFormat="yy-mm-dd"
                                            class="w-full" />
                                    </div>
                                    <div class="field">
                                        <label for="whatsapp" class="block text-emerald-800">হোয়াটসঅ্যাপ নম্বর</label>
                                        <InputText id="whatsapp" v-model="form.whatsapp"
                                            class="w-full"
                                            placeholder="হোয়াটসঅ্যাপ নম্বর" />
                                    </div>
                                    <div class="field">
                                        <label for="birthRegNo" class="block text-emerald-800">জন্ম নিবন্ধন নম্বর</label>
                                        <InputText id="birthRegNo" v-model="form.birth_reg_no"
                                            class="w-full"
                                            placeholder="জন্ম নিবন্ধন নম্বর" />
                                    </div>
                                    <div class="field">
                                        <label for="nid" class="block text-emerald-800">এন.আইডি নম্বর</label>
                                        <InputText id="nid" v-model="form.nid"
                                            class="w-full"
                                            placeholder="এন.আইডি নম্বর" />
                                    </div>
                                    <div class="field">
                                        <label for="gender" class="block text-emerald-800">জেন্ডার</label>
                                        <Dropdown id="gender" v-model="form.gender"
                                            :options="genderOptions"
                                            class="w-full" />
                                    </div>
                                </div>
                            </template>
                        </Card>



 <Card class="mb-8">
        <template #header>
            <div class="flex items-center text-lg font-semibold text-emerald-800 mt-3 mx-5">
                <i class="pi pi-book mr-2 text-emerald-600"></i>
                শিক্ষাগত যোগ্যতা
            </div>
        </template>
        <template #content>
            <div v-for="(qualification, index) in form.educationalQualifications" :key="index"
                class="mb-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">
                    <div class="field">
                        <label :for="`class-${index}`" class="block text-emerald-800">মারহালার
                            নাম/ক্লাসের নাম</label>
                        <Dropdown :id="`class-${index}`"
                            v-model="form.educationalQualifications[index].className"
                            :options="classOptions" placeholder="ক্লাস নির্বাচন করুন"
                            class="w-full" />
                    </div>
                    <div class="field">
                        <label :for="`passYear-${index}`" class="block text-emerald-800">পাসের
                            সন</label>
                        <InputText :id="`passYear-${index}`"
                            v-model="form.educationalQualifications[index].passYear" class="w-full"
                            placeholder="পাসের সন" />
                    </div>
                    <div class="field">
                        <label :for="`result-${index}`" class="block text-emerald-800">ফলাফল
                            (বিভাগ)</label>
                        <InputText :id="`result-${index}`"
                            v-model="form.educationalQualifications[index].result" class="w-full"
                            placeholder="ফলাফল (বিভাগ)" />
                    </div>
                    <div class="field">
                        <label :for="`institution-${index}`" class="block text-emerald-800">সমাপনি
                            বোর্ড/মাদরাসা</label>
                        <div class="flex gap-2">
                            <InputText :id="`institution-${index}`"
                                v-model="form.educationalQualifications[index].institution"
                                class="w-full" placeholder="সমাপনি বোর্ড/মাদরাসা" />
                            <button v-if="form.educationalQualifications.length > 1"
                                @click="removeQualification(index)" type="button"
                                class="p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition flex-shrink-0"
                                title="রো মুছুন">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
   clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="flex justify-between items-center mt-4">
                <div class="field md:w-1/4">
                    <label for="teachingExperience" class="block text-emerald-800">শিক্ষকতার
                        অভিজ্ঞতা</label>
                    <InputText id="teachingExperience" v-model="form.teaching_experience"
                        class="w-full" placeholder="কত বছর" />
                </div>
                <Button type="button" @click="addQualification" label="আরো যোগ করুন"
                    icon="pi pi-plus" severity="success" />
            </div>
        </template>
    </Card>







                        <!-- Additional Information -->
                        <Card class="mb-8">
                            <template #header>
                                <div class="flex items-center text-lg font-semibold text-emerald-800 mt-3 mx-5">
                                    <i class="pi pi-info-circle mr-2 text-emerald-600"></i>
                                    অতিরিক্ত তথ্য
                                </div>
                            </template>
                            <template #content>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="field">
                                        <label for="teachingExperience" class="block text-emerald-800">শিক্ষকতার অভিজ্ঞতা</label>
                                        <InputText id="teachingExperience" v-model="form.teaching_experience"
                                            class="w-full"
                                            placeholder="শিক্ষকতার অভিজ্ঞতা" />
                                    </div>
                                    <div class="field">
                                        <label for="selectedBook" class="block text-emerald-800">নির্বাচিত বই</label>
                                        <InputText id="selectedBook" v-model="form.selected_book"
                                            class="w-full"
                                            placeholder="নির্বাচিত বই" />
                                    </div>
                                </div>
                            </template>
                        </Card>

                        <!-- Payment Information -->
                        <Card class="mb-8">
                            <template #header>
                                <div class="flex items-center text-lg font-semibold text-emerald-800 mt-3 mx-5">
                                    <i class="pi pi-wallet mr-2 text-emerald-600"></i>
                                    পেমেন্ট তথ্য
                                </div>
                            </template>
                            <template #content>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div class="field">
                                        <label for="nagadNumber" class="block text-emerald-800">নগদ নম্বর</label>
                                        <InputText id="nagadNumber" v-model="form.nagad_number"
                                            class="w-full"
                                            placeholder="নগদ নম্বর" />
                                    </div>
                                    <div class="field">
                                        <label for="rocketNumber" class="block text-emerald-800">রকেট নম্বর</label>
                                        <InputText id="rocketNumber" v-model="form.rocket_number"
                                            class="w-full"
                                            placeholder="রকেট নম্বর" />
                                    </div>
                                    <div class="field">
                                        <label for="bkashNumber" class="block text-emerald-800">বিকাশ নম্বর</label>
                                        <InputText id="bkashNumber" v-model="form.bkash_number"
                                            class="w-full"
                                            placeholder="বিকাশ নম্বর" />
                                    </div>
                                </div>
                            </template>
                        </Card>

                        <!-- Comments -->
                        <Card class="mb-8">
                            <template #header>
                                <div class="flex items-center text-lg font-semibold text-emerald-800 mt-3 mx-5">
                                    <i class="pi pi-comments mr-2 text-emerald-600"></i>
                                    মন্তব্য
                                </div>
                            </template>
                            <template #content>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div class="field">
                                        <label for="negaranComments" class="block text-emerald-800">নেগরান মন্তব্য</label>
                                        <Editor id="negaranComments" v-model="form.negaranComments" editorStyle="height: 150px" />
                                    </div>
                                    <div class="field">
                                        <label for="mumtahinComments" class="block text-emerald-800">মুমতাহিন মন্তব্য</label>
                                        <Editor id="mumtahinComments" v-model="form.mumtahinComments" editorStyle="height: 150px" />
                                    </div>
                                </div>
                            </template>
                        </Card>

                        <!-- Submit Button -->
                        <div class="flex justify-center mt-5">
                            <button type="submit"
                                class="px-6 py-3 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition text-lg"
                                :disabled="processing">
                                {{ processing ? 'প্রক্রিয়াকরণ হচ্ছে...' : 'আপডেট করো' }}
                            </button>
                        </div>

                        <p v-if="message" class="text-green-600 mt-3 text-center">{{ message }}</p>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';
import Card from 'primevue/card';
import Editor from 'primevue/editor';
import Button from 'primevue/button';

// const toaster = createToaster({
//     position: "top-right"
// });

// Get ID from query string
const urlParams = new URLSearchParams(window.location.search);
const id = urlParams.get('id');

// Store original values for conversion
const originalGender = ref('');
const originalAppType = ref('');

// Form data
const form = ref({
    name: '',
    mobile: '',
    birth_date: '',
    whatsapp: '',
    birth_reg_no: '',
    nid: '',
    gender: '',
    application_type: '',
    teaching_experience: '',
    selected_book: '',
    nagad_number: '',
    rocket_number: '',
    bkash_number: '',
    muhtamim_file: '',
    nid_file: '',
    birth_cert_file: '',
    negaranComments: '',
    mumtahinComments: '',
    profile_image: '',
    educationalQualifications: [] // শিক্ষাগত যোগ্যতার জন্য অ্যারে যোগ করা হয়েছে
});

const loading = ref(true);
const message = ref('');
const processing = ref(false);

// Application type and gender options
const applicationTypes = [
    'নেগরান',
    'মুমতাহিন',
    'উভয়',
    'হিফজ মুমতাহিন',
    'কেরাত মুমতাহিন'
];

const genderOptions = [
    'পুরুষ',
    'মহিলা',
    'অন্যান্য'
];

// Class options for dropdown
const classOptions = [
    'তাকমিল',
    'ফযীলত',
    'সানাবিয়া উলইয়া',
    'সানাবিয়া',
    'মাস্টার্স',
    'কামিল',
    'H.S.C'
]




// Handle profile image change
const handleProfileImageChange = (event) => {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = (e) => {
            form.value.profile_image = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

// Add new qualification row
const addQualification = () => {
    form.value.educationalQualifications.push({
        className: '',
        passYear: '',
        result: '',
        institution: ''
    });
};

// Remove qualification row
const removeQualification = (index) => {
    form.value.educationalQualifications.splice(index, 1);
};

// Get teacher data from API
const getTeacher = async () => {
    try {
        const response = await axios.get(`/api/negran/edit/${id}`);
        console.log('API Response:', response.data); // Debug log

        // Set basic fields
        form.value.name = response.data.name || '';
        form.value.mobile = response.data.mobile || '';
        form.value.birth_date = response.data.birth_date || '';
        form.value.whatsapp = response.data.whatsapp || '';
        form.value.birth_reg_no = response.data.birth_reg_no || '';
        form.value.nid = response.data.nid || '';
        form.value.teaching_experience = response.data.teaching_experience || '';
        form.value.selected_book = response.data.selected_book || '';
        form.value.nagad_number = response.data.nagad_number || '';
        form.value.rocket_number = response.data.rocket_number || '';
        form.value.bkash_number = response.data.bkash_number || '';
        form.value.muhtamim_file = response.data.muhtamim_file || '';
        form.value.nid_file = response.data.nid_file || '';
        form.value.birth_cert_file = response.data.birth_cert_file || '';
        form.value.negaranComments = response.data.negaranComments || '';
        form.value.mumtahinComments = response.data.mumtahinComments || '';
        form.value.profile_image = response.data.profile_image || '';

        // Save original values
        originalGender.value = response.data.gender;
        originalAppType.value = response.data.application_type;

        // Set gender based on value
        if (response.data.gender === 'পুরুষ' || response.data.gender === 'male' || response.data.gender === 1) {
            form.value.gender = 'পুরুষ';
        } else if (response.data.gender === 'মহিলা' || response.data.gender === 'female' || response.data.gender === 2) {
            form.value.gender = 'মহিলা';
        } else if (response.data.gender === 'অন্যান্য' || response.data.gender === 'other' || response.data.gender === 3) {
            form.value.gender = 'অন্যান্য';
        } else {
            form.value.gender = '';
        }

        // Set application type based on value
        if (response.data.application_type === 'নেগরান' || response.data.application_type === 'negran' || response.data.application_type === 1) {
            form.value.application_type = 'নেগরান';
        } else if (response.data.application_type === 'মুমতাহিন' || response.data.application_type === 'mumtahin' || response.data.application_type === 2) {
            form.value.application_type = 'মুমতাহিন';
        } else if (response.data.application_type === 'উভয়' || response.data.application_type === 'both' || response.data.application_type === 3) {
            form.value.application_type = 'উভয়';
        } else {
            form.value.application_type = '';
        }

        // Set educational qualifications
        if (response.data.educational_qualifications && response.data.educational_qualifications.length > 0) {
            form.value.educationalQualifications = response.data.educational_qualifications.map(qual => ({
                id: qual.id, // সম্পাদনার সময় আইডি সংরক্ষণ করা হচ্ছে
                className: qual.class_name,
                passYear: qual.pass_year,
                result: qual.result,
                institution: qual.institution
            }));
        } else {
            // যদি কোন শিক্ষাগত যোগ্যতা না থাকে, তাহলে একটি খালি রো যোগ করুন
            form.value.educationalQualifications = [{
                className: '',
                passYear: '',
                result: '',
                institution: ''
            }];
        }

        loading.value = false;
    } catch (error) {
        console.error('Failed to load teacher:', error);
        toaster.error('তথ্য লোড করতে সমস্যা হয়েছে');
        loading.value = false;
    }
};

// Update teacher data
// Update teacher data
const updateTeacher = async () => {
    try {
        processing.value = true;

        // Convert values for API if needed
        const formData = { ...form.value };

        // Convert gender if needed
        if (originalGender.value === 'male' || originalGender.value === 1) {
            formData.gender = formData.gender === 'পুরুষ' ? 'male' : formData.gender;
        } else if (originalGender.value === 'female' || originalGender.value === 2) {
            formData.gender = formData.gender === 'মহিলা' ? 'female' : formData.gender;
        } else if (originalGender.value === 'other' || originalGender.value === 3) {
            formData.gender = formData.gender === 'অন্যান্য' ? 'other' : formData.gender;
        }

        // Convert application type if needed
        if (originalAppType.value === 'negran' || originalAppType.value === 1) {
            formData.application_type = formData.application_type === 'নেগরান' ? 'negran' : formData.application_type;
        } else if (originalAppType.value === 'mumtahin' || originalAppType.value === 2) {
            formData.application_type = formData.application_type === 'মুমতাহিন' ? 'mumtahin' : formData.application_type;
        } else if (originalAppType.value === 'both' || originalAppType.value === 3) {
            formData.application_type = formData.application_type === 'উভয়' ? 'both' : formData.application_type;
        }

        // Format educational qualifications for API
        const educationalQualificationsFormatted = formData.educationalQualifications.map(qual => ({
            id: qual.id,
            class_name: qual.className,
            pass_year: qual.passYear,
            result: qual.result,
            institution: qual.institution
        }));

        // Create a new object for API submission
        const apiData = {
            ...formData,
            educational_qualifications: educationalQualificationsFormatted
        };

        // Remove the camelCase version to avoid confusion
        delete apiData.educationalQualifications;

        const response = await axios.put(`/api/negran/update/${id}`, apiData);
        message.value = response.data.message;
        toaster.success('সফলভাবে আপডেট করা হয়েছে');
        processing.value = false;
    } catch (error) {
        console.error('Update failed:', error);
        toaster.error('আপডেট করতে সমস্যা হয়েছে');
        processing.value = false;
    }
};



// Load data on component mount
onMounted(() => {
    getTeacher();

    // যদি শিক্ষাগত যোগ্যতা খালি থাকে, তাহলে একটি খালি রো যোগ করুন
    if (form.value.educationalQualifications.length === 0) {
        addQualification();
    }
});
</script>

