import axios from 'axios';
import { useCallback, useEffect, useState } from 'react';
import { translateError } from '../lib/errorTranslator';
import SelectControl from './SelectControl';

export default function VoteWorkspace() {
    const [data, setData] = useState(null);
    const [date, setDate] = useState('');
    const [loading, setLoading] = useState(true);
    const [savingId, setSavingId] = useState(null);
    const [message, setMessage] = useState('');

    const load = useCallback(async () => {
        setLoading(true);
        setMessage('');
        try {
            const response = await axios.get('/api/charts/workspace/votes', {
                params: { date: date || undefined },
                silent: true,
            });
            const next = response.data.data;
            setData(next);
            if (!date) setDate(next.selected_date);
        } catch (error) {
            setMessage(translateError(error).message);
        } finally {
            setLoading(false);
        }
    }, [date]);

    useEffect(() => { load(); }, [load]);

    const increment = async (record, channel) => {
        setSavingId(`${record.id}:${channel}`);
        setMessage('');
        try {
            await axios.post(`/api/charts/workspace/votes/${record.id}/increment`, { channel }, { silent: true });
            setMessage(`${channel === 'phone' ? 'Phone' : 'Social'} vote added to ${record.song}.`);
            await load();
        } catch (error) {
            setMessage(translateError(error).message);
        } finally {
            setSavingId(null);
        }
    };

    return (
        <div className="space-y-5">
            <section className="rx-panel flex flex-col justify-between gap-4 p-5 sm:flex-row sm:items-end">
                <div>
                    <p className="rx-kicker">Station chart voting</p>
                    <h2 className="mt-1 font-heading text-2xl font-bold uppercase">{data?.station_chart_name ?? "Station's Chart"} Votes</h2>
                </div>
                <label className="text-xs font-semibold uppercase text-ink-muted">Chart date
                    <SelectControl className="mt-1 min-w-48" onChange={setDate} options={(data?.dates ?? []).map((availableDate) => ({ label: availableDate, value: availableDate }))} value={date} />
                </label>
            </section>

            {message && <p className="border-l-4 border-rx-blue bg-surface px-4 py-3 text-sm text-ink">{message}</p>}

            <section className="overflow-hidden rounded-xl border border-line bg-surface">
                {loading ? <p className="p-12 text-center text-sm text-ink-muted">Loading chart votes...</p> : (data?.records ?? []).length === 0 ? (
                    <p className="p-12 text-center text-sm text-ink-muted">No chart entries were found for this date.</p>
                ) : (
                    <div className="overflow-x-auto">
                        <table className="min-w-full text-left text-sm">
                            <thead className="bg-surface-muted/70 text-xs uppercase text-ink-muted"><tr><th className="px-4 py-3">Position</th><th className="px-4 py-3">Song</th><th className="px-4 py-3 text-right">Online</th><th className="px-4 py-3 text-right">Phone</th><th className="px-4 py-3 text-right">Social</th><th className="px-4 py-3 text-right">Total</th><th className="px-4 py-3">Last voted</th><th className="px-4 py-3 text-right">Manual votes</th></tr></thead>
                            <tbody className="divide-y divide-line/70">{data.records.map((record) => <tr className="hover:bg-surface-muted/50" key={record.id}>
                                <td className="px-4 py-3 font-heading text-lg font-bold text-rx-blue">#{record.position}</td>
                                <td className="max-w-sm whitespace-normal break-words px-4 py-3"><p className="font-semibold">{record.song}</p><p className="text-xs text-ink-muted">{record.artist || 'Artist not assigned'}</p></td>
                                <td className="px-4 py-3 text-right">{record.online_votes}</td><td className="px-4 py-3 text-right">{record.phone_votes}</td><td className="px-4 py-3 text-right">{record.social_votes}</td><td className="px-4 py-3 text-right font-bold">{record.total_votes}</td>
                                <td className="px-4 py-3 text-ink-muted">{record.voted_at || 'No votes yet'}</td>
                                <td className="px-4 py-3"><div className="flex justify-end gap-2"><button className="rx-button-secondary px-3 py-2 text-xs" disabled={!data.can_write || savingId !== null} onClick={() => increment(record, 'phone')} type="button">{savingId === `${record.id}:phone` ? 'Adding...' : '+ Phone'}</button><button className="rx-button-secondary px-3 py-2 text-xs" disabled={!data.can_write || savingId !== null} onClick={() => increment(record, 'social')} type="button">{savingId === `${record.id}:social` ? 'Adding...' : '+ Social'}</button></div></td>
                            </tr>)}</tbody>
                        </table>
                    </div>
                )}
            </section>
        </div>
    );
}
