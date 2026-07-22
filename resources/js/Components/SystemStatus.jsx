import axios from 'axios';
import { useEffect, useState } from 'react';

const states = {
    checking: { label: 'Checking system', dot: 'bg-rx-yellow', description: 'Connecting to the application API.' },
    operational: { label: 'System online', dot: 'bg-emerald-500', description: 'Application and database are connected.' },
    degraded: { label: 'System degraded', dot: 'bg-red-500', description: 'A required service is not responding.' },
};

export default function SystemStatus({ authenticated }) {
    const [status, setStatus] = useState('checking');

    useEffect(() => {
        let active = true;

        axios.get('/api/system/health', {
            silent: true,
            trackActivity: false,
        }).then(({ data }) => {
            if (active) {
                setStatus(data?.data?.database?.status === 'connected' ? 'operational' : 'degraded');
            }
        }).catch(() => {
            if (active) {
                setStatus('degraded');
            }
        });

        return () => {
            active = false;
        };
    }, []);

    const current = states[status];

    return (
        <div aria-live="polite" className="rounded-lg bg-sidebar-muted px-4 py-3 transition-colors duration-300">
            <div className="flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.14em] text-sidebar-ink">
                <span className={`h-2 w-2 rounded-full ${current.dot}`} />
                {current.label}
            </div>
            <p className="mt-1.5 text-xs leading-5 text-sidebar-ink-muted">{current.description}</p>
            <p className="mt-2 border-t border-sidebar-line pt-2 text-[0.65rem] uppercase tracking-[0.14em] text-sidebar-ink-muted">
                {authenticated ? 'Secure session active' : 'Public status view'}
            </p>
        </div>
    );
}
