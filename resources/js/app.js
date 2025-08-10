import '../css/app.css';
import './bootstrap';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import './utils/router'; // Import enhanced router
import navDebugger from './utils/navigationDebugger'; // Import navigation debugger
import { router } from '@inertiajs/vue3';

import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';
import Tooltip from 'primevue/tooltip'
import { MotionPlugin } from '@vueuse/motion'

import 'primeicons/primeicons.css';

const appName = import.meta.env.VITE_APP_NAME || 'WEMS';

// Add global navigation event listeners for debugging
router.on('start', (event) => {
    console.log('Navigation started');
    navDebugger.log('Navigation Start', { url: event.detail.visit.url });
});

router.on('finish', (event) => {
    console.log('Navigation finished');
    navDebugger.log('Navigation Finish', { url: event.detail.visit.url });
});

router.on('error', (error) => {
    console.error('Navigation error:', error);
    navDebugger.log('Navigation Error', { error: error.toString() });
});

router.on('navigate', (event) => {
    console.log('Navigate event fired');
    navDebugger.log('Navigate Event', { url: event.detail.page.url });
});

router.on('before', (event) => {
    console.log('Before navigation:', event);
    navDebugger.log('Before Navigation', { url: event.detail.visit.url });
});

router.on('success', (event) => {
    console.log('Navigation success:', event);
    navDebugger.log('Navigation Success', {
        url: event.detail.page.url,
        component: event.detail.page.component
    });
});

router.on('exception', (error) => {
    console.error('Navigation exception:', error);
    navDebugger.log('Navigation Exception', { error: error.toString() });
});

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        // Global error handler for better debugging
        app.config.errorHandler = (error, instance, info) => {
            console.error('Vue Global Error:', error);
            console.error('Component Instance:', instance);
            console.error('Error Info:', info);
        };

        app.use(plugin)
        .use(ZiggyVue)
        .use(MotionPlugin)

        .use(ToastService)
        .use(ConfirmationService)
        .directive('tooltip', Tooltip)
        .use(PrimeVue, {
            theme: {
                preset: Aura
            }
        })
        .mount(el);

        return app;
    },
    progress: {
        delay: 250,
        color: '#4B5563',
        includeCSS: true,
        showSpinner: true,
    },
});
