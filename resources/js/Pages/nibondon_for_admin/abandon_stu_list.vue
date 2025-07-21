<template>
  <AuthenticatedLayout>
    <div class="py-12">
      <div class=" sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <!-- Header Section -->
            <div class="mb-6">
              <h1 class="text-2xl font-bold text-gray-800 flex items-center gap-3">
                মারকাজ ভিত্তিক মাদরাসার তালিকা
              </h1>
              <p v-if="totalStudents" class="mt-2 text-gray-600">
                মোট ছাত্র সংখ্যা: <span class="font-semibold">{{ totalStudents }}</span>
              </p>
            </div>
            <!-- Results Table -->
            <div class="bg-white overflow-hidden border-b border-gray-200 sm:rounded-lg">
              <div class="bg-gray-100 px-4 py-3 flex justify-between items-center border-b border-gray-200">
                <h2 class="text-lg font-medium text-gray-700 flex items-center gap-2">
                  <i class="fas fa-list"></i>
                  মাদরাসা তালিকা
                </h2>
                <div class="text-gray-700">মোট উপাত্ত: {{ madrashaList.length || 0 }}</div>
              </div>
              <div v-if="loading" class="p-6 text-center">
                <div class="flex justify-center">
                  <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-gray-700"></div>
                </div>
                <p class="mt-2 text-gray-600">ডাটা লোড হচ্ছে...</p>
              </div>
              <div v-else-if="error" class="p-6 text-center text-red-500">
                <p>{{ error }}</p>
              </div>
              <div v-else class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead>
                    <tr>
                      <th v-for="header in tableHeaders" :key="header.id"
                        class="px-6 py-3 bg-gray-50 text-left text-md  text-gray-500 uppercase tracking-wider">
                        {{ header.label }}
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="(madrasha, index) in madrashaList" :key="madrasha.id"
                      class="hover:bg-gray-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ index + 1 }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="font-medium">{{ madrasha.student_count || 0 }}</span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <Link
                          :href="route('nibondon_for_admin.madrashaWari_stu_nibond_list', madrasha.id)"
                          class="inline-flex items-center justify-center p-1 rounded text-blue-600 hover:text-blue-900 hover:underline"
                          title="View Details"
                        >
                          {{ madrasha.name }}
                        </Link>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ madrasha.Elhaq_no }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ madrasha.id }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ madrasha.mobile_no }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full"
                          :class="getStatusClass(madrasha.student_count)">
                          {{ getStatusText(madrasha.student_count) }}
                        </span>
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        <SplitButton
                          label="একশন"
                          icon="pi pi-cog"
                          :model="getActionItems(madrasha)"
                          class="p-button-sm p-button-outlined p-button-secondary"
                        />
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { Link } from '@inertiajs/vue3';
import SplitButton from 'primevue/splitbutton';

// Table Headers
const tableHeaders = [
  { id: 1, label: "ক্রমিক" },
  { id: 2, label: "ছাত্র সংখ্যা" },
  { id: 3, label: "মাদরাসার নাম" },
  { id: 4, label: "এলহাক নম্বর" },
  { id: 5, label: "আইডি" },
  { id: 6, label: "মোবাইল নম্বর" },
  { id: 7, label: "অবস্থা" },
  { id: 8, label: "অ্যাকশন" }
];

const props = defineProps({
  markaz_id: {
    type: [Number, String],
    required: true
  }
});

// স্টেট ভেরিয়েবল
const madrashaList = ref([]);
const markazName = ref('');
const totalStudents = ref(0);
const loading = ref(true);
const error = ref(null);

// ডাটা ফেচ করার ফাংশন
const fetchData = async () => {
  try {
    loading.value = true;
    error.value = null;
    console.log('Fetching data for markaz_id:', props.markaz_id);
    const response = await axios.get(`/api/markaz-madrasa-list/${props.markaz_id}`);
    console.log('Response:', response.data);
    madrashaList.value = response.data.madrashaList;
    markazName.value = response.data.markazName;
    totalStudents.value = response.data.totalStudents;
  } catch (err) {
    console.error('Error fetching data:', err);
    error.value = 'ডাটা লোড করতে সমস্যা হয়েছে। পুনরায় চেষ্টা করুন।';
  } finally {
    loading.value = false;
  }
};

// Get status class based on student count
const getStatusClass = (count) => {
  if (!count) return 'bg-red-100 text-red-800';
  if (count < 10) return 'bg-yellow-100 text-yellow-800';
  return 'bg-green-100 text-green-800';
};

// Get status text based on student count
const getStatusText = (count) => {
  if (!count) return 'কোন ছাত্র নেই';
  if (count < 10) return 'কম ছাত্র';
  return 'সক্রিয়';
};

// Action items for SplitButton
const getActionItems = (madrasha) => {
  return [
    {
      label: 'বিস্তারিত দেখুন',
      icon: 'pi pi-eye',
      command: () => viewApplication(madrasha)
    },
    {
      label: 'মেসেজ পাঠান',
      icon: 'pi pi-envelope',
      command: () => editApplication(madrasha)
    },
    {
      label: 'প্রিন্ট করুন',
      icon: 'pi pi-print',
      command: () => printMadrasha(madrasha)
    }
  ];
};

// Action functions
const viewApplication = (madrasha) => {
  console.log('View madrasha:', madrasha);
  // Implement view functionality
};

const editApplication = (madrasha) => {
  console.log('Edit madrasha:', madrasha);
  // Implement edit functionality
};

const printMadrasha = (madrasha) => {
  console.log('Print madrasha:', madrasha);
  // Implement print functionality
};

// কম্পোনেন্ট মাউন্ট হওয়ার সময় ডাটা ফেচ করা
onMounted(() => {
  fetchData();
});
</script>

<style scoped>
/* Custom styling for PrimeVue SplitButton */
</style>
