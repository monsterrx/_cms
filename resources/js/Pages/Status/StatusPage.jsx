import { Head, Link } from '@inertiajs/react';
import AppShell from '../../Components/AppShell';
import Icon from '../../Components/Icon';

export default function StatusPage({ status = 500, title, description, retryable = false }) {
    return (
        <AppShell>
            <Head title={`${status} - ${title}`} />

            <div className="flex min-h-[calc(100vh-5rem)] items-center px-4 py-12 sm:px-6 lg:px-8">
                <section className="mx-auto w-full max-w-4xl overflow-hidden border border-line bg-surface">
                    <div className="grid lg:grid-cols-[0.7fr_1.3fr]">
                        <div className="relative flex min-h-64 items-center justify-center overflow-hidden bg-[#181818] p-8 text-white lg:min-h-[30rem]">
                            <span className="absolute -bottom-10 -left-6 font-heading text-[13rem] font-bold leading-none text-[#282828]" aria-hidden="true">
                                {status}
                            </span>
                            <div className="relative z-10 text-center">
                                <Icon className="mx-auto h-14 w-14 text-rx-yellow" name="alert" />
                                <p className="mt-5 font-heading text-6xl font-bold tracking-tight text-rx-blue">{status}</p>
                                <p className="mt-2 text-xs uppercase tracking-[0.25em] text-neutral-400">System response</p>
                            </div>
                        </div>

                        <div className="flex flex-col justify-center p-7 sm:p-10 lg:p-14">
                            <p className="rx-kicker">Monster CMS</p>
                            <h1 className="mt-4 font-heading text-3xl font-bold uppercase tracking-tight sm:text-5xl">
                                {title}
                            </h1>
                            <p className="mt-5 max-w-xl text-base leading-7 text-ink-muted">{description}</p>

                            <div className="mt-8 flex flex-col gap-3 sm:flex-row">
                                <Link className="rx-button" href="/dashboard">
                                    Return to dashboard
                                    <Icon className="h-4 w-4" name="arrow" />
                                </Link>
                                {retryable && (
                                    <button className="rx-button-secondary" onClick={() => window.location.reload()} type="button">
                                        Try again
                                    </button>
                                )}
                            </div>

                            <p className="mt-8 border-l-2 border-rx-yellow pl-4 text-sm leading-6 text-ink-muted">
                                If the problem continues, note response code <strong className="text-ink">{status}</strong> when contacting support.
                            </p>
                        </div>
                    </div>
                </section>
            </div>
        </AppShell>
    );
}

