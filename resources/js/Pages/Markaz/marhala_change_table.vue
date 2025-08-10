<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref } from 'vue';


const items = ref([
  { id: 1, applicationNo: "12345", currentLevel: "তৃতীয় স্তর", currentMadrasa: "মারকাযুল কুরআন", date: "2024-02-10", joiningDate: "2024-03-01", status: "active" },
  { id: 2, applicationNo: "67890", currentLevel: "দ্বিতীয় স্তর", currentMadrasa: "জামিয়া ইসলামিয়া", date: "2024-01-15", joiningDate: "2024-02-05", status: "inactive" },
]);

const modalOpen = ref(false);
const modalTitle = ref("");
const modalMessage = ref("");
const confirmText = ref("");
let actionType = null;
let selectedItem = null;

const openModal = (type, item) => {
  selectedItem = item;
  actionType = type;
  modalOpen.value = true;

  if (type === "view") {
    modalTitle.value = "আবেদন দেখুন";
    modalMessage.value = `আপনি কি আবেদন নং ${item.applicationNo} দেখতে চান?`;
    confirmText.value = "ঠিক আছে";
  } else if (type === "edit") {
    modalTitle.value = "আবেদন সম্পাদনা করুন";
    modalMessage.value = `আপনি কি আবেদন নং ${item.applicationNo} সম্পাদনা করতে চান?`;
    confirmText.value = "সম্পাদনা করুন";
  } else if (type === "delete") {
    modalTitle.value = "আবেদন মুছুন";
    modalMessage.value = `আপনি কি আবেদন নং ${item.applicationNo} মুছতে চান?`;
    confirmText.value = "হ্যাঁ, মুছুন";
  }
};

const confirmAction = () => {
  modalOpen.value = false;
  console.log(`${actionType} confirmed for`, selectedItem);
};

  </script>


<template>
<AuthenticatedLayout>
    <div class="bg-white rounded-sm shadow-lg overflow-hidden mx-5 mt-5">
    <!-- Table Header with Islamic Pattern -->
    <div class="bg-gradient-to-r from-emerald-800 to-emerald-600 px-6 py-4  relative overflow-hidden">
      <div class="absolute inset-0 opacity-10 bg-gray-100"></div>
      <div class="flex justify-between items-center relative z-10">
        <h2 class="text-2xl font-bold text-white font-arabic">মারহালা আবেদন তালিকা</h2>
        <Link
          :href="route('Markaz.marhala_change')"
          class="px-5 py-2 bg-amber-500 hover:bg-amber-600 text-white rounded-sm transition-all duration-200 flex items-center gap-2 "
        >
          <span class="font-arabic">মারহালা পরিবর্তন ফরম</span>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd" />
          </svg>
        </Link>
      </div>
    </div>

    <!-- Table Container -->
    <div class="p-6 bg-gray-50 bg-opacity-5">
      <div class="overflow-x-auto">
        <table class="w-full border border-emerald-200 rounded-lg">
          <thead>
            <tr class="bg-gradient-to-r from-emerald-900 to-emerald-800 text-white">
              <th class="px-6 py-4 font-arabic">আবেদন নং</th>
              <th class="px-6 py-4 font-arabic">বর্তমান স্তর</th>
              <th class="px-6 py-4 font-arabic">বর্তমান মাদরাসা</th>
              <th class="px-6 py-4 font-arabic">তারিখ</th>
              <th class="px-6 py-4 font-arabic">জয়েনিং তারিক</th>
              <th class="px-6 py-4 font-arabic">স্টাটাস</th>
              <th class="px-6 py-4 font-arabic">একশন</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="item in items" :key="item.id" class="border-b hover:bg-emerald-50 transition-all duration-200">
              <td class="px-6 py-4 text-center">{{ item.applicationNo }}</td>
              <td class="px-6 py-4 text-center">{{ item.currentLevel }}</td>
              <td class="px-6 py-4 text-center">{{ item.currentMadrasa }}</td>
              <td class="px-6 py-4 text-center">{{ item.date }}</td>
              <td class="px-6 py-4 text-center">{{ item.joiningDate }}</td>
              <td class="px-6 py-4 text-center">
                <span class="px-4 py-1.5 text-sm font-medium rounded-sm"
                      :class="{
                        'bg-emerald-100 text-emerald-800': item.status === 'active',
                        'bg-red-100 text-red-800': item.status === 'inactive'
                      }">
                  {{ item.status }}
                </span>
              </td>
              <td class="px-6 py-4">
                <div class="flex justify-center gap-3">
                  <button @click="openModal('view', item)"
                          class="p-2 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all duration-200">
                    <i class="fas fa-eye"></i>
                  </button>
                  <button @click="openModal('edit', item)"
                          class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-all duration-200">
                    <i class="fas fa-edit"></i>
                  </button>
                  <button @click="openModal('delete', item)"
                          class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all duration-200">
                    <i class="fas fa-trash-alt"></i>
                  </button>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Modal -->
    <div v-if="modalOpen" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center">
      <div class="bg-white rounded-sm shadow-xl w-96 p-6">
        <h2 class="text-xl font-bold text-emerald-800">{{ modalTitle }}</h2>
        <p class="mt-2 text-gray-600">{{ modalMessage }}</p>
        <div class="mt-4 flex justify-end gap-3">
          <button @click="modalOpen = false" class="px-4 py-2 bg-gray-200 rounded-md">বাতিল</button>
          <button @click="confirmAction" class="px-4 py-2 bg-emerald-600 text-white rounded-md">{{ confirmText }}</button>
        </div>
      </div>
    </div>
  </div>
</AuthenticatedLayout>

  </template>


