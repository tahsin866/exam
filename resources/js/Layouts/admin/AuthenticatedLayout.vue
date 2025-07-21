<script setup>
import { ref } from 'vue';
import Sidebar from '@/Layouts/admin/components/Sidebar.vue';
import Navbar from '@/Layouts/admin/components/Navbar.vue';

const showSidebar = ref(false);
</script>

<template>
    <div style="font-family: 'Merriweather','SolaimanLipi',sans-serif;" class="flex bg-gray-100 dark:bg-gray-900 min-h-screen">
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
