<template>
  <div>
    <div class="bg-white p-6 rounded-lg border border-gray-200 mb-6">
      <h3 class="text-lg font-semibold mb-4 text-gray-800">ঠিকানা</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="mb-4">
          <label class="block text-gray-700 mb-2">বিভাগ</label>
          <div class="relative">
            <Dropdown v-model="presentFilters.division"
                      @change="$emit('present-handle-division-change')"
                      :options="divisions"
                      optionLabel="Division" optionValue="id" placeholder="সকল"
                      class="w-full" />
          </div>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-2">জেলা</label>
          <div class="relative">
            <Dropdown v-model="presentFilters.district"
                      @change="$emit('present-handle-district-change')"
                      :options="presentDistricts"
                      optionLabel="District" optionValue="DesID" placeholder="সকল"
                      class="w-full" />
          </div>
        </div>
        <div class="mb-4">
          <label class="block text-gray-700 mb-2">থানা/উপজেলা</label>
          <div class="relative">
            <Dropdown v-model="presentFilters.Thana" :options="presentThanas"
                      optionLabel="Thana" optionValue="Thana_ID" placeholder="সকল"
                      class="w-full" />
          </div>
        </div>
      </div>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="mb-4">
          <label for="post" class="block text-gray-700 mb-2">পোস্ট</label>
          <InputText id="post" v-model="form.teachers.post" class="w-full"
                    placeholder="পোস্ট" />
        </div>
        <div class="mb-4">
          <label for="village" class="block text-gray-700 mb-2">গ্রাম</label>
          <InputText id="village" v-model="form.teachers.village" class="w-full"
                    placeholder="গ্রাম" />
        </div>
      </div>
    </div>
    <div class="bg-white p-6 rounded-lg border border-gray-200 mb-6">
      <h3 class="text-lg font-semibold mb-4 text-gray-800">পেমেন্ট</h3>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="mb-4">
          <label for="nagadNumber" class="block text-gray-700 mb-2">নগদ নম্বর</label>
          <InputText id="nagadNumber" v-model="form.teachers.nagad_number" class="w-full"
                    placeholder="নগদ নম্বর দিন" />
        </div>
        <div class="mb-4">
          <label for="rocketNumber" class="block text-gray-700 mb-2">রকেট নম্বর</label>
          <InputText id="rocketNumber" v-model="form.teachers.rocket_number"
                    class="w-full" placeholder="রকেট নম্বর দিন" />
        </div>
        <div class="mb-4">
          <label for="bkashNumber" class="block text-gray-700 mb-2">বিকাশ নম্বর</label>
          <InputText id="bkashNumber" v-model="form.teachers.bkash_number" class="w-full"
                    placeholder="বিকাশ নম্বর দিন" />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';

defineProps({
  form: Object,
  divisions: Array,
  presentDistricts: Array,
  presentThanas: Array,
  presentFilters: Object
});
defineEmits(['present-handle-division-change', 'present-handle-district-change']);
</script>
