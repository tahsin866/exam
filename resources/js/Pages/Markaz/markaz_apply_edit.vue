<script setup>
import { useMarkazApplyEdit } from "@/composables/useMarkazApplyEdit";
import CategorySelect from "@/Pages/Markaz/Edit/components/CategorySelect.vue";
import MainMadrasaInfo from "@/Pages/Markaz/Edit/components/MainMadrasaInfo.vue";
import DynamicMadrasas from "@/Pages/Markaz/Edit/components/DynamicMadrasas.vue";
import RequirementsSection from "@/Pages/Markaz/Edit/components/RequirementsSection.vue";
import AttachmentSection from "@/Pages/Markaz/Edit/components/AttachmentSection.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

const props = defineProps({
  markazData: Object,
  associatedMadrasas: Array
});

const {
  page,
  flashMessages,
  autoHideFlash,
  madrashas,
  form,
  rows,
  submitForm
} = useMarkazApplyEdit(props);
</script>

<template>
  <AuthenticatedLayout>
    <div class="container mx-auto px-4 mt-5">
      <!-- Flash Messages -->
      <div v-if="flashMessages.success || flashMessages.error || (form.errors && Object.keys(form.errors).length > 0)" class="mb-6">
        <!-- Success Message -->
        <div v-if="flashMessages.success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
          <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <span>{{ flashMessages.success }}</span>
          </div>
          <button @click="page.props.flash = {}" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
          </button>
        </div>

        <!-- Error Message: Only show if NO success message -->
        <div v-else-if="flashMessages.error || (form.errors && Object.keys(form.errors).length > 0)" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
          <div class="flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <div>
              <div v-if="flashMessages.error">{{ flashMessages.error }}</div>
              <div v-if="form.errors && Object.keys(form.errors).length > 0">
                <div v-for="(error, field) in form.errors" :key="field" class="mb-1">
                  <span class="font-semibold">{{ field }}:</span> {{ Array.isArray(error) ? error[0] : error }}
                </div>
              </div>
            </div>
          </div>
          <button @click="page.props.flash = {}; form.clearErrors()" class="absolute top-0 bottom-0 right-0 px-4 py-3">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
            </svg>
          </button>
        </div>
      </div>

      <div class="space-y-6">
        <!-- Each component is now wrapped in a card container -->
        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <CategorySelect v-model="form.markaz_type" />
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <MainMadrasaInfo :form="form" :markazData="props.markazData" @update:file="(field, file) => { form[field] = file }" />
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <DynamicMadrasas
            :rows="rows"
            :madrashas="madrashas"
            :markazType="form.markaz_type"
            @update:rows="rows = $event"
          />
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <RequirementsSection v-model="form.requirements" />
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <AttachmentSection
            :form="form"
            :markazData="props.markazData"
            @update:file="(field, file) => { form[field] = file }"
          />
        </div>

        <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
          <button
            @click="submitForm"
            class="px-6 py-2 bg-blue-600 text-white rounded-sm hover:bg-blue-700"
            :disabled="form.processing"
          >
            {{ form.processing ? 'প্রসেসিং...' : 'সেভ করুন' }}
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
