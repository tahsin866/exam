<template>
  <AuthenticatedLayout>
    <div class="mx-auto  px-4 py-5 sm:px-6 lg:px-8">
      <div class="rounded-md mt-5 shadow-2xl overflow-hidden bg-gradient-to-br from-slate-50 to-slate-100 border border-gray-200">
        <!-- Stepper Header -->
        <div class="flex flex-col sm:flex-row justify-between items-center px-8 pt-8 pb-4 bg-gradient-to-r from-cyan-800 to-emerald-700">
          <div class="flex items-center gap-4">
            <i class="fa-solid fa-user-graduate text-3xl text-white drop-shadow-xl"></i>
            <h2 class="text-2xl font-bold text-white tracking-wide">ছাত্র তথ্য সম্পাদনা</h2>
          </div>
          <div class="mt-4 sm:mt-0">
            <span class="inline-block bg-white/10 text-white text-xs rounded-full px-3 py-1 font-semibold tracking-wider shadow-sm">Advanced Edit Mode</span>
          </div>
        </div>

        <!-- Progress Bar -->
        <div class="px-8 pt-4">
          <div class="flex items-center gap-2 mb-2">
            <template v-for="(tab, idx) in tabs" :key="tab">
              <div class="flex flex-col items-center">
                <div
                  :class="[
                    'w-8 h-8 flex items-center justify-center rounded-full text-base font-bold transition-all duration-300 shadow',
                    currentTab === idx ? 'bg-emerald-500 text-white scale-110 shadow-lg ring-2 ring-emerald-700' :
                      idx < currentTab ? 'bg-emerald-300 text-white' :
                      'bg-gray-200 text-gray-400'
                  ]"
                >{{ idx + 1 }}</div>
                <span class="mt-1 text-xs font-medium"
                  :class="currentTab === idx ? 'text-emerald-700' : 'text-gray-400'"
                >{{ tab }}</span>
              </div>
              <template v-if="idx < tabs.length - 1">
                <div class="w-6 h-1 rounded bg-gradient-to-r from-emerald-400 to-cyan-400"></div>
              </template>
            </template>
          </div>
          <div class="w-full h-1 bg-gray-200 rounded relative mb-2">
            <div
              class="h-1 bg-gradient-to-r from-emerald-500 to-cyan-500 rounded transition-all duration-300"
              :style="{ width: ((currentTab + 1) / tabs.length * 100) + '%' }"
            ></div>
          </div>
        </div>

        <form @submit.prevent="submitStudentInfo" class="p-8 space-y-8 bg-white rounded-b-2xl">
          <!-- Animated Tab Content -->
          <transition name="fade-slide" mode="out-in">
            <div :key="currentTab">
              <!-- Tab 1: Personal Info -->
              <div v-if="currentTab === 0">
                <OldStudentPersonalInfo v-model="studentInfoForm" />
              </div>
              <!-- Tab 2: Past & Current Exam Info -->
              <div v-if="currentTab === 1">
                <OldStudentExamInfo
                  :pastExamForm="pastExamForm"
                  :currentExamForm="currentExamForm"
                />
              </div>
              <!-- Tab 3: Address Section -->
              <div v-if="currentTab === 2">
                <OldStudentAddressSection
                  :divisions="divisions"
                  :presentFilters="presentFilters"
                  :presentDistricts="presentDistricts"
                  :presentThanas="presentThanas"
                  :permanentFilters="permanentFilters"
                  :districts="districts"
                  :thanas="thanas"
                  @present-division-change="presentHandleDivisionChange"
                  @present-district-change="presentHandleDistrictChange"
                  @permanent-division-change="handleDivisionChange"
                  @permanent-district-change="handleDistrictChange"
                  v-model:studentInfoForm="studentInfoForm"
                />
              </div>
              <!-- Tab 4: Attachments -->
              <div v-if="currentTab === 3">
                <OldStudentAttachments
                  :studentPhoto="studentPhoto"
                  :studentPhotoPreview="studentPhotoPreview"
                  :nidAttachment="nidAttachment"
                  :nidAttachmentPreview="nidAttachmentPreview"
                  @file-upload="handleFileUpload"
                  @remove-file="removeFile"
                />
              </div>
              <!-- Tab 5: Review & Submit -->
              <div v-if="currentTab === 4">
                <div class="text-xl font-semibold text-emerald-700 mb-3 flex items-center gap-2">
                  <i class="fa-solid fa-circle-check text-2xl text-cyan-700"></i> তথ্য যাচাই ও সংরক্ষণ
                </div>
                <div class="text-gray-700 mb-3">
                  অনুগ্রহ করে পূর্বের সকল তথ্য যাচাই করুন। চাইলে আগের ধাপে ফিরে তথ্য পরিবর্তন করতে পারেন।
                </div>
                <div class="rounded-lg border border-dashed border-emerald-200 p-4 bg-emerald-50 text-emerald-800 shadow-inner">
                  <i class="fa-solid fa-info-circle mr-2"></i>
                  সকল তথ্য সঠিক থাকলে <b>সংরক্ষণ করুন</b> বাটনে ক্লিক করুন।
                </div>
              </div>
            </div>
          </transition>

          <!-- Navigation Buttons -->
          <div class="flex justify-between items-center mt-8">
            <button
              type="button"
              class="flex items-center gap-2 px-6 py-2 rounded-md font-semibold text-gray-700 bg-gray-100 hover:bg-gray-200 transition disabled:opacity-60 disabled:cursor-not-allowed shadow"
              @click="prevTab"
              :disabled="currentTab === 0"
            >
              <i class="fa-solid fa-arrow-left"></i> পূর্বের ধাপ
            </button>
            <div class="flex gap-2">
              <button
                v-if="currentTab < tabs.length - 1"
                type="button"
                class="flex items-center gap-2 px-8 py-2 rounded-md font-bold shadow bg-gradient-to-r from-emerald-600 to-cyan-600 text-white hover:from-emerald-700 hover:to-cyan-700 transition"
                @click="nextTab"
              >
                পরবর্তী ধাপ <i class="fa-solid fa-arrow-right"></i>
              </button>
              <button
                v-else
                type="submit"
                class="flex items-center gap-2 px-10 py-2 rounded-md font-bold shadow bg-gradient-to-r from-cyan-700 to-emerald-700 text-white hover:from-cyan-800 hover:to-emerald-800 transition"
              >
                <i class="fa-solid fa-cloud-upload-alt"></i> সংরক্ষণ করুন
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";

import OldStudentPersonalInfo from '@/Pages/students_registration/components/OldStudentPersonalInfo.vue';
import OldStudentExamInfo from '@/Pages/students_registration/components/OldStudentExamInfo.vue';
import OldStudentAddressSection from '@/Pages/students_registration/components/OldStudentAddressSection,.vue';
import OldStudentAttachments from '@/Pages/students_registration/components/OldStudentAttachments.vue';

const props = defineProps({
  roll: String,
  reg_id: String,
  CID: String,
  modelValue: Object
});

const toaster = createToaster({
  position: "top-right",
  duration: 3000
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
  parmanent_division_name: '',
  parmanent_DID: '',
  parmanent_district_name: '',
  parmanent_desId: '',
  parmanent_thana_name: '',
  parmanent_TID: '',
  student_image: '',
});

// File Attachments
const studentPhoto = ref(null);
const studentPhotoPreview = ref(null);
const nidAttachment = ref(null);
const nidAttachmentPreview = ref(null);

// Address Data
const divisions = ref([]);
const presentDistricts = ref([]);
const presentThanas = ref([]);
const districts = ref([]);
const thanas = ref([]);

const presentFilters = ref({ division: '', district: '', Thana: '' });
const permanentFilters = ref({ division: '', district: '', Thana: '' });

const tabs = [
  'ব্যক্তিগত তথ্য',
  'পরীক্ষার তথ্য',
  'ঠিকানা',
  'সংযুক্তি',
  'শেষ ধাপ'
];
const currentTab = ref(0);

const nextTab = () => {
  if (currentTab.value < tabs.length - 1) currentTab.value++;
};
const prevTab = () => {
  if (currentTab.value > 0) currentTab.value--;
};

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

onMounted(async () => {
  try {
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
    if (response.data.currentExam) {
      currentExamForm.Madrasha = response.data.currentExam.Madrasha || '';
      currentExamForm.Markaj = response.data.currentExam.Markaj || '';
      currentExamForm.Class = response.data.currentExam.Class || '';
      currentExamForm.student_type = response.data.currentExam.student_type || '';
      currentExamForm.marhalaId = response.data.currentExam.marhalaId || '';
      currentExamForm.irregular_subjects = response.data.currentExam.irregular_subjects || '';
      studentInfoForm.current_madrasha = response.data.currentExam.Madrasha
      studentInfoForm.current_markaz = response.data.currentExam.Markaj
      studentInfoForm.student_type = response.data.currentExam.student_type
      studentInfoForm.exam_books_name = response.data.currentExam.irregular_subjects
    }

    // Fill address
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
    studentInfoForm.current_class = response.data.marhalaName;
    studentInfoForm.marhala_id = marhalaId;
  } catch (error) {
    console.error("Error fetching marhala info:", error);
  }
});

// File Upload Handlers
const handleFileUpload = (event, type) => {
  const file = event.target.files[0];
  if (!file) return;
  if (type === 'studentPhoto') {
    studentPhoto.value = file;
    createFilePreview(file, 'studentPhoto');
  } else if (type === 'nidAttachment') {
    nidAttachment.value = file;
    createFilePreview(file, 'nidAttachment');
  }
};

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

const removeFile = (type) => {
  if (type === 'studentPhoto') {
    studentPhoto.value = null;
    studentPhotoPreview.value = null;
  } else if (type === 'nidAttachment') {
    nidAttachment.value = null;
    nidAttachmentPreview.value = null;
  }
};

const goBack = () => {
  window.history.back();
};

// Address Loaders
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

const submitStudentInfo = () => {
  updateFormData();
  const marhalaId = props.CID || localStorage.getItem('marhalaId');
  studentInfoForm.marhala_id = marhalaId;

  if (studentPhoto.value) {
    studentInfoForm.student_image = studentPhoto.value;
  }
  if (nidAttachment.value) {
    studentInfoForm.NID_attach = nidAttachment.value;
  }

  studentInfoForm.post(`/api/save-student-info/${marhalaId}`, {
    forceFormData: true,
    onSuccess: (page) => {
      toaster.success(page.props.flash?.success || 'ছাত্রের তথ্য সফলভাবে সংরক্ষণ করা হয়েছে');
    },
    onError: (errors) => {
      toaster.error('ছাত্রের তথ্য সংরক্ষণ করতে সমস্যা হয়েছে');
      console.error('Error saving student information:', errors);
    },
    preserveScroll: true
  });
};
</script>

<style scoped>
.fade-slide-enter-active,
.fade-slide-leave-active {
  transition: opacity 0.3s, transform 0.4s cubic-bezier(.4,2,.3,1);
}
.fade-slide-enter-from,
.fade-slide-leave-to {
  opacity: 0;
  transform: translateY(24px) scale(.97);
}
.fade-slide-enter-to,
.fade-slide-leave-from {
  opacity: 1;
  transform: translateY(0) scale(1);
}
</style>
