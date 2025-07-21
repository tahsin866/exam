<script setup>
defineProps(['form', 'student', 'marhalaName']);
</script>

<template>
  <div class="bg-white rounded-lg shadow-lg border border-gray-100">
    <!-- Header -->
    <div class="flex items-center gap-3 px-8 py-5 bg-gradient-to-r from-gray-800 via-emerald-900 to-emerald-700 rounded-t-lg border-b border-emerald-900/20">
      <i class="fa-solid fa-user-tie text-3xl text-emerald-200 drop-shadow"></i>
      <h5 class="text-2xl font-semibold text-white tracking-wide font-arabic">
        ব্যক্তিগত তথ্য<span v-if="marhalaName"> - {{ marhalaName }}</span>
      </h5>
    </div>

    <!-- Form -->
    <div class="px-8 py-8">
      <form @submit.prevent>
        <!-- Name Fields -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-7 mb-7">
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">নাম (বাংলা)
              <span class="text-red-500">*</span>
            </label>
            <div class="relative">
              <input
                v-model="form.name_bn"
                type="text"
                :disabled="student.is_old_student === 1"
                :class="[
                  'peer block w-full rounded-lg border px-4 py-2.5 text-lg shadow-sm transition focus:ring-2',
                  'border-gray-300 bg-gray-50 focus:border-emerald-500 focus:ring-emerald-200',
                  student.is_old_student === 1 ? 'bg-gray-100 cursor-not-allowed text-gray-400' : 'text-gray-900'
                ]"
                placeholder="বাংলা নাম লিখুন"
                autocomplete="off"
              />
              <span
                v-if="student.is_old_student === 1"
                class="absolute top-2 right-3 text-xs text-gray-400"
              >শুধু দেখার জন্য</span>
            </div>
          </div>
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">নাম (ইংরেজি)</label>
            <input
              v-model="form.name_en"
              type="text"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
              placeholder="English Name"
              autocomplete="off"
            />
          </div>
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">নাম (আরবি)</label>
            <input
              v-model="form.name_ar"
              type="text"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
              placeholder="العربية"
              autocomplete="off"
            />
          </div>
        </div>

        <!-- Mother's Name Fields -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-7 mb-7">
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">মাতার নাম (বাংলা)</label>
            <input
              v-model="form.mother_name_bn"
              type="text"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
              placeholder="বাংলা"
              autocomplete="off"
            />
          </div>
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">মাতার নাম (ইংরেজি)</label>
            <input
              v-model="form.mother_name_en"
              type="text"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
              placeholder="English"
              autocomplete="off"
            />
          </div>
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">মাতার নাম (আরবি)</label>
            <input
              v-model="form.mother_name_ar"
              type="text"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
              placeholder="العربية"
              autocomplete="off"
            />
          </div>
        </div>

        <!-- Father's Name Fields -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-7 mb-7">
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">পিতার নাম (বাংলা)</label>
            <div class="relative">
              <input
                v-model="form.father_name_bn"
                type="text"
                :disabled="student.is_old_student === 1"
                :class="[
                  'block w-full rounded-lg border px-4 py-2.5 text-lg shadow-sm transition focus:ring-2',
                  'border-gray-300 bg-gray-50 focus:border-emerald-500 focus:ring-emerald-200',
                  student.is_old_student === 1 ? 'bg-gray-100 cursor-not-allowed text-gray-400' : 'text-gray-900'
                ]"
                placeholder="বাংলা"
                autocomplete="off"
              />
              <span
                v-if="student.is_old_student === 1"
                class="absolute top-2 right-3 text-xs text-gray-400"
              >শুধু দেখার জন্য</span>
            </div>
          </div>
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">পিতার নাম (ইংরেজি)</label>
            <input
              v-model="form.father_name_en"
              type="text"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
              placeholder="English"
              autocomplete="off"
            />
          </div>
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">পিতার নাম (আরবি)</label>
            <input
              v-model="form.father_name_ar"
              type="text"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
              placeholder="العربية"
              autocomplete="off"
            />
          </div>
        </div>

        <!-- Date, BRN, NID -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-7">
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">জন্ম-তারিখ</label>
            <div class="relative">
              <input
                v-model="form.Date_of_birth"
                type="date"
                :disabled="student.is_old_student === 1"
                :class="[
                  'block w-full rounded-lg border px-4 py-2.5 text-lg shadow-sm transition focus:ring-2',
                  'border-gray-300 bg-gray-50 focus:border-emerald-500 focus:ring-emerald-200',
                  student.is_old_student === 1 ? 'bg-gray-100 cursor-not-allowed text-gray-400' : 'text-gray-900'
                ]"
                autocomplete="off"
              />
              <span
                v-if="student.is_old_student === 1"
                class="absolute top-2 right-3 text-xs text-gray-400"
              >শুধু দেখার জন্য</span>
            </div>
          </div>
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">জন্ম-নিবন্ধন নম্বর</label>
            <input
              v-model="form.BRN_no"
              type="text"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
              placeholder="BRN Number"
              autocomplete="off"
            />
          </div>
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">জাতীয় পরিচয়পত্র নম্বর</label>
            <input
              v-model="form.NID_no"
              type="text"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition text-gray-900 focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
              placeholder="NID Number"
              autocomplete="off"
            />
          </div>
        </div>
      </form>
    </div>
  </div>
</template>

<style scoped>
/* Subtle field focus/hover styling */
input:focus, input:active {
  outline: none;
  border-color: #059669 !important;
  box-shadow: 0 0 0 2px #bbf7d0;
}
label span {
  font-size: 1em;
}
</style>
