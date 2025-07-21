<script setup>
import { ref, defineProps, defineEmits } from 'vue'
const props = defineProps({ label: String, modelValue: File, previewUrl: String })
const emit = defineEmits(['update:modelValue'])
const fileInput = ref(null)
const preview = ref(props.previewUrl ? `/storage/${props.previewUrl}` : null)
const handleFile = e => {
    const file = e.target.files[0]
    emit('update:modelValue', file)
    preview.value = file ? URL.createObjectURL(file) : null
}
const removeFile = () => {
    emit('update:modelValue', null)
    preview.value = null
}
</script>
<template>
    <div class="space-y-3">
        <label class="block text-lg font-medium text-emerald-700 arabic-font">{{ label }}</label>
        <div class="relative">
            <div class="flex items-center justify-between border-2 border-dashed border-emerald-300 rounded-md p-4 bg-emerald-50">
                <span class="text-emerald-600 arabic-font">
                    {{ modelValue ? modelValue.name : 'ফাইল আপলোড করুন' }}
                </span>
                <div v-if="preview" class="flex items-center space-x-2">
                    <a :href="preview" target="_blank" class="text-emerald-600 hover:text-emerald-800">প্রিভিউ</a>
                    <button @click.stop="removeFile" class="text-red-600 hover:text-red-800">মুছুন</button>
                </div>
            </div>
            <input type="file" ref="fileInput" @change="handleFile" class="absolute inset-0 opacity-0 w-full h-full cursor-pointer z-0" />
        </div>
    </div>
</template>
