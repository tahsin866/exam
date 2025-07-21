<template>
  <section
    style="font-family: 'Merriweather', 'SolaimanLipi', sans-serif;"
    class="py-16 relative overflow-hidden"
  >
    <!-- Decorative Background Elements -->
    <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 via-teal-50/80 to-emerald-100 -z-10"></div>

    <!-- Islamic Pattern Background -->
    <div class="absolute inset-0 opacity-10 -z-10">
      <svg class="w-full h-full" preserveAspectRatio="none">
        <pattern id="islamic-geometric" x="0" y="0" width="50" height="50" patternUnits="userSpaceOnUse">
          <path d="M25 0L50 25L25 50L0 25z" stroke="currentColor" stroke-width="0.5" class="text-emerald-900" fill="none"/>
          <circle cx="25" cy="25" r="8" stroke="currentColor" stroke-width="0.5" class="text-emerald-900" fill="none"/>
          <circle cx="25" cy="25" r="4" stroke="currentColor" stroke-width="0.5" class="text-emerald-900" fill="none"/>
        </pattern>
        <rect width="100%" height="100%" fill="url(#islamic-geometric)"/>
      </svg>
    </div>

    <!-- Floating Decoration Elements -->
    <div class="absolute top-10 right-10 w-32 h-32 bg-emerald-300/20 rounded-full filter blur-2xl animate-pulse-slow"></div>
    <div class="absolute bottom-10 left-10 w-48 h-48 bg-teal-300/20 rounded-full filter blur-3xl animate-float-slow"></div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 relative">
      <!-- Section Title -->
      <div class="max-w-3xl mx-auto text-center mb-10"
           v-motion
           :initial="{ opacity: 0, y: 20 }"
           :enter="{ opacity: 1, y: 0, transition: { duration: 600 } }">
        <h2 class="text-3xl md:text-4xl font-bold text-emerald-800 mb-3 flex items-center justify-center">
          <span class="inline-block w-1.5 h-10 bg-emerald-500 rounded-full mr-4"></span>
          ছাত্র-ছাত্রী সেবাসমূহ
          <span class="inline-block w-1.5 h-10 bg-emerald-500 rounded-full ml-4"></span>
        </h2>
        <p class="text-gray-600 max-w-2xl mx-auto">বেফাকুল মাদারিসিল আরাবিয়া বাংলাদেশ এর অনলাইন শিক্ষা পোর্টালে আপনাকে স্বাগতম</p>
      </div>

      <!-- Card Grid with Advanced Features -->
      <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-10">
        <!-- Result Search Panel - Enhanced -->
        <div class="group perspective"
             v-motion
             :initial="{ opacity: 0, x: -50 }"
             :enter="{ opacity: 1, x: 0, transition: { duration: 600, delay: 200 } }">
          <div class="relative preserve-3d transform transition-transform duration-500 group-hover:rotate-y-5">
            <!-- Card Front -->
            <div class="bg-white/90 backdrop-blur p-8 rounded-2xl border-t border-l border-white/50 border-r-4 border-r-emerald-600 shadow-2xl hover:shadow-emerald-400/20 transition-all duration-500 h-full">
              <!-- Notification Badge -->
              <div class="absolute -top-3 -right-3 bg-red-500 text-white rounded-full w-8 h-8 flex items-center justify-center text-sm font-bold animate-pulse-gentle">
                নতুন
              </div>

              <!-- Card Content -->
              <div class="flex flex-col md:flex-row items-center md:items-start">
                <div class="w-20 h-20 mb-4 md:mb-0 md:mr-6 flex-shrink-0 relative group-hover:scale-110 transition-transform duration-500">
                  <!-- Animated Icon with Glow Effect -->
                  <div class="absolute inset-0 bg-emerald-400/30 rounded-full blur-md transform scale-90 group-hover:scale-110 transition-transform duration-500"></div>
                  <svg class="w-full h-full text-emerald-600 relative z-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 22H5C3.9 22 3 21.1 3 20V6C3 4.9 3.9 4 5 4H7V2H9V4H15V2H17V4H19C20.1 4 21 4.9 21 6V20C21 21.1 20.1 22 19 22Z"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="group-hover:animate-bounce-slow" />
                    <path d="M16 13L12 17L8 13" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" class="group-hover:animate-pulse-slow" />
                    <path d="M12 17V9" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                          stroke-linejoin="round" class="group-hover:animate-pulse-slow" />
                  </svg>
                </div>

                <div class="flex-1 text-center md:text-left">
                  <h3 class="text-2xl font-bold text-emerald-800 mb-2 group-hover:text-emerald-600 transition-colors">ফলাফল অনুসন্ধান</h3>
                  <p class="text-gray-600 mb-4 text-sm md:text-base">৪৭ তম কেন্দ্রীয় পরীক্ষার সকল মারহালার ফলাফল দেখুন</p>

                  <!-- Quick Search Form -->
                  <div class="mb-4 bg-emerald-50/70 p-3 rounded-lg transition-all duration-300 transform group-hover:translate-y-[-5px]">
                    <form class="flex flex-col space-y-2">
                      <div class="grid grid-cols-2 gap-2">
                        <select class="bg-white/80 border border-emerald-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                          <option value="" disabled selected>মারহালা নির্বাচন করুন</option>
                          <option value="1">ইবতিদাইয়্যাহ</option>
                          <option value="2">মুতাওয়াসসিতাহ</option>
                          <option value="3">সানাবিয়্যাহ আম্মাহ</option>
                          <option value="4">ফাজিল</option>
                          <option value="5">কামিল</option>
                        </select>
                        <select class="bg-white/80 border border-emerald-200 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                          <option value="" disabled selected>সন নির্বাচন করুন</option>
                          <option value="2025">২০২৫</option>
                          <option value="2024">২০২৪</option>
                          <option value="2023">২০২৩</option>
                        </select>
                      </div>
                      <input type="text" placeholder="রোল নম্বর লিখুন" class="bg-white/80 border border-emerald-200 rounded-lg px-3 py-2 text-sm w-full focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </form>
                  </div>

                  <!-- Statistics -->
                  <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-1 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                      </svg>
                      <span>৩,৫৬,৮২৪ জন দেখেছেন</span>
                    </div>
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-1 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                      </svg>
                      <span>১৭-০৭-২০২৫ তারিখে প্রকাশিত</span>
                    </div>
                  </div>

                  <button class="w-full bg-gradient-to-r from-emerald-600 to-teal-500 hover:from-emerald-700 hover:to-teal-600 text-white px-6 py-3 rounded-lg transition-all duration-300 font-medium transform group-hover:translate-y-[-2px] hover:shadow-lg hover:shadow-emerald-500/30 flex items-center justify-center">
                    <span>ফলাফল দেখুন</span>
                    <svg class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Student Portal Panel - Enhanced -->
        <div class="group perspective"
             v-motion
             :initial="{ opacity: 0, x: 50 }"
             :enter="{ opacity: 1, x: 0, transition: { duration: 600, delay: 400 } }">
          <div class="relative preserve-3d transform transition-transform duration-500 group-hover:rotate-y-5">
            <!-- Card Front -->
            <div class="bg-white/90 backdrop-blur p-8 rounded-2xl border-t border-l border-white/50 border-r-4 border-r-emerald-600 shadow-2xl hover:shadow-teal-400/20 transition-all duration-500 h-full">
              <div class="flex flex-col md:flex-row items-center md:items-start">
                <div class="w-20 h-20 mb-4 md:mb-0 md:mr-6 flex-shrink-0 relative group-hover:scale-110 transition-transform duration-500">
                  <!-- Animated Icon with Glow Effect -->
                  <div class="absolute inset-0 bg-teal-400/30 rounded-full blur-md transform scale-90 group-hover:scale-110 transition-transform duration-500"></div>
                  <svg class="w-full h-full text-emerald-600 relative z-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M12 15C15.866 15 19 11.866 19 8C19 4.13401 15.866 1 12 1C8.13401 1 5 4.13401 5 8C5 11.866 8.13401 15 12 15Z"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="group-hover:animate-pulse-slow" />
                    <path d="M8.21 14.77L7 22L12 19L17 22L15.79 14.76"
                          stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                          class="group-hover:animate-bounce-slow" />
                  </svg>
                </div>

                <div class="flex-1 text-center md:text-left">
                  <h3 class="text-2xl font-bold text-emerald-800 mb-2 group-hover:text-emerald-600 transition-colors">তালিবুল ইলম পোর্টাল</h3>
                  <p class="text-gray-600 mb-4 text-sm md:text-base">শিক্ষার্থীর প্রোফাইল ব্যবস্থাপনা ও সনদ প্রাপ্তির আবেদন</p>

                  <!-- Features List -->
                  <div class="mb-4 bg-emerald-50/70 p-3 rounded-lg transition-all duration-300 transform group-hover:translate-y-[-5px]">
                    <ul class="space-y-1.5">
                      <li class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-2 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        ছাত্র প্রোফাইল ম্যানেজমেন্ট এবং আপডেট
                      </li>
                      <li class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-2 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        সনদপত্র অনলাইনে আবেদন ও ট্র্যাকিং
                      </li>
                      <li class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-2 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        পরীক্ষা সংক্রান্ত নোটিফিকেশন
                      </li>
                      <li class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-2 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        মার্কশীট ও ট্রান্সক্রিপ্ট ডাউনলোড
                      </li>
                    </ul>
                  </div>

                  <!-- User Status -->
                  <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-1 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                      </svg>
                      <span>{{ userStatus }}</span>
                    </div>
                    <div class="flex items-center">
                      <svg class="w-4 h-4 mr-1 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a6 6 0 00-6 6v3.586l-.707.707A1 1 0 004 14h12a1 1 0 00.707-1.707L16 11.586V8a6 6 0 00-6-6zM10 18a3 3 0 01-3-3h6a3 3 0 01-3 3z" clip-rule="evenodd" />
                      </svg>
                      <span>৩ টি নতুন নোটিফিকেশন</span>
                    </div>
                  </div>

                  <button class="w-full bg-gradient-to-r from-teal-600 to-emerald-500 hover:from-teal-700 hover:to-emerald-600 text-white px-6 py-3 rounded-lg transition-all duration-300 font-medium transform group-hover:translate-y-[-2px] hover:shadow-lg hover:shadow-teal-500/30 flex items-center justify-center">
                    <span>প্রবেশ করুন</span>
                    <svg class="w-5 h-5 ml-2 transition-transform duration-300 group-hover:translate-x-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                      <path fill-rule="evenodd" d="M3 3a1 1 0 00-1 1v12a1 1 0 001 1h12a1 1 0 001-1V7.414l-4-4H3zm7 2v2h2v2h-2v2h-2V9H6V7h2V5h2z" clip-rule="evenodd" />
                    </svg>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- New Latest News/Updates Ticker -->
      <div class="max-w-5xl mx-auto mt-10 bg-white/70 backdrop-blur-sm rounded-xl shadow-lg overflow-hidden border border-emerald-100"
           v-motion
           :initial="{ opacity: 0, y: 20 }"
           :enter="{ opacity: 1, y: 0, transition: { duration: 600, delay: 600 } }">
        <div class="bg-emerald-600 text-white px-4 py-2 flex items-center">
          <svg class="w-5 h-5 mr-2 animate-spin-slow" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2h-1V9a1 1 0 00-1-1z" clip-rule="evenodd" />
          </svg>
          <h4 class="font-bold">সর্বশেষ আপডেট</h4>
        </div>
        <div class="p-1 overflow-hidden relative">
          <div class="ticker-container overflow-hidden whitespace-nowrap">
            <div class="ticker-content inline-block animate-ticker">
              <span class="inline-flex items-center mx-4 text-emerald-800">
                <svg class="w-4 h-4 mr-1 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                ৪৭ তম কেন্দ্রীয় পরীক্ষার ফলাফল প্রকাশিত হয়েছে - ১৭/০৭/২০২৫
              </span>
              <span class="inline-flex items-center mx-4 text-emerald-800">
                <svg class="w-4 h-4 mr-1 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd" />
                </svg>
                ৪৮ তম কেন্দ্রীয় পরীক্ষার ফরম পূরণের সময়সীমা বর্ধিত করা হয়েছে - ২৫/০৭/২০২৫
              </span>
              <span class="inline-flex items-center mx-4 text-emerald-800">
                <svg class="w-4 h-4 mr-1 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                </svg>
                ফাজিল ও কামিল পরীক্ষার নতুন সিলেবাস প্রকাশিত হয়েছে - ০১/০৮/২০২৫
              </span>
            </div>
            <!-- Duplicate content for seamless looping -->
            <div class="ticker-content inline-block animate-ticker">
              <span class="inline-flex items-center mx-4 text-emerald-800">
                <svg class="w-4 h-4 mr-1 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                </svg>
                ৪৭ তম কেন্দ্রীয় পরীক্ষার ফলাফল প্রকাশিত হয়েছে - ১৭/০৭/২০২৫
              </span>
              <span class="inline-flex items-center mx-4 text-emerald-800">
                <svg class="w-4 h-4 mr-1 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path fill-rule="evenodd" d="M5 3a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2V5a2 2 0 00-2-2H5zm0 2h10v7h-2l-1 2H8l-1-2H5V5z" clip-rule="evenodd" />
                </svg>
                ৪৮ তম কেন্দ্রীয় পরীক্ষার ফরম পূরণের সময়সীমা বর্ধিত করা হয়েছে - ২৫/০৭/২০২৫
              </span>
              <span class="inline-flex items-center mx-4 text-emerald-800">
                <svg class="w-4 h-4 mr-1 text-emerald-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                  <path d="M5 4a2 2 0 012-2h6a2 2 0 012 2v14l-5-2.5L5 18V4z" />
                </svg>
                ফাজিল ও কামিল পরীক্ষার নতুন সিলেবাস প্রকাশিত হয়েছে - ০১/০৮/২০২৫
              </span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup>
import { ref, computed } from 'vue';

// User login status (dynamically set based on user login)
const isLoggedIn = ref('tahsin866' !== ''); // Check if user is logged in
const username = ref('tahsin866');

// Computed property for user status message
const userStatus = computed(() => {
  return isLoggedIn.value
    ? `${username.value} হিসাবে লগইন আছেন`
    : 'লগইন করা নেই';
});
</script>

<style scoped>
.perspective {
  perspective: 1000px;
}

.preserve-3d {
  transform-style: preserve-3d;
}

.group:hover .rotate-y-5 {
  transform: rotateY(5deg);
}

/* Animations */
@keyframes spin-slow {
  from { transform: rotate(0deg); }
  to { transform: rotate(360deg); }
}

@keyframes bounce-slow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-5px); }
}

@keyframes pulse-slow {
  0%, 100% { opacity: 1; }
  50% { opacity: 0.7; }
}

@keyframes pulse-gentle {
  0%, 100% { transform: scale(1); }
  50% { transform: scale(1.1); }
}

@keyframes float-slow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-15px); }
}

@keyframes ticker {
  0% { transform: translateX(0); }
  100% { transform: translateX(-100%); }
}

.animate-spin-slow {
  animation: spin-slow 4s linear infinite;
}

.animate-bounce-slow {
  animation: bounce-slow 2s ease-in-out infinite;
}

.animate-pulse-slow {
  animation: pulse-slow 3s ease-in-out infinite;
}

.animate-pulse-gentle {
  animation: pulse-gentle 2s ease-in-out infinite;
}

.animate-float-slow {
  animation: float-slow 6s ease-in-out infinite;
}

.animate-ticker {
  animation: ticker 30s linear infinite;
}

.ticker-container {
  width: 100%;
  height: 40px;
  line-height: 40px;
}

.ticker-content {
  padding-right: 50px;
}
</style>
