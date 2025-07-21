<script setup>
import { ref ,computed} from 'vue';

const registrationStats = ref({
  totalRegistered: '৭৫,৬৭৮',
  pendingApproval: '৩,৪৫৬',
  approved: '৭২,২২২',
  rejected: '৯৮৭'
});

const recentRegistrations = ref([
  {
    id: 'REG-7845',
    madrasaName: 'দারুল উলুম মাদরাসা',
    madrasaCode: 'DUM-1234',
    studentCount: '৪৫',
    date: '১০ জুলাই, ২০২৩',
    status: 'pending'
  },
  {
    id: 'REG-7844',
    madrasaName: 'জামিয়া ইসলামিয়া',
    madrasaCode: 'JIM-5678',
    studentCount: '৭৮',
    date: '০৯ জুলাই, ২০২৩',
    status: 'approved'
  },
  {
    id: 'REG-7843',
    madrasaName: 'মাদরাসা আল-আরাবিয়া',
    madrasaCode: 'MAA-9012',
    studentCount: '৬৩',
    date: '০৯ জুলাই, ২০২৩',
    status: 'approved'
  },
  {
    id: 'REG-7842',
    madrasaName: 'নূরানী মাদরাসা',
    madrasaCode: 'NUR-3456',
    studentCount: '৩২',
    date: '০৮ জুলাই, ২০২৩',
    status: 'rejected'
  },
  {
    id: 'REG-7841',
    madrasaName: 'হাফেজিয়া মাদরাসা',
    madrasaCode: 'HAF-7890',
    studentCount: '৫৫',
    date: '০৮ জুলাই, ২০২৩',
    status: 'approved'
  }
]);

const madrasaPerformance = ref([
  {
    name: 'দারুল উলুম মাদরাসা',
    code: 'DUM-1234',
    passRate: '৯৫%',
    students: '১২৫',
    trend: 'up'
  },
  {
    name: 'জামিয়া ইসলামিয়া',
    code: 'JIM-5678',
    passRate: '৯২%',
    students: '১৮৭',
    trend: 'up'
  },
  {
    name: 'মাদরাসা আল-আরাবিয়া',
    code: 'MAA-9012',
    passRate: '৮৮%',
    students: '১৫৬',
    trend: 'down'
  },
  {
    name: 'নূরানী মাদরাসা',
    code: 'NUR-3456',
    passRate: '৯০%',
    students: '১১২',
    trend: 'up'
  },
  {
    name: 'হাফেজিয়া মাদরাসা',
    code: 'HAF-7890',
    passRate: '৮৫%',
    students: '১৪৫',
    trend: 'down'
  }
]);

const selectedTab = ref('registrations');
const regSearch = ref('');
const perfSearch = ref('');

const filteredRegistrations = computed(() =>
  !regSearch.value
    ? recentRegistrations.value
    : recentRegistrations.value.filter(
        reg =>
          reg.id.includes(regSearch.value) ||
          reg.madrasaName.includes(regSearch.value) ||
          reg.madrasaCode.includes(regSearch.value)
      )
);

const filteredPerformance = computed(() =>
  !perfSearch.value
    ? madrasaPerformance.value
    : madrasaPerformance.value.filter(
        m =>
          m.name.includes(perfSearch.value) ||
          m.code.includes(perfSearch.value)
      )
);

const regStatusBadge = status => {
  if (status === 'pending') return 'bg-yellow-100 text-yellow-800 border-yellow-300';
  if (status === 'approved') return 'bg-green-100 text-green-800 border-green-300';
  return 'bg-red-100 text-red-800 border-red-300';
};
</script>

<template>
  <div class="rounded-2xl overflow-hidden shadow-xl border border-emerald-100 bg-gradient-to-br from-emerald-50 via-white to-emerald-100">
    <!-- Header -->
    <div class="bg-emerald-50/80 px-8 py-6 border-b border-emerald-100 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
      <div class="flex items-center gap-3">
        <div class="p-3 rounded-full bg-emerald-100 shadow-sm">
          <i class="fas fa-chart-line text-emerald-700 text-xl"></i>
        </div>
        <h3 class="text-2xl font-bold text-emerald-900 tracking-wide">রেজিস্ট্রেশন আপডেট</h3>
      </div>
      <div class="flex gap-2">
        <button
          @click="selectedTab = 'registrations'"
          class="px-5 py-2 rounded-lg font-semibold text-sm border transition"
          :class="selectedTab === 'registrations'
            ? 'bg-emerald-600 text-white border-emerald-700 shadow'
            : 'text-emerald-700 bg-white border-emerald-200 hover:bg-emerald-50'"
        >
          রেজিস্ট্রেশন
        </button>
        <button
          @click="selectedTab = 'performance'"
          class="px-5 py-2 rounded-lg font-semibold text-sm border transition"
          :class="selectedTab === 'performance'
            ? 'bg-emerald-600 text-white border-emerald-700 shadow'
            : 'text-emerald-700 bg-white border-emerald-200 hover:bg-emerald-50'"
        >
          মাদরাসা পারফরম্যান্স
        </button>
      </div>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-5 px-8 pt-8 mb-7">
      <div class="bg-indigo-50 rounded-xl p-5 flex flex-col items-center shadow-sm border border-indigo-100">
        <i class="fas fa-users text-indigo-500 text-2xl mb-2"></i>
        <p class="text-xs text-indigo-700 font-semibold">মোট রেজিস্ট্রেশন</p>
        <h4 class="text-2xl font-extrabold text-indigo-800 mt-1">{{ registrationStats.totalRegistered }}</h4>
      </div>
      <div class="bg-yellow-50 rounded-xl p-5 flex flex-col items-center shadow-sm border border-yellow-100">
        <i class="fas fa-hourglass-half text-yellow-500 text-2xl mb-2"></i>
        <p class="text-xs text-yellow-700 font-semibold">অপেক্ষমান</p>
        <h4 class="text-2xl font-extrabold text-yellow-800 mt-1">{{ registrationStats.pendingApproval }}</h4>
      </div>
      <div class="bg-green-50 rounded-xl p-5 flex flex-col items-center shadow-sm border border-green-100">
        <i class="fas fa-check-circle text-green-500 text-2xl mb-2"></i>
        <p class="text-xs text-green-700 font-semibold">অনুমোদিত</p>
        <h4 class="text-2xl font-extrabold text-green-800 mt-1">{{ registrationStats.approved }}</h4>
      </div>
      <div class="bg-red-50 rounded-xl p-5 flex flex-col items-center shadow-sm border border-red-100">
        <i class="fas fa-times-circle text-red-500 text-2xl mb-2"></i>
        <p class="text-xs text-red-700 font-semibold">বাতিলকৃত</p>
        <h4 class="text-2xl font-extrabold text-red-800 mt-1">{{ registrationStats.rejected }}</h4>
      </div>
    </div>

    <!-- Registration Tab -->
    <div v-if="selectedTab === 'registrations'" class="px-8 pb-8 animate-fade-in">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-3">
        <h4 class="text-lg font-bold text-emerald-800 flex items-center gap-2">
          <i class="fas fa-clipboard-list text-emerald-600"></i>
          সাম্প্রতিক রেজিস্ট্রেশনসমূহ
        </h4>
        <div class="relative w-full md:w-64">
          <input
            v-model="regSearch"
            type="text"
            class="w-full rounded-md border border-emerald-200 bg-white/90 py-2 px-10 text-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition"
            placeholder="মাদরাসা/কোড/রেজিস্ট্রেশন আইডি..."
          >
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-emerald-400">
            <i class="fas fa-search"></i>
          </span>
        </div>
      </div>
      <div class="overflow-x-auto rounded-xl border border-emerald-100 bg-white/90">
        <table class="min-w-full divide-y divide-emerald-100">
          <thead class="bg-emerald-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-bold text-emerald-700 uppercase tracking-wider">রেজিস্ট্রেশন আইডি</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-emerald-700 uppercase tracking-wider">মাদরাসা</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-emerald-700 uppercase tracking-wider">ছাত্র সংখ্যা</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-emerald-700 uppercase tracking-wider">তারিখ</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-emerald-700 uppercase tracking-wider">অবস্থা</th>
              <th class="px-6 py-3 text-right text-xs font-bold text-emerald-700 uppercase tracking-wider">অ্যাকশন</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-emerald-50">
            <tr v-for="reg in filteredRegistrations" :key="reg.id" class="hover:bg-emerald-50/60">
              <td class="px-6 py-4 whitespace-nowrap font-semibold text-emerald-900">{{ reg.id }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-base text-emerald-900 font-bold">{{ reg.madrasaName }}</div>
                <div class="text-xs text-emerald-500">{{ reg.madrasaCode }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-emerald-700">{{ reg.studentCount }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-emerald-700">{{ reg.date }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2.5 py-1 inline-flex text-xs font-semibold rounded-full border"
                  :class="regStatusBadge(reg.status)">
                  {{ reg.status === 'pending' ? 'অপেক্ষমান' :
                     reg.status === 'approved' ? 'অনুমোদিত' : 'বাতিলকৃত' }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right">
                <button class="text-emerald-600 hover:text-emerald-900 mr-3 font-semibold">দেখুন</button>
                <button v-if="reg.status === 'pending'" class="text-green-600 hover:text-green-800 font-semibold">অনুমোদন</button>
              </td>
            </tr>
            <tr v-if="!filteredRegistrations.length">
              <td colspan="6" class="px-6 py-8 text-center text-emerald-500 text-base">কোনো রেজিস্ট্রেশন পাওয়া যায়নি</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Performance Tab -->
    <div v-if="selectedTab === 'performance'" class="px-8 pb-8 animate-fade-in">
      <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-3">
        <h4 class="text-lg font-bold text-emerald-800 flex items-center gap-2">
          <i class="fas fa-school text-emerald-600"></i>
          মাদরাসা পারফরম্যান্স
        </h4>
        <div class="relative w-full md:w-64">
          <input
            v-model="perfSearch"
            type="text"
            class="w-full rounded-md border border-emerald-200 bg-white/90 py-2 px-10 text-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition"
            placeholder="মাদরাসা/কোড..."
          >
          <span class="absolute left-3 top-1/2 -translate-y-1/2 text-emerald-400">
            <i class="fas fa-search"></i>
          </span>
        </div>
      </div>
      <div class="overflow-x-auto rounded-xl border border-emerald-100 bg-white/90">
        <table class="min-w-full divide-y divide-emerald-100">
          <thead class="bg-emerald-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-bold text-emerald-700 uppercase tracking-wider">মাদরাসা</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-emerald-700 uppercase tracking-wider">পাস রেট</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-emerald-700 uppercase tracking-wider">ছাত্র সংখ্যা</th>
              <th class="px-6 py-3 text-left text-xs font-bold text-emerald-700 uppercase tracking-wider">ট্রেন্ড</th>
              <th class="px-6 py-3 text-right text-xs font-bold text-emerald-700 uppercase tracking-wider">অ্যাকশন</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-emerald-50">
            <tr v-for="madrasa in filteredPerformance" :key="madrasa.code" class="hover:bg-emerald-50/60">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-base font-bold text-emerald-900">{{ madrasa.name }}</div>
                <div class="text-xs text-emerald-500">{{ madrasa.code }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-emerald-700 font-bold">{{ madrasa.passRate }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-emerald-700">{{ madrasa.students }}</td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div v-if="madrasa.trend === 'up'" class="flex items-center gap-1 text-green-600 font-bold">
                  <i class="fas fa-arrow-up"></i> উর্ধ্বমুখী
                </div>
                <div v-else class="flex items-center gap-1 text-red-600 font-bold">
                  <i class="fas fa-arrow-down"></i> নিম্নমুখী
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right">
                <button class="text-emerald-600 hover:text-emerald-900 font-semibold">বিস্তারিত</button>
              </td>
            </tr>
            <tr v-if="!filteredPerformance.length">
              <td colspan="5" class="px-6 py-8 text-center text-emerald-500 text-base">কোনো তথ্য পাওয়া যায়নি</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="bg-emerald-50/80 px-6 py-4 text-right border-t border-emerald-100">
      <button class="text-sm text-emerald-700 hover:text-emerald-900 font-semibold flex items-center gap-1">
        <i class="fas fa-list-ul"></i>
        সকল তথ্য দেখুন
      </button>
    </div>
  </div>
</template>

<style scoped>
@keyframes fade-in {
  from { opacity: 0; transform: translateY(10px);}
  to { opacity: 1; transform: translateY(0);}
}
.animate-fade-in {
  animation: fade-in .3s;
}
</style>
