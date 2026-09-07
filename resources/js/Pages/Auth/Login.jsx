import axios from 'axios';
import { Head } from '@inertiajs/react';
import { useState } from 'react';
import Icon from '../../Components/Icon';
import ThemeSwitcher from '../../Components/ThemeSwitcher';
import AppVersion from '../../Components/AppVersion';
import { appPath } from '../../lib/appUrl';
import { translateError } from '../../lib/errorTranslator';

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
    const [showPassword, setShowPassword] = useState(false);
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
            window.location.assign(appPath(data.data?.redirect_url ?? '/dashboard'));
        } catch (error) {
            const translated = translateError(error);

            setErrors(translated.fieldErrors);
            setMessage(translated.message);
        } finally {
            setSubmitting(false);
        }
    };

    const emailError = firstError(errors, 'email');
    const passwordError = firstError(errors, 'password');
    const inputClass = 'mt-2 block w-full rounded-none border bg-canvas px-3.5 py-3 text-base text-ink shadow-none transition placeholder:text-ink-muted/60';

    return (
        <>
            <Head title="Sign in" />

            <main className="grid min-h-screen bg-canvas lg:grid-cols-[0.85fr_1.15fr]">
                <section className="relative hidden overflow-hidden bg-[#181818] p-12 text-white lg:flex lg:flex-col lg:justify-between">
                    <div>
                        <div className="flex items-center gap-4">
                            <span className="flex h-14 w-14 items-center justify-center bg-rx-yellow font-heading text-lg font-bold text-neutral-950">RX</span>
                            <div>
                                <p className="font-heading text-xl font-bold uppercase tracking-wide">Monster Content Management System</p>
                                <p className="text-sm uppercase tracking-[0.24em] text-rx-blue">RX93.1</p>
                            </div>
                        </div>
                    </div>

                    <div className="relative z-10 max-w-lg">
                        <p className="font-heading text-sm font-semibold uppercase tracking-[0.24em] text-rx-yellow">Content management system</p>
                        <h1 className="mt-5 font-heading text-5xl font-bold uppercase leading-[0.95] tracking-tight">
                            Keep it locked in, on the Monster.
                        </h1>
                        <p className="mt-6 max-w-md text-lg leading-7 text-neutral-400">
                            Manage promos, shows, charts, podcasts, and digital campaigns from one secure workspace.
                        </p>
                    </div>

                    <div className="absolute -bottom-24 -right-16 font-heading text-[19rem] font-bold leading-none text-[#282828]" aria-hidden="true">
                        93.1
                    </div>
                    <p className="relative z-10 text-xs uppercase tracking-[0.2em] text-neutral-500">Monster</p>
                </section>

                <section className="relative flex items-center justify-center px-4 py-24 sm:px-8">
                    <div className="absolute right-4 top-4 sm:right-8 sm:top-8">
                        <ThemeSwitcher compact />
                    </div>

                    <div className="w-full max-w-md">
                        <div className="mb-8 lg:hidden">
                            <span className="flex h-12 w-12 items-center justify-center bg-rx-yellow font-heading text-base font-bold text-neutral-950">RX</span>
                            <p className="mt-4 font-heading text-sm font-semibold uppercase tracking-[0.2em] text-rx-blue">Monster Content Operations</p>
                        </div>

                        {/* <p className="rx-kicker">Authorized access</p> */}
                        <h2 className="mt-3 font-heading text-4xl font-bold uppercase tracking-tight sm:text-5xl">Sign in</h2>
                        {/* <p className="mt-3 text-base leading-6 text-ink-muted">
                            Enter your account credentials to continue.
                        </p> */}

                        {message && (
                            <div className="mt-6 flex gap-3 border-l-4 border-red-500 bg-surface p-4 text-sm text-ink" role="alert">
                                <Icon className="mt-0.5 h-5 w-5 shrink-0 text-red-500" name="alert" />
                                <span>{message}</span>
                            </div>
                        )}

                        <form className="mt-8 space-y-5" noValidate onSubmit={submit}>
                            <div>
                                <label className="font-heading text-sm font-semibold uppercase tracking-wide" htmlFor="email">
                                    Email address
                                </label>
                                <input
                                    aria-describedby={emailError ? 'email-error' : undefined}
                                    aria-invalid={Boolean(emailError)}
                                    autoComplete="email"
                                    autoFocus
                                    className={`${inputClass} ${emailError ? 'border-red-500' : 'border-line focus:border-rx-blue'}`}
                                    id="email"
                                    name="email"
                                    onChange={updateField}
                                    type="email"
                                    value={form.email}
                                />
                                {emailError && <p className="mt-2 text-sm text-red-500" id="email-error">{emailError}</p>}
                            </div>

                            <div>
                                <label className="font-heading text-sm font-semibold uppercase tracking-wide" htmlFor="password">
                                    Password
                                </label>
                                <div className="relative">
                                    <input
                                        aria-describedby={passwordError ? 'password-error' : undefined}
                                        aria-invalid={Boolean(passwordError)}
                                        autoComplete="current-password"
                                        className={`${inputClass} pr-20 ${passwordError ? 'border-red-500' : 'border-line focus:border-rx-blue'}`}
                                        id="password"
                                        name="password"
                                        onChange={updateField}
                                        type={showPassword ? 'text' : 'password'}
                                        value={form.password}
                                    />
                                    <button
                                        aria-controls="password"
                                        aria-label={showPassword ? 'Hide password' : 'Show password'}
                                        aria-pressed={showPassword}
                                        className="absolute inset-y-0 right-0 mt-2 flex items-center px-4 font-heading text-xs font-semibold uppercase tracking-wide text-rx-blue transition-colors duration-200 hover:text-ink focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-rx-blue"
                                        onClick={() => setShowPassword((current) => !current)}
                                        type="button"
                                    >
                                        {showPassword ? 'Hide' : 'Show'}
                                    </button>
                                </div>
                                {passwordError && <p className="mt-2 text-sm text-red-500" id="password-error">{passwordError}</p>}
                            </div>

                            <label className="flex cursor-pointer items-center gap-3 text-sm text-ink-muted">
                                <input
                                    checked={form.remember}
                                    className="rounded-none border-line bg-canvas text-rx-blue focus:ring-rx-blue"
                                    name="remember"
                                    onChange={updateField}
                                    type="checkbox"
                                />
                                Keep me signed in on this device
                            </label>

                            <button className="rx-button w-full" disabled={submitting} type="submit">
                                {submitting ? 'Signing in...' : 'Sign in'}
                                {!submitting && <Icon className="h-4 w-4" name="arrow" />}
                            </button>
                        </form>
                        <AppVersion className="mt-8 text-ink-muted" />
                    </div>
                </section>
            </main>
        </>
    );
}
