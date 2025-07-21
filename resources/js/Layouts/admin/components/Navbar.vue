<script setup>
import { ref } from 'vue';
import Dropdown from '@/Components/admin/Dropdown.vue';
import DropdownLink from '@/Components/admin/DropdownLink.vue';
import { usePage } from '@inertiajs/vue3';

const showNotifications = ref(false);
const showMessages = ref(false);

const openNotifications = () => {
    showNotifications.value = !showNotifications.value;
    showMessages.value = false;
};
const openMessages = () => {
    showMessages.value = !showMessages.value;
    showNotifications.value = false;
};

const page = usePage();
</script>

<template>
    <nav class="fixed left-0 md:left-64 right-0 top-0 z-30 shadow-sm border-b border-gray-200 dark:border-gray-700 bg-[#f9fafb] dark:bg-gray-900 bg-opacity-80 dark:bg-opacity-80 backdrop-blur supports-backdrop-blur">
        <div class="mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex h-16 items-center justify-between">
                <!-- Brand / Logo Section -->
                <div class="flex items-center gap-4">
                    <span class="block md:hidden text-emerald-700 dark:text-emerald-300 text-2xl font-bold">EMS</span>
                    <span class="hidden md:block text-emerald-700 dark:text-emerald-300 text-xl font-bold tracking-wide opacity-90">Exam Management System</span>
                </div>
                <!-- Right Section -->
                <div class="flex items-center gap-2 md:gap-4">
                    <!-- Prayer Button (demo) -->
                    <button class="p-2 rounded-full hover:bg-emerald-100/50 dark:hover:bg-emerald-900/60 transition text-emerald-600 dark:text-emerald-300">
                        <i class="fa-kaaba fas"></i>
                    </button>
                    <!-- Notification Button -->
                    <button @click="openNotifications"
                        class="relative p-2 rounded-full hover:bg-emerald-100/50 dark:hover:bg-emerald-900/60 transition text-emerald-600 dark:text-emerald-300">
                        <i class="fa-bell fas"></i>
                        <span class="absolute top-0 right-0 bg-gradient-to-br from-emerald-400 to-green-500 text-white rounded-full text-xs px-1.5 font-bold -mt-1 -mr-1 shadow">
                            3
                        </span>
                    </button>
                    <!-- Message Button -->
                    <button @click="openMessages"
                        class="relative p-2 rounded-full hover:bg-emerald-100/50 dark:hover:bg-emerald-900/60 transition text-emerald-600 dark:text-emerald-300">
                        <i class="fa-envelope fas"></i>
                        <span class="absolute top-0 right-0 bg-gradient-to-br from-emerald-400 to-green-500 text-white rounded-full text-xs px-1.5 font-bold -mt-1 -mr-1 shadow">
                            5
                        </span>
                    </button>
                    <!-- User Dropdown -->
                    <Dropdown align="right" width="48">
                        <template #trigger>
                            <span class="rounded-md inline-flex">
                                <button
                                    class="flex items-center gap-2 bg-white border border-gray-200 dark:bg-gray-800 dark:border-gray-600 rounded-full shadow-sm px-3 py-1.5 transition hover:border-emerald-300 dark:hover:border-emerald-400 focus:outline-none"
                                >
                                    <div class="h-10 w-10 rounded-full overflow-hidden border-2 border-emerald-300 shadow">
                                        <img
                                            v-if="page.props.auth.user && page.props.auth.user.profile_image"
                                            :src="`/storage/${page.props.auth.user.profile_image}`"
                                            alt="Profile"
                                            class="h-full w-full object-cover"
                                        >
                                        <div v-else class="h-full w-full flex items-center justify-center bg-emerald-100 text-emerald-600">
                                            <i class="fa-user fas text-lg"></i>
                                        </div>
                                    </div>
                                    <div class="flex flex-col items-start leading-none">
                                        <span class="font-semibold text-emerald-900 dark:text-emerald-200 text-sm">
                                            {{ page.props.auth.user ? page.props.auth.user.name : '' }}
                                        </span>
                                        <span class="mt-0.5 text-xs">
                                            <span
                                                :class="{
                                                    'bg-purple-100 text-purple-800': page.props.auth.user && page.props.auth.user.roles && page.props.auth.user.roles[0]?.name === 'super-admin',
                                                    'bg-emerald-100 text-emerald-800': page.props.auth.user && page.props.auth.user.roles && page.props.auth.user.roles[0]?.name === 'assistant-super-admin',
                                                    'bg-gray-100 text-gray-800': page.props.auth.user && page.props.auth.user.roles && page.props.auth.user.roles[0]?.name === 'board-admin'
                                                }"
                                                class="px-2 py-0.5 rounded-full text-xs font-medium"
                                            >
                                                {{
                                                    page.props.auth.user && page.props.auth.user.roles && page.props.auth.user.roles[0]?.name === 'super-admin' ? 'সুপার এডমিন' :
                                                    page.props.auth.user && page.props.auth.user.roles && page.props.auth.user.roles[0]?.name === 'assistant-super-admin' ? 'সহ সুপার এডমিন' :
                                                    page.props.auth.user && page.props.auth.user.roles && page.props.auth.user.roles[0]?.name === 'board-admin' ? 'বোর্ড এডমিন' : 'ব্যবহারকারী'
                                                }}
                                            </span>
                                        </span>
                                    </div>
                                    <i class="fa-chevron-down fas ml-2 text-gray-400"></i>
                                </button>
                            </span>
                        </template>
                        <template #content>
                            <DropdownLink :href="route('profile.edit')"
                                class="flex rounded-lg text-emerald-700 dark:hover:bg-emerald-700 dark:text-emerald-300 hover:bg-emerald-50 items-center px-4 py-2 transition">
                                <i class="fa-user-edit fas mr-2"></i>
                                প্রোফাইল
                            </DropdownLink>
                            <DropdownLink href="route('admin.settings')"
                                class="flex rounded-lg text-emerald-700 dark:hover:bg-emerald-700 dark:text-emerald-300 hover:bg-emerald-50 items-center px-4 py-2 transition">
                                <i class="fa-cog fas mr-2"></i>
                                সেটিংস
                            </DropdownLink>
                            <DropdownLink :href="route('admin.logout')" method="post" as="button"
                                class="flex rounded-lg text-red-600 dark:hover:bg-red-900 dark:text-red-400 hover:bg-red-50 items-center px-4 py-2 transition">
                                <i class="fa-sign-out-alt fas mr-2"></i>
                                লগ আউট
                            </DropdownLink>
                        </template>
                    </Dropdown>
                </div>
            </div>
        </div>
    </nav>
</template>

<style scoped>
nav {
    /* Make navbar float visually above content and blend bg */
    box-shadow: 0 2px 8px 0 rgba(16, 185, 129, 0.05);
    background: rgba(249, 250, 251, 0.92);
    /* use backdrop-blur for pro look, see tailwind class as well */
}
</style>
