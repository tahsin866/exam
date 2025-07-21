<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, watch, onMounted } from 'vue';
import { createToaster } from "@meforma/vue-toaster";
import { useForm, router } from '@inertiajs/vue3';
import InputText from 'primevue/inputtext';
import InputMask from 'primevue/inputmask';
import Calendar from 'primevue/calendar';
import Button from 'primevue/button';
import Dropdown from 'primevue/dropdown';
import FileUpload from 'primevue/fileupload';
import axios from 'axios';

// Board options - kept as is since these are needed
const boardOptions = [
  { name: 'বেফাকুল মাদারিসিল আরাবিয়া বাংলাদেশ', value: 'বেফাকুল মাদারিসিল আরাবিয়া বাংলাদেশ' },
  { name: 'বেফাকুল মাদারিসিল কওমিয়া গওহরডাঙ্গা বাংলাদেশ', value: 'বেফাকুল মাদারিসিল কওমিয়া গওহরডাঙ্গা বাংলাদেশ' },
  { name: 'আযাদ দ্বীনি এদারায়ে তালীম বাংলাদেশ', value: 'আযাদ দ্বীনি এদারায়ে তালীম বাংলাদেশ' },
  { name: 'তানযীমুল মাদারিসিদ দ্বীনিয়া বাংলাদেশ', value: 'তানযীমুল মাদারিসিদ দ্বীনিয়া বাংলাদেশ' },
  { name: 'জাতীয় দ্বীনি মাদরাসা শিক্ষাবোর্ড বাংলাদেশ', value: 'জাতীয় দ্বীনি মাদরাসা শিক্ষাবোর্ড বাংলাদেশ' },
  { name: 'আঞ্জুমানে ইত্তেহাদুল মাদারিস বাংলাদেশ', value: 'আঞ্জুমানে ইত্তেহাদুল মাদারিস বাংলাদেশ' }
];

// Props definition - simplified
const props = defineProps({
  roll: String,
  reg_id: String,
  CID: String,
  modelValue: Object
});

// Toast setup
const toaster = createToaster({
  position: "top-right",
  duration: 3000
});

// Step management
const currentStep = ref(1);
const examName = ref('');
const marhalaName = ref('');
const currentMarhalaId = ref(null);

// Form data
const studentInfoForm = useForm({
  name_bn: '',
  name_en: '',
  name_ar: '',
  father_name_bn: '',
  father_name_en: '',
  father_name_ar: '',
  mother_name_bn: '',
  mother_name_en: '',
  mother_name_ar: '',
  BRN_no: '',
  NID_no: '',
  past_Roll: '',
  past_reg_id: '',
  madrasha_id: '',
  class: '',
  Division: '',
  Date_of_birth: '',
  current_madrasha: '',
  current_markaz: '',
  student_type: '',
  current_class: '',
  exam_books_name: '',
  mobile_no: '',
  board_name: '',
  marhala_id: '',
  // Present address fields
  present_division_name: '',
  presernt_DID: '',
  present_district_name: '',
  present_desId: '',
  present_thana_name: '',
  present_TID: '',
  // Permanent address fields
  parmanent_division_name: '',
  parmanent_DID: '',
  parmanent_district_name: '',
  parmanent_desId: '',
  parmanent_thana_name: '',
  parmanent_TID: '',
  // Attachments
  student_image: '',
  NID_attach: null,
  markaz_id: ''
});

// Address data
const divisions = ref([]);
const presentDistricts = ref([]);
const presentThanas = ref([]);
const districts = ref([]);
const thanas = ref([]);

// Address filters - simplified to directly use in form
const presentFilters = ref({
  division: '',
  district: '',
  Thana: ''
});

const permanentFilters = ref({
  division: '',
  district: '',
  Thana: ''
});

// File handling
const studentPhotoFile = ref(null);
const birthCertificateFile = ref(null);
const marksheetFile = ref(null);
const studentPhotoPreview = ref(null);
const birthCertificatePreview = ref(null);
const marksheetPreview = ref(null);

// Load divisions on component mount
onMounted(async () => {
  await loadDivisions();

  // Get marhala ID from route
  const marhalaId = route().params.marhalaId;
  currentMarhalaId.value = marhalaId;

  try {
    const response = await axios.get(`/api/student-registration/${marhalaId}`);
    examName.value = response.data.examName;
    marhalaName.value = response.data.marhalaName;

    // Set marhala info in form
    studentInfoForm.current_class = response.data.marhalaName;
    studentInfoForm.marhala_id = marhalaId;
  } catch (error) {
    console.error("Error fetching marhala info:", error);
  }
});

// Load divisions
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

  if (!presentFilters.value.division) return;

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

  if (!presentFilters.value.district) return;

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

  if (!permanentFilters.value.division) return;

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

  if (!permanentFilters.value.district) return;

  try {
    const response = await axios.get(`/api/thanas/${permanentFilters.value.district}`);
    thanas.value = response.data;
  } catch (error) {
    console.error('Error loading thanas:', error);
  }
};

// Update form data from filters before submission
const updateFormData = () => {
  // Present address
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

  // Permanent address
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
};

// Handle file upload
const handleFileUpload = (event, fileType) => {
  const file = event.files[0];
  if (!file) return;

  if (fileType === 'studentPhoto') {
    studentPhotoFile.value = file;
    createFilePreview(file, 'studentPhoto');
  } else if (fileType === 'birthCertificate') {
    birthCertificateFile.value = file;
    createFilePreview(file, 'birthCertificate');
  } else if (fileType === 'marksheet') {
    marksheetFile.value = file;
    createFilePreview(file, 'marksheet');
  }
};

// Create preview URL for the file
const createFilePreview = (file, fileType) => {
  const previewUrl = URL.createObjectURL(file);

  if (fileType === 'studentPhoto') {
    studentPhotoPreview.value = previewUrl;
  } else if (fileType === 'birthCertificate') {
    birthCertificatePreview.value = previewUrl;
  } else if (fileType === 'marksheet') {
    marksheetPreview.value = previewUrl;
  }
};

// Remove file
const removeFile = (fileType) => {
  if (fileType === 'studentPhoto') {
    if (studentPhotoPreview.value) {
      URL.revokeObjectURL(studentPhotoPreview.value);
    }
    studentPhotoFile.value = null;
    studentPhotoPreview.value = null;
  } else if (fileType === 'birthCertificate') {
    if (birthCertificatePreview.value) {
      URL.revokeObjectURL(birthCertificatePreview.value);
    }
    birthCertificateFile.value = null;
    birthCertificatePreview.value = null;
  } else if (fileType === 'marksheet') {
    if (marksheetPreview.value) {
      URL.revokeObjectURL(marksheetPreview.value);
    }
    marksheetFile.value = null;
    marksheetPreview.value = null;
  }
};

// Submit form
const submitStudentInfo = () => {
  // Update address data
  updateFormData();

  // Add files to form
  if (studentPhotoFile.value) {
    studentInfoForm.student_image = studentPhotoFile.value;
  }
  if (birthCertificateFile.value) {
    studentInfoForm.NID_attach = birthCertificateFile.value;
  }

  // Get marhala ID
  const marhalaId = currentMarhalaId.value;

  // Submit form
  studentInfoForm.post(`/api/save-student-info_1/${marhalaId}`, {
    forceFormData: true,
    onSuccess: () => {
      toaster.success('ছাত্রের তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', {
        position: "top-left",
        duration: 5000,
        className: "elegant-success-toast"
      });
    },
    onError: (errors) => {
      toaster.error('ছাত্রের তথ্য সংরক্ষণ করতে সমস্যা হয়েছে', {
        position: "top-left",
        duration: 5000,
        className: "elegant-error-toast"
      });
      console.error('Error saving student information:', errors);
    },
    preserveScroll: true
  });
};

// Expose files for form submission
defineExpose({
  getFiles: () => ({
    studentPhoto: studentPhotoFile.value,
    birthCertificate: birthCertificateFile.value,
    marksheet: marksheetFile.value
  })
});

</script>




<template>
    <AuthenticatedLayout>
        <div class="py-12">
            <div class=" sm:px-6 lg:px-8">
                <!-- Step indicator -->
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

                <!-- Step 1: Personal Information -->
                <div v-if="currentStep === 1" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                            ব্যক্তিগত তথ্য - {{ marhalaName }}
                        </h2>
                        <form class="space-y-6">
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div class="field">
                                    <label class="block font-medium text-sm text-gray-700">নাম (বাংলা)</label>
                                    <InputText v-model="studentInfoForm.name_bn" class="w-full mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                                </div>
                                <div class="field">
                                    <label class="block font-medium text-sm text-gray-700">নাম (ইংরেজি)</label>
                                    <InputText v-model="studentInfoForm.name_en" class="w-full mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                                </div>
                                <div class="field">
                                    <label class="block font-medium text-sm text-gray-700">নাম (আরবি)</label>
                                    <InputText v-model="studentInfoForm.name_ar" class="w-full mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div class="field">
                                    <label class="block font-medium text-sm text-gray-700">মাতার নাম (বাংলা)</label>
                                    <InputText v-model="studentInfoForm.mother_name_bn" class="w-full mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                                </div>
                                <div class="field">
                                    <label class="block font-medium text-sm text-gray-700">মাতার নাম (ইংরেজি)</label>
                                    <InputText v-model="studentInfoForm.mother_name_en" class="w-full mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                                </div>
                                <div class="field">
                                    <label class="block font-medium text-sm text-gray-700">মাতার নাম (আরবি)</label>
                                    <InputText v-model="studentInfoForm.mother_name_ar" class="w-full mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div class="field">
                                    <label class="block font-medium text-sm text-gray-700">পিতার নাম (বাংলা)</label>
                                    <InputText v-model="studentInfoForm.father_name_bn" class="w-full mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                                </div>
                                <div class="field">
                                    <label class="block font-medium text-sm text-gray-700">পিতার নাম (ইংরেজি)</label>
                                    <InputText v-model="studentInfoForm.father_name_en" class="w-full mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                                </div>
                                <div class="field">
                                    <label class="block font-medium text-sm text-gray-700">পিতার নাম (আরবি)</label>
                                    <InputText v-model="studentInfoForm.father_name_ar" class="w-full mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                                </div>
                            </div>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                                <div class="field">
                                    <label class="block font-medium text-sm text-gray-700">জন্ম-তারিখ</label>
                                    <Calendar v-model="studentInfoForm.Date_of_birth" dateFormat="dd/mm/yy" class="w-full mt-1" showIcon />
                                </div>
                                <div class="field">
                                    <label class="block font-medium text-sm text-gray-700">জন্ম-নিবন্ধন নম্বর</label>
                                    <InputText v-model="studentInfoForm.BRN_no" class="w-full mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                                </div>
                                <div class="field">
                                    <label class="block font-medium text-sm text-gray-700">জাতীয় পরিচয়পত্র নম্বর</label>
                                    <InputMask v-model="studentInfoForm.NID_no" mask="9999-9999-9999-9999" placeholder="0000-0000-0000-0000" class="w-full mt-1 border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 rounded-md shadow-sm" />
                                </div>
                            </div>
                        </form>
                        <div class="flex justify-end mt-6">
                            <Button label="পরবর্তী ধাপ" icon="pi pi-arrow-right" iconPos="right" @click="currentStep = 2" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" />
                        </div>
                    </div>
                </div>

                <!-- Step 2: Required Information and Address -->
                <div v-if="currentStep === 2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                                প্রয়োজনীয় তথ্য
                            </h2>
                            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                                <div class="field">
                                    <label class="block font-medium text-sm text-gray-700">পরীক্ষার্থীর ধরন</label>
                                    <InputText value="নিয়মিত" disabled class="w-full mt-1 bg-gray-100 border-gray-300 rounded-md shadow-sm" />
                                </div>
                                <div class="field">
                                    <label class="block font-medium text-sm text-gray-700">বোর্ড</label>
                                    <Dropdown v-model="studentInfoForm.board_name" :options="boardOptions" optionLabel="name" optionValue="value" placeholder="বোর্ড নির্বাচন করুন" class="w-full mt-1" />
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                                    বর্তমান ঠিকানা
                                </h2>
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="field">
                                        <label class="block font-medium text-sm text-gray-700">বিভাগ</label>
                                        <Dropdown v-model="presentFilters.division" :options="divisions" optionLabel="Division" optionValue="id" placeholder="সকল" class="w-full mt-1" @change="presentHandleDivisionChange" />
                                    </div>
                                    <div class="field">
                                        <label class="block font-medium text-sm text-gray-700">জেলা</label>
                                        <Dropdown v-model="presentFilters.district" :options="presentDistricts" optionLabel="District" optionValue="DesID" placeholder="সকল" class="w-full mt-1" @change="presentHandleDistrictChange" />
                                    </div>
                                    <div class="field">
                                        <label class="block font-medium text-sm text-gray-700">থানা/উপজেলা</label>
                                        <Dropdown v-model="presentFilters.Thana" :options="presentThanas" optionLabel="Thana" optionValue="Thana_ID" placeholder="সকল" class="w-full mt-1" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6 bg-white border-b border-gray-200">
                                <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                                    স্থায়ী ঠিকানা
                                </h2>
                                <div class="grid grid-cols-1 gap-4">
                                    <div class="field">
                                        <label class="block font-medium text-sm text-gray-700">বিভাগ</label>
                                        <Dropdown v-model="permanentFilters.division" :options="divisions" optionLabel="Division" optionValue="id" placeholder="সকল" class="w-full mt-1" @change="handleDivisionChange" />
                                    </div>
                                    <div class="field">
                                        <label class="block font-medium text-sm text-gray-700">জেলা</label>
                                        <Dropdown v-model="permanentFilters.district" :options="districts" optionLabel="District" optionValue="DesID" placeholder="সকল" class="w-full mt-1" @change="handleDistrictChange" />
                                    </div>
                                    <div class="field">
                                        <label class="block font-medium text-sm text-gray-700">থানা/উপজেলা</label>
                                        <Dropdown v-model="permanentFilters.Thana" :options="thanas" optionLabel="Thana" optionValue="Thana_ID" placeholder="সকল" class="w-full mt-1" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex justify-between mt-6">
                        <Button label="আগের ধাপ" icon="pi pi-arrow-left" @click="currentStep = 1" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" />
            <Button label="পরবর্তী ধাপ" icon="pi pi-arrow-right" iconPos="right" @click="currentStep = 3" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" />
                    </div>
                </div>

                <!-- Step 3: Attachments -->
                <div v-if="currentStep === 3" class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">
                            সংযুক্তি
                        </h2>
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                            <div class="space-y-3">
                                <label class="block font-medium text-sm text-gray-700">ছাত্রের ছবি (পাসপোর্ট সাইজ এবং নীল ব্যাকগ্রাইন্ড হতে হবে)</label>
                                <div class="relative">
                                    <FileUpload mode="basic" :customUpload="true" @uploader="(event) => handleFileUpload(event, 'studentPhoto')" accept="image/*,.pdf" :auto="true" chooseLabel="ফাইল আপলোড করুন" class="w-full" />
                                    <div v-if="studentPhotoFile" class="flex justify-between items-center mt-2 p-2 bg-gray-50 border border-gray-200 rounded">
                                        <span class="text-gray-600">{{ studentPhotoFile.name }}</span>
                                        <div class="flex gap-2">
                                            <Button v-if="studentPhotoPreview" icon="pi pi-eye" class="p-button-text p-button-sm" @click="() => window.open(studentPhotoPreview, '_blank')" />
                                            <Button icon="pi pi-trash" class="p-button-text p-button-sm p-button-danger" @click="removeFile('studentPhoto')" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-3">
                                <label class="block font-medium text-sm text-gray-700">জন্ম নিবন্ধন/এন আইডি সংযুক্তি করুন</label>
                                <div class="relative">
                                    <FileUpload mode="basic" :customUpload="true" @uploader="(event) => handleFileUpload(event, 'birthCertificate')" accept="image/*,.pdf,.doc,.docx" :auto="true" chooseLabel="ফাইল আপলোড করুন" class="w-full" />
                                    <div v-if="birthCertificateFile" class="flex justify-between items-center mt-2 p-2 bg-gray-50 border border-gray-200 rounded">
                                        <span class="text-gray-600">{{ birthCertificateFile.name }}</span>
                                        <div class="flex gap-2">
                                            <Button v-if="birthCertificatePreview" icon="pi pi-eye" class="p-button-text p-button-sm" @click="() => window.open(birthCertificatePreview, '_blank')" />
                                            <Button icon="pi pi-trash" class="p-button-text p-button-sm p-button-danger" @click="removeFile('birthCertificate')" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div v-if="studentInfoForm.board_name && studentInfoForm.board_name !== 'বেফাকুল মাদারিসিল আরাবিয়া বাংলাদেশ'" class="space-y-3">
                                <label class="block font-medium text-sm text-gray-700">মার্কশীট সংযুক্ত করুন</label>
                                <div class="relative">
                                    <FileUpload mode="basic" :customUpload="true" @uploader="(event) => handleFileUpload(event, 'marksheet')" accept="image/*,.pdf,.doc,.docx" :auto="true" chooseLabel="ফাইল আপলোড করুন" class="w-full" />
                                    <div v-if="marksheetFile" class="flex justify-between items-center mt-2 p-2 bg-gray-50 border border-gray-200 rounded">
                                        <span class="text-gray-600">{{ marksheetFile.name }}</span>
                                        <div class="flex gap-2">
                                            <Button v-if="marksheetPreview" icon="pi pi-eye" class="p-button-text p-button-sm" @click="() => window.open(marksheetPreview, '_blank')" />
                                            <Button icon="pi pi-trash" class="p-button-text p-button-sm p-button-danger" @click="removeFile('marksheet')" />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex justify-between mt-6">
                            <Button label="আগের ধাপ" icon="pi pi-arrow-left" @click="currentStep = 2" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400 active:bg-gray-500 focus:outline-none focus:border-gray-500 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" />
                            <Button icon="pi pi-save" label="সংরক্ষণ করুন" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring focus:ring-gray-300 disabled:opacity-25 transition" @click="submitStudentInfo" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style>
/* Toast container styling */
.vue-toastification__container {
    z-index: 9999;
    max-width: 350px !important;
    top: 20px !important;
    left: 20px !important; /* বাম দিকে সেট করা হয়েছে */
    right: auto !important; /* ডান দিক থেকে অটো সেট করা হয়েছে */
}

/* Base toast styling for both success and error */
.elegant-success-toast,
.elegant-error-toast {
    background-color: white !important;
    color: #333 !important;
    border-radius: 6px !important;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1) !important;
    padding: 15px !important;
    font-size: 14px !important;
    font-weight: 500 !important;
    display: flex !important;
    align-items: center !important;
    width: auto !important;
    max-width: 350px !important;
    margin-bottom: 10px !important;
    position: relative !important;
    left: 0 !important; /* বাম দিকে স্থির */
}

/* Success toast specific styling */
.elegant-success-toast {
    border-left: 4px solid #28a745 !important;
}

.elegant-success-toast::before {
    content: "✓";
    display: inline-block;
    margin-right: 10px;
    background-color: #28a745;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    text-align: center;
    line-height: 24px;
    font-weight: bold;
}

/* Error toast specific styling */
.elegant-error-toast {
    border-left: 4px solid #dc3545 !important;
}

.elegant-error-toast::before {
    content: "✕";
    display: inline-block;
    margin-right: 10px;
    background-color: #dc3545;
    color: white;
    width: 24px;
    height: 24px;
    border-radius: 50%;
    text-align: center;
    line-height: 24px;
    font-weight: bold;
}

/* Toast animation - উপর থেকে নিচে ফেড ইন */
.elegant-success-toast,
.elegant-error-toast {
    animation: fadeInDown 0.3s ease-out forwards;
}

@keyframes fadeInDown {
    from {
        transform: translateY(-20px);
        opacity: 0;
    }
    to {
        transform: translateY(0);
        opacity: 1;
    }
}

/* Close button styling */
.vue-toastification__close-button {
    color: #666 !important;
    opacity: 0.7 !important;
}

.vue-toastification__close-button:hover {
    opacity: 1 !important;
}
</style>





