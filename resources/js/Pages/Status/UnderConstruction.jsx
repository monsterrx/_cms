import { Head, Link } from '@inertiajs/react';
import AppShell from '../../Components/AppShell';
import Icon from '../../Components/Icon';
import { appPath } from '../../lib/appUrl';

export default function UnderConstruction({ section = 'This section' }) {
    return (
        <AppShell>
            <Head title={`${section} - Under construction`} />

            <div className="flex min-h-[calc(100vh-5rem)] items-center px-4 py-12 sm:px-6 lg:px-8">
                <section className="mx-auto w-full max-w-5xl border border-line bg-surface">
                    <div className="h-2 bg-rx-yellow" />
                    <div className="grid gap-10 p-7 sm:p-10 lg:grid-cols-[1.2fr_0.8fr] lg:p-14">
                        <div className="flex flex-col justify-center">
                            <p className="rx-kicker">Work in progress</p>
                            <h1 className="mt-4 font-heading text-4xl font-bold uppercase tracking-tight sm:text-6xl">
                                {section} is being tuned
                            </h1>
                            <p className="mt-5 max-w-2xl text-base leading-7 text-ink-muted">
                                This workspace is under construction. The content model and publishing controls are being prepared for review.
                            </p>
                            <div className="mt-8 flex flex-col gap-3 sm:flex-row">
                                <Link className="rx-button" href={appPath('/dashboard')}>
                                    Back to dashboard
                                    <Icon className="h-4 w-4" name="arrow" />
                                </Link>
                            </div>
                        </div>

                        <div className="flex min-h-72 items-center justify-center border border-line bg-[#181818] p-8">
                            <div className="w-full max-w-xs text-center text-white">
                                <Icon className="mx-auto h-20 w-20 text-rx-blue" name="construction" />
                                <p className="mt-6 font-heading text-xl font-semibold uppercase tracking-[0.18em] text-rx-yellow">
                                    Building in progress
                                </p>
                                <div className="mt-6 grid grid-cols-5 gap-2" aria-hidden="true">
                                    {[0, 1, 2, 3, 4].map((item) => (
                                        <span
                                            className={item < 3 ? 'h-2 bg-rx-blue' : 'h-2 bg-[#353535]'}
                                            key={item}
                                        />
                                    ))}
                                </div>
                                <p className="mt-4 text-xs uppercase tracking-[0.2em] text-neutral-500">Interface preview only</p>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </AppShell>
    );
}
