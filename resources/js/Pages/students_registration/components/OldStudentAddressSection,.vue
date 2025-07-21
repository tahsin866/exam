<script setup>
const props = defineProps({
  divisions: Array,
  presentFilters: Object,
  presentDistricts: Array,
  presentThanas: Array,
  permanentFilters: Object,
  districts: Array,
  thanas: Array,
  studentInfoForm: Object
});
const emit = defineEmits([
  'present-division-change',
  'present-district-change',
  'permanent-division-change',
  'permanent-district-change',
  'update:studentInfoForm'
]);
</script>

<template>
  <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
    <!-- Present Address -->
    <div class="bg-white border border-emerald-100 rounded-md shadow">
      <div class="bg-gradient-to-r rounded-t-md from-emerald-800 overflow-hidden px-6 py-3 relative to-emerald-600">
        <div class="flex gap-3 items-center relative z-10">
          <i class="text-2xl text-white fa-map-marker-alt fas"></i>
          <h5 class="text-white text-xl font-arabic">বর্তমান ঠিকানা</h5>
        </div>
      </div>
      <div class="bg-opacity-5 bg-white p-6">
        <div class="grid grid-cols-1 gap-4">
          <div class="relative">
            <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">বিভাগ</label>
            <div class="relative">
              <select v-model="presentFilters.division"
                      @change="$emit('present-division-change')"
                      class="bg-white border border-gray-200 rounded-sm w-full block focus:ring-[#2d6a4f] focus:ring-2 px-4 py-2">
                <option value="">সকল</option>
                <option v-for="division in divisions" :key="division.id" :value="division.id + ''">
                  {{ division.Division }}
                </option>
              </select>
            </div>
          </div>
          <div class="relative">
            <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">জেলা</label>
            <div class="relative">
              <select v-model="presentFilters.district"
                      @change="$emit('present-district-change')"
                      class="bg-white border border-gray-200 rounded-sm w-full block focus:ring-[#2d6a4f] focus:ring-2 px-4 py-2"
                      :disabled="!presentFilters.division">
                <option value="">সকল</option>
                <option v-for="district in presentDistricts" :key="district.DesID" :value="district.DesID + ''">
                  {{ district.District }}
                </option>
              </select>
            </div>
          </div>
          <div class="relative">
            <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">থানা/উপজেলা</label>
            <div class="relative">
              <select v-model="presentFilters.Thana"
                      class="bg-white border border-gray-200 rounded-sm w-full block focus:ring-[#2d6a4f] focus:ring-2 px-4 py-2"
                      :disabled="!presentFilters.district">
                <option value="">সকল</option>
                <option v-for="thana in presentThanas" :key="thana.Thana_ID" :value="thana.Thana_ID + ''">
                  {{ thana.Thana }}
                </option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Permanent Address -->
    <div class="bg-white border border-emerald-100 rounded-md shadow">
      <div class="bg-gradient-to-r rounded-t-md from-emerald-800 overflow-hidden px-6 py-3 relative to-emerald-600">
        <div class="flex gap-3 items-center relative z-10">
          <i class="text-2xl text-white fa-home fas"></i>
          <h5 class="text-white text-xl font-arabic">স্থায়ী ঠিকানা</h5>
        </div>
      </div>
      <div class="bg-opacity-5 bg-white p-6">
        <div class="grid grid-cols-1 gap-4">
          <div class="relative">
            <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">বিভাগ</label>
            <div class="relative">
              <select v-model="permanentFilters.division"
                      @change="$emit('permanent-division-change')"
                      class="bg-white border border-gray-200 rounded-sm w-full block focus:ring-[#2d6a4f] focus:ring-2 px-4 py-2">
                <option value="">সকল</option>
                <option v-for="division in divisions" :key="division.id" :value="division.id + ''">
                  {{ division.Division }}
                </option>
              </select>
            </div>
          </div>
          <div class="relative">
            <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">জেলা</label>
            <div class="relative">
              <select v-model="permanentFilters.district"
                      @change="$emit('permanent-district-change')"
                      class="bg-white border border-gray-200 rounded-sm w-full block focus:ring-[#2d6a4f] focus:ring-2 px-4 py-2"
                      :disabled="!permanentFilters.division">
                <option value="">সকল</option>
                <option v-for="district in districts" :key="district.DesID" :value="district.DesID + ''">
                  {{ district.District }}
                </option>
              </select>
            </div>
          </div>
          <div class="relative">
            <label class="flex text-emerald-700 text-lg font-arabic font-medium gap-2 items-center mb-1">থানা/উপজেলা</label>
            <div class="relative">
              <select v-model="permanentFilters.Thana"
                      class="bg-white border border-gray-200 rounded-sm w-full block focus:ring-[#2d6a4f] focus:ring-2 px-4 py-2"
                      :disabled="!permanentFilters.district">
                <option value="">সকল</option>
                <option v-for="thana in thanas" :key="thana.Thana_ID" :value="thana.Thana_ID + ''">
                  {{ thana.Thana }}
                </option>
              </select>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>
