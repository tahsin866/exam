<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class=" sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-t-4 border-indigo-600">
          <div class="p-6">
            <!-- Header -->
            <div class="mb-6 text-center relative">
              <div class="absolute inset-0 bg-gray-100 opacity-25"></div>
              <h2 class="text-2xl font-bold text-gray-800 py-3 relative z-10">বিষয় তথ্য আপডেট</h2>
            </div>

            <!-- Flash Messages -->
            <div id="flash-messages"></div>

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
                  <option value="আবশ্যিক">আবশ্যিক</option>
                  <option value="নৈর্বাচনিক">নৈর্বাচনিক</option>
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
                  <option value="দরসিয়াত">দরসিয়াত</option>
                  <option value="হিফজুল কোরআন">হিফজুল কোরআন</option>
                  <option value="কিরাআত">কিরাআত</option>
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
                  <option value="মিইয়ারী">মিইয়ারী</option>
                  <option value="গায়রে মিইয়ারী">গায়রে মিইয়ারী</option>
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
                  <option value="ছাত্র">ছাত্র</option>
                  <option value="ছাত্রী">ছাত্রী</option>
                  <option value="উভয়">উভয়</option>
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
                  <option value="active">সক্রিয়</option>
                  <option value="inactive">নিষ্ক্রিয়</option>
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
                  {{ form.processing ? 'আপডেট হচ্ছে...' : 'আপডেট করুন' }}
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
const subjectSetting = ref({})
const marhalaName = computed(() => marhala.value?.marhala_name_bn || '')

const fetchSubjectSetting = async (id) => {
    try {
        const response = await axios.get(`/api/subject-settings/${id}`);
        if (response.data.success) {
            subjectSetting.value = response.data.subjectSetting;
            // Populate the form with existing data
            form.marhala_id = subjectSetting.value.marhala_id;
            form.subject_id = subjectSetting.value.subject_id;
            form.Marhala_type = subjectSetting.value.Marhala_type;
            form.Subject_Names = subjectSetting.value.Subject_Names;
            form.student_type = subjectSetting.value.student_type;
            form.syllabus_type = subjectSetting.value.syllabus_type;
            form.markaz_type = subjectSetting.value.markaz_type;
            form.subject_type = subjectSetting.value.subject_type;
            form.subject_code = subjectSetting.value.subject_code;
            form.total_marks = subjectSetting.value.total_marks;
            form.pass_marks = subjectSetting.value.pass_marks;
            form.status = subjectSetting.value.status;
            // Fetch marhala and subjects data
            await fetchData(subjectSetting.value.marhala_id);
        }
    } catch (error) {
        console.error('Error fetching subject setting:', error);
        showFlashMessage('error', 'তথ্য লোড করতে সমস্যা হয়েছে। পরে আবার চেষ্টা করুন।');
    }
};

const fetchData = async (marhalaId) => {
    try {
        const response = await axios.get(`/api/marhala/${marhalaId}/subjects`);
        marhala.value = response.data.marhala;
        subjects.value = response.data.subjects;
    } catch (error) {
        console.error('Error fetching data:', error);
        showFlashMessage('error', 'তথ্য লোড করতে সমস্যা হয়েছে। পরে আবার চেষ্টা করুন।');
    }
};

const form = useForm({
    marhala_id: '',
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

const showFlashMessage = (type, message) => {
    const flashContainer = document.getElementById('flash-messages');
    const flashMessage = document.createElement('div');

    if (type === 'success') {
        flashMessage.className = 'mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded relative';
        flashMessage.innerHTML = `
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span>${message}</span>
            </div>
            <button class="absolute top-0 right-0 p-2" onclick="this.parentElement.remove()">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;
    } else {
        flashMessage.className = 'mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded relative';
        flashMessage.innerHTML = `
            <div class="flex items-center">
                               <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>${message}</span>
            </div>
            <button class="absolute top-0 right-0 p-2" onclick="this.parentElement.remove()">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;
    }

    flashContainer.appendChild(flashMessage);

    // Remove the flash message after 5 seconds
    setTimeout(() => {
        if (flashMessage.parentNode) {
            flashMessage.classList.add('opacity-0', 'transition-opacity', 'duration-500');
            setTimeout(() => {
                if (flashMessage.parentNode) {
                    flashMessage.remove();
                }
            }, 500);
        }
    }, 5000);
};

const submit = async () => {
    try {
        const id = route().params.marhala;
        const response = await axios.put(`/api/subject-settings/${id}`, form);
        if (response.data.success) {
            showFlashMessage('success', response.data.message || 'বিষয় তথ্য সফলভাবে আপডেট করা হয়েছে।');
        }
    } catch (error) {
        if (error.response?.data?.errors) {
            // Handle validation errors
            console.error('Validation errors:', error.response.data.errors);
            showFlashMessage('error', 'ফর্মে কিছু ত্রুটি রয়েছে। অনুগ্রহ করে চেক করুন।');
        } else {
            console.error('Error updating data:', error);
            showFlashMessage('error', 'একটি ত্রুটি ঘটেছে। পরে আবার চেষ্টা করুন।');
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
    const id = route().params.marhala;
    fetchSubjectSetting(id);
});
</script>

