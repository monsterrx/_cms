import { useEffect, useId, useMemo, useRef, useState } from 'react';
import { createPortal } from 'react-dom';
import Icon from './Icon';

export default function CardDropdown({
    ariaDescribedBy,
    ariaInvalid = false,
    ariaRequired = false,
    buttonClassName = '',
    disabled = false,
    id,
    onBlur,
    onChange,
    onFocus,
    onOpenChange,
    options,
    placeholder = '',
    value,
}) {
    const generatedId = useId();
    const menuId = `${id || generatedId}-choices`;
    const buttonRef = useRef(null);
    const menuRef = useRef(null);
    const [open, setOpen] = useState(false);
    const [query, setQuery] = useState('');
    const [position, setPosition] = useState({ left: 0, top: 0, width: 0 });
    const selected = options.find((option) => String(option.value) === String(value));
    const searchable = options.length > 8;
    const visibleOptions = useMemo(() => {
        const normalized = query.trim().toLowerCase();
        const filtered = normalized
            ? options.filter((option) => option.label.toLowerCase().includes(normalized))
            : options;

        return filtered.slice(0, 100);
    }, [options, query]);

    const setOpenState = (nextOpen) => {
        setOpen(nextOpen);
        onOpenChange?.(nextOpen);

        if (!nextOpen) {
            setQuery('');
        }
    };

    useEffect(() => {
        if (!open) {
            return undefined;
        }

        const updatePosition = () => {
            const rect = buttonRef.current?.getBoundingClientRect();

            if (!rect) {
                return;
            }

            const estimatedHeight = Math.min(288, 16 + (searchable ? 56 : 0) + (Math.min(options.length, 6) * 44));
            const spaceBelow = window.innerHeight - rect.bottom;
            const placeAbove = spaceBelow < estimatedHeight && rect.top > spaceBelow;
            const top = placeAbove
                ? Math.max(8, rect.top - estimatedHeight - 8)
                : Math.min(window.innerHeight - estimatedHeight - 8, rect.bottom + 8);

            setPosition({
                left: Math.max(8, Math.min(rect.left, window.innerWidth - rect.width - 8)),
                top: Math.max(8, top),
                width: rect.width,
            });
        };
        const handlePointerDown = (event) => {
            if (
                !buttonRef.current?.contains(event.target)
                && !menuRef.current?.contains(event.target)
            ) {
                setOpenState(false);
            }
        };
        const handleKeyDown = (event) => {
            if (event.key === 'Escape') {
                setOpenState(false);
                buttonRef.current?.focus();
            }
        };

        updatePosition();
        document.addEventListener('pointerdown', handlePointerDown);
        document.addEventListener('keydown', handleKeyDown);
        window.addEventListener('resize', updatePosition);
        window.addEventListener('scroll', updatePosition, true);

        return () => {
            document.removeEventListener('pointerdown', handlePointerDown);
            document.removeEventListener('keydown', handleKeyDown);
            window.removeEventListener('resize', updatePosition);
            window.removeEventListener('scroll', updatePosition, true);
        };
    }, [open, options.length, searchable]);

    const choose = (option) => {
        onChange(option.value);
        setOpenState(false);
        buttonRef.current?.focus();
    };

    return (
        <>
            <button
                aria-controls={open ? menuId : undefined}
                aria-describedby={ariaDescribedBy}
                aria-expanded={open}
                aria-haspopup="listbox"
                aria-invalid={ariaInvalid}
                aria-required={ariaRequired}
                className={`${buttonClassName} ${open ? 'border-rx-blue bg-rx-blue/10' : ''}`}
                disabled={disabled}
                id={id}
                onBlur={(event) => {
                    if (!open) {
                        onBlur?.(event);
                    }
                }}
                onClick={() => setOpenState(!open)}
                onFocus={onFocus}
                ref={buttonRef}
                type="button"
            >
                <span className="block truncate">{selected?.label || placeholder}</span>
                <Icon className={`absolute right-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted transition-transform duration-200 ${open ? 'rotate-180 text-rx-blue' : ''}`} name="chevron" />
            </button>

            {open && createPortal(
                <div
                    className="fixed z-[120] overflow-hidden rounded-md border-2 border-line bg-surface text-ink"
                    id={menuId}
                    ref={menuRef}
                    style={position}
                >
                    {searchable && (
                        <div className="border-b border-line p-2">
                            <div className="relative">
                                <Icon className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-ink-muted" name="search" />
                                <input
                                    aria-label="Filter choices"
                                    autoFocus
                                    className="h-10 w-full rounded-sm border border-line bg-canvas pl-9 pr-3 text-sm text-ink shadow-none focus:border-rx-blue focus:bg-rx-blue/10 focus:ring-0"
                                    onChange={(event) => setQuery(event.target.value)}
                                    placeholder="Filter choices"
                                    type="search"
                                    value={query}
                                />
                            </div>
                        </div>
                    )}

                    <div className="max-h-56 overflow-y-auto p-1.5" role="listbox">
                        {visibleOptions.map((option) => {
                            const active = String(option.value) === String(value);

                            return (
                                <button
                                    aria-selected={active}
                                    className={`flex min-h-10 w-full items-center justify-between rounded-sm px-3 py-2 text-left text-sm transition-colors ${
                                        active
                                            ? 'bg-rx-blue text-neutral-950'
                                            : 'hover:bg-rx-blue/10 focus:bg-rx-blue/10 focus:outline-none'
                                    }`}
                                    key={String(option.value)}
                                    onClick={() => choose(option)}
                                    role="option"
                                    type="button"
                                >
                                    <span className="truncate">{option.label}</span>
                                    {active && <Icon className="h-4 w-4" name="check" />}
                                </button>
                            );
                        })}

                        {visibleOptions.length === 0 && (
                            <p className="px-3 py-5 text-center text-sm text-ink-muted">No matching choices.</p>
                        )}
                    </div>
                </div>,
                document.body,
            )}
        </>
    );
}
