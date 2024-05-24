import './bootstrap';
import '../css/app.css';
import 'vue-final-modal/style.css';
import 'v-dropdown-menu/css';

import { createApp, h, DefineComponent } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import { createVfm } from 'vue-final-modal';

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

const vfm = createVfm()

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob<DefineComponent>('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(vfm)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
