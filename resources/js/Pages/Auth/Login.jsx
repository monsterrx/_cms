import axios from 'axios';
import { Head } from '@inertiajs/react';
import { useState } from 'react';

const initialForm = {
    email: '',
    password: '',
    remember: false,
};

function firstError(errors, field) {
    const error = errors?.[field];

    if (Array.isArray(error)) {
        return error[0] ?? '';
    }

    return typeof error === 'string' ? error : '';
}

export default function Login() {
    const [form, setForm] = useState(initialForm);
    const [errors, setErrors] = useState({});
    const [message, setMessage] = useState('');
    const [submitting, setSubmitting] = useState(false);

    const updateField = (event) => {
        const { name, type, checked, value } = event.target;

        setForm((current) => ({
            ...current,
            [name]: type === 'checkbox' ? checked : value,
        }));
        setErrors((current) => ({ ...current, [name]: undefined }));
        setMessage('');
    };

    const submit = async (event) => {
        event.preventDefault();
        setSubmitting(true);
        setErrors({});
        setMessage('');

        try {
            const { data } = await axios.post('/login', form);
            window.location.assign(data.data?.redirect_url ?? '/dashboard');
        } catch (error) {
            const response = error.response;

            if (!response) {
                setMessage('Unable to reach the server. Check your connection and try again.');
            } else {
                setErrors(response.data?.errors ?? {});
                setMessage(response.data?.message ?? 'Sign in failed. Please try again.');
            }
        } finally {
            setSubmitting(false);
        }
    };

    const emailError = firstError(errors, 'email');
    const passwordError = firstError(errors, 'password');

    return (
        <>
            <Head title="Sign in" />

            <main className="flex min-h-screen items-center justify-center bg-slate-100 px-4 py-10">
                <section className="w-full max-w-md border border-slate-200 bg-white p-8 shadow-sm sm:p-10">
                    <div className="mb-8">
                        <div className="mb-5 flex h-11 w-11 items-center justify-center bg-slate-900 text-sm font-bold tracking-wider text-white">
                            RX
                        </div>
                        <p className="text-sm font-medium uppercase tracking-[0.2em] text-slate-500">
                            Monster CMS
                        </p>
                        <h1 className="mt-2 text-3xl font-semibold tracking-tight text-slate-950">
                            Sign in
                        </h1>
                        <p className="mt-2 text-sm leading-6 text-slate-600">
                            Use your account credentials to access the content management system.
                        </p>
                    </div>

                    {message && (
                        <div
                            className="mb-6 border-l-4 border-red-500 bg-red-50 px-4 py-3 text-sm text-red-800"
                            role="alert"
                        >
                            {message}
                        </div>
                    )}

                    <form className="space-y-5" onSubmit={submit} noValidate>
                        <div>
                            <label className="block text-sm font-medium text-slate-800" htmlFor="email">
                                Email address
                            </label>
                            <input
                                autoComplete="email"
                                autoFocus
                                className={`mt-2 block w-full rounded-none border px-3.5 py-3 text-sm shadow-none outline-none transition focus:ring-2 ${
                                    emailError
                                        ? 'border-red-500 focus:border-red-500 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-slate-900 focus:ring-slate-200'
                                }`}
                                id="email"
                                name="email"
                                onChange={updateField}
                                type="email"
                                value={form.email}
                                aria-invalid={Boolean(emailError)}
                                aria-describedby={emailError ? 'email-error' : undefined}
                            />
                            {emailError && (
                                <p className="mt-2 text-sm text-red-700" id="email-error">
                                    {emailError}
                                </p>
                            )}
                        </div>

                        <div>
                            <label className="block text-sm font-medium text-slate-800" htmlFor="password">
                                Password
                            </label>
                            <input
                                autoComplete="current-password"
                                className={`mt-2 block w-full rounded-none border px-3.5 py-3 text-sm shadow-none outline-none transition focus:ring-2 ${
                                    passwordError
                                        ? 'border-red-500 focus:border-red-500 focus:ring-red-100'
                                        : 'border-slate-300 focus:border-slate-900 focus:ring-slate-200'
                                }`}
                                id="password"
                                name="password"
                                onChange={updateField}
                                type="password"
                                value={form.password}
                                aria-invalid={Boolean(passwordError)}
                                aria-describedby={passwordError ? 'password-error' : undefined}
                            />
                            {passwordError && (
                                <p className="mt-2 text-sm text-red-700" id="password-error">
                                    {passwordError}
                                </p>
                            )}
                        </div>

                        <label className="flex cursor-pointer items-center gap-3 text-sm text-slate-700">
                            <input
                                checked={form.remember}
                                className="rounded-none border-slate-300 text-slate-900 focus:ring-slate-500"
                                name="remember"
                                onChange={updateField}
                                type="checkbox"
                            />
                            Keep me signed in
                        </label>

                        <button
                            className="flex w-full items-center justify-center bg-slate-900 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 disabled:cursor-not-allowed disabled:bg-slate-400"
                            disabled={submitting}
                            type="submit"
                        >
                            {submitting ? 'Signing in…' : 'Sign in'}
                        </button>
                    </form>
                </section>
            </main>
        </>
    );
}
