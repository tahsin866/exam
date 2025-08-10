<script setup>
import { ref, computed, onMounted,watch  } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import Sidebar from '@/Layouts/admin/components/Sidebar.vue';
import Navbar from '@/Layouts/admin/components/Navbar.vue';
import LoadingIndicator from '@/Components/LoadingIndicator.vue';

const showSidebar = ref(false);
const page = usePage();

// Security check for admin layout
const auth = computed(() => page.props.auth);

// Security check with watcher for auth data
watch(auth, (newAuth) => {
    // Only run security check if auth data is available
    if (newAuth && typeof newAuth === 'object') {
        // Check if user is logged in and is admin
        if (!newAuth.user) {
            // Not logged in - redirect to login
            router.visit('/login');
            return;
        }

        if (newAuth.user && !newAuth.isAdmin) {
            // Not admin - log security violation and redirect to appropriate dashboard
            console.error('Security Violation: Non-admin user attempted to access admin layout', {
                user: newAuth.user,
                userType: newAuth.userType,
                timestamp: new Date().toISOString()
            });

            // Redirect based on user type
            if (newAuth.isMadrasa) {
                router.visit('/dashboard');
            } else {
                router.visit('/login');
            }
            return;
        }
    }
}, { immediate: true });
</script>

<template>
    <div style="font-family: 'Merriweather','SolaimanLipi',sans-serif;" class="flex bg-gray-100 dark:bg-gray-900 min-h-screen">
        <!-- Loading Indicator -->
        <LoadingIndicator />

        <!-- Sidebar -->
        <Sidebar />

        <!-- Main Content -->
        <div
            :class="[
                'flex-1',
                'transition-all duration-300 ease-in-out',
                'md:ml-64',
                'w-full',
                'overflow-x-hidden'
            ]"
        >
            <!-- Navbar -->
            <Navbar />

            <main class="flex-1 pt-16  p-4">
                <slot />
            </main>
        </div>
        <!-- Overlay for mobile sidebar -->
        <div
            v-show="showSidebar"
            @click="showSidebar = false"
            class="bg-black bg-opacity-50 fixed inset-0 lg:hidden z-40"
        ></div>
    </div>
</template>
