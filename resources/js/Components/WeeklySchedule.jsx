import axios from 'axios';
import { useEffect, useMemo, useState } from 'react';
import { translateError } from '../lib/errorTranslator';
import SelectControl from './SelectControl';

const days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

function timeValue(value) {
    return String(value ?? '').slice(0, 5);
}

function ScheduleEntry({ canWrite, endpoint, onEdit, onSaved, record, showOptions }) {
    const [editing, setEditing] = useState(false);
    const [saving, setSaving] = useState(false);
    const [message, setMessage] = useState('');
    const [values, setValues] = useState({
        show_id: record.show_id ?? '',
        start: timeValue(record.start),
        end: timeValue(record.end),
    });

    useEffect(() => setValues({ show_id: record.show_id ?? '', start: timeValue(record.start), end: timeValue(record.end) }), [record]);

    const save = async () => {
        setSaving(true); setMessage('');
        try {
            await axios.put(`${endpoint}/${record.id}`, { ...values, day: record.day });
            setEditing(false); onSaved();
        } catch (error) {
            setMessage(translateError(error).message);
        } finally {
            setSaving(false);
        }
    };

    return <article className="rounded-md border border-line bg-surface p-3 text-sm">
        {editing ? <div className="space-y-2">
            <SelectControl
                onChange={(showId) => setValues((current) => ({ ...current, show_id: showId }))}
                options={[{ value: '', label: 'Jocks only / no show' }, ...showOptions]}
                value={values.show_id}
            />
            <div className="grid grid-cols-2 gap-2"><input className="rounded-md border-line bg-canvas text-sm" onChange={(event) => setValues((current) => ({ ...current, start: event.target.value }))} type="time" value={values.start} /><input className="rounded-md border-line bg-canvas text-sm" onChange={(event) => setValues((current) => ({ ...current, end: event.target.value }))} type="time" value={values.end} /></div>
            {message && <p className="text-xs text-red-500">{message}</p>}
            <div className="flex gap-2"><button className="rx-button px-3 py-2 text-xs" disabled={saving} onClick={save} type="button">{saving ? 'Saving...' : 'Save'}</button><button className="rx-button-secondary px-3 py-2 text-xs" onClick={() => setEditing(false)} type="button">Cancel</button></div>
        </div> : <>
            <p className="font-heading font-semibold">{record._schedule?.show || 'Jocks only'}</p>
            <p className="mt-1 text-xs text-ink-muted">{timeValue(record.start)}-{timeValue(record.end)}</p>
            {(record._schedule?.jocks ?? []).length > 0 && <p className="mt-2 text-xs text-rx-blue">{record._schedule.jocks.join(', ')}</p>}
            {canWrite && <div className="mt-3 flex gap-2"><button className="text-xs font-semibold uppercase text-rx-blue" onClick={() => setEditing(true)} type="button">Quick edit</button><button className="text-xs font-semibold uppercase text-ink-muted" onClick={() => onEdit(record)} type="button">Jocks</button></div>}
        </>}
    </article>;
}

export default function WeeklySchedule({ canWrite, endpoint, fields, loading, onEdit, onSaved, records }) {
    const showOptions = fields.find((field) => field.name === 'show_id')?.options ?? [];
    const grouped = useMemo(() => Object.fromEntries(days.map((day) => [day, records.filter((record) => record.day === day).sort((a, b) => String(a.start).localeCompare(String(b.start)))])), [records]);

    if (loading) return <div className="rounded-xl border border-line bg-surface p-12 text-center text-sm text-ink-muted">Loading weekly schedule...</div>;

    return <div className="grid gap-3 md:grid-cols-2 xl:grid-cols-7">
        {days.map((day) => <section className="min-w-0 rounded-xl border border-line bg-canvas/30 p-3" key={day}>
            <h3 className="mb-3 font-heading text-xs font-semibold uppercase tracking-wide text-ink">{day}</h3>
            <div className="space-y-2">{grouped[day].length === 0 ? <p className="rounded-md border border-dashed border-line p-4 text-center text-xs text-ink-muted">No schedule</p> : grouped[day].map((record) => <ScheduleEntry canWrite={canWrite} endpoint={endpoint} key={record.id} onEdit={onEdit} onSaved={onSaved} record={record} showOptions={showOptions} />)}</div>
        </section>)}
    </div>;
}
