<template>
    <Head title="ব্যবহারকারী ব্যবস্থাপনা" />

    <AuthenticatedLayout>
        <div class="py-12">
            <div class=" sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl rounded-xl">
                    <div class="p-6">
                        <!-- Header with Statistics -->
                        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center mb-8 gap-6">
                            <div>
                                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    ব্যবহারকারী ব্যবস্থাপনা
                                </h2>
                                <p class="text-gray-500 mt-1">সিস্টেমের সকল এডমিন ব্যবহারকারীদের তালিকা</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="text-center px-4 py-2 bg-blue-50 rounded-lg">
                                    <div class="text-2xl font-bold text-blue-600">{{ users.total || 0 }}</div>
                                    <div class="text-xs text-blue-700">মোট ব্যবহারকারী</div>
                                </div>

                                <div class="text-center px-4 py-2 bg-green-50 rounded-lg">
                                    <div class="text-2xl font-bold text-green-600">{{ users.data.filter(u => u.is_active).length }}</div>
                                    <div class="text-xs text-green-700">সক্রিয়</div>
                                </div>

                                <Link
                                    :href="route('admin.users.create')"
                                    class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-indigo-600 border border-transparent rounded-lg font-medium text-white hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out shadow-sm hover:shadow"
                                >
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    নতুন ব্যবহারকারী
                                </Link>
                            </div>
                        </div>

                        <!-- Filters -->
                        <div class="bg-gray-50 p-6 rounded-xl mb-6 shadow-inner border border-gray-100">
                            <div class="flex flex-col md:flex-row md:items-end gap-4">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        নাম দিয়ে সার্চ করুন
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                            </svg>
                                        </div>
                                        <input
                                            v-model="filters.search"
                                            type="text"
                                            placeholder="নাম লিখুন..."
                                            class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            @input="search"
                                        />
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        ফোন নাম্বার দিয়ে সার্চ করুন
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                        </div>
                                        <input
                                            v-model="filters.phone"
                                            type="text"
                                            placeholder="ফোন নাম্বার লিখুন..."
                                            class="block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                                            @input="search"
                                        />
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">
                                        রোল দিয়ে ফিল্টার করুন
                                    </label>
                                    <div class="relative">
                                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                            <svg class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                            </svg>
                                        </div>
                                        <select
                                            v-model="filters.role"
                                            class="block w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg appearance-none focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors bg-white"
                                            @change="search"
                                        >
                                            <option value="">সব রোল</option>
                                            <option value="super-admin">সুপার এডমিন</option>
                                            <option value="assistant-super-admin">সহ সুপার এডমিন</option>
                                            <option value="board-admin">বোর্ড এডমিন</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                            <svg class="h-4 w-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex-none">
                                    <button
                                        @click="resetFilters"
                                        class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                        </svg>
                                        রিসেট
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Table -->
                        <div class="overflow-x-auto rounded-lg border border-gray-200 shadow-sm">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            #
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            নাম
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            ইমেইল
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            ফোন
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            রোল
                                        </th>
                                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            স্ট্যাটাস
                                        </th>
                                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                            অ্যাকশন
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="(user, index) in users.data" :key="user.id" class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            {{ (users.current_page - 1) * users.per_page + index + 1 }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 rounded-full overflow-hidden bg-gray-100 mr-3 border border-gray-200 flex-shrink-0">
                                                    <img
                                                        v-if="user.profile_image"
                                                        :src="`/storage/${user.profile_image}`"
                                                        :alt="user.name"
                                                        class="h-full w-full object-cover"
                                                    />
                                                    <div v-else class="h-full w-full flex items-center justify-center text-gray-400 bg-gray-50">
                                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ user.name }}
                                                    </div>
                                                    <div v-if="user.created_at" class="text-xs text-gray-500">
                                                        তৈরি: {{ new Date(user.created_at).toLocaleDateString() }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">{{ user.email }}</div>
                                            <div v-if="user.email_verified_at" class="text-xs text-green-600 flex items-center">
                                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                                ভেরিফাইড
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <div v-if="user.phone" class="flex items-center">
                                                <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                                </svg>
                                                {{ user.phone }}
                                            </div>
                                            <div v-else class="text-gray-400">N/A</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span
                                                v-for="role in user.roles"
                                                :key="role.id"
                                                :class="{
                                                    'bg-purple-100 text-purple-800 border-purple-200': role.name === 'super-admin',
                                                    'bg-blue-100 text-blue-800 border-blue-200': role.name === 'assistant-super-admin',
                                                    'bg-green-100 text-green-800 border-green-200': role.name === 'board-admin'
                                                }"
                                                class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full mr-1 border"
                                            >
                                                {{
                                                    role.name === 'super-admin' ? 'সুপার এডমিন' :
                                                    role.name === 'assistant-super-admin' ? 'সহ সুপার এডমিন' :
                                                    role.name === 'board-admin' ? 'বোর্ড এডমিন' : role.name
                                                }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <button
                                                @click="toggleStatus(user)"
                                                :class="[
                                                    'inline-flex items-center px-2.5 py-1.5 border text-xs font-medium rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 transition-colors',
                                                    user.is_active
                                                        ? 'bg-green-100 text-green-800 border-green-200 hover:bg-green-200 focus:ring-green-500'
                                                        : 'bg-red-100 text-red-800 border-red-200 hover:bg-red-200 focus:ring-red-500'
                                                ]"
                                            >
                                                <span class="flex w-2 h-2 rounded-full mr-1.5"
                                                    :class="user.is_active ? 'bg-green-500' : 'bg-red-500'"></span>
                                                {{ user.is_active ? 'সক্রিয়' : 'নিষ্ক্রিয়' }}
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-2">
                                                <Link
                                                    :href="route('admin.users.show', user.id)"
                                                    class="inline-flex items-center p-1.5 border border-blue-200 rounded-md bg-blue-50 text-blue-600 hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                                    title="বিস্তারিত দেখুন"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                    </svg>
                                                </Link>
                                                <Link
                                                    :href="route('admin.users.edit', user.id)"
                                                    class="inline-flex items-center p-1.5 border border-indigo-200 rounded-md bg-indigo-50 text-indigo-600 hover:bg-indigo-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors"
                                                    title="সম্পাদনা করুন"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                    </svg>
                                                </Link>
                                                <button
                                                    @click="deleteUser(user)"
                                                    class="inline-flex items-center p-1.5 border border-red-200 rounded-md bg-red-50 text-red-600 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors"
                                                    title="মুছে ফেলুন"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <!-- Empty State -->
                            <div v-if="users.data.length === 0" class="py-16 flex flex-col items-center justify-center bg-gray-50">
                                <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                                <p class="mt-4 text-gray-500">কোনো ব্যবহারকারী পাওয়া যায়নি</p>
                                <button
                                    @click="resetFilters"
                                    class="mt-2 inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                                >
                                    ফিল্টার রিসেট করুন
                                </button>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6 flex items-center justify-between" v-if="users.last_page > 1">
                            <div class="text-sm text-gray-500">
                                মোট {{ users.total }} টি ব্যবহারকারী | পেইজ {{ users.current_page }} / {{ users.last_page }}
                            </div>

                            <nav class="relative z-0 inline-flex shadow-sm rounded-md" aria-label="Pagination">
                                <Link
                                    :href="users.prev_page_url || '#'"
                                    :class="[
                                        'relative inline-flex items-center px-3 py-2 rounded-l-md border text-sm font-medium transition-colors',
                                        users.prev_page_url
                                            ? 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'
                                            : 'border-gray-200 bg-gray-50 text-gray-400 cursor-not-allowed'
                                    ]"
                                    :aria-disabled="!users.prev_page_url"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                </Link>

                                <template v-for="page in paginationRange" :key="page">
                                    <Link
                                        v-if="page !== '...'"
                                        :href="`?page=${page}`"
                                        :class="[
                                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium transition-colors',
                                            page === users.current_page
                                                ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                                : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'
                                        ]"
                                    >
                                        {{ page }}
                                    </Link>
                                    <span
                                        v-else
                                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                                    >
                                        ...
                                    </span>
                                </template>

                                <Link
                                    :href="users.next_page_url || '#'"
                                    :class="[
                                        'relative inline-flex items-center px-3 py-2 rounded-r-md border text-sm font-medium transition-colors',
                                        users.next_page_url
                                            ? 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'
                                            : 'border-gray-200 bg-gray-50 text-gray-400 cursor-not-allowed'
                                    ]"
                                    :aria-disabled="!users.next_page_url"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </Link>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AuthenticatedLayout from '@/Layouts/admin/AuthenticatedLayout.vue'

const props = defineProps({
    users: Object,
})

const filters = ref({
    search: '',
    phone: '',
    role: ''
})

const search = () => {
    router.get(route('admin.users.index'), {
        search: filters.value.search,
        phone: filters.value.phone,
        role: filters.value.role
    }, {
        preserveState: true,
        replace: true
    })
}

const resetFilters = () => {
    filters.value = {
        search: '',
        phone: '',
        role: ''
    }
    search()
}

const toggleStatus = (user) => {
    router.patch(route('admin.users.toggle-status', user.id), {}, {
        preserveState: true,
        onSuccess: () => {
            user.is_active = !user.is_active
        }
    })
}

const deleteUser = (user) => {
    if (confirm('আপনি কি এই ব্যবহারকারীকে মুছে ফেলতে চান?')) {
        router.delete(route('admin.users.destroy', user.id), {
            preserveState: true,
            onSuccess: () => {
                // Reload page
                router.get(route('admin.users.index'), {
                    search: filters.value.search,
                    phone: filters.value.phone,
                    role: filters.value.role
                })
            }
        })
    }
}

// Improved pagination range
const paginationRange = computed(() => {
    const currentPage = props.users.current_page;
    const lastPage = props.users.last_page;

    if (lastPage <= 7) {
        return Array.from({ length: lastPage }, (_, i) => i + 1);
    }

    if (currentPage <= 3) {
        return [1, 2, 3, 4, '...', lastPage - 1, lastPage];
    }

    if (currentPage >= lastPage - 2) {
        return [1, 2, '...', lastPage - 3, lastPage - 2, lastPage - 1, lastPage];
    }

    return [
        1,
        '...',
        currentPage - 1,
        currentPage,
        currentPage + 1,
        '...',
        lastPage
    ];
})

onMounted(() => {
    // Get URL parameters
    const urlParams = new URLSearchParams(window.location.search)
    filters.value.search = urlParams.get('search') || ''
    filters.value.phone = urlParams.get('phone') || ''
    filters.value.role = urlParams.get('role') || ''
})
</script>

<style scoped>
/* Smooth transitions for all interactive elements */
button, a, input, select {
    transition-property: all;
    transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
    transition-duration: 150ms;
}

/* Custom scrollbar for better UX */
::-webkit-scrollbar {
    width: 8px;
    height: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb {
    background: #c5c5c5;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #a0a0a0;
}
</style>
