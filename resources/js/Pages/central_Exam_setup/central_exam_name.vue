<template>
  <AuthenticatedLayout>
    <div class="p-6 bg-gray-50 mx-5 mt-5">
      <Card class="mx-auto shadow-lg border-2 border-emerald-600">
        <template #title>
          <div class="text-center mb-4">
            <h3 class="text-2xl font-semibold text-emerald-700">কেন্দ্রীয় পরীক্ষা সেটাপ</h3>
          </div>
        </template>
        <template #content>
          <form @submit.prevent="handleSubmit" class="space-y-6">
            <!-- Exam Name Input -->
            <div class="field">
              <label for="examName" class="block text-emerald-700 mb-2 font-medium">পরীক্ষার নাম</label>
              <InputText
                id="examName"
                v-model="examName"
                class="w-full p-inputtext-lg"
                placeholder="পরীক্ষার নাম লিখুন"
              />
            </div>
            <!-- Year Inputs Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <!-- Arabic Year -->
              <div class="field">
                <label for="gregorianYear" class="block text-emerald-700 mb-2 font-medium">আরবি সন লিখুন (হিজরি)</label>
                <InputText
                  id="gregorianYear"
                  v-model="gregorianYear"
                  class="w-full"
                />
              </div>
              <!-- Bangla Year -->
              <div class="field">
                <label for="banglaYear" class="block text-emerald-700 mb-2 font-medium">বাংলা সন লিখুন (বঙ্গাব্দ)</label>
                <InputText
                  id="banglaYear"
                  v-model="banglaYear"
                  class="w-full"
                />
              </div>
              <!-- English Year -->
              <div class="field">
                <label for="hijriYear" class="block text-emerald-700 mb-2 font-medium">ইংরেজি সন লিখুন (ঈসাব্দ)</label>
                <InputText
                  id="hijriYear"
                  v-model="hijriYear"
                  class="w-full"
                />
              </div>
            </div>
            <div class="flex justify-end mt-6">
              <Button
                type="submit"
                label="সেভ করুন"
                icon="pi pi-check"
                class="p-button-success p-button-lg"
              />
            </div>
          </form>
        </template>
      </Card>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue'
import { ref } from 'vue'
import axios from 'axios'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Button from 'primevue/button'

const examName = ref('')
const gregorianYear = ref('')
const banglaYear = ref('')
const hijriYear = ref('')

const handleSubmit = async () => {
  try {
    const response = await axios.post('/api/exam-setups', {
      exam_name: examName.value,
      arabic_year: gregorianYear.value,
      bangla_year: banglaYear.value,
      english_year: hijriYear.value
    })
    if (response.status === 201) {
      // Clear form
      examName.value = ''
      gregorianYear.value = ''
      banglaYear.value = ''
      hijriYear.value = ''
      // Show success message
      alert('পরীক্ষা সেটাপ সফলভাবে সংরক্ষণ করা হয়েছে!')
    }
  } catch (error) {
    alert('দুঃখিত! কিছু সমস্যা হয়েছে। আবার চেষ্টা করুন।')
    console.error('Error:', error)
  }
}
</script>

<style>
/* Custom font for Arabic text */

</style>
