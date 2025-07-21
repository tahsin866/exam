<script setup>
import { ref, computed } from 'vue';

const notices = ref([
  {
    id: 1,
    title: 'দাওরায়ে হাদীস পরীক্ষার সময়সূচী প্রকাশিত হয়েছে',
    date: '১০ জুলাই, ২০২৩',
    priority: 'high',
    content: 'আগামী ১৫ আগস্ট থেকে দাওরায়ে হাদীস পরীক্ষা শুরু হবে। সকল মাদরাসাকে প্রস্তুতি নিতে অনুরোধ করা হচ্ছে।',
    attachment: 'exam_schedule_2023.pdf'
  },
  {
    id: 2,
    title: 'মারকাজ পরিদর্শন সংক্রান্ত বিজ্ঞপ্তি',
    date: '০৫ জুলাই, ২০২৩',
    priority: 'medium',
    content: 'আগামী মাসে সকল মারকাজ পরিদর্শন করা হবে। প্রয়োজনীয় ডকুমেন্ট প্রস্তুত রাখার জন্য অনুরোধ করা হচ্ছে।',
    attachment: null
  },
  {
    id: 3,
    title: 'নতুন পাঠ্যক্রম সংক্রান্ত সভা',
    date: '০১ জুলাই, ২০২৩',
    priority: 'low',
    content: 'আগামী ২০ জুলাই সকল মাদরাসার প্রধান শিক্ষকদের উপস্থিতিতে নতুন পাঠ্যক্রম নিয়ে আলোচনা সভা অনুষ্ঠিত হবে।',
    attachment: null
  }
]);

const upcomingExams = ref([
  {
    id: 1,
    name: 'দাওরায়ে হাদীস',
    date: '১৫ আগস্ট, ২০২৩',
    registeredStudents: '১২,৫৬৭',
    centers: '১৫৬',
    status: 'upcoming',
    statusText: 'আসন্ন',
    color: 'bg-green-100 text-green-800'
  },
  {
    id: 2,
    name: 'জামাত-ই-মুতাওয়াসসিতাহ',
    date: '২০ আগস্ট, ২০২৩',
    registeredStudents: '২৩,৪৫৬',
    centers: '২৩৪',
    status: 'upcoming',
    statusText: 'আসন্ন',
    color: 'bg-green-100 text-green-800'
  },
  {
    id: 3,
    name: 'জামাত-ই-সানাবিয়া',
    date: '২৫ আগস্ট, ২০২৩',
    registeredStudents: '৩৪,৫৬৭',
    centers: '২৮৯',
    status: 'upcoming',
    statusText: 'আসন্ন',
    color: 'bg-green-100 text-green-800'
  },
  {
    id: 4,
    name: 'জামাত-ই-আলিয়া',
    date: '০১ সেপ্টেম্বর, ২০২৩',
    registeredStudents: '১৮,৯৮৭',
    centers: '১৭৮',
    status: 'upcoming',
    statusText: 'আসন্ন',
    color: 'bg-green-100 text-green-800'
  }
]);

const selectedNotice = ref(null);

const showNoticeDetails = (notice) => {
  selectedNotice.value = notice;
};

const closeNoticeDetails = () => {
  selectedNotice.value = null;
};

const noticePriorityBadge = (priority) => {
  switch (priority) {
    case 'high': return 'bg-red-100 text-red-700 border-red-200';
    case 'medium': return 'bg-yellow-100 text-yellow-700 border-yellow-200';
    case 'low': return 'bg-blue-100 text-blue-700 border-blue-200';
    default: return 'bg-gray-100 text-gray-600 border-gray-200';
  }
};

// Exam search/filter feature (advance)
const examSearch = ref('');
const filteredExams = computed(() => {
  if (!examSearch.value) return upcomingExams.value;
  return upcomingExams.value.filter(
    e =>
      e.name.includes(examSearch.value) ||
      e.date.includes(examSearch.value) ||
      e.registeredStudents.includes(examSearch.value) ||
      e.centers.includes(examSearch.value)
  );
});
</script>

<template>
  <div class="grid grid-cols-1 lg:grid-cols-3 gap-7 mb-7">
    <!-- Notice Board -->
    <div class="lg:col-span-1">
      <div class="relative bg-gradient-to-br from-emerald-50 via-white to-emerald-100 rounded-2xl shadow-md border border-emerald-100 overflow-hidden hover:shadow-lg transition-shadow">
        <!-- Top border ornament -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-400 via-emerald-600 to-emerald-400"></div>
        <div class="bg-emerald-50/80 px-7 py-5 border-b border-emerald-100">
          <h3 class="text-xl font-extrabold text-emerald-900 flex items-center gap-2">
            <i class="fas fa-bullhorn text-emerald-700"></i>
            নোটিশ বোর্ড
          </h3>
        </div>
        <div class="divide-y divide-emerald-50 max-h-[320px] overflow-y-auto custom-scroll">
          <div
            v-for="notice in notices"
            :key="notice.id"
            class="group p-5 hover:bg-emerald-50/70 cursor-pointer transition-colors duration-200"
            @click="showNoticeDetails(notice)"
          >
            <div class="flex items-start gap-2">
              <span
                class="inline-flex items-center px-2.5 py-0.5 rounded-full border text-xs font-semibold"
                :class="noticePriorityBadge(notice.priority)"
              >
                <i
                  v-if="notice.priority === 'high'"
                  class="fas fa-exclamation-circle mr-1 text-red-500"
                ></i>
                <i
                  v-else-if="notice.priority === 'medium'"
                  class="fas fa-exclamation-triangle mr-1 text-yellow-500"
                ></i>
                <i
                  v-else
                  class="fas fa-info-circle mr-1 text-blue-500"
                ></i>
                {{
                  notice.priority === 'high'
                    ? 'জরুরি'
                    : notice.priority === 'medium'
                    ? 'গুরুত্বপূর্ণ'
                    : 'সাধারণ'
                }}
              </span>
              <div>
                <h4 class="text-base font-semibold text-emerald-900 group-hover:underline">
                  {{ notice.title }}
                </h4>
                <div class="flex items-center gap-1 mt-1 text-xs text-emerald-500">
                  <i class="fas fa-calendar-alt"></i>
                  <span>{{ notice.date }}</span>
                </div>
              </div>
            </div>
          </div>
          <div v-if="!notices.length" class="p-6 text-center text-emerald-600 text-sm">কোনো নোটিশ নেই</div>
        </div>
        <div class="bg-emerald-50/80 px-5 py-3 text-right">
          <button class="text-sm text-emerald-700 hover:text-emerald-900 font-semibold flex items-center gap-1">
            <i class="fas fa-list-ul"></i>
            সকল নোটিশ দেখুন
          </button>
        </div>
      </div>
    </div>

    <!-- Upcoming Exams -->
    <div class="lg:col-span-2">
      <div class="relative bg-gradient-to-br from-white via-emerald-50 to-white rounded-2xl shadow-md border border-emerald-100 overflow-hidden hover:shadow-lg transition-shadow">
        <!-- Top border ornament -->
        <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-400 via-emerald-600 to-emerald-400"></div>
        <div class="bg-emerald-50/80 px-7 py-5 border-b border-emerald-100 flex flex-col md:flex-row md:items-center md:justify-between gap-3">
          <h3 class="text-xl font-extrabold text-emerald-900 flex items-center gap-2">
            <i class="fas fa-calendar-alt text-emerald-700"></i>
            আসন্ন পরীক্ষাসমূহ
          </h3>
          <!-- Exam Search -->
          <div class="relative w-full md:w-64">
            <input
              v-model="examSearch"
              type="text"
              class="w-full rounded-md border border-emerald-200 bg-white/90 py-2 px-10 text-emerald-900 focus:outline-none focus:ring-2 focus:ring-emerald-400 transition"
              placeholder="পরীক্ষার নাম, তারিখ, কেন্দ্র..."
            >
            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-emerald-400">
              <i class="fas fa-search"></i>
            </span>
          </div>
        </div>
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-emerald-100">
            <thead class="bg-emerald-50">
              <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-emerald-700 uppercase tracking-wider">
                  পরীক্ষার নাম
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-emerald-700 uppercase tracking-wider">
                  তারিখ
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-emerald-700 uppercase tracking-wider">
                  রেজিস্ট্রেশন
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-emerald-700 uppercase tracking-wider">
                  কেন্দ্র
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-emerald-700 uppercase tracking-wider">
                  অবস্থা
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-emerald-50">
              <tr v-for="exam in filteredExams" :key="exam.id" class="hover:bg-emerald-50/60">
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center gap-2">
                    <i class="fas fa-graduation-cap text-emerald-400"></i>
                    <span class="text-base font-semibold text-emerald-900">{{ exam.name }}</span>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-base text-emerald-600">{{ exam.date }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-base text-emerald-700 font-semibold">{{ exam.registeredStudents }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-base text-emerald-800">{{ exam.centers }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-full" :class="exam.color">
                    {{ exam.statusText }}
                  </span>
                </td>
              </tr>
              <tr v-if="!filteredExams.length">
                <td colspan="5" class="px-6 py-6 text-center text-emerald-500 text-base">কোনো পরীক্ষা পাওয়া যায়নি</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="bg-emerald-50/80 px-5 py-3 text-right">
          <button class="text-sm text-emerald-700 hover:text-emerald-900 font-semibold flex items-center gap-1">
            <i class="fas fa-list-ul"></i>
            সকল পরীক্ষা দেখুন
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Notice Details Modal -->
  <transition name="fade">
    <div v-if="selectedNotice" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-2xl shadow-2xl border border-emerald-100 max-w-lg w-full relative animate-fade-in">
        <div class="px-7 py-5 border-b border-emerald-100 flex justify-between items-center bg-emerald-50/90 rounded-t-2xl">
          <div class="flex items-center gap-2">
            <span
              class="inline-flex items-center px-2.5 py-0.5 rounded-full border text-xs font-semibold"
              :class="noticePriorityBadge(selectedNotice.priority)"
            >
              <i
                v-if="selectedNotice.priority === 'high'"
                class="fas fa-exclamation-circle mr-1 text-red-500"
              ></i>
              <i
                v-else-if="selectedNotice.priority === 'medium'"
                class="fas fa-exclamation-triangle mr-1 text-yellow-500"
              ></i>
              <i
                v-else
                class="fas fa-info-circle mr-1 text-blue-500"
              ></i>
              {{
                selectedNotice.priority === 'high'
                  ? 'জরুরি'
                  : selectedNotice.priority === 'medium'
                  ? 'গুরুত্বপূর্ণ'
                  : 'সাধারণ'
              }}
            </span>
            <h3 class="text-lg font-bold text-emerald-800">{{ selectedNotice.title }}</h3>
          </div>
          <button @click="closeNoticeDetails" class="text-emerald-400 hover:text-emerald-700 rounded-full p-2 focus:outline-none">
            <i class="fas fa-times text-xl"></i>
          </button>
        </div>
        <div class="px-7 py-6">
          <p class="text-base text-emerald-900 mb-4">{{ selectedNotice.content }}</p>
          <div v-if="selectedNotice.attachment" class="mt-2">
            <a
              :href="`/notices/${selectedNotice.attachment}`"
              download
              class="inline-flex items-center text-xs font-semibold text-emerald-700 hover:text-emerald-900 bg-emerald-50 border border-emerald-200 rounded px-3 py-1 gap-1"
            >
              <i class="fas fa-paperclip"></i>
              সংযুক্তি ডাউনলোড
            </a>
          </div>
          <div class="text-xs text-emerald-500 mt-6">প্রকাশিত: {{ selectedNotice.date }}</div>
        </div>
        <div class="px-7 py-4 bg-emerald-50/80 text-right rounded-b-2xl">
          <button @click="closeNoticeDetails" class="px-6 py-2 bg-emerald-100 text-emerald-700 rounded-lg hover:bg-emerald-200 text-sm font-semibold transition">
            বন্ধ করুন
          </button>
        </div>
      </div>
    </div>
  </transition>
</template>

<style scoped>
.custom-scroll::-webkit-scrollbar {
  width: 5px;
  background: transparent;
}
.custom-scroll::-webkit-scrollbar-thumb {
  background: #e0f2f1;
  border-radius: 8px;
}
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.2s;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
@keyframes fade-in {
  from { opacity: 0; transform: scale(.98);}
  to { opacity: 1; transform: scale(1);}
}
.animate-fade-in { animation: fade-in .25s; }
</style>
