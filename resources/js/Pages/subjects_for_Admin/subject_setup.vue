<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class=" sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-indigo-600">
          <div class="p-6">
            <!-- Header -->
            <div class="mb-6 text-center relative">
              <div class="absolute inset-0 bg-gray-100 opacity-25"></div>
              <h2 class="text-2xl font-bold text-gray-800 py-3 relative z-10">বিষয় তথ্য সংযোজন</h2>
            </div>

            <!-- Success Message -->
            <div v-if="successMessage" class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
              <span class="block sm:inline">{{ successMessage }}</span>
              <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="successMessage = ''">
                <svg class="fill-current h-6 w-6 text-green-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <title>Close</title>
                  <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
              </span>
            </div>

            <!-- Error Message -->
            <div v-if="errorMessage" class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative">
              <span class="block sm:inline">{{ errorMessage }}</span>
              <span class="absolute top-0 bottom-0 right-0 px-4 py-3" @click="errorMessage = ''">
                <svg class="fill-current h-6 w-6 text-red-500" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                  <title>Close</title>
                  <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.029a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
              </span>
            </div>

            <form @submit.prevent="submit" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
              <!-- মারহালার নাম -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">মারহালার নাম</label>
                <input
                  type="text"
                  :value="marhalaName"
                  readonly
                  class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md bg-gray-100"
                />
              </div>

              <!-- বিষয় -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">বিষয় নির্বাচন করুন *</label>
                <select
                  v-model="form.subject_id"
                  class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
                  <option value="" disabled>নির্বাচন করুন</option>
                  <option v-for="subject in subjects" :key="subject.id" :value="subject.id">
                    {{ subject.name_bangla }}
                  </option>
                </select>
              </div>

              <!-- সিলেবাসের ধরন -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">সিলেবাসের ধরন *</label>
                <select
                  v-model="form.syllabus_type"
                  class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
                  <option value="" disabled>নির্বাচন করুন</option>
                  <option v-for="type in syllabusTypes" :key="type.value" :value="type.value">
                    {{ type.name }}
                  </option>
                </select>
              </div>

              <!-- মারকাযের ধরন -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">মারকাযের ধরন *</label>
                <select
                  v-model="form.markaz_type"
                  class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
                  <option value="" disabled>নির্বাচন করুন</option>
                  <option v-for="type in markazTypes" :key="type.value" :value="type.value">
                    {{ type.name }}
                  </option>
                </select>
              </div>

              <!-- বিষয়ের ধরন -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">বিষয়ের ধরন *</label>
                <select
                  v-model="form.subject_type"
                  class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
                  <option value="" disabled>নির্বাচন করুন</option>
                  <option v-for="type in subjectTypes" :key="type.value" :value="type.value">
                    {{ type.name }}
                  </option>
                </select>
              </div>

              <!-- ছাত্র/ছাত্রীর ধরন -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">ছাত্র/ছাত্রীর ধরন *</label>
                <select
                  v-model="form.student_type"
                  class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
                  <option value="" disabled>নির্বাচন করুন</option>
                  <option v-for="type in studentTypes" :key="type.value" :value="type.value">
                    {{ type.name }}
                  </option>
                </select>
              </div>

              <!-- মোট মার্ক -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">মোট মার্ক *</label>
                <input
                  type="number"
                  v-model="form.total_marks"
                  class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                />
              </div>

              <!-- পাশ মার্ক -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">পাশ মার্ক *</label>
                <input
                  type="number"
                  v-model="form.pass_marks"
                  class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"
                />
              </div>

              <!-- স্ট্যাটাস -->
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">স্ট্যাটাস *</label>
                <select
                  v-model="form.status"
                  class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                >
                  <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                    {{ option.name }}
                  </option>
                </select>
              </div>

              <!-- Submit Button -->
              <div class="col-span-1 md:col-span-2 lg:col-span-4 flex justify-end mt-4">
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                  <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  সংরক্ষণ করুন
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue'
import { ref, computed, onMounted, watch } from 'vue'
import axios from 'axios'
import { useForm } from '@inertiajs/vue3'

const marhala = ref({})
const subjects = ref([])
const successMessage = ref('')
const errorMessage = ref('')

const marhalaName = computed(() => marhala.value?.marhala_name_bn || '')

// Dropdown options
const syllabusTypes = [
  { name: 'আবশ্যিক', value: 'আবশ্যিক' },
  { name: 'নৈর্বাচনিক', value: 'নৈর্বাচনিক' }
]

const markazTypes = [
  { name: 'দরসিয়াত', value: 'দরসিয়াত' },
  { name: 'হিফজুল কোরআন', value: 'হিফজুল কোরআন' },
  { name: 'কিরাআত', value: 'কিরাআত' }
]

const subjectTypes = [
  { name: 'মিইয়ারী', value: 'মিইয়ারী' },
  { name: 'গায়রে মিইয়ারী', value: 'গায়রে মিইয়ারী' }
]

const studentTypes = [
  { name: 'ছাত্র', value: 'ছাত্র' },
  { name: 'ছাত্রী', value: 'ছাত্রী' },
  { name: 'উভয়', value: 'উভয়' }
]

const statusOptions = [
  { name: 'সক্রিয়', value: 'active' },
  { name: 'নিষ্ক্রিয়', value: 'inactive' }
]

const fetchData = async (marhalaId) => {
  try {
    const response = await axios.get(`/api/marhala/${marhalaId}/subjects`);
    marhala.value = response.data.marhala;
    subjects.value = response.data.subjects;
    form.Marhala_type = response.data.marhala.marhala_name_bn;
  } catch (error) {
    console.error('Error fetching data:', error);
    errorMessage.value = 'তথ্য লোড করতে সমস্যা হয়েছে। পরে আবার চেষ্টা করুন।';
  }
};

const form = useForm({
  marhala_id: route().params.marhala,
  subject_id: '',
  Marhala_type: '',
  Subject_Names: '',
  student_type: '',
  syllabus_type: '',
  markaz_type: '',
  subject_type: '',
  subject_code: '',
  total_marks: '',
  pass_marks: '',
  status: 'active'
});

const submit = async () => {
  try {
    const response = await axios.post(route('subject-settings.store'), form);
    if (response.data.success) {
      const marhalaId = form.marhala_id;
      form.reset();
      form.marhala_id = marhalaId;
      // Show success message
      successMessage.value = response.data.message;
      // Hide success message after 3 seconds
      setTimeout(() => {
        successMessage.value = '';
      }, 3000);
      // Refresh data
      await fetchData(marhalaId);
    }
  } catch (error) {
    if (error.response?.data?.errors) {
      // Handle validation errors
      console.error('Validation errors:', error.response.data.errors);
      errorMessage.value = 'ফর্ম সংরক্ষণ করা যায়নি। অনুগ্রহ করে সকল তথ্য সঠিকভাবে পূরণ করুন।';
      // Hide error message after 5 seconds
      setTimeout(() => {
        errorMessage.value = '';
      }, 5000);
    } else {
      console.error('Error saving data:', error);
      errorMessage.value = 'একটি ত্রুটি ঘটেছে। পরে আবার চেষ্টা করুন।';
      // Hide error message after 5 seconds
      setTimeout(() => {
        errorMessage.value = '';
      }, 5000);
    }
  }
};

watch(() => form.subject_id, (newValue) => {
  if (newValue) {
    const selectedSubject = subjects.value.find(subject => subject.id === parseInt(newValue));
    if (selectedSubject) {
      form.Subject_Names = selectedSubject.name_bangla;
      form.subject_code = selectedSubject.subject_code;
      form.Marhala_type = marhala.value.marhala_name_bn;
    }
  }
});

onMounted(() => {
  const marhalaId = route().params.marhala
  fetchData(marhalaId)
})
</script>
