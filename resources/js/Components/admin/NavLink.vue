<script setup>
import { computed, ref } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    active: {
        type: Boolean,
    },
});

const isNavigating = ref(false);

// Listen for navigation events
router.on('start', (event) => {
    if (event.detail.visit.url === props.href ||
        event.detail.visit.url.pathname === props.href) {
        isNavigating.value = true;
    }
});

router.on('finish', () => {
    isNavigating.value = false;
});

router.on('error', () => {
    isNavigating.value = false;
});

const classes = computed(() => {
    const baseClasses = props.active
        ? 'inline-flex items-center px-1 pt-1 border-b-2 border-indigo-400 dark:border-indigo-600 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100 focus:outline-none focus:border-indigo-700 transition duration-150 ease-in-out'
        : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-gray-700 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out';

    return isNavigating.value ? `${baseClasses} opacity-50 pointer-events-none` : baseClasses;
});
</script>

<template>
    <Link :href="href" :class="classes" :disabled="isNavigating">
        <span v-if="isNavigating" class="mr-2">
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </span>
        <slot />
    </Link>
</template>
