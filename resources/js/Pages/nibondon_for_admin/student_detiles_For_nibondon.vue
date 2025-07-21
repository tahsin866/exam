<template>
  <AuthenticatedLayout>
    <div v-if="student" class="py-12">
      <div class=" sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
              <h2 class="text-xl font-semibold text-gray-800">Student Registration Details</h2>
              <div class="flex space-x-2">
                <button class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-300 focus:outline-none focus:border-gray-300 focus:ring ring-gray-300 disabled:opacity-25 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                  </svg>
                  Download
                </button>
                <button class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                  </svg>
                  Print
                </button>
              </div>
            </div>

            <!-- Registration Info -->
            <div class="bg-gray-50 p-4 rounded-md text-sm text-gray-700 mb-6 flex flex-wrap justify-between items-center">
              <div class="mb-2 sm:mb-0">
                <span class="font-semibold">Registration No:</span> {{ student.id || '5' }}
              </div>
              <div class="mb-2 sm:mb-0">
                <span class="font-semibold">Reference No:</span> {{ student.reference_no || '00208357' }}
              </div>
              <div class="mb-2 sm:mb-0">
                <span class="font-semibold">Registration ID:</span> {{ student.registration_id || '460203305' }}
              </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <!-- Left Column - Photo and Application Type -->
              <div class="md:col-span-1">
                <!-- Photo Section -->
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm mb-6">
                  <div class="aspect-[3/4] w-32 h-40 mx-auto bg-gray-100 rounded-md flex flex-col items-center justify-center border border-dashed border-gray-300 mb-2 overflow-hidden">
                    <img
                      v-if="student.student_image"
                      :src="'/storage/' + student.student_image"
                      alt="Student"
                      class="w-full h-full object-cover"
                    />
                    <p v-else class="text-gray-500 text-sm">No image available</p>
                  </div>
                  <p class="text-xs text-center text-gray-500 italic">* Not applicable for female students</p>
                </div>

                <!-- Application Type Section -->
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm mb-6">
                  <h2 class="text-lg font-medium text-gray-800 mb-3">
                   আবেদনের ধরন
                  </h2>
                  <div class="space-y-2">
                    <div>
                      <span class="text-gray-700 font-medium block">পরীক্ষার নাম:</span>
                      <span class="text-gray-600">{{ student.exam_name_Bn }}</span>
                    </div>
                    <div>
                      <span class="text-gray-700 font-medium block">পরীক্ষার্থীর ধরন:</span>
                      <span class="text-gray-600">{{ student.student_type }}</span>
                    </div>
                    <div>
                      <span class="text-gray-700 font-medium block">Exam Year:</span>
                      <span class="text-gray-600">1446 Hijri</span>
                    </div>
                  </div>
                </div>

                <!-- Board Info -->
                <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm">
                  <h2 class="text-lg font-medium text-gray-800 mb-3">
                    Board Information
                  </h2>
                  <div class="space-y-2">
                    <div>
                      <span class="text-gray-700 font-medium block">Registration Board:</span>
                      <span class="text-gray-600">Befaqul Madarisil Arabia Bangladesh</span>
                    </div>
                    <div>
                      <span class="text-gray-700 font-medium block">Affiliated Madrasa:</span>
                      <span class="text-gray-600">40142 - Begum Lutfunnesa Women's Madrasa, Manua, Bhedarganj, Shariatpur</span>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Right Column - Previous Exam and Personal Info -->
              <div class="md:col-span-2">
                <!-- Previous Exam Info Section -->
                <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm mb-6">
                  <h2 class="text-lg font-medium text-gray-800 mb-4">
                   পূর্ববর্তী পরীক্ষার তথ্য
                  </h2>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4">
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">পরীক্ষার নাম:</span>
                      <span class="text-gray-600">{{ student.exam_name_Bn }}</span>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">পরীক্ষার্থীর নাম:</span>
                      <span class="text-gray-600">{{ student.name_bn }}</span>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">লিঙ্গ:</span>
                      <span class="text-gray-600">Female</span>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">পিতার নাম:</span>
                      <span class="text-gray-600">{{ student.father_name_bn }}</span>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">রোল নম্বর:</span>
                      <span class="text-gray-600">{{ student.past_Roll }}</span>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">নিবন্ধন নম্বর:</span>
                      <span class="text-gray-600">{{ student.past_reg_id }}</span>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">বিগত শিক্ষাবর্ষ:</span>
                      <span class="text-gray-600">{{ student.passing_year }}</span>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">বিভাগ:</span>
                      <span class="text-gray-600">{{ student.division }}</span>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">বিগত মাদরাসার :</span>
                      <span class="text-gray-600">{{ student.madrasha_name }}</span>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">বিগত মারকায:</span>
                      <span class="text-gray-600">{{ student.madrasha_name }}</span>
                    </div>
                    <div class="flex items-start col-span-2">
                      <span class="text-gray-700 font-medium w-32">বোর্ড:</span>
                      <span class="text-gray-600">Befaqul Madarisil Arabia Bangladesh</span>
                    </div>
                  </div>
                </div>

                <!-- Personal Info Section -->
                <div class="bg-white p-5 rounded-lg border border-gray-200 shadow-sm">
                  <h2 class="text-lg font-medium text-gray-800 mb-4">
                    Applicant Information
                  </h2>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-y-4">
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">পরীক্ষার্থীর নাম:</span>
                      <div>
                        <span class="text-gray-600 block">{{ student.name_bn }}</span>
                        <span class="text-gray-500 text-sm font-semibold">{{ student.name_en }}</span>
                        <span class="text-gray-500 text-sm font-semibold block">{{ student.name_ar }}</span>
                      </div>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">পিতার নাম:</span>
                      <div>
                        <span class="text-gray-600 block">{{ student.father_name_bn }}</span>
                        <span class="text-gray-600 block">{{ student.father_name_en }}</span>
                        <span class="text-gray-500 text-xs">{{ student.father_name_ar }}</span>
                      </div>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">মাতার নাম:</span>
                      <div>
                        <span class="text-gray-600 block">{{ student.mother_name_bn }}</span>
                        <span class="text-gray-500 text-xs">{{ student.mother_name_en }}</span>
                        <span class="text-gray-500 text-xs block">{{ student.mother_name_ar }}</span>
                      </div>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">জন্মতারিখ:</span>
                      <span class="text-gray-600">{{ student.Date_of_birth }}</span>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">জাতিয়তা:</span>
                      <div>
                        <span class="text-gray-600 block">Bangladeshi</span>

                      </div>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">জন্মবিন্ধন:</span>
                      <div>
                        <span class="text-gray-600 block">জন্মবিন্ধন</span>
                        <span class="text-gray-600 block text-sm">{{ student.NID_no }}</span>
                        <button
                          @click="showIdModal = true"
                          class="mt-1 text-xs text-blue-600 hover:text-blue-800 flex items-center"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                          </svg>
                         জন্মবিন্ধন স্ক্যান কপি দেখেতে এখানে ক্লিক করুন
                        </button>
                      </div>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">ফোন নম্বর:</span>
                      <span class="text-gray-600">01766839308</span>
                    </div>
                    <div class="flex items-start">
                      <span class="text-gray-700 font-medium w-32">ইমেইল :</span>
                      <span class="text-gray-500 italic">Not provided</span>
                    </div>
                    <div class="flex items-start col-span-2">
                      <span class="text-gray-700 font-medium w-32">Permanent Address:</span>

                      <span class="text-gray-600">Haji Mohammad Khorshed Akan Kandi, Palerchar Hat, Jazira, Shariatpur</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex flex-col md:flex-row justify-between items-center">
              <div class="flex space-x-3 mb-4 md:mb-0">
                <button
                  @click="openApprovalModal()"
                  class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                  </svg>
                  Approve
                </button>
                <button
                  class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z" />
                  </svg>
                  Suspend
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div v-else class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6 bg-white border-b border-gray-200 flex justify-center items-center">
            <p class="text-lg text-gray-600">Loading data...</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Approval Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg w-96 overflow-hidden">
        <div class="bg-gray-100 p-4">
          <h3 class="text-lg font-medium text-gray-900">Confirm Approval</h3>
        </div>
        <div class="p-6">
          <div class="text-center mb-6">
            <div class="w-16 h-16 mx-auto mb-4 bg-green-100 rounded-full flex items-center justify-center">
              <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
              </svg>
            </div>
            <p class="text-gray-600 mt-2">Are you sure you want to approve this application?</p>
          </div>
          <div class="flex justify-center gap-4">
            <button
              @click="confirmApproval"
              class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition"
            >
              Yes, Confirm
            </button>
            <button
              @click="showModal = false"
              class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition"
            >
              No, Cancel
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- ID Document Modal -->
    <div v-if="showIdModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg shadow-xl max-w-3xl w-full max-h-[90vh] overflow-hidden">
        <div class="bg-gray-100 px-4 py-3 flex justify-between items-center">
          <h3 class="font-medium text-gray-900">ID Document Details</h3>
          <button @click="showIdModal = false" class="text-gray-500 hover:text-gray-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
          </button>
        </div>
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-4rem)]">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- ID Document Image -->
            <div class="bg-gray-100 rounded-lg p-2 flex items-center justify-center">
              <img
                v-if="student.NID_attach"
                :src="'/storage/' + student.NID_attach"
                alt="Student"
                class="w-full h-full object-cover"
              />
            </div>
            <!-- ID Document Details -->
            <div>
              <h4 class="text-lg font-medium text-gray-800 mb-4">Birth Certificate Details</h4>
              <div class="space-y-3">
                <div>
                  <span class="text-gray-700 font-medium block">Name:</span>
                  <span class="text-gray-600">{{ student.name_bn }}</span>
                </div>
                <div>
                  <span class="text-gray-700 font-medium block">Father's Name:</span>
                  <span class="text-gray-600">{{ student.father_name_bn }}</span>
                </div>
                <div>
                  <span class="text-gray-700 font-medium block">Mother's Name:</span>
                  <span class="text-gray-600">{{ student.mother_name_bn }}</span>
                </div>
                <div>
                  <span class="text-gray-700 font-medium block">Date of Birth:</span>
                  <span class="text-gray-600">{{ student.Date_of_birth }}</span>
                </div>
                <div>
                  <span class="text-gray-700 font-medium block">Birth Certificate No:</span>
                  <span class="text-gray-600">{{ student.BRN_no }}</span>
                </div>
                <div>
                  <span class="text-gray-700 font-medium block">Registration No:</span>
                  <span class="text-gray-600">460203305</span>
                </div>
                <div>
                  <span class="text-gray-700 font-medium block">Issue Date:</span>
                  <span class="text-gray-600">12 January 2010</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Return Reason Section -->
          <div v-if="showReturnReason" class="mt-6 bg-yellow-50 p-4 rounded-md border border-yellow-200">
            <h5 class="text-sm font-medium text-gray-800 mb-2">Return Reason</h5>
            <textarea
              v-model="returnReason"
              placeholder="Enter reason for return..."
              class="w-full p-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
              rows="4"
            ></textarea>
            <div class="mt-3 flex justify-end space-x-2">
              <button
                @click="submitReturn"
                class="inline-flex items-center px-3 py-1 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 disabled:opacity-25 transition"
              >
                Confirm Return
              </button>
              <button
                @click="showReturnReason = false"
                class="inline-flex items-center px-3 py-1 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition"
              >
                Cancel
              </button>
            </div>
          </div>

          <!-- Footer Buttons -->
          <div class="mt-6 flex justify-end">
            <button
              @click="showReturnReason = true"
              class="inline-flex items-center px-4 py-2 bg-red-100 border border-transparent rounded-md font-semibold text-xs text-red-700 uppercase tracking-widest hover:bg-red-200 active:bg-red-300 focus:outline-none focus:border-red-300 focus:ring ring-red-200 disabled:opacity-25 transition mr-3"
            >
              Return to Board
            </button>
            <button
              @click="showIdModal = false"
              class="inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-25 transition"
            >
              Close
            </button>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3'

const showToast = ref(false);
const showModal = ref(false);

const openApprovalModal = () => {
  showModal.value = true;
};

const confirmApproval = () => {
  router.post(route('student.approve', { id: props.student.id }), {}, {
    preserveScroll: true,
    onSuccess: () => {
      showModal.value = false;
      showToast.value = true;
      setTimeout(() => window.location.reload(), 100);
    },
    onError: (errors) => {
      if (errors.error) {
        alert(errors.error);
      }
    }
  });
};

const props = defineProps({
  student: {
    type: Object,
    required: true
  }
});

// State for modal
const showIdModal = ref(false);

// Return reason state
const showReturnReason = ref(false);
const returnReason = ref('');

// Submit return function
const submitReturn = () => {
  router.post(route('student.return', { id: props.student.id }), {
    message: returnReason.value
  }, {
    preserveScroll: true,
    onSuccess: () => {
      showReturnReason.value = false;
      showIdModal.value = false;
      setTimeout(() => window.location.reload(), 100);
    },
    onError: (errors) => {
      if (errors.error) {
        alert(errors.error);
      }
    }
  });
};
</script>
