import { router } from '@inertiajs/react';
import { useEffect, useMemo, useRef, useState } from 'react';
import Icon from './Icon';

function flattenNavigation(navigation) {
    return navigation.flatMap((section) => section.groups.flatMap((group) => (
        group.items.map((item) => ({
            ...item,
            group: group.label,
            href: `/workspace/${section.slug}/${item.slug}`,
            icon: section.icon,
            section: section.label,
        }))
    )));
}

export default function CommandPalette({ navigation, open, onClose }) {
    const inputRef = useRef(null);
    const [query, setQuery] = useState('');
    const destinations = useMemo(() => flattenNavigation(navigation), [navigation]);
    const results = useMemo(() => {
        const normalizedQuery = query.trim().toLowerCase();

        if (!normalizedQuery) {
            return destinations.slice(0, 8);
        }

        return destinations.filter((destination) => (
            [destination.label, destination.section, destination.group, destination.description]
                .join(' ')
                .toLowerCase()
                .includes(normalizedQuery)
        )).slice(0, 10);
    }, [destinations, query]);

    useEffect(() => {
        if (!open) {
            setQuery('');
            return undefined;
        }

        const previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        window.requestAnimationFrame(() => inputRef.current?.focus());

        const handleKeyDown = (event) => {
            if (event.key === 'Escape') {
                onClose();
            }
        };

        document.addEventListener('keydown', handleKeyDown);

        return () => {
            document.body.style.overflow = previousOverflow;
            document.removeEventListener('keydown', handleKeyDown);
        };
    }, [onClose, open]);

    if (!open) {
        return null;
    }

    const visit = (href) => {
        onClose();
        router.visit(href);
    };

    return (
        <div aria-label="Search CMS tools" aria-modal="true" className="fixed inset-0 z-[90] flex items-start justify-center bg-black/70 px-4 pt-[10vh] backdrop-blur-sm" role="dialog">
            <button aria-label="Close CMS search" className="absolute inset-0 cursor-default" onClick={onClose} type="button" />
            <section className="rx-page relative z-10 w-full max-w-2xl overflow-hidden rounded-xl bg-surface shadow-2xl shadow-black/30">
                <div className="flex items-center gap-3 border-b border-line/60 px-4 sm:px-5">
                    <Icon className="h-5 w-5 shrink-0 text-rx-blue" name="search" />
                    <input
                        className="min-h-16 w-full border-0 bg-transparent px-0 text-base text-ink placeholder:text-ink-muted focus:ring-0"
                        onChange={(event) => setQuery(event.target.value)}
                        onKeyDown={(event) => {
                            if (event.key === 'Enter' && results[0]) {
                                visit(results[0].href);
                            }
                        }}
                        placeholder="Search tools, sections, and records..."
                        ref={inputRef}
                        type="search"
                        value={query}
                    />
                    <kbd className="hidden rounded-md bg-canvas px-2 py-1 text-[0.65rem] font-semibold uppercase tracking-wide text-ink-muted sm:block">Esc</kbd>
                </div>

                <div className="max-h-[60vh] overflow-y-auto p-2">
                    {results.length > 0 ? results.map((destination) => (
                        <button
                            className="group flex w-full items-start gap-4 rounded-lg px-3 py-3 text-left transition-colors duration-200 hover:bg-surface-muted focus-visible:bg-surface-muted"
                            key={destination.href}
                            onClick={() => visit(destination.href)}
                            type="button"
                        >
                            <span className="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-canvas text-rx-blue transition-colors group-hover:bg-rx-blue group-hover:text-neutral-950">
                                <Icon className="h-4 w-4" name={destination.icon} />
                            </span>
                            <span className="min-w-0 flex-1">
                                <span className="block font-heading text-sm font-semibold">{destination.label}</span>
                                <span className="mt-0.5 block text-xs text-ink-muted">{destination.section} / {destination.group}</span>
                            </span>
                            <Icon className="mt-2 h-4 w-4 text-ink-muted transition-transform group-hover:translate-x-1" name="arrow" />
                        </button>
                    )) : (
                        <div className="px-5 py-12 text-center">
                            <p className="font-heading text-sm font-semibold">No matching CMS tools</p>
                            <p className="mt-1 text-sm text-ink-muted">Try a module name such as articles, charts, shows, or reports.</p>
                        </div>
                    )}
                </div>

                <footer className="flex items-center justify-between border-t border-line/60 bg-canvas/60 px-5 py-3 text-xs text-ink-muted">
                    <span>{destinations.length} tools indexed</span>
                    <span>Enter to open first result</span>
                </footer>
            </section>
        </div>
    );
}
