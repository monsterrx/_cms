import axios from 'axios';
import { Head, Link, usePage } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import AppShell from '../Components/AppShell';
import Icon from '../Components/Icon';
import { appPath } from '../lib/appUrl';
import { translateError } from '../lib/errorTranslator';

function formatTimestamp(value) {
    const date = value ? new Date(value) : null;

    return date && !Number.isNaN(date.getTime()) ? date.toLocaleString() : 'Unknown';
}

function itemCount(section) {
    return section.groups.reduce((total, group) => total + group.items.length, 0);
}

export default function Dashboard() {
    const { props } = usePage();
    const navigation = props.navigation ?? [];
    const [summary, setSummary] = useState({
        active_promos: 0,
        pending_reviews: 0,
        recent_activity: [],
    });
    const [summaryError, setSummaryError] = useState('');
    const destinations = navigation.reduce((total, section) => total + itemCount(section), 0);
    const today = new Intl.DateTimeFormat('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
    }).format(new Date());

    useEffect(() => {
        let active = true;

        axios.get('/api/dashboard-summary', { silent: true })
            .then(({ data }) => {
                if (active) {
                    setSummary(data.data);
                    setSummaryError('');
                }
            })
            .catch((error) => {
                if (active) {
                    setSummaryError(translateError(error).message);
                }
            });

        return () => {
            active = false;
        };
    }, []);

    return (
        <AppShell>
            <Head title="Dashboard" />

            <div className="mx-auto max-w-[96rem] px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
                <section className="flex flex-col justify-between gap-6 pb-8 lg:flex-row lg:items-end">
                    <div>
                        <p className="rx-kicker">{today}</p>
                        <h1 className="mt-3 font-heading text-4xl font-bold uppercase tracking-tight sm:text-5xl">
                            Station dashboard
                        </h1>
                        <p className="mt-3 max-w-2xl text-base leading-6 text-ink-muted">
                            Manage station staff, music, digital content, programs, events, promos, and system records.
                        </p>
                    </div>
                    <Link className="rx-button self-start lg:self-auto" href={appPath('/workspace/digital-content-programs/articles')}>
                        Create article
                        <Icon className="h-4 w-4" name="plus" />
                    </Link>
                </section>

                <section aria-label="Content summary" className="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
                    {[
                        ['Management areas', String(navigation.length).padStart(2, '0'), 'Workspace modules', 'blue'],
                        ['Destinations', String(destinations).padStart(2, '0'), 'Tools and registries', 'yellow'],
                        ['Pending reviews', String(summary.pending_reviews).padStart(2, '0'), 'Unpublished articles', 'blue'],
                        ['Active promos', String(summary.active_promos).padStart(2, '0'), 'Giveaways running', 'yellow'],
                    ].map(([label, value, detail, accent]) => (
                        <article className="rx-panel relative overflow-hidden p-5" key={label}>
                            <span className={`absolute inset-y-0 left-0 w-1 ${accent === 'yellow' ? 'bg-rx-yellow' : 'bg-rx-blue'}`} />
                            <p className="font-heading text-xs font-semibold uppercase tracking-[0.16em] text-ink-muted">{label}</p>
                            <div className="mt-5 flex items-end justify-between gap-4">
                                <strong className="font-heading text-4xl font-bold tracking-tight">{value}</strong>
                                <span className="pb-1 text-right text-xs text-ink-muted">{detail}</span>
                            </div>
                        </article>
                    ))}
                </section>

                <section aria-labelledby="areas-heading" className="mt-10">
                    <div className="mb-5 flex flex-col justify-between gap-2 sm:flex-row sm:items-end">
                        <div>
                            <p className="rx-kicker">Workspace structure</p>
                            <h2 className="mt-1 font-heading text-xl font-semibold uppercase tracking-wide" id="areas-heading">
                                Management areas
                            </h2>
                        </div>
                        <p className="text-sm text-ink-muted">Configured from the current navigation and resource permissions.</p>
                    </div>

                    <div className="grid gap-4 md:grid-cols-2 2xl:grid-cols-3">
                        {navigation.map((section, index) => (
                            <Link
                                className="rx-panel group flex min-h-52 flex-col p-6 hover:-translate-y-1"
                                href={appPath(`/workspace/${section.slug}`)}
                                key={section.slug}
                            >
                                <div className="flex items-start justify-between gap-4">
                                    <span className={`flex h-11 w-11 items-center justify-center rounded-lg text-neutral-950 ${index % 2 === 0 ? 'bg-rx-blue' : 'bg-rx-yellow'}`}>
                                        <Icon className="h-5 w-5" name={section.icon} />
                                    </span>
                                    <span className="rounded-full bg-canvas px-3 py-1 text-xs font-semibold uppercase tracking-wide text-ink-muted">
                                        {itemCount(section)} tools
                                    </span>
                                </div>
                                <h3 className="mt-5 font-heading text-xl font-semibold uppercase tracking-wide group-hover:text-rx-blue">
                                    {section.label}
                                </h3>
                                <p className="mt-2 flex-1 text-sm leading-6 text-ink-muted">{section.description}</p>
                                <div className="mt-5 flex items-center justify-between">
                                    <span className="text-xs uppercase tracking-[0.16em] text-ink-muted">
                                        {section.groups.map((group) => group.label).join(' / ')}
                                    </span>
                                    <Icon className="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" name="arrow" />
                                </div>
                            </Link>
                        ))}
                    </div>
                </section>

                <div className="mt-10 grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(20rem,0.7fr)]">
                    <section aria-labelledby="activity-heading" className="rx-panel overflow-hidden">
                        <div className="flex items-center justify-between px-6 py-5">
                            <div>
                                <p className="rx-kicker">Across the station</p>
                                <h2 className="mt-1 font-heading text-lg font-semibold uppercase tracking-wide" id="activity-heading">Recent activity</h2>
                            </div>
                            <Link className="text-sm font-semibold uppercase tracking-wide text-rx-blue transition-colors hover:text-rx-yellow" href={appPath('/workspace/utilities/logs')}>
                                View logs
                            </Link>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="w-full min-w-[42rem] text-left">
                                <thead className="bg-surface-muted/70 text-xs uppercase tracking-[0.12em] text-ink-muted">
                                    <tr>
                                        <th className="px-6 py-3 font-semibold" scope="col">Action</th>
                                        <th className="px-6 py-3 font-semibold" scope="col">User</th>
                                        <th className="px-6 py-3 font-semibold" scope="col">Updated</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-line/50">
                                    {summary.recent_activity.map((activity) => (
                                        <tr className="transition-colors duration-200 hover:bg-surface-muted/40" key={activity.id}>
                                            <td className="px-6 py-4 font-heading text-sm font-semibold uppercase tracking-wide">{activity.action}</td>
                                            <td className="px-6 py-4 text-sm text-ink-muted">{activity.user}</td>
                                            <td className="px-6 py-4 text-sm text-ink-muted">{formatTimestamp(activity.created_at)}</td>
                                        </tr>
                                    ))}
                                    {summary.recent_activity.length === 0 && (
                                        <tr>
                                            <td className="px-6 py-10 text-center text-sm text-ink-muted" colSpan="3">
                                                {summaryError || 'No recent activity found.'}
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </section>

                    <aside className="rx-panel p-6" aria-labelledby="shortcuts-heading">
                        <p className="rx-kicker">Daily work</p>
                        <h2 className="mt-1 font-heading text-lg font-semibold uppercase tracking-wide" id="shortcuts-heading">Quick access</h2>
                        <div className="mt-5 space-y-2">
                            {[
                                ['Update station chart', '/workspace/music/station-chart', 'music'],
                                ['Manage shows', '/workspace/digital-content-programs/shows', 'content'],
                                ['Review contestants', '/workspace/promos/contestants', 'gift'],
                                ['View reports', '/workspace/utilities/reports', 'tools'],
                            ].map(([label, href, icon]) => (
                                <Link className="group flex items-center gap-3 rounded-lg bg-canvas px-4 py-3 transition-all duration-200 hover:translate-x-1 hover:bg-surface-muted" href={appPath(href)} key={href}>
                                    <Icon className="h-5 w-5 text-rx-blue" name={icon} />
                                    <span className="flex-1 font-heading text-sm font-semibold uppercase tracking-wide">{label}</span>
                                    <Icon className="h-4 w-4 text-ink-muted transition-transform group-hover:translate-x-1" name="arrow" />
                                </Link>
                            ))}
                        </div>
                    </aside>
                </div>
            </div>
        </AppShell>
    );
}
