<template>
  <AuthenticatedLayout>
    <div class="py-12 min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-emerald-100">
      <div class="mx-auto px-2 sm:px-6 lg:px-8">
        <div class="bg-white/90 shadow-2xl rounded-2xl border border-emerald-200 p-8 relative overflow-hidden">
          <!-- Decorative SVG background -->
          <div class="absolute left-0 top-0 w-56 h-56 opacity-10 pointer-events-none z-0">
            <svg viewBox="0 0 200 200" fill="none">
              <ellipse cx="100" cy="100" rx="90" ry="80" fill="#059669"/>
              <ellipse cx="100" cy="100" rx="70" ry="60" fill="#10b981" fill-opacity="0.6"/>
            </svg>
          </div>
          <div class="absolute right-0 bottom-0 w-72 h-52 opacity-10 pointer-events-none z-0">
            <svg viewBox="0 0 300 200" fill="none">
              <rect x="180" y="100" width="100" height="80" rx="30" fill="#059669" />
              <circle cx="230" cy="140" r="35" fill="#10b981" fill-opacity="0.5"/>
            </svg>
          </div>
          <!-- Title -->
          <div class="text-center mb-10 relative z-10">
            <div class="inline-block px-7 py-2 bg-gradient-to-r from-emerald-600 via-teal-500 to-emerald-700 rounded-full shadow-sm mb-3 animate-fade-in">
              <h3 class="text-2xl md:text-3xl font-black text-white tracking-widest">মারহালা সাবজেক্ট ইনফরমেশন</h3>
            </div>
            <div class="mt-2 text-emerald-700 text-sm tracking-wide font-semibold">নতুন মারহালা ও কিতাব যুক্ত করুন অথবা তথ্য আপডেট করুন</div>
          </div>
          <form @submit.prevent="handleSubmit" class="relative z-10">
            <!-- Marhala Info -->
            <fieldset class="border border-emerald-200 rounded-xl p-6 mb-8 bg-white/50 shadow-sm">
              <legend class="font-semibold text-emerald-900 px-3 text-base">মারহালা তথ্য</legend>
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                  <label class="block text-emerald-800 text-sm font-bold mb-2">
                    মারহালা নাম (বাংলা)
                  </label>
                  <input
                    type="text"
                    v-model="formData.marhalaNameBn"
                    required
                    class="input-main input-large"
                  />
                </div>
                <div>
                  <label class="block text-emerald-800 text-sm font-bold mb-2">
                    মারহালা নাম (ইংরেজি)
                  </label>
                  <input
                    type="text"
                    v-model="formData.marhalaNameEn"
                    required
                    class="input-main input-large"
                  />
                </div>
                <div>
                  <label class="block text-emerald-800 text-sm font-bold mb-2">
                    মারহালা নাম (আরবি)
                  </label>
                  <input
                    type="text"
                    v-model="formData.marhalaNameAr"
                    required
                    class="input-main input-large font-arabic"
                  />
                </div>
              </div>
            </fieldset>

            <!-- Add Subject Button -->
            <div class="mb-5 flex justify-end">
              <button
                type="button"
                @click="addNewRow"
                class="flex items-center px-5 py-3 bg-gradient-to-r from-emerald-600 to-emerald-400 border border-emerald-500 rounded-lg font-semibold text-sm text-white uppercase tracking-widest hover:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:border-emerald-900 focus:ring ring-emerald-300 disabled:opacity-25 transition ease-in-out duration-150 shadow group"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 group-hover:scale-125 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                নতুন সাবজেক্ট
              </button>
            </div>

            <!-- Subjects Table -->
            <div class="overflow-x-auto rounded-xl bg-white/90 border border-emerald-100 shadow mb-8">
              <table class="min-w-full divide-y divide-emerald-100 table-auto">
                <thead class="bg-emerald-50">
                  <tr>
                    <th v-for="header in subjectHeaders" :key="header"
                        class="px-5 py-3 text-left text-sm font-bold text-emerald-800 uppercase tracking-wider">
                      {{ header }}
                    </th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(subject, index) in subjects" :key="index" class="hover:bg-emerald-50/40 transition">
                    <td class="px-3 py-3 whitespace-nowrap">
                      <input
                        type="text"
                        v-model="subjects[index].code"
                        required
                        class="input-table input-large"
                        placeholder="কোড"
                      />
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap">
                      <input
                        type="text"
                        v-model="subjects[index].nameBangla"
                        required
                        class="input-table input-large"
                        placeholder="বাংলা নাম"
                      />
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap">
                      <input
                        type="text"
                        v-model="subjects[index].nameEnglish"
                        required
                        class="input-table input-large"
                        placeholder="English Name"
                      />
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap">
                      <input
                        type="text"
                        v-model="subjects[index].nameArabic"
                        required
                        class="input-table input-large font-arabic"
                        placeholder="اسم الكتاب"
                      />
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap">
                      <div class="flex items-center gap-3 justify-center">
                        <label v-for="(typeLabel, typeValue) in subjectStatusLabels" :key="typeValue" class="flex items-center gap-2 cursor-pointer">
                          <span class="relative flex items-center">
                            <input
                              type="radio"
                              v-model="subjects[index].status"
                              :value="typeValue"
                              :id="`status_${typeValue}_${index}`"
                              class="sr-only peer"
                            />
                            <span
                              class="block w-5 h-5 rounded-full border-2 border-emerald-400 transition
                                    peer-checked:border-emerald-600 peer-checked:bg-emerald-500
                                    bg-white shadow-sm
                                    after:content-[''] after:block after:w-2.5 after:h-2.5 after:rounded-full after:mx-auto after:my-auto
                                    after:bg-white after:opacity-0 peer-checked:after:opacity-100 after:transition-all after:duration-200"
                            ></span>
                          </span>
                          <span class="text-xs text-emerald-700 font-semibold">{{ typeLabel }}</span>
                        </label>
                      </div>
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-right">
                      <button
                        type="button"
                        @click="removeRow(index)"
                        class="inline-flex items-center px-3 py-2 bg-gradient-to-br from-red-500 to-red-700 border-none rounded-lg font-bold text-sm text-white uppercase tracking-widest
                               shadow hover:scale-110 active:scale-95 focus:outline-none focus:ring-2 focus:ring-red-400 transition transform duration-150 group"
                        title="এই সাবজেক্ট মুছে ফেলুন"
                      >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        <span class="group-hover:underline">ডিলিট</span>
                      </button>
                    </td>
                  </tr>
                  <!-- Empty state when no subjects are available -->
                  <tr v-if="subjects.length === 0">
                    <td colspan="6" class="px-6 py-4 text-center text-sm text-emerald-500 font-semibold">
                      <span class="animate-pulse">কোন সাবজেক্ট যোগ করা হয়নি</span>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <div class="flex justify-end mt-8">
              <button
                type="submit"
                class="inline-flex items-center px-7 py-3 bg-gradient-to-r from-emerald-600 to-emerald-400 border border-emerald-500 rounded-xl font-bold text-lg text-white uppercase tracking-widest shadow hover:bg-emerald-700 active:bg-emerald-900 focus:outline-none focus:border-emerald-900 focus:ring ring-emerald-300 disabled:opacity-25 transition-all duration-200"
              >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7" />
                </svg>
                সংরক্ষণ করুন
              </button>
            </div>
          </form>
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

// Table headers and status labels for easy localization/customization
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
.input-main {
  @apply shadow appearance-none border rounded w-full py-4 px-5 text-emerald-900 bg-emerald-50 text-lg leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 transition;
}
.input-table {
  @apply shadow appearance-none border rounded w-full py-3 px-4 text-emerald-900 bg-emerald-50 text-base leading-tight focus:outline-none focus:ring-2 focus:ring-emerald-500 transition;
}
.input-large {
  font-size: 1.125rem !important; /* text-lg */
  padding-top: 1rem !important;
  padding-bottom: 1rem !important;
  padding-left: 1rem !important;
  padding-right: 1rem !important;
}
.font-arabic {
  font-family: 'Amiri', 'Scheherazade New', serif;
}
@keyframes fade-in {
  from { opacity: 0; transform: translateY(-10px);}
  to { opacity: 1; transform: translateY(0);}
}
.animate-fade-in {
  animation: fade-in 0.8s cubic-bezier(0.4,0,0.2,1);
}
</style>
