import { useEffect, useMemo, useState } from 'react';

function FilterButton({ active, children, onClick }) {
    return (
        <button
            className={`rounded-md px-3 py-2 text-xs font-semibold uppercase tracking-wide transition-colors ${active ? 'bg-rx-blue text-neutral-950' : 'bg-surface text-ink-muted hover:text-ink'}`}
            onClick={onClick}
            type="button"
        >
            {children}
        </button>
    );
}

function CardImage({ display }) {
    const useFallback = (event) => {
        if (!display.fallback_image || event.currentTarget.dataset.fallbackApplied === 'true') {
            return;
        }

        event.currentTarget.dataset.fallbackApplied = 'true';
        event.currentTarget.src = display.fallback_image;
    };

    return (
        <div className={`relative overflow-hidden bg-canvas ${display.aspect === 'square' ? 'aspect-square' : 'aspect-[4/3]'}`}>
            {display.secondary_image ? (
                <div className="grid h-full grid-cols-[38%_62%]">
                    <img
                        alt=""
                        className="h-full w-full object-cover"
                        onError={useFallback}
                        src={display.image}
                    />
                    <img
                        alt=""
                        className="h-full w-full object-cover"
                        onError={useFallback}
                        src={display.secondary_image}
                    />
                </div>
            ) : display.image ? (
                <img
                    alt=""
                    className="h-full w-full object-cover transition-transform duration-300 group-hover:scale-[1.03]"
                    onError={useFallback}
                    src={display.image}
                />
            ) : (
                <div className="flex h-full items-center justify-center bg-gradient-to-br from-rx-blue/15 to-rx-yellow/10 font-heading text-3xl font-bold text-ink-muted">
                    {String(display.title || '?').slice(0, 1).toUpperCase()}
                </div>
            )}
            {display.status && (
                <span className="absolute right-3 top-3 rounded-md bg-neutral-950/80 px-2.5 py-1 text-[0.65rem] font-semibold uppercase tracking-wide text-white backdrop-blur">
                    {display.status}
                </span>
            )}
        </div>
    );
}

export default function ResourceCardGrid({
    canWrite,
    loading,
    meta,
    onAction,
    onDelete,
    onEdit,
    onOrderSave,
    onPageChange,
    presentation,
    records,
}) {
    const [filter, setFilter] = useState('all');
    const [reordering, setReordering] = useState(false);
    const [ordered, setOrdered] = useState(records);
    const [draggedId, setDraggedId] = useState(null);

    useEffect(() => setOrdered(records), [records]);
    useEffect(() => setFilter('all'), [presentation]);

    const visibleRecords = useMemo(() => {
        const source = presentation === 'sortable-graphic-cards' ? ordered : records;
        if (filter === 'all') {
            return source;
        }
        return source.filter((record) => (
            record._display?.kind === filter || record._display?.active_kind === filter
        ));
    }, [filter, ordered, presentation, records]);

    const filters = presentation === 'award-cards'
        ? [['all', 'All'], ['jock', 'Jocks'], ['show', 'Shows']]
        : (presentation === 'wallpaper-cards'
            ? [['all', 'All'], ['web', 'Desktop'], ['mobile', 'Mobile']]
            : (presentation === 'show-cards'
                ? [['all', 'All'], ['daily', 'Daily'], ['special', 'Special'], ['active', 'Active'], ['inactive', 'Inactive']]
                : []));

    const moveBefore = (targetId) => {
        if (!draggedId || draggedId === targetId) {
            return;
        }

        setOrdered((current) => {
            const next = [...current];
            const from = next.findIndex((record) => record.id === draggedId);
            const to = next.findIndex((record) => record.id === targetId);
            const [moved] = next.splice(from, 1);
            next.splice(to, 0, moved);
            return next;
        });
    };

    if (loading) {
        return <div className="rounded-xl border border-line bg-surface px-6 py-16 text-center text-sm text-ink-muted">Loading cards...</div>;
    }

    return (
        <div className="rounded-xl border border-line bg-surface">
            {(filters.length > 0 || presentation === 'sortable-graphic-cards') && (
                <div className="flex flex-wrap items-center justify-between gap-3 border-b border-line p-4">
                    <div className="flex flex-wrap gap-2">
                        {filters.map(([value, label]) => (
                            <FilterButton active={filter === value} key={value} onClick={() => setFilter(value)}>{label}</FilterButton>
                        ))}
                    </div>
                    {presentation === 'sortable-graphic-cards' && canWrite && (
                        <div className="flex gap-2">
                            {reordering && (
                                <button
                                    className="rx-button-secondary"
                                    onClick={() => {
                                        setOrdered(records);
                                        setReordering(false);
                                    }}
                                    type="button"
                                >
                                    Cancel
                                </button>
                            )}
                            <button
                                className="rx-button"
                                onClick={async () => {
                                    if (reordering) {
                                        try {
                                            await onOrderSave(ordered.map((record) => record.id));
                                        } catch {
                                            return;
                                        }
                                    }
                                    setReordering((current) => !current);
                                }}
                                type="button"
                            >
                                {reordering ? 'Save order' : 'Reorder'}
                            </button>
                        </div>
                    )}
                </div>
            )}

            {visibleRecords.length === 0 ? (
                <div className="px-6 py-16 text-center text-sm text-ink-muted">No matching records found.</div>
            ) : (
                <div className="grid gap-4 p-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                    {visibleRecords.map((record) => {
                        const display = record._display ?? {};

                        return (
                            <article
                                className={`group overflow-hidden rounded-lg border border-line bg-canvas/30 transition-all duration-200 hover:-translate-y-0.5 hover:border-rx-blue/60 ${reordering ? 'cursor-grab active:cursor-grabbing' : ''}`}
                                draggable={reordering}
                                key={record.id}
                                onDragEnd={() => setDraggedId(null)}
                                onDragOver={(event) => {
                                    event.preventDefault();
                                    moveBefore(record.id);
                                }}
                                onDragStart={() => setDraggedId(record.id)}
                            >
                                <button className="block w-full text-left" disabled={reordering} onClick={() => onEdit(record)} type="button">
                                    <CardImage display={display} />
                                    <div className="p-4">
                                        <p className="truncate font-heading text-sm font-semibold uppercase tracking-wide text-ink">{display.title}</p>
                                        <p className="mt-1 truncate text-xs text-ink-muted">{display.subtitle || 'Open details'}</p>
                                    </div>
                                </button>
                                {canWrite && !reordering && (
                                    <div className="flex flex-wrap gap-2 border-t border-line px-4 py-3">
                                        <button className="text-xs font-semibold uppercase tracking-wide text-rx-blue" onClick={() => onEdit(record)} type="button">Edit</button>
                                        {presentation === 'article-cards' && (
                                            <button
                                                className="text-xs font-semibold uppercase tracking-wide text-ink-muted hover:text-rx-blue"
                                                onClick={() => onAction(record, record.published_at ? 'unpublish' : 'publish')}
                                                type="button"
                                            >
                                                {record.published_at ? 'Unpublish' : 'Publish'}
                                            </button>
                                        )}
                                        <button className="ml-auto text-xs font-semibold uppercase tracking-wide text-red-500" onClick={() => onDelete(record)} type="button">Delete</button>
                                    </div>
                                )}
                            </article>
                        );
                    })}
                </div>
            )}

            <div className="flex items-center justify-between gap-4 border-t border-line px-4 py-3 text-xs text-ink-muted">
                <span>{meta.from ?? 0}–{meta.to ?? 0} of {meta.total}</span>
                <div className="flex gap-2">
                    <button className="rx-button-secondary" disabled={meta.current_page <= 1} onClick={() => onPageChange(meta.current_page - 1)} type="button">Previous</button>
                    <button className="rx-button-secondary" disabled={meta.current_page >= meta.last_page} onClick={() => onPageChange(meta.current_page + 1)} type="button">Next</button>
                </div>
            </div>
        </div>
    );
}
