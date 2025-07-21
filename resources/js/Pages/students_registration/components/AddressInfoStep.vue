<script setup>
import { ref, watch, defineEmits, defineProps, onMounted } from 'vue'
import Dropdown from 'primevue/dropdown'
import InputText from 'primevue/inputtext'
import axios from 'axios'

const emit = defineEmits(['update:modelValue', 'prev', 'next'])
const props = defineProps({
  modelValue: Object,
  boardOptions: Array,
  form: Object
})

const divisions = ref([])
const presentDistricts = ref([])
const presentThanas = ref([])
const districts = ref([])
const thanas = ref([])

const presentFilters = ref({
  division: '',
  district: '',
  Thana: ''
})
const permanentFilters = ref({
  division: '',
  district: '',
  Thana: ''
})

onMounted(async () => {
  await loadDivisions()
})

const loadDivisions = async () => {
  try {
    const response = await axios.get('/api/divisions')
    divisions.value = response.data
    return true
  } catch (error) {
    return false
  }
}

const presentHandleDivisionChange = async () => {
  presentFilters.value.district = ''
  presentFilters.value.Thana = ''
  presentDistricts.value = []
  presentThanas.value = []

  if (!presentFilters.value.division) return
  try {
    const response = await axios.get(`/api/districts/${presentFilters.value.division}`)
    presentDistricts.value = response.data
  } catch (error) {}
}

const presentHandleDistrictChange = async () => {
  presentFilters.value.Thana = ''
  presentThanas.value = []

  if (!presentFilters.value.district) return
  try {
    const response = await axios.get(`/api/thanas/${presentFilters.value.district}`)
    presentThanas.value = response.data
  } catch (error) {}
}

const handleDivisionChange = async () => {
  permanentFilters.value.district = ''
  permanentFilters.value.Thana = ''
  districts.value = []
  thanas.value = []

  if (!permanentFilters.value.division) return
  try {
    const response = await axios.get(`/api/districts/${permanentFilters.value.division}`)
    districts.value = response.data
  } catch (error) {}
}

const handleDistrictChange = async () => {
  permanentFilters.value.Thana = ''
  thanas.value = []

  if (!permanentFilters.value.district) return
  try {
    const response = await axios.get(`/api/thanas/${permanentFilters.value.district}`)
    thanas.value = response.data
  } catch (error) {}
}

// Update modelValue when address fields are changed
watch([presentFilters, permanentFilters], () => {
  // Present address
  if (presentFilters.value.division) {
    const selectedDivision = divisions.value.find(d => d.id == presentFilters.value.division)
    if (selectedDivision) {
      props.modelValue.present_division_name = selectedDivision.Division
      props.modelValue.presernt_DID = selectedDivision.id
    }
  }
  if (presentFilters.value.district) {
    const selectedDistrict = presentDistricts.value.find(d => d.DesID == presentFilters.value.district)
    if (selectedDistrict) {
      props.modelValue.present_district_name = selectedDistrict.District
      props.modelValue.present_desId = selectedDistrict.DesID
    }
  }
  if (presentFilters.value.Thana) {
    const selectedThana = presentThanas.value.find(t => t.Thana_ID == presentFilters.value.Thana)
    if (selectedThana) {
      props.modelValue.present_thana_name = selectedThana.Thana
      props.modelValue.present_TID = selectedThana.Thana_ID
    }
  }
  // Permanent address
  if (permanentFilters.value.division) {
    const selectedDivision = divisions.value.find(d => d.id == permanentFilters.value.division)
    if (selectedDivision) {
      props.modelValue.parmanent_division_name = selectedDivision.Division
      props.modelValue.parmanent_DID = selectedDivision.id
    }
  }
  if (permanentFilters.value.district) {
    const selectedDistrict = districts.value.find(d => d.DesID == permanentFilters.value.district)
    if (selectedDistrict) {
      props.modelValue.parmanent_district_name = selectedDistrict.District
      props.modelValue.parmanent_desId = selectedDistrict.DesID
    }
  }
  if (permanentFilters.value.Thana) {
    const selectedThana = thanas.value.find(t => t.Thana_ID == permanentFilters.value.Thana)
    if (selectedThana) {
      props.modelValue.parmanent_thana_name = selectedThana.Thana
      props.modelValue.parmanent_TID = selectedThana.Thana_ID
    }
  }
  emit('update:modelValue', { ...props.modelValue })
}, { deep: true })

</script>

<template>
  <div>
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
      <div class="p-6 bg-white border-b border-gray-200">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">প্রয়োজনীয় তথ্য</h2>
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
          <div class="field">
            <label class="block font-medium text-sm text-gray-700">পরীক্ষার্থীর ধরন</label>
            <InputText value="নিয়মিত" disabled class="w-full mt-1 bg-gray-100" />
          </div>
          <div class="field">
            <label class="block font-medium text-sm text-gray-700">বোর্ড</label>
            <Dropdown v-model="props.modelValue.board_name" :options="boardOptions" optionLabel="name" optionValue="value" placeholder="বোর্ড নির্বাচন করুন" class="w-full mt-1" />
          </div>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">বর্তমান ঠিকানা</h2>
          <div class="grid grid-cols-1 gap-4">
            <div class="field">
              <label class="block font-medium text-sm text-gray-700">বিভাগ</label>
              <Dropdown v-model="presentFilters.division" :options="divisions" optionLabel="Division" optionValue="id" placeholder="সকল" class="w-full mt-1" @change="presentHandleDivisionChange" />
            </div>
            <div class="field">
              <label class="block font-medium text-sm text-gray-700">জেলা</label>
              <Dropdown v-model="presentFilters.district" :options="presentDistricts" optionLabel="District" optionValue="DesID" placeholder="সকল" class="w-full mt-1" @change="presentHandleDistrictChange" />
            </div>
            <div class="field">
              <label class="block font-medium text-sm text-gray-700">থানা/উপজেলা</label>
              <Dropdown v-model="presentFilters.Thana" :options="presentThanas" optionLabel="Thana" optionValue="Thana_ID" placeholder="সকল" class="w-full mt-1" />
            </div>
          </div>
        </div>
      </div>
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 bg-white border-b border-gray-200">
          <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-4">স্থায়ী ঠিকানা</h2>
          <div class="grid grid-cols-1 gap-4">
            <div class="field">
              <label class="block font-medium text-sm text-gray-700">বিভাগ</label>
              <Dropdown v-model="permanentFilters.division" :options="divisions" optionLabel="Division" optionValue="id" placeholder="সকল" class="w-full mt-1" @change="handleDivisionChange" />
            </div>
            <div class="field">
              <label class="block font-medium text-sm text-gray-700">জেলা</label>
              <Dropdown v-model="permanentFilters.district" :options="districts" optionLabel="District" optionValue="DesID" placeholder="সকল" class="w-full mt-1" @change="handleDistrictChange" />
            </div>
            <div class="field">
              <label class="block font-medium text-sm text-gray-700">থানা/উপজেলা</label>
              <Dropdown v-model="permanentFilters.Thana" :options="thanas" optionLabel="Thana" optionValue="Thana_ID" placeholder="সকল" class="w-full mt-1" />
            </div>
          </div>
        </div>
      </div>
    </div>
        <div class="flex justify-between mt-6">
        <button type="button" @click="emit('prev')" class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-400">
            আগের ধাপ
        </button>
        <button type="button" @click="emit('next')" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            পরবর্তী ধাপ
        </button>
        </div>
  </div>
</template>
