<script setup>
defineProps({
  studentPhoto: Object,
  studentPhotoPreview: String,
  nidAttachment: Object,
  nidAttachmentPreview: String
});
const emit = defineEmits(['file-upload', 'remove-file']);
</script>
<template>
  <div class="bg-white border border-emerald-100 rounded-md shadow">
    <div class="bg-white border border-emerald-200 p-6 rounded-md shadow-md">
      <h3 class="text-emerald-800 text-xl arabic-font font-bold mb-6">সংযুক্তি</h3>
      <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
        <!-- Student Photo -->
        <div class="space-y-3">
          <label class="text-emerald-700 text-lg arabic-font block font-medium">
            ছাত্রের ছবি (পাসপোর্ট সাইজ এবং নীল ব্যাকগ্রাইন্ড হতে হবে)
          </label>
          <div class="relative">
            <div class="flex bg-emerald-50 border-2 border-dashed border-emerald-300 justify-between p-4 rounded-md duration-200 hover:border-emerald-500 items-center transition-colors">
              <span class="text-emerald-600 arabic-font">
                {{ studentPhoto ? studentPhoto.name : 'ফাইল আপলোড করুন' }}
              </span>
              <div v-if="studentPhotoPreview" class="flex items-center pointer-events-auto relative space-x-2 z-10">
                <a :href="studentPhotoPreview" target="_blank" class="text-emerald-600 hover:text-emerald-800">প্রিভিউ</a>
                <button @click.stop="emit('remove-file', 'studentPhoto')" class="text-red-600 hover:text-red-800">মুছুন</button>
              </div>
            </div>
            <input type="file" @change="e => emit('file-upload', e, 'studentPhoto')"
              class="h-full w-full absolute cursor-pointer inset-0 opacity-0 z-0"
              accept="image/*,.pdf,.doc,.docx">
          </div>
        </div>
        <!-- Birth Certificate/NID -->
        <div class="space-y-3">
          <label class="text-emerald-700 text-lg arabic-font block font-medium">
            জন্ম নিবন্ধন/এন আইডি সংযুক্তি করুন
          </label>
          <div class="relative">
            <div class="flex bg-emerald-50 border-2 border-dashed border-emerald-300 justify-between p-4 rounded-md duration-200 hover:border-emerald-500 items-center transition-colors">
              <span class="text-emerald-600 arabic-font">
                {{ nidAttachment ? nidAttachment.name : 'ফাইল আপলোড করুন' }}
              </span>
              <div v-if="nidAttachmentPreview" class="flex items-center pointer-events-auto relative space-x-2 z-10">
                <a :href="nidAttachmentPreview" target="_blank" class="text-emerald-600 hover:text-emerald-800">প্রিভিউ</a>
                <button @click.stop="emit('remove-file', 'nidAttachment')" class="text-red-600 hover:text-red-800">মুছুন</button>
              </div>
            </div>
            <input type="file" @change="e => emit('file-upload', e, 'nidAttachment')"
              class="h-full w-full absolute cursor-pointer inset-0 opacity-0 z-0"
              accept="image/*,.pdf,.doc,.docx">
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
