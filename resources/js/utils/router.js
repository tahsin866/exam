import { router } from '@inertiajs/vue3';

let isNavigating = false;

// Prevent duplicate navigation
const originalVisit = router.visit;
router.visit = function(url, options = {}) {
    // If already navigating to the same URL, prevent duplicate request
    if (isNavigating && window.location.href.includes(url)) {
        console.log('Duplicate navigation prevented:', url);
        return;
    }

    isNavigating = true;

    // Call original visit method
    return originalVisit.call(this, url, {
        ...options,
        onFinish: () => {
            isNavigating = false;
            if (options.onFinish) {
                options.onFinish();
            }
        },
        onError: () => {
            isNavigating = false;
            if (options.onError) {
                options.onError();
            }
        }
    });
};

// Reset navigation state on page load
window.addEventListener('load', () => {
    isNavigating = false;
});

export default router;
