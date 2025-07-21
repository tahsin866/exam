<template>
  <div>
    <div class="bg-white p-6 rounded-lg border border-gray-200 mb-6">
      <h3 class="text-lg font-semibold mb-4 text-gray-800">সংযুক্তি</h3>
      <div class="space-y-3">
        <div class="relative">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            <i class="fas fa-file-alt mr-1"></i>মুহতামিম সাহেব সত্যায়িত কপি
          </label>
          <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                 @change="e => $emit('handle-file-change', e, 'muhtamim')" v-if="!files.muhtamim" />
          <div v-if="!files.muhtamim"
               class="flex items-center justify-center w-full px-4 py-2 bg-white border-2 border-dashed border-gray-300 rounded-md hover:border-blue-500">
            <i class="fas fa-cloud-upload-alt text-gray-400 text-xl mr-2"></i>
            <span class="text-gray-600">ফাইল আপলোড করুন</span>
          </div>
          <div v-else
               class="flex items-center justify-between w-full px-4 py-2 bg-white border border-gray-300 rounded-md">
            <div class="flex items-center">
              <i class="fas fa-file-pdf text-blue-500 text-xl mr-2"></i>
              <span class="text-gray-700 truncate">{{ files.muhtamim.name }}</span>
            </div>
            <div class="flex space-x-2">
              <button type="button" @click="$emit('view-file', files.muhtamim)"
                      class="text-blue-500 hover:text-blue-700">
                <i class="fas fa-eye"></i>
              </button>
              <button type="button" @click="$emit('remove-file', 'muhtamim')"
                      class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="relative">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            <i class="fas fa-id-card mr-1"></i>এন.আইডি কপি
          </label>
          <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                 @change="e => $emit('handle-file-change', e, 'nid')" v-if="!files.nid" />
          <div v-if="!files.nid"
               class="flex items-center justify-center w-full px-4 py-2 bg-white border-2 border-dashed border-gray-300 rounded-md hover:border-blue-500">
            <i class="fas fa-cloud-upload-alt text-gray-400 text-xl mr-2"></i>
            <span class="text-gray-600">ফাইল আপলোড করুন</span>
          </div>
          <div v-else
               class="flex items-center justify-between w-full px-4 py-2 bg-white border border-gray-300 rounded-md">
            <div class="flex items-center">
              <i class="fas fa-file-pdf text-blue-500 text-xl mr-2"></i>
              <span class="text-gray-700 truncate">{{ files.nid.name }}</span>
            </div>
            <div class="flex space-x-2">
              <button type="button" @click="$emit('view-file', files.nid)"
                      class="text-blue-500 hover:text-blue-700">
                <i class="fas fa-eye"></i>
              </button>
              <button type="button" @click="$emit('remove-file', 'nid')"
                      class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>
        </div>
        <div class="relative">
          <label class="block text-sm font-medium text-gray-700 mb-1">
            <i class="fas fa-certificate mr-1"></i>জন্ম-সনদ কপি
          </label>
          <input type="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer"
                 @change="e => $emit('handle-file-change', e, 'birthCert')" v-if="!files.birthCert" />
          <div v-if="!files.birthCert"
               class="flex items-center justify-center w-full px-4 py-2 bg-white border-2 border-dashed border-gray-300 rounded-md hover:border-blue-500">
            <i class="fas fa-cloud-upload-alt text-gray-400 text-xl mr-2"></i>
            <span class="text-gray-600">ফাইল আপলোড করুন</span>
          </div>
          <div v-else
               class="flex items-center justify-between w-full px-4 py-2 bg-white border border-gray-300 rounded-md">
            <div class="flex items-center">
              <i class="fas fa-file-pdf text-blue-500 text-xl mr-2"></i>
              <span class="text-gray-700 truncate">{{ files.birthCert.name }}</span>
            </div>
            <div class="flex space-x-2">
              <button type="button" @click="$emit('view-file', files.birthCert)"
                      class="text-blue-500 hover:text-blue-700">
                <i class="fas fa-eye"></i>
              </button>
              <button type="button" @click="$emit('remove-file', 'birthCert')"
                      class="text-red-500 hover:text-red-700">
                <i class="fas fa-trash-alt"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="bg-white p-6 rounded-lg border border-gray-200 mb-6">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="mb-4">
          <label for="negaran-comments" class="block text-gray-700 font-medium mb-2">নেগরান মন্তব্য</label>
          <Editor v-model="form.teachers.negaranComments" editorStyle="height: 150px"
                  class="w-full" />
        </div>
        <div class="mb-4">
          <label for="mumtahin-comments" class="block text-gray-700 font-medium mb-2">মুমতাহিন মন্তব্য</label>
          <Editor v-model="form.teachers.mumtahinComments" editorStyle="height: 150px"
                  class="w-full" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import Editor from 'primevue/editor';

defineProps({
  form: Object,
  files: Object
});
</script>
