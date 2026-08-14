import axios from 'axios';
import { useCallback, useEffect, useMemo, useState } from 'react';
import { translateError } from '../lib/errorTranslator';
import SelectControl from './SelectControl';

const moduleBySlug = {
    'station-chart': 'station',
    'daily-survey-top-5': 'daily',
    dropouts: 'dropouts',
};

function ChartRow({ canWrite, index, onDelete, onDragEnd, onDragOver, onDragStart, onDrop, record }) {
    return (
        <article
            className="grid items-center gap-3 border-b border-line bg-surface px-4 py-3 transition-colors last:border-b-0 hover:bg-canvas/50 sm:grid-cols-[2.5rem_5rem_minmax(0,1fr)_7rem_2.5rem]"
            draggable={canWrite}
            onDragEnd={onDragEnd}
            onDragOver={onDragOver}
            onDragStart={onDragStart}
            onDrop={onDrop}
        >
            <span aria-label="Drag vertically" className={`select-none text-xl text-ink-muted ${canWrite ? 'cursor-grab active:cursor-grabbing' : ''}`}>⋮⋮</span>
            <span className="font-heading text-2xl font-bold text-rx-blue">#{index + 1}</span>
            <div className="min-w-0">
                <p className="truncate font-heading font-semibold text-ink">{record.song}</p>
                <p className="truncate text-sm text-ink-muted">{record.artist || 'Artist not assigned'}</p>
            </div>
            <span className={`w-fit rounded-full px-3 py-1 text-xs font-semibold uppercase ${Number(record.is_posted) === 1 ? 'bg-rx-yellow/20 text-rx-yellow' : 'bg-rx-blue/15 text-rx-blue'}`}>
                {Number(record.is_posted) === 1 ? 'Posted' : 'Draft'}
            </span>
            {canWrite ? <button aria-label={`Remove ${record.song}`} className="text-xl text-red-500 transition-transform hover:scale-110" onClick={onDelete} type="button">×</button> : <span />}
        </article>
    );
}

export default function ChartWorkspace({ item }) {
    const module = moduleBySlug[item.slug];
    const readOnly = module === 'dropouts';
    const [data, setData] = useState(null);
    const [loading, setLoading] = useState(true);
    const [message, setMessage] = useState('');
    const [type, setType] = useState('');
    const [date, setDate] = useState('');
    const [status, setStatus] = useState(readOnly ? 'all' : 'draft');
    const [songId, setSongId] = useState('');
    const [publishAt, setPublishAt] = useState('');
    const [customDate, setCustomDate] = useState(false);
    const [draggedId, setDraggedId] = useState(null);
    const [records, setRecords] = useState([]);

    const load = useCallback(async () => {
        if (customDate && !date) {
            setLoading(false);
            setRecords([]);
            return;
        }

        setLoading(true);
        setMessage('');
        try {
            const response = await axios.get('/api/charts/workspace', {
                params: { module, type: type || undefined, date: date || undefined, status },
                silent: true,
            });
            const next = response.data.data;
            setData(next);
            setRecords(next.records ?? []);
            if (!type) setType(next.selected_type);
            if (!date) setDate(next.selected_date);
        } catch (error) {
            setMessage(translateError(error).message);
        } finally {
            setLoading(false);
        }
    }, [customDate, date, module, status, type]);

    useEffect(() => { load(); }, [load]);

    const request = async (method, url, payload, successMessage) => {
        setMessage('');
        try {
            await axios({ method, url, data: payload, silent: true });
            setMessage(successMessage);
            await load();
            return true;
        } catch (error) {
            setMessage(translateError(error).message);
            return false;
        }
    };

    const context = { module, type, date };
    const songOptions = useMemo(() => data?.songs ?? [], [data]);

    const addSong = async () => {
        if (!songId) {
            setMessage('Select a song before adding it to the draft.');
            return;
        }
        if (await request('post', '/api/charts/workspace/entries', { ...context, song_id: songId }, 'Song added to the draft.')) setSongId('');
    };

    const dropOn = async (targetId) => {
        if (!draggedId || draggedId === targetId) return;
        const reordered = [...records];
        const from = reordered.findIndex((record) => record.id === draggedId);
        const to = reordered.findIndex((record) => record.id === targetId);
        const [moved] = reordered.splice(from, 1);
        reordered.splice(to, 0, moved);
        setRecords(reordered);
        setDraggedId(null);
        await request('put', '/api/charts/workspace/order', { ...context, ids: reordered.map((record) => record.id) }, 'Chart order saved.');
    };

    const canWrite = Boolean(data?.can_write) && !readOnly;
    const editingDraft = canWrite && status === 'draft';
    const stationChart = module === 'station';

    return (
        <div className="space-y-5">
            <section className="rx-panel p-5">
                <div className="flex flex-col justify-between gap-4 lg:flex-row lg:items-end">
                    <div>
                        <p className="rx-kicker">{stationChart ? "Station's Chart" : (data?.station_chart_name ?? 'Station chart')}</p>
                        <h2 className="mt-1 font-heading text-2xl font-bold uppercase">{stationChart ? (data?.station_chart_name ?? "Station's Chart") : item.label}</h2>
                    </div>
                    <div className={`grid gap-3 ${stationChart ? 'sm:grid-cols-2' : 'sm:grid-cols-3'}`}>
                        {!stationChart && <label className="text-xs font-semibold uppercase text-ink-muted">Chart type
                            <SelectControl className="mt-1" onChange={(selectedType) => { setCustomDate(false); setDate(''); setType(selectedType); }} options={data?.types ?? []} value={type} />
                        </label>}
                        <label className="text-xs font-semibold uppercase text-ink-muted">Chart date
                            {customDate ? <span className="mt-1 flex gap-2">
                                <input className="w-full rounded-md border-line bg-canvas text-sm focus:border-rx-blue focus:ring-rx-blue" onChange={(event) => setDate(event.target.value)} type="date" value={date} />
                                <button className="rx-button-secondary px-3 text-xs" onClick={() => { setCustomDate(false); setDate(''); }} type="button">Cancel</button>
                            </span> : <span className="mt-1 flex gap-2">
                                <SelectControl onChange={setDate} options={(data?.dates ?? []).map((availableDate) => ({ label: availableDate, value: availableDate }))} value={date} />
                                {canWrite && status === 'draft' && <button className="rx-button-secondary whitespace-nowrap px-3 text-xs" onClick={() => { setCustomDate(true); setDate(''); }} type="button">New date</button>}
                            </span>}
                        </label>
                        <label className="text-xs font-semibold uppercase text-ink-muted">Status
                            <SelectControl
                                className="mt-1"
                                onChange={(selectedStatus) => { setCustomDate(false); setDate(''); setStatus(selectedStatus); }}
                                options={[
                                    ...(!readOnly ? [{ value: 'draft', label: 'Draft' }, { value: 'posted', label: 'Posted' }] : []),
                                    { value: 'all', label: 'All' },
                                ]}
                                value={status}
                            />
                        </label>
                    </div>
                </div>
            </section>

            {editingDraft && date && <section className="rx-panel grid gap-4 p-5 lg:grid-cols-[auto_minmax(16rem,1fr)_auto] lg:items-end">
                <button className="rx-button" disabled={records.length === 0} onClick={() => request('post', '/api/charts/workspace/publish', context, 'The complete chart was published.')} type="button">Publish chart now</button>
                <label className="text-xs font-semibold uppercase text-ink-muted">Scheduled release date and time
                    <input className="mt-1 w-full rounded-md border-line bg-canvas text-sm focus:border-rx-blue focus:ring-rx-blue" min={new Date(Date.now() - (new Date().getTimezoneOffset() * 60000)).toISOString().slice(0, 16)} onChange={(event) => setPublishAt(event.target.value)} type="datetime-local" value={publishAt} />
                    <span className="mt-1 block text-[0.65rem] normal-case leading-4 text-ink-muted">Philippine time. The complete draft publishes automatically.</span>
                </label>
                <button className="rx-button-secondary" disabled={!publishAt || records.length === 0} onClick={() => request('post', '/api/charts/workspace/schedules', { ...context, publish_at: publishAt }, 'Chart release scheduled.')} type="button">Schedule</button>
            </section>}

            {editingDraft && <section className="rx-panel grid gap-3 p-5 lg:grid-cols-[minmax(0,1fr)_auto]">
                <label className="text-xs font-semibold uppercase text-ink-muted">Add song
                    <SelectControl
                        className="mt-1"
                        onChange={setSongId}
                        options={songOptions.map((song) => ({ value: song.id, label: `${song.name}${song.artist ? ` — ${song.artist}` : ''}` }))}
                        placeholder="Select a song"
                        value={songId}
                    />
                </label>
                <button className="rx-button self-end" onClick={addSong} type="button">Add to draft</button>
            </section>}

            {message && <p className="border-l-4 border-rx-blue bg-surface px-4 py-3 text-sm text-ink">{message}</p>}

            <section className="overflow-hidden rounded-xl border border-line bg-surface">
                {loading ? <p className="p-12 text-center text-sm text-ink-muted">Loading chart entries...</p> : records.length === 0 ? <p className="p-12 text-center text-sm text-ink-muted">No entries were found for this chart date and status.</p> : records.map((record, index) => (
                    <ChartRow
                        canWrite={editingDraft}
                        index={index}
                        key={record.id}
                        onDelete={() => request('delete', `/api/charts/workspace/entries/${record.id}`, null, 'Chart entry removed.')}
                        onDragEnd={() => setDraggedId(null)}
                        onDragOver={(event) => event.preventDefault()}
                        onDragStart={() => setDraggedId(record.id)}
                        onDrop={() => dropOn(record.id)}
                        record={record}
                    />
                ))}
            </section>

            {(data?.schedules ?? []).length > 0 && <section className="rx-panel p-5">
                <h3 className="font-heading text-sm font-semibold uppercase">Publication history</h3>
                <div className="mt-3 space-y-2">{data.schedules.map((schedule) => <p className="text-sm text-ink-muted" key={schedule.id}>{new Date(schedule.publish_at).toLocaleString()} — <span className="uppercase text-ink">{schedule.status}</span>{schedule.failure_message ? `: ${schedule.failure_message}` : ''}</p>)}</div>
            </section>}
        </div>
    );
}
