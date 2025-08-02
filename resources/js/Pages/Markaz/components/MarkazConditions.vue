<template>
  <div class="p-6 bg-white rounded-lg shadow-md">
    <div class="mb-6">
      <div class="flex items-center justify-center gap-3 mb-4">
        <i class="pi pi-book text-blue-600 text-2xl"></i>
        <h2 class="text-2xl font-bold text-center text-blue-800">
          মারকায প্রাপ্তির শর্ত ও পালনীয় কর্তব্য (দরসিয়াত)
        </h2>
      </div>
      <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
        <div class="flex">
          <div class="flex-shrink-0">
            <i class="pi pi-info-circle text-blue-500"></i>
          </div>
          <div class="ml-3">
            <p class="text-sm text-blue-700">
              নিম্নের শর্তাবলী পড়ুন এবং আবেদন পরবর্তী ধাপে যেতে সম্মতিপত্রে টিক চিহ্ন দিন।
            </p>
          </div>
        </div>
      </div>
    </div>

    <div class="overflow-y-auto max-h-[500px] pr-2 mb-6 terms-scrollbar">
      <ul class="space-y-4">
        <li v-for="(condition, index) in 21" :key="index" class="bg-white border-l-4 border-blue-500 p-4 rounded shadow-sm hover:shadow-md transition-shadow">
          <p v-if="index === 0" class="font-medium">১. মারকায আবেদনের জন্য বেফাকের কেন্দ্রীয় পরীক্ষায় কমপক্ষে ৩ বছর অংশগ্রহণ করতে হবে।</p>
          <p v-else-if="index === 1" class="font-medium">২. বেফাক হতে প্রকাশিত পাঠ্যবই পড়াতে হবে এবং বেফাক কর্তৃক নির্ধারিত বার্ষিক চাঁদা ও ফি পরিশোধ থাকতে হবে।</p>
          <div v-else-if="index === 2">
            <p class="font-medium mb-2">৩. মারকায-বিধি অনুসারে পরীক্ষার্থীর সংখ্যা পূর্ণ হতে হবে:</p>
            <ul class="ml-6 space-y-2 mt-2">
              <li class="bg-blue-50 p-2 rounded">
                <span class="inline-block w-5">ক.</span> দরসিয়াত ছাত্র: ঢাকা মহানগরী ২২৫ জন, ঢাকা মফস্বল ও জেলা শহর ১৫০ জন এবং মফস্বল ১২৫ জন।
              </li>
              <li class="bg-blue-50 p-2 rounded">
                <span class="inline-block w-5">খ.</span> দরসিয়াত ছাত্রী: ঢাকা মহানগরী ১৫০ জন, ঢাকা মফস্বল ও জেলা শহর ১২৫ জন এবং মফস্বল ১০০ জন।
              </li>
            </ul>
          </div>
          <p v-else-if="index === 3" class="font-medium">৪. আবেদনকৃত মারকায মাদরাসার নিজ পরীক্ষার্থী কমপক্ষে এক-তৃতীয়াংশ হতে হবে।</p>
          <p v-else-if="index === 4" class="font-medium">৫. পূর্বের মারকায ও পার্শ্ববর্তী মারকাযের ক্ষতি না হয়; এমন অবস্থানে থাকতে হবে।</p>
          <p v-else-if="index === 5" class="font-medium">৬. আবেদনকৃত মারকায মাদরাসার ইলহাক কমপক্ষে সানাবিয়া উলিয়া মারহালা পর্যন্ত থাকতে হবে।</p>
          <p v-else-if="index === 6" class="font-medium">৭. আবেদনকৃত মারকায মাদরাসা আবাসিক হতে হবে।</p>
          <p v-else-if="index === 7" class="font-medium">৮. পূর্বের মারকাযের সিল-স্বাক্ষরসহ অনাপত্তিপত্র অনলাইনে দাখিল করতে হবে।</p>
          <p v-else-if="index === 8" class="font-medium">৯. মাদরাসা কমিটির মারকায নেওয়ার সিদ্ধান্তের রেজুলেশন অনলাইনে দাখিল করতে হবে।</p>
          <p v-else-if="index === 9" class="font-medium">১০. বেফাক জেলা বা থানা কমিটির সভাপতি/সেক্রেটারির সুপারিশ অনলাইনে দাখিল করতে হবে।</p>
          <p v-else-if="index === 10" class="font-medium">১১. আবেদনকৃত মারকাযে পরীক্ষা দিতে ইচ্ছুক মাদরাসাসমূহের সম্মতিপত্র এবং গত বছর পরীক্ষায় অংশগ্রহণ করে থাকলে পূর্বের মারকাযের সিল-স্বাক্ষরসহ অনাপত্তিপত্র অনলাইনে দাখিল করতে হবে।</p>
          <div v-else-if="index === 11">
            <p class="font-medium mb-2">১২. মারকায-বিধি অনুসারে পরীক্ষার্থীর সংখ্যা অনুপাতে প্রশস্ত পরীক্ষার হল থাকতে হবে:</p>
            <ul class="ml-6 space-y-2 mt-2">
              <li class="bg-blue-50 p-2 rounded">
                <span class="inline-block w-5">ক.</span> প্রতি ৪ (চার) ফুট লম্বা তেপায়ায় ২জন, ৬ (ছয়) ফুট তেপায়ায় ৩জন পরীক্ষার্থী বসবে; এ অনুপাতে পর্যাপ্ত তেপায়ার ব্যবস্থা থাকতে হবে।
              </li>
              <li class="bg-blue-50 p-2 rounded">
                <span class="inline-block w-5">খ.</span> তেপায়ার দুই সারির মাঝে কমপক্ষে ৩ (তিন) ফুট দূরত্ব থাকতে হবে।
              </li>
              <li class="bg-blue-50 p-2 rounded">
                <span class="inline-block w-5">গ.</span> পরীক্ষা চলাকালীন পরবর্তী সময়ে হল তালাবদ্ধ থাকতে হবে।
              </li>
              <li class="bg-blue-50 p-2 rounded">
                <span class="inline-block w-5">ঘ.</span> হলের সম্পূর্ণ কর্তৃত্ব-নেতৃত্ব নেগরানে আলার হাতে অবশ্যই থাকতে হবে।
              </li>
              <li class="bg-blue-50 p-2 rounded">
                <span class="inline-block w-5">ঙ.</span> হলে পর্যাপ্ত আলো-বাতাস, নিরিবিলি পরিবেশ এবং নেগরানবৃন্দ বসার জন্য (সামনে-পিছনে) উন্মুক্ত জায়গা থাকতে হবে।
              </li>
            </ul>
          </div>
          <p v-else-if="index === 12" class="font-medium">১৩. হলের নিকটবর্তী স্থানে ইস্তিঞ্জাখানা থাকতে হবে।</p>
          <p v-else-if="index === 13" class="font-medium">১৪. নেগরানদের জন্য সমূদয় চাবিসহ আলাদা কক্ষ ও আলমারির ব্যবস্থা থাকতে হবে।</p>
          <p v-else-if="index === 14" class="font-medium">১৫. নেগরানদের কক্ষে নেগরান ব্যতীত কারও প্রবেশাধিকার থাকবে না এবং দিবারাত্রিতে কেউ অবস্থান করতে পারবে না।</p>
          <p v-else-if="index === 15" class="font-medium">১৬. পরীক্ষা সংক্রান্ত কাগজপত্র ও প্রশ্নপত্র প্রিন্টের জন্য কম্পিউটার, প্রিন্টার, ফটোকপি মেশিন, ইন্টারনেট ও বিদ্যুতের ব্যবস্থা অবশ্যই রাখতে হবে। পাশাপাশি বিদ্যুতের বিকল্প ব্যবস্থা অবশ্যই থাকতে হবে। এ সকল ব্যবস্থাপনার ব্যয়ভার মারকায মাদরাসা বহন করবে।</p>
          <p v-else-if="index === 16" class="font-medium">১৭. বিধি মোতাবেক মারকায কমিটি গঠন করে অর্পিত দায়িত্ব যথাযথভাবে আদায় করা এবং পরীক্ষা চলাকালীন প্রতিদিন মারকাযে উপস্থিত থাকতে হবে।</p>
          <p v-else-if="index === 17" class="font-medium">১৮. পরীক্ষার্থীর সংখ্যানুপাতে যোগ্য নেগরান-মুমতাহিন প্রদান করতে হবে।</p>
          <p v-else-if="index === 18" class="font-medium">১৯. বেফাক-এর পরীক্ষা সংক্রান্ত যাবতীয় বিজ্ঞপ্তি/ঘোষণা যথাসময়ে মারকাযের অধীন মাদরাসাসমূহকে অবগত করতে হবে। মাদরাসাগুলোর সাথে সার্বিক যোগাযোগ ও সৌহার্দপূর্ণ আচরণ করতে হবে। প্রয়োজনে পরীক্ষা চলাকালীন তাদের থাকা-খাওয়ার সু-ব্যবস্থা করবে।</p>
          <p v-else-if="index === 19" class="font-medium">২০. মারকাযের অনলাইন কার্যক্রম পরিচালনা, মারকাযের অধীন মাদরাসাসমূহের এডমিনদেরকে প্রশিক্ষণ দিয়ে অনলাইন কার্যক্রম সম্পাদনে সহায়তা এবং পরীক্ষা চলাকালীন পূর্ণ সময়ে নেগরানে আলাকে সার্বিক সহযোগিতা করতে, অনলাইন কাজ বুঝে এমন একজন মারকায এডমিন (দায়িত্বশীল)-এর ব্যবস্থা করতে হবে।</p>
          <p v-else-if="index === 20" class="font-medium">২১. বিগত ৭/৮/১৮ ঈ. তারিখের ইমতিহান কমিটির বৈঠকের সিদ্ধান্ত মোতাবেক, দরসিয়াত মারকায (ছাত্র/ছাত্রী) মঞ্জুরি-ফি (এককালীন)- ঢাকা মহানগর ২৪০০০/-, ঢাকা মফস্বল ও জেলা শহরগুলোতে ১৫,০০০/- এবং মফস্বল এলাকায় ৯,০০০/- মারকায মঞ্জুর হওয়া-সাপেক্ষে প্রদান করতে হবে।</p>
        </li>
      </ul>
    </div>

    <div class="mt-8 pt-6 border-t border-gray-200">
      <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
        <h3 class="text-lg font-medium text-red-700">অঙ্গীকারনামা</h3>
        <p class="mt-2 text-red-600">
          আমি অঙ্গীকার করছি যে, উল্লিখিত কোনো শর্ত পূরণ না হলে এবং আবেদন ফরমে কোনো অসম্পূর্ণ বা ভুল তথ্য প্রদান করলে; কিংবা নিবন্ধন ও অন্তর্ভুক্তি পরীক্ষার্থী প্রকৃত সংখ্যার চেয়ে বেশি দেখিয়ে থাকলে এবং মারকায-বিধি পালনে কোনোরূপ অসঙ্গতি পরিলক্ষিত হলে, বেফাক কর্তৃপক্ষের যে কোনো ফয়সালা মানতে বাধ্য থাকব।
        </p>
      </div>

      <div class="flex justify-between items-center">


        <div class="agreement-checkbox">
          <Checkbox v-model="agreed" :binary="true" id="agree" />
          <label for="agree" class="ml-2 font-medium text-gray-700">
            আমি উপরোক্ত শর্তসমূহ পড়েছি এবং সম্মত আছি
          </label>
        </div>
      </div>

      <div class="mt-6 flex justify-end">
        <Button @click="$emit('continue')" :disabled="!agreed"
          class="px-4 py-2" severity="info" label="পরবর্তী ধাপে যান" icon="pi pi-arrow-right" iconPos="right" />
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import Button from 'primevue/button';
import Checkbox from 'primevue/checkbox';

// User agreement state
const agreed = ref(false);

// Emit events when user wants to continue
defineEmits(['continue']);

// Helper function to get current date in YYYY-MM-DD format
const getCurrentDateTime = () => {
  return "2025-07-22 04:41:11"; // Using the timestamp provided
};
</script>

<style scoped>
.terms-scrollbar {
  scrollbar-width: thin;
  scrollbar-color: #3b82f6 #f1f5f9;
}

.terms-scrollbar::-webkit-scrollbar {
  width: 8px;
}

.terms-scrollbar::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 8px;
}

.terms-scrollbar::-webkit-scrollbar-thumb {
  background-color: #3b82f6;
  border-radius: 8px;
  border: 2px solid #f1f5f9;
}

.signature-line {
  border-top: 1px solid #000;
  width: 300px;
  padding-top: 8px;
}

/* Arabic font for Bengali text */
.font-medium {
  font-family: 'SolaimanLipi', 'Kalpurush', Arial, sans-serif;
}
</style>
