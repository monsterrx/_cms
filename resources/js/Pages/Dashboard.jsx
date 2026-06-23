import axios from 'axios';
import { Head } from '@inertiajs/react';
import { useState } from 'react';

export default function Dashboard() {
    const [signingOut, setSigningOut] = useState(false);
    const [error, setError] = useState('');

    const signOut = async () => {
        setSigningOut(true);
        setError('');

        try {
            await axios.post('/logout');
            window.location.assign('/login');
        } catch (requestError) {
            setError(requestError.response?.data?.message ?? 'Unable to sign out.');
            setSigningOut(false);
        }
    };

    return (
        <>
            <Head title="Dashboard" />
            <main className="min-h-screen bg-slate-100 px-4 py-10">
                <section className="mx-auto max-w-5xl border border-slate-200 bg-white p-8 shadow-sm">
                    <div className="flex flex-col justify-between gap-6 sm:flex-row sm:items-center">
                        <div>
                            <p className="text-sm font-medium uppercase tracking-[0.2em] text-slate-500">
                                Monster CMS
                            </p>
                            <h1 className="mt-2 text-3xl font-semibold tracking-tight">Dashboard</h1>
                        </div>
                        <button
                            className="bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-slate-700 disabled:bg-slate-400"
                            disabled={signingOut}
                            onClick={signOut}
                            type="button"
                        >
                            {signingOut ? 'Signing out…' : 'Sign out'}
                        </button>
                    </div>
                    {error && <p className="mt-6 text-sm text-red-700">{error}</p>}
                </section>
            </main>
        </>
    );
}
