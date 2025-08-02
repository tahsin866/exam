<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import { ref, onMounted } from 'vue'

import PersonalInfo from '@/Pages/students_registration/components/PersonalInfoStep.vue'
import AddressInfo from '@/Pages/students_registration/components/AddressInfoStep.vue'
import AttachmentsInfo from '@/Pages/students_registration/components/AttachmentsInfo.vue'
import { useForm, router } from '@inertiajs/vue3'
import axios from 'axios'

const props = defineProps({
  roll: String,
  reg_id: String,
  CID: String,
  modelValue: Object
})


const currentStep = ref(1)
const examName = ref('')
const marhalaName = ref('')
const currentMarhalaId = ref(null)
const boardOptions = [
  { name: 'বেফাকুল মাদারিসিল আরাবিয়া বাংলাদেশ', value: 'বেফাকুল মাদারিসিল আরাবিয়া বাংলাদেশ' },
  { name: 'বেফাকুল মাদারিসিল কওমিয়া গওহরডাঙ্গা বাংলাদেশ', value: 'বেফাকুল মাদারিসিল কওমিয়া গওহরডাঙ্গা বাংলাদেশ' },
  { name: 'আযাদ দ্বীনি এদারায়ে তালীম বাংলাদেশ', value: 'আযাদ দ্বীনি এদারায়ে তালীম বাংলাদেশ' },
  { name: 'তানযীমুল মাদারিসিদ দ্বীনিয়া বাংলাদেশ', value: 'তানযীমুল মাদারিসিদ দ্বীনিয়া বাংলাদেশ' },
  { name: 'জাতীয় দ্বীনি মাদরাসা শিক্ষাবোর্ড বাংলাদেশ', value: 'জাতীয় দ্বীনি মাদরাসা শিক্ষাবোর্ড বাংলাদেশ' },
  { name: 'আঞ্জুমানে ইত্তেহাদুল মাদারিস বাংলাদেশ', value: 'আঞ্জুমানে ইত্তেহাদুল মাদারিস বাংলাদেশ' }
]

// Main form state (share to children via v-model)
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
  NID_attach: null,
  markaz_id: ''
})

// File refs for attachments
const studentPhotoFile = ref(null)
const birthCertificateFile = ref(null)
const marksheetFile = ref(null)

onMounted(async () => {
  const marhalaId = route().params.marhalaId
  currentMarhalaId.value = marhalaId
  try {
    const response = await axios.get(`/api/student-registration/${marhalaId}`)
    examName.value = response.data.examName
    marhalaName.value = response.data.marhalaName
    studentInfoForm.current_class = response.data.marhalaName
    studentInfoForm.marhala_id = marhalaId
  } catch (error) {
    console.error("Error fetching marhala info:", error)
  }
})

// Step navigation
const gotoStep = (step) => {
  currentStep.value = step
}

// Data update from children
const updatePersonalInfo = (info) => {
  Object.assign(studentInfoForm, info)
}
const updateAddressInfo = (info) => {
  Object.assign(studentInfoForm, info)
}
const updateAttachments = (files) => {
  studentPhotoFile.value = files.studentPhoto
  birthCertificateFile.value = files.birthCertificate
  marksheetFile.value = files.marksheet
}

// Submit main form
const submitStudentInfo = () => {
  if (studentPhotoFile.value) {
    studentInfoForm.student_image = studentPhotoFile.value
  }
  if (birthCertificateFile.value) {
    studentInfoForm.NID_attach = birthCertificateFile.value
  }

  const marhalaId = currentMarhalaId.value
  studentInfoForm.post(`/api/save-student-info_1/${marhalaId}`, {
    forceFormData: true,
    onSuccess: () => {
      toaster.success('ছাত্রের তথ্য সফলভাবে সংরক্ষণ করা হয়েছে', {
        position: "top-left",
        duration: 5000,
        className: "elegant-success-toast"
      })
    },
    onError: (errors) => {
      toaster.error('ছাত্রের তথ্য সংরক্ষণ করতে সমস্যা হয়েছে', {
        position: "top-left",
        duration: 5000,
        className: "elegant-error-toast"
      })
      console.error('Error saving student information:', errors)
    },
    preserveScroll: true
  })
}
</script>

<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class="sm:px-6 lg:px-8">
        <!-- Step indicator -->
        <div class="mb-6">
          <ul class="flex border-b">
            <li class="mr-1 -mb-px" :class="{ 'border-b-2 border-indigo-500': currentStep === 1 }">
              <a @click="gotoStep(1)" class="bg-white inline-block py-2 px-4 font-medium cursor-pointer"
                 :class="currentStep === 1 ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-800'">
                ব্যক্তিগত তথ্য
              </a>
            </li>
            <li class="mr-1" :class="{ 'border-b-2 border-indigo-500': currentStep === 2 }">
              <a @click="gotoStep(2)" class="bg-white inline-block py-2 px-4 font-medium cursor-pointer"
                 :class="currentStep === 2 ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-800'">
                প্রয়োজনীয় তথ্য ও ঠিকানা
              </a>
            </li>
            <li class="mr-1" :class="{ 'border-b-2 border-indigo-500': currentStep === 3 }">
              <a @click="gotoStep(3)" class="bg-white inline-block py-2 px-4 font-medium cursor-pointer"
                 :class="currentStep === 3 ? 'text-indigo-600' : 'text-gray-500 hover:text-gray-800'">
                সংযুক্তি
              </a>
            </li>
          </ul>
        </div>

        <PersonalInfo
          v-if="currentStep === 1"
          :marhalaName="marhalaName"
          v-model="studentInfoForm"
          @next="gotoStep(2)"
        />

        <AddressInfo
          v-if="currentStep === 2"
          :form="studentInfoForm"
          :boardOptions="boardOptions"
          v-model="studentInfoForm"
          @prev="gotoStep(1)"
          @next="gotoStep(3)"
        />

        <AttachmentsInfo
          v-if="currentStep === 3"
          :form="studentInfoForm"
          :boardOptions="boardOptions"
          @prev="gotoStep(2)"
          @submit="submitStudentInfo"
          @update-files="updateAttachments"
        />
      </div>
    </div>
  </AuthenticatedLayout>
</template>
