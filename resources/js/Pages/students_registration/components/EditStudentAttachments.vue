<script setup>
defineProps([
  'student',
  'studentPhotoFile',
  'birthCertificateFile',
  'marksheetFile',
  'studentPhotoPreview',
  'birthCertificatePreview',
  'marksheetPreview'
]);
</script>

<template>
  <div class="bg-white rounded-xl shadow-lg border border-gray-100">
    <div class="px-8 pt-8 pb-6">
      <h3 class="text-2xl font-semibold text-emerald-900 mb-8 flex items-center gap-2">
        <i class="fa-solid fa-paperclip text-emerald-700"></i>
        <span class="font-arabic">সংযুক্তি</span>
      </h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Student Photo -->
        <div>
          <label class="block text-gray-800 font-bold font-arabic mb-2">
            শিক্ষার্থীর ছবি
            <span class="block text-xs text-gray-500 font-normal mt-1">পাসপোর্ট সাইজ, নীল ব্যাকগ্রাউন্ড</span>
          </label>
          <div
            class="relative rounded-lg border-2 border-dashed border-emerald-300 bg-emerald-50 hover:border-emerald-500 transition-colors p-4 flex flex-col items-center justify-between min-h-[112px]"
          >
            <span class="text-emerald-700 font-arabic text-base">
              {{ studentPhotoFile ? studentPhotoFile.name : (student.student_image ? 'বর্তমান ছবি আছে' : 'ফাইল আপলোড করুন') }}
            </span>
            <div v-if="studentPhotoPreview || student.student_image" class="flex gap-2 mt-2 z-20 relative">
              <a
                :href="studentPhotoPreview || ('/storage/' + student.student_image)"
                target="_blank"
                class="inline-flex items-center gap-1 px-2 py-1 text-emerald-700 hover:text-emerald-900 rounded focus:outline-none border border-emerald-100 bg-emerald-100 hover:bg-emerald-200 text-sm transition z-30"
                @click.stop
              >
                <i class="fa-solid fa-eye"></i> প্রিভিউ
              </a>
              <button
                type="button"
                @click.stop="$emit('remove-file', 'studentPhoto')"
                class="inline-flex items-center gap-1 px-2 py-1 text-red-600 hover:text-red-800 rounded focus:outline-none border border-red-100 bg-red-50 hover:bg-red-100 text-sm transition z-30"
              >
                <i class="fa-solid fa-trash"></i> মুছুন
              </button>
            </div>
            <!-- Notice the pointer-events-none here, and only on the input, not the overlayed buttons -->
            <input
              type="file"
              @change="$emit('file-upload', $event, 'studentPhoto')"
              class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
              accept="image/*,.pdf"
              title="শিক্ষার্থীর ছবি নির্বাচন করুন"
              :style="{'pointer-events': 'auto'}"
            />
          </div>
        </div>
        <!-- Birth Certificate / NID -->
        <div>
          <label class="block text-gray-800 font-bold font-arabic mb-2">
            জন্ম নিবন্ধন/এন আইডি সংযুক্তি
          </label>
          <div
            class="relative rounded-lg border-2 border-dashed border-emerald-300 bg-emerald-50 hover:border-emerald-500 transition-colors p-4 flex flex-col items-center justify-between min-h-[112px]"
          >
            <span class="text-emerald-700 font-arabic text-base">
              {{ birthCertificateFile ? birthCertificateFile.name : (student.NID_attach ? 'বর্তমান ছবি আছে' : 'ফাইল আপলোড করুন') }}
            </span>
            <div v-if="birthCertificatePreview || student.NID_attach" class="flex gap-2 mt-2 z-20 relative">
              <a
                :href="birthCertificatePreview || ('/storage/' + student.NID_attach)"
                target="_blank"
                class="inline-flex items-center gap-1 px-2 py-1 text-emerald-700 hover:text-emerald-900 rounded focus:outline-none border border-emerald-100 bg-emerald-100 hover:bg-emerald-200 text-sm transition z-30"
                @click.stop
              >
                <i class="fa-solid fa-eye"></i> প্রিভিউ
              </a>
              <button
                type="button"
                @click.stop="$emit('remove-file', 'birthCertificate')"
                class="inline-flex items-center gap-1 px-2 py-1 text-red-600 hover:text-red-800 rounded focus:outline-none border border-red-100 bg-red-50 hover:bg-red-100 text-sm transition z-30"
              >
                <i class="fa-solid fa-trash"></i> মুছুন
              </button>
            </div>
            <input
              type="file"
              @change="$emit('file-upload', $event, 'birthCertificate')"
              class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
              accept="image/*,.pdf,.doc,.docx"
              title="জন্ম নিবন্ধন বা এনআইডি নির্বাচন করুন"
              :style="{'pointer-events': 'auto'}"
            />
          </div>
        </div>
        <!-- Marksheet -->
        <div>
          <label class="block text-gray-800 font-bold font-arabic mb-2">
            মার্কশীট সংযুক্তি
          </label>
          <div
            class="relative rounded-lg border-2 border-dashed border-emerald-300 bg-emerald-50 hover:border-emerald-500 transition-colors p-4 flex flex-col items-center justify-between min-h-[112px]"
          >
            <span class="text-emerald-700 font-arabic text-base">
              {{ marksheetFile ? marksheetFile.name : 'ফাইল আপলোড করুন' }}
            </span>
            <div v-if="marksheetPreview" class="flex gap-2 mt-2 z-20 relative">
              <a
                :href="marksheetPreview"
                target="_blank"
                class="inline-flex items-center gap-1 px-2 py-1 text-emerald-700 hover:text-emerald-900 rounded focus:outline-none border border-emerald-100 bg-emerald-100 hover:bg-emerald-200 text-sm transition z-30"
                @click.stop
              >
                <i class="fa-solid fa-eye"></i> প্রিভিউ
              </a>
              <button
                @click.stop="$emit('remove-file', 'marksheet')"
                class="inline-flex items-center gap-1 px-2 py-1 text-red-600 hover:text-red-800 rounded focus:outline-none border border-red-100 bg-red-50 hover:bg-red-100 text-sm transition z-30"
              >
                <i class="fa-solid fa-trash"></i> মুছুন
              </button>
            </div>
            <input
              type="file"
              @change="$emit('file-upload', $event, 'marksheet')"
              class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10"
              accept="image/*,.pdf,.doc,.docx"
              title="মার্কশীট নির্বাচন করুন"
              :style="{'pointer-events': 'auto'}"
            />
          </div>
        </div>
      </div>
      <div class="mt-10 text-right">
        <button
          @click.prevent="$emit('update-student')"
          class="inline-flex items-center gap-2 bg-gradient-to-r from-emerald-700 to-emerald-900 text-white font-bold rounded-lg shadow hover:shadow-lg px-7 py-3 text-lg transition hover:bg-emerald-800 focus:ring-2 focus:ring-emerald-400 focus:outline-none"
        >
          <i class="fa-solid fa-floppy-disk"></i> সংরক্ষণ করুন
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Ensure action buttons are on top of the input */
.relative .z-20,
.relative .z-30 {
  pointer-events: auto;
}
.relative input[type="file"].z-10 {
  pointer-events: auto;
}
.relative .z-20 a,
.relative .z-20 button {
  pointer-events: auto;
}
</style>
