<script setup>
defineProps([
  'divisions',
  'presentFilters',
  'permanentFilters',
  'presentDistricts',
  'presentThanas',
  'districts',
  'thanas'
]);
</script>

<template>
  <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
    <!-- Present Address -->
    <section class="bg-white rounded-lg shadow-lg border border-gray-100">
      <header class="flex items-center gap-3 px-8 py-5 bg-gradient-to-r from-gray-800 via-emerald-900 to-emerald-700 rounded-t-lg border-b border-emerald-900/20">
        <i class="fa-solid fa-map-location-dot text-2xl text-emerald-200 drop-shadow"></i>
        <h5 class="text-xl font-semibold text-white tracking-wide font-arabic">
          বর্তমান ঠিকানা
        </h5>
      </header>
      <div class="px-8 py-8">
        <div class="grid grid-cols-1 gap-7">
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">বিভাগ</label>
            <select
              v-model="presentFilters.division"
              @change="$emit('present-division-change')"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
            >
              <option value="">সকল</option>
              <option
                v-for="division in divisions"
                :key="division.id"
                :value="division.id + ''"
              >
                {{ division.Division }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">জেলা</label>
            <select
              v-model="presentFilters.district"
              @change="$emit('present-district-change')"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
              :disabled="!presentFilters.division"
            >
              <option value="">সকল</option>
              <option
                v-for="district in presentDistricts"
                :key="district.DesID"
                :value="district.DesID + ''"
              >
                {{ district.District }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">থানা/উপজেলা</label>
            <select
              v-model="presentFilters.Thana"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
              :disabled="!presentFilters.district"
            >
              <option value="">সকল</option>
              <option
                v-for="thana in presentThanas"
                :key="thana.Thana_ID"
                :value="thana.Thana_ID + ''"
              >
                {{ thana.Thana }}
              </option>
            </select>
          </div>
        </div>
      </div>
    </section>
    <!-- Permanent Address -->
    <section class="bg-white rounded-lg shadow-lg border border-gray-100">
      <header class="flex items-center gap-3 px-8 py-5 bg-gradient-to-r from-gray-800 via-emerald-900 to-emerald-700 rounded-t-lg border-b border-emerald-900/20">
        <i class="fa-solid fa-house-user text-2xl text-emerald-200 drop-shadow"></i>
        <h5 class="text-xl font-semibold text-white tracking-wide font-arabic">
          স্থায়ী ঠিকানা
        </h5>
      </header>
      <div class="px-8 py-8">
        <div class="grid grid-cols-1 gap-7">
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">বিভাগ</label>
            <select
              v-model="permanentFilters.division"
              @change="$emit('permanent-division-change')"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
            >
              <option value="">সকল</option>
              <option
                v-for="division in divisions"
                :key="division.id"
                :value="division.id + ''"
              >
                {{ division.Division }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">জেলা</label>
            <select
              v-model="permanentFilters.district"
              @change="$emit('permanent-district-change')"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
              :disabled="!permanentFilters.division"
            >
              <option value="">সকল</option>
              <option
                v-for="district in districts"
                :key="district.DesID"
                :value="district.DesID + ''"
              >
                {{ district.District }}
              </option>
            </select>
          </div>
          <div>
            <label class="block text-gray-800 font-bold mb-2 font-arabic">থানা/উপজেলা</label>
            <select
              v-model="permanentFilters.Thana"
              class="block w-full rounded-lg border border-gray-300 bg-gray-50 px-4 py-2.5 text-lg shadow-sm transition focus:border-emerald-500 focus:ring-2 focus:ring-emerald-200"
              :disabled="!permanentFilters.district"
            >
              <option value="">সকল</option>
              <option
                v-for="thana in thanas"
                :key="thana.Thana_ID"
                :value="thana.Thana_ID + ''"
              >
                {{ thana.Thana }}
              </option>
            </select>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<style scoped>
/* Subtle focus and hover effects for selects */
select:focus {
  border-color: #059669 !important;
  box-shadow: 0 0 0 2px #bbf7d0;
  outline: none;
}
</style>
