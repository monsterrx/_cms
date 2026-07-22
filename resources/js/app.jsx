import axios from 'axios';
import { createInertiaApp } from '@inertiajs/react';
import { createRoot } from 'react-dom/client';
import { AppStateProvider } from './Contexts/AppStateContext';
import '../css/app.css';

axios.defaults.headers.common.Accept = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.withCredentials = true;

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

if (csrfToken) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfToken;
}

createInertiaApp({
    resolve: (name) => {
        const pages = require.context('./Pages', true, /\.jsx$/);

        return pages(`./${name}.jsx`);
    },
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
