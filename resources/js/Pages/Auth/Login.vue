<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canResetPassword: Boolean,
    status: String,
});

const form = useForm({
    login: '',
    password: '',
    remember: false,
});

const showPassword = ref(false);
const togglePassword = () => {
    showPassword.value = !showPassword.value;
};

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <div
        style="font-family: 'Merriweather','SolaimanLipi',sans-serif;"
        class="min-h-screen flex items-center justify-center bg-gradient-to-br from-emerald-50 via-teal-50 to-emerald-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 p-4 relative overflow-hidden"
    >
        <Head title="Login to WEMS" />

        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-20">
            <svg class="h-full w-full" preserveAspectRatio="none">
                <pattern id="islamic-pattern" x="0" y="0" width="56" height="56" patternUnits="userSpaceOnUse">
                    <path d="M28 0l28 28-28 28L0 28z" stroke="currentColor" stroke-width="0.5" class="text-emerald-700" fill="none"/>
                    <circle cx="28" cy="28" r="10" stroke="currentColor" stroke-width="0.5" class="text-emerald-700" fill="none"/>
                    <circle cx="28" cy="28" r="6" stroke="currentColor" stroke-width="0.5" class="text-emerald-700" fill="none"/>
                </pattern>
                <rect width="100%" height="100%" fill="url(#islamic-pattern)"/>
            </svg>
        </div>

        <!-- Decorative Elements -->
        <div class="absolute top-20 left-20 w-64 h-64 bg-emerald-300/20 rounded-full filter blur-3xl animate-float-slow"></div>
        <div class="absolute bottom-20 right-20 w-80 h-80 bg-teal-300/20 rounded-full filter blur-3xl animate-float-slow delay-1000"></div>

        <!-- Login Container -->
        <div class="w-full max-w-md relative z-10">
            <!-- Logo/Branding -->
            <div class="flex justify-center mb-6" v-motion :initial="{ opacity: 0, y: -20 }" :enter="{ opacity: 1, y: 0 }">
                <div class="bg-white dark:bg-gray-800 rounded-full p-3 shadow-xl">
                    <div class="bg-gradient-to-br from-emerald-500 to-teal-600 rounded-full w-16 h-16 flex items-center justify-center text-white shadow-inner">
                        <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 1L15.5 8.5H18.5L12 22L5.5 8.5H8.5L12 1Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="white"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card -->
            <div
                class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg rounded-2xl shadow-2xl overflow-hidden border border-white/50 dark:border-gray-700/50"
                v-motion :initial="{ opacity: 0, y: 20 }" :enter="{ opacity: 1, y: 0, transition: { delay: 200 } }">

                <!-- Header with Decorative Border -->
                <div class="relative">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 via-teal-500 to-emerald-400"></div>
                    <div class="text-center p-8 border-b border-gray-200 dark:border-gray-700">
                        <h2 class="text-3xl font-bold text-emerald-800 dark:text-emerald-300 mb-2">লগইন</h2>
                        <p class="text-gray-600 dark:text-gray-400">ইমেইল বা মোবাইল নম্বর দিয়ে প্রবেশ করুন</p>
                    </div>
                </div>

                <div class="p-8">
                    <!-- Status Message -->
                    <div v-if="status"
                         class="mb-6 p-4 bg-emerald-50/80 dark:bg-emerald-900/30 rounded-xl border-l-4 border-emerald-500 text-emerald-700 dark:text-emerald-300 flex items-center animate-fade-in"
                         v-motion :initial="{ opacity: 0, x: -20 }" :enter="{ opacity: 1, x: 0 }">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-3 flex-shrink-0 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span>{{ status }}</span>
                    </div>

                    <!-- Form Content -->
                    <form @submit.prevent="submit" class="space-y-6">
                        <!-- Login Field (Email or Mobile) -->
                        <div class="group"
                             v-motion :initial="{ opacity: 0, y: 10 }" :enter="{ opacity: 1, y: 0, transition: { delay: 300 } }">
                            <label for="login" class="block text-gray-700 dark:text-gray-300 font-medium mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                </svg>
                                ইমেইল বা মোবাইল নম্বর
                            </label>
                            <div class="relative group/input">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-500 transition-all duration-300 group-focus-within/input:text-emerald-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                                    </svg>
                                </div>
                                <input
                                    id="login"
                                    type="text"
                                    v-model="form.login"
                                    class="pl-12 block w-full rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white/60 dark:bg-gray-800/60 shadow-inner focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300"
                                    placeholder="example@email.com বা ০১XXXXXXXXX"
                                    required
                                    autofocus
                                />
                                <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-emerald-500 transition-all duration-300 group-focus-within/input:w-full"></div>
                            </div>
                            <p v-if="form.errors.login" class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ form.errors.login }}
                            </p>
                        </div>

                        <!-- Password Field with Show/Hide Toggle -->
                        <div class="group"
                             v-motion :initial="{ opacity: 0, y: 10 }" :enter="{ opacity: 1, y: 0, transition: { delay: 400 } }">
                            <label for="password" class="block text-gray-700 dark:text-gray-300 font-medium mb-2 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-emerald-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                </svg>
                                পাসওয়ার্ড
                            </label>
                            <div class="relative group/input">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-emerald-500 transition-all duration-300 group-focus-within/input:text-emerald-600">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <input
                                    id="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    v-model="form.password"
                                    class="pl-12 pr-12 block w-full rounded-xl border-2 border-gray-200 dark:border-gray-700 bg-white/60 dark:bg-gray-800/60 shadow-inner focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all duration-300"
                                    placeholder="আপনার পাসওয়ার্ড লিখুন"
                                    required
                                />
                                <div class="absolute bottom-0 left-0 w-0 h-0.5 bg-emerald-500 transition-all duration-300 group-focus-within/input:w-full"></div>
                                <button
                                    type="button"
                                    @click="togglePassword"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-emerald-500 hover:text-emerald-700 dark:hover:text-emerald-300 transition-colors duration-200 focus:outline-none"
                                >
                                    <!-- Eye Icon (Show Password) -->
                                    <svg v-if="!showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd" />
                                    </svg>
                                    <!-- Eye-Slash Icon (Hide Password) -->
                                    <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3.707 2.293a1 1 0 00-1.414 1.414l14 14a1 1 0 001.414-1.414l-1.473-1.473A10.014 10.014 0 0019.542 10C18.268 5.943 14.478 3 10 3a9.958 9.958 0 00-4.512 1.074l-1.78-1.781zm4.261 4.26l1.514 1.515a2.003 2.003 0 012.45 2.45l1.514 1.514a4 4 0 00-5.478-5.478z" clip-rule="evenodd" />
                                        <path d="M12.454 16.697L9.75 13.992a4 4 0 01-3.742-3.741L2.335 6.578A9.98 9.98 0 00.458 10c1.274 4.057 5.065 7 9.542 7 .847 0 1.669-.105 2.454-.303z" />
                                    </svg>
                                </button>
                            </div>
                            <p v-if="form.errors.password" class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                                {{ form.errors.password }}
                            </p>
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between"
                             v-motion :initial="{ opacity: 0, y: 10 }" :enter="{ opacity: 1, y: 0, transition: { delay: 500 } }">
                            <label class="flex items-center group/checkbox cursor-pointer">
                                <div class="relative">
                                    <input
                                        type="checkbox"
                                        v-model="form.remember"
                                        class="sr-only"
                                    />
                                    <div class="w-5 h-5 rounded border-2 border-gray-300 dark:border-gray-600 group-hover/checkbox:border-emerald-500 transition-colors duration-200"></div>
                                    <div class="absolute inset-0 flex items-center justify-center" v-show="form.remember">
                                        <svg class="w-3 h-3 text-emerald-600 dark:text-emerald-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </div>
                                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">মনে রাখুন</span>
                            </label>
                            <Link
                                v-if="canResetPassword"
                                href="route('password.request')"
                                class="text-sm text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 transition-colors duration-200 hover:underline"
                            >
                                পাসওয়ার্ড ভুলে গেছেন?
                            </Link>
                        </div>

                        <!-- Submit Button & Register Link -->
                        <div class="space-y-4">
                            <button
                                type="submit"
                                class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-lg text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition-all duration-300 text-lg font-medium transform hover:-translate-y-0.5 active:translate-y-0"
                                :class="{ 'opacity-75 cursor-not-allowed': form.processing }"
                                :disabled="form.processing"
                                v-motion :initial="{ opacity: 0, y: 10 }" :enter="{ opacity: 1, y: 0, transition: { delay: 600 } }"
                            >
                                <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span>প্রবেশ করুন</span>
                            </button>

                            <div class="relative flex items-center justify-center w-full mt-6" v-motion :initial="{ opacity: 0 }" :enter="{ opacity: 1, transition: { delay: 700 } }">
                                <div class="w-full h-px bg-gray-200 dark:bg-gray-700"></div>
                                <div class="absolute px-4 font-medium text-gray-500 bg-white dark:text-gray-400 dark:bg-gray-800">অথবা</div>
                            </div>

                            <div class="text-sm text-center text-gray-600 dark:text-gray-400 mt-6" v-motion :initial="{ opacity: 0 }" :enter="{ opacity: 1, transition: { delay: 800 } }">
                                <div class="space-y-2">
                                    <div>
                                        মাদরাসা একাউন্ট নেই?
                                        <Link
                                            :href="route('madrasha_check_for_user')"
                                            class="font-medium text-emerald-600 hover:text-emerald-700 dark:text-emerald-400 dark:hover:text-emerald-300 transition-colors duration-200 ml-1 hover:underline"
                                        >
                                            মাদরাসা নিবন্ধন করুন
                                        </Link>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        এডমিন একাউন্টের জন্য আলাদা লগিন ব্যবহার করুন
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Footer with User Info -->
                <div class="bg-gray-50 dark:bg-gray-800/50 py-3 px-6 text-xs text-center text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-800">
                    <div class="flex justify-center items-center gap-1">
                        <span>Current User:</span>
                        <span class="font-mono bg-gray-100 dark:bg-gray-700 px-1.5 py-0.5 rounded">{{ 'tahsin866' }}</span>
                        <span>•</span>
                        <span>{{ '2025-07-17 17:46:55' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Animations */
@keyframes float-slow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-10px); }
}

.animate-float-slow {
  animation: float-slow 6s ease-in-out infinite;
}

.delay-1000 {
  animation-delay: 1s;
}

@keyframes fade-in {
  from { opacity: 0; transform: translateY(-10px); }
  to { opacity: 1; transform: translateY(0); }
}

.animate-fade-in {
  animation: fade-in 0.5s ease-out;
}

/* Custom checkbox styling */
.group\/checkbox:hover .border-gray-300 {
  border-color: #10b981;
}
</style>
