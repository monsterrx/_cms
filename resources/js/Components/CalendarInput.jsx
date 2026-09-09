import { useEffect, useRef, useState } from 'react';

function isoDate(date) {
    return `${date.getFullYear()}-${String(date.getMonth() + 1).padStart(2, '0')}-${String(date.getDate()).padStart(2, '0')}`;
}

export default function CalendarInput({ id, name, value, disabled, required, error, errorId, className, onChange, onFocus, onBlur }) {
    const root = useRef(null);
    const input = useRef(null);
    const [open, setOpen] = useState(false);
    const [month, setMonth] = useState(() => new Date());
    const show = () => {
        if (disabled) return;
        const selected = /^\d{4}-\d{2}-\d{2}$/.test(value) ? new Date(`${value}T12:00:00`) : new Date();
        setMonth(Number.isNaN(selected.getTime()) ? new Date() : selected);
        setOpen(true);
    };
    useEffect(() => {
        const closeOutside = (event) => { if (!root.current?.contains(event.target)) setOpen(false); };
        document.addEventListener('pointerdown', closeOutside);
        return () => document.removeEventListener('pointerdown', closeOutside);
    }, []);
    const choose = (date) => { onChange(date); setOpen(false); input.current?.focus(); };
    const first = new Date(month.getFullYear(), month.getMonth(), 1);
    const days = new Date(month.getFullYear(), month.getMonth() + 1, 0).getDate();
    return (
        <div className="relative" ref={root} onKeyDown={(event) => {
            if (open && event.key === 'Escape') { event.preventDefault(); event.stopPropagation(); setOpen(false); input.current?.focus(); }
        }}>
            <input aria-describedby={error ? errorId : undefined} aria-invalid={Boolean(error)} aria-required={required}
                aria-expanded={open} aria-controls={`${id}-calendar`} className={`${className} pr-12 !placeholder:text-ink-muted`}
                disabled={disabled} id={id} name={name} ref={input} inputMode="numeric" maxLength={10}
                onBlur={onBlur} onClick={show} onFocus={onFocus} onChange={(event) => onChange(event.target.value)}
                onKeyDown={(event) => {
                    if (event.key === 'ArrowDown') { event.preventDefault(); show(); }
                    if (event.key === 'Enter' && open) { event.preventDefault(); event.stopPropagation(); setOpen(false); }
                }} placeholder="YYYY-MM-DD" type="text" value={value} />
            <button aria-label={`Choose ${name.replaceAll('_', ' ')}`} className="absolute right-3 top-1/2 -translate-y-1/2 text-ink-muted" disabled={disabled} onClick={() => open ? setOpen(false) : show()} type="button">
                <svg aria-hidden="true" className="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="1.5"><rect x="3" y="5" width="18" height="16" rx="2"/><path d="M16 3v4M8 3v4M3 11h18"/></svg>
            </button>
            {open && <div id={`${id}-calendar`} aria-label="Choose a date" className="absolute left-0 top-full z-30 mt-2 w-72 max-w-full rounded-lg border border-line bg-surface p-3 text-ink shadow-2xl">
                <div className="mb-2 flex items-center justify-between">
                    <button aria-label="Previous month" className="h-9 w-9 rounded hover:bg-canvas" onClick={() => setMonth(new Date(month.getFullYear(), month.getMonth() - 1, 1))} type="button">&lsaquo;</button>
                    <span aria-live="polite" className="text-sm font-semibold">{month.toLocaleDateString('en', { month: 'long', year: 'numeric' })}</span>
                    <button aria-label="Next month" className="h-9 w-9 rounded hover:bg-canvas" onClick={() => setMonth(new Date(month.getFullYear(), month.getMonth() + 1, 1))} type="button">&rsaquo;</button>
                </div>
                <div className="grid grid-cols-7 gap-1 text-center text-xs">
                    {['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'].map((day) => <span className="py-1 text-ink-muted" key={day}>{day}</span>)}
                    {Array.from({length: first.getDay()}, (_, index) => <span key={`empty-${index}`} />)}
                    {Array.from({length: days}, (_, index) => {
                        const day = isoDate(new Date(month.getFullYear(), month.getMonth(), index + 1));
                        return <button aria-label={day} aria-pressed={day === value} className={`h-8 rounded focus:ring-2 focus:ring-rx-blue ${day === value ? 'bg-rx-blue text-black' : 'hover:bg-canvas'}`} key={day} onClick={() => choose(day)} type="button">{index + 1}</button>;
                    })}
                </div>
                <div className="mt-2 flex justify-between border-t border-line pt-2 text-xs"><button onClick={() => choose('')} type="button">Clear</button><button onClick={() => choose(isoDate(new Date()))} type="button">Today</button></div>
            </div>}
        </div>
    );
}
