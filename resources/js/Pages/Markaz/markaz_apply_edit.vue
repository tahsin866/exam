<script setup>
import { ref, onMounted } from "vue";
import { useForm } from "@inertiajs/vue3";
import CategorySelect from "@/Pages/Markaz/Edit/components/CategorySelect.vue";
import MainMadrasaInfo from "@/Pages/Markaz/Edit/components/MainMadrasaInfo.vue";
import DynamicMadrasas from "@/Pages/Markaz/Edit/components/DynamicMadrasas.vue";
import RequirementsSection from "@/Pages/Markaz/Edit/components/RequirementsSection.vue";
import AttachmentSection from "@/Pages/Markaz/Edit/components/AttachmentSection.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import axios from "axios";

const props = defineProps({
  markazData: Object,
  associatedMadrasas: Array
});

// Madrasha search list
const madrashas = ref([]);

// API থেকে madrashas আনুন (মাউন্ট হলে - দ্রুত লোডের জন্য immediate async)
(async () => {
  try {
    const { data } = await axios.get(route('madrashas.list'));
    madrashas.value = Array.isArray(data) ? data : (data.madrashas ?? []);
  } catch (e) {
    madrashas.value = [];
  }
})();

const form = useForm({
  markaz_type: props.markazData.markaz_type || "",
  fazilat: props.markazData.fazilat || "",
  sanabiya_ulya: props.markazData.sanabiya_ulya || "",
  sanabiya: props.markazData.sanabiya || "",
  mutawassita: props.markazData.mutawassita || "",
  ibtedaiyyah: props.markazData.ibtedaiyyah || "",
  hifzul_quran: props.markazData.hifzul_quran || "",
  qirat: props.markazData.qirat || "",
  requirements: props.markazData.requirements || "",
  noc_file: null,
  resolution_file: null,
  muhtamim_consent: null,
  president_consent: null,
  committee_resolution: null,
  associated_madrasas: []
});

const rows = ref(
  (props.associatedMadrasas ?? []).map(madrasa => ({
    id: madrasa.id,
    fazilat: madrasa.fazilat || "",
    sanabiya_ulya: madrasa.sanabiya_ulya || "",
    sanabiya: madrasa.sanabiya || "",
    mutawassita: madrasa.mutawassita || "",
    ibtedaiyyah: madrasa.ibtedaiyyah || "",
    hifzul_quran: madrasa.hifzul_quran || "",
    qirat: madrasa.qirat || "",
    madrasa_Name: madrasa.madrasa_Name || "",
    searchQuery: madrasa.madrasa_Name || "",
    selectedMadrasha: null,
    files: {
      noc: null,
      nocPreview: madrasa.noc_file ? `/storage/${madrasa.noc_file}` : null,
      resolution: null,
      resolutionPreview: madrasa.resolution_file ? `/storage/${madrasa.resolution_file}` : null
    }
  }))
);

const submitForm = () => {
  form.associated_madrasas = rows.value.map(row => ({
    id: row.id || null,
    madrasa_Name: row.madrasa_Name,
    fazilat: row.fazilat,
    sanabiya_ulya: row.sanabiya_ulya,
    sanabiya: row.sanabiya,
    mutawassita: row.mutawassita,
    ibtedaiyyah: row.ibtedaiyyah,
    hifzul_quran: row.hifzul_quran,
    qirat: row.qirat,
    noc_file: row.files.noc,
    resolution_file: row.files.resolution
  }));

  form.post(route("markaz-agreements.update", props.markazData.id), {
    preserveScroll: true
  });
};
</script>

<template>
  <AuthenticatedLayout>
    <div class="container mx-auto px-4 mt-5">
      <div>
        <CategorySelect v-model="form.markaz_type" />
        <MainMadrasaInfo :form="form" @update:file="(field, file) => { form[field] = file }" />
        <DynamicMadrasas v-model:rows="rows" :madrashas="madrashas" />
        <RequirementsSection v-model="form.requirements" />
        <AttachmentSection
          :form="form"
          :markazData="props.markazData"
          @update:file="(field, file) => { form[field] = file }"
        />
        <div class="mt-8 mx-5">
          <button @click="submitForm" class="px-6 py-2 mb-5 bg-blue-600 text-white rounded-sm hover:bg-blue-700">
            সেভ করুন
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>
