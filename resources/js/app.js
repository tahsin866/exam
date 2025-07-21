import '../css/app.css';
import './bootstrap';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import Toaster from "@meforma/vue-toaster";
import PrimeVue from 'primevue/config';
import Aura from '@primeuix/themes/aura';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';
import Tooltip from 'primevue/tooltip'
import { MotionPlugin } from '@vueuse/motion'

import 'primeicons/primeicons.css';

const appName = import.meta.env.VITE_APP_NAME || 'WEMS';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        app.use(plugin)
        .use(ZiggyVue)
        .use(MotionPlugin)
        .use(Toaster)
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
        color: '#4B5563',
    },
});
