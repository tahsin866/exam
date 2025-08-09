<template>
  <AuthenticatedLayout>
    <div class="py-12 bg-gray-50">
      <div class=" mx-auto px-4 sm:px-8">
        <div class="bg-white shadow-lg rounded-2xl border border-gray-200">
          <div class="px-8 py-10">
            <!-- Title -->
            <div class="mb-10">
              <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-1">
                মারহালা সাবজেক্ট ইনফরমেশন
              </h1>
              <div class="text-gray-500 text-base font-medium">
                নতুন মারহালা ও কিতাব যুক্ত করুন অথবা তথ্য আপডেট করুন
              </div>
            </div>

            <form @submit.prevent="handleSubmit" class="space-y-10">
              <!-- Marhala Info -->
              <fieldset class="border border-gray-200 rounded-lg p-6 bg-gray-50">
                <legend class="font-semibold text-gray-700 text-base px-2">মারহালা তথ্য</legend>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-4">
                  <div>
                    <label class="block text-gray-800 text-sm font-semibold mb-2">
                      মারহালা নাম (বাংলা)
                    </label>
                    <input
                      type="text"
                      v-model="formData.marhalaNameBn"
                      required
                      class="block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-400 transition"
                    />
                  </div>
                  <div>
                    <label class="block text-gray-800 text-sm font-semibold mb-2">
                      মারহালা নাম (ইংরেজি)
                    </label>
                    <input
                      type="text"
                      v-model="formData.marhalaNameEn"
                      required
                      class="block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-400 transition"
                    />
                  </div>
                  <div>
                    <label class="block text-gray-800 text-sm font-semibold mb-2">
                      মারহালা নাম (আরবি)
                    </label>
                    <input
                      type="text"
                      v-model="formData.marhalaNameAr"
                      required
                      class="block w-full border border-gray-300 rounded-md px-3 py-2 text-gray-900 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-gray-400 transition font-arabic"
                    />
                  </div>
                </div>
              </fieldset>

              <!-- Add Subject Button -->
              <div class="flex justify-end">
                <button
                  type="button"
                  @click="addNewRow"
                  class="inline-flex items-center px-5 py-2 border border-gray-400 rounded-md font-semibold text-sm text-gray-800 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 transition shadow-sm"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                  </svg>
                  নতুন সাবজেক্ট
                </button>
              </div>

              <!-- Subjects Table -->
              <div class="overflow-x-auto rounded-lg bg-white border border-gray-200 shadow-sm">
                <table class="min-w-full divide-y divide-gray-100 table-auto">
                  <thead class="bg-gray-50">
                    <tr>
                      <th v-for="header in subjectHeaders" :key="header"
                          class="px-5 py-3 text-left text-xs font-bold text-gray-700 uppercase tracking-wider">
                        {{ header }}
                      </th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(subject, index) in subjects" :key="index" class="hover:bg-gray-50 transition">
                      <td class="px-3 py-2 whitespace-nowrap">
                        <input
                          type="text"
                          v-model="subjects[index].code"
                          required
                          class="block w-full border border-gray-300 rounded-md px-2 py-1.5 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm"
                          placeholder="কোড"
                        />
                      </td>
                      <td class="px-3 py-2 whitespace-nowrap">
                        <input
                          type="text"
                          v-model="subjects[index].nameBangla"
                          required
                          class="block w-full border border-gray-300 rounded-md px-2 py-1.5 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm"
                          placeholder="বাংলা নাম"
                        />
                      </td>
                      <td class="px-3 py-2 whitespace-nowrap">
                        <input
                          type="text"
                          v-model="subjects[index].nameEnglish"
                          required
                          class="block w-full border border-gray-300 rounded-md px-2 py-1.5 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm"
                          placeholder="English Name"
                        />
                      </td>
                      <td class="px-3 py-2 whitespace-nowrap">
                        <input
                          type="text"
                          v-model="subjects[index].nameArabic"
                          required
                          class="block w-full border border-gray-300 rounded-md px-2 py-1.5 text-gray-900 bg-white focus:outline-none focus:ring-2 focus:ring-gray-300 text-sm font-arabic"
                          placeholder="اسم الكتاب"
                        />
                      </td>
                      <td class="px-3 py-2 whitespace-nowrap">
                        <div class="flex items-center gap-3 justify-center">
                          <label v-for="(typeLabel, typeValue) in subjectStatusLabels" :key="typeValue" class="flex items-center gap-2 cursor-pointer">
                            <input
                              type="radio"
                              v-model="subjects[index].status"
                              :value="typeValue"
                              :id="`status_${typeValue}_${index}`"
                              class="h-4 w-4 text-gray-900 focus:ring-gray-500 border-gray-300"
                            />
                            <span class="text-xs text-gray-700 font-medium">{{ typeLabel }}</span>
                          </label>
                        </div>
                      </td>
                      <td class="px-3 py-2 whitespace-nowrap text-right">
                        <button
                          type="button"
                          @click="removeRow(index)"
                          class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-400 rounded-md text-xs text-gray-700 font-semibold uppercase hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-400 transition"
                          title="এই সাবজেক্ট মুছে ফেলুন"
                        >
                          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                          </svg>
                          ডিলিট
                        </button>
                      </td>
                    </tr>
                    <tr v-if="subjects.length === 0">
                      <td colspan="6" class="px-6 py-8 text-center text-sm text-gray-400 font-medium">
                        <span>কোন সাবজেক্ট যোগ করা হয়নি</span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="flex justify-end">
                <button
                  type="submit"
                  class="inline-flex items-center px-8 py-3 bg-gray-900 border border-gray-700 rounded-md font-bold text-base text-white uppercase tracking-widest shadow hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-gray-400 transition"
                >
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
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
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const formData = ref({
  marhalaNameBn: '',
  marhalaNameEn: '',
  marhalaNameAr: '',
});

const subjects = ref([]);

const subjectHeaders = [
  'সাবজেক্ট কোড',
  'কিতাব নাম (বাংলা)',
  'কিতাব নাম (ইংরেজি)',
  'কিতাব নাম (আরবি)',
  'স্ট্যাটাস',
  'অ্যাকশন'
];

const subjectStatusLabels = {
  SRtype_1: 'ছাত্র',
  SRtype_0: 'ছাত্রী',
  Both: 'উভয়'
};

const addNewRow = () => {
  subjects.value.push({
    code: '',
    nameBangla: '',
    nameEnglish: '',
    nameArabic: '',
    status: 'Both'
  });
};

const removeRow = (index) => {
  subjects.value.splice(index, 1);
};

const handleSubmit = () => {
  router.post(route('marhalas.store'), {
    ...formData.value,
    subjects: subjects.value
  });
};
</script>

<style scoped>
.font-arabic {
  font-family: 'Amiri', 'Scheherazade New', serif;
}
</style>