<template>
  <div>
    <!-- শিক্ষাগত যোগ্যতা -->
    <div class="bg-white p-6 rounded-md border border-gray-200 mb-8 shadow">
      <h3 class="text-lg font-semibold mb-4 text-gray-800">শিক্ষাগত যোগ্যতা</h3>
      <div v-for="(qualification, index) in form.educationalQualifications" :key="index" class="mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-4">
          <div>
            <label :for="`class-${index}`" class="block text-gray-700 mb-2">মারহালার নাম/ক্লাসের নাম</label>
            <Dropdown :id="`class-${index}`"
                      v-model="form.educationalQualifications[index].className"
                      :options="classOptions" placeholder="ক্লাস নির্বাচন করুন"
                      class="w-full" />
          </div>
          <div>
            <label :for="`passYear-${index}`" class="block text-gray-700 mb-2">পাসের সন</label>
            <InputText :id="`passYear-${index}`"
                      v-model="form.educationalQualifications[index].passYear"
                      class="w-full"
                      placeholder="পাসের সন" />
          </div>
          <div>
            <label :for="`result-${index}`" class="block text-gray-700 mb-2">ফলাফল (বিভাগ)</label>
            <InputText :id="`result-${index}`"
                      v-model="form.educationalQualifications[index].result"
                      class="w-full"
                      placeholder="ফলাফল (বিভাগ)" />
          </div>
          <div>
            <label :for="`institution-${index}`" class="block text-gray-700 mb-2">সমাপনি বোর্ড/মাদরাসা</label>
            <div class="flex gap-2">
              <InputText :id="`institution-${index}`"
                        v-model="form.educationalQualifications[index].institution"
                        class="w-full" placeholder="সমাপনি বোর্ড/মাদরাসা" />
              <button v-if="form.educationalQualifications.length > 1"
                      @click="$emit('remove-qualification', index)" type="button"
                      class="p-2 bg-red-500 text-white rounded hover:bg-red-600 transition"
                      title="রো মুছুন">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                     viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd"
                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                        clip-rule="evenodd" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </div>
      <div class="flex flex-col md:flex-row justify-between items-center mt-4 gap-2">
        <div class="md:w-1/4 w-full">
          <label for="teachingExperience" class="block text-gray-700 mb-2">শিক্ষকতার অভিজ্ঞতা</label>
          <InputText id="teachingExperience" v-model="form.teachers.teaching_experience"
                    class="w-full" placeholder="কত বছর" />
        </div>
        <Button type="button" @click="$emit('add-qualification')" label="আরো যোগ করুন"
                icon="pi pi-plus" severity="success"
                class="mt-2 md:mt-0 bg-blue-50 text-blue-600 border-blue-200 hover:bg-blue-100" />
      </div>
    </div>

    <!-- কাঙ্খিত কিতাব সেকশন -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white border border-gray-200 rounded-md p-6 mb-8 shadow">
      <div>
        <h5 class="text-base font-semibold mb-3 border-b border-gray-200 pb-2 text-gray-800">
          কাঙ্খিত কিতাব নির্বাচন
        </h5>
        <div class="relative">
          <Button type="button" @click="$emit('toggle-book-dropdown')"
                  class="w-full flex items-center justify-between px-4 py-2 border border-gray-300 rounded bg-gray-50 text-sm hover:bg-gray-100"
                  outlined>
            <span>ক্যাটাগরি ও কিতাব নির্বাচন</span>
            <i class="pi pi-chevron-down"></i>
          </Button>
          <!-- স্ক্রল যুক্ত -->
          <div v-if="showBookDropdown"
               class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-lg max-h-72 overflow-y-auto">
            <ul>
              <li v-for="(category, index) in bookCategories" :key="index" class="group">
                <div @click="$emit('toggle-sub-menu', index)"
                     class="flex justify-between items-center px-4 py-2 hover:bg-gray-100 cursor-pointer text-gray-800">
                  <span>{{ category.name }}</span>
                  <i class="pi pi-chevron-right text-gray-400"></i>
                </div>
                <ul v-if="category.showSubmenu"
                    class="ml-3 mt-1 mb-2 border-l-2 border-gray-100 pl-2 bg-white shadow rounded max-h-60 overflow-y-auto">
                  <li v-for="(book, bookIndex) in category.books" :key="bookIndex"
                      class="flex items-center px-3 py-1 hover:bg-gray-50 rounded cursor-pointer text-gray-700"
                      @click="$emit('add-selected-book', book)">
                    <i class="pi pi-book mr-2 text-gray-400"></i>
                    {{ book }}
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <div>
        <h5 class="text-base font-semibold mb-3 border-b border-gray-200 pb-2 text-gray-800">
          নির্বাচিত কাঙ্খিত কিতাব
        </h5>
        <Panel class="min-h-[80px] bg-gray-50 rounded border border-gray-100">
          <div v-if="selectedBooks.length === 0" class="text-center text-gray-400 py-6">
            <span>কোন কিতাব নির্বাচন করা হয়নি</span>
          </div>
          <ul v-else>
            <li v-for="(book, index) in selectedBooks" :key="index"
                class="flex justify-between items-center p-2 mb-2 bg-white rounded border border-gray-100">
              <span class="text-gray-900">{{ book }}</span>
              <button type="button" @click="$emit('remove-book', index)"
                      class="text-red-500 hover:text-red-700 p-1 rounded-full"
                      title="রিমুভ">
                <i class="pi pi-times"></i>
              </button>
            </li>
          </ul>
        </Panel>
      </div>
    </div>

    <!-- পড়ানো কিতাব সেকশন -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 bg-white border border-gray-200 rounded-md p-6 shadow">
      <div>
        <h5 class="text-base font-semibold mb-3 border-b border-gray-200 pb-2 text-gray-800">
          এবছর পড়ানো কিতাব
        </h5>
        <div class="relative">
          <Button type="button" @click="$emit('toggle-teaching-book-dropdown')"
                  class="w-full flex items-center justify-between px-4 py-2 border border-gray-300 rounded bg-gray-50 text-sm hover:bg-gray-100"
                  outlined>
            <span>ক্যাটাগরি ও কিতাব নির্বাচন</span>
            <i class="pi pi-chevron-down"></i>
          </Button>
          <!-- স্ক্রল যুক্ত -->
          <div v-if="showTeachingBookDropdown"
               class="absolute z-50 mt-1 w-full bg-white border border-gray-200 rounded shadow-lg max-h-72 overflow-y-auto">
            <ul>
              <li v-for="(category, index) in bookCategories" :key="index" class="group">
                <div @click="$emit('toggle-teaching-book-sub-menu', index)"
                     class="flex justify-between items-center px-4 py-2 hover:bg-gray-100 cursor-pointer text-gray-800">
                  <span>{{ category.name }}</span>
                  <i class="pi pi-chevron-right text-gray-400"></i>
                </div>
                <ul v-if="category.showTeachingSubmenu"
                    class="ml-3 mt-1 mb-2 border-l-2 border-gray-100 pl-2 bg-white shadow rounded max-h-60 overflow-y-auto">
                  <li v-for="(book, bookIndex) in category.books" :key="bookIndex"
                      class="flex items-center px-3 py-1 hover:bg-gray-50 rounded cursor-pointer text-gray-700"
                      @click="$emit('add-teaching-book', book)">
                    <i class="pi pi-book mr-2 text-gray-400"></i>
                    {{ book }}
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </div>
        <div class="mt-4">
          <label for="customBooks" class="block mb-1 text-sm text-gray-600">কাস্টম কিতাব (যদি তালিকায় না পান)</label>
          <div class="flex gap-2">
            <InputText
              id="customBooks"
              :modelValue="customBookInput"
              @update:modelValue="val => $emit('update:custom-book-input', val)"
              class="w-full bg-white border border-gray-300 rounded"
              placeholder="নতুন কিতাবের নাম লিখুন"
              @keyup.enter="$emit('add-custom-book')"
            />
            <Button
              type="button"
              icon="pi pi-plus"
              class="bg-gray-200 text-gray-900 font-bold rounded px-3 py-2 hover:bg-gray-300"
              @click="$emit('add-custom-book')"
            />
          </div>
        </div>
      </div>
      <div>
        <h5 class="text-base font-semibold mb-3 border-b border-gray-200 pb-2 text-gray-800">
          নির্বাচিত পড়ানো কিতাব
        </h5>
        <Panel class="min-h-[80px] bg-gray-50 rounded border border-gray-100">
          <div v-if="teachingBooks.length === 0" class="text-center text-gray-400 py-6">
            <span>কোন কিতাব নির্বাচন করা হয়নি</span>
          </div>
          <ul v-else>
            <li v-for="(book, index) in teachingBooks" :key="index"
                class="flex justify-between items-center p-2 mb-2 bg-white rounded border border-gray-100">
              <span class="text-gray-900">{{ book }}</span>
              <button type="button" @click="$emit('remove-teaching-book', index)"
                      class="text-red-500 hover:text-red-700 p-1 rounded-full"
                      title="রিমুভ">
                <i class="pi pi-times"></i>
              </button>
            </li>
          </ul>
        </Panel>
      </div>
    </div>
  </div>
</template>

<script setup>
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Panel from 'primevue/panel';

defineProps({
  form: Object,
  classOptions: Array,
  bookCategories: Array,
  selectedBooks: Array,
  teachingBooks: Array,
  showBookDropdown: Boolean,
  showTeachingBookDropdown: Boolean,
  customBookInput: String
});
defineEmits([
  'add-qualification',
  'remove-qualification',
  'toggle-book-dropdown',
  'toggle-teaching-book-dropdown',
  'toggle-sub-menu',
  'toggle-teaching-book-sub-menu',
  'add-selected-book',
  'add-teaching-book',
  'remove-book',
  'remove-teaching-book',
  'add-custom-book',
  'update:custom-book-input'
]);
</script>
