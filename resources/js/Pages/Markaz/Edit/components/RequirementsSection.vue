<script setup>
import { defineProps, defineEmits } from 'vue'
import Textarea from 'primevue/textarea'

const props = defineProps({ modelValue: String })
const emit = defineEmits(['update:modelValue'])
</script>
<template>
  <div class="mb-8 bg-white p-6 rounded-md shadow-md border border-slate-300">
    <label class="block text-xl text-slate-800 font-bold mb-4 arabic-font">মারকায প্রাপ্তির প্রয়োজনীয়তা</label>
    <Textarea
      :modelValue="modelValue"
      @update:modelValue="emit('update:modelValue', $event)"
      autoResize
      rows="4"
      class="w-full"
      inputClass="w-full px-4 py-2 border border-slate-300 rounded-md focus:ring-2 focus:ring-blue-600 focus:border-blue-600 bg-white shadow-sm"
      placeholder="আপনার প্রয়োজনীয়তা লিখুন..."
    />
  </div>
</template>
