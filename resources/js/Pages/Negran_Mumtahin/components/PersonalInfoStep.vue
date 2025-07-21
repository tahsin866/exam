<template>
  <div>
    <div class="text-center mt-4 mb-6">
      <img :src="profileImage || 'img/islamic.png'" alt="User profile picture"
           class="w-24 h-24 rounded-full mx-auto mb-3 object-cover">
      <input type="file" id="profileImageInput" class="hidden"
             @change="onProfileImageChange" />
      <label for="profileImageInput"
             class="px-4 py-2 border border-blue-500 text-blue-500 rounded-md text-sm inline-flex items-center hover:bg-blue-50 transition cursor-pointer">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2" fill="none"
             viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
        </svg>
        প্রোফাইল ছবি নির্বাচন করুন
      </label>
    </div>

    <div class="mb-6">
      <div class="md:w-1/3">
        <label for="applicationType" class="block text-gray-700 font-semibold mb-2">আবেদনের ধরন</label>
        <Dropdown id="applicationType" v-model="form.applicationType"
                  :options="applicationTypes" placeholder="আবেদনের ধরন নির্বাচন করুন"
                  class="w-full" />
      </div>
    </div>

    <div class="bg-white p-6 rounded-lg border border-gray-200 mb-6">
      <h3 class="text-lg font-semibold mb-4 text-gray-800">ব্যক্তিগত তথ্য</h3>
      <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="mb-4">
          <label for="name" class="block text-gray-700 mb-2">নাম</label>
          <InputText id="name" v-model="form.teachers.name" class="w-full"
                     placeholder="আপনার নাম লিখুন" />
          <small v-if="form.errors['teachers.name']" class="text-red-500">
            {{ form.errors['teachers.name'] }}
          </small>
        </div>
        <div class="mb-4">
          <label for="mobile" class="block text-gray-700 mb-2">মোবাইল নম্বর</label>
          <InputText id="mobile" v-model="form.teachers.Mobile" class="w-full"
                     placeholder="মোবাইল নম্বর লিখুন" />
          <small v-if="form.errors['teachers.Mobile']" class="text-red-500">
            {{ form.errors['teachers.Mobile'] }}
          </small>
        </div>
        <div class="mb-4">
          <label for="birthDate" class="block text-gray-700 mb-2">জন্ম-তারিখ</label>
          <Calendar id="birthDate" v-model="form.teachers.DateofBirth"
                    dateFormat="yy-mm-dd" class="w-full" />
          <small v-if="form.errors['teachers.DateofBirth']" class="text-red-500">
            {{ form.errors['teachers.DateofBirth'] }}
          </small>
        </div>
        <div class="mb-4">
          <label for="whatsapp" class="block text-gray-700 mb-2">হোয়াটসঅ্যাপ নম্বর</label>
          <InputText id="whatsapp" v-model="form.teachers.whatsapp" class="w-full"
                     placeholder="হোয়াটসঅ্যাপ নম্বর" />
          <small v-if="form.errors['teachers.whatsapp']" class="text-red-500">
            {{ form.errors['teachers.whatsapp'] }}
          </small>
        </div>
        <div class="mb-4">
          <label for="birthRegNo" class="block text-gray-700 mb-2">জন্ম নিবন্ধন নম্বর</label>
          <InputText id="birthRegNo" v-model="form.teachers.National_Id_Card"
                     class="w-full" placeholder="জন্ম নিবন্ধন নম্বর" />
          <small v-if="form.errors['teachers.National_Id_Card']" class="text-red-500">
            {{ form.errors['teachers.National_Id_Card'] }}
          </small>
        </div>
        <div class="mb-4">
          <label for="nid" class="block text-gray-700 mb-2">এন.আইডি নম্বর</label>
          <InputText id="nid" v-model="form.teachers.nid" class="w-full"
                     placeholder="এন.আইডি নম্বর" />
          <small v-if="form.errors['teachers.nid']" class="text-red-500">
            {{ form.errors['teachers.nid'] }}
          </small>
        </div>
        <div class="mb-4">
          <label for="gender" class="block text-gray-700 mb-2">জেন্ডার</label>
          <Dropdown id="gender" v-model="form.teachers.gender" :options="genderOptions"
                    class="w-full" />
          <small v-if="form.errors['teachers.gender']" class="text-red-500">
            {{ form.errors['teachers.gender'] }}
          </small>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Calendar from 'primevue/calendar';

defineProps({
  form: Object,
  genderOptions: Array,
  applicationTypes: Array,
  profileImage: String
});
const emit = defineEmits(['change-profile-image']);

function onProfileImageChange(e) {
  const file = e.target.files[0];
  if (file) emit('change-profile-image', file);
}
</script>
