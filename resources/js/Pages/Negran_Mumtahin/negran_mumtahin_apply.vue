<template>
  <AuthenticatedLayout>
    <div class="py-10 min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100">
      <div class=" mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden transition-all duration-300 border border-gray-200">
          <!-- Modern Stepper -->
          <nav class="flex items-center justify-between px-8 py-6 bg-gradient-to-r from-gray-800 via-emerald-900 to-emerald-700 rounded-t-lg border-b border-emerald-900/20">
            <ol class="flex w-full">
              <li
                v-for="(step, index) in steps"
                :key="index"
                class="flex-1 flex items-center"
              >
                <div class="relative flex flex-col items-center group">
                  <div
                    :class="[
                      'w-10 h-10 flex items-center justify-center rounded-full text-lg font-bold border-4 transition',
                      currentStep === index
                        ? 'bg-white border-indigo-300 text-indigo-700 shadow-lg scale-110'
                        : currentStep > index
                        ? 'bg-green-500 border-green-300 text-white'
                        : 'bg-gray-100 border-gray-200 text-gray-400'
                    ]"
                  >
                    {{ index + 1 }}
                  </div>
                  <span
                    class="mt-2 text-xs font-semibold"
                    :class="currentStep === index ? 'text-white' : 'text-indigo-100'"
                  >
                    {{ step }}
                  </span>
                  <div
                    v-if="index < steps.length - 1"
                    class="absolute top-5 left-full w-24 h-1"
                  >
                    <div
                      :class="[
                        'h-1 rounded transition-all duration-300',
                        currentStep > index
                          ? 'bg-green-300'
                          : 'bg-indigo-100'
                      ]"
                    ></div>
                  </div>
                </div>
              </li>
            </ol>
          </nav>

          <div class="p-8 md:p-10">
            <!-- Animated form transition -->
            <transition name="fade" mode="out-in">
              <div :key="currentStep">
                <PersonalInfoStep
                  v-if="currentStep === 0"
                  v-model:form="form"
                  :gender-options="genderOptions"
                  :application-types="applicationTypes"
                  :profile-image="profileImage"
                  @change-profile-image="handleProfileImageChange"
                />

                <EducationBooksStep
                  v-if="currentStep === 1"
                  v-model:form="form"
                  :class-options="classOptions"
                  :book-categories="bookCategories"
                  :selected-books="selectedBooks"
                  :teaching-books="teachingBooks"
                  :show-book-dropdown="showBookDropdown"
                  :show-teaching-book-dropdown="showTeachingBookDropdown"
                  :custom-book-input="customBookInput"
                  @add-qualification="addQualification"
                  @remove-qualification="removeQualification"
                  @toggle-book-dropdown="toggleBookDropdown"
                  @toggle-teaching-book-dropdown="toggleTeachingBookDropdown"
                  @toggle-sub-menu="toggleSubMenu"
                  @toggle-teaching-book-sub-menu="toggleTeachingBookSubMenu"
                  @add-selected-book="addSelectedBook"
                  @add-teaching-book="addTeachingBook"
                  @remove-book="removeBook"
                  @remove-teaching-book="removeTeachingBook"
                  @add-custom-book="addCustomBook"
                  @update:selected-books="val => selectedBooks = val"
                  @update:teaching-books="val => teachingBooks = val"
                  @update:custom-book-input="val => customBookInput = val"
                />

                <AddressPaymentStep
                  v-if="currentStep === 2"
                  v-model:form="form"
                  :divisions="divisions"
                  :present-districts="presentDistricts"
                  :present-thanas="presentThanas"
                  :present-filters="presentFilters"
                  @present-handle-division-change="presentHandleDivisionChange"
                  @present-handle-district-change="presentHandleDistrictChange"
                />

                <AttachmentsCommentsStep
                  v-if="currentStep === 3"
                  v-model:form="form"
                  :files="files"
                  @handle-file-change="handleFileChange"
                  @remove-file="removeFile"
                  @view-file="viewFile"
                />
              </div>
            </transition>

            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-10 gap-2">
              <button
                v-if="currentStep > 0"
                type="button"
                @click="prevStep"
                class="flex items-center gap-2 px-6 py-3 rounded-lg transition bg-gradient-to-r from-gray-400 to-gray-600 text-white font-semibold shadow hover:scale-105 active:scale-100"
              >
                <i class="pi pi-angle-left"></i> পূর্ববর্তী ধাপ
              </button>
              <div class="flex-grow"></div>
              <button
                v-if="currentStep < steps.length - 1"
                type="button"
                @click="nextStep"
                class="flex items-center gap-2 px-6 py-3 rounded-lg transition bg-gradient-to-r from-blue-600 to-indigo-500 text-white font-semibold shadow hover:scale-105 active:scale-100"
              >
                পরবর্তী ধাপ <i class="pi pi-angle-right"></i>
              </button>
              <button
                v-if="currentStep === steps.length - 1"
                type="submit"
                @click="submitForm"
                class="flex items-center gap-2 px-8 py-3 rounded-lg transition bg-gradient-to-r from-green-500 to-emerald-500 text-white font-bold shadow-lg hover:scale-105 active:scale-100"
                :disabled="form.processing"
              >
                <i class="pi pi-save"></i>
                {{ form.processing ? 'প্রক্রিয়াকরণ...' : 'সেভ করুন' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Snackbar/Toaster -->
    <transition name="fade">
      <div
        v-if="showSuccessToast"
        class="fixed top-8 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-xl shadow-xl text-lg z-50 flex items-center gap-2"
      >
        <i class="pi pi-check-circle text-2xl"></i>
        <span>{{ successMessage }}</span>
      </div>
    </transition>
    <transition name="fade">
      <div
        v-if="showErrorToast"
        class="fixed top-8 left-1/2 transform -translate-x-1/2 bg-red-600 text-white px-6 py-3 rounded-xl shadow-xl text-lg z-50 flex items-center gap-2"
      >
        <i class="pi pi-times-circle text-2xl"></i>
        <span>{{ errorMessage }}</span>
      </div>
    </transition>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, watch, onMounted, computed } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, usePage } from '@inertiajs/vue3';
// Steps
import PersonalInfoStep from '@/Pages/Negran_Mumtahin/components/PersonalInfoStep.vue';
import EducationBooksStep from '@/Pages/Negran_Mumtahin/components/EducationBooksStep.vue';
import AddressPaymentStep from '@/Pages/Negran_Mumtahin/components/AddressPaymentStep.vue';
import AttachmentsCommentsStep from '@/Pages/Negran_Mumtahin/components/AttachmentsCommentsStep.vue';

const currentStep = ref(0);
const steps = [
  'ব্যক্তিগত তথ্য',
  'শিক্ষাগত যোগ্যতা',
  'ঠিকানা ও পেমেন্ট',
  'সংযুক্তি ও মন্তব্য'
];

function nextStep() {
  if (currentStep.value < steps.length - 1) currentStep.value++;
}
function prevStep() {
  if (currentStep.value > 0) currentStep.value--;
}

// ====== Form State & Logic =======
const genderOptions = ['পুরুষ', 'মহিলা'];
const applicationTypes = [
  'নেগরান',
  'মুমতাহিন',
  'নেগরান মুমতাহিন উভয়',
  'হিফজ মুমতাহিন',
  'কেরাত মুমতাহিন'
];
const classOptions = [
  'তাকমিল',
  'ফযীলত',
  'সানাবিয়া উলইয়া',
  'সানাবিয়া',
  'মাস্টার্স',
  'কামিল',
  'H.S.C'
];
const profileImage = ref(null);
const form = useForm({
  profileImage: null,
  muhtamim_file: null,
  nid_file: null,
  birth_cert_file: null,
  applicationType: 'নেগরান',
  teachers: {
    name: '',
    Mobile: '',
    DateofBirth: '',
    comments: '',
    whatsapp: '',
    post: '',
    village: '',
    division: '',
    district: '',
    thana: '',
    teaching_experience: '',
    negaranComments: '',
    mumtahinComments: '',
    nagad_number: '',
    rocket_number: '',
    bkash_number: '',
    National_Id_Card: '',
    nid: '',
    gender: 'পুরুষ',
    selected_book: '',
    teaching_books: ''
  },
  educationalQualifications: [
    { className: '', passYear: '', result: '', institution: '' }
  ],
  selectedBooks: []
});
// Book Dropdowns & Selection
const showBookDropdown = ref(false);
const bookCategories = ref([]);
const selectedBooks = ref([]);
const showTeachingBookDropdown = ref(false);
const teachingBooks = ref([]);
const customBookInput = ref('');
const files = reactive({ muhtamim: null, nid: null, birthCert: null });

// Advanced: Save progress to localStorage
const DRAFT_KEY = "negaran-mumtahin-draft";
onMounted(() => {
  const draft = localStorage.getItem(DRAFT_KEY);
  if (draft) {
    const parsed = JSON.parse(draft);
    Object.assign(form, parsed);
    if (parsed.selectedBooks) selectedBooks.value = parsed.selectedBooks;
    if (parsed.teachingBooks) teachingBooks.value = parsed.teachingBooks;
    if (parsed.currentStep) currentStep.value = parsed.currentStep;
  }
});
watch(
  [form, selectedBooks, teachingBooks, currentStep],
  () => {
    const saveObj = {
      ...form,
      selectedBooks: selectedBooks.value,
      teachingBooks: teachingBooks.value,
      currentStep: currentStep.value
    };
    localStorage.setItem(DRAFT_KEY, JSON.stringify(saveObj));
  },
  { deep: true }
);

// Profile Image logic
function handleProfileImageChange(file) {
  form.profileImage = file;
  const reader = new FileReader();
  reader.onload = (e) => { profileImage.value = e.target.result; };
  reader.readAsDataURL(file);
}

// File handlers
function handleFileChange(event, type) {
  const file = event.target.files[0];
  if (!file) return;
  files[type] = file;
  if (type === 'muhtamim') form.muhtamim_file = file;
  else if (type === 'nid') form.nid_file = file;
  else if (type === 'birthCert') form.birth_cert_file = file;
}
function removeFile(type) {
  files[type] = null;
  if (type === 'muhtamim') form.muhtamim_file = null;
  else if (type === 'nid') form.nid_file = null;
  else if (type === 'birthCert') form.birth_cert_file = null;
}
function viewFile(file) {
  if (!file) return;
  const fileUrl = URL.createObjectURL(file);
  window.open(fileUrl, '_blank');
}

// Book dropdown handlers
function toggleBookDropdown() { showBookDropdown.value = !showBookDropdown.value; if (showBookDropdown.value) showTeachingBookDropdown.value = false; }
function toggleTeachingBookDropdown() { showTeachingBookDropdown.value = !showTeachingBookDropdown.value; if (showTeachingBookDropdown.value) showBookDropdown.value = false; }
function toggleSubMenu(index) { bookCategories.value.forEach((category, i) => { if (i !== index) category.showSubmenu = false; }); bookCategories.value[index].showSubmenu = !bookCategories.value[index].showSubmenu; }
function toggleTeachingBookSubMenu(index) { bookCategories.value.forEach((category, i) => { if (i !== index) category.showTeachingSubmenu = false; }); bookCategories.value[index].showTeachingSubmenu = !bookCategories.value[index].showTeachingSubmenu; }
function addSelectedBook(book) { if (!selectedBooks.value.includes(book)) selectedBooks.value.push(book); showBookDropdown.value = false; }
function addTeachingBook(book) { if (!teachingBooks.value.includes(book)) teachingBooks.value.push(book); showTeachingBookDropdown.value = false; }
function removeBook(index) { selectedBooks.value.splice(index, 1); }
function removeTeachingBook(index) { teachingBooks.value.splice(index, 1); }
function addCustomBook() { if (customBookInput.value.trim()) { teachingBooks.value.push(customBookInput.value.trim()); customBookInput.value = ''; setTimeout(() => { document.getElementById('customBooks')?.focus(); }, 10); } }

// Education
function addQualification() { form.educationalQualifications.push({ className: 'তাকমিল', passYear: '', result: '', institution: '' }); }
function removeQualification(index) { if (form.educationalQualifications.length > 1) form.educationalQualifications.splice(index, 1); }

// Book categories API (simulate loading)
onMounted(async () => {
  try {
    // Demo: mock categories if no API
    const data = [
      { name: "হাদিস", books: ["বুখারী", "তিরমিজী"], showSubmenu: false, showTeachingSubmenu: false },
      { name: "তাফসীর", books: ["তাফসীরে জালালাইন", "তাফসীরে মাআরেফ"], showSubmenu: false, showTeachingSubmenu: false }
    ];
    bookCategories.value = data;
    // If you have API: uncomment below and remove above
    const response = await fetch('/api/get-book-categories');
    if (response.ok) {
      const apiData = await response.json();
      bookCategories.value = apiData.map(category => ({
        ...category,
        showSubmenu: false,
        showTeachingSubmenu: false
      }));
    }
  } catch (error) { }
});

// Watchers for syncing books to form
watch(selectedBooks, (newBooks) => {
  form.teachers.selected_book = newBooks.join(', ');
}, { deep: true });
watch(teachingBooks, (newBooks) => {
  form.teachers.teaching_books = newBooks.join(', ');
}, { deep: true });

// Address
import axios from 'axios';
const divisions = ref([]);
const presentDistricts = ref([]);
const presentThanas = ref([]);
const presentFilters = ref({ division: '', district: '', Thana: '' });
const loadDivisions = async () => {
  try { const response = await axios.get('/api/divisions'); divisions.value = response.data; }
  catch { }
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
  } catch (error) { }
};
const presentHandleDistrictChange = async () => {
  presentFilters.value.Thana = '';
  presentThanas.value = [];
  if (!presentFilters.value.district) return;
  try {
    const response = await axios.get(`/api/thanas/${presentFilters.value.district}`);
    presentThanas.value = response.data;
  } catch (error) { }
};
onMounted(loadDivisions);
watch(presentFilters, (newValues) => {
  form.teachers.division = newValues.division;
  form.teachers.district = newValues.district;
  form.teachers.thana = newValues.Thana;
}, { deep: true });

// ======= Snackbar/Toaster =======
const showSuccessToast = ref(false);
const successMessage = ref('');
const showErrorToast = ref(false);
const errorMessage = ref('');
function showSuccess(msg) {
  successMessage.value = msg;
  showSuccessToast.value = true;
  setTimeout(() => (showSuccessToast.value = false), 3500);
}
function showError(msg) {
  errorMessage.value = msg;
  showErrorToast.value = true;
  setTimeout(() => (showErrorToast.value = false), 4000);
}

// ========== Submission ==========
const page = usePage();
function submitForm() {
  form.post('/teacher-store', {
    onSuccess: () => {
      showSuccess(page.props.flash?.success || 'আপনার আবেদন সফলভাবে জমা হয়েছে');
      localStorage.removeItem(DRAFT_KEY);
      form.reset();
      profileImage.value = null;
      selectedBooks.value = [];
      teachingBooks.value = [];
      currentStep.value = 0;
    },
    onError: () => {
      showError('আবেদন জমা দেওয়ার সময় একটি ত্রুটি হয়েছে');
    },
    preserveScroll: true,
    forceFormData: true,
  });
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.35s cubic-bezier(.4,0,.2,1);
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
