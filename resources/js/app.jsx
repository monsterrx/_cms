import axios from 'axios';
import { createInertiaApp } from '@inertiajs/react';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createRoot } from 'react-dom/client';
import { AppStateProvider } from './Contexts/AppStateContext';
import { getAppBasePath } from './lib/appUrl';
import '../css/app.css';

axios.defaults.headers.common.Accept = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

const appBasePath = getAppBasePath();

if (appBasePath) {
    axios.defaults.baseURL = appBasePath;
}

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

if (csrfToken) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

createInertiaApp({
    resolve: (name) => resolvePageComponent(
        `./Pages/${name}.jsx`,
        import.meta.glob('./Pages/**/*.jsx'),
    ),
    setup({ el, App, props }) {
        createRoot(el).render(
            <AppStateProvider>
                <App {...props} />
            </AppStateProvider>,
        );
    },
    progress: {
        color: '#00c9ff',
    },
});
