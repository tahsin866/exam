<script setup>
import { ref } from 'vue';

const examCenters = ref([
  {
    id: 1,
    name: 'দারুল উলুম মাদরাসা কেন্দ্র',
    location: 'ঢাকা',
    capacity: '৫০০',
    assignedStudents: '৪৮৫',
    status: 'active'
  },
  {
    id: 2,
    name: 'জামিয়া ইসলামিয়া কেন্দ্র',
    location: 'চট্টগ্রাম',
    capacity: '৪৫০',
    assignedStudents: '৪২০',
    status: 'active'
  },
  {
    id: 3,
    name: 'মাদরাসা আল-আরাবিয়া কেন্দ্র',
    location: 'সিলেট',
    capacity: '৩৫০',
    assignedStudents: '৩৪০',
    status: 'active'
  },
  {
    id: 4,
    name: 'নূরানী মাদরাসা কেন্দ্র',
    location: 'রাজশাহী',
    capacity: '৪০০',
    assignedStudents: '৩৮০',
    status: 'active'
  },
  {
    id: 5,
    name: 'হাফেজিয়া মাদরাসা কেন্দ্র',
    location: 'খুলনা',
    capacity: '৩০০',
    assignedStudents: '২৮৫',
    status: 'active'
  }
]);

const centerSummary = ref({
  totalCenters: '৩৪৫',
  totalCapacity: '১,৫০,০০০',
  assignedStudents: '১,৪৫,৬৭৮',
  remainingCapacity: '৪,৩২২'
});

const selectedCenter = ref(null);

const showCenterDetails = (center) => {
  selectedCenter.value = center;
};

const closeCenterDetails = () => {
  selectedCenter.value = null;
};
</script>

<template>
  <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden mb-6">
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-100">
      <h3 class="text-lg font-semibold text-gray-800 flex items-center">
        <i class="fas fa-building mr-2 text-gray-600"></i>
        পরীক্ষা কেন্দ্র ব্যবস্থাপনা
      </h3>
    </div>

    <div class="p-6">
      <!-- Center Summary -->
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-50 rounded-lg p-4">
          <p class="text-xs text-blue-700 font-medium">মোট কেন্দ্র</p>
          <h4 class="text-xl font-bold text-blue-800 mt-1">{{ centerSummary.totalCenters }}</h4>
        </div>
        <div class="bg-purple-50 rounded-lg p-4">
          <p class="text-xs text-purple-700 font-medium">মোট ধারণক্ষমতা</p>
          <h4 class="text-xl font-bold text-purple-800 mt-1">{{ centerSummary.totalCapacity }}</h4>
        </div>
        <div class="bg-green-50 rounded-lg p-4">
          <p class="text-xs text-green-700 font-medium">বরাদ্দকৃত পরীক্ষার্থী</p>
          <h4 class="text-xl font-bold text-green-800 mt-1">{{ centerSummary.assignedStudents }}</h4>
        </div>
        <div class="bg-amber-50 rounded-lg p-4">
          <p class="text-xs text-amber-700 font-medium">অবশিষ্ট ধারণক্ষমতা</p>
          <h4 class="text-xl font-bold text-amber-800 mt-1">{{ centerSummary.remainingCapacity }}</h4>
        </div>
      </div>

      <!-- Centers Table -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                কেন্দ্রের নাম
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                অবস্থান
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                ধারণক্ষমতা
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                বরাদ্দকৃত পরীক্ষার্থী
              </th>
              <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                অবস্থা
              </th>
              <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                অ্যাকশন
              </th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="center in examCenters" :key="center.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">{{ center.name }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-500">{{ center.location }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-500">{{ center.capacity }}</div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-500">{{ center.assignedStudents }}</div>
                <div class="w-full bg-gray-200 rounded-full h-1.5 mt-1">
                  <div class="bg-green-600 h-1.5 rounded-full" :style="`width: ${(parseInt(center.assignedStudents.replace(/,/g, '')) / parseInt(center.capacity.replace(/,/g, '')) * 100)}%`"></div>
                </div>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                  সক্রিয়
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                <button @click="showCenterDetails(center)" class="text-indigo-600 hover:text-indigo-900">বিস্তারিত</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <div class="bg-gray-50 px-4 py-3 text-right">
      <button class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
        সকল কেন্দ্র দেখুন
      </button>
    </div>

    <!-- Center Details Modal -->
    <div v-if="selectedCenter" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
          <h3 class="text-lg font-semibold text-gray-800">{{ selectedCenter.name }}</h3>
          <button @click="closeCenterDetails" class="text-gray-400 hover:text-gray-500">
            <i class="fas fa-times"></i>
          </button>
        </div>
        <div class="px-6 py-4">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <p class="text-sm text-gray-500">অবস্থান</p>
              <p class="text-base font-medium">{{ selectedCenter.location }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">ধারণক্ষমতা</p>
              <p class="text-base font-medium">{{ selectedCenter.capacity }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">বরাদ্দকৃত পরীক্ষার্থী</p>
              <p class="text-base font-medium">{{ selectedCenter.assignedStudents }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">অবস্থা</p>
              <p class="text-base font-medium">সক্রিয়</p>
            </div>
          </div>

          <div class="mt-4">
            <h4 class="text-sm font-medium text-gray-700 mb-2">ধারণক্ষমতা ব্যবহার</h4>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
              <div class="bg-green-600 h-2.5 rounded-full" :style="`width: ${(parseInt(selectedCenter.assignedStudents.replace(/,/g, '')) / parseInt(selectedCenter.capacity.replace(/,/g, '')) * 100)}%`"></div>
            </div>
            <div class="flex justify-between mt-1">
              <span class="text-xs text-gray-500">{{ selectedCenter.assignedStudents }} বরাদ্দকৃত</span>
              <span class="text-xs text-gray-500">{{ selectedCenter.capacity }} মোট</span>
            </div>
          </div>

          <div class="mt-6">
            <h4 class="text-sm font-medium text-gray-700 mb-2">কেন্দ্র সুপারভাইজার</h4>
            <div class="flex items-center">
              <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                এম
              </div>
              <div class="ml-3">
                <p class="text-sm font-medium text-gray-900">মাওলানা আব্দুল করিম</p>
                <p class="text-xs text-gray-500">০১৭১২-৩৪৫৬৭৮</p>
              </div>
            </div>
          </div>
        </div>
        <div class="px-6 py-3 bg-gray-50 text-right rounded-b-lg">
          <button class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 text-sm font-medium mr-2">
            সম্পাদনা করুন
          </button>
          <button @click="closeCenterDetails" class="px-4 py-2 bg-gray-200 text-gray-700 rounded hover:bg-gray-300 text-sm font-medium">
            বন্ধ করুন
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
