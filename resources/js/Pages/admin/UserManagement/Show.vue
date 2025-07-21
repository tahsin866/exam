<template>
    <Head :title="`${user.name} - বিস্তারিত`" />
    
    <AuthenticatedLayout>
        <div class="py-12">
            <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <!-- Header -->
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-semibold text-gray-900">ব্যবহারকারীর বিস্তারিত</h2>
                            <div class="flex space-x-2">
                                <Link
                                    :href="route('admin.users.edit', user.id)"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    সম্পাদনা
                                </Link>
                                <Link
                                    :href="route('admin.users.index')"
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                    </svg>
                                    ফিরে যান
                                </Link>
                            </div>
                        </div>

                        <!-- User Information -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                            <!-- Profile Image -->
                            <div class="md:col-span-1">
                                <div class="text-center">
                                    <div class="w-48 h-48 mx-auto mb-4 rounded-full overflow-hidden bg-gray-100">
                                        <img 
                                            v-if="user.profile_image" 
                                            :src="`/storage/${user.profile_image}`" 
                                            :alt="user.name"
                                            class="w-full h-full object-cover"
                                        />
                                        <div v-else class="w-full h-full flex items-center justify-center text-gray-400">
                                            <svg class="w-24 h-24" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    <div class="mb-4">
                                        <span 
                                            :class="[
                                                'inline-flex px-3 py-1 text-sm font-semibold rounded-full',
                                                user.is_active 
                                                    ? 'bg-green-100 text-green-800' 
                                                    : 'bg-red-100 text-red-800'
                                            ]"
                                        >
                                            {{ user.is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                        </span>
                                    </div>
                                    
                                    <!-- Roles -->
                                    <div class="mb-4">
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">রোল</h3>
                                        <div class="flex flex-wrap justify-center gap-2">
                                            <span 
                                                v-for="role in user.roles" 
                                                :key="role.id"
                                                :class="{
                                                    'bg-purple-100 text-purple-800': role.name === 'super-admin',
                                                    'bg-blue-100 text-blue-800': role.name === 'assistant-super-admin',
                                                    'bg-green-100 text-green-800': role.name === 'board-admin'
                                                }"
                                                class="inline-flex px-2 py-1 text-xs font-medium rounded-full"
                                            >
                                                {{ 
                                                    role.name === 'super-admin' ? 'সুপার এডমিন' :
                                                    role.name === 'assistant-super-admin' ? 'সহ সুপার এডমিন' :
                                                    role.name === 'board-admin' ? 'বোর্ড এডমিন' : role.name
                                                }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- User Details -->
                            <div class="md:col-span-2">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">নাম</label>
                                        <p class="text-lg text-gray-900 font-semibold">{{ user.name }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">ইমেইল</label>
                                        <p class="text-lg text-gray-900">{{ user.email }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">ফোন নাম্বার</label>
                                        <p class="text-lg text-gray-900">{{ user.phone || 'N/A' }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">জাতীয় পরিচয়পত্র</label>
                                        <p class="text-lg text-gray-900">{{ user.nid || 'N/A' }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">জন্ম নিবন্ধন</label>
                                        <p class="text-lg text-gray-900">{{ user.brn || 'N/A' }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">ব্যবহারকারীর ধরন</label>
                                        <p class="text-lg text-gray-900">{{ user.user_type === 'admin' ? 'এডমিন' : 'মাদরাসা' }}</p>
                                    </div>
                                    
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-700 mb-1">ঠিকানা</label>
                                        <p class="text-lg text-gray-900">{{ user.address || 'N/A' }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">তৈরি হয়েছে</label>
                                        <p class="text-lg text-gray-900">{{ formatDate(user.created_at) }}</p>
                                    </div>
                                    
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700 mb-1">সর্বশেষ আপডেট</label>
                                        <p class="text-lg text-gray-900">{{ formatDate(user.updated_at) }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Permissions -->
                        <div class="mt-8">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">পারমিশন</h3>
                            <div v-if="user.permissions && user.permissions.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                <div
                                    v-for="permission in user.permissions"
                                    :key="permission.id"
                                    class="flex items-center space-x-2 p-3 bg-gray-50 rounded-md"
                                >
                                    <svg class="w-4 h-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm text-gray-700">{{ permission.name }}</span>
                                </div>
                            </div>
                            <div v-else class="text-gray-500 text-center py-4">
                                কোনো বিশেষ পারমিশন নেই
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue'

const props = defineProps({
    user: Object,
})

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('bn-BD', {
        year: 'numeric',
        month: 'long',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    })
}
</script>
