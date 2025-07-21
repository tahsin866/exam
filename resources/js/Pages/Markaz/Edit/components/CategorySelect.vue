<script setup>
import { defineProps, defineEmits } from 'vue'
import Dropdown from 'primevue/dropdown'

const props = defineProps({ modelValue: String })
const emit = defineEmits(['update:modelValue'])

const options = [
  { label: 'দরসিয়াত', value: 'দরসিয়াত' },
  { label: 'তাহফিজুল কোরআন', value: 'তাহফিজুল কোরআন' },
  { label: 'কিরাআত', value: 'কিরাআত' },
]
</script>
<template>
  <div class="mb-6">
    <label class="block text-xl font-semibold text-emerald-800 mb-2">মারকাযের স্তর নির্বাচন করুন</label>
    <Dropdown
      :options="options"
      optionLabel="label"
      optionValue="value"
      :modelValue="modelValue"
      @update:modelValue="emit('update:modelValue', $event)"
      placeholder="নির্বাচন করুন"
      class="w-full"
      showClear
    />
  </div>
</template>
