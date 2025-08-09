<template>
  <AuthenticatedLayout>
    <div class="py-10  bg-gray-50">
      <div class=" mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white shadow-xl rounded-xl border-t-4 border-indigo-700">
          <div class="px-8 py-10">
            <!-- Header -->
            <div class="mb-10">
              <div class="flex items-center justify-center relative">
                <span class="absolute inset-0 rounded-lg bg-gradient-to-r from-indigo-100 to-white opacity-30"></span>
                <h2 class="relative z-10 text-3xl font-extrabold text-gray-900 tracking-tight">
                  বিষয় তথ্য সংযোজন
                </h2>
              </div>
            </div>
            <!-- Success Message -->
            <transition name="fade">
              <div
                v-if="successMessage"
                class="mb-6 flex items-center justify-between rounded-lg border border-green-400 bg-green-50 px-6 py-4 text-green-900 shadow"
              >
                <span class="font-semibold">{{ successMessage }}</span>
                <button
                  type="button"
                  @click="successMessage = ''"
                  class="ml-4 rounded-full p-1 hover:bg-green-100 transition"
                >
                  <svg class="h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>
            </transition>
            <!-- Error Message -->
            <transition name="fade">
              <div
                v-if="errorMessage"
                class="mb-6 flex items-center justify-between rounded-lg border border-red-400 bg-red-50 px-6 py-4 text-red-900 shadow"
              >
                <span class="font-semibold">{{ errorMessage }}</span>
                <button
                  type="button"
                  @click="errorMessage = ''"
                  class="ml-4 rounded-full p-1 hover:bg-red-100 transition"
                >
                  <svg class="h-6 w-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                  </svg>
                </button>
              </div>
            </transition>
            <form @submit.prevent="submit" autocomplete="off">
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <!-- মারহালার নাম -->
                <div>
                  <label class="block text-base font-semibold text-gray-700 mb-2">মারহালার নাম</label>
                  <input
                    type="text"
                    :value="marhalaName"
                    readonly
                    class="w-full bg-gray-100 border border-gray-300 rounded-lg text-gray-700 py-2 px-3 shadow-inner cursor-not-allowed"
                  />
                </div>

                <!-- বিষয় -->
                <div>
                  <label class="block text-base font-semibold text-gray-700 mb-2">বিষয় নির্বাচন করুন <span class="text-red-500">*</span></label>
                  <select
                    v-model="form.subject_id"
                    class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 transition"
                  >
                    <option value="" disabled>নির্বাচন করুন</option>
                    <option v-for="subject in subjects" :key="subject.id" :value="subject.id">
                      {{ subject.name_bangla }}
                    </option>
                  </select>
                </div>

                <!-- সিলেবাসের ধরন -->
                <div>
                  <label class="block text-base font-semibold text-gray-700 mb-2">সিলেবাসের ধরন <span class="text-red-500">*</span></label>
                  <select
                    v-model="form.syllabus_type"
                    class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 transition"
                  >
                    <option value="" disabled>নির্বাচন করুন</option>
                    <option v-for="type in syllabusTypes" :key="type.value" :value="type.value">
                      {{ type.name }}
                    </option>
                  </select>
                </div>

                <!-- মারকাযের ধরন -->
                <div>
                  <label class="block text-base font-semibold text-gray-700 mb-2">মারকাযের ধরন <span class="text-red-500">*</span></label>
                  <select
                    v-model="form.markaz_type"
                    class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 transition"
                  >
                    <option value="" disabled>নির্বাচন করুন</option>
                    <option v-for="type in markazTypes" :key="type.value" :value="type.value">
                      {{ type.name }}
                    </option>
                  </select>
                </div>

                <!-- বিষয়ের ধরন -->
                <div>
                  <label class="block text-base font-semibold text-gray-700 mb-2">বিষয়ের ধরন <span class="text-red-500">*</span></label>
                  <select
                    v-model="form.subject_type"
                    class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 transition"
                  >
                    <option value="" disabled>নির্বাচন করুন</option>
                    <option v-for="type in subjectTypes" :key="type.value" :value="type.value">
                      {{ type.name }}
                    </option>
                  </select>
                </div>

                <!-- ছাত্র/ছাত্রীর ধরন -->
                <div>
                  <label class="block text-base font-semibold text-gray-700 mb-2">ছাত্র/ছাত্রীর ধরন <span class="text-red-500">*</span></label>
                  <select
                    v-model="form.student_type"
                    class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 transition"
                  >
                    <option value="" disabled>নির্বাচন করুন</option>
                    <option v-for="type in studentTypes" :key="type.value" :value="type.value">
                      {{ type.name }}
                    </option>
                  </select>
                </div>

                <!-- মোট মার্ক -->
                <div>
                  <label class="block text-base font-semibold text-gray-700 mb-2">মোট মার্ক <span class="text-red-500">*</span></label>
                  <input
                    type="number"
                    v-model="form.total_marks"
                    class="w-full py-2 px-3 border border-gray-300 rounded-lg shadow focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 transition"
                  />
                </div>

                <!-- পাশ মার্ক -->
                <div>
                  <label class="block text-base font-semibold text-gray-700 mb-2">পাশ মার্ক <span class="text-red-500">*</span></label>
                  <input
                    type="number"
                    v-model="form.pass_marks"
                    class="w-full py-2 px-3 border border-gray-300 rounded-lg shadow focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 transition"
                  />
                </div>

                <!-- স্ট্যাটাস -->
                <div>
                  <label class="block text-base font-semibold text-gray-700 mb-2">স্ট্যাটাস <span class="text-red-500">*</span></label>
                  <select
                    v-model="form.status"
                    class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-lg shadow focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 transition"
                  >
                    <option v-for="option in statusOptions" :key="option.value" :value="option.value">
                      {{ option.name }}
                    </option>
                  </select>
                </div>
              </div>
              <!-- Submit Button -->
              <div class="flex justify-end mt-10">
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="inline-flex items-center px-6 py-2 border border-transparent text-base font-bold rounded-lg shadow text-white bg-gradient-to-r from-indigo-600 to-indigo-800 hover:from-indigo-700 hover:to-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition"
                >
                  <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-20" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
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
      successMessage.value = response.data.message;
      setTimeout(() => {
        successMessage.value = '';
      }, 3000);
      await fetchData(marhalaId);
    }
  } catch (error) {
    if (error.response?.data?.errors) {
      errorMessage.value = 'ফর্ম সংরক্ষণ করা যায়নি। অনুগ্রহ করে সকল তথ্য সঠিকভাবে পূরণ করুন।';
      setTimeout(() => {
        errorMessage.value = '';
      }, 5000);
    } else {
      errorMessage.value = 'একটি ত্রুটি ঘটেছে। পরে আবার চেষ্টা করুন।';
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

<style>
.fade-enter-active, .fade-leave-active {
  transition: opacity .2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>