import { Head, Link } from '@inertiajs/react';
import AppShell from '../../Components/AppShell';
import Icon from '../../Components/Icon';
import { appPath } from '../../lib/appUrl';

function formatFileSize(bytes) {
    if (!Number.isFinite(Number(bytes))) return '';
    return `${(Number(bytes) / 1024 / 1024).toFixed(2)} MB`;
}

export default function BugReportShow({ report }) {
    return (
        <AppShell>
            <Head title={`Bug report #${report.id}`} />
            <div className="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8 lg:py-10">
                <Link className="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-wide text-ink-muted transition-colors hover:text-rx-blue" href={appPath('/dashboard')}>
                    <Icon className="h-4 w-4 rotate-180" name="arrow" /> Back to dashboard
                </Link>

                <header className="mt-6 border-b border-line pb-6">
                    <div className="flex flex-wrap items-center gap-3">
                        <p className="rx-kicker">Bug report #{report.id}</p>
                        <span className={`rounded-full px-3 py-1 text-xs font-semibold uppercase ${report.is_resolved ? 'bg-rx-yellow/15 text-rx-yellow' : 'bg-rx-blue/15 text-rx-blue'}`}>
                            {report.is_resolved ? 'Resolved' : 'Open'}
                        </span>
                    </div>
                    <h1 className="mt-3 break-words font-heading text-3xl font-bold uppercase tracking-tight sm:text-5xl">{report.title}</h1>
                    <div className="mt-4 flex flex-wrap gap-x-6 gap-y-2 text-sm text-ink-muted">
                        <span>Reported by {report.reporter_email}</span>
                        <span>{String(report.location || '').toUpperCase()}</span>
                        <span>{report.created_at ? new Date(report.created_at).toLocaleString() : ''}</span>
                    </div>
                </header>

                <div className="mt-8 grid gap-6 lg:grid-cols-[minmax(0,1fr)_18rem]">
                    <section className="rx-panel min-w-0 p-6">
                        <h2 className="font-heading text-sm font-semibold uppercase tracking-wide">Report details</h2>
                        <div
                            className="mt-5 break-words text-sm leading-7 text-ink [&_a]:text-rx-blue [&_a]:underline [&_blockquote]:border-l-4 [&_blockquote]:border-rx-blue [&_blockquote]:pl-4 [&_h2]:text-xl [&_h2]:font-semibold [&_h3]:text-lg [&_h3]:font-semibold [&_img]:max-w-full [&_ol]:list-decimal [&_ol]:pl-6 [&_p]:mb-3 [&_ul]:list-disc [&_ul]:pl-6"
                            dangerouslySetInnerHTML={{ __html: report.description }}
                        />
                    </section>
                    <aside className="rx-panel h-fit p-5">
                        <p className="rx-kicker">Reported page</p>
                        {report.page_url ? (
                            <a className="mt-3 block break-all text-sm text-rx-blue underline" href={report.page_url} rel="noreferrer" target="_blank">{report.page_url}</a>
                        ) : <p className="mt-3 text-sm text-ink-muted">No page URL was supplied.</p>}
                    </aside>
                </div>

                <section className="mt-6 rx-panel p-6">
                    <h2 className="font-heading text-sm font-semibold uppercase tracking-wide">Screenshots</h2>
                    {report.attachments.length === 0 ? (
                        <p className="mt-4 text-sm text-ink-muted">No screenshots were attached.</p>
                    ) : (
                        <div className="mt-5 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
                            {report.attachments.map((attachment) => (
                                <a className="group overflow-hidden rounded-lg border border-line bg-canvas" href={attachment.url} key={attachment.id} rel="noreferrer" target="_blank">
                                    <img alt={attachment.name} className="aspect-video w-full object-contain transition-transform duration-200 group-hover:scale-[1.02]" src={attachment.url} />
                                    <div className="border-t border-line p-3">
                                        <p className="truncate text-sm font-semibold">{attachment.name}</p>
                                        <p className="mt-1 text-xs text-ink-muted">{formatFileSize(attachment.size)}</p>
                                    </div>
                                </a>
                            ))}
                        </div>
                    )}
                </section>
            </div>
        </AppShell>
    );
}
