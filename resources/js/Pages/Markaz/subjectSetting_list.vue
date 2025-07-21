<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
        </svg>
        মাদ্রাসার পরীক্ষার বিষয়ভিত্তিক নির্বাচন
      </h2>
    </template>

    <div class="py-12">
      <div class="sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 text-gray-900">
            <div v-if="loading" class="flex justify-center items-center py-10">
              <div class="border-b-2 border-indigo-500 h-12 rounded-full w-12 animate-spin"></div>
            </div>
            <div v-else>
              <!-- আবশ্যিক বিষয় -->
              <div class="mb-8" v-if="mandatorySubjects.length > 0">
                <div class="flex items-center mb-3">
                  <div class="flex bg-gray-800 h-8 justify-center rounded-full text-white w-8 items-center mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <h3 class="text-gray-800 text-lg font-semibold">আবশ্যিক বিষয়সমূহ</h3>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                  <div class="bg-gray-800 text-center text-white text-xl font-medium px-4 py-2">
                    সকল আবশ্যিক বিষয় পরীক্ষায় অন্তর্ভুক্ত থাকবে
                  </div>
                  <div class="overflow-x-auto">
                    <table class="border-collapse w-full">
                      <thead>
                        <tr class="bg-gray-100">
                          <th class="border-b border-gray-200 p-3 text-gray-800 text-left text-xl w-12 font-medium">#</th>
                          <th class="border-b border-gray-200 p-3 text-gray-800 text-left text-xl w-32 font-medium">কোড</th>
                          <th class="border-b border-gray-200 p-3 text-gray-800 text-left text-xl font-medium">বিষয়</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(subject, index) in mandatorySubjects" :key="index" class="hover:bg-gray-50 transition-colors">
                          <td class="border-b border-gray-100 p-3 text-gray-700 text-xl">{{ index + 1 }}</td>
                          <td class="border-b border-gray-100 p-3 text-gray-700 text-xl font-medium">{{ subject.code }}</td>
                          <td class="border-b border-gray-100 p-3 text-gray-700 text-xl">{{ subject.name }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <!-- ঐচ্ছিক বিষয় -->
              <div class="mb-8" v-if="optionalSubjects.length > 0">
                <div class="flex items-center mb-3">
                  <div class="flex bg-gray-600 h-8 justify-center rounded-full text-white w-8 items-center mr-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                      <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z" />
                      <path fill-rule="evenodd" d="M2 6a2 2 0 012-2h4a1 1 0 010 2H4v10h10v-4a1 1 0 112 0v4a2 2 0 01-2 2H4a2 2 0 01-2-2V6z" clip-rule="evenodd" />
                    </svg>
                  </div>
                  <h3 class="text-gray-800 text-lg font-semibold">ঐচ্ছিক বিষয়সমূহ</h3>
                </div>
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                  <div class="flex bg-gray-600 justify-between text-white font-medium items-center px-4 py-2">
                    <span class="bg-white rounded-full text-gray-700 text-sm font-bold px-3 py-1">
                      যে কোন একটি ১ টি নির্বাচন করুন
                    </span>
                  </div>
                  <div class="overflow-x-auto">
                    <table class="border-collapse w-full">
                      <thead>
                        <tr class="bg-gray-100">
                          <th class="border-gray-200 border-b p-3 text-gray-800 text-left text-sm w-16 font-medium">নির্বাচন</th>
                          <th class="border-gray-200 border-b p-3 text-gray-800 text-left text-sm w-32 font-medium">কোড</th>
                          <th class="border-gray-200 border-b p-3 text-gray-800 text-left text-sm font-medium">বিষয়</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr v-for="(subject, index) in optionalSubjects" :key="index" class="hover:bg-gray-50 transition-colors">
                          <td class="border-gray-100 border-b p-3 text-center">
                            <div class="flex justify-center">
                              <label class="inline-flex items-center" :class="{'opacity-50': isDisabled(subject.code)}">
                                <input
                                  type="checkbox"
                                  :value="subject.code"
                                  :checked="selectedOptionalSubject === subject.code"
                                  @change="toggleSubject(subject.code)"
                                  :disabled="isDisabled(subject.code)"
                                  class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                >
                              </label>
                            </div>
                          </td>
                          <td class="border-gray-100 border-b p-3 text-gray-700 text-sm font-medium">{{ subject.code }}</td>
                          <td class="border-gray-100 border-b p-3 text-gray-700 text-sm">{{ subject.name }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <!-- সাবমিট বাটন -->
              <div class="flex justify-end mt-8" v-if="optionalSubjects.length > 0">
                <button
                  @click="submitSelection"
                  class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                  </svg>
                  {{ submitButtonText() }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { ref, onMounted } from "vue";
import axios from "axios";

// Get marhalaId from URL query parameters
const getMarhalaId = () => {
  const urlParams = new URLSearchParams(window.location.search);
  return urlParams.get('marhala') || window.location.pathname.split('/').pop();
};

const marhalaId = getMarhalaId();
const mandatorySubjects = ref([]);
// const optionalSubjects = ref([]);
const selectedSubjects = ref([]);
const loading = ref(true);

onMounted(() => {
  fetchSubjects();
});

const isUpdating = ref(false);
const previousSelection = ref(null);

const fetchSubjects = async () => {
  try {
    loading.value = true;
    // Get subjects and existing selection in parallel
    const [subjectsResponse, selectionResponse] = await Promise.all([
      axios.get(`/api/subjects/${marhalaId}`),
      axios.get(`/api/user-subject-selection/${marhalaId}`)
    ]);

    // Map the subjects data
    mandatorySubjects.value = subjectsResponse.data.mandatorySubjects.map(subject => ({
      id: subject.id,
      name: subject.name,
      code: subject.code || '',
      syllabus_type: subject.syllabus_type
    }));

    optionalSubjects.value = subjectsResponse.data.optionalSubjects.map(subject => ({
      id: subject.id,
      name: subject.name,
      code: subject.code || '',
      syllabus_type: subject.syllabus_type
    }));

    // If user has an existing selection, pre-select it
    if (selectionResponse.data && selectionResponse.data.selection) {
      selectedOptionalSubject.value = selectionResponse.data.selection.subject_code;
      previousSelection.value = selectionResponse.data.selection.subject_code;
      isUpdating.value = true;
    }
  } catch (error) {
    console.error("Error fetching data:", error);
  } finally {
    loading.value = false;
  }
};

// Update the submit button text based on whether we're creating or updating
const submitButtonText = () => {
  return isUpdating.value ? 'আপডেট করুন' : 'সাবমিট করুন';
};

const submitSelection = async () => {
  if (!selectedOptionalSubject.value) {
    alert("দয়া করে কমপক্ষে একটি ঐচ্ছিক বিষয় নির্বাচন করুন।");
    return;
  }

  try {
    const response = await axios({
      method: 'post',
      url: '/api/save-subject-selection',
      data: {
        marhalaId: marhalaId,
        selectedSubjects: [selectedOptionalSubject.value],
        previousSelection: previousSelection.value
      },
    });

    // Update the previous selection after successful save
    previousSelection.value = selectedOptionalSubject.value;
    isUpdating.value = true;
    alert(response.data.message || "আপনি নির্বাচিত বিষয়সমূহ সফলভাবে সংরক্ষণ করা হয়েছে।");
  } catch (error) {
    console.error("Error saving subject selection:", error);
    if (error.response) {
      console.error("Response data:", error.response.data);
      console.error("Response status:", error.response.status);
    }
    alert("বিষয় নির্বাচন সংরক্ষণ করতে সমস্যা হয়েছে। দয়া করে আবার চেষ্টা করুন।");
  }
};

// Reactive state
const optionalSubjects = ref([
  // Your optional subjects array here
  // Example: { code: '101', name: 'Subject 1' }
]);

const selectedOptionalSubject = ref(null);

// Methods
const toggleSubject = (code) => {
  // If the clicked checkbox is already selected, unselect it
  if (selectedOptionalSubject.value === code) {
    selectedOptionalSubject.value = null;
  } else {
    // Otherwise, select the clicked checkbox
    selectedOptionalSubject.value = code;
  }
    // If you need to emit the change to a parent component
  // emit('update:selectedSubject', selectedOptionalSubject.value);
};

const isDisabled = (code) => {
  // Return true if another subject is selected (not this one)
  return selectedOptionalSubject.value !== null && selectedOptionalSubject.value !== code;
};
</script>

<style>
/* Add this to your CSS to import Noto Sans Bengali font */
</style>

